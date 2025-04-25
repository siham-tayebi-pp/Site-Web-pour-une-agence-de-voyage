<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = ['user_id', 'voyage_id', 'nb_personnes', 'statut'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function voyage()
    {
        return $this->belongsTo(Voyage::class);
    }

    public function paiement()
    {
        return $this->hasOne(Paiement::class);
    }
}