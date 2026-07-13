<?php

namespace App\Mail;

use App\Models\BorrowingSchedule;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Mailable class untuk notifikasi email Check-In berhasil.
 *
 * Email ini dikirim ke member setelah proses check-in
 * peminjaman peralatan berhasil dilakukan.
 */
class CheckInSuccessMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Data jadwal peminjaman yang akan ditampilkan di email.
     *
     * Menggunakan public property agar otomatis tersedia di Blade view.
     */
    public BorrowingSchedule $schedule;

    /**
     * Create a new message instance.
     *
     * @param  BorrowingSchedule  $schedule  Data jadwal peminjaman yang sudah di-check-in.
     */
    public function __construct(BorrowingSchedule $schedule)
    {
        $this->schedule = $schedule;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Check-In Berhasil - Smart-Hub Management System',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.check-in-success',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
