<?php

namespace App\Listeners\Application;

class UploadSupportFiles
{
    /**
     * Handle the event.
     *
     * @param    $event
     * @return void
     */
    public function handle($event)
    {
        $application = $event->application;
        $files = $event->files ?? [];
        $type = $event->type;

        foreach ($files as $file) {
            $uploadedFiles[] = [
                'type' => $type,
                'file_path' => $file->storePublicly($application->getFilePath()),
                'file_name' => $file->getClientOriginalName(),
            ];
        }

        if (! empty($uploadedFiles)) {
            $application->mediaFiles()->createMany($uploadedFiles);
        }
    }
}
