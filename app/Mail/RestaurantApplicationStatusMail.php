<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RestaurantApplicationStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public $restaurantName;
    public $restaurantEmail;
    public $restaurantPhone;
    public $status; // 'approved' or 'rejected'

    /**
     * Create a new message instance.
     */
    public function __construct(string $restaurantName, string $restaurantEmail, ?string $restaurantPhone, string $status)
    {
        $this->restaurantName = $restaurantName;
        $this->restaurantEmail = $restaurantEmail;
        $this->restaurantPhone = $restaurantPhone;
        $this->status = $status;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = ($this->status == 'approved')
            ? 'Pengajuan Restoran Anda Disetujui! - ' . config('app.name')
            : 'Pemberitahuan Status Pengajuan Restoran - ' . config('app.name');

        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.restaurant_application_status', // Nama template Blade Markdown
            with: [
                'name' => $this->restaurantName,
                'email' => $this->restaurantEmail,
                'phone' => $this->restaurantPhone,
                'status' => $this->status,
                'appName' => config('app.name'),
            ],
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
