<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ElectoralRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'year',
        'position',
        'barangay_id',
        'barangay_name',
        'registered_voters',
        'actual_votes',
        'turnout_percentage',
        'winner_name',
        'winner_color',
        'winner_votes',
        'candidates_data',
    ];

    protected $casts = [
        'barangay_id' => 'integer',
        'registered_voters' => 'integer',
        'actual_votes' => 'integer',
        'turnout_percentage' => 'float',
        'winner_votes' => 'integer',
        'candidates_data' => 'array',
    ];

    public function electionYear()
    {
        return $this->belongsTo(ElectionYear::class, 'year', 'year');
    }

    public function barangay()
    {
        return $this->belongsTo(Barangay::class, 'barangay_id', 'id');
    }
}
