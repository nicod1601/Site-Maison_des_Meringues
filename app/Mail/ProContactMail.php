<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ProContactMail extends Mailable
{
	use Queueable, SerializesModels;

	public function __construct(public array $data) {}

	public function build(): self
	{
		return $this
			->subject('Nouvelle demande professionnelle — ' . $this->data['prenom'] . ' ' . $this->data['nom'])
			->replyTo($this->data['email'], $this->data['prenom'] . ' ' . $this->data['nom'])
			->view('emails.pro-contact')
			->with($this->data);
	}
}
