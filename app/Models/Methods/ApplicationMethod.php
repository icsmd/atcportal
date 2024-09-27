<?php

namespace App\Models\Methods;

use App\Models\Application;
use App\Models\Comment;
use App\Models\Scopes\ApplicationGlobalScope;
use App\Models\Vote;
use Carbon\Carbon;

/**
 * Trait ApplicationMethod.
 */
trait ApplicationMethod
{
    /**
     * @return Comment
     */
    public function addComment($comment)
    {
        /** @var Comment $comment */
        $comment = $this->comments()->create($comment);

        return $comment;
    }

    /**
     * @return Comment
     */
    public function addNarrativeHistory($narrative)
    {
        $narrative = $this->narrativeHistories()->create([
            'user_id' => auth()->user()->id,
            'narrative' => $narrative,
        ]);

        return $narrative;
    }

    /**
     * @return Vote
     */
    public function addVote($vote)
    {
        $vote = $this->votes()->create($vote);

        return $vote;
    }

    /**
     * @return bool
     */
    public function isAlreadyVoted()
    {
        return $this->votes()->where('user_id', auth()->user()->id)->count() >= 1;
    }

    /**
     * @return mixed
     */
    public function isVoteMajority()
    {
        $approvedCount = $this->votes()->where('status', Vote::APPROVED)->count();
        $disapprovedCount = $this->votes()->where('status', Vote::DISAPPROVED)->count();

        $majorityCount = config('atc.access.majority_count');

        if ($approvedCount >= $majorityCount) {
            return Vote::APPROVED;
        }

        if ($disapprovedCount >= $majorityCount) {
            return Vote::DISAPPROVED;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function hasExtensionRequest()
    {
        return $this->where('control_number', $this->control_number)
                    ->where('is_extension', true)
                    ->count() > 0;
    }

    /**
     * @return bool
     */
    public function isArchive()
    {
        return $this->detention_expiration <= now() || $this->status == Application::DISAPPROVED || $this->status == Application::EXPIRED;
    }

    /**
     * @return string
     */
    public function createControlNumber()
    {
        $totalApplicationCount = $this->withoutGlobalScope(ApplicationGlobalScope::class)
                            ->where('status', '<>', Application::DRAFT)->count();

        $seq = str_pad($totalApplicationCount + 1, 2, '0', STR_PAD_LEFT);
        $dateNow = now()->format('dmY');
        $atcNumber = 'ATC'.$seq.$dateNow;

        $hash = hash('sha256', $atcNumber.'65736b6965');

        $code = \Str::limit($hash, 4);

        $first = $code[0].$code[1];
        $second = $code[2].$code[3];

        $controlNumber = 'ATC'.$seq.\Str::upper($first).$dateNow.\Str::upper($second);

        return $controlNumber; //ATC013B200520210B
    }

    /**
     * @return int
     */
    public function getProcessedTime(): int
    {
        if (empty($this->when)) {
            return 0;
        }

        return $this->when->diffInSeconds($this->updated_at) * 1000;
    }

    /**
     * @param  string  $controlNumber
     * @return string
     */
    public function getMugshotPath()
    {
        if (empty($this->control_number)) {
            return $this->user_id.'/draft/'.$this->id.'/mugshot';
        }

        if ($this->is_extension) {
            return $this->user_id.'/'.$this->control_number.'/extension/mugshot';
        }

        return $this->user_id.'/'.$this->control_number.'/mugshot';
    }

    /**
     * @param  string  $controlNumber
     * @return string
     */
    public function getResolutionDraftPath()
    {
        if ($this->is_extension) {
            return $this->user_id.'/'.$this->control_number.'/extension/draft/resolution';
        }

        return $this->user_id.'/'.$this->control_number.'/resolution/draft';
    }

    /**
     * @param  string  $controlNumber
     * @return string
     */
    public function getResolutionPath()
    {
        if ($this->is_extension) {
            return $this->user_id.'/'.$this->control_number.'/extension/resolution';
        }

        return $this->user_id.'/'.$this->control_number.'/resolution';
    }

    /**
     * @param  string  $key
     * @return Media Collection
     */
    public function getFiles($key)
    {
        return $this->getMedia($key);
    }

    /**
     * @param  string  $key
     * @return Media
     */
    public function getMediaFile($key)
    {
        if ($this->status == self::DRAFT) {
            return $this->getFiles('draft')->where('name', $key)->first();
        }

        return $this->getFiles($key)->first();
    }

    /**
     * @param  string  $key
     * @return string
     */
    public function getFileUrl($key)
    {
        if (empty($this->getMediaFile($key))) {
            return null;
        }

        if (config('media-library.disk_name') === 's3') {
            return $this->getMediaFile($key)->getTemporaryUrl(Carbon::now()->addMinutes(5));
        }

        return $this->getMediaFile($key)->getPath();
    }

    /**
     * @param  string  $key
     * @return string
     */
    public function getFilePath($key)
    {
        if (empty($this->getMediaFile($key))) {
            return null;
        }

        return $this->getMediaFile($key)->getPath();
    }

    /**
     * @return Media
     */
    public function getSupportingDocuments()
    {
        if ($this->status == self::DRAFT) {
            return $this->getDraftSupportingDocuments()->all();
        }

        return $this->getFiles('supporting_documents');
    }

    /**
     * @return Media
     */
    public function getDraftSupportingDocuments()
    {
        return $this->getFiles('draft')->whereNotIn('name', ['arrested_picture', 'sworn_affidavit', 'logbook', 'book_sheet', 'spot_report', 'sworn_affidavit_witness', 'warrantless_arrest']);
    }

    /**
     * @return Media
     */
    public function getApprovedDocuments()
    {
        return $this->getFiles('approved_documents');
    }

    /**
     * @return Media
     */
    public function getDisapprovedDocuments()
    {
        return $this->getFiles('disapproved_documents');
    }

    /**
     * @return Media
     */
    public function getEndorsedDocuments()
    {
        return $this->getFiles('endorsed_documents');
    }

    /**
     * @return Media
     */
    public function getResolutionDocuments()
    {
        return $this->getFiles('resolution_documents');
    }

    /**
     * @return bool
     */
    public function canUpdateDraft()
    {
        return optional(auth()->user())->canSendApplication() && $this->status == self::DRAFT;
    }

    /**
     * @return bool
     */
    public function canUpdate()
    {
        return optional(auth()->user())->canUpdateApplication() && $this->status != self::DRAFT && $this->status != self::APPROVED && $this->status != self::EXPIRED && ! $this->isArchive();
    }

    /**
     * @return bool
     */
    public function canApprove()
    {
        return optional(auth()->user())->canApproveApplication() &&
                $this->status == self::AVAILABLE &&
                ! $this->isArchive();
    }

    /**
     * @return bool
     */
    public function canDisapprove()
    {
        return optional(auth()->user())->can('disapprove application') && $this->status != self::APPROVED && $this->status != self::EXPIRED && $this->status != self::DRAFT && $this->status != self::DISAPPROVED && $this->status != self::VOTING;
    }

    /**
     * @return bool
     */
    public function canComment()
    {
        return optional(auth()->user())->can('comment application') &&
                $this->status == self::ENDORSING &&
                ! $this->isArchive();
    }

    /**
     * @return bool
     */
    public function canEndorse()
    {
        return optional(auth()->user())->can('endorse application') &&
                $this->status == self::ENDORSING &&
                ! $this->isArchive();
    }

    /**
     * @return bool
     */
    public function canVote()
    {
        return optional(auth()->user())->can('vote application') &&
                $this->status == self::VOTING &&
                ! $this->isArchive() && ! $this->isAlreadyVoted();
    }

    /**
     * @return bool
     */
    public function canProvideResolution()
    {
        return optional(auth()->user())->can('provide resolution') &&
                $this->status == self::APPROVED &&
                ! $this->isArchive();
    }

    /**
     * @return bool
     */
    public function canExtend()
    {
        return ! $this->is_extension &&
                $this->status == self::APPROVED &&
                ! empty($this->final_resolution) &&
                ! $this->isArchive() &&
                ! $this->hasExtensionRequest() &&
                optional(auth()->user())->can(config('atc.access.permission.send_application'));
    }

    /**
     * @return bool
     */
    public function canEditNarrative()
    {
        return optional(auth()->user())->can('edit narrative') &&
                $this->status != self::APPROVED &&
                ! $this->isArchive();
    }

    /**
     * @param  Application  $application
     * @param  string  $collection
     * @return void
     */
    public function copyFilesTo(Application $application, $collection)
    {
        $media = $this->getMediaFile($collection);
        if (empty($media)) {
            return;
        }

        $media->copy($application, $collection);
    }
}
