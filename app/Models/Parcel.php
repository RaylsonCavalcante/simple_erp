<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Parcel extends Model
{
    protected $fillable = ['sale_id', 'numero', 'vencimento', 'valor'];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

}
