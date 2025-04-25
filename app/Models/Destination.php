<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Destination extends Model
{
    protected $fillable = ['nom', 'description'];

    public function voyages()
    {
        return $this->hasMany(Voyage::class);
    }
}