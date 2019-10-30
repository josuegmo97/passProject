<?php

namespace App\Http\Controllers\Credential;

use App\Credential;
use App\Folder;
use App\Http\Controllers\HelperController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CredentialController extends HelperController
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    // Listado de credenciales de una carpeta
    public function index($slug)
    {
        // Busco la lista de credenciales de la carpeta
        $credentials = Folder::where('user_id', Auth::user()->id)->where('slug', $slug)->first();

        // Valido que exista
        if(!$credentials)
        {
            return $this->errorResponse('Unknown folder', 404);
        }

        return response()->json($credentials->credentials, 200);
    }

    // Nueva credentials
    public function store(Request $request)
    {
        // Validacion
        $rules = [
            'slug' => 'required|string',
            'name' => 'required|string',
            'url'  => 'required|string',
            'credential' => 'required|string',            
        ];

        $this->validate($request, $rules);

        // Busco la carpeta por medio del slug
        $credential = Folder::where('user_id', Auth::user()->id)->where('slug', $request->slug)->first();

        // Creo la credencial del usuario
        Credential::create([
            'folder_id'   => $credential->id,
            'name'        => $request->name,
            'url'         => $request->url,
            'credential'  => $request->credential
        ]);

        return $this->showMessage('Save');
    }
}
