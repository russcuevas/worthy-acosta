<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyPeriod extends Model
{
    use HasFactory;

    protected $table = 'survey_periods';

    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'sample_size',
        'methodology',
        'notes',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
        'sample_size' => 'integer',
    ];

    public function records()
    {
        return $this->hasMany(SurveyRecord::class, 'survey_period_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
