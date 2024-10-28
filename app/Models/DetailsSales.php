<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailsSales extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_identifier', // id de la tabla sales
        'nombre',             // nombre del producto
        'stock',              // cantidad
        'price',              // precio
        'total',              // total
    ];

    // Define la relación con la tabla de ventas si es necesario
    public function sale()
    {
        return $this->belongsTo(Sale::class, 'account_identifier');
    }
}
