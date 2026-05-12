<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WelcomeMail extends Mailable
{
	use Queueable, SerializesModels;

	public function __construct(public string $prenom) {}

	public function build(): self
	{
		return $this
			->subject('Bienvenue à La Maison des Meringues 🍬')
			->view('emails.test')
			->with([
				'prenom'   => $this->prenom,
				'shop_url' => config('app.url'),
			]);
	}
}
