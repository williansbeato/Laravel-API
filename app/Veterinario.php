<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Veterinario extends Model
{
    protected $fillable = ['nome', 'crmv', 'especialidade_id'];

    public function especialidade() {
        return $this->belongsTo('App\Especialidade');
    }
   
}
