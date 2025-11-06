<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;


class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * La tabla asociada con el modelo.
     */
    protected $table = 'usuarios';
    protected $primaryKey = 'idUsuarios';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'email',
        'password',
        'pinOlvido',
        'pinOlvido_expires_at', // tiempo de caducidad del pin
        'pinOlvido_created_at', // cuando se creo el pin
        'pinOlvido_attempts', // intentos fallidos de Pin
        'pin_blocked_until', // tiempo de bloqueo de la solicitud del pin
        'datosPersonales_idDatosPersonales',
        'estado', // FALTAN ATRIBUTOS
        //XD
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'pinOlvido'
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * 
     * 
     */
    public function generateForgotPin(): string
    {
        $plainPin = str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);

        $this->update([
            'pinOlvido' => Hash::make($plainPin),
            'pinOlvido_expires_at' => Carbon::now()->addMinutes(15),
            'pinOlvido_created_at' => Carbon::now(),
            'pin_attempts' => 0,
            'pin_blocked_until' => null,
        ]);

        return $plainPin;
    }

    /**
     * Verifica si el pin es valido
     */
    public function verifyForgotPIn(string $pin): bool
    {
        if ($this->isPinBlocked()) {
            return false;
        }

        if ($this->pinOlvido_expires_at || $this->pinOlvido_expires_at->isPast()) {
            return false;
        }

        if (!$this->pinOlvido || !Hash::check($pin, $this->pin_olvido)) {
            $this->incrementPinAttempts();
            return false;
        }

        return true;
    }

    /**
     * Verifica si el pin esta bloqueado por intentos
     */
    public function isPinBLocked(): bool
    {
        return $this->pin_blocked_until && $this->pin_blocked_until->isFuture();
    }

    /**
     * Incrementa los intentos fallidos de PIN
     */
    private function incrementPinAttempts(): void
    {
        $attempts = $this->pin_attempts + 1;

        $updateData = ['pin_attempts' => $attempts];

        if ($attempts >= 5) {
            $updateData['pin_blocked_until'] = Carbon::now()->addMinutes(30);
        }
    }

    /**
     * Verifica si puede solicitar un nuevo pin
     */
    public function canRequestNewPin(): bool
    {
        if ($this->pinOlvido_created_at && $this->pinOlvido_created_at->diffInMinutes(Carbon::now()) < 5) {
            return false;
        }

        return true;
    }

    /**
     * Obtiene cuanto tiempo falta para solicitar un nuevo pin
     */
    public function getBlockerTimeRemaining(): ?int
    {
        if (!$this->isPinBLocked()) {
            return null;
        }

        return $this->pin_blocked_until->diffInMinutes(Carbon::now());
    }

    /**
     * Limpia el pin después de su uso exitoso
     */
    public function clearForgotPin(): void
    {
        $this->update([
            'pinOlvido' => null,
            'pinOlvido_expires_at' => null,
            'pinOlvido_created_at' => null,
            'pin_attempts' => 0,
            'pin_blocked_until' => null,
        ]);
    }

    /**
     * relacion con datos personales.
     */
    public function datosPersonales()
    {
        return $this->belongsTo(DatoPersonal::class, 'datosPersonales_idDatosPersonales', 'idDatosPersonales');
    }

    // TODAS LAS CONEXIONES Quien recibe y quien va
    public function medicos(){
        return $this->hasMany(Medico::class, 'usuarios_idUsuarios', 'idUsuarios');
    }

    public function auditoria(){
        return $this->hasMany(Auditoria::class, 'usuarios_idUsuarios', 'idUsuarios');
    }
}