<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactForm extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $inputs;

    public function __construct($inputs)
    {
        $this->inputs = $inputs;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        //件名を入れる
        return new Envelope(
            subject: 'お問い合わせを受け付けました',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        //ビューファイルに渡す　大事なところ。
        return new Content(
            view: 'emails.contact',
            with:[
                'inputs' => $this->inputs
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
        //添付ファイルを設定できる。今回は使用しない。
        return [];
    }
}
