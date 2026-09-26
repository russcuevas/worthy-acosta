<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Issue extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'issue_type',
        'is_municipal_wide',
        'who_affected',
        'details',
        'status',
        'priority',
        'date_reported',
        'action_taken',
        'resolution_notes',
        'created_by',
    ];

    protected $casts = [
        'is_municipal_wide' => 'boolean',
        'date_reported' => 'date',
    ];

    /**
     * The barangays affected by this issue.
     */
    public function barangays()
    {
        return $this->belongsToMany(Barangay::class, 'issue_barangay');
    }

    /**
     * The user who encoded this issue.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get scope display text (e.g. "Municipal-Wide" or comma separated barangay names).
     */
    public function getScopeDisplayTextAttribute(): string
    {
        if ($this->is_municipal_wide) {
            return 'Municipal-Wide (All 18 Barangays)';
        }

        $names = $this->barangays->pluck('name')->toArray();
        if (empty($names)) {
            return 'Municipal-Wide';
        }

        return implode(', ', $names);
    }
}
