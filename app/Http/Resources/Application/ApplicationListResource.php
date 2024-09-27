<?php

namespace App\Http\Resources\Application;

use Illuminate\Http\Resources\Json\JsonResource;

class ApplicationListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return  [
            'id' => $this->id,
            'control_number' => $this->control_number,
            'officer' => $this->name,
            'when' => ! empty($this->when) ? $this->when->format('d F Y Hi').'H' : '',
            'person' => $this->arrested_fullname,
            'status_label' => $this->status_label,
            'status' => $this->status,
            'can_update' => $this->can_update,
            'can_extend' => $this->can_extend,
            'is_extension' => $this->is_extension,
            'can_update_draft' => $this->can_update_draft,
            'application_expiration' => $this->time_left,
            'processed_time' => $this->getProcessedTime(),
            'hasResolution' => $this->getFileUrl('resolution'),
        ];
    }
}
