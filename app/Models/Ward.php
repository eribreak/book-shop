<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ward extends Model
{
    /** @use HasFactory<\Database\Factories\WardFactory> */
    use HasFactory;

    protected $fillable = ['name', 'district_id'];

    protected $casts = [
        'district_id' => 'integer',
    ];

    public function district()
    {
        return $this->belongsTo(District::class);
    }
}
