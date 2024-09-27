<?php

namespace App\Http\Controllers;

use App\Http\Requests\Application\ApproveApplicationRequest;
use App\Http\Requests\Application\CreateApplicationRequest;
use App\Http\Requests\Application\DeleteDraftApplicationRequest;
use App\Http\Requests\Application\DisapproveApplicationRequest;
use App\Http\Requests\Application\DraftApplicationRequest;
use App\Http\Requests\Application\EditApplicationRequest;
use App\Http\Requests\Application\EndorseApplicationRequest;
use App\Http\Requests\Application\ExtendApplicationRequest;
use App\Http\Requests\Application\ResolutionApplicationRequest;
use App\Http\Requests\Application\StoreApplicationRequest;
use App\Http\Requests\Application\UpdateApplicationRequest;
use App\Http\Requests\Application\UpdateBriefNarrativeRequest;
use App\Http\Requests\Application\UpdateDraftApplicationRequest;
use App\Http\Resources\Application\ApplicationDetailResource;
use App\Http\Resources\Application\ApplicationListResource;
use App\Http\Resources\Application\ApplicationQrResource;
use App\Models\Application;
use App\Services\ApplicationService;
use App\Services\MediaFileService;
use Cache;
use Inertia\Inertia;

class ApplicationController extends Controller
{
    /**
     * @var ApplicationService
     */
    protected $applicationService;

    /**
     * @var MediaFileService
     */
    protected $mediaService;

    /**
     * ApplicationController constructor.
     *
     * @param  ApplicationService  $applicationService
     * @param  MediaFileService  $mediaService
     */
    public function __construct(ApplicationService $applicationService, MediaFileService $mediaService)
    {
        $this->applicationService = $applicationService;
        $this->mediaService = $mediaService;
    }

    /**
     * @return \Inertia\Response
     */
    public function index()
    {
        return Inertia::render('Application/Index', [
            'applications' => ApplicationListResource::collection($this->applicationService->search(['with_draft' => true, 'control_number' => null])),
        ]);
    }

    /**
     * @param  Application  $application
     * @return \Inertia\Response
     */
    public function show(Application $application)
    {
        $user = auth()->user();
        $key_count = "endorse-{$application->control_number}-count";
        $endorse_count = 0;
        if (Cache::has($key_count)) {
            $endorse_count = Cache::get($key_count);
        }

        return Inertia::render('Application/Detail', [
            'applicationData' => new ApplicationDetailResource($application),
            'comments' => fn () => $user->canViewDiscussion() ? $application->comments->map(function ($comment) {
                return [
                    'name' => $comment->user->name,
                    'image' => $comment->user->profile_photo_url,
                    'message' => $comment->body,
                    'created_at' => $comment->created_at->diffForHumans(),
                ];
            }) : [],
            'votes' => fn () => $user->canViewVote() ? $application->votes->map(function ($vote) {
                return [
                    'name' => $vote->user->name,
                    'image' => $vote->user->profile_photo_url,
                    'message' => $vote->message,
                    'status' => $vote->status,
                    'created_at' => $vote->created_at->diffForHumans(),
                ];
            }) : [],
            'max_votes' => (config('atc.access.majority_count') * 2) - 1,
            'current_endorse' => $endorse_count,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  CreateApplicationRequest  $request
     * @return \Inertia\Response
     */
    public function create(CreateApplicationRequest $request)
    {
        return Inertia::render('Application/Create');
    }

    /**
     * @param  StoreApplicationRequest  $request
     * @return \Illuminate\Http\Response
     *
     * @throws \App\Exceptions\GeneralException
     * @throws \Throwable
     */
    public function store(StoreApplicationRequest $request)
    {
        $this->applicationService->store($request);

        return redirect()->route('applications')->banner('Application submitted successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  EditApplicationRequest  $request
     * @param  Application  $application
     * @return \Inertia\Response
     */
    public function edit(EditApplicationRequest $request, Application $application)
    {
        return Inertia::render('Application/Edit', [
            'application' => $application,
            'arrested_picture_path' => $application->getMediaFile('arrested_picture'),
            'sworn_affidavit_path' => $application->getMediaFile('sworn_affidavit'),
            'logbook_path' => $application->getMediaFile('logbook'),
            'book_sheet_path' => $application->getMediaFile('book_sheet'),
            'spot_report_path' => $application->getMediaFile('spot_report'),
            'sworn_affidavit_witness_path' => $application->getMediaFile('sworn_affidavit_witness'),
            'warrantless_arrest_path' => $application->getMediaFile('warrantless_arrest'),
            'supporting_documents' => $application->getSupportingDocuments(),
        ]);
    }

    /**
     * @param  UpdateApplicationRequest  $request
     * @param  Application  $application
     * @return \Illuminate\Http\Response
     *
     * @throws \App\Exceptions\GeneralException
     * @throws \Throwable
     */
    public function update(UpdateApplicationRequest $request, Application $application)
    {
        $this->applicationService->update($request, $application);

        return redirect()->route('applications')->banner('Application was updated successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  CreateApplicationRequest  $request
     * @param  Application  $application
     * @return \Inertia\Response
     */
    public function duplicate(CreateApplicationRequest $request, Application $application)
    {
        return Inertia::render('Application/Duplicate', [
            'application' => $application,
        ]);
    }

    /**
     * @param  DraftApplicationRequest  $request
     * @return \Illuminate\Http\Response
     *
     * @throws \App\Exceptions\GeneralException
     * @throws \Throwable
     */
    public function draft(DraftApplicationRequest $request)
    {
        $this->applicationService->draft($request);

        return redirect()->route('applications')->banner('Application was drafted successfully.');
    }

    /**
     * @param  UpdateDraftApplicationRequest  $request
     * @param  Application  $application
     * @return \Illuminate\Http\Response
     *
     * @throws \App\Exceptions\GeneralException
     * @throws \Throwable
     */
    public function updateDraft(UpdateDraftApplicationRequest $request, Application $application)
    {
        $this->applicationService->updateDraft($request, $application);

        return redirect()->route('applications')->banner('Drafted Application was updated successfully.');
    }

    /**
     * @param  DeleteDraftApplicationRequest  $request
     * @param  Application  $application
     * @return \Illuminate\Http\Response
     *
     * @throws \App\Exceptions\GeneralException
     * @throws \Throwable
     */
    public function deleteDraft(DeleteDraftApplicationRequest $request, Application $application)
    {
        $this->applicationService->deleteDraft($application);

        return redirect()->route('applications')->banner('Drafted Application was deleted successfully.');
    }

    /**
     * @param  UpdateDraftApplicationRequest  $request
     * @param  Application  $application
     * @return \Illuminate\Http\Response
     *
     * @throws \App\Exceptions\GeneralException
     * @throws \Throwable
     */
    public function submitDraft(UpdateDraftApplicationRequest $request, Application $application)
    {
        $this->applicationService->submitDraft($request, $application);

        return redirect()->route('applications')->banner('Application submitted successfully.');
    }

    /**
     * @param  UpdateBriefNarrativeRequest  $request
     * @param  Application  $application
     * @return \Illuminate\Http\Response
     *
     * @throws \App\Exceptions\GeneralException
     * @throws \Throwable
     */
    public function updateNarrative(UpdateBriefNarrativeRequest $request, Application $application)
    {
        $this->applicationService->updateNarrative($request, $application);

        return redirect()->back()->banner('Brief Narrative was updated successfully.');
    }

    /**
     * @param  ApproveApplicationRequest  $request
     * @param  Application  $application
     * @return \Illuminate\Http\Response
     *
     * @throws \App\Exceptions\GeneralException
     * @throws \Throwable
     */
    public function approve(ApproveApplicationRequest $request, Application $application)
    {
        $this->applicationService->approve($application, $request);

        return redirect()->back()->banner('Application was approved successfully.');
    }

    /**
     * @param  DisapproveApplicationRequest  $request
     * @param  Application  $application
     * @return \Illuminate\Http\Response
     *
     * @throws \App\Exceptions\GeneralException
     * @throws \Throwable
     */
    public function disapprove(DisapproveApplicationRequest $request, Application $application)
    {
        $this->applicationService->disapprove($application, $request);

        return redirect()->back()->banner('Application was disapproved successfully.');
    }

    /**
     * @param  EndorseApplicationRequest  $request
     * @param  Application  $application
     * @return \Illuminate\Http\Response
     *
     * @throws \App\Exceptions\GeneralException
     * @throws \Throwable
     */
    public function endorse(EndorseApplicationRequest $request, Application $application)
    {
        $this->applicationService->endorse($application, $request);

        return redirect()->back()->banner('Application was endorsed successfully.');
    }

    /**
     * @param  ResolutionApplicationRequest  $request
     * @param  Application  $application
     * @return \Illuminate\Http\Response
     *
     * @throws \App\Exceptions\GeneralException
     * @throws \Throwable
     */
    public function resolution(ResolutionApplicationRequest $request, Application $application)
    {
        $this->applicationService->resolution($application, $request);

        return redirect()->back()->banner('Resolution was uploaded successfully.');
    }

    /**
     * @param  Application  $application
     * @return \Inertia\Response
     */
    public function viewQR(Application $application)
    {
        return Inertia::render('Application/QR', [
            'application' => new ApplicationQrResource($application),
        ]);
    }

    /**
     * @param  ExtendApplicationRequest  $request
     * @param  Application  $application
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \App\Exceptions\GeneralException
     * @throws \Throwable
     */
    public function extension(ExtendApplicationRequest $request, Application $application)
    {
        $application = $this->applicationService->extension($application);

        return redirect()->route('applications.edit', ['application' => $application]);
    }

    /**
     * @return \Inertia\Response
     */
    public function archive()
    {
        return Inertia::render('Application/Archive', [
            'applications' => ApplicationListResource::collection($this->applicationService->archive()),
        ]);
    }

    /**
     * @param  Application  $application
     * @return \Inertia\Response
     */
    public function showArchive(Application $application)
    {
        return Inertia::render('Application/Detail', [
            'applicationData' => new ApplicationDetailResource($application),
            'max_votes' => (config('atc.access.majority_count') * 2) - 1,
        ]);
    }

    /**
     * @return int
     */
    public function archiveCount()
    {
        return $this->applicationService->applicationCount('archive');
    }

    /**
     * @return int
     */
    public function availableCount()
    {
        return $this->applicationService->applicationCount('available');
    }

    /**
     * @return int
     */
    public function reviewCount()
    {
        return $this->applicationService->applicationCount('review');
    }

    /**
     * @return int
     */
    public function voteCount()
    {
        return $this->applicationService->applicationCount('vote');
    }
}
