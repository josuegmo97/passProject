<?php

namespace App\Http\Controllers\Csv;

use App\Folder;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CsvController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function startDownloadCSV(Request $request)
    {
        $folders = Folder::where('slug', $request->slug)->first()->credentials()->select('name', 'url', 'credential')->get();

        // Crear archivo CSV.
        $fileName = date('Y-m-d').'.csv';
        // $filePath = public_path($fileName);
        $file = fopen($fileName, 'w');

        $datas = [];
        $all = [];
        foreach($folders as $folder)
        {
            $all["nombre"] = $folder->name;
            $all["url"] = $folder->url;
            $all["credencial"] = $folder->credential;
            
            $datas[] = $all;
        }

        // Llenar archivo con la informacion de los contactos.
        foreach($datas as $data)
        {
            fputcsv($file, $data);
        }

        // Generar 1 descarga.
        return response()->download($fileName);
    }
}
