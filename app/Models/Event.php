<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Event extends Model
{
    use HasFactory;

    protected $table = 'events';

    protected $fillable = [
        'status',
        'attendance_status',
        'barangay_id',
        'name',
        'event_datetime',
        'venue',
        'event_type',
        'custom_type',
        'who_invited',
        'contact_info',
        'details',
        'request',
        'speech_required',
        'theme',
        'expected_attendees',
        'actual_attendees',
        'created_by',
    ];

    protected $casts = [
        'event_datetime' => 'datetime',
        'speech_required' => 'boolean',
        'expected_attendees' => 'integer',
        'actual_attendees' => 'integer',
    ];

    /**
     * Get the barangay associated with this event.
     */
    public function barangay()
    {
        return $this->belongsTo(Barangay::class, 'barangay_id', 'id');
    }

    /**
     * Get the user who encoded this event.
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
        if ($this->event_type === 'Others' && !empty($this->custom_type)) {
            return 'Others (' . $this->custom_type . ')';
        }
        
        if (in_array($this->event_type, ['Municipal', 'Barangay'])) {
            return $this->event_type . ' Event';
        }

        return $this->event_type;
    }
}
