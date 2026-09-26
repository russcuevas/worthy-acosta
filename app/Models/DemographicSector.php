<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class DemographicSector extends Model
{
    use HasFactory;

    protected $table = 'demographic_sectors';

    protected $fillable = [
        'name',
        'slug',
        'color',
        'description',
        'is_system',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($sector) {
            if (empty($sector->slug)) {
                $sector->slug = Str::slug($sector->name);
            }
        });
    }

    /**
     * Demographic records associated with this sector.
     */
    public function records(): HasMany
    {
        return $this->hasMany(DemographicRecord::class, 'sector_id');
    }

    /**
     * Total member count across all barangays.
     */
    public function getTotalMembersAttribute(): int
    {
        return (int) $this->records()->sum('members_count');
    }
}
