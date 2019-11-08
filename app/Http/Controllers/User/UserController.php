<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Controllers\HelperController;
use App\Role;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends HelperController
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    // Muestro todos los usuarios a exepcion del mio.
    public function index()
    {
        // Middleware fast
        if(Auth::user()->role_id != Role::ADMIN)
        {
            return $this->errorResponse('No tienes permiso de administrador.');
        }

        $users = User::select(
                    'users.id',
                    'users.username as name',
                    'users.email',
                    'roles.name as rol'
                )
                ->join('roles', 'roles.id', 'users.role_id')
                ->where('users.id', '!=', Auth::user()->id)->get();

        return response()->json($users, 200);
    }

    // Actualizo el rol del usuario
    public function change_role(Request $request)
    {
        // Seteo el usuario
        $user = User::find($request->user_id);

        // valido su rol
        if($user->role_id === Role::ADMIN)
        {
            $user->role_id = Role::GENERAL;
        }else{
            $user->role_id = Role::ADMIN;
        }

        // Actualizo
        $user->update();
        
        return('Success');
    }
}
