<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataEditHistory extends Model
{
    use HasFactory;
    
    protected $table = 'data_edit_history';

    protected $fillable = [
        'data_record_id',
        'field_name',
        'old_value',
        'new_value',
        'changed_by',
        'action_type',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the data record that this history belongs to
     */
    public function dataRecord()
    {
        return $this->belongsTo(DataRecord::class);
    }

    /**
     * Get the user who made the change
     */
    public function changedBy()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
