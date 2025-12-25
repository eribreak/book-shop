<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderDetail extends Model
{
    /** @use HasFactory<\Database\Factories\OrderDetailFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_id',
        'book_id',
        'book_name',
        'quantity',
        'status',
        'due_date',
    ];

    protected $casts = [
        'order_id' => 'integer',
        'book_id' => 'integer',
        'quantity' => 'integer',
        'status' => 'integer',
        'due_date' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
