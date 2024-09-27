<?php

namespace App\Models\Attributes;

use Carbon\Carbon;

/**
 * Trait ApplicationAttribute.
 */
trait ApplicationAttribute
{
    /**
     * @return string
     */
    public function getArrestedFullnameAttribute()
    {
        return trim("{$this->arrested_firstname} {$this->arrested_middlename} {$this->arrested_lastname} {$this->arrested_suffix}");
    }

    /**
     * @return string
     */
    public function getApplicationExpirationAttribute()
    {
        if (empty($this->getAttributes()['when'])) {
            return null;
        }

        return (new Carbon($this->getAttributes()['when']))->addHours(config('atc.access.hours_remaining'));
    }

    /**
     * @return bool
     */
    public function getCanExtendAttribute()
    {
        return $this->canExtend();
    }

    /**
     * @param    $value
     * @return
     */
    public function getApprovedDateAttribute($value)
    {
        if (empty($value)) {
            return null;
        }

        return $this->militaryTimeFormat(new Carbon($value));
    }

    /**
     * @param    $value
     * @return
     */
    public function getDisapprovedDateAttribute($value)
    {
        if (empty($value)) {
            return null;
        }

        return $this->militaryTimeFormat(new Carbon($value));
    }

    /**
     * @param    $value
     * @return
     */
    public function getEndorsedDateAttribute($value)
    {
        if (empty($value)) {
            return null;
        }

        return $this->militaryTimeFormat(new Carbon($value));
    }

    /**
     * @return float|int
     */
    public function getTimeLeftAttribute()
    {
        if (empty($this->application_expiration)) {
            return 0;
        }

        $timeLeft = now();
        if (! empty($this->posted_date)) {
            $timeLeft = $this->posted_date;
        }

        return $this->application_expiration->diffInSeconds($timeLeft) * 1000;
    }

    /**
     * @param    $value
     * @return
     */
    public function getPostedDateAttribute($value)
    {
        if (empty($value)) {
            return null;
        }

        return $this->militaryTimeFormat(new Carbon($value));
    }

    /**
     * @param  string  $value
     */
    public function setWhenAttribute($value)
    {
        if (empty($value)) {
            return $this->attributes['when'] = null;
        }

        $when = Carbon::createFromFormat('Y-m-d H:i', $value);
        $detention_expiration = Carbon::createFromFormat('Y-m-d H:i', $value);

        $this->attributes['when'] = $when;
        $this->attributes['detention_expiration'] = ! $this->is_extension ? $detention_expiration->addDays(config('atc.access.days_of_detention')) : $detention_expiration->addDays(config('atc.access.days_of_detention') + config('atc.access.days_of_detention_extension'));
    }

    /**
     * @param  array  $value
     */
    public function setMentalConditionAttribute($value)
    {
        if (empty($value)) {
            return $this->attributes['mental_condition'] = null;
        }

        $this->attributes['mental_condition'] = serialize($value);
    }

    /**
     * @return array
     */
    public function getMentalConditionAttribute()
    {
        if (empty($this->getAttributes()['mental_condition'])) {
            return null;
        }

        return unserialize($this->getAttributes()['mental_condition']);
    }

    /**
     * @param  array  $value
     */
    public function setPhysicalConditionAttribute($value)
    {
        if (empty($value)) {
            return $this->attributes['physical_condition'] = null;
        }

        $this->attributes['physical_condition'] = serialize($value);
    }

    /**
     * @return array
     */
    public function getPhysicalConditionAttribute()
    {
        if (empty($this->getAttributes()['physical_condition'])) {
            return null;
        }

        return unserialize($this->getAttributes()['physical_condition']);
    }

    /**
     * @param  array  $value
     */
    public function setExtensionReasonAttribute($value)
    {
        if (empty($value)) {
            return $this->attributes['extension_reason'] = null;
        }

        $this->attributes['extension_reason'] = serialize($value);
    }

    /**
     * @return array
     */
    public function getExtensionReasonAttribute()
    {
        if (empty($this->getAttributes()['extension_reason'])) {
            return null;
        }

        return unserialize($this->getAttributes()['extension_reason']);
    }

    /**
     * @param  array  $value
     */
    public function setArrestedSpouseNameAttribute($value)
    {
        if (empty($value)) {
            return $this->attributes['arrested_spouse_name'] = null;
        }

        $this->attributes['arrested_spouse_name'] = serialize($value);
    }

    /**
     * @return array
     */
    public function getArrestedSpouseNameAttribute()
    {
        if (empty($this->getAttributes()['arrested_spouse_name'])) {
            return null;
        }

        return unserialize($this->getAttributes()['arrested_spouse_name']);
    }

    /**
     * @param  array  $value
     */
    public function setArrestedSpouseAddressAttribute($value)
    {
        if (empty($value)) {
            return $this->attributes['arrested_spouse_address'] = null;
        }

        $this->attributes['arrested_spouse_address'] = serialize($value);
    }

    /**
     * @return array
     */
    public function getArrestedSpouseAddressAttribute()
    {
        if (empty($this->getAttributes()['arrested_spouse_address'])) {
            return null;
        }

        return unserialize($this->getAttributes()['arrested_spouse_address']);
    }

    /**
     * @param  array  $value
     */
    public function setArrestedMarksAttribute($value)
    {
        if (empty($value)) {
            return $this->attributes['arrested_marks'] = null;
        }

        $this->attributes['arrested_marks'] = serialize($value);
    }

    /**
     * @return array
     */
    public function getArrestedMarksAttribute()
    {
        if (empty($this->getAttributes()['arrested_marks'])) {
            return null;
        }

        return unserialize($this->getAttributes()['arrested_marks']);
    }

    /**
     * @return string
     */
    public function getStatusLabelAttribute()
    {
        if ($this->status == self::DRAFT) {
            return '<span class="badge badge-success"> Draft </span>';
        }

        if ($this->status == self::AVAILABLE) {
            return '<span class="badge badge-primary"> Available </span>';
        }

        if ($this->status == self::DISAPPROVED) {
            return '<span class="badge badge-danger"> Disapproved </span>';
        }

        if ($this->status == self::ENDORSING) {
            return '<span class="badge badge-orange"> Endorsing </span>';
        }

        if ($this->status == self::VOTING) {
            return '<span class="badge badge-warning"> Voting </span>';
        }

        if ($this->status == self::APPROVED) {
            return '<span class="badge badge-success"> Approved </span>';
        }

        if ($this->status == self::EXPIRED) {
            return '<span class="badge badge-black"> Expired </span>';
        }
    }

    /**
     * @param  Carbon  $date
     * @return string
     */
    protected function militaryTimeFormat(Carbon $date)
    {
        return $date->format('d F y Hi').'H';
    }

    /**
     * @return bool
     */
    public function getCanUpdateDraftAttribute()
    {
        return $this->canUpdateDraft();
    }

    /**
     * @return bool
     */
    public function getCanUpdateAttribute()
    {
        return $this->canUpdate();
    }

    /**
     * @return bool
     */
    public function getCanApproveAttribute()
    {
        return $this->canApprove();
    }

    /**
     * @return bool
     */
    public function getCanDisapproveAttribute()
    {
        return $this->canDisapprove();
    }

    /**
     * @return bool
     */
    public function getCanCommentAttribute()
    {
        return $this->canComment();
    }

    /**
     * @return bool
     */
    public function getCanEndorseAttribute()
    {
        return $this->canEndorse();
    }

    /**
     * @return bool
     */
    public function getCanVoteAttribute()
    {
        return $this->canVote();
    }

    /**
     * @return bool
     */
    public function getCanProvideResolutionAttribute()
    {
        return $this->canProvideResolution();
    }

    /**
     * @return bool
     */
    public function getCanEditNarrativeAttribute()
    {
        return $this->canEditNarrative();
    }
}
