<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketCommandeController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Remplace par ta vraie relation/model quand tu l'auras
        $commandes = []; // ex: $user->commandes()->with('produits')->latest()->get();

        return view('ticket-commande', compact('commandes'));
    }
}