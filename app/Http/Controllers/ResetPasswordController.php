<?php

namespace App\Http\Controllers;

use App\Http\Requests\PasswordReset\PasswordResetRequest;
use App\Http\Requests\PasswordReset\SendPasswordResetRequest;
use App\Services\UserService;

class ResetPasswordController extends Controller
{
    /**
     * @var UserService
     */
    protected $userService;

    /**
     * ResetPasswordController constructor.
     *
     * @param  UserService  $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @param  SendPasswordResetRequest  $request
     * @return \Illuminate\Http\Response
     *
     * @throws \App\Exceptions\GeneralException
     * @throws \Throwable
     */
    public function sendPasswordReset(SendPasswordResetRequest $request)
    {
        $this->userService->sendPasswordReset($request->only('email'));

        return response()->json('Password Reset Link was sent successfully.');
    }

    /**
     * @param  PasswordResetRequest  $request
     * @return \Illuminate\Http\Response
     *
     * @throws \App\Exceptions\GeneralException
     * @throws \Throwable
     */
    public function resetPassword(PasswordResetRequest $request)
    {
        $this->userService->resetPassword($request->only('email', 'password', 'password_confirmation', 'token'));

        return response()->json('Password Reset successfully.');
    }
}
