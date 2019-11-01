<?php

namespace App\Http\Controllers\Credential;

use App\Credential;
use App\Folder;
use App\Http\Controllers\HelperController;
use App\Http\Controllers\Folder\FolderController;
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

        $credentials->credentials->map(function($cre){
            $cre->view = false;
        });

        return response()->json($credentials->credentials, 200);
    }

    // Nueva credential
    public function store(Request $request)
    {
        // Extiendo el creador de slug para no repetir codigo
        $generate = new FolderController;

        // Validacion
        $rules = [
            'slug' => 'required|string',
            'name' => 'required|string',
            'url'  => 'required|string',
            'pwCredential' => 'required|string',
        ];

        if($this->jgmo($request, $rules))
        {
            return $this->jgmo($request, $rules);
        }

        // Busco la carpeta por medio del slug
        $credential = Folder::where('user_id', Auth::user()->id)->where('slug', $request->slug)->first();

        // Creo la credencial del usuario
        Credential::create([
            'folder_id'   => $credential->id,
            'name'        => $request->name,
            'url'         => $request->url,
            'credential'  => $request->pwCredential,
            'slug'        => $generate->slug_generate($request->name)
        ]);

        return $this->showMessage('Save');
    }

    // Editar credential
    public function update(Request $request)
    {
        // Extiendo el creador de slug para no repetir codigo
        $generate = new FolderController;

        // Validacion
        $rules = [
            'slug' => 'required|string'
        ];

        $this->validate($request, $rules);

        // La credencial a actualizar
        $credential = Credential::where('slug', $request->slug)->first();

        // Valido que exista
        if(!$credential)
        {
            return $this->errorResponse('Error Ocurried', 401);
        }

        // Si hay cambio en el nombre, actualizo slug y nombre
        if($request->name)
        {
            $credential->name = $request->name;
            $credential->slug = $generate->slug_generate($request->name);
        }

        // Si hay cambio en el pwCredential, actualizo credential
        if($request->pwCredential)
        {
            $credential->pwCredential = $request->pwCredential;
        }
    }

    // Eliminar Credencial
    public function destroy(Request $request)
    {
        // Validacion
        $rules = ['slug' => 'required|string'];

        $this->validate($request, $rules);

        // Busco la credencial y elimino
        Credential::where('slug', $request->slug)->first()->delete();

        return $this->showMessage('Delete');
    }
}
