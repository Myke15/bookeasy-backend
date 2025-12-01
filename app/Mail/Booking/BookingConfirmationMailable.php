<?php

namespace App\Mail\Booking;

use App\Models\Booking\Booking;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class BookingConfirmationMailable extends Mailable
{

    public $booking;

    /**
     * Create a new message instance.
     */
    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $serviceName = config('constant.services')[$this->booking->service] ?? $this->booking->service;
        return new Envelope(
            subject: 'Booking Confirmation - ' . $serviceName,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.booking-confirmation',
            with: [
                'booking' => $this->booking,
                'client' => $this->booking->client,
                'serviceName' => config('constant.services')[$this->booking->service] ?? $this->booking->service,
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