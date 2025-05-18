<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Facture extends Model
{
    protected $fillable = ['paiement_id', 'numero', 'fichier_pdf', 'date_emission'];

    public function paiement()
    {
        return $this->belongsTo(Paiement::class);
    }
}