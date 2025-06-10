<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Upazila extends Model
{
    use HasFactory;

    protected $fillable = [
        'district_id',
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
     * Get the district that owns the upazila
     */
    public function district()
    {
        return $this->belongsTo(District::class);
    }

    /**
     * Get the mouzas for the upazila
     */
    public function mouzas()
    {
        return $this->hasMany(Mouza::class);
    }

    /**
     * Get active mouzas for the upazila
     */
    public function activeMouzas()
    {
        return $this->hasMany(Mouza::class)->where('is_active', true);
    }

    /**
     * Scope for active upazilas
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
