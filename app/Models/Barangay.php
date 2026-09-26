<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barangay extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'pin_x',
        'pin_y',
        'is_active',
    ];

    protected $casts = [
        'pin_x' => 'float',
        'pin_y' => 'float',
        'is_active' => 'boolean',
    ];

    /**
     * Get electoral records for this barangay.
     */
    public function electoralRecords()
    {
        return $this->hasMany(ElectoralRecord::class, 'barangay_id', 'id');
    }

    /**
     * Get assistance records for this barangay.
     */
    public function assistances()
    {
        return $this->hasMany(Assistance::class, 'barangay_id', 'id');
    }

    /**
     * Get events for this barangay.
     */
    public function events()
    {
        return $this->hasMany(Event::class, 'barangay_id', 'id');
    }

    /**
     * Get directory contacts for this barangay.
     */
    public function directories()
    {
        return $this->hasMany(Directory::class, 'barangay_id', 'id');
    }
}

