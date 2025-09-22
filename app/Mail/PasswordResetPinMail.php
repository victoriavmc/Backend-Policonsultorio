<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PasswordResetPinMail extends Mailable
{
    use Queueable, SerializesModels;

    protected User $user;
    protected string $pin;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, string $pin)
    {
        $this->user = $user;
        $this->pin = $pin;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Codigo de restablecimiento de contraseña - ' . config('app.name'),
            from: config('mail.from.address'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            html: 'emails.password-reset-pin',
            text: 'emails.password-reset-pin-text',
            with: [
                'user' => $this->user,
                'pin' => $this->pin,
                'userName' => $this->user->datosPersonales->nombre ?? 'Usuario',
                'appName' => config('app.name'),
                'expirationMinutes' => 15
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
