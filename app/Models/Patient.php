<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id',
        'full_name',
        'card_no',
        'case_no',
        'contact',
        'age',
        'email',
        'profile_photo_path',
        'gender',
        'address',
        'weight',
        'cat_category',
        'treatment_required',
        'bite_type',
        'place_of_bite',
        'source',
        'severity',
        'generic_name',
        'route',
        'brand_name',
        'dosage',
        'anti_rabies_dose',
        'anti_rabies_date',
        'tetanus_status',
        'tetanus_dose',
        'tetanus_date',
        'rabies_immunoglobulin',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'treatment_required' => 'array',
            'anti_rabies_date' => 'date',
            'tetanus_date' => 'date',
        ];
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
