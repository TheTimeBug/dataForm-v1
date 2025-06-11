<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'mobile',
        'password',
        'role',
        'admin_type',
        'division_id',
        'district_id',
        'upazila_id',
        'status',
        'status_reason',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'password_updated_at' => 'datetime',
            'status_changed_at' => 'datetime',
        ];
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * User who changed the status
     */
    public function statusChangedBy()
    {
        return $this->belongsTo(User::class, 'status_changed_by');
    }

    /**
     * Check if user is admin (simple role check)
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is superadmin
     */
    public function isSuperAdmin()
    {
        return $this->role === 'admin' && $this->admin_type === 'superadmin';
    }

    /**
     * Check if user has admin privileges (admin or superadmin)
     */
    public function hasAdminPrivileges()
    {
        return $this->role === 'admin';
    }

    /**
     * Get admin hierarchy level (higher number = higher authority)
     */
    public function getAdminLevel()
    {
        $levels = [
            'upazila' => 1,
            'district' => 2,
            'divisional' => 3,
            'national' => 4,
            'superadmin' => 5
        ];
        return $levels[$this->admin_type] ?? 0;
    }

    /**
     * Check if this admin can manage another admin type
     */
    public function canManageAdminType($targetAdminType)
    {
        $currentLevel = $this->getAdminLevel();
        $targetLevel = (new self(['admin_type' => $targetAdminType]))->getAdminLevel();
        
        return $currentLevel > $targetLevel;
    }

    /**
     * Get allowed admin types this user can create/manage
     */
    public function getAllowedAdminTypes()
    {
        switch ($this->admin_type) {
            case 'superadmin':
                return ['superadmin', 'national', 'divisional', 'district', 'upazila'];
            case 'national':
                return ['divisional', 'district', 'upazila'];
            case 'divisional':
                return ['district', 'upazila'];
            case 'district':
                return ['upazila'];
            case 'upazila':
                return [];
            default:
                return [];
        }
    }

    /**
     * Check if this admin can access a specific area
     */
    public function canAccessArea($divisionId = null, $districtId = null, $upazilaId = null)
    {
        // Superadmin and national can access all areas
        if (in_array($this->admin_type, ['superadmin', 'national'])) {
            return true;
        }

        // Divisional admin can only access their division
        if ($this->admin_type === 'divisional') {
            return $this->division_id == $divisionId;
        }

        // District admin can only access their district
        if ($this->admin_type === 'district') {
            return $this->division_id == $divisionId && $this->district_id == $districtId;
        }

        // Upazila admin can only access their upazila
        if ($this->admin_type === 'upazila') {
            return $this->division_id == $divisionId && 
                   $this->district_id == $districtId && 
                   $this->upazila_id == $upazilaId;
        }

        return false;
    }

    /**
     * Check if user is regular user
     */
    public function isUser()
    {
        return $this->role === 'user';
    }

    /**
     * Update user status
     */
    public function updateStatus($status, $reason = null, $changedBy = null)
    {
        $this->update([
            'status' => $status,
            'status_reason' => $reason,
            'status_changed_at' => now(),
            'status_changed_by' => $changedBy,
        ]);
    }

    /**
     * Data records relationship
     */
    public function dataRecords()
    {
        return $this->hasMany(DataRecord::class);
    }

    /**
     * Edit requests relationship (as user)
     */
    public function editRequests()
    {
        return $this->hasMany(DataRecord::class)->where('is_edit_request', true);
    }

    /**
     * Edit requests relationship (as admin)
     */
    public function adminEditRequests()
    {
        return $this->hasMany(DataRecord::class, 'admin_id')->where('is_edit_request', true);
    }

    /**
     * Division relationship
     */
    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    /**
     * District relationship
     */
    public function district()
    {
        return $this->belongsTo(District::class);
    }

    /**
     * Upazila relationship
     */
    public function upazila()
    {
        return $this->belongsTo(Upazila::class);
    }

    /**
     * User who created this admin
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
