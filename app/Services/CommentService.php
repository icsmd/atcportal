<?php

namespace App\Services;

use App\Events\Application\CommentPosted;
use App\Exceptions\GeneralException;
use App\Models\Application;
use App\Models\Comment;
use Exception;
use Illuminate\Support\Facades\DB;

/**
 * Class CommentService.
 */
class CommentService extends BaseService
{
    /**
     * CommentService constructor.
     *
     * @param  Comment  $comment
     */
    public function __construct(Comment $comment)
    {
        $this->model = $comment;
    }

    /**
     * @param  array  $data
     * @param  Application  $application
     * @return Comment
     *
     * @throws GeneralException
     * @throws \Throwable
     */
    public function comment(array $data, Application $application): Comment
    {
        DB::beginTransaction();

        try {
            $comment = $application->addComment([
                'user_id' => auth()->user()->id,
                'body' => $data['body'],
            ]);

            event(new CommentPosted($application->id, $comment));
        } catch (Exception $e) {
            DB::rollBack();
            \Log::debug($e->getMessage());

            throw new GeneralException('There was a problem creating this comment. Please try again.');
        }

        DB::commit();

        return $comment;
    }

    /**
     * @param  array  $data
     * @return Comment
     */
    protected function createComment(array $data = []): Comment
    {
        return $this->model::create([
            'user_id' => $data['user_id'],
            'application_id' => $data['application_id'],
            'body' => $data['body'],
        ]);
    }
}
