<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;

    protected $fillable = [
        'division_id',
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
     * Get the division that owns the district
     */
    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    /**
     * Get the upazilas for the district
     */
    public function upazilas()
    {
        return $this->hasMany(Upazila::class);
    }

    /**
     * Get active upazilas for the district
     */
    public function activeUpazilas()
    {
        return $this->hasMany(Upazila::class)->where('is_active', true);
    }

    /**
     * Scope for active districts
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
