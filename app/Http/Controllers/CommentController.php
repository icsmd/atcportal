<?php

namespace App\Http\Controllers;

use App\Http\Requests\Comment\CreateCommentRequest;
use App\Models\Application;
use App\Services\CommentService;

class CommentController extends Controller
{
    /**
     * @var CommentService
     */
    protected $commentService;

    /**
     * CommentController constructor.
     *
     * @param  CommentService  $commentService
     */
    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    /**
     * @param  CreateCommentRequest  $request
     * @param  Application  $application
     * @return \Illuminate\Http\Response
     *
     * @throws \App\Exceptions\GeneralException
     * @throws \Throwable
     */
    public function comment(CreateCommentRequest $request, Application $application)
    {
        $this->commentService->comment($request->only('body'), $application);

        return redirect()->back()->banner('Comment was posted successfully.');
    }
}
