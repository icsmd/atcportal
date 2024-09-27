<?php

namespace App\Http\Controllers;

use App\Http\Requests\Media\UploadTemporaryDocumentRequest;
use App\Services\MediaFileService;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    /**
     * @var MediaFileService
     */
    protected $mediaService;

    /**
     * MediaController constructor.
     *
     * @param  MediaFileService  $mediaService
     */
    public function __construct(MediaFileService $mediaService)
    {
        $this->mediaService = $mediaService;
    }

    /**
     * @param  UploadTemporaryDocumentRequest  $request
     * @return string
     */
    public function uploadTemporaryFile(UploadTemporaryDocumentRequest $request)
    {
        return $this->mediaService->uploadTemporaryFile();
    }

    /**
     * @return \Inertia\Inertia
     */
    public function removeTemporaryFile(Request $request)
    {
        return $this->mediaService->removeTemporaryFile($request->getContent());
    }
}
