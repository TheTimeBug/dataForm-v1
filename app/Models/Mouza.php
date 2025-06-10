<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mouza extends Model
{
    use HasFactory;

    protected $fillable = [
        'upazila_id',
        'name',
        'name_bn',
        'code',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the upazila that owns the mouza
     */
    public function upazila()
    {
        return $this->belongsTo(Upazila::class);
    }

    /**
     * Scope for active mouzas
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
