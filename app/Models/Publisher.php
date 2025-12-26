<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Publisher extends Model
{
    /** @use HasFactory<\Database\Factories\PublisherFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'description', 'slug'];

    public function books()
    {
        return $this->hasMany(Book::class);
    }
}
