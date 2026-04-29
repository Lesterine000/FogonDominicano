<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReservationConfirmed extends Mailable
{
    use Queueable, SerializesModels;

    public $order;

    /**
     * Create a new message instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
        $this->afterCommit();
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Confirmacion de reserva')
            ->view('emails.reservation_confirmed')
            ->with([
                'order' => $this->order,
            ]);
    }
}
