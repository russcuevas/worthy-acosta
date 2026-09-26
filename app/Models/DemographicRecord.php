<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DemographicRecord extends Model
{
    use HasFactory;

    protected $table = 'demographic_records';

    protected $fillable = [
        'barangay_id',
        'sector_id',
        'members_count',
        'notes',
        'last_updated_date',
        'created_by',
    ];

    protected $casts = [
        'members_count' => 'integer',
        'last_updated_date' => 'datetime',
    ];

    /**
     * Barangay this demographic count belongs to.
     */
    public function barangay(): BelongsTo
    {
        return $this->belongsTo(Barangay::class, 'barangay_id');
    }

    /**
     * Community sector for this demographic count.
     */
    public function sector(): BelongsTo
    {
        return $this->belongsTo(DemographicSector::class, 'sector_id');
    }

    /**
     * User who encoded/updated this demographic count.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
