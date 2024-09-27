<?php

use App\Models\Application;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->string('control_number')->nullable();
            //Arresting Office Info
            $table->string('name')->nullable();
            $table->string('rank')->nullable();
            $table->string('badge_number')->nullable();
            $table->string('unit')->nullable();
            $table->string('office_address')->nullable();
            $table->string('tel')->nullable();
            //Arrested person info
            $table->string('arrested_firstname')->nullable();
            $table->string('arrested_middlename')->nullable();
            $table->string('arrested_lastname')->nullable();
            $table->string('arrested_suffix')->nullable();
            $table->string('arrested_age')->nullable();
            $table->enum('arrested_category', [Application::ELDERLY, Application::PWD, Application::PREGNANT_WOMAN, Application::WOMAN, Application::CHILDREN])->nullable();
            $table->string('arrested_address')->nullable();
            $table->string('arrested_tel')->nullable();
            $table->string('arrested_pob')->nullable();
            $table->date('arrested_dob')->nullable();
            $table->enum('arrested_sex', [Application::MALE, Application::FEMALE])->nullable();
            $table->enum('arrested_status', [Application::SINGLE, Application::MARRIED, Application::WIDOWED, Application::SEPARATED])->nullable();
            $table->string('arrested_spouse_name')->nullable();
            $table->string('arrested_spouse_address')->nullable();
            $table->string('arrested_weight')->nullable();
            $table->string('arrested_height')->nullable();
            $table->string('arrested_eyes')->nullable();
            $table->string('arrested_hair')->nullable();
            $table->string('arrested_complexion')->nullable();
            $table->string('arrested_occupation')->nullable();
            $table->string('arrested_nationality')->nullable();
            $table->string('arrested_tribe')->nullable();
            $table->string('arrested_language')->nullable();
            $table->string('arrested_educ_attainment')->nullable();
            $table->string('arrested_school_name')->nullable();
            $table->string('arrested_school_address')->nullable();
            $table->string('arrested_marks')->nullable();
            $table->string('arrested_location_marks')->nullable();
            $table->string('arrested_defect')->nullable();
            //Warrant Arrest
            $table->string('who')->nullable();
            $table->dateTime('when')->nullable();
            $table->string('where')->nullable();
            $table->string('what')->nullable();
            $table->text('how')->nullable();
            $table->text('why')->nullable();
            $table->text('other_details')->nullable();
            //Warrantless Arrest
            $table->string('is_informed_of_right')->nullable();
            $table->text('mental_condition')->nullable();
            $table->text('physical_condition')->nullable();
            //Request Details
            $table->text('extension_reason')->nullable();
            $table->text('reason_narration')->nullable();
            //Remarks
            $table->text('approved_remarks')->nullable();
            $table->dateTime('approved_date')->nullable();
            $table->text('disapproved_remarks')->nullable();
            $table->dateTime('disapproved_date')->nullable();
            $table->text('endorsed_remarks')->nullable();
            $table->dateTime('endorsed_date')->nullable();
            $table->dateTime('final_resolution')->nullable();
            //
            $table->boolean('is_extension')->default(false);
            $table->boolean('expiration_notified')->default(false);
            $table->dateTime('detention_expiration')->nullable();
            $table->dateTime('date_submitted')->nullable();
            $table->dateTime('posted_date')->nullable();
            $table->enum('status', [Application::DRAFT, Application::AVAILABLE, Application::DISAPPROVED, Application::ENDORSING, Application::VOTING, Application::APPROVED, Application::EXPIRED])->default(Application::AVAILABLE);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('applications');
    }
}
