<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Credential extends Model
{
    protected $fillable = [
        'folder_id',
        'name',
        'url',
        'credential',
        'slug'
    ];
}
