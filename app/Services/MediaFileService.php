<?php

namespace App\Services;

use App\Exceptions\GeneralException;
use App\Models\Application;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * Class MediaFileService.
 */
class MediaFileService extends BaseService
{
    /**
     * MediaFileService constructor.
     *
     * @param  Media  $media
     */
    public function __construct(Media $media)
    {
        $this->model = $media;
    }

    /**
     * @param  array  $data
     * @param  Application  $application
     * @return string
     *
     * @throws GeneralException
     * @throws \Throwable
     */
    public function uploadTemporaryFile()
    {
        if (request()->document->getSize() > 1073741824) { //1GB
            throw new GeneralException('Exceed File Size Limit. Please Upload maximum of 1GB per file');
        }

        $name = md5(\Str::uuid());
        $ext = request()->file('document')->extension();

        $media = auth()->user()->addMediaFromRequest('document')
                    ->preservingOriginal()
                    ->usingFileName("$name.$ext")
                    ->toMediaCollection('temporary');

        return $media->uuid;
    }

    /**
     * @param  string  $uuid
     * @return void
     */
    public function removeTemporaryFile($uuid)
    {
        $media = $this->model->where('uuid', $uuid)->first();
        auth()->user()->deleteMedia($media->id);
    }
}
