<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Voyage extends Model
{
    protected $fillable = ['titre', 'description', 'prix', 'date_depart', 'duree', 'places_dispo', 'destination_id', 'categorie_id'];

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function destination()
    {
        return $this->belongsTo(Destination::class);
    }

    public function categorie()
    {
        return $this->belongsTo(Categorie::class);
    }
}