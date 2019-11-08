<?php

namespace App\Http\Controllers\Folder;

use App\Folder;
use App\Http\Controllers\HelperController;
use App\Role;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FolderController extends HelperController
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    // Listado de carpetas
    public function index()
    {
        // Busco la lista de carpetas del usuario autenticado
        // $folders = User::find(Auth::user()->id)->folders; //viejo
        $folders = Folder::all();

        // Agrego elemento extra del conteo de las credenciales por carpeta.
        $folders->map(function($cre){
            $cre->count_credentials = $cre->credentials->count();
        });

        return response()->json($folders, 200);
    }

    // Nueva Carpeta
    public function store(Request $request)
    {
        // Middleware fast
        if(Auth::user()->role_id != Role::ADMIN)
        {
            return $this->errorResponse('No tienes permiso de administrador.');
        }

        // Validacion
        $rules = ['name' => 'required|string'];

        if($this->jgmo($request, $rules))
        {
            return $this->jgmo($request, $rules);
        }

        // Creo la carpeta del usuario
        Folder::create([
            'user_id' => Auth::user()->id,
            'name'    => $request->name,
            'slug'    => $this->slug_generate($request->name)
        ]);

        return $this->showMessage('Save');
    }

    // Editar Nombre de Carpeta
    public function update(Request $request)
    {
        // Middleware fast
        if(Auth::user()->role_id != Role::ADMIN)
        {
            return $this->errorResponse('No tienes permiso de administrador.');
        }

        // Validacion
        $rules = ['slug' => 'required|string', 'name' => 'required|string'];

        $this->validate($request, $rules);

        // Busco la carpeta
        $folder = Folder::where('user_id', Auth::user()->id)->where('slug', $request->slug)->first();

        $folder->name = $request->name;
        $folder->slug = $this->slug_generate($request->name);
        $folder->update();

        return $this->showMessage('Update');
    }

    // Eliminar Carpeta
    public function destroy(Request $request)
    {
        // Middleware fast
        if(Auth::user()->role_id != Role::ADMIN)
        {
            return $this->errorResponse('No tienes permiso de administrador.');
        }
        
        // Validacion
        $rules = ['slug' => 'required|string'];

        $this->validate($request, $rules);

        // Busco la carpeta y elimino
        Folder::where('user_id', Auth::user()->id)->where('slug', $request->slug)->first()->delete();

        return $this->showMessage('Delete');
    }

    // Creo slug unico
    public function slug_generate($name)
    {
        // id user
        $user_id = Auth::user()->id;

        // number random
        $random = rand(10,500);

        // replace space for -
        $replace = str_replace(" ", "-", $name);

        return $replace . '-' .$user_id . $random;
    }
}
