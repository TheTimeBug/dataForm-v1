<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DataRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
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
     * Edit requests relationship
     */
    public function editRequests()
    {
        return $this->hasMany(EditRequest::class);
    }
}
