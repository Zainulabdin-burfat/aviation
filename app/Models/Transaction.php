<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'buyer_id',   // Assuming each transaction has a buyer
        'seller_id',  // Assuming each transaction has a seller
        'amount',     // Amount of the transaction
        'status',     // Status of the transaction (e.g., pending, approved, declined)
    ];

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function listing()
{
    return $this->belongsTo(Listing::class);
}
}
