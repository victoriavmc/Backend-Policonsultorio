<?php

namespace App\Services;

use App\Jobs\SendPasswordResetPinJob;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthService
{

    /**
     * Intenta autenticar y crear un token para el usuario.
     *
     * @param array $data
     * @return array|null
     */
    public function login(array $data)
    {
        if (Auth::attempt(['email' => $data['email'], 'password' => $data['password']])) {
            $user = Auth::user();

            return [
                'email' => $user['email'],
                'token' => $user->createToken("api")->plainTextToken,
            ];
        }

        return null;
    }

    /**
     * Elimina el token actual para hacer logout.
     *
     * @param Request $request
     * @return bool
     */
    public function logout($request)
    {
        $user = $request->user();

        if ($user && $user->currentAccessToken()) {
            $user->currentAccessToken()->delete();
            return true;
        }

        return false;
    }

    /**
     * Solicita un pin de restablecimiento
     * 
     * @return array
     */
    public function requestPasswordResetPin(string $email)
    {
        $user = User::where('email', $email)->first();

        if (!$user) {
            return [
                'success' => true,
                'message' => 'Si el email es correcto, se le enviara un pin de restablecimiento al correo'
            ];
        }

        if (!$user->canRequestNewPin()) {
            throw new \Exception('Debes esperar 5 minutos antes de solicitar otro PIN.');
        }

        if ($user->isPinBlocked()) {
            $minutesRemaining = $user->getBlockedTimeRemaining();
            throw new \Exception("PIN bloqueado por intentos fallidos. Intenta en {$minutesRemaining} minutos.");
        }

        $plainPin = $user->generateForgotPin();

        SendPasswordResetPinJob::dispatch($user, $plainPin);

        return [
            'success' => true,
            'message' => 'Se envió un PIN a tu correo electrónico.',
            'expires_in_minutes' => 15
        ];
    }

    /**
     * Verifica PIN y permite cambiar contraseña
     */
    public function resetPasswordWithPin(
        string $email,
        string $pin,
        string $newPassword,
    ): array {

        $user = User::where('email', $email)->first();
        if (!$user) {
            throw new \Exception('Usuario no encontrado.');
        }

        if (!$user->verifyForgotPin($pin)) {

            if ($user->isPinBlocked()) {
                $minutesRemaining = $user->getBlockedTimeRemaining();
                throw new \Exception("PIN bloqueado. Intenta en {$minutesRemaining} minutos.");
            }

            $attemptsRemaining = 3 - $user->pin_attempts;
            throw new \Exception("PIN incorrecto. Te quedan {$attemptsRemaining} intentos.");
        }

        if (Hash::check($newPassword, $user->password)) {
            throw new \Exception('La nueva contraseña debe ser diferente a la actual.');
        }

        DB::transaction(function () use ($user, $newPassword) {
            $user->update([
                'password' => Hash::make($newPassword),
                'email_verified_at' => $user->email_verified_at ?? now(),
            ]);

            $user->clearForgotPin();
        });

        return [
            'success' => true,
            'message' => 'Contraseña restablecida exitosamente.'
        ];
    }

    /**
     * Verifica si un PIN es válido (sin usarlo)
     */
    public function verifyPin(string $email): array
    {
        $user = User::where('email', $email)->first();

        if (!$user) {
            return ['valid' => false, 'message' => 'Usuario no encontrado'];
        }

        if ($user->isPinBlocked()) {
            $minutes = $user->getBlockedTimeRemaining();
            return ['valid' => false, 'message' => "PIN bloqueado por {$minutes} minutos"];
        }

        if (!$user->pin_olvido_expires_at || $user->pin_olvido_expires_at->isPast()) {
            return ['valid' => false, 'message' => 'PIN expirado'];
        }

        if (!$user->pin_olvido) {
            return ['valid' => false, 'message' => 'No hay PIN activo'];
        }

        return [
            'valid' => true,
            'message' => 'PIN puede ser verificado',
            'expires_at' => $user->pin_olvido_expires_at->toISOString()
        ];
    }
}
