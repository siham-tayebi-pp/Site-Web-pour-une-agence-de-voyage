<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    protected $fillable = ['reservation_id', 'montant', 'mode', 'statut', 'date_paiement'];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }

    public function facture()
    {
        return $this->hasOne(Facture::class);
    }
}