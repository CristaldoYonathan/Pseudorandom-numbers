<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NumerosCongruencia extends Model
{
    use HasFactory;
    protected $table = 'numeroscongruencia';


    protected $fillable = [
        'semillavi',
        'semiillavik',
        'cantidadCong',
        'constanteA',
        'constanteC',
        'constanteM',
        ];


    //relacion muchos a uno user
    public function user(){
        return $this->belongsTo('App\Models\User');
    }
}
