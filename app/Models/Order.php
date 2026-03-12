<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number', 'user_id', 'total_amount', 'shipping_cost', 
        'grand_total', 'status', 'payment_method', 'payment_token', 
        'payment_deadline', 'shipping_service', 'tracking_number',
        'receiver_name', 'receiver_phone', 'receiver_address'
    ];

    // Otomatis set deadline 2 jam saat order dibuat
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($order) {
            $order->payment_deadline = Carbon::now()->addHours(2);
            $order->order_number = 'FRH-' . date('Ymd') . '-' . strtoupper(bin2hex(random_bytes(3)));
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}