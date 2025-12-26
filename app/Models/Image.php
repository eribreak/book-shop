<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Image extends Model
{
    /** @use HasFactory<\Database\Factories\ImageFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = ['url', 'book_id'];

    protected $casts = ['book_id' => 'integer'];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
