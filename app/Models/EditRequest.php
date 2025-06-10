<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EditRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'data_record_id',
        'user_id',
        'admin_id',
        'status',
        'admin_notes',
    ];

    /**
     * Data record relationship
     */
    public function dataRecord()
    {
        return $this->belongsTo(DataRecord::class);
    }

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
}
