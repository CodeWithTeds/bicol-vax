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
        'birthday',
        'card_no',
        'case_no',
        'contact',
        'age',
        'email',
        'profile_photo_path',
        'gender',
        'address',
        'weight',
        'blood_pressure',
        'temperature',
        'allergy',
        // Animal bite info
        'animal_type',
        'pet_or_stray',
        'vaccinated_animal',
        'animal_status',
        'date_of_bite',
        'bite_type',
        'place_of_bite',
        'washing_of_wound',
        'tandok_tambal',
        'owner_name',
        'owner_address',
        'severity',
        // Medical history
        'has_diabetes',
        'has_cancer',
        'has_organ_transplant',
        'has_ckd',
        'has_hiv',
        'taking_steroid',
        'has_riv',
        // Clinical (admin-only)
        'source',
        'cat_category',
        'treatment_required',
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
            'date_of_bite' => 'date',
            'birthday' => 'date',
            'has_diabetes' => 'boolean',
            'has_cancer' => 'boolean',
            'has_organ_transplant' => 'boolean',
            'has_ckd' => 'boolean',
            'has_hiv' => 'boolean',
            'taking_steroid' => 'boolean',
            'has_riv' => 'boolean',
        ];
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
