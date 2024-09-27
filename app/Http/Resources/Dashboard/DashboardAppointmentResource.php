<?php

namespace App\Http\Resources\Dashboard;

use Illuminate\Http\Resources\Json\JsonResource;

class DashboardAppointmentResource extends JsonResource
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
            'when' => ! empty($this->when) ? $this->when->format('d F Y Hi').'H' : '',
            'application_expiration' => $this->time_left,
            'progress' => 100,
            'status' => $this->status,
            'status_label' => $this->status_label,
            'hasResolution' => $this->getFileUrl('resolution'),
            'is_extension' => $this->is_extension,
        ];
    }
}
