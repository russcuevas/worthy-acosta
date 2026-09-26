<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyRecord extends Model
{
    use HasFactory;

    protected $table = 'survey_records';

    protected $fillable = [
        'survey_period_id',
        'barangay_id',
        'candidate_name',
        'candidate_color',
        'rating',
        'sample_size',
        'methodology',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'rating' => 'float',
        'sample_size' => 'integer',
    ];

    public function period()
    {
        return $this->belongsTo(SurveyPeriod::class, 'survey_period_id');
    }

    public function barangay()
    {
        return $this->belongsTo(Barangay::class, 'barangay_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
