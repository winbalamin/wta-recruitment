<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('cv_applications', function (Blueprint $table) {
            $table->string('position_applied', 120)->after('name');
            $table->date('date_of_birth')->after('position_applied');
            $table->string('education_level', 40)->after('date_of_birth');
            $table->string('current_employer', 120)->nullable()->after('education_level');
            $table->string('current_job_title', 120)->nullable()->after('current_employer');
            $table->decimal('expected_salary', 12, 2)->nullable()->after('current_job_title');
            $table->text('skills')->nullable()->after('work_experience');
            $table->string('languages', 255)->nullable()->after('skills');
            $table->date('start_date')->nullable()->after('languages');
            $table->string('emergency_contact_name', 120)->after('start_date');
            $table->string('emergency_contact_relationship', 40)->after('emergency_contact_name');
            $table->string('emergency_contact_phone', 40)->after('emergency_contact_relationship');
            $table->string('portfolio_url', 255)->nullable()->after('emergency_contact_phone');
            $table->text('references')->nullable()->after('portfolio_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cv_applications', function (Blueprint $table) {
            $table->dropColumn([
                'position_applied',
                'date_of_birth',
                'education_level',
                'current_employer',
                'current_job_title',
                'expected_salary',
                'skills',
                'languages',
                'start_date',
                'emergency_contact_name',
                'emergency_contact_relationship',
                'emergency_contact_phone',
                'portfolio_url',
                'references',
            ]);
        });
    }
};
