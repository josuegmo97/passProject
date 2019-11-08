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

        // Llenar archivo con la informacion de los contactos.
                $products = [];
                $product1['name'] = 'Gabriel';
                $product1['description'] = 'kionda';
                $product1['prince'] = '2111';
                $products[] = $product1;

                $product2['name'] = 'Osuna';
                $product2['description'] = 'kiiii';
                $product2['prince'] = '2112312311';
                $products[] = $product2;

                // dd($this->products);
        $data = [];
        $all = [];
        foreach($folders as $id => $product)
        {
            $all['name'] = $product->name;
            $all['url'] = $product->url;
            $all['credential'] = $product->credential;
            
            $data[] = $all;
            // return response()->json(['data' => $this->products->toString()]);
        }
        fputcsv($file, $data);

        // Generar 1 descarga.
        return response()->download($fileName);
    }
}
