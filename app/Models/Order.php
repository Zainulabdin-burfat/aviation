<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'order_number',
        'customer_id',
        'shipment_date',
        'status',
        'items',
        'origin',
        'destination',
        'name',
        'email',
        'phone',
        'address',
        'created_by',
        'updated_by',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function originBranch()
    {
        return $this->belongsTo(Branch::class, 'origin');
    }

    public function destinationState()
    {
        return $this->belongsTo(State::class, 'destination');
    }

    public function deliveryComments()
    {
        return $this->hasMany(DeliveryComment::class, 'order_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
