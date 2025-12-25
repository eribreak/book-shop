<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = ['name', 'description', 'slug', 'is_home',];

    protected function casts(): array
    {
        return [
            'deleted_at' => 'datetime',
        ];
    }

    public function books()
    {
        return $this->belongsToMany(Book::class, 'book_category');
    }
}
