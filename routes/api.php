<?php

use Illuminate\Http\Request;

Route::group(['prefix' => 'auth'], function () {
    Route::post('login', 'Auth\AuthController@login');
    Route::post('signup', 'Auth\AuthController@signup');

    Route::group(['middleware' => 'auth:api'], function() {
        Route::get('logout', 'Auth\AuthController@logout');
        Route::get('user', 'Auth\AuthController@user');
    });
});

// Folders
Route::group(['prefix' => 'folder'], function () {
    Route::get('/', 'Folder\FolderController@index');        // Muestra todas las carpetas
    Route::post('/create', 'Folder\FolderController@store');  // Crea nueva carpeta
    Route::post('/update', 'Folder\FolderController@update');  // Modifica carpeta
    Route::post('/delete', 'Folder\FolderController@destroy');  // Modifica carpeta
});

// Credentials
Route::group(['prefix' => 'credentials'], function () {
    Route::get('/{slug}', 'Credential\CredentialController@index');   // Muestra todas las credenciales de una carpeta.
    Route::post('/create', 'Credential\CredentialController@store');   // Crea nueva credencial
    Route::post('/update', 'Credential\CredentialController@update');   // Actualizo credencial
    Route::post('/delete', 'Credential\CredentialController@destroy');   // Elimino credencial
});

// Users
Route::group(['prefix' => 'users'], function () {
    Route::get('/', 'User\UserController@index');   // Muestra todos los usuarios.
    Route::post('/update', 'User\UserController@change_role');   // Actualizo rol
});

// Csv
Route::group(['prefix' => 'csv'], function () {
    Route::post('/', 'Csv\CsvController@startDownloadCSV');   // Generar csv
});
