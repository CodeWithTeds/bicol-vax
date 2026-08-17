<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            // Animal bite details (patient-fillable)
            if (! Schema::hasColumn('patients', 'animal_type')) {
                $table->string('animal_type')->nullable()->after('source'); // dog/cat/etc
            }
            if (! Schema::hasColumn('patients', 'pet_or_stray')) {
                $table->string('pet_or_stray')->nullable()->after('animal_type'); // pet/stray
            }
            if (! Schema::hasColumn('patients', 'vaccinated_animal')) {
                $table->string('vaccinated_animal')->nullable()->after('pet_or_stray'); // yes/no
            }
            if (! Schema::hasColumn('patients', 'animal_status')) {
                $table->string('animal_status')->nullable()->after('vaccinated_animal');
            }
            if (! Schema::hasColumn('patients', 'date_of_bite')) {
                $table->date('date_of_bite')->nullable()->after('animal_status');
            }
            if (! Schema::hasColumn('patients', 'washing_of_wound')) {
                $table->string('washing_of_wound')->nullable()->after('bite_type'); // yes/no
            }
            if (! Schema::hasColumn('patients', 'tandok_tambal')) {
                $table->string('tandok_tambal')->nullable()->after('washing_of_wound'); // yes/no
            }
            if (! Schema::hasColumn('patients', 'owner_name')) {
                $table->string('owner_name')->nullable()->after('tandok_tambal');
            }
            if (! Schema::hasColumn('patients', 'owner_address')) {
                $table->string('owner_address')->nullable()->after('owner_name');
            }
            // Medical history (patient-fillable)
            if (! Schema::hasColumn('patients', 'has_diabetes')) {
                $table->boolean('has_diabetes')->default(false)->after('owner_address');
            }
            if (! Schema::hasColumn('patients', 'has_cancer')) {
                $table->boolean('has_cancer')->default(false)->after('has_diabetes');
            }
            if (! Schema::hasColumn('patients', 'has_organ_transplant')) {
                $table->boolean('has_organ_transplant')->default(false)->after('has_cancer');
            }
            if (! Schema::hasColumn('patients', 'has_ckd')) {
                $table->boolean('has_ckd')->default(false)->after('has_organ_transplant');
            }
            if (! Schema::hasColumn('patients', 'has_hiv')) {
                $table->boolean('has_hiv')->default(false)->after('has_ckd');
            }
            if (! Schema::hasColumn('patients', 'taking_steroid')) {
                $table->boolean('taking_steroid')->default(false)->after('has_hiv');
            }
            if (! Schema::hasColumn('patients', 'has_riv')) {
                $table->boolean('has_riv')->default(false)->after('taking_steroid');
            }
            if (! Schema::hasColumn('patients', 'allergy')) {
                $table->string('allergy')->nullable()->after('has_riv');
            }
            if (! Schema::hasColumn('patients', 'blood_pressure')) {
                $table->string('blood_pressure')->nullable()->after('weight');
            }
            if (! Schema::hasColumn('patients', 'temperature')) {
                $table->string('temperature')->nullable()->after('blood_pressure');
            }
            if (! Schema::hasColumn('patients', 'birthday')) {
                $table->date('birthday')->nullable()->after('age');
            }
        });
    }

    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn([
                'animal_type', 'pet_or_stray', 'vaccinated_animal', 'animal_status',
                'date_of_bite', 'washing_of_wound', 'tandok_tambal', 'owner_name',
                'owner_address', 'has_diabetes', 'has_cancer', 'has_organ_transplant',
                'has_ckd', 'has_hiv', 'taking_steroid', 'has_riv', 'allergy',
                'blood_pressure', 'temperature', 'birthday',
            ]);
        });
    }
};
