<?php

namespace App\Http\Controllers\Folder;

use App\Folder;
use App\Http\Controllers\HelperController;
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
        $user_folders = User::find(Auth::user()->id);

        return response()->json($user_folders->folders, 200);
    }

    // Nueva Carpeta
    public function store(Request $request)
    {
        // Validacion
        $rules = ['name' => 'required|string'];

        $this->validate($request, $rules);

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
