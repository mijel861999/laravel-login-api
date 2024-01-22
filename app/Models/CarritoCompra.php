<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarritoCompra extends Model
{
    use HasFactory;

    protected $fillable = ["usuario_id", "estado", "detalle_pedido_id", "producto_id"];

    public function usuario() {
        return $this->belongsTo(User::class);
    }

    public function detallePedido() {
        return $this->belongsTo(DetallePedido::class, 'detalle_pedido_id');
    }

    public function producto() {
        return $this->belongsTo(Producto::class);
    }
}
