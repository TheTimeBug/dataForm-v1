<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DataRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'is_edit_request',
        'parent_id',
        'status',
        'admin_id',
        'admin_notes',
        'integer_field_1',
        'integer_field_2',
        'integer_field_3',
        'integer_field_4',
        'selector_field_1',
        'selector_field_2',
        'selector_field_3',
        'selector_field_4',
        'comment_field_1',
        'comment_field_2',
    ];

    /**
     * User relationship
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Admin relationship
     */
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /**
     * Parent data record relationship (for edit requests)
     */
    public function parent()
    {
        return $this->belongsTo(DataRecord::class, 'parent_id');
    }

    /**
     * Child edit requests relationship
     */
    public function editRequests()
    {
        return $this->hasMany(DataRecord::class, 'parent_id')->where('is_edit_request', true);
    }

    /**
     * Edit history relationship
     */
    public function editHistory()
    {
        return $this->hasMany(DataEditHistory::class);
    }

    /**
     * Scope for actual data records (not edit requests)
     */
    public function scopeActualRecords($query)
    {
        return $query->where('is_edit_request', false);
    }

    /**
     * Scope for edit requests
     */
    public function scopeEditRequests($query)
    {
        return $query->where('is_edit_request', true);
    }

    /**
     * Scope for pending edit requests
     */
    public function scopePendingEditRequests($query)
    {
        return $query->where('is_edit_request', true)->where('status', 'pending');
    }


}
