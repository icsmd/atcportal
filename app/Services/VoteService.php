<?php

namespace App\Services;

use App\Events\Application\ApplicationVoted;
use App\Exceptions\GeneralException;
use App\Models\Application;
use App\Models\Vote;
use Exception;
use Illuminate\Support\Facades\DB;
use Log;

/**
 * Class VoteService.
 */
class VoteService extends BaseService
{
    /**
     * VoteService constructor.
     *
     * @param  Vote  $vote
     */
    public function __construct(Vote $vote)
    {
        $this->model = $vote;
    }

    /**
     * @param  array  $data
     * @param  Application  $application
     * @return Vote
     *
     * @throws GeneralException
     * @throws \Throwable
     */
    public function vote(array $data, Application $application): Vote
    {
        DB::beginTransaction();

        try {
            $vote = $application->addVote([
                'user_id' => auth()->user()->id,
                'message' => $data['message'],
                'status' => $data['status'],
            ]);

            event(new ApplicationVoted($application, $vote));
        } catch (Exception $e) {
            DB::rollBack();
            Log::debug($e->getMessage());

            throw new GeneralException('There was a problem creating this vote. Please try again.');
        }

        DB::commit();

        return $vote;
    }
}
