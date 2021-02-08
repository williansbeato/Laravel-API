<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Especialidade extends Model
{
    protected $fillable = ['nome', 'descricao'];

    public function veterinarios() {
        return $this->hasMany('App\Veterinario');
    }
}
