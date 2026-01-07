<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

enum ReviewStatus: int
{
    case PENDING = 0;
    case APPROVED = 1;
    case DECLINED = 2;
}

class Review extends Model
{
    /** @use HasFactory<\Database\Factories\ReviewFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = ['user_id', 'book_id', 'content', 'status', 'rating'];

    protected $casts = [
        'user_id' => 'integer',
        'book_id' => 'integer',
        'status' => 'integer',
        'rating' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
