<?php

namespace App\Http\Resources\Application;

use Illuminate\Http\Resources\Json\JsonResource;

class ApplicationDetailResource extends JsonResource
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
            'status' => $this->status_label,
            'status_data' => $this->status,
            'date_submitted' => ! empty($this->date_submitted) ? $this->date_submitted->format('d F Y Hi').'H' : '',
            'when' => ! empty($this->when) ? $this->when->format('d F Y Hi').'H' : '',
            'where' => $this->where,
            'what' => $this->what,
            'how' => $this->how,
            'other_details' => $this->other_details,
            'arrested_picture' => $this->getFileUrl('arrested_picture'),
            'arrested_fullname' => $this->arrested_fullname,
            'arrested_age' => $this->arrested_age,
            'arrested_sex' => ucfirst($this->arrested_sex),
            'sworn_affidavit' => $this->getFileUrl('sworn_affidavit'),
            'logbook' => $this->getFileUrl('logbook'),
            'book_sheet' => $this->getFileUrl('book_sheet'),
            'spot_report' => $this->getFileUrl('spot_report'),
            'sworn_affidavit_witness' => $this->getFileUrl('sworn_affidavit_witness'),
            'warrantless_arrest' => $this->getFileUrl('warrantless_arrest'),
            'supporting_documents' => $this->getSupportingDocuments(),
            'arrested_address' => $this->arrested_address,
            'arrested_tel' => $this->arrested_tel,
            'arrested_pob' => $this->arrested_pob,
            'arrested_dob' => ! empty($this->arrested_dob) ? $this->arrested_dob->format('d F Y') : '',
            'arrested_status' => ucfirst($this->arrested_status),
            'arrested_spouse' => $this->arrested_spouse,
            'arrested_spouse_address' => $this->arrested_spouse_address,
            'arrested_weight' => $this->arrested_weight,
            'arrested_height' => $this->arrested_height,
            'arrested_school_name' => $this->arrested_school_name,
            'arrested_school_address' => $this->arrested_school_address,
            'arrested_educ_attainment' => $this->arrested_educ_attainment,
            'arrested_eyes' => ucfirst($this->arrested_eyes),
            'arrested_hair' => ucfirst($this->arrested_hair),
            'arrested_complexion' => ucfirst($this->arrested_complexion),
            'arrested_nationality' => $this->arrested_nationality,
            'arrested_occupation' => $this->arrested_occupation,
            'arrested_tribe' => $this->arrested_tribe,
            'arrested_language' => $this->arrested_language,
            'arrested_marks' => ! empty($this->arrested_marks) ? implode(',', $this->arrested_marks) : '',
            'arrested_location_marks' => $this->arrested_location_marks,
            'arrested_defect' => $this->arrested_defect,
            'name' => $this->name,
            'rank' => $this->rank,
            'badge_number' => $this->badge_number,
            'unit' => $this->unit,
            'office_address' => $this->office_address,
            'tel' => $this->tel,
            'reason_narration' => $this->reason_narration,
            'extension_reason' => $this->extension_reason,
            'mental_condition' => $this->mental_condition,
            'physical_condition' => $this->physical_condition,
            'arrested_category' => $this->arrested_category,
            'is_informed_of_right' => $this->is_informed_of_right,
            'application_expiration' => $this->time_left,
            'processed_time' => $this->getProcessedTime(),
            'approve' => [
                'remarks' => $this->approved_remarks,
                'date' => $this->approved_date,
                'attachments' => $this->getApprovedDocuments(),
            ],
            'disapprove' => [
                'remarks' => $this->disapproved_remarks,
                'date' => $this->disapproved_date,
                'attachments' => $this->getDisapprovedDocuments(),
            ],
            'can_approve' => $this->can_approve,
            'can_disapprove' => $this->can_disapprove,
            'can_comment' => $this->can_comment,
            'can_endorse' => $this->can_endorse,
            'can_edit_narrative' => $this->can_edit_narrative,
            'can_vote' => $this->can_vote,
            'endorse' => [
                'remarks' => $this->endorsed_remarks,
                'date' => $this->endorsed_date,
                'attachments' => $this->getEndorsedDocuments(),
            ],
            'resolution_draft' => $this->getFileUrl('resolution_draft'),
            'can_provide_resolution' => $this->can_provide_resolution,
            'resolution' => [
                'date' => $this->posted_date,
                'file' => $this->getFileUrl('resolution'),
                'attachments' => $this->getResolutionDocuments(),
            ],
        ];
    }
}
