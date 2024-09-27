<?php

namespace App\MediaLibrary;

use App\Models\Application;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\PathGenerator\PathGenerator;

class CustomPathGenerator implements PathGenerator
{
    public function getPath(Media $media): string
    {
        if ($media->collection_name == 'temporary') {
            return 'temporary/'.auth()->user()->id.'/';
        }

        $application = Application::find($media->model_id);

        if ($media->collection_name == 'draft') {
            return 'drafts/'.auth()->user()->id.'/'.$application->id.'/'.$media->name.'/';
        }

        if ($application->is_extension) {
            return $application->control_number.'/extension/'.$media->collection_name.'/';
        }

        return $application->control_number.'/'.$media->collection_name.'/';
    }

    public function getPathForConversions(Media $media): string
    {
        return $this->getPath($media).'converted/';
    }

    public function getPathForResponsiveImages(Media $media): string
    {
        return $this->getPath($media).'responsive/';
    }
}
