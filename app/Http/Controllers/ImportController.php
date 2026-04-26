<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\UsersImport;

class ImportController extends Controller
{
	public function import(Request $request)
	{
		$request->validate([
			'file' => 'required|mimes:xlsx,xls,csv',
		]);

		$file = $request->file('file');

		$datas = Excel::toArray([], $file);
		session(['import_preview' => $datas]);

		Excel::import(new UsersImport(), $file);

		return redirect()->route('gestion')->with('success', 'Import effectué avec succès !');
	}

	public function clear()
	{
		session()->forget('import_preview');
		return redirect()->route('gestion');
	}
}
