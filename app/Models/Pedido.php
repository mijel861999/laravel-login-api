<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;

    protected $fillable = ["usuario_id", "estado"];

    public function usuario() {
        return $this->belongsTo(User::class);
    }

    public function detallePedido() {
        return $this->hasMany(DetallePedido::class);
    }
}
