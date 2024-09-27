<?php

namespace App\Http\Resources\Application;

use App\Models\Application;
use Illuminate\Http\Resources\Json\JsonResource;

class ApplicationQrResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $application = Application::where('control_number', $this->control_number)->where('is_extension', true)->first();

        return  [
            'resolution' => [
                'file' => $this->getFileUrl('resolution'),
                'attachments' => $this->getResolutionDocuments(),
            ],
            'extension' => [
                'file' => ! empty($application) ? $application->getFileUrl('resolution') : '',
                'attachments' => ! empty($application) ? $application->getResolutionDocuments() : [],
            ],
        ];
    }
}
