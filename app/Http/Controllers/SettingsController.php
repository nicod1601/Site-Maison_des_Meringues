<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class SettingsController extends Controller
{
	// ── Page principale ───────────────────────────────────────────────────────
	public function index()
	{
		return view('settings');
	}

	// ── Mise à jour du profil ─────────────────────────────────────────────────
	public function updateProfile(Request $request)
	{
		$user = Auth::user();

		$request->validate([
			'name'  => 'required|string|max:255',
			'email' => 'required|email|unique:users,email,' . $user->id,
			'phone' => 'nullable|string|max:20',
			'adresse' => 'nullable|string|max:255',
			'ville' => 'nullable|string|max:100',
			'code_postal' => 'nullable|string|max:20',
		]);

		$user->update([
			'name'  => $request->name,
			'email' => $request->email,
			'phone' => $request->phone,
			'adresse' => $request->adresse,
			'ville' => $request->ville,
			'code_postal' => $request->code_postal,
		]);

		return back()->with('success_profile', 'Profil mis à jour avec succès.');
	}

	// ── Changement de mot de passe ────────────────────────────────────────────
	public function updatePassword(Request $request)
	{
		$request->validate([
			'current_password' => 'required',
			'password'         => ['required', 'confirmed', Password::min(8)],
		]);

		if (!Hash::check($request->current_password, Auth::user()->password)) {
			return back()
				->withErrors(['current_password' => 'Le mot de passe actuel est incorrect.'])
				->with('tab', 'compte');
		}

		Auth::user()->update([
			'password' => Hash::make($request->password),
		]);

		return back()->with('success_password', 'Mot de passe changé avec succès.');
	}

	// ── Désactiver le compte ──────────────────────────────────────────────────
	public function deactivate(Request $request)
	{
		$user = Auth::user();
		$user->delete();

		return redirect('/')->with('info', 'Votre compte a été désactivé.');
	}
}
