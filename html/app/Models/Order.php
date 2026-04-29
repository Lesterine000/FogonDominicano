<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    /**
     * Campos que se pueden llenar masivamente.
     */
    protected $fillable = [
        'dish_id',
        'dish_name',
        'unit_price',
        'service_date',
        'customer_name',
        'customer_email',
        'customer_phone',
        'quantity',
        'pickup_time',
        'status',
        'is_paid',
        'paid_at',
        'paid_by',
        'status_updated_at',
        'status_updated_by',
        'cancelled_at',
        'cancelled_by',
    ];

    protected $casts = [
        'service_date' => 'date',
        'unit_price' => 'decimal:2',
        'is_paid' => 'boolean',
        'paid_at' => 'datetime',
        'status_updated_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    /**
     * RELACIÓN CRÍTICA: Un pedido pertenece a un plato.
     * Si aquí decía 'inventory', cámbialo a 'dish'.
     */
    public function dish()
    {
        return $this->belongsTo(Dish::class, 'dish_id')->withTrashed();
    }

    /**
     * Formatear el estado para la vista (Opcional)
     */
    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'pending' => 'Pendiente',
            'completed' => 'Completado',
            'cancelled' => 'Cancelado',
            default => 'Desconocido',
        };
    }

    public function paidByUser()
    {
        return $this->belongsTo(User::class, 'paid_by');
    }

    public function statusUpdatedByUser()
    {
        return $this->belongsTo(User::class, 'status_updated_by');
    }

    public function cancelledByUser()
    {
        return $this->belongsTo(User::class, 'cancelled_by');
    }
}
