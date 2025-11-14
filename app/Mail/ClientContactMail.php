<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ClientContactMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $user;
    public string $email;
    public string $phone;
    public string $country;
    public string $title;
    public string $content;
    public string $app_name;
    public string $support_mail;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(string $name, string $email, string $phone, string $title, string $content, string $country)
    {
        $this->user = $name;
        $this->email = $email;
        $this->phone = $phone;
        $this->country = $country;
        $this->title = $title;
        $this->content = $content;

        $this->app_name = config('app.name', __('message.webName'));
        $this->support_mail = config('mail.email_customer', '');
    }

    /**
     * Build the message contains a link to download the product
     *
     * @return $this the instance to send the message
     */
    public function build()
    {
        return $this
            ->to($this->support_mail)
            ->subject("Inquiry Regarding [" . $this->title . "]")
            ->view('mail.client_contact');
    }
}
