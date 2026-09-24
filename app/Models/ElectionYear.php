<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ElectionYear extends Model
{
    use HasFactory;

    protected $fillable = [
        'year',
        'title',
        'positions',
        'is_active',
    ];

    protected $casts = [
        'positions' => 'array',
        'is_active' => 'boolean',
    ];

    public function records()
    {
        return $this->hasMany(ElectoralRecord::class, 'year', 'year');
    }
}
