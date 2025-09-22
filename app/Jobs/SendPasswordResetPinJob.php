<?php

namespace App\Jobs;

use App\Mail\PasswordResetPinMail;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendPasswordResetPinJob implements ShouldQueue
{
    use Queueable, Dispatchable, InteractsWithQueue, SerializesModels;

    protected User $user;
    protected string $pin;

    /**
     * Crea una nueva instancia de job
     */
    public function __construct(User $user, string $pin)
    {
        $this->user = $user;
        $this->pin = $pin;

        $this->onQueue('emails');
    }

    /**
     * Ejecuta el job.
     */
    public function handle(): void
    {
        try {
            Mail::to()->send(new PasswordResetPinMail(
                $this->user,
                $this->pin
            ));

            Log::info('pin de restablecimiento enviado exitosamente', [
                'userId' => $this->user->id,
                'email' => $this->user->email,
            ]);

        } catch(\Exception $e) {
            Log::error("error al enviar el pin de restablecimiento", [
                'userId' => $this->user->id,
                'email' => $this->user->email,
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }

    /**
     * Manejar job en caso de error
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('Falló el envío de PIN de restablecimiento después de reintentos', [
            'user_id' => $this->user->id,
            'email' => $this->user->email,
            'error' => $exception->getMessage(),
            'attempts' => $this->attempts()
        ]);
    }

    /**
     * Determina el tiempo el cual el trabajo estara en timeout
     */
    public function retryUntil(): \DateTime
    {
        return now()->addMinutes(10);
    }

    /**
     * Calcula el numero de segundos para reintentar el job
     */
    public function backoff(): array
    {
        return [30, 60, 120]; // Reintento después de 30s, 1m, 2m
    }
}
