<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;
    public string $motDePasse;

    public function __construct(User $user, string $motDePasse)
    {
        $this->user       = $user;
        $this->motDePasse = $motDePasse;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🐟 Bienvenue sur FreshMarket — Vos identifiants de connexion',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.welcome',
        );
    }
}