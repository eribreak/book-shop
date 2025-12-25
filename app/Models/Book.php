<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Book extends Model
{
    /** @use HasFactory<\Database\Factories\BookFactory> */
    use HasFactory, SoftDeletes;
    protected $fillable = ['name', 'slug', 'short_description', 'description', 'image_url', 'quantity', 'published_at', 'publisher_id',];

    protected $casts = [
        'quantity' => 'integer',
        'published_at' => 'datetime',
        'publisher_id' => 'integer',
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'book_category');
    }

    public function images()
    {
        return $this->hasMany(Image::class);
    }
    public function authors()
    {
        return $this->belongsToMany(Author::class, 'author_book');
    }
    public function publisher()
    {
        return $this->belongsTo(Publisher::class);
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }
}
