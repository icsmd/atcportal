<?php

namespace App\Services;

use App\Events\Application\ApplicationAccepted;
use App\Events\Application\ApplicationDisapproved;
use App\Events\Application\ApplicationEndorsed;
use App\Events\Application\ApplicationSent;
use App\Events\Application\ExtensionRequested;
use App\Events\Application\ResolutionUploaded;
use App\Exceptions\GeneralException;
use App\Models\Application;
use Cache;
use Exception;
use Illuminate\Support\Facades\DB;
use Log;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * Class ApplicationService.
 */
class ApplicationService extends BaseService
{
    /**
     * ApplicationService constructor.
     *
     * @param  Application  $application
     */
    public function __construct(Application $application)
    {
        /** @var Application model */
        $this->model = $application;
    }

    /**
     * @param  array  $data
     * @param  int  $paginate
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function search(array $data, $paginate = 15)
    {
        $result = $this->model->where('detention_expiration', '>', now())
                    ->whereNotIn('status', [Application::EXPIRED, Application::DISAPPROVED]);

        if (! empty($data['control_number'])) {
            $result = $result->where('control_number', $data['control_number']);
        }

        if (! empty($data['with_draft'])) {
            $result = $result->orWhere('status', Application::DRAFT);
        } else {
            $result = $result->where('status', '<>', Application::DRAFT);
        }

        return $result->latest('when')->get();
    }

    /**
     * @param  int  $paginate
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function archive($paginate = 15)
    {
        return $this->model->archivedApplication()->get();
        //return $this->model->archivedApplication()->paginate($paginate);
    }

    /**
     * @param  string  $type
     * @return int
     */
    public function applicationCount($type)
    {
        if ($type == 'archive') {
            return $this->model->archivedApplication()->count();
        }

        if ($type == 'available') {
            return $this->model->availableApplication()->count();
        }

        if ($type == 'review') {
            return $this->model->reviewApplication()->count();
        }

        if ($type == 'vote') {
            return $this->model->voteApplication()->count();
        }
    }

    /**
     * @param    $data
     * @return Application
     *
     * @throws GeneralException
     * @throws \Throwable
     */
    public function store($data): Application
    {
        DB::beginTransaction();

        try {
            $controlNumber = $this->model->createControlNumber();
            $application = $this->processApplication($data, $controlNumber);

            event(new ApplicationSent($application));
        } catch (Exception $e) {
            DB::rollBack();
            Log::debug($e);

            throw new GeneralException('There was a problem creating this application. Please try again.');
        }

        DB::commit();

        return $application;
    }

    /**
     * @param    $data
     * @param  Application  $application
     * @return Application
     *
     * @throws GeneralException
     * @throws \Throwable
     */
    public function update($data, Application $application): Application
    {
        DB::beginTransaction();

        try {
            $oldNarrative = $application->reason_narration;

            $this->updateApplication($data, $application);

            if ($application->reason_narration != $oldNarrative) {
                event(new BriefNarrativeUpdated($application, $data->reason_narration));
            }
        } catch (Exception $e) {
            DB::rollBack();
            Log::debug($e->getMessage());

            throw new GeneralException('There was a problem updating this application. Please try again.');
        }

        DB::commit();

        return $application;
    }

    /**
     * @param    $data
     * @param  Application  $application
     * @return Application
     *
     * @throws GeneralException
     * @throws \Throwable
     */
    public function submitDraft($data, Application $application): Application
    {
        $this->updateDraft($data, $application);
        $application = $application->fresh();

        DB::beginTransaction();

        try {
            $oldNarrative = $application->reason_narration;
            $controlNumber = $this->model->createControlNumber();
            $oldApplicationStatus = $application->status;

            $application->update([
                'control_number' => $controlNumber,
                'date_submitted' => now(),
                'status' => Application::AVAILABLE,
            ]);

            $this->moveDraftFile($application, 'arrested_picture');
            $this->moveDraftFile($application, 'sworn_affidavit');
            $this->moveDraftFile($application, 'logbook');
            $this->moveDraftFile($application, 'book_sheet');
            $this->moveDraftFile($application, 'spot_report');
            $this->moveDraftFile($application, 'sworn_affidavit_witness');
            $this->moveDraftFile($application, 'warrantless_arrest');
            $this->moveSupportingDocument($application);
            $application->clearMediaCollection('draft');

            if ($application->reason_narration != $oldNarrative) {
                event(new BriefNarrativeUpdated($application, $data->reason_narration));
            }
        } catch (Exception $e) {
            DB::rollBack();
            Log::debug($e->getMessage());

            throw new GeneralException('There was a problem submitting this draft application. Please try again.');
        }

        DB::commit();

        return $application;
    }

    /**
     * @param    $data
     * @param  Application  $application
     * @return Application
     *
     * @throws GeneralException
     * @throws \Throwable
     */
    public function updateNarrative($data, Application $application): Application
    {
        DB::beginTransaction();

        try {
            $oldNarrative = $application->reason_narration;

            $application->update([
                'reason_narration' => $data->reason_narration,
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            Log::debug($e->getMessage());

            throw new GeneralException('There was a problem updating the brief narrative. Please try again.');
        }

        DB::commit();

        return $application;
    }

    /**
     * @param    $data
     * @return Application
     *
     * @throws GeneralException
     * @throws \Throwable
     */
    public function draft($data): Application
    {
        DB::beginTransaction();

        try {
            $application = $this->processApplication($data, null, true);

            event(new ApplicationSent($application));
        } catch (Exception $e) {
            DB::rollBack();
            Log::debug($e->getMessage());

            throw new GeneralException('There was a problem drafting this application. Please try again.');
        }

        DB::commit();

        return $application;
    }

    /**
     * @param    $data
     * @param  Application  $application
     * @return Application
     *
     * @throws GeneralException
     * @throws \Throwable
     */
    public function updateDraft($data, Application $application): Application
    {
        DB::beginTransaction();

        try {
            $application->update([
                'name' => $data->name,
                'rank' => $data->rank,
                'badge_number' => $data->badge_number,
                'unit' => $data->unit,
                'office_address' => $data->office_address,
                'tel' => $data->tel,
                'arrested_firstname' => $data->arrested_firstname,
                'arrested_middlename' => $data->arrested_middlename,
                'arrested_lastname' => $data->arrested_lastname,
                'arrested_suffix' => $data->arrested_suffix,
                'arrested_address' => $data->arrested_address,
                'arrested_tel' => $data->arrested_tel,
                'arrested_pob' => $data->arrested_pob,
                'arrested_dob' => $data->arrested_dob,
                'arrested_age' => $data->arrested_age,
                'arrested_sex' => $data->arrested_sex,
                'arrested_status' => $data->arrested_status,
                'arrested_spouse_name' => $data->arrested_spouse_name,
                'arrested_spouse_address' => $data->arrested_spouse_address,
                'arrested_weight' => $data->arrested_weight,
                'arrested_height' => $data->arrested_height,
                'arrested_eyes' => $data->arrested_eyes,
                'arrested_hair' => $data->arrested_hair,
                'arrested_complexion' => $data->arrested_complexion,
                'arrested_occupation' => $data->arrested_occupation,
                'arrested_nationality' => $data->arrested_nationality,
                'arrested_tribe' => $data->arrested_tribe,
                'arrested_language' => $data->arrested_language,
                'arrested_educ_attainment' => $data->arrested_educ_attainment,
                'arrested_school_name' => $data->arrested_school_name,
                'arrested_school_address' => $data->arrested_school_address,
                'arrested_marks' => $data->arrested_marks,
                'arrested_location_marks' => $data->arrested_location_marks,
                'arrested_defect' => $data->arrested_defect,
                'who' => trim("{$data->arrested_firstname} {$data->arrested_lastname} {$data->arrested_suffix}"),
                'when' => $data->when,
                'where' => $data->where,
                'what' => $data->what,
                'how' => $data->how,
                'why' => $data->why,
                'other_details' => $data->other_details,
                'arrested_category' => $data->arrested_category,
                'is_informed_of_right' => $data->is_informed_of_right,
                'mental_condition' => $data->mental_condition,
                'physical_condition' => $data->physical_condition,
                'extension_reason' => $data->extension_reason,
                'reason_narration' => $data->reason_narration,
            ]);

            $this->updateDraftMediaFile($application, 'arrested_picture');
            $this->updateDraftMediaFile($application, 'sworn_affidavit');
            $this->updateDraftMediaFile($application, 'logbook');
            $this->updateDraftMediaFile($application, 'book_sheet');
            $this->updateDraftMediaFile($application, 'spot_report');
            $this->updateDraftMediaFile($application, 'sworn_affidavit_witness');
            $this->updateDraftMediaFile($application, 'warrantless_arrest');

            //Upload Supporting Documents
            if (! empty($data->temporary_documents)) {
                $unusedUuids = $application->getDraftSupportingDocuments()->whereNotIn('uuid', $data->temporary_documents)->pluck('uuid')->toArray();

                foreach ($unusedUuids as $uuid) {
                    $media = Media::where('uuid', $uuid)->first();
                    $media->delete();
                }

                foreach ($data->temporary_documents as $document) {
                    $media = auth()->user()->getMedia('temporary')->where('uuid', $document)->first();
                    if (empty($media)) {
                        continue;
                    }

                    $media->copy($application, 'draft');
                }
            } else {
                $unusedUuids = $application->getDraftSupportingDocuments()->pluck('uuid')->toArray();

                foreach ($unusedUuids as $uuid) {
                    $media = Media::where('uuid', $uuid)->first();
                    $media->delete();
                }
            }

            auth()->user()->clearMediaCollection('temporary');
        } catch (Exception $e) {
            DB::rollBack();
            Log::debug($e->getMessage());

            throw new GeneralException('There was a problem updating this drafted application. Please try again.');
        }

        DB::commit();

        return $application;
    }

    /**
     * @param  Application  $application
     *
     * @throws GeneralException
     * @throws \Throwable
     */
    public function deleteDraft(Application $application)
    {
        DB::beginTransaction();

        try {
            $application->delete();
        } catch (Exception $e) {
            DB::rollBack();
            Log::debug($e->getMessage());

            throw new GeneralException('There was a problem deleting this drafted application. Please try again.');
        }

        DB::commit();
    }

    /**
     * @param  Application  $application
     * @param    $data
     * @return Application
     *
     * @throws GeneralException
     * @throws \Throwable
     */
    public function approve(Application $application, $data): Application
    {
        DB::beginTransaction();

        try {
            $application->update([
                'approved_remarks' => $data->remarks ?? null,
                'approved_date' => now(),
                'status' => Application::ENDORSING,
            ]);

            //Upload Supporting Documents
            if (! empty($data->temporary_documents)) {
                foreach ($data->temporary_documents as $document) {
                    $media = Media::where('uuid', $document)->first();
                    if (empty($media)) {
                        continue;
                    }

                    $media->copy($application, 'approved_documents');
                }
            }

            auth()->user()->clearMediaCollection('temporary');

            event(new ApplicationAccepted($application));
        } catch (Exception $e) {
            DB::rollBack();
            Log::debug($e->getMessage());

            throw new GeneralException('There was a problem approving this application. Please try again.');
        }

        DB::commit();

        return $application;
    }

    /**
     * @param  Application  $application
     * @param    $data
     * @return Application
     *
     * @throws GeneralException
     * @throws \Throwable
     */
    public function disapprove(Application $application, $data): Application
    {
        DB::beginTransaction();

        try {
            $application->update([
                'status' => Application::DISAPPROVED,
                'disapproved_remarks' => $data->remarks,
                'disapproved_date' => now(),
            ]);

            //Upload Supporting Documents
            if (! empty($data->temporary_documents)) {
                foreach ($data->temporary_documents as $document) {
                    $media = Media::where('uuid', $document)->first();
                    if (empty($media)) {
                        continue;
                    }

                    $media->copy($application, 'disapproved_documents');
                }
            }

            auth()->user()->clearMediaCollection('temporary');

            event(new ApplicationDisapproved($application));
        } catch (Exception $e) {
            DB::rollBack();
            Log::debug($e->getMessage());

            throw new GeneralException('There was a problem disapproving this application. Please try again.');
        }

        DB::commit();

        return $application;
    }

    /**
     * @param  Application  $application
     * @param    $data
     * @return Application
     *
     * @throws GeneralException
     * @throws \Throwable
     */
    public function endorse(Application $application, $data): Application
    {
        DB::beginTransaction();

        try {
            $user = auth()->user();
            $key = "endorse-{$application->control_number}-{$user->id}";
            $key_count = "endorse-{$application->control_number}-count";

            if (!Cache::has($key)) {
                Cache::forever($key, true);
            } else {
                return $application;
            }

            // check if there is a cache else we will create for checking number of user who endorsed
            if (!Cache::has($key_count)) {
                Cache::forever($key_count, 1);
            } else {
                Cache::increment($key_count);
            }

            if (Cache::has($key_count) && Cache::get($key_count) >= 4) {
                $application->update([
                    'endorsed_remarks' => null,
                    'endorsed_date' => now(),
                    'status' => Application::VOTING,
                ]);

                event(new ApplicationEndorsed($application));
            }

        } catch (Exception $e) {
            DB::rollBack();
            Log::debug($e->getMessage());

            throw new GeneralException('There was a problem updating this application. Please try again.');
        }

        DB::commit();

        return $application;
    }

    /**
     * @param  Application  $application
     * @param    $data
     * @return Application
     *
     * @throws GeneralException
     * @throws \Throwable
     */
    public function resolution(Application $application, $data): Application
    {
        DB::beginTransaction();

        try {
            $this->uploadMediaFiles($application, 'resolution_file', false, 'resolution');

            $application->update([
                'final_resolution' => now(),
            ]);

            //Upload Supporting Documents
            if (! empty($data->temporary_documents)) {
                foreach ($data->temporary_documents as $document) {
                    $media = Media::where('uuid', $document)->first();
                    if (empty($media)) {
                        continue;
                    }

                    $media->copy($application, 'resolution_documents');
                }
            }

            auth()->user()->clearMediaCollection('temporary');

            event(new ResolutionUploaded($application));
        } catch (Exception $e) {
            DB::rollBack();
            Log::debug($e->getMessage());

            throw new GeneralException('There was a problem uploading this resolution. Please try again.');
        }

        DB::commit();

        return $application;
    }

    /**
     * @param  Application  $application
     * @return Application
     *
     * @throws GeneralException
     * @throws \Throwable
     */
    public function extension(Application $application): Application
    {
        //Check if application is eligible for extension
        $remainingDays = $application->when->diffInDays(now());
        if ($remainingDays < (config('atc.access.days_of_detention') - config('atc.access.days_remaining'))) {
            throw new GeneralException('You can only apply for extension '.config('atc.access.days_remaining').' days before the last day of detention', 422);
        }

        DB::beginTransaction();

        try {
            $newApplication = $application->replicate();
            $newApplication->status = Application::AVAILABLE;
            $newApplication->is_extension = true;
            $newApplication->date_submitted = now();
            $newApplication->posted_date = null;
            $newApplication->approved_remarks = null;
            $newApplication->disapproved_remarks = null;
            $newApplication->endorsed_remarks = null;
            $newApplication->approved_date = null;
            $newApplication->disapproved_date = null;
            $newApplication->endorsed_date = null;
            $newApplication->final_resolution = null;
            $newApplication->push();
            $newApplication->update();

            $application->copyFilesTo($newApplication, 'arrested_picture');
            $application->copyFilesTo($newApplication, 'sworn_affidavit');
            $application->copyFilesTo($newApplication, 'logbook');
            $application->copyFilesTo($newApplication, 'book_sheet');
            $application->copyFilesTo($newApplication, 'spot_report');
            $application->copyFilesTo($newApplication, 'sworn_affidavit_witness');
            $application->copyFilesTo($newApplication, 'warrantless_arrest');

            $documents = $application->getSupportingDocuments();
            foreach ($documents as $document) {
                $document->copy($newApplication, 'supporting_documents');
            }

            event(new ExtensionRequested($newApplication));
        } catch (Exception $e) {
            DB::rollBack();
            Log::debug($e->getMessage());

            throw new GeneralException('There was a problem extending this application. Please try again.');
        }

        DB::commit();

        return $newApplication;
    }

    /**
     * @param  array  $data
     * @return Application
     */
    protected function createApplication(array $data = []): Application
    {
        return $this->model::create([
            'user_id' => $data['user_id'] ?? null,
            'control_number' => $data['control_number'] ?? null,
            'name' => $data['name'] ?? null,
            'rank' => $data['rank'] ?? null,
            'badge_number' => $data['badge_number'] ?? null,
            'unit' => $data['unit'] ?? null,
            'office_address' => $data['office_address'] ?? null,
            'tel' => $data['tel'] ?? null,
            'arrested_picture' => $data['arrested_picture'] ?? null,
            'arrested_firstname' => $data['arrested_firstname'] ?? null,
            'arrested_middlename' => $data['arrested_middlename'] ?? null,
            'arrested_lastname' => $data['arrested_lastname'] ?? null,
            'arrested_suffix' => $data['arrested_suffix'] ?? null,
            'arrested_address' => $data['arrested_address'] ?? null,
            'arrested_tel' => $data['arrested_tel'] ?? null,
            'arrested_pob' => $data['arrested_pob'] ?? null,
            'arrested_dob' => $data['arrested_dob'] ?? null,
            'arrested_age' => $data['arrested_age'] ?? null,
            'arrested_sex' => $data['arrested_sex'] ?? null,
            'arrested_status' => $data['arrested_status'] ?? null,
            'arrested_spouse_name' => $data['arrested_spouse_name'] ?? null,
            'arrested_spouse_address' => $data['arrested_spouse_address'] ?? null,
            'arrested_weight' => $data['arrested_weight'] ?? null,
            'arrested_height' => $data['arrested_height'] ?? null,
            'arrested_eyes' => $data['arrested_eyes'] ?? null,
            'arrested_hair' => $data['arrested_hair'] ?? null,
            'arrested_complexion' => $data['arrested_complexion'] ?? null,
            'arrested_occupation' => $data['arrested_occupation'] ?? null,
            'arrested_nationality' => $data['arrested_nationality'] ?? null,
            'arrested_tribe' => $data['arrested_tribe'] ?? null,
            'arrested_language' => $data['arrested_language'] ?? null,
            'arrested_educ_attainment' => $data['arrested_educ_attainment'] ?? null,
            'arrested_school_name' => $data['arrested_school_name'] ?? null,
            'arrested_school_address' => $data['arrested_school_address'] ?? null,
            'arrested_marks' => $data['arrested_marks'] ?? null,
            'arrested_location_marks' => $data['arrested_location_marks'] ?? null,
            'arrested_defect' => $data['arrested_defect'] ?? null,
            'who' => $data['who'] ?? null,
            'when' => $data['when'] ?? null,
            'where' => $data['where'] ?? null,
            'what' => $data['what'] ?? null,
            'how' => $data['how'] ?? null,
            'why' => $data['why'] ?? null,
            'other_details' => $data['other_details'] ?? null,
            'arrested_category' => $data['arrested_category'] ?? null,
            'is_informed_of_right' => $data['is_informed_of_right'] ?? null,
            'mental_condition' => $data['mental_condition'] ?? null,
            'physical_condition' => $data['physical_condition'] ?? null,
            'extension_reason' => $data['extension_reason'] ?? null,
            'reason_narration' => $data['reason_narration'] ?? null,
            'sworn_affidavit' => $data['sworn_affidavit'] ?? null,
            'logbook' => $data['logbook'] ?? null,
            'book_sheet' => $data['book_sheet'] ?? null,
            'spot_report' => $data['spot_report'] ?? null,
            'sworn_affidavit_witness' => $data['sworn_affidavit_witness'] ?? null,
            'warrantless_arrest' => $data['warrantless_arrest'] ?? null,
            'date_submitted' => $data['date_submitted'] ?? null,
            'status' => $data['status'] ?? null,
        ]);
    }

    /**
     * @param  array  $data
     * @param  string  $controlNumber
     * @param  bool  $controlNumber
     * @return Application
     */
    protected function processApplication($data, $controlNumber = null, $isDraft = false)
    {
        $application = $this->createApplication([
            'user_id' => auth()->user()->id,
            'control_number' => $controlNumber,
            'name' => $data->name,
            'rank' => $data->rank,
            'badge_number' => $data->badge_number,
            'unit' => $data->unit,
            'office_address' => $data->office_address,
            'tel' => $data->tel,
            'arrested_firstname' => $data->arrested_firstname,
            'arrested_middlename' => $data->arrested_middlename,
            'arrested_lastname' => $data->arrested_lastname,
            'arrested_suffix' => $data->arrested_suffix,
            'arrested_address' => $data->arrested_address,
            'arrested_tel' => $data->arrested_tel,
            'arrested_pob' => $data->arrested_pob,
            'arrested_dob' => $data->arrested_dob,
            'arrested_age' => $data->arrested_age,
            'arrested_sex' => $data->arrested_sex,
            'arrested_status' => $data->arrested_status,
            'arrested_spouse_name' => $data->arrested_spouse_name,
            'arrested_spouse_address' => $data->arrested_spouse_address,
            'arrested_weight' => $data->arrested_weight,
            'arrested_height' => $data->arrested_height,
            'arrested_eyes' => $data->arrested_eyes,
            'arrested_hair' => $data->arrested_hair,
            'arrested_complexion' => $data->arrested_complexion,
            'arrested_occupation' => $data->arrested_occupation,
            'arrested_nationality' => $data->arrested_nationality,
            'arrested_tribe' => $data->arrested_tribe,
            'arrested_language' => $data->arrested_language,
            'arrested_educ_attainment' => $data->arrested_educ_attainment,
            'arrested_school_name' => $data->arrested_school_name,
            'arrested_school_address' => $data->arrested_school_address,
            'arrested_marks' => $data->arrested_marks,
            'arrested_location_marks' => $data->arrested_location_marks,
            'arrested_defect' => $data->arrested_defect,
            'who' => trim("{$data->arrested_firstname} {$data->arrested_lastname} {$data->arrested_suffix}"),
            'when' => $data->when,
            'where' => $data->where,
            'what' => $data->what,
            'how' => $data->how,
            'why' => $data->why,
            'other_details' => $data->other_details,
            'arrested_category' => $data->arrested_category,
            'is_informed_of_right' => $data->is_informed_of_right,
            'mental_condition' => $data->mental_condition,
            'physical_condition' => $data->physical_condition,
            'extension_reason' => $data->extension_reason,
            'reason_narration' => $data->reason_narration,
            'date_submitted' => now(),
            'status' => $isDraft ? Application::DRAFT : Application::AVAILABLE,
        ]);

        if ($isDraft) {
            $this->uploadMediaFiles($application, 'arrested_picture', false, 'draft');
            $this->uploadMediaFiles($application, 'sworn_affidavit', false, 'draft');
            $this->uploadMediaFiles($application, 'logbook', false, 'draft');
            $this->uploadMediaFiles($application, 'book_sheet', false, 'draft');
            $this->uploadMediaFiles($application, 'spot_report', false, 'draft');
            $this->uploadMediaFiles($application, 'sworn_affidavit_witness', false, 'draft');
            $this->uploadMediaFiles($application, 'warrantless_arrest', false, 'draft');
        } else {
            $this->uploadMediaFiles($application, 'arrested_picture');
            $this->uploadMediaFiles($application, 'sworn_affidavit');
            $this->uploadMediaFiles($application, 'logbook');
            $this->uploadMediaFiles($application, 'book_sheet');
            $this->uploadMediaFiles($application, 'spot_report');
            $this->uploadMediaFiles($application, 'sworn_affidavit_witness');
            $this->uploadMediaFiles($application, 'warrantless_arrest');
        }

        //Upload Supporting Documents
        if (! empty($data->temporary_documents)) {
            foreach ($data->temporary_documents as $document) {
                $media = Media::where('uuid', $document)->first();
                if (empty($media)) {
                    continue;
                }

                if ($isDraft) {
                    $media->copy($application, 'draft');
                } else {
                    $media->copy($application, 'supporting_documents');
                }
            }
        }

        auth()->user()->clearMediaCollection('temporary');

        return $application;
    }

    /**
     * @param    $data
     * @param  Application  $application
     * @return Application
     */
    protected function updateApplication($data, Application $application)
    {
        $controlNumber = empty($application->control_number) ? '' : $application->control_number.'-';

        $application->update([
            'name' => $data->name,
            'rank' => $data->rank,
            'badge_number' => $data->badge_number,
            'unit' => $data->unit,
            'office_address' => $data->office_address,
            'tel' => $data->tel,
            'arrested_firstname' => $data->arrested_firstname,
            'arrested_middlename' => $data->arrested_middlename,
            'arrested_lastname' => $data->arrested_lastname,
            'arrested_suffix' => $data->arrested_suffix,
            'arrested_address' => $data->arrested_address,
            'arrested_tel' => $data->arrested_tel,
            'arrested_pob' => $data->arrested_pob,
            'arrested_dob' => $data->arrested_dob,
            'arrested_age' => $data->arrested_age,
            'arrested_sex' => $data->arrested_sex,
            'arrested_status' => $data->arrested_status,
            'arrested_spouse_name' => $data->arrested_spouse_name,
            'arrested_spouse_address' => $data->arrested_spouse_address,
            'arrested_weight' => $data->arrested_weight,
            'arrested_height' => $data->arrested_height,
            'arrested_eyes' => $data->arrested_eyes,
            'arrested_hair' => $data->arrested_hair,
            'arrested_complexion' => $data->arrested_complexion,
            'arrested_occupation' => $data->arrested_occupation,
            'arrested_nationality' => $data->arrested_nationality,
            'arrested_tribe' => $data->arrested_tribe,
            'arrested_language' => $data->arrested_language,
            'arrested_educ_attainment' => $data->arrested_educ_attainment,
            'arrested_school_name' => $data->arrested_school_name,
            'arrested_school_address' => $data->arrested_school_address,
            'arrested_marks' => $data->arrested_marks,
            'arrested_location_marks' => $data->arrested_location_marks,
            'arrested_defect' => $data->arrested_defect,
            'who' => trim("{$data->arrested_firstname} {$data->arrested_lastname} {$data->arrested_suffix}"),
            'when' => $data->when,
            'where' => $data->where,
            'what' => $data->what,
            'how' => $data->how,
            'why' => $data->why,
            'other_details' => $data->other_details,
            'arrested_category' => $data->arrested_category,
            'is_informed_of_right' => $data->is_informed_of_right,
            'mental_condition' => $data->mental_condition,
            'physical_condition' => $data->physical_condition,
            'extension_reason' => $data->extension_reason,
            'reason_narration' => $data->reason_narration,
        ]);

        $this->updateMediaFile($application, 'arrested_picture');
        $this->updateMediaFile($application, 'sworn_affidavit');
        $this->updateMediaFile($application, 'logbook');
        $this->updateMediaFile($application, 'book_sheet');
        $this->updateMediaFile($application, 'spot_report');
        $this->updateMediaFile($application, 'sworn_affidavit_witness');
        $this->updateMediaFile($application, 'warrantless_arrest');

        //Upload Supporting Documents
        if (! empty($data->temporary_documents)) {
            $unusedUuids = $application->getMedia('supporting_documents')->whereNotIn('uuid', $data->temporary_documents)->pluck('uuid')->toArray();

            foreach ($unusedUuids as $uuid) {
                $media = Media::where('uuid', $uuid)->first();
                $media->delete();
            }

            foreach ($data->temporary_documents as $document) {
                $media = auth()->user()->getMedia('temporary')->where('uuid', $document)->first();
                if (empty($media)) {
                    continue;
                }

                $media->copy($application, 'supporting_documents');
            }
        } else {
            $application->clearMediaCollection('supporting_documents');
        }

        auth()->user()->clearMediaCollection('temporary');

        return $application;
    }

    /**
     * @param  Application  $application
     * @param  string  $fieldName
     * @param  bool  $deleteMedia
     * @param  string  $collectionName
     * @return void
     */
    protected function uploadMediaFiles(Application $application, $fieldName, $deleteMedia = false, $collectionName = null)
    {
        if (empty($collectionName)) {
            $collectionName = $fieldName;
        }

        if ($deleteMedia) {
            $application->clearMediaCollection($collectionName);
        }

        if (empty(request()->file($fieldName))) {
            return;
        }

        $application->addMediaFromRequest($fieldName)
                    ->preservingOriginal()
                    ->usingName($fieldName)
                    ->toMediaCollection($collectionName);
    }

    /**
     * @param  Application  $application
     * @param  string  $fieldName
     * @param  string  $collectionName
     * @return void
     */
    protected function updateMediaFile(Application $application, $fieldName, $collectionName = null)
    {
        //If user didn't replace the file
        if (! empty(request($fieldName.'_path'))) {
            return;
        }

        $this->uploadMediaFiles($application, $fieldName, true, $collectionName);
    }

    /**
     * @param  Application  $application
     * @param  string  $fieldName
     * @return void
     */
    protected function updateDraftMediaFile(Application $application, $fieldName)
    {
        //If user didn't replace the file
        if (! empty(request($fieldName.'_path'))) {
            return;
        }

        $this->uploadMediaFiles($application, $fieldName, false, 'draft');
    }

    /**
     * @param  Application  $application
     * @param  string  $collectionName
     * @return void
     */
    protected function moveDraftFile(Application $application, $collectionName)
    {
        $media = $application->getFiles('draft')->where('name', $collectionName)->first();
        if (empty($media)) {
            return;
        }

        $media->move($application, $collectionName);
    }

    /**
     * @param  Application  $application
     * @return void
     */
    protected function moveSupportingDocument(Application $application)
    {
        $documents = $application->getDraftSupportingDocuments()->all();

        foreach ($documents as $document) {
            $document->copy($application, 'supporting_documents');
        }
    }
}
