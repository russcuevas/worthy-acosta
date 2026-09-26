<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Directory extends Model
{
    use HasFactory;

    protected $table = 'directories';

    protected $fillable = [
        'barangay_id',
        'contact_type',
        'name',
        'position',
        'contact_number',
        'other_info',
        'internal_label',
        'created_by',
        'updated_by',
    ];

    /**
     * Standard contact types
     */
    public const CONTACT_TYPES = [
        'Sectoral',
        'Barangay Officials',
        'Neighborhood Association',
        'Coordinator',
        'Leader',
        'Supporter',
        'Others',
    ];

    /**
     * Standard internal labels
     */
    public const INTERNAL_LABELS = [
        'Saint' => [
            'name' => 'Saint',
            'color' => 'blue',
            'bg' => '#EFF6FF',
            'text' => '#1D4ED8',
            'border' => '#93C5FD',
            'desc' => 'User-defined internal directory category.'
        ],
        'Sinner' => [
            'name' => 'Sinner',
            'color' => 'green',
            'bg' => '#F0FDF4',
            'text' => '#15803D',
            'border' => '#86EFAC',
            'desc' => 'User-defined internal directory category.'
        ],
        'Savable' => [
            'name' => 'Savable',
            'color' => 'yellow',
            'bg' => '#FEFCE8',
            'text' => '#A16207',
            'border' => '#FDE047',
            'desc' => 'User-defined internal directory category.'
        ],
    ];

    /**
     * Get the barangay associated with this directory record.
     */
    public function barangay()
    {
        return $this->belongsTo(Barangay::class, 'barangay_id', 'id');
    }

    /**
     * Get the user who encoded this directory record.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    /**
     * Get the user who last updated this directory record.
     */
    public function editor()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }
}
