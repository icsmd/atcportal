<?php

namespace App\Http\Controllers;

use App\Http\Resources\Dashboard\DashboardAppointmentResource;
use App\Services\ApplicationService;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * @var ApplicationService
     */
    protected $applicationService;

    /**
     * DashboardController constructor.
     *
     * @param  ApplicationService  $applicationService
     */
    public function __construct(ApplicationService $applicationService)
    {
        $this->applicationService = $applicationService;
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Inertia::render('Dashboard', [
            'applications' => DashboardAppointmentResource::collection($this->applicationService->search(request()->only('with_draft', 'control_number'))),
            'available_count' => $this->applicationService->applicationCount('available'),
            'review_count' => $this->applicationService->applicationCount('review'),
            'vote_count' => $this->applicationService->applicationCount('vote'),
            'archive_count' => $this->applicationService->applicationCount('archive'),
        ]);
    }
}
