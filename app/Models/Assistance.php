<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assistance extends Model
{
    use HasFactory;

    protected $table = 'assistances';

    protected $fillable = [
        'barangay_id',
        'type',
        'custom_type',
        'assistance_given',
        'date',
        'amount',
        'beneficiaries_count',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'date' => 'date:Y-m-d',
        'amount' => 'float',
        'beneficiaries_count' => 'integer',
    ];

    /**
     * Get the barangay associated with this assistance record.
     */
    public function barangay()
    {
        return $this->belongsTo(Barangay::class, 'barangay_id', 'id');
    }

    /**
     * Get the user who recorded this assistance.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    /**
     * Get formatted display type
     */
    public function getDisplayTypeAttribute(): string
    {
        if ($this->type === 'Others' && !empty($this->custom_type)) {
            return 'Others (' . $this->custom_type . ')';
        }
        return $this->type;
    }
}
