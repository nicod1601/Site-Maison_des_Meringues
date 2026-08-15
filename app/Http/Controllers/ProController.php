<?php

namespace App\Http\Controllers;

use App\Mail\ProContactMail;
use App\Models\Forme;
use App\Models\Conditionnement;
use App\Models\Parfum;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ProController extends Controller
{
	public function index()
	{
		$formes = Forme::all();
		$conditionnements = Conditionnement::all();
		$parfums = Parfum::all();

		return view('pro', compact('formes', 'conditionnements', 'parfums'));
	}

	public function contact(Request $request): RedirectResponse
	{
		$typesLabels = [
			'epicerie'   => 'Épicerie fine',
			'restaurant' => 'Restaurant / Hôtel',
			'revendeur'  => 'Revendeur',
			'evenement'  => 'Événementiel',
			'autre'      => 'Autre',
		];

		$data = $request->validate([
			'prenom'    => ['required', 'string', 'max:100'],
			'nom'       => ['required', 'string', 'max:100'],
			'societe'   => ['nullable', 'string', 'max:150'],
			'email'     => ['required', 'email', 'max:190'],
			'telephone' => ['nullable', 'string', 'max:30'],
			'type_pro'  => ['required', 'in:' . implode(',', array_keys($typesLabels))],
			'message'   => ['required', 'string', 'max:3000'],
		], [
			'prenom.required'   => 'Merci d\'indiquer votre prénom.',
			'nom.required'      => 'Merci d\'indiquer votre nom.',
			'email.required'    => 'Merci d\'indiquer votre email.',
			'email.email'       => 'Cet email ne semble pas valide.',
			'type_pro.required' => 'Merci de choisir votre type d\'activité.',
			'message.required'  => 'Merci de décrire brièvement votre projet.',
		]);

		$data['type_pro_label'] = $typesLabels[$data['type_pro']] ?? $data['type_pro'];

		// "message" est un nom réservé dans les vues d'email Laravel
		// (il désigne l'objet technique du mail) → on le renomme pour l'affichage.
		$data['contenu_message'] = $data['message'];
		unset($data['message']);

		try {
			Mail::to(config('mail.pro_contact_to', 'contact@lamaisondesmeringues.fr'))
				->send(new ProContactMail($data));
		} catch (\Throwable $e) {
			Log::error('Échec envoi email contact pro : ' . $e->getMessage());

			return back()
				->withInput()
				->with('pro_contact_error', "Votre message n'a pas pu être envoyé pour le moment. Merci de réessayer ou de nous appeler directement.");
		}

		return back()->with('pro_contact_success', 'Merci ! Votre demande a bien été envoyée, nous vous répondrons sous 24h.');
	}
}
