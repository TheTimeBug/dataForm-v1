<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Division extends Model
{
    use HasFactory;

    protected $fillable = [
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
     * Get the districts for the division
     */
    public function districts()
    {
        return $this->hasMany(District::class);
    }

    /**
     * Get active districts for the division
     */
    public function activeDistricts()
    {
        return $this->hasMany(District::class)->where('is_active', true);
    }

    /**
     * Scope for active divisions
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
