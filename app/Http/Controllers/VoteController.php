<?php

namespace App\Http\Controllers;

use App\Http\Requests\Vote\CreateVoteRequest;
use App\Models\Application;
use App\Services\VoteService;

class VoteController extends Controller
{
    /**
     * @var VoteService
     */
    protected $voteService;

    /**
     * VoteController constructor.
     *
     * @param  VoteService  $voteService
     */
    public function __construct(VoteService $voteService)
    {
        $this->voteService = $voteService;
    }

    /**
     * @param  CreateVoteRequest  $request
     * @param  Application  $application
     * @return \Illuminate\Http\Response
     *
     * @throws \App\Exceptions\GeneralException
     * @throws \Throwable
     */
    public function vote(CreateVoteRequest $request, Application $application)
    {
        if ($application->isAlreadyVoted()) {
            return response()->json('You have already voted.', 403);
        }

        $this->voteService->vote($request->only('message', 'status'), $application);

        return redirect()->back()->banner('Vote was posted successfully.');
    }
}
