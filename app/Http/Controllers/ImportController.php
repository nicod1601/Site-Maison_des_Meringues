<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\UsersImport;

class ImportController extends Controller
{
	public function import(Request $request)
	{
		// ✅ Corrigé : id_rayon validé — sans ça, UsersImport plante si absent ou invalide
		$request->validate([
			'file'     => 'required|mimes:xlsx,xls,csv',
			'id_rayon' => 'required|exists:rayon,id_rayon',
		]);

		$file = $request->file('file');

		// Stocke un aperçu brut du fichier en session (utilisé dans GestionController->index)
		$datas = Excel::toArray([], $file);
		session(['import_preview' => $datas]);

		Excel::import(new UsersImport((int) $request->id_rayon), $file);

		// ✅ Supprimé : $type_donnee — tableau créé mais jamais utilisé ni passé à la vue

		return redirect()->route('gestion')->with('success', 'Import effectué avec succès !');
	}

	public function clear()
	{
		session()->forget('import_preview');
		return redirect()->route('gestion');
	}
}
