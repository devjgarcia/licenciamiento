<?php

namespace App\Http\Controllers;

use App\Imports\IdiomaImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ImportIdiomaController extends Controller
{
    public function create()
    {
        return view('idioma.import');
    }

    public function import( Request $request )
    {
        $resp = Excel::import(new IdiomaImport, $request->file);


        dd($resp);
        //return redirect()->route('users.index')->with('success', 'User Imported Successfully');
    }
}
