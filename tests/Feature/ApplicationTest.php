<?php

namespace Tests\Feature;

use App\Events\Application\ApplicationAccepted;
use App\Events\Application\ApplicationDisapproved;
use App\Events\Application\ApplicationEndorsed;
use App\Events\Application\ApplicationSent;
use App\Events\Application\BriefNarrativeUpdated;
use App\Events\Application\ExtensionRequested;
use App\Models\Application;
use App\Models\MediaFile;
use App\Models\Vote;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class ApplicationTest extends TestCase
{
    // use RefreshDatabase;

    // /**
    //  * @return array
    //  */
    // protected function applicationData()
    // {
    //     return [
    //         'name'                      => 'police eryok',
    //         'rank'                      => 'elite',
    //         'badge_number'              => '123',
    //         'unit'                      => 'unit',
    //         'office_address'            => 'address',
    //         'tel'                       => '09101234567',
    //         'arrested_picture'          => UploadedFile::fake()->image('mugshot.jpg'),
    //         'arrested_firstname'        => 'firstname',
    //         'arrested_middlename'       => 'middle',
    //         'arrested_lastname'         => 'lastname',
    //         'arrested_suffix'           => 'suffix',
    //         'arrested_address'          => 'address',
    //         'arrested_tel'              => '09101234567',
    //         'arrested_pob'              => 'place of birth',
    //         'arrested_dob'              => new Carbon('2001-01-01'),
    //         'arrested_age'              => 28,
    //         'arrested_sex'              => Application::MALE,
    //         'arrested_status'           => Application::SINGLE,
    //         'arrested_spouse_name'      => 'spouse name',
    //         'arrested_spouse_address'   => 'spouse address',
    //         'arrested_weight'           => 50,
    //         'arrested_height'           => 165,
    //         'arrested_eyes'             => 'eyes',
    //         'arrested_hair'             => 'hair',
    //         'arrested_complexion'       => 'complexion',
    //         'arrested_occupation'       => 'occupation',
    //         'arrested_nationality'      => 'nationality',
    //         'arrested_tribe'            => 'tribe',
    //         'arrested_language'         => 'language',
    //         'arrested_educ_attainment'  => 'educational attainment',
    //         'arrested_school_name'      => 'school name',
    //         'arrested_school_address'   => 'school address',
    //         'arrested_marks'            => 'marks',
    //         'arrested_location_marks'   => 'location of marks',
    //         'arrested_defect'           => 'defect',
    //         'who'                       => 'firstname lastname suffix',
    //         'when'                      => now()->startOfDay(),
    //         'where'                     => 'where',
    //         'what'                      => 'what',
    //         'how'                       => 'how',
    //         'why'                       => 'why',
    //         'other_details'             => 'other details',
    //         'arrested_category'         => Application::ELDERLY,
    //         'is_informed_of_right'      => 'NO',
    //         'mental_condition'          => 'mental',
    //         'physical_condition'        => 'physical',
    //         'extension_reason'          => 'extension reason',
    //         'reason_narration'          => 'reason narration',
    //         'sworn_affidavit'           => UploadedFile::fake()->image('test.jpg'),
    //         'logbook'                   => UploadedFile::fake()->image('test.jpg'),
    //         'book_sheet'                => UploadedFile::fake()->image('test.jpg'),
    //         'spot_report'               => UploadedFile::fake()->image('test.jpg'),
    //         'sworn_affidavit_witness'   => UploadedFile::fake()->image('test.jpg'),
    //         'warrantless_arrest'        => UploadedFile::fake()->image('test.jpg'),
    //         'terms'                     => true
    //     ];
    // }

    // /**
    //  * @param  $userId
    //  * @param  $folderName
    //  * @param  $filename
    //  * @return
    //  */
    // protected function getStorageUrl($userId,$folderName,$filename)
    // {
    //     return $userId.'/'.$folderName.'/'.$filename;
    // }

    // /** @test */
    // public function only_own_application_can_view_if_have_restrict_view_permission()
    // {
    //     $applicant = $this->loginAsCustomUser(config('atc.access.permission.restrict_view'));

    //     $notOwnApplication = Application::factory()->create([
    //         'user_id' => 1,
    //         'when' => now(),
    //         'status' => Application::AVAILABLE
    //     ]);

    //     $application = Application::factory()->create([
    //         'user_id' => $applicant->id,
    //         'when'    => now(),
    //         'status' => Application::AVAILABLE
    //     ]);

    //     $response = $this->get('/application');

    //     $getApplications = $response->getData();

    //     $this->assertEquals(1,count($getApplications));
    // }

    // /** @test */
    // public function able_to_search_archive_application()
    // {
    //     $applicant = $this->loginAsCustomUser(config('atc.access.permission.restrict_view'));

    //     config('atc.access.days_of_detention',14);
    //     config('atc.access.days_of_detention_extension',10);

    //     Application::factory()->create([
    //         'user_id' => $applicant->id,
    //         'status'  => Application::APPROVED,
    //         'when'    => now()
    //     ]);

    //     Application::factory()->create([
    //         'user_id' => $applicant->id,
    //         'status'  => Application::APPROVED,
    //         'when'    => now()->subDays(16)
    //     ]);

    //     Application::factory()->create([
    //         'user_id' => $applicant->id,
    //         'status'  => Application::ENDORSING,
    //         'when'    => now(),
    //     ]);

    //     Application::factory()->create([
    //         'user_id' => $applicant->id,
    //         'status'  => Application::APPROVED,
    //         'final_resolution' => 'wew',
    //         'when'    => now()
    //     ]);

    //     Application::factory()->create([
    //         'user_id' => $applicant->id,
    //         'status'  => Application::EXPIRED,
    //         'when'    => now()
    //     ]);

    //     $response = $this->get('/application/archive');

    //     $getApplications = $response->getData();

    //     $this->assertEquals(2,count($getApplications));
    // }

    // /** @test */
    // public function able_to_search_application_by_control_number()
    // {
    //     $applicant = $this->loginAsCustomUser(config('atc.access.permission.restrict_view'));

    //     Application::factory()->create([
    //         'user_id' => $applicant->id,
    //         'control_number' => 'ATC01',
    //         'when'    => now()
    //     ]);

    //     Application::factory()->create([
    //         'user_id' => $applicant->id,
    //         'control_number' => 'ATC02',
    //         'when'    => now()
    //     ]);

    //     $response = $this->get('/application?control_number=ATC01');

    //     $getApplications = $response->getData();

    //     $this->assertEquals(1,count($getApplications));
    // }

    // /** @test */
    // public function can_only_view_own_application()
    // {
    //     $applicant = $this->loginAsCustomUser(config('atc.access.permission.restrict_view'));

    //     $notOwnApplication = Application::factory()->create([
    //         'user_id' => 1,
    //         'when'    => now()
    //     ]);

    //     $application = Application::factory()->create([
    //         'user_id' => $applicant->id,
    //         'when'    => now()
    //     ]);

    //     $response = $this->get('/application/'.$application->id);

    //     $response->assertOk();

    //     $response = $this->get('/application/'.$notOwnApplication->id);

    //     $response->assertStatus(404);
    // }

    // /** @test */
    // public function only_own_drafted_application_can_view_if_have_restrict_view_permission()
    // {
    //     $applicant = $this->loginAsCustomUser(config('atc.access.permission.restrict_view'));

    //     $notOwnApplication = Application::factory()->create([
    //         'user_id' => 1,
    //         'status'  => Application::DRAFT,
    //         'when'    => now()
    //     ]);

    //     $application = Application::factory()->create([
    //         'user_id' => $applicant->id,
    //         'status'  => Application::DRAFT,
    //         'when'    => now()
    //     ]);

    //     $response = $this->get('/application?with_draft=yes');

    //     $getApplications = $response->getData();

    //     $this->assertEquals(1,count($getApplications));
    // }

    // /** @test */
    // public function can_view_all_applications_if_dont_have_restrict_view_permission()
    // {
    //     $admin = $this->loginAsCustomUser('none');

    //     $application = Application::factory()->create([
    //         'user_id' => $admin->id,
    //         'when'    => now()
    //     ]);

    //     $notOwnApplication = Application::factory()->create([
    //         'user_id' => 2,
    //         'when'    => now()
    //     ]);

    //     $response = $this->get('/application');

    //     $getApplications = $response->getData();

    //     $this->assertEquals(2,count($getApplications));
    // }

    // /** @test */
    // public function drafts_cant_be_viewed_by_other_users()
    // {
    //     $admin = $this->loginAsCustomUser(config('atc.access.permission.approve_application'));

    //     $notOwnApplication = Application::factory()->create([
    //         'user_id' => 2,
    //         'status'  => Application::DRAFT,
    //         'when'    => now()
    //     ]);

    //     $response = $this->get('/application');

    //     $getApplications = $response->getData();

    //     $this->assertEquals(0,count($getApplications));
    // }

    // /** @test */
    // public function only_send_application_permission_can_send_application()
    // {
    //     $applicant = $this->loginAsCustomUser(config('atc.access.permission.send_application'));

    //     $response = $this->post('/application',$this->applicationData());

    //     $response->assertOk();

    //     $application = $applicant->applications[0];

    //     $this->assertDatabaseHas('applications',[
    //         'name'                      => 'police eryok',
    //         'rank'                      => 'elite',
    //         'badge_number'              => '123',
    //         'unit'                      => 'unit',
    //         'office_address'            => 'address',
    //         'tel'                       => '09101234567',
    //         'arrested_firstname'        => 'firstname',
    //         'arrested_middlename'       => 'middle',
    //         'arrested_lastname'         => 'lastname',
    //         'arrested_suffix'           => 'suffix',
    //         'arrested_address'          => 'address',
    //         'arrested_tel'              => '09101234567',
    //         'arrested_pob'              => 'place of birth',
    //         'arrested_dob'              => new Carbon('2001-01-01'),
    //         'arrested_age'              => 28,
    //         'arrested_sex'              => Application::MALE,
    //         'arrested_status'           => Application::SINGLE,
    //         'arrested_spouse_name'      => 'spouse name',
    //         'arrested_spouse_address'   => 'spouse address',
    //         'arrested_weight'           => 50,
    //         'arrested_height'           => 165,
    //         'arrested_eyes'             => 'eyes',
    //         'arrested_hair'             => 'hair',
    //         'arrested_complexion'       => 'complexion',
    //         'arrested_occupation'       => 'occupation',
    //         'arrested_nationality'      => 'nationality',
    //         'arrested_tribe'            => 'tribe',
    //         'arrested_language'         => 'language',
    //         'arrested_educ_attainment'  => 'educational attainment',
    //         'arrested_school_name'      => 'school name',
    //         'arrested_school_address'   => 'school address',
    //         'arrested_marks'            => 'marks',
    //         'arrested_location_marks'   => 'location of marks',
    //         'arrested_defect'           => 'defect',
    //         'who'                       => 'firstname lastname suffix',
    //         'when'                      => now()->startOfDay(),
    //         'where'                     => 'where',
    //         'what'                      => 'what',
    //         'how'                       => 'how',
    //         'other_details'             => 'other details',
    //         'arrested_category'         => Application::ELDERLY,
    //         'is_informed_of_right'      => 'NO',
    //         'mental_condition'          => 'mental',
    //         'physical_condition'        => 'physical',
    //         'extension_reason'          => 'extension reason',
    //         'reason_narration'          => 'reason narration',
    //         'arrested_picture'          => $this->getStorageUrl($applicant->id,$application->control_number.'/mugshot','mugshot.jpg'),
    //         'sworn_affidavit'           => $this->getStorageUrl($applicant->id,$application->control_number.'/files',$application->control_number.'-Sworn Affidavit.jpg'),
    //         'logbook'                   => $this->getStorageUrl($applicant->id,$application->control_number.'/files',$application->control_number.'-Logbook.jpg'),
    //         'book_sheet'                => $this->getStorageUrl($applicant->id,$application->control_number.'/files',$application->control_number.'-Book Sheet.jpg'),
    //         'spot_report'               => $this->getStorageUrl($applicant->id,$application->control_number.'/files',$application->control_number.'-Spot Report.jpg'),
    //         'sworn_affidavit_witness'   => $this->getStorageUrl($applicant->id,$application->control_number.'/files',$application->control_number.'-Sworn Affidavit Witness.jpg'),
    //         'warrantless_arrest'        => $this->getStorageUrl($applicant->id,$application->control_number.'/files',$application->control_number.'-Warrantless Arrest.jpg'),
    //     ]);
    // }

    // /** @test */
    // public function check_control_number_sequence()
    // {
    //     Carbon::setTestNow(Carbon::create(2001, 5, 21,11,11));

    //     $applicant = $this->loginAsCustomUser(config('atc.access.permission.send_application'));

    //     $response = $this->post('/application',$this->applicationData());

    //     $response->assertOk();

    //     $application = $applicant->applications[0];

    //     $this->assertEquals('ATC01FD210520016F',$application->control_number);
    // }

    // /** @test */
    // public function create_narrative_history_after_submitting_application()
    // {
    //     $applicant = $this->loginAsCustomUser(config('atc.access.permission.send_application'));

    //     $response = $this->post('/application',$this->applicationData());

    //     $response->assertOk();

    //     $this->assertDatabaseHas('brief_narrative_histories',[
    //         'narrative' => 'reason narration',
    //     ]);
    // }

    // /** @test */
    // public function send_application_with_support_documents()
    // {
    //     $applicant = $this->loginAsCustomUser(config('atc.access.permission.send_application'));

    //     $file1 = UploadedFile::fake()->image('support_1.jpg');
    //     $file2 = UploadedFile::fake()->image('support_2.jpg');

    //     $files = [
    //         'support_documents' => [$file1,$file2],
    //     ];

    //     $data = array_merge($this->applicationData(),$files);
    //     $response = $this->post('/application',$data);

    //     $response->assertOk();

    //     $this->assertDatabaseHas('applications',[
    //         'name'                      => 'police eryok',
    //         'rank'                      => 'elite',
    //         'badge_number'              => '123',
    //         'unit'                      => 'unit',
    //         'office_address'            => 'address',
    //         'tel'                       => '09101234567',
    //         'arrested_firstname'        => 'firstname',
    //         'arrested_middlename'       => 'middle',
    //         'arrested_lastname'         => 'lastname',
    //         'arrested_suffix'           => 'suffix',
    //         'arrested_address'          => 'address',
    //         'arrested_tel'              => '09101234567',
    //         'arrested_pob'              => 'place of birth',
    //         'arrested_dob'              => new Carbon('2001-01-01'),
    //         'arrested_age'              => 28,
    //         'arrested_sex'              => Application::MALE,
    //         'arrested_status'           => Application::SINGLE,
    //         'arrested_spouse_name'      => 'spouse name',
    //         'arrested_spouse_address'   => 'spouse address',
    //         'arrested_weight'           => 50,
    //         'arrested_height'           => 165,
    //         'arrested_eyes'             => 'eyes',
    //         'arrested_hair'             => 'hair',
    //         'arrested_complexion'       => 'complexion',
    //         'arrested_occupation'       => 'occupation',
    //         'arrested_nationality'      => 'nationality',
    //         'arrested_tribe'            => 'tribe',
    //         'arrested_language'         => 'language',
    //         'arrested_educ_attainment'  => 'educational attainment',
    //         'arrested_school_name'      => 'school name',
    //         'arrested_school_address'   => 'school address',
    //         'arrested_marks'            => 'marks',
    //         'arrested_location_marks'   => 'location of marks',
    //         'arrested_defect'           => 'defect',
    //         'who'                       => 'firstname lastname suffix',
    //         'when'                      => now()->startOfDay(),
    //         'where'                     => 'where',
    //         'what'                      => 'what',
    //         'how'                       => 'how',
    //         'other_details'             => 'other details',
    //         'arrested_category'         => Application::ELDERLY,
    //         'is_informed_of_right'      => 'NO',
    //         'mental_condition'          => 'mental',
    //         'physical_condition'        => 'physical',
    //         'extension_reason'          => 'extension reason',
    //         'reason_narration'          => 'reason narration',
    //     ]);

    //     $this->assertDatabaseHas('media_files',[
    //         'file_name' => 'support_1.jpg'
    //     ]);

    //     $this->assertDatabaseHas('media_files',[
    //         'file_name' => 'support_2.jpg'
    //     ]);
    // }

    // /** @test */
    // public function can_update_application_with_update_application_permission()
    // {
    //     $applicant = $this->loginAsCustomUser(config('atc.access.permission.update_application'));

    //     $application = Application::factory()->create([
    //         'user_id'           => $applicant->id,
    //         'control_number'    => 'ATC-001',
    //         'status'            => Application::AVAILABLE,
    //         'sworn_affidavit'   => $this->getStorageUrl($applicant->id,'files','ATC-001-Sworn Affidavit.jpg'),
    //         'reason_narration'  => 'somewhat error'
    //     ]);

    //     $response = $this->post('/application/'.$application->id.'/update',$this->applicationData());

    //     $response->assertOk();

    //     $this->assertDatabaseHas('applications', [
    //         'id'                        => $application->id,
    //         'name'                      => 'police eryok',
    //         'rank'                      => 'elite',
    //         'badge_number'              => '123',
    //         'unit'                      => 'unit',
    //         'office_address'            => 'address',
    //         'tel'                       => '09101234567',
    //         'arrested_firstname'        => 'firstname',
    //         'arrested_middlename'       => 'middle',
    //         'arrested_lastname'         => 'lastname',
    //         'arrested_suffix'           => 'suffix',
    //         'arrested_address'          => 'address',
    //         'arrested_tel'              => '09101234567',
    //         'arrested_pob'              => 'place of birth',
    //         'arrested_dob'              => new Carbon('2001-01-01'),
    //         'arrested_age'              => 28,
    //         'arrested_sex'              => Application::MALE,
    //         'arrested_status'           => Application::SINGLE,
    //         'arrested_spouse_name'      => 'spouse name',
    //         'arrested_spouse_address'   => 'spouse address',
    //         'arrested_weight'           => 50,
    //         'arrested_height'           => 165,
    //         'arrested_eyes'             => 'eyes',
    //         'arrested_hair'             => 'hair',
    //         'arrested_complexion'       => 'complexion',
    //         'arrested_occupation'       => 'occupation',
    //         'arrested_nationality'      => 'nationality',
    //         'arrested_tribe'            => 'tribe',
    //         'arrested_language'         => 'language',
    //         'arrested_educ_attainment'  => 'educational attainment',
    //         'arrested_school_name'      => 'school name',
    //         'arrested_school_address'   => 'school address',
    //         'arrested_marks'            => 'marks',
    //         'arrested_location_marks'   => 'location of marks',
    //         'arrested_defect'           => 'defect',
    //         'who'                       => 'firstname lastname suffix',
    //         'when'                      => now()->startOfDay(),
    //         'where'                     => 'where',
    //         'what'                      => 'what',
    //         'how'                       => 'how',
    //         'other_details'             => 'other details',
    //         'arrested_category'         => Application::ELDERLY,
    //         'is_informed_of_right'      => 'NO',
    //         'mental_condition'          => 'mental',
    //         'physical_condition'        => 'physical',
    //         'extension_reason'          => 'extension reason',
    //         'reason_narration'          => 'reason narration',
    //         'arrested_picture'          => $this->getStorageUrl($applicant->id,$application->control_number.'/mugshot','mugshot.jpg'),
    //         'sworn_affidavit'           => $this->getStorageUrl($applicant->id,$application->control_number.'/files',$application->control_number.'-Sworn Affidavit.jpg'),
    //         'logbook'                   => $this->getStorageUrl($applicant->id,$application->control_number.'/files',$application->control_number.'-Logbook.jpg'),
    //         'book_sheet'                => $this->getStorageUrl($applicant->id,$application->control_number.'/files',$application->control_number.'-Book Sheet.jpg'),
    //         'spot_report'               => $this->getStorageUrl($applicant->id,$application->control_number.'/files',$application->control_number.'-Spot Report.jpg'),
    //         'sworn_affidavit_witness'   => $this->getStorageUrl($applicant->id,$application->control_number.'/files',$application->control_number.'-Sworn Affidavit Witness.jpg'),
    //         'warrantless_arrest'        => $this->getStorageUrl($applicant->id,$application->control_number.'/files',$application->control_number.'-Warrantless Arrest.jpg'),
    //     ]);

    //     $this->assertDatabaseMissing('applications', [
    //         'id' => $application->id,
    //         'sworn_affidavit' => $application->sworn_affidavit,
    //         'book_sheet' => $application->book_sheet
    //     ]);

    //     $this->assertDatabaseHas('brief_narrative_histories',[
    //         'narrative' => 'reason narration',
    //     ]);
    // }

    // /** @test */
    // public function update_application_with_supporting_documents()
    // {
    //     $applicant = $this->loginAsCustomUser(config('atc.access.permission.update_application'));

    //     $application = Application::factory()->create([
    //         'user_id'           => $applicant->id,
    //         'status'            => Application::AVAILABLE,
    //         'reason_narration'  => 'somewhat error'
    //     ]);

    //     $file1 = UploadedFile::fake()->image('support_1.jpg');
    //     $file2 = UploadedFile::fake()->image('support_2.jpg');

    //     $files = [
    //         'support_documents' => [$file1,$file2],
    //     ];

    //     $data = array_merge($this->applicationData(),$files);

    //     $response = $this->post('/application/'.$application->id.'/update',$data);

    //     $response->assertOk();

    //     $this->assertDatabaseHas('media_files',[
    //         'file_name' => 'support_1.jpg'
    //     ]);

    //     $this->assertDatabaseHas('media_files',[
    //         'file_name' => 'support_2.jpg'
    //     ]);
    // }

    // /** @test */
    // public function update_application_with_additional_supporting_documents()
    // {
    //     $applicant = $this->loginAsCustomUser(config('atc.access.permission.update_application'));

    //     $application = Application::factory()->create([
    //         'user_id'           => $applicant->id,
    //         'status'            => Application::AVAILABLE,
    //         'reason_narration'  => 'somewhat error'
    //     ]);

    //     $mediaFile = MediaFile::factory()->create([
    //         'application_id' => $application->id,
    //         'file_name'      => 'test.jpg',
    //         'file_path'      => 'somewhere'
    //     ]);

    //     $file1 = UploadedFile::fake()->image('support_1.jpg');
    //     $file2 = UploadedFile::fake()->image('support_2.jpg');

    //     $files = [
    //         'support_documents'     => [$file1,$file2],
    //         'support_document_ids'  => [$mediaFile->id]
    //     ];

    //     $data = array_merge($this->applicationData(),$files);

    //     $response = $this->post('/application/'.$application->id.'/update',$data);

    //     $this->assertDatabaseHas('media_files',[
    //         'file_name' => 'test.jpg',
    //     ]);

    //     $this->assertDatabaseHas('media_files',[
    //         'file_name' => 'support_1.jpg'
    //     ]);

    //     $this->assertDatabaseHas('media_files',[
    //         'file_name' => 'support_2.jpg'
    //     ]);
    // }

    // /** @test */
    // public function update_application_with_removal_of_supporting_documents()
    // {
    //     $applicant = $this->loginAsCustomUser(config('atc.access.permission.update_application'));

    //     $application = Application::factory()->create([
    //         'user_id'           => $applicant->id,
    //         'status'            => Application::AVAILABLE,
    //         'reason_narration'  => 'somewhat error'
    //     ]);

    //     $mediaFile = MediaFile::factory()->create([
    //         'application_id' => $application->id,
    //         'file_name'      => 'test.jpg',
    //         'file_path'      => 'somewhere'
    //     ]);

    //     $this->assertDatabaseHas('media_files',[
    //         'file_name' => 'test.jpg'
    //     ]);

    //     $files = [
    //         'support_document_ids'  => []
    //     ];

    //     $data = array_merge($this->applicationData(),$files);

    //     $response = $this->post('/application/'.$application->id.'/update',$data);

    //     $this->assertDatabaseMissing('media_files',[
    //         'file_name' => 'test.jpg'
    //     ]);
    // }

    // /** @test */
    // public function update_application_with_supporting_documents_then_replace_all_with_new()
    // {
    //     $applicant = $this->loginAsCustomUser(config('atc.access.permission.update_application'));

    //     $application = Application::factory()->create([
    //         'user_id'           => $applicant->id,
    //         'status'            => Application::AVAILABLE,
    //         'reason_narration'  => 'somewhat error'
    //     ]);

    //     $mediaFile = MediaFile::factory()->create([
    //         'application_id' => $application->id,
    //         'file_name'      => 'test.jpg',
    //         'file_path'      => 'somewhere'
    //     ]);

    //     $this->assertDatabaseHas('media_files',[
    //         'file_name' => 'test.jpg'
    //     ]);

    //     $file = UploadedFile::fake()->image('support_1.jpg');

    //     $files = [
    //         'support_documents'     => [$file],
    //         'support_document_ids'  => []
    //     ];

    //     $data = array_merge($this->applicationData(),$files);

    //     $response = $this->post('/application/'.$application->id.'/update',$data);

    //     $this->assertDatabaseMissing('media_files',[
    //         'file_name' => 'test.jpg'
    //     ]);

    //     $this->assertDatabaseHas('media_files',[
    //         'file_name' => 'support_1.jpg'
    //     ]);
    // }

    // /** @test */
    // public function cant_update_application_if_user_dont_have_update_application_permission()
    // {
    //     $applicant = $this->loginAsCustomUser('none');

    //     $application = Application::factory()->create([
    //         'user_id'           => $applicant->id,
    //         'status'            => Application::AVAILABLE,
    //         'reason_narration'  => 'somewhat error'
    //     ]);

    //     $response = $this->post('/application/'.$application->id.'/update',$this->applicationData());

    //     $response->assertStatus(403);
    // }

    // /** @test */
    // public function cant_update_application_of_other_user_if_have_restrict_view_permission()
    // {
    //     $applicant = $this->loginAsCustomUser([config('atc.access.permission.update_application'),config('atc.access.permission.restrict_view')]);

    //     $notOwnApplication = Application::factory()->create([
    //         'user_id' => 1,
    //         'status'  => Application::AVAILABLE
    //     ]);

    //     $application = Application::factory()->create([
    //         'user_id' => $applicant->id,
    //         'status'  => Application::AVAILABLE
    //     ]);

    //     $response = $this->post('/application/'.$notOwnApplication->id.'/update',[
    //         'sworn_affidavit'           => UploadedFile::fake()->image('test.jpg'),
    //         'spot_report'               => UploadedFile::fake()->image('test.jpg'),
    //         'sworn_affidavit_witness'   => UploadedFile::fake()->image('test.jpg')
    //     ]);

    //     $response->assertStatus(404);
    // }

    // /** @test */
    // public function can_update_application_by_making_warrant_of_arrest_null()
    // {
    //     $applicant = $this->loginAsCustomUser(config('atc.access.permission.update_application'));

    //     $application = Application::factory()->create([
    //         'user_id' => $applicant->id,
    //         'status'  => Application::AVAILABLE
    //     ]);

    //     $response = $this->post('/application/'.$application->id.'/update',[
    //         'name'                      => 'police eryok',
    //         'rank'                      => 'elite',
    //         'badge_number'              => '123',
    //         'unit'                      => 'unit',
    //         'office_address'            => 'address',
    //         'tel'                       => '09101234567',
    //         'arrested_picture'          => UploadedFile::fake()->image('mugshot.jpg'),
    //         'arrested_firstname'        => 'firstname',
    //         'arrested_middlename'       => 'middle',
    //         'arrested_lastname'         => 'lastname',
    //         'arrested_suffix'           => 'suffix',
    //         'arrested_address'          => 'address',
    //         'arrested_tel'              => '09101234567',
    //         'arrested_pob'              => 'place of birth',
    //         'arrested_dob'              => new Carbon('2001-01-01'),
    //         'arrested_age'              => 28,
    //         'arrested_sex'              => Application::MALE,
    //         'arrested_status'           => Application::SINGLE,
    //         'arrested_spouse_name'      => 'spouse name',
    //         'arrested_spouse_address'   => 'spouse address',
    //         'arrested_weight'           => 50,
    //         'arrested_height'           => 165,
    //         'arrested_eyes'             => 'eyes',
    //         'arrested_hair'             => 'hair',
    //         'arrested_complexion'       => 'complexion',
    //         'arrested_occupation'       => 'occupation',
    //         'arrested_nationality'      => 'nationality',
    //         'arrested_tribe'            => 'tribe',
    //         'arrested_language'         => 'language',
    //         'arrested_educ_attainment'  => 'educational attainment',
    //         'arrested_school_name'      => 'school name',
    //         'arrested_school_address'   => 'school address',
    //         'arrested_marks'            => 'marks',
    //         'arrested_location_marks'   => 'location of marks',
    //         'arrested_defect'           => 'defect',
    //         'who'                       => 'firstname lastname suffix',
    //         'when'                      => now()->startOfDay()->subDays(6),
    //         'where'                     => 'where',
    //         'what'                      => 'what',
    //         'how'                       => 'how',
    //         'other_details'             => 'other details',
    //         'arrested_category'         => Application::ELDERLY,
    //         'is_informed_of_right'      => 'NO',
    //         'mental_condition'          => 'mental',
    //         'physical_condition'        => 'physical',
    //         'extension_reason'          => 'extension reason',
    //         'reason_narration'          => 'reason narration',
    //         'sworn_affidavit'           => UploadedFile::fake()->image('test.jpg'),
    //         'logbook'                   => UploadedFile::fake()->image('test.jpg'),
    //         'book_sheet'                => UploadedFile::fake()->image('test.jpg'),
    //         'spot_report'               => UploadedFile::fake()->image('test.jpg'),
    //         'sworn_affidavit_witness'   => UploadedFile::fake()->image('test.jpg'),
    //         'warrant_of_arrest'         => ''
    //     ]);

    //     $response->assertOk();

    //     $this->assertDatabaseHas('applications', [
    //         'id'                    => $application->id,
    //         'warrantless_arrest'    => null
    //     ]);
    // }

    // /** @test */
    // public function can_update_application_by_making_warrant_of_arrest_field_not_included_in_update()
    // {
    //     $applicant = $this->loginAsCustomUser(config('atc.access.permission.update_application'));

    //     $application = Application::factory()->create([
    //         'user_id'               => $applicant->id,
    //         'status'                => Application::AVAILABLE,
    //         'warrantless_arrest'    => UploadedFile::fake()->image('test.jpg'),
    //     ]);

    //     $response = $this->post('/application/'.$application->id.'/update',[
    //         'name'                      => 'police eryok',
    //         'rank'                      => 'elite',
    //         'badge_number'              => '123',
    //         'unit'                      => 'unit',
    //         'office_address'            => 'address',
    //         'tel'                       => '09101234567',
    //         'arrested_picture'          => UploadedFile::fake()->image('mugshot.jpg'),
    //         'arrested_firstname'        => 'firstname',
    //         'arrested_middlename'       => 'middle',
    //         'arrested_lastname'         => 'lastname',
    //         'arrested_suffix'           => 'suffix',
    //         'arrested_address'          => 'address',
    //         'arrested_tel'              => '09101234567',
    //         'arrested_pob'              => 'place of birth',
    //         'arrested_dob'              => new Carbon('2001-01-01'),
    //         'arrested_age'              => 28,
    //         'arrested_sex'              => Application::MALE,
    //         'arrested_status'           => Application::SINGLE,
    //         'arrested_spouse_name'      => 'spouse name',
    //         'arrested_spouse_address'   => 'spouse address',
    //         'arrested_weight'           => 50,
    //         'arrested_height'           => 165,
    //         'arrested_eyes'             => 'eyes',
    //         'arrested_hair'             => 'hair',
    //         'arrested_complexion'       => 'complexion',
    //         'arrested_occupation'       => 'occupation',
    //         'arrested_nationality'      => 'nationality',
    //         'arrested_tribe'            => 'tribe',
    //         'arrested_language'         => 'language',
    //         'arrested_educ_attainment'  => 'educational attainment',
    //         'arrested_school_name'      => 'school name',
    //         'arrested_school_address'   => 'school address',
    //         'arrested_marks'            => 'marks',
    //         'arrested_location_marks'   => 'location of marks',
    //         'arrested_defect'           => 'defect',
    //         'who'                       => 'firstname lastname suffix',
    //         'when'                      => now()->startOfDay()->subDays(6),
    //         'where'                     => 'where',
    //         'what'                      => 'what',
    //         'how'                       => 'how',
    //         'other_details'             => 'other details',
    //         'arrested_category'         => Application::ELDERLY,
    //         'is_informed_of_right'      => 'NO',
    //         'mental_condition'          => 'mental',
    //         'physical_condition'        => 'physical',
    //         'extension_reason'          => 'extension reason',
    //         'reason_narration'          => 'reason narration',
    //         'sworn_affidavit'           => UploadedFile::fake()->image('test.jpg'),
    //         'logbook'                   => UploadedFile::fake()->image('test.jpg'),
    //         'book_sheet'                => UploadedFile::fake()->image('test.jpg'),
    //         'spot_report'               => UploadedFile::fake()->image('test.jpg'),
    //         'sworn_affidavit_witness'   => UploadedFile::fake()->image('test.jpg'),
    //     ]);

    //     $response->assertOk();

    //     $this->assertDatabaseMissing('applications', [
    //         'id'                    => $application->id,
    //         'warrantless_arrest'    => null
    //     ]);
    // }

    // /** @test */
    // public function can_update_application_without_upload_fields_not_included()
    // {
    //     $applicant = $this->loginAsCustomUser(config('atc.access.permission.update_application'));

    //     $application = Application::factory()->create([
    //         'user_id'                   => $applicant->id,
    //         'status'                    => Application::AVAILABLE,
    //         'arrested_picture'          => UploadedFile::fake()->image('mugshot.jpg'),
    //         'sworn_affidavit'           => UploadedFile::fake()->image('test.jpg'),
    //         'logbook'                   => UploadedFile::fake()->image('test.jpg'),
    //         'book_sheet'                => UploadedFile::fake()->image('test.jpg'),
    //         'spot_report'               => UploadedFile::fake()->image('test.jpg'),
    //         'sworn_affidavit_witness'   => UploadedFile::fake()->image('test.jpg'),
    //     ]);

    //     $response = $this->post('/application/'.$application->id.'/update',[
    //         'name'                      => 'police eryok',
    //         'rank'                      => 'elite',
    //         'badge_number'              => '123',
    //         'unit'                      => 'unit',
    //         'office_address'            => 'address',
    //         'tel'                       => '09101234567',
    //         'arrested_firstname'        => 'firstname',
    //         'arrested_middlename'       => 'middle',
    //         'arrested_lastname'         => 'lastname',
    //         'arrested_suffix'           => 'suffix',
    //         'arrested_address'          => 'address',
    //         'arrested_tel'              => '09101234567',
    //         'arrested_pob'              => 'place of birth',
    //         'arrested_dob'              => new Carbon('2001-01-01'),
    //         'arrested_age'              => 28,
    //         'arrested_sex'              => Application::MALE,
    //         'arrested_status'           => Application::SINGLE,
    //         'arrested_spouse_name'      => 'spouse name',
    //         'arrested_spouse_address'   => 'spouse address',
    //         'arrested_weight'           => 50,
    //         'arrested_height'           => 165,
    //         'arrested_eyes'             => 'eyes',
    //         'arrested_hair'             => 'hair',
    //         'arrested_complexion'       => 'complexion',
    //         'arrested_occupation'       => 'occupation',
    //         'arrested_nationality'      => 'nationality',
    //         'arrested_tribe'            => 'tribe',
    //         'arrested_language'         => 'language',
    //         'arrested_educ_attainment'  => 'educational attainment',
    //         'arrested_school_name'      => 'school name',
    //         'arrested_school_address'   => 'school address',
    //         'arrested_marks'            => 'marks',
    //         'arrested_location_marks'   => 'location of marks',
    //         'arrested_defect'           => 'defect',
    //         'who'                       => 'firstname lastname suffix',
    //         'when'                      => now()->startOfDay()->subDays(6),
    //         'where'                     => 'where',
    //         'what'                      => 'what',
    //         'how'                       => 'how',
    //         'other_details'             => 'other details',
    //         'arrested_category'         => Application::ELDERLY,
    //         'is_informed_of_right'      => 'NO',
    //         'mental_condition'          => 'mental',
    //         'physical_condition'        => 'physical',
    //         'extension_reason'          => 'extension reason',
    //         'reason_narration'          => 'reason narration',
    //     ]);

    //     $response->assertOk();

    //     $this->assertDatabaseMissing('applications', [
    //         'id'                        => $application->id,
    //         'warrantless_arrest'        => null,
    //         'arrested_picture'          => null,
    //         'sworn_affidavit'           => null,
    //         'logbook'                   => null,
    //         'book_sheet'                => null,
    //         'spot_report'               => null,
    //         'sworn_affidavit_witness'   => null,
    //     ]);
    // }

    // /** @test */
    // public function cant_update_application_if_required_upload_fields_is_null()
    // {
    //     $applicant = $this->loginAsCustomUser(config('atc.access.permission.update_application'));

    //     $application = Application::factory()->create([
    //         'user_id'                   => $applicant->id,
    //         'status'                    => Application::AVAILABLE,
    //         'arrested_picture'          => UploadedFile::fake()->image('mugshot.jpg'),
    //         'sworn_affidavit'           => UploadedFile::fake()->image('test.jpg'),
    //         'logbook'                   => UploadedFile::fake()->image('test.jpg'),
    //         'book_sheet'                => UploadedFile::fake()->image('test.jpg'),
    //         'spot_report'               => UploadedFile::fake()->image('test.jpg'),
    //         'sworn_affidavit_witness'   => UploadedFile::fake()->image('test.jpg'),
    //     ]);

    //     $response = $this->post('/application/'.$application->id.'/update',[
    //         'name'                      => 'police eryok',
    //         'rank'                      => 'elite',
    //         'badge_number'              => '123',
    //         'unit'                      => 'unit',
    //         'office_address'            => 'address',
    //         'tel'                       => '09101234567',
    //         'arrested_firstname'        => 'firstname',
    //         'arrested_middlename'       => 'middle',
    //         'arrested_lastname'         => 'lastname',
    //         'arrested_suffix'           => 'suffix',
    //         'arrested_address'          => 'address',
    //         'arrested_tel'              => '09101234567',
    //         'arrested_pob'              => 'place of birth',
    //         'arrested_dob'              => new Carbon('2001-01-01'),
    //         'arrested_age'              => 28,
    //         'arrested_sex'              => Application::MALE,
    //         'arrested_status'           => Application::SINGLE,
    //         'arrested_spouse_name'      => 'spouse name',
    //         'arrested_spouse_address'   => 'spouse address',
    //         'arrested_weight'           => 50,
    //         'arrested_height'           => 165,
    //         'arrested_eyes'             => 'eyes',
    //         'arrested_hair'             => 'hair',
    //         'arrested_complexion'       => 'complexion',
    //         'arrested_occupation'       => 'occupation',
    //         'arrested_nationality'      => 'nationality',
    //         'arrested_tribe'            => 'tribe',
    //         'arrested_language'         => 'language',
    //         'arrested_educ_attainment'  => 'educational attainment',
    //         'arrested_school_name'      => 'school name',
    //         'arrested_school_address'   => 'school address',
    //         'arrested_marks'            => 'marks',
    //         'arrested_location_marks'   => 'location of marks',
    //         'arrested_defect'           => 'defect',
    //         'who'                       => 'firstname lastname suffix',
    //         'when'                      => now()->startOfDay()->subDays(6),
    //         'where'                     => 'where',
    //         'what'                      => 'what',
    //         'how'                       => 'how',
    //         'other_details'             => 'other details',
    //         'arrested_category'         => Application::ELDERLY,
    //         'is_informed_of_right'      => 'NO',
    //         'mental_condition'          => 'mental',
    //         'physical_condition'        => 'physical',
    //         'extension_reason'          => 'extension reason',
    //         'reason_narration'          => 'reason narration',
    //         'arrested_picture'          => '',
    //         'sworn_affidavit'           => '',
    //         'logbook'                   => '',
    //         'book_sheet'                => '',
    //         'spot_report'               => '',
    //         'sworn_affidavit_witness'   => '',
    //     ]);

    //     $response->assertStatus(302);
    // }

    // /** @test */
    // public function can_submit_draft_application()
    // {
    //     $applicant = $this->loginAsCustomUser([config('atc.access.permission.send_application'),config('atc.access.permission.update_application')]);

    //     $this->post('/application/draft',[
    //         'sworn_affidavit'           => UploadedFile::fake()->image('test.jpg'),
    //         'spot_report'               => UploadedFile::fake()->image('test.jpg'),
    //         'sworn_affidavit_witness'   => UploadedFile::fake()->image('test.jpg')
    //     ]);

    //     $application = $applicant->applications[0];

    //     $response = $this->post('/application/'.$application->id.'/update',$this->applicationData());

    //     $this->assertDatabaseHas('applications', [
    //         'id'                        => $application->id,
    //         'name'                      => 'police eryok',
    //         'rank'                      => 'elite',
    //         'badge_number'              => '123',
    //         'unit'                      => 'unit',
    //         'office_address'            => 'address',
    //         'tel'                       => '09101234567',
    //         'arrested_firstname'        => 'firstname',
    //         'arrested_middlename'       => 'middle',
    //         'arrested_lastname'         => 'lastname',
    //         'arrested_suffix'           => 'suffix',
    //         'arrested_address'          => 'address',
    //         'arrested_tel'              => '09101234567',
    //         'arrested_pob'              => 'place of birth',
    //         'arrested_dob'              => new Carbon('2001-01-01'),
    //         'arrested_age'              => 28,
    //         'arrested_sex'              => Application::MALE,
    //         'arrested_status'           => Application::SINGLE,
    //         'arrested_spouse_name'      => 'spouse name',
    //         'arrested_spouse_address'   => 'spouse address',
    //         'arrested_weight'           => 50,
    //         'arrested_height'           => 165,
    //         'arrested_eyes'             => 'eyes',
    //         'arrested_hair'             => 'hair',
    //         'arrested_complexion'       => 'complexion',
    //         'arrested_occupation'       => 'occupation',
    //         'arrested_nationality'      => 'nationality',
    //         'arrested_tribe'            => 'tribe',
    //         'arrested_language'         => 'language',
    //         'arrested_educ_attainment'  => 'educational attainment',
    //         'arrested_school_name'      => 'school name',
    //         'arrested_school_address'   => 'school address',
    //         'arrested_marks'            => 'marks',
    //         'arrested_location_marks'   => 'location of marks',
    //         'arrested_defect'           => 'defect',
    //         'who'                       => 'firstname lastname suffix',
    //         'when'                      => now()->startOfDay(),
    //         'where'                     => 'where',
    //         'what'                      => 'what',
    //         'how'                       => 'how',
    //         'other_details'             => 'other details',
    //         'arrested_category'         => Application::ELDERLY,
    //         'is_informed_of_right'      => 'NO',
    //         'mental_condition'          => 'mental',
    //         'physical_condition'        => 'physical',
    //         'extension_reason'          => 'extension reason',
    //         'reason_narration'          => 'reason narration',
    //         'status'                    => Application::AVAILABLE
    //     ]);
    // }

    // /** @test */
    // public function cant_submit_other_users_draft_application()
    // {
    //     $applicant = $this->loginAsCustomUser([config('atc.access.permission.update_application')]);

    //     $application = Application::factory()->create([
    //         'user_id' => $applicant->id + 1,
    //         'status'  => Application::DRAFT
    //     ]);

    //     $response = $this->post('/application/'.$application->id.'/update',$this->applicationData());

    //     $response->assertStatus(404);
    // }

    // /** @test */
    // public function only_send_application_permission_can_draft_application()
    // {
    //     $applicant = $this->loginAsCustomUser(config('atc.access.permission.send_application'));

    //     $response = $this->post('/application/draft',[
    //         'sworn_affidavit'           => UploadedFile::fake()->image('test.jpg'),
    //         'spot_report'               => UploadedFile::fake()->image('test.jpg'),
    //         'sworn_affidavit_witness'   => UploadedFile::fake()->image('test.jpg')
    //     ]);

    //     $response->assertOk();
    // }

    // /** @test */
    // public function can_send_draft_with_supported_documents()
    // {
    //     $applicant = $this->loginAsCustomUser(config('atc.access.permission.send_application'));

    //     $file1 = UploadedFile::fake()->image('support_1.jpg');
    //     $file2 = UploadedFile::fake()->image('support_2.jpg');

    //     $response = $this->post('/application/draft',[
    //         'sworn_affidavit'           => UploadedFile::fake()->image('test.jpg'),
    //         'spot_report'               => UploadedFile::fake()->image('test.jpg'),
    //         'sworn_affidavit_witness'   => UploadedFile::fake()->image('test.jpg'),
    //         'support_documents'         => [$file1,$file2]
    //     ]);

    //     $response->assertOk();

    //     $this->assertDatabaseHas('media_files',[
    //         'file_name' => 'support_1.jpg'
    //     ]);

    //     $this->assertDatabaseHas('media_files',[
    //         'file_name' => 'support_2.jpg'
    //     ]);
    // }

    // /** @test */
    // public function can_update_drafted_application()
    // {
    //     $applicant = $this->loginAsCustomUser(config('atc.access.permission.send_application'));

    //     $application = Application::factory()->create([
    //         'user_id' => $applicant->id,
    //         'status'  => Application::DRAFT
    //     ]);

    //     $response = $this->post('/application/'.$application->id.'/draft',$this->applicationData());

    //     $response->assertOk();

    //     $this->assertDatabaseHas('applications', [
    //         'id'                        => $application->id,
    //         'name'                      => 'police eryok',
    //         'rank'                      => 'elite',
    //         'badge_number'              => '123',
    //         'unit'                      => 'unit',
    //         'office_address'            => 'address',
    //         'tel'                       => '09101234567',
    //         'arrested_firstname'        => 'firstname',
    //         'arrested_middlename'       => 'middle',
    //         'arrested_lastname'         => 'lastname',
    //         'arrested_suffix'           => 'suffix',
    //         'arrested_address'          => 'address',
    //         'arrested_tel'              => '09101234567',
    //         'arrested_pob'              => 'place of birth',
    //         'arrested_dob'              => new Carbon('2001-01-01'),
    //         'arrested_age'              => 28,
    //         'arrested_sex'              => Application::MALE,
    //         'arrested_status'           => Application::SINGLE,
    //         'arrested_spouse_name'      => 'spouse name',
    //         'arrested_spouse_address'   => 'spouse address',
    //         'arrested_weight'           => 50,
    //         'arrested_height'           => 165,
    //         'arrested_eyes'             => 'eyes',
    //         'arrested_hair'             => 'hair',
    //         'arrested_complexion'       => 'complexion',
    //         'arrested_occupation'       => 'occupation',
    //         'arrested_nationality'      => 'nationality',
    //         'arrested_tribe'            => 'tribe',
    //         'arrested_language'         => 'language',
    //         'arrested_educ_attainment'  => 'educational attainment',
    //         'arrested_school_name'      => 'school name',
    //         'arrested_school_address'   => 'school address',
    //         'arrested_marks'            => 'marks',
    //         'arrested_location_marks'   => 'location of marks',
    //         'arrested_defect'           => 'defect',
    //         'who'                       => 'firstname lastname suffix',
    //         'when'                      => now()->startOfDay(),
    //         'where'                     => 'where',
    //         'what'                      => 'what',
    //         'how'                       => 'how',
    //         'other_details'             => 'other details',
    //         'arrested_category'         => Application::ELDERLY,
    //         'is_informed_of_right'      => 'NO',
    //         'mental_condition'          => 'mental',
    //         'physical_condition'        => 'physical',
    //         'extension_reason'          => 'extension reason',
    //         'reason_narration'          => 'reason narration',
    //     ]);

    //     $this->assertDatabaseMissing('applications', [
    //         'id' => $application->id,
    //         'sworn_affidavit' => $application->sworn_affidavit,
    //         'book_sheet' => $application->book_sheet
    //     ]);
    // }

    // /** @test */
    // public function update_draft_with_supporting_documents()
    // {
    //     $applicant = $this->loginAsCustomUser(config('atc.access.permission.send_application'));

    //     $application = Application::factory()->create([
    //         'user_id' => $applicant->id,
    //         'status'  => Application::DRAFT
    //     ]);

    //     $file1 = UploadedFile::fake()->image('support_1.jpg');
    //     $file2 = UploadedFile::fake()->image('support_2.jpg');

    //     $response = $this->post('/application/'.$application->id.'/draft',[
    //         'support_documents' => [$file1,$file2]
    //     ]);

    //     $response->assertOk();

    //     $this->assertDatabaseHas('media_files',[
    //         'file_name' => 'support_1.jpg'
    //     ]);

    //     $this->assertDatabaseHas('media_files',[
    //         'file_name' => 'support_2.jpg'
    //     ]);
    // }

    // /** @test */
    // public function update_draft_with_additional_supporting_documents()
    // {
    //     $applicant = $this->loginAsCustomUser(config('atc.access.permission.send_application'));

    //     $application = Application::factory()->create([
    //         'user_id'           => $applicant->id,
    //         'status'            => Application::DRAFT,
    //         'reason_narration'  => 'somewhat error'
    //     ]);

    //     $mediaFile = MediaFile::factory()->create([
    //         'application_id' => $application->id,
    //         'file_name'      => 'test.jpg',
    //         'file_path'      => 'somewhere'
    //     ]);

    //     $file1 = UploadedFile::fake()->image('support_1.jpg');
    //     $file2 = UploadedFile::fake()->image('support_2.jpg');

    //     $files = [
    //         'support_documents'     => [$file1,$file2],
    //         'support_document_ids'  => [$mediaFile->id]
    //     ];

    //     $data = array_merge($this->applicationData(),$files);

    //     $response = $this->post('/application/'.$application->id.'/draft',$data);

    //     $this->assertDatabaseHas('media_files',[
    //         'file_name' => 'test.jpg'
    //     ]);

    //     $this->assertDatabaseHas('media_files',[
    //         'file_name' => 'support_1.jpg'
    //     ]);

    //     $this->assertDatabaseHas('media_files',[
    //         'file_name' => 'support_2.jpg'
    //     ]);
    // }

    // /** @test */
    // public function update_draft_with_removal_of_supporting_documents()
    // {
    //     $applicant = $this->loginAsCustomUser(config('atc.access.permission.send_application'));

    //     $application = Application::factory()->create([
    //         'user_id'           => $applicant->id,
    //         'status'            => Application::DRAFT,
    //         'reason_narration'  => 'somewhat error'
    //     ]);

    //     $mediaFile = MediaFile::factory()->create([
    //         'application_id' => $application->id,
    //         'file_name'      => 'test.jpg',
    //         'file_path'      => 'somewhere'
    //     ]);

    //     $this->assertDatabaseHas('media_files',[
    //         'file_name' => 'test.jpg'
    //     ]);

    //     $files = [
    //         'support_document_ids'  => []
    //     ];

    //     $data = array_merge($this->applicationData(),$files);

    //     $response = $this->post('/application/'.$application->id.'/draft',$data);

    //     $this->assertDatabaseMissing('media_files',[
    //         'file_name' => 'test.jpg'
    //     ]);
    // }

    // /** @test */
    // public function update_draft_with_supporting_documents_then_replace_all_with_new()
    // {
    //     $applicant = $this->loginAsCustomUser(config('atc.access.permission.send_application'));

    //     $application = Application::factory()->create([
    //         'user_id'           => $applicant->id,
    //         'status'            => Application::DRAFT,
    //         'reason_narration'  => 'somewhat error'
    //     ]);

    //     $mediaFile = MediaFile::factory()->create([
    //         'application_id' => $application->id,
    //         'file_name'      => 'test.jpg',
    //         'file_path'      => 'somewhere'
    //     ]);

    //     $this->assertDatabaseHas('media_files',[
    //         'file_name' => 'test.jpg'
    //     ]);

    //     $file = UploadedFile::fake()->image('support_1.jpg');

    //     $files = [
    //         'support_documents'     => [$file],
    //         'support_document_ids'  => []
    //     ];

    //     $data = array_merge($this->applicationData(),$files);

    //     $response = $this->post('/application/'.$application->id.'/draft',$data);

    //     $this->assertDatabaseMissing('media_files',[
    //         'file_name' => 'test.jpg'
    //     ]);

    //     $this->assertDatabaseHas('media_files',[
    //         'file_name' => 'support_1.jpg'
    //     ]);
    // }

    // /** @test */
    // public function dont_fire_notification_when_saving_draft()
    // {
    //     Bus::fake();

    //     $applicant = $this->loginAsCustomUser([config('atc.access.permission.send_application'),config('atc.access.permission.update_application')]);

    //     $this->post('/application/draft',[
    //         'sworn_affidavit'           => UploadedFile::fake()->image('test.jpg'),
    //         'spot_report'               => UploadedFile::fake()->image('test.jpg'),
    //         'sworn_affidavit_witness'   => UploadedFile::fake()->image('test.jpg')
    //     ]);

    //     Bus::assertNotDispatched(\App\Jobs\SendNotificationOnUserForNewApplication::class);
    // }

    // /** @test */
    // public function cant_update_drafted_application_of_other_user()
    // {
    //     $applicant = $this->loginAsCustomUser(config('atc.access.permission.send_application'));

    //     $notOwnApplication = Application::factory()->create([
    //         'user_id' => 1,
    //         'status'  => Application::DRAFT
    //     ]);

    //     $application = Application::factory()->create([
    //         'user_id' => $applicant->id,
    //         'status'  => Application::DRAFT
    //     ]);

    //     $response = $this->post('/application/'.$notOwnApplication->id.'/draft',[
    //         'sworn_affidavit'           => UploadedFile::fake()->image('test.jpg'),
    //         'spot_report'               => UploadedFile::fake()->image('test.jpg'),
    //         'sworn_affidavit_witness'   => UploadedFile::fake()->image('test.jpg')
    //     ]);

    //     $response->assertStatus(404);
    // }

    // /** @test */
    // public function cant_update_application_as_draft_if_application_has_been_submitted()
    // {
    //     $applicant = $this->loginAsCustomUser(config('atc.access.permission.send_application'));

    //     $application = Application::factory()->create([
    //         'user_id' => $applicant->id,
    //         'status'  => Application::AVAILABLE
    //     ]);

    //     $response = $this->post('/application/'.$application->id.'/draft',[
    //         'sworn_affidavit'           => UploadedFile::fake()->image('test.jpg'),
    //         'spot_report'               => UploadedFile::fake()->image('test.jpg'),
    //         'sworn_affidavit_witness'   => UploadedFile::fake()->image('test.jpg')
    //     ]);

    //     $response->assertStatus(403);
    // }

    // /** @test */
    // public function cant_send_application_when_no_send_application_permission()
    // {
    //     $applicant = $this->loginAsCustomUser('none');

    //     $response = $this->post('/application',[
    //         'sworn_affidavit'           => UploadedFile::fake()->image('test.jpg'),
    //         'logbook'                   => UploadedFile::fake()->image('test.jpg'),
    //         'book_sheet'                => UploadedFile::fake()->image('test.jpg'),
    //         'spot_report'               => UploadedFile::fake()->image('test.jpg'),
    //         'sworn_affidavit_witness'   => UploadedFile::fake()->image('test.jpg')
    //     ]);

    //     $response->assertStatus(403);
    // }

    // /** @test */
    // public function only_edit_narrative_permission_can_edit_narrative()
    // {
    //     $secretariat = $this->loginAsCustomUser(config('atc.access.permission.edit_narrative'));

    //     $application = Application::factory()->create([
    //         'user_id' => 3,
    //         'status'  => Application::AVAILABLE,
    //         'reason_narration' => 'dummy'
    //     ]);

    //     $response = $this->patch('/application/'.$application->id.'/brief-narrative/edit',[
    //         'reason_narration' => 'new one'
    //     ]);

    //     $response->assertOk();

    //     $this->assertDatabaseHas('applications',[
    //         'reason_narration' => 'new one',
    //     ]);

    //     $this->assertDatabaseHas('brief_narrative_histories',[
    //         'narrative' => 'new one',
    //     ]);
    // }

    // /** @test */
    // public function cant_edit_narrative_without_edit_narrative_permission()
    // {
    //     $this->loginAsCustomUser('none');

    //     $application = Application::factory()->create([
    //         'user_id' => 3,
    //         'status'  => Application::AVAILABLE,
    //         'reason_narration' => 'dummy'
    //     ]);

    //     $response = $this->patch('/application/'.$application->id.'/brief-narrative/edit',[
    //         'reason_narration' => 'new one'
    //     ]);

    //     $response->assertStatus(403);
    // }

    // /** @test */
    // public function fire_event_after_sending_application()
    // {
    //     $applicant = $this->loginAsCustomUser(config('atc.access.permission.send_application'));

    //     Event::fake();

    //     $response = $this->post('/application',$this->applicationData());

    //     $response->assertOk();

    //     Event::assertDispatched(ApplicationSent::class);
    // }

    // /** @test */
    // public function fire_brief_narrative_event_after_sending_application()
    // {
    //     $applicant = $this->loginAsCustomUser(config('atc.access.permission.send_application'));

    //     Event::fake();

    //     $response = $this->post('/application',$this->applicationData());

    //     $response->assertOk();

    //     Event::assertDispatched(BriefNarrativeUpdated::class);
    // }

    // /** @test */
    // public function validate_approved_application_if_both_remarks_and_upload_files_are_null()
    // {
    //     $user = $this->loginAsCustomUser(config('atc.access.permission.approve_application'));

    //     $application = Application::factory()->create([
    //         'user_id' => 2
    //     ]);

    //     $response = $this->post('/application/'.$application->id.'/approve',[]);

    //     $response->assertStatus(302);
    // }

    // /** @test */
    // public function user_with_approve_application_permission_can_approve_application_with_only_remarks()
    // {
    //     $user = $this->loginAsCustomUser(config('atc.access.permission.approve_application'));

    //     $application = Application::factory()->create([
    //         'user_id' => 2
    //     ]);

    //     $response = $this->post('/application/'.$application->id.'/approve',[
    //         'approved_remarks' => 'approved remarks'
    //     ]);

    //     $response->assertOk();

    //     $this->assertDatabaseHas('applications', [
    //         'id' => $application->id,
    //         'status' => Application::ENDORSING,
    //         'approved_remarks' => 'approved remarks'
    //     ]);
    // }

    // /** @test */
    // public function fire_approved_application_event_after_approving_application()
    // {
    //     $applicant = $this->loginAsCustomUser(config('atc.access.permission.approve_application'));

    //     Event::fake();

    //     $application = Application::factory()->create([
    //         'user_id' => 2
    //     ]);

    //     $response = $this->post('/application/'.$application->id.'/approve',[
    //         'approved_remarks' => 'approved remarks'
    //     ]);

    //     $response->assertOk();

    //     Event::assertDispatched(ApplicationAccepted::class);
    // }

    // /** @test */
    // public function user_with_approve_application_permission_can_approve_application_with_only_upload_file()
    // {
    //     $user = $this->loginAsCustomUser(config('atc.access.permission.approve_application'));

    //     $application = Application::factory()->create([
    //         'user_id' => 2
    //     ]);

    //     $response = $this->post('/application/'.$application->id.'/approve',[
    //         'files' => [
    //             UploadedFile::fake()->image('test.jpg'),
    //             UploadedFile::fake()->image('test1.jpg')
    //         ],
    //     ]);

    //     $response->assertOk();

    //     $this->assertDatabaseHas('applications', [
    //         'id'        => $application->id,
    //         'status'    => Application::ENDORSING,
    //     ]);

    //     $this->assertDatabaseHas('media_files', [
    //         'application_id'    => $application->id,
    //         'type'              => MediaFile::APPROVED_FILES,
    //         'file_name'         => 'test.jpg',
    //     ]);

    //     $this->assertDatabaseHas('media_files', [
    //         'application_id'    => $application->id,
    //         'type'              => MediaFile::APPROVED_FILES,
    //         'file_name'         => 'test1.jpg',
    //     ]);
    // }

    // /** @test */
    // public function user_with_approve_application_permission_can_approve_application_with_remarks_and_upload_file()
    // {
    //     $user = $this->loginAsCustomUser(config('atc.access.permission.approve_application'));

    //     $application = Application::factory()->create([
    //         'user_id' => 2
    //     ]);

    //     $response = $this->post('/application/'.$application->id.'/approve',[
    //         'approved_remarks' => 'approved remarks',
    //         'files' => [
    //             UploadedFile::fake()->image('test.jpg'),
    //             UploadedFile::fake()->image('test1.jpg')
    //         ],
    //     ]);

    //     $response->assertOk();

    //     $this->assertDatabaseHas('applications', [
    //         'id'                => $application->id,
    //         'status'            => Application::ENDORSING,
    //         'approved_remarks'  => 'approved remarks'
    //     ]);

    //     $this->assertDatabaseHas('media_files', [
    //         'application_id'    => $application->id,
    //         'type'              => MediaFile::APPROVED_FILES,
    //         'file_name'         => 'test.jpg',
    //     ]);

    //     $this->assertDatabaseHas('media_files', [
    //         'application_id'    => $application->id,
    //         'type'              => MediaFile::APPROVED_FILES,
    //         'file_name'         => 'test1.jpg',
    //     ]);
    // }

    // /** @test */
    // public function user_without_approve_application_permission_cant_approve_application()
    // {
    //     $user = $this->loginAsCustomUser('none');

    //     $application = Application::factory()->create([
    //         'user_id' => 2
    //     ]);

    //     $response = $this->post('/application/'.$application->id.'/approve');

    //     $response->assertStatus(403);
    // }

    // /** @test */
    // public function only_approve_application_when_application_status_is_available()
    // {
    //     $user = $this->loginAsCustomUser(config('atc.access.permission.approve_application'));

    //     $application = Application::factory()->create([
    //         'user_id'   => 2,
    //         'status'    => Application::DISAPPROVED
    //     ]);

    //     $response = $this->post('/application/'.$application->id.'/approve');

    //     $response->assertStatus(403);
    // }

    // /** @test */
    // public function validate_disapprove_application_if_both_remarks_and_upload_files_are_null()
    // {
    //     $user = $this->loginAsCustomUser(config('atc.access.permission.disapprove_application'));

    //     $application = Application::factory()->create([
    //         'user_id' => 2,
    //         'status' => Application::ENDORSING
    //     ]);

    //     $response = $this->post('/application/'.$application->id.'/disapprove',[]);

    //     $response->assertStatus(302);
    // }

    // /** @test */
    // public function user_with_disapprove_application_permission_can_disapprove_application_with_only_remarks()
    // {
    //     $user = $this->loginAsCustomUser(config('atc.access.permission.disapprove_application'));

    //     $application = Application::factory()->create([
    //         'user_id' => 2
    //     ]);

    //     $response = $this->post('/application/'.$application->id.'/disapprove',[
    //         'disapproved_remarks' => 'disapproved remarks'
    //     ]);

    //     $response->assertOk();

    //     $this->assertDatabaseHas('applications', [
    //         'id'                    => $application->id,
    //         'disapproved_remarks'   => 'disapproved remarks',
    //         'status'                => Application::DISAPPROVED
    //     ]);
    // }

    // /** @test */
    // public function fire_disapproved_application_event_after_disapproving_application()
    // {
    //     $applicant = $this->loginAsCustomUser(config('atc.access.permission.disapprove_application'));

    //     Event::fake();

    //     $application = Application::factory()->create([
    //         'user_id' => 2
    //     ]);

    //     $response = $this->post('/application/'.$application->id.'/disapprove',[
    //         'disapproved_remarks' => 'disapproved remarks'
    //     ]);

    //     $response->assertOk();

    //     Event::assertDispatched(ApplicationDisapproved::class);
    // }

    // /** @test */
    // public function user_with_disapprove_application_permission_can_disapprove_application_with_only_upload_file()
    // {
    //     $user = $this->loginAsCustomUser(config('atc.access.permission.disapprove_application'));

    //     $application = Application::factory()->create([
    //         'user_id' => 2,
    //         'status' => Application::ENDORSING
    //     ]);

    //     $response = $this->post('/application/'.$application->id.'/disapprove',[
    //         'files' => [
    //             UploadedFile::fake()->image('test.jpg'),
    //             UploadedFile::fake()->image('test1.jpg')
    //         ],
    //     ]);

    //     $response->assertOk();

    //     $this->assertDatabaseHas('applications', [
    //         'id' => $application->id,
    //         'status' => Application::DISAPPROVED,
    //     ]);

    //     $this->assertDatabaseHas('media_files', [
    //         'application_id'    => $application->id,
    //         'type'              => MediaFile::DISAPPROVED_FILES,
    //         'file_name'         => 'test.jpg',
    //     ]);

    //     $this->assertDatabaseHas('media_files', [
    //         'application_id'    => $application->id,
    //         'type'              => MediaFile::DISAPPROVED_FILES,
    //         'file_name'         => 'test1.jpg',
    //     ]);
    // }

    // /** @test */
    // public function user_without_disapprove_application_permission_cant_disapprove_application()
    // {
    //     $user = $this->loginAsCustomUser('none');

    //     $application = Application::factory()->create([
    //         'user_id' => 2
    //     ]);

    //     $response = $this->post('/application/'.$application->id.'/disapprove');

    //     $response->assertStatus(403);
    // }

    // /** @test */
    // public function user_with_disapprove_application_permission_can_disapprove_application_with_remarks_and_upload_file()
    // {
    //     $user = $this->loginAsCustomUser(config('atc.access.permission.disapprove_application'));

    //     $application = Application::factory()->create([
    //         'user_id' => 2
    //     ]);

    //     $response = $this->post('/application/'.$application->id.'/disapprove',[
    //         'disapproved_remarks' => 'disapproved remarks',
    //         'files' => [
    //             UploadedFile::fake()->image('test.jpg'),
    //             UploadedFile::fake()->image('test1.jpg')
    //         ],
    //     ]);

    //     $response->assertOk();

    //     $this->assertDatabaseHas('applications', [
    //         'id'                => $application->id,
    //         'status'            => Application::DISAPPROVED,
    //         'disapproved_remarks'  => 'disapproved remarks'
    //     ]);

    //     $this->assertDatabaseHas('media_files', [
    //         'application_id'    => $application->id,
    //         'type'              => MediaFile::DISAPPROVED_FILES,
    //         'file_name'         => 'test.jpg',
    //     ]);

    //     $this->assertDatabaseHas('media_files', [
    //         'application_id'    => $application->id,
    //         'type'              => MediaFile::DISAPPROVED_FILES,
    //         'file_name'         => 'test1.jpg',
    //     ]);
    // }

    // /** @test */
    // public function only_disapprove_application_when_application_status_is_not_approved()
    // {
    //     $user = $this->loginAsCustomUser(config('atc.access.permission.disapprove_application'));

    //     $application = Application::factory()->create([
    //         'user_id'   => 2,
    //         'status'    => Application::APPROVED
    //     ]);

    //     $response = $this->post('/application/'.$application->id.'/disapprove');

    //     $response->assertStatus(403);
    // }

    // /** @test */
    // public function validate_endorsed_application_if_both_remarks_and_upload_files_are_null()
    // {
    //     $user = $this->loginAsCustomUser(config('atc.access.permission.endorse_application'));

    //     $application = Application::factory()->create([
    //         'user_id' => 2,
    //         'status' => Application::ENDORSING
    //     ]);

    //     $response = $this->post('/application/'.$application->id.'/endorse',[]);

    //     $response->assertStatus(302);
    // }

    // /** @test */
    // public function user_with_endorse_application_permission_can_endorse_application_with_only_remarks()
    // {
    //     $user = $this->loginAsCustomUser(config('atc.access.permission.endorse_application'));

    //     $application = Application::factory()->create([
    //         'user_id' => 2,
    //         'status' => Application::ENDORSING
    //     ]);

    //     $response = $this->post('/application/'.$application->id.'/endorse',[
    //         'endorsed_remarks' => 'endorsed remarks'
    //     ]);

    //     $response->assertOk();

    //     $this->assertDatabaseHas('applications', [
    //         'id' => $application->id,
    //         'status' => Application::VOTING,
    //         'endorsed_remarks' => 'endorsed remarks'
    //     ]);
    // }

    // /** @test */
    // public function fire_endorsed_application_event_after_endorsing_application()
    // {
    //     $applicant = $this->loginAsCustomUser(config('atc.access.permission.endorse_application'));

    //     Event::fake();

    //     $application = Application::factory()->create([
    //         'user_id' => 2,
    //         'status' => Application::ENDORSING
    //     ]);

    //     $response = $this->post('/application/'.$application->id.'/endorse',[
    //         'endorsed_remarks' => 'endorsed remarks'
    //     ]);

    //     $response->assertOk();

    //     $response->assertOk();

    //     Event::assertDispatched(ApplicationEndorsed::class);
    // }

    // /** @test */
    // public function user_with_endorse_application_permission_can_endorse_application_with_only_upload_file()
    // {
    //     $user = $this->loginAsCustomUser(config('atc.access.permission.endorse_application'));

    //     $application = Application::factory()->create([
    //         'user_id' => 2,
    //         'status' => Application::ENDORSING
    //     ]);

    //     $response = $this->post('/application/'.$application->id.'/endorse',[
    //         'files' => [
    //             UploadedFile::fake()->image('test.jpg'),
    //             UploadedFile::fake()->image('test1.jpg')
    //         ],
    //     ]);

    //     $response->assertOk();

    //     $this->assertDatabaseHas('applications', [
    //         'id' => $application->id,
    //         'status' => Application::VOTING,
    //     ]);

    //     $this->assertDatabaseHas('media_files', [
    //         'application_id'    => $application->id,
    //         'type'              => MediaFile::ENDORSED_FILES,
    //         'file_name'         => 'test.jpg',
    //     ]);

    //     $this->assertDatabaseHas('media_files', [
    //         'application_id'    => $application->id,
    //         'type'              => MediaFile::ENDORSED_FILES,
    //         'file_name'         => 'test1.jpg',
    //     ]);
    // }

    // /** @test */
    // public function user_with_endorse_application_permission_can_endorse_application_with_remarks_and_upload_file()
    // {
    //     $user = $this->loginAsCustomUser(config('atc.access.permission.endorse_application'));

    //     $application = Application::factory()->create([
    //         'user_id' => 2,
    //         'status' => Application::ENDORSING
    //     ]);

    //     $response = $this->post('/application/'.$application->id.'/endorse',[
    //         'endorsed_remarks' => 'endorsed remarks',
    //         'files' => [
    //             UploadedFile::fake()->image('test.jpg'),
    //             UploadedFile::fake()->image('test1.jpg')
    //         ],
    //     ]);

    //     $response->assertOk();

    //     $this->assertDatabaseHas('applications', [
    //         'id'                => $application->id,
    //         'status'            => Application::VOTING,
    //         'endorsed_remarks'  => 'endorsed remarks'
    //     ]);

    //     $this->assertDatabaseHas('media_files', [
    //         'application_id'    => $application->id,
    //         'type'              => MediaFile::ENDORSED_FILES,
    //         'file_name'         => 'test.jpg',
    //     ]);

    //     $this->assertDatabaseHas('media_files', [
    //         'application_id'    => $application->id,
    //         'type'              => MediaFile::ENDORSED_FILES,
    //         'file_name'         => 'test1.jpg',
    //     ]);
    // }

    // /** @test */
    // public function user_without_endorse_application_permission_cant_endorse_application()
    // {
    //     $user = $this->loginAsCustomUser('none');

    //     $application = Application::factory()->create([
    //         'user_id' => 2,
    //         'status' => Application::ENDORSING
    //     ]);

    //     $response = $this->post('/application/'.$application->id.'/endorse');

    //     $response->assertStatus(403);
    // }

    // /** @test */
    // public function only_endorse_application_when_application_status_is_endorsing()
    // {
    //     $user = $this->loginAsCustomUser(config('atc.access.permission.endorse_application'));

    //     $application = Application::factory()->create([
    //         'user_id'   => 2,
    //         'status'    => Application::DISAPPROVED
    //     ]);

    //     $response = $this->post('/application/'.$application->id.'/endorse');

    //     $response->assertStatus(403);
    // }

    // /** @test */
    // public function validate_upload_resolution_in_application()
    // {
    //     $user = $this->loginAsCustomUser(config('atc.access.permission.provide_resolution'));

    //     $application = Application::factory()->create([
    //         'user_id' => 2,
    //         'status' => Application::APPROVED
    //     ]);

    //     $response = $this->post('/application/'.$application->id.'/resolution',[]);

    //     $response->assertStatus(302);
    // }

    // /** @test */
    // public function can_upload_final_resolution_without_supporting_files()
    // {
    //     $user = $this->loginAsCustomUser(config('atc.access.permission.provide_resolution'));

    //     $application = Application::factory()->create([
    //         'user_id' => 2,
    //         'status' => Application::APPROVED
    //     ]);

    //     $response = $this->post('/application/'.$application->id.'/resolution',[
    //         'resolution_file' => UploadedFile::fake()->image('test.jpg')
    //     ]);

    //     $response->assertOk();

    //     $this->assertDatabaseMissing('applications', [
    //         'id' => $application->id,
    //         'status' => Application::APPROVED,
    //         'final_resolution' => null
    //     ]);
    // }

    // /** @test */
    // public function can_upload_final_resolution_with_supporting_files()
    // {
    //     $user = $this->loginAsCustomUser(config('atc.access.permission.provide_resolution'));

    //     $application = Application::factory()->create([
    //         'user_id' => 2,
    //         'status' => Application::APPROVED
    //     ]);

    //     $response = $this->post('/application/'.$application->id.'/resolution',[
    //         'resolution_file' => UploadedFile::fake()->image('test.jpg'),
    //         'files' => [
    //             UploadedFile::fake()->image('test.jpg'),
    //             UploadedFile::fake()->image('test1.jpg')
    //         ],
    //     ]);

    //     $response->assertOk();

    //     $this->assertDatabaseHas('media_files', [
    //         'application_id'    => $application->id,
    //         'type'              => MediaFile::RESOLUTION_FILES,
    //         'file_name'         => 'test.jpg',
    //     ]);

    //     $this->assertDatabaseHas('media_files', [
    //         'application_id'    => $application->id,
    //         'type'              => MediaFile::RESOLUTION_FILES,
    //         'file_name'         => 'test1.jpg',
    //     ]);
    // }

    // /** @test */
    // public function user_without_provide_resolution_permission_cant_upload_resolution()
    // {
    //     $user = $this->loginAsCustomUser('none');

    //     $application = Application::factory()->create([
    //         'user_id' => 2,
    //         'status' => Application::APPROVED
    //     ]);

    //     $response = $this->post('/application/'.$application->id.'/resolution');

    //     $response->assertStatus(403);
    // }

    // /** @test */
    // public function only_upload_resolution_when_application_status_is_approved()
    // {
    //     $user = $this->loginAsCustomUser(config('atc.access.permission.provide_resolution'));

    //     $application = Application::factory()->create([
    //         'user_id'   => 2,
    //         'status'    => Application::DISAPPROVED
    //     ]);

    //     $response = $this->post('/application/'.$application->id.'/resolution');

    //     $response->assertStatus(403);
    // }

    // /** @test */
    // public function display_final_resolution_and_supporting_documents_through_qr()
    // {
    //     $application = Application::factory()->create([
    //         'user_id'           => 2,
    //         'control_number'    => 'ATC00000001',
    //         'status'            => Application::APPROVED,
    //         'final_resolution'  => 'somewhere'
    //     ]);

    //     $mediaFile = MediaFile::factory()->create([
    //         'application_id'    => $application->id,
    //         'type'              => MediaFile::RESOLUTION_FILES,
    //         'file_name'         => 'test.jpg',
    //         'file_path'         => 'somewhere'
    //     ]);

    //     $response = $this->get('/public/resolution/'.$application->control_number);

    //     $response->assertOk();
    // }

    // /** @test */
    // public function user_with_send_application_permission_can_extend_application()
    // {
    //     $user = $this->loginAsCustomUser(config('atc.access.permission.send_application'));

    //     config(['atc.access.days_remaining' => 3]);

    //     $application = Application::factory()->create([
    //         'user_id' => 2,
    //         'status' => Application::APPROVED,
    //         'final_resolution' => 'wew',
    //         'when'  => now()->subDays(12)
    //     ]);

    //     $response = $this->post('/application/'.$application->id.'/extension');

    //     $response->assertOk();

    //     $this->assertDatabaseHas('applications', [
    //         'id'                        => $application->id + 1,
    //         'name'                      => $application->name,
    //         'rank'                      => $application->rank,
    //         'badge_number'              => $application->badge_number,
    //         'unit'                      => $application->unit,
    //         'office_address'            => $application->office_address,
    //         'tel'                       => $application->tel,
    //         'arrested_firstname'        => $application->arrested_firstname,
    //         'arrested_middlename'       => $application->arrested_middlename,
    //         'arrested_lastname'         => $application->arrested_lastname,
    //         'arrested_suffix'           => $application->arrested_suffix,
    //         'arrested_address'          => $application->arrested_address,
    //         'arrested_tel'              => $application->arrested_tel,
    //         'arrested_pob'              => $application->arrested_pob,
    //         'arrested_dob'              => $application->arrested_dob,
    //         'arrested_age'              => $application->arrested_age,
    //         'arrested_sex'              => $application->arrested_sex,
    //         'arrested_status'           => $application->arrested_status,
    //         'arrested_spouse_name'      => $application->arrested_spouse_name,
    //         'arrested_spouse_address'   => $application->arrested_spouse_address,
    //         'arrested_weight'           => $application->arrested_weight,
    //         'arrested_height'           => $application->arrested_height,
    //         'arrested_eyes'             => $application->arrested_eyes,
    //         'arrested_hair'             => $application->arrested_hair,
    //         'arrested_complexion'       => $application->arrested_complexion,
    //         'arrested_occupation'       => $application->arrested_occupation,
    //         'arrested_nationality'      => $application->arrested_nationality,
    //         'arrested_tribe'            => $application->arrested_tribe,
    //         'arrested_language'         => $application->arrested_language,
    //         'arrested_educ_attainment'  => $application->arrested_educ_attainment,
    //         'arrested_school_name'      => $application->arrested_school_name,
    //         'arrested_school_address'   => $application->arrested_school_address,
    //         'arrested_marks'            => $application->arrested_marks,
    //         'arrested_location_marks'   => $application->arrested_location_marks,
    //         'arrested_defect'           => $application->arrested_defect,
    //         'who'                       => $application->who,
    //         'when'                      => $application->when,
    //         'where'                     => $application->where,
    //         'what'                      => $application->what,
    //         'how'                       => $application->how,
    //         'other_details'             => $application->other_details,
    //         'arrested_category'         => $application->arrested_category,
    //         'is_informed_of_right'      => $application->is_informed_of_right,
    //         'mental_condition'          => $application->mental_condition,
    //         'physical_condition'        => $application->physical_condition,
    //         'extension_reason'          => $application->extension_reason,
    //         'reason_narration'          => $application->reason_narration,
    //         'status'                    => Application::AVAILABLE,
    //         'is_extension'              => true,
    //         'posted_date'               => null,
    //         'approved_remarks'          => null,
    //         'disapproved_remarks'       => null,
    //         'endorsed_remarks'          => null,
    //     ]);
    // }

    // /** @test */
    // public function extending_application_copy_all_media_files()
    // {
    //     $this->loginAsCustomUser(config('atc.access.permission.send_application'));

    //     config(['atc.access.days_remaining' => 14]);

    //     $file1 = UploadedFile::fake()->image('support_1.jpg');
    //     $file2 = UploadedFile::fake()->image('support_2.jpg');

    //     $files = [
    //         'support_documents' => [$file1,$file2],
    //     ];

    //     $data = array_merge($this->applicationData(),$files);

    //     $response = $this->post('/application',$data);
    //     $response->assertOk();

    //     $this->assertDatabaseHas('applications', [
    //         'id' => 1,
    //     ]);

    //     $this->post('/logout');
    //     $this->app->get('auth')->forgetGuards();

    //     $user = $this->loginAsCustomUser(config('atc.access.permission.approve_application'));

    //     $response = $this->post('/application/1/approve',[
    //         'approved_remarks' => 'approved remarks'
    //     ]);

    //     $response->assertOk();

    //     $this->post('/logout');
    //     $this->app->get('auth')->forgetGuards();

    //     $user = $this->loginAsCustomUser([
    //         config('atc.access.permission.endorse_application'),
    //         config('atc.access.permission.vote_application')
    //     ]);

    //     $response = $this->post('/application/1/endorse',[
    //         'endorsed_remarks' => 'endorsed remarks',
    //     ]);

    //     $response->assertOk();

    //     config(['atc.access.majority_count' => 1]);

    //     $response = $this->post('/application/1/vote',[
    //         'message' => 'this is a good vote!',
    //         'status'  => Vote::APPROVED
    //     ]);

    //     $response->assertOk();

    //     $this->post('/logout');
    //     $this->app->get('auth')->forgetGuards();

    //     $user = $this->loginAsCustomUser(config('atc.access.permission.provide_resolution'));

    //     $response = $this->post('/application/1/resolution',[
    //         'resolution_file' => UploadedFile::fake()->image('test.jpg'),
    //     ]);

    //     $response->assertOk();

    //     $this->post('/logout');
    //     $this->app->get('auth')->forgetGuards();

    //     $user = $this->loginAsCustomUser(config('atc.access.permission.send_application'));

    //     $response = $this->post('/application/1/extension');

    //     $response->assertOk();

    //     $this->assertDatabaseHas('applications', [
    //         'id'                        => 2,
    //         'status'                    => Application::AVAILABLE,
    //         'is_extension'              => true,
    //         'posted_date'               => null,
    //         'approved_remarks'          => null,
    //         'disapproved_remarks'       => null,
    //         'endorsed_remarks'          => null,
    //     ]);

    //     $this->assertDatabaseHas('media_files',[
    //         'application_id'    => 2,
    //         'file_name' => 'support_1.jpg'
    //     ]);

    //     $this->assertDatabaseHas('media_files',[
    //         'application_id'    => 2,
    //         'file_name' => 'support_2.jpg'
    //     ]);
    // }

    // /** @test */
    // public function application_with_approved_status_and_has_final_resolution_can_extend_application()
    // {
    //     config(['atc.access.days_remaining' => 3]);

    //     $user = $this->loginAsCustomUser(config('atc.access.permission.send_application'));

    //     $application = Application::factory()->create([
    //         'user_id' => $user->id,
    //         'status' => Application::APPROVED,
    //         'final_resolution' => 'wew',
    //         'when'  => now()->subDays(11)
    //     ]);

    //     $response = $this->post('/application/'.$application->id.'/extension');

    //     $response->assertOk();

    //     $application = Application::factory()->create([
    //         'user_id' => $user->id,
    //         'status' => Application::APPROVED,
    //         'final_resolution' => ''
    //     ]);

    //     $response = $this->post('/application/'.$application->id.'/extension');

    //     $response->assertStatus(403);

    //     $application = Application::factory()->create([
    //         'user_id' => $user->id,
    //         'status' => Application::AVAILABLE,
    //         'final_resolution' => 'sadasd'
    //     ]);

    //     $response = $this->post('/application/'.$application->id.'/extension');

    //     $response->assertStatus(403);
    // }

    // /** @test */
    // public function user_without_send_application_permission_cant_extend_application()
    // {
    //     $user = $this->loginAsCustomUser('none');

    //     $application = Application::factory()->create([
    //         'user_id' => 2,
    //         'status' => Application::APPROVED,
    //         'final_resolution' => 'wew'
    //     ]);

    //     $response = $this->post('/application/'.$application->id.'/extension');

    //     $response->assertStatus(403);
    // }

    // /** @test */
    // public function cant_extend_when_remaining_detention_days_is_more_than_the_required_day()
    // {
    //     config(['atc.access.days_remaining' => 3]);

    //     $user = $this->loginAsCustomUser(config('atc.access.permission.send_application'));

    //     $application = Application::factory()->create([
    //         'user_id' => 2,
    //         'status' => Application::APPROVED,
    //         'final_resolution' => 'wew',
    //         'when'   => now()->subDays(1)
    //     ]);

    //     $response = $this->post('/application/'.$application->id.'/extension');

    //     $response->assertStatus(422);
    // }

    // /** @test */
    // public function cant_extend_already_extended_application()
    // {
    //     config(['atc.access.days_remaining' => 3]);

    //     $user = $this->loginAsCustomUser(config('atc.access.permission.send_application'));

    //     $application = Application::factory()->create([
    //         'user_id' => 2,
    //         'status' => Application::APPROVED,
    //         'final_resolution' => 'wew',
    //         'when'   => now()->subDays(12),
    //         'is_extension' => true
    //     ]);

    //     $response = $this->post('/application/'.$application->id.'/extension');

    //     $response->assertStatus(403);
    // }

    // /** @test */
    // public function fire_extension_application_event_after_requestion_extension()
    // {
    //     config(['atc.access.days_remaining' => 3]);

    //     $applicant = $this->loginAsCustomUser(config('atc.access.permission.send_application'));

    //     Event::fake();

    //     $application = Application::factory()->create([
    //         'user_id' => $applicant->id,
    //         'status' => Application::APPROVED,
    //         'final_resolution' => 'wew',
    //         'when'   => now()->subDays(12)
    //     ]);

    //     $response = $this->post('/application/'.$application->id.'/extension');

    //     $response->assertOk();

    //     Event::assertDispatched(ExtensionRequested::class);
    // }

    // /** @test */
    // public function draft_files_to_update_draft_no_change_in_file_path_check()
    // {
    //     $applicant = $this->loginAsCustomUser(config('atc.access.permission.send_application'));

    //     $response = $this->post('/application/draft',[
    //         'arrested_picture'          => UploadedFile::fake()->image('test.jpg'),
    //         'sworn_affidavit'           => UploadedFile::fake()->image('test.jpg'),
    //         'logbook'                   => UploadedFile::fake()->image('test.jpg'),
    //         'book_sheet'                => UploadedFile::fake()->image('test.jpg'),
    //         'spot_report'               => UploadedFile::fake()->image('test.jpg'),
    //         'sworn_affidavit_witness'   => UploadedFile::fake()->image('test.jpg'),
    //         'warrantless_arrest'        => UploadedFile::fake()->image('test.jpg'),
    //     ]);

    //     $application = $applicant->applications[0];
    //     $path = $applicant->id.'/draft/'.$application->id.'/files/';
    //     $mugshot = $applicant->id.'/draft/'.$application->id.'/mugshot/';

    //     $this->assertDatabaseHas('applications',[
    //         'status'                    => Application::DRAFT,
    //         'arrested_picture'          => $mugshot.'mugshot.jpg',
    //         'sworn_affidavit'           => $path.'Sworn Affidavit.jpg',
    //         'logbook'                   => $path.'Logbook.jpg',
    //         'book_sheet'                => $path.'Book Sheet.jpg',
    //         'spot_report'               => $path.'Spot Report.jpg',
    //         'sworn_affidavit_witness'   => $path.'Sworn Affidavit Witness.jpg',
    //         'warrantless_arrest'        => $path.'Warrantless Arrest.jpg',
    //     ]);

    //     $this->post('/application/'.$application->id.'/draft',[]);

    //     $this->assertDatabaseHas('applications',[
    //         'status'                    => Application::DRAFT,
    //         'arrested_picture'          => $mugshot.'mugshot.jpg',
    //         'sworn_affidavit'           => $path.'Sworn Affidavit.jpg',
    //         'logbook'                   => $path.'Logbook.jpg',
    //         'book_sheet'                => $path.'Book Sheet.jpg',
    //         'spot_report'               => $path.'Spot Report.jpg',
    //         'sworn_affidavit_witness'   => $path.'Sworn Affidavit Witness.jpg',
    //         'warrantless_arrest'        => $path.'Warrantless Arrest.jpg',
    //     ]);
    // }

    // /** @test */
    // public function draft_files_to_update_draft_all_change_in_file_path_check()
    // {
    //     $applicant = $this->loginAsCustomUser(config('atc.access.permission.send_application'));

    //     $response = $this->post('/application/draft',[
    //         'arrested_picture'          => UploadedFile::fake()->image('test.jpg'),
    //         'sworn_affidavit'           => UploadedFile::fake()->image('test.jpg'),
    //         'logbook'                   => UploadedFile::fake()->image('test.jpg'),
    //         'book_sheet'                => UploadedFile::fake()->image('test.jpg'),
    //         'spot_report'               => UploadedFile::fake()->image('test.jpg'),
    //         'sworn_affidavit_witness'   => UploadedFile::fake()->image('test.jpg'),
    //         'warrantless_arrest'        => UploadedFile::fake()->image('test.jpg'),
    //     ]);

    //     $application = $applicant->applications[0];
    //     $path = $applicant->id.'/draft/'.$application->id.'/files/';
    //     $mugshot = $applicant->id.'/draft/'.$application->id.'/mugshot/';

    //     $this->assertDatabaseHas('applications',[
    //         'status'                    => Application::DRAFT,
    //         'arrested_picture'          => $mugshot.'mugshot.jpg',
    //         'sworn_affidavit'           => $path.'Sworn Affidavit.jpg',
    //         'logbook'                   => $path.'Logbook.jpg',
    //         'book_sheet'                => $path.'Book Sheet.jpg',
    //         'spot_report'               => $path.'Spot Report.jpg',
    //         'sworn_affidavit_witness'   => $path.'Sworn Affidavit Witness.jpg',
    //         'warrantless_arrest'        => $path.'Warrantless Arrest.jpg',
    //     ]);

    //     $this->post('/application/'.$application->id.'/draft',[
    //         'arrested_picture'          => UploadedFile::fake()->image('test.png'),
    //         'sworn_affidavit'           => UploadedFile::fake()->image('test.png'),
    //         'logbook'                   => UploadedFile::fake()->image('test.png'),
    //         'book_sheet'                => UploadedFile::fake()->image('test.png'),
    //         'spot_report'               => UploadedFile::fake()->image('test.png'),
    //         'sworn_affidavit_witness'   => UploadedFile::fake()->image('test.png'),
    //         'warrantless_arrest'        => UploadedFile::fake()->image('test.png'),
    //     ]);

    //     $this->assertDatabaseHas('applications',[
    //         'status'                    => Application::DRAFT,
    //         'arrested_picture'          => $mugshot.'mugshot.png',
    //         'sworn_affidavit'           => $path.'Sworn Affidavit.png',
    //         'logbook'                   => $path.'Logbook.png',
    //         'book_sheet'                => $path.'Book Sheet.png',
    //         'spot_report'               => $path.'Spot Report.png',
    //         'sworn_affidavit_witness'   => $path.'Sworn Affidavit Witness.png',
    //         'warrantless_arrest'        => $path.'Warrantless Arrest.png',
    //     ]);
    // }

    // /** @test */
    // public function draft_files_to_update_draft_remove_all_file_path_check()
    // {
    //     $applicant = $this->loginAsCustomUser(config('atc.access.permission.send_application'));

    //     $response = $this->post('/application/draft',[
    //         'arrested_picture'          => UploadedFile::fake()->image('test.jpg'),
    //         'sworn_affidavit'           => UploadedFile::fake()->image('test.jpg'),
    //         'logbook'                   => UploadedFile::fake()->image('test.jpg'),
    //         'book_sheet'                => UploadedFile::fake()->image('test.jpg'),
    //         'spot_report'               => UploadedFile::fake()->image('test.jpg'),
    //         'sworn_affidavit_witness'   => UploadedFile::fake()->image('test.jpg'),
    //         'warrantless_arrest'        => UploadedFile::fake()->image('test.jpg'),
    //     ]);

    //     $application = $applicant->applications[0];
    //     $path = $applicant->id.'/draft/'.$application->id.'/files/';
    //     $mugshot = $applicant->id.'/draft/'.$application->id.'/mugshot/';

    //     $this->assertDatabaseHas('applications',[
    //         'status'                    => Application::DRAFT,
    //         'arrested_picture'          => $mugshot.'mugshot.jpg',
    //         'sworn_affidavit'           => $path.'Sworn Affidavit.jpg',
    //         'logbook'                   => $path.'Logbook.jpg',
    //         'book_sheet'                => $path.'Book Sheet.jpg',
    //         'spot_report'               => $path.'Spot Report.jpg',
    //         'sworn_affidavit_witness'   => $path.'Sworn Affidavit Witness.jpg',
    //         'warrantless_arrest'        => $path.'Warrantless Arrest.jpg',
    //     ]);

    //     $this->post('/application/'.$application->id.'/draft',[
    //         'arrested_picture'          => null,
    //         'sworn_affidavit'           => null,
    //         'logbook'                   => null,
    //         'book_sheet'                => null,
    //         'spot_report'               => null,
    //         'sworn_affidavit_witness'   => null,
    //         'warrantless_arrest'        => null,
    //     ]);

    //     $this->assertDatabaseHas('applications',[
    //         'status'                    => Application::DRAFT,
    //         'arrested_picture'          => '',
    //         'sworn_affidavit'           => '',
    //         'logbook'                   => '',
    //         'book_sheet'                => '',
    //         'spot_report'               => '',
    //         'sworn_affidavit_witness'   => '',
    //         'warrantless_arrest'        => '',
    //     ]);
    // }

    // /** @test */
    // public function draft_files_to_submit_draft_no_change_in_file_path_check()
    // {
    //     $applicant = $this->loginAsCustomUser([config('atc.access.permission.send_application'),config('atc.access.permission.update_application')]);

    //     $response = $this->post('/application/draft',[
    //         'arrested_picture'          => UploadedFile::fake()->image('test.jpg'),
    //         'sworn_affidavit'           => UploadedFile::fake()->image('test.jpg'),
    //         'logbook'                   => UploadedFile::fake()->image('test.jpg'),
    //         'book_sheet'                => UploadedFile::fake()->image('test.jpg'),
    //         'spot_report'               => UploadedFile::fake()->image('test.jpg'),
    //         'sworn_affidavit_witness'   => UploadedFile::fake()->image('test.jpg'),
    //         'warrantless_arrest'        => UploadedFile::fake()->image('test.jpg'),
    //     ]);

    //     $application = $applicant->applications[0];
    //     $path = $applicant->id.'/draft/'.$application->id.'/files/';
    //     $mugshot = $applicant->id.'/draft/'.$application->id.'/mugshot/';

    //     $this->assertDatabaseHas('applications',[
    //         'status'                    => Application::DRAFT,
    //         'arrested_picture'          => $mugshot.'mugshot.jpg',
    //         'sworn_affidavit'           => $path.'Sworn Affidavit.jpg',
    //         'logbook'                   => $path.'Logbook.jpg',
    //         'book_sheet'                => $path.'Book Sheet.jpg',
    //         'spot_report'               => $path.'Spot Report.jpg',
    //         'sworn_affidavit_witness'   => $path.'Sworn Affidavit Witness.jpg',
    //         'warrantless_arrest'        => $path.'Warrantless Arrest.jpg',
    //     ]);

    //     $response = $this->post('/application/'.$application->id.'/update',[
    //         'name'                      => 'police eryok',
    //         'rank'                      => 'elite',
    //         'badge_number'              => '123',
    //         'unit'                      => 'unit',
    //         'office_address'            => 'address',
    //         'tel'                       => '09101234567',
    //         'arrested_picture'          => UploadedFile::fake()->image('mugshot.jpg'),
    //         'arrested_firstname'        => 'firstname',
    //         'arrested_middlename'       => 'middle',
    //         'arrested_lastname'         => 'lastname',
    //         'arrested_suffix'           => 'suffix',
    //         'arrested_address'          => 'address',
    //         'arrested_tel'              => '09101234567',
    //         'arrested_pob'              => 'place of birth',
    //         'arrested_dob'              => new Carbon('2001-01-01'),
    //         'arrested_age'              => 28,
    //         'arrested_sex'              => Application::MALE,
    //         'arrested_status'           => Application::SINGLE,
    //         'arrested_spouse_name'      => 'spouse name',
    //         'arrested_spouse_address'   => 'spouse address',
    //         'arrested_weight'           => 50,
    //         'arrested_height'           => 165,
    //         'arrested_eyes'             => 'eyes',
    //         'arrested_hair'             => 'hair',
    //         'arrested_complexion'       => 'complexion',
    //         'arrested_occupation'       => 'occupation',
    //         'arrested_nationality'      => 'nationality',
    //         'arrested_tribe'            => 'tribe',
    //         'arrested_language'         => 'language',
    //         'arrested_educ_attainment'  => 'educational attainment',
    //         'arrested_school_name'      => 'school name',
    //         'arrested_school_address'   => 'school address',
    //         'arrested_marks'            => 'marks',
    //         'arrested_location_marks'   => 'location of marks',
    //         'arrested_defect'           => 'defect',
    //         'who'                       => 'firstname lastname suffix',
    //         'when'                      => now()->startOfDay()->subDays(6),
    //         'where'                     => 'where',
    //         'what'                      => 'what',
    //         'how'                       => 'how',
    //         'why'                       => 'why',
    //         'other_details'             => 'other details',
    //         'arrested_category'         => Application::ELDERLY,
    //         'is_informed_of_right'      => 'NO',
    //         'mental_condition'          => 'mental',
    //         'physical_condition'        => 'physical',
    //         'extension_reason'          => 'extension reason',
    //         'reason_narration'          => 'reason narration',
    //     ]);

    //     $application = $application->fresh();
    //     $path = $applicant->id.'/'.$application->control_number.'/files/'.$application->control_number.'-';
    //     $mugshot = $applicant->id.'/'.$application->control_number.'/mugshot/';

    //     $this->assertDatabaseHas('applications',[
    //         'status'                    => Application::AVAILABLE,
    //         'arrested_picture'          => $mugshot.'mugshot.jpg',
    //         'sworn_affidavit'           => $path.'Sworn Affidavit.jpg',
    //         'logbook'                   => $path.'Logbook.jpg',
    //         'book_sheet'                => $path.'Book Sheet.jpg',
    //         'spot_report'               => $path.'Spot Report.jpg',
    //         'sworn_affidavit_witness'   => $path.'Sworn Affidavit Witness.jpg',
    //         'warrantless_arrest'        => $path.'Warrantless Arrest.jpg',
    //     ]);
    // }

    // /** @test */
    // public function draft_files_to_submit_draft_all_file_change_path_check()
    // {
    //     $applicant = $this->loginAsCustomUser([config('atc.access.permission.send_application'),config('atc.access.permission.update_application')]);

    //     $response = $this->post('/application/draft',[
    //         'arrested_picture'          => UploadedFile::fake()->image('test.jpg'),
    //         'sworn_affidavit'           => UploadedFile::fake()->image('test.jpg'),
    //         'logbook'                   => UploadedFile::fake()->image('test.jpg'),
    //         'book_sheet'                => UploadedFile::fake()->image('test.jpg'),
    //         'spot_report'               => UploadedFile::fake()->image('test.jpg'),
    //         'sworn_affidavit_witness'   => UploadedFile::fake()->image('test.jpg'),
    //         'warrantless_arrest'        => UploadedFile::fake()->image('test.jpg'),
    //     ]);

    //     $application = $applicant->applications[0];
    //     $path = $applicant->id.'/draft/'.$application->id.'/files/';
    //     $mugshot = $applicant->id.'/draft/'.$application->id.'/mugshot/';

    //     $this->assertDatabaseHas('applications',[
    //         'status'                    => Application::DRAFT,
    //         'arrested_picture'          => $mugshot.'mugshot.jpg',
    //         'sworn_affidavit'           => $path.'Sworn Affidavit.jpg',
    //         'logbook'                   => $path.'Logbook.jpg',
    //         'book_sheet'                => $path.'Book Sheet.jpg',
    //         'spot_report'               => $path.'Spot Report.jpg',
    //         'sworn_affidavit_witness'   => $path.'Sworn Affidavit Witness.jpg',
    //         'warrantless_arrest'        => $path.'Warrantless Arrest.jpg',
    //     ]);

    //     $response = $this->post('/application/'.$application->id.'/update',[
    //         'name'                      => 'police eryok',
    //         'rank'                      => 'elite',
    //         'badge_number'              => '123',
    //         'unit'                      => 'unit',
    //         'office_address'            => 'address',
    //         'tel'                       => '09101234567',
    //         'arrested_picture'          => UploadedFile::fake()->image('mugshot.jpg'),
    //         'arrested_firstname'        => 'firstname',
    //         'arrested_middlename'       => 'middle',
    //         'arrested_lastname'         => 'lastname',
    //         'arrested_suffix'           => 'suffix',
    //         'arrested_address'          => 'address',
    //         'arrested_tel'              => '09101234567',
    //         'arrested_pob'              => 'place of birth',
    //         'arrested_dob'              => new Carbon('2001-01-01'),
    //         'arrested_age'              => 28,
    //         'arrested_sex'              => Application::MALE,
    //         'arrested_status'           => Application::SINGLE,
    //         'arrested_spouse_name'      => 'spouse name',
    //         'arrested_spouse_address'   => 'spouse address',
    //         'arrested_weight'           => 50,
    //         'arrested_height'           => 165,
    //         'arrested_eyes'             => 'eyes',
    //         'arrested_hair'             => 'hair',
    //         'arrested_complexion'       => 'complexion',
    //         'arrested_occupation'       => 'occupation',
    //         'arrested_nationality'      => 'nationality',
    //         'arrested_tribe'            => 'tribe',
    //         'arrested_language'         => 'language',
    //         'arrested_educ_attainment'  => 'educational attainment',
    //         'arrested_school_name'      => 'school name',
    //         'arrested_school_address'   => 'school address',
    //         'arrested_marks'            => 'marks',
    //         'arrested_location_marks'   => 'location of marks',
    //         'arrested_defect'           => 'defect',
    //         'who'                       => 'firstname lastname suffix',
    //         'when'                      => now()->startOfDay()->subDays(6),
    //         'where'                     => 'where',
    //         'what'                      => 'what',
    //         'how'                       => 'how',
    //         'why'                       => 'why',
    //         'other_details'             => 'other details',
    //         'arrested_category'         => Application::ELDERLY,
    //         'is_informed_of_right'      => 'NO',
    //         'mental_condition'          => 'mental',
    //         'physical_condition'        => 'physical',
    //         'extension_reason'          => 'extension reason',
    //         'reason_narration'          => 'reason narration',
    //         'arrested_picture'          => UploadedFile::fake()->image('test.png'),
    //         'sworn_affidavit'           => UploadedFile::fake()->image('test.png'),
    //         'logbook'                   => UploadedFile::fake()->image('test.png'),
    //         'book_sheet'                => UploadedFile::fake()->image('test.png'),
    //         'spot_report'               => UploadedFile::fake()->image('test.png'),
    //         'sworn_affidavit_witness'   => UploadedFile::fake()->image('test.png'),
    //         'warrantless_arrest'        => UploadedFile::fake()->image('test.png'),
    //     ]);

    //     $application = $application->fresh();
    //     $path = $applicant->id.'/'.$application->control_number.'/files/'.$application->control_number.'-';
    //     $mugshot = $applicant->id.'/'.$application->control_number.'/mugshot/';

    //     $this->assertDatabaseHas('applications',[
    //         'status'                    => Application::AVAILABLE,
    //         'arrested_picture'          => $mugshot.'mugshot.png',
    //         'sworn_affidavit'           => $path.'Sworn Affidavit.png',
    //         'logbook'                   => $path.'Logbook.png',
    //         'book_sheet'                => $path.'Book Sheet.png',
    //         'spot_report'               => $path.'Spot Report.png',
    //         'sworn_affidavit_witness'   => $path.'Sworn Affidavit Witness.png',
    //         'warrantless_arrest'        => $path.'Warrantless Arrest.png',
    //     ]);
    // }

    // /** @test */
    // public function able_to_get_expired_applications()
    // {
    //     $applicant = $this->loginAsCustomUser(config('atc.access.permission.restrict_view'));

    //     Carbon::setTestNow(now());

    //     config(['atc.access.hours_remaining' => 18]);

    //     //Draft Application
    //     Application::factory()->create([
    //         'user_id' => $applicant->id,
    //         'status'  => Application::DRAFT,
    //     ]);

    //     Application::factory()->create([
    //         'user_id' => $applicant->id,
    //         'status'  => Application::AVAILABLE,
    //         'when' => now()
    //     ]);

    //     Application::factory()->create([
    //         'user_id' => $applicant->id,
    //         'status'  => Application::ENDORSING,
    //         'when' => now()
    //     ]);

    //     Application::factory()->create([
    //         'user_id' => $applicant->id,
    //         'status'  => Application::VOTING,
    //         'when' => now()
    //     ]);

    //     Application::factory()->create([
    //         'user_id' => $applicant->id,
    //         'status'  => Application::APPROVED,
    //         'final_resolution' => '',
    //         'when' => now()
    //     ]);

    //     Application::factory()->create([
    //         'user_id' => $applicant->id,
    //         'status'  => Application::APPROVED,
    //         'final_resolution' => 'wew',
    //         'when' => now()
    //     ]);

    //     Application::factory()->create([
    //         'user_id' => $applicant->id,
    //         'status'  => Application::APPROVED,
    //         'final_resolution' => 'wew',
    //         'when' => now()
    //     ]);

    //     Application::factory()->create([
    //         'user_id' => $applicant->id,
    //         'status'  => Application::DISAPPROVED,
    //         'when' => now()
    //     ]);

    //     Application::factory()->create([
    //         'user_id' => $applicant->id,
    //         'status'  => Application::DISAPPROVED,
    //         'when' => now()
    //     ]);

    //     Application::factory()->create([
    //         'user_id' => $applicant->id,
    //         'status'  => Application::EXPIRED,
    //         'when' => now()
    //     ]);

    //     //From here are Expired Applications
    //     Application::factory()->create([
    //         'user_id' => $applicant->id,
    //         'name'  => 'number 1',
    //         'status'  => Application::AVAILABLE,
    //         'when' => now()->subHours(20)
    //     ]);

    //     Application::factory()->create([
    //         'user_id' => $applicant->id,
    //         'name'  => 'number 2',
    //         'status'  => Application::ENDORSING,
    //         'when' => now()->subHours(20)
    //     ]);

    //     Application::factory()->create([
    //         'user_id' => $applicant->id,
    //         'name'  => 'number 3',
    //         'status'  => Application::VOTING,
    //         'when' => now()->subHours(20)
    //     ]);

    //     Application::factory()->create([
    //         'user_id' => $applicant->id,
    //         'name'  => 'number 4',
    //         'status'  => Application::APPROVED,
    //         'when' => now()->subHours(20)
    //     ]);

    //     $numberOfExpiredApplications = Application::expiredApplication()->count();

    //     $this->assertEquals(4,$numberOfExpiredApplications);
    // }

    // /** @test */
    // public function user_cant_extend_application_if_it_has_already_extension_application()
    // {
    //     $user = $this->loginAsCustomUser(config('atc.access.permission.send_application'));

    //     config(['atc.access.days_remaining' => 3]);

    //     $application = Application::factory()->create([
    //         'user_id' => 2,
    //         'status' => Application::APPROVED,
    //         'final_resolution' => 'wew',
    //         'when'  => now()->subDays(12)
    //     ]);

    //     Application::factory()->create([
    //         'user_id' => 2,
    //         'status' => Application::AVAILABLE,
    //         'is_extension' => true
    //     ]);

    //     $response = $this->post('/application/'.$application->id.'/extension');

    //     $response->assertStatus(403);
    // }

    // /** @test */
    // public function dashboard_application_count_for_restricted_users()
    // {
    //     $user = $this->loginAsCustomUser([config('atc.access.permission.restrict_view'),config('atc.access.permission.send_application')]);

    //     Application::factory()->create([
    //         'user_id' => $user->id,
    //         'status' => Application::AVAILABLE,
    //         'when' => now()
    //     ]);

    //     Application::factory()->create([
    //         'user_id' => 10,
    //         'status' => Application::AVAILABLE,
    //         'when' => now()
    //     ]);

    //     Application::factory()->create([
    //         'user_id' => $user->id,
    //         'status' => Application::AVAILABLE,
    //         'when' => now(),
    //         'is_extension' => true
    //     ]);

    //     Application::factory()->create([
    //         'user_id' => $user->id,
    //         'status' => Application::ENDORSING,
    //         'when' => now()
    //     ]);

    //     Application::factory()->create([
    //         'user_id' => 10,
    //         'status' => Application::ENDORSING,
    //         'when' => now()
    //     ]);

    //     Application::factory()->create([
    //         'user_id' => $user->id,
    //         'status' => Application::ENDORSING,
    //         'when' => now()
    //     ]);

    //     Application::factory()->create([
    //         'user_id' => $user->id,
    //         'status' => Application::VOTING,
    //         'when' => now()
    //     ]);

    //     Application::factory()->create([
    //         'user_id' => 10,
    //         'status' => Application::VOTING,
    //         'when' => now()
    //     ]);

    //     Application::factory()->create([
    //         'user_id' => $user->id,
    //         'status' => Application::VOTING,
    //         'when' => now()
    //     ]);

    //     Application::factory()->create([
    //         'user_id' => $user->id,
    //         'status' => Application::DISAPPROVED,
    //         'when' => now()
    //     ]);

    //     Application::factory()->create([
    //         'user_id' => 10,
    //         'status' => Application::DISAPPROVED,
    //         'when' => now()
    //     ]);

    //     Application::factory()->create([
    //         'user_id' => $user->id,
    //         'status' => Application::EXPIRED,
    //         'when' => now()
    //     ]);

    //     Application::factory()->create([
    //         'user_id' => 10,
    //         'status' => Application::EXPIRED,
    //         'when' => now()
    //     ]);

    //     $response = $this->get('/application/available/count');

    //     $getCount = $response->getContent();

    //     $this->assertEquals(2,$getCount);

    //     $response = $this->get('/application/review/count');

    //     $getCount = $response->getContent();

    //     $this->assertEquals(2,$getCount);

    //     $response = $this->get('/application/vote/count');

    //     $getCount = $response->getContent();

    //     $this->assertEquals(2,$getCount);

    //     $response = $this->get('/application/archive/count');

    //     $getCount = $response->getContent();

    //     $this->assertEquals(2,$getCount);
    // }

    // /** @test */
    // public function dashboard_application_count_for_non_restricted_users()
    // {
    //     $user = $this->loginAsCustomUser([config('atc.access.permission.send_application')]);

    //     Application::factory()->create([
    //         'user_id' => $user->id,
    //         'status' => Application::AVAILABLE,
    //         'when' => now()
    //     ]);

    //     Application::factory()->create([
    //         'user_id' => 10,
    //         'status' => Application::AVAILABLE,
    //         'when' => now()
    //     ]);

    //     Application::factory()->create([
    //         'user_id' => $user->id,
    //         'status' => Application::AVAILABLE,
    //         'when' => now(),
    //         'is_extension' => true
    //     ]);

    //     Application::factory()->create([
    //         'user_id' => $user->id,
    //         'status' => Application::ENDORSING,
    //         'when' => now()
    //     ]);

    //     Application::factory()->create([
    //         'user_id' => 10,
    //         'status' => Application::ENDORSING,
    //         'when' => now()
    //     ]);

    //     Application::factory()->create([
    //         'user_id' => $user->id,
    //         'status' => Application::ENDORSING,
    //         'when' => now()
    //     ]);

    //     Application::factory()->create([
    //         'user_id' => $user->id,
    //         'status' => Application::VOTING,
    //         'when' => now()
    //     ]);

    //     Application::factory()->create([
    //         'user_id' => 10,
    //         'status' => Application::VOTING,
    //         'when' => now()
    //     ]);

    //     Application::factory()->create([
    //         'user_id' => $user->id,
    //         'status' => Application::VOTING,
    //         'when' => now()
    //     ]);

    //     Application::factory()->create([
    //         'user_id' => $user->id,
    //         'status' => Application::DISAPPROVED,
    //         'when' => now()
    //     ]);

    //     Application::factory()->create([
    //         'user_id' => 10,
    //         'status' => Application::DISAPPROVED,
    //         'when' => now()
    //     ]);

    //     Application::factory()->create([
    //         'user_id' => $user->id,
    //         'status' => Application::EXPIRED,
    //         'when' => now()
    //     ]);

    //     Application::factory()->create([
    //         'user_id' => 10,
    //         'status' => Application::EXPIRED,
    //         'when' => now()
    //     ]);

    //     $response = $this->get('/application/available/count');

    //     $getCount = $response->getContent();

    //     $this->assertEquals(3,$getCount);

    //     $response = $this->get('/application/review/count');

    //     $getCount = $response->getContent();

    //     $this->assertEquals(3,$getCount);

    //     $response = $this->get('/application/vote/count');

    //     $getCount = $response->getContent();

    //     $this->assertEquals(3,$getCount);

    //     $response = $this->get('/application/archive/count');

    //     $getCount = $response->getContent();

    //     $this->assertEquals(4,$getCount);
    // }
}
