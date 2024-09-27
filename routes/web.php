<?php

use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VoteController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('login');
});

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/application', [ApplicationController::class, 'index'])->name('applications');
    Route::get('/application/create', [ApplicationController::class, 'create'])->name('applications.create');
    Route::post('/application', [ApplicationController::class, 'store'])->name('applications.store');
    Route::post('/application/draft', [ApplicationController::class, 'draft'])->name('applications.draft');
    Route::get('/application/archive', [ApplicationController::class, 'archive'])->name('applications.archive');
    Route::get('/application/archive/{application}', [ApplicationController::class, 'showArchive'])->name('applications.archive.show');

    Route::get('/application/available/count', [ApplicationController::class, 'availableCount']);
    Route::get('/application/review/count', [ApplicationController::class, 'reviewCount']);
    Route::get('/application/vote/count', [ApplicationController::class, 'voteCount']);
    Route::get('/application/archive/count', [ApplicationController::class, 'archiveCount']);

    Route::post('/application/temporary/upload', [MediaController::class, 'uploadTemporaryFile'])->name('upload.temporary');
    Route::delete('/application/temporary/upload', [MediaController::class, 'removeTemporaryFile'])->name('upload.temporary.destroy');

    Route::group(['prefix' => '/application/{application}'], function () {
        Route::get('/', [ApplicationController::class, 'show'])->name('applications.show');
        Route::get('/edit', [ApplicationController::class, 'edit'])->name('applications.edit');
        Route::put('/update', [ApplicationController::class, 'update'])->name('applications.update');
        Route::get('/duplicate', [ApplicationController::class, 'duplicate'])->name('applications.duplicate');
        Route::post('/duplicate', [ApplicationController::class, 'duplicateStore'])->name('applications.duplicate.store');
        Route::delete('/draft', [ApplicationController::class, 'deleteDraft'])->name('applications.delete.draft');
        Route::put('/draft', [ApplicationController::class, 'updateDraft'])->name('applications.update.draft');
        Route::put('/draft/submit', [ApplicationController::class, 'submitDraft'])->name('applications.draft.submit');
        Route::post('/approve', [ApplicationController::class, 'approve'])->name('applications.approve');
        Route::post('/disapprove', [ApplicationController::class, 'disapprove'])->name('applications.disapprove');
        Route::post('/endorse', [ApplicationController::class, 'endorse'])->name('applications.endorse');
        Route::post('/resolution', [ApplicationController::class, 'resolution'])->name('applications.resolution');
        Route::post('/extension', [ApplicationController::class, 'extension'])->name('applications.extension');

        Route::post('/comment', [CommentController::class, 'comment'])->name('applications.comment');
        Route::post('/vote', [VoteController::class, 'vote'])->name('applications.vote');

        Route::patch('/brief-narrative/edit', [ApplicationController::class, 'updateNarrative'])->name('appliations.narrative');

        Route::get('/resolution/download', [ApplicationController::class, 'downloadResolutionDraft']);
        Route::get('/file', [ApplicationController::class, 'downloadFile']);
    });

    Route::group(['prefix' => '/users', 'middleware' => ['can:manage user']], function () {
        Route::get('/', [UserController::class, 'index'])->name('users');
        Route::get('/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/', [UserController::class, 'store'])->name('users.store');

        Route::group(['prefix' => '/{user}'], function () {
            Route::get('/edit', [UserController::class, 'edit'])->name('users.edit');
            Route::put('/update', [UserController::class, 'update'])->name('users.update');
        });
    });

    Route::get('/roles', [RoleController::class, 'index']);
    Route::get('/permissions', [PermissionController::class, 'index']);

    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/font', [UserController::class, 'changeFontSize'])->name('font.change');
});

Route::get('/qrcode/{application:control_number} ', [ApplicationController::class, 'viewQR'])->name('applications.qr');
