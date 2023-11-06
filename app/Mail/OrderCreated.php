<?php

/**
 * MyClass File Doc Comment
 * php version 8.1
 *
 * @category Mails
 * @package  App\Mails
 * @author   Marcos Motta <mrcsmotta1@gmail.com>
 * @license  MIT License
 * @link     https://github.com/mrcsmotta1/sistema-gerenciamento-pastelaria
 */

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Class Mail
 *
 * Represents a Mail in the system.
 *
 * @category Mails
 * @package  App\Mails
 * @author   Marcos Motta <mrcsmotta1@gmail.com>
 * @license  MIT License
 * @link     https://github.com/mrcsmotta1/sistema-gerenciamento-pastelaria
 */
class OrderCreated extends Mailable
{
    use Queueable;
    use SerializesModels;

    public $order;
    public $orderNumber;
    public $dateOfBirth;

    /**
     * Create a new message instance.
     *
     * @param Order  $order       The Order instance.
     * @param string $orderNumber The order number.
     * @param string $dateOfBirth The date of birth.
     */
    public function __construct(Order $order, $orderNumber, $dateOfBirth)
    {
        $this->order = $order;
        $this->orderNumber = $orderNumber;
        $this->dateOfBirth = $dateOfBirth;
    }

    /**
     * Get the message envelope.
     *
     * @return Envelope
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Confirmação de Pedido - Pedido #' . $this->orderNumber,
        );
    }

    /**
     * Get the message content definition.
     *
     * @return Content
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'mail.order-created',
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
