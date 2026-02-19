<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    // إضافة الحقول التي يمكن تعبئتها جماعياً
    protected $fillable = [
        'user_id',
        'total',
        'address',
        'visitor_name',
        'visitor_phone',
        'payment_method',
        'status',
        'notes',
    ];

    // العلاقة مع المستخدم
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // العلاقة مع عناصر الطلب
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

}
