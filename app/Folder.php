<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Folder extends Model
{
    protected $fillable = ['user_id', 'name', 'slug'];

    protected $hidden = ['id', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function credentials()
    {
        return $this->hasMany(Credential::class);
    }
}
