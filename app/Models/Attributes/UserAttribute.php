<?php

namespace App\Models\Attributes;

use Illuminate\Support\Facades\Hash;

/**
 * Trait UserAttribute.
 */
trait UserAttribute
{
    /**
     * @param $password
     */
    public function setPasswordAttribute($password): void
    {
        // If password was accidentally passed in already hashed, try not to double hash it
        // Note: Password Histories are logged from the \App\Domains\Auth\Observer\UserObserver class
        $this->attributes['password'] =
            (strlen($password) === 60 && preg_match('/^\$2y\$/', $password)) ||
            (strlen($password) === 95 && preg_match('/^\$argon2i\$/', $password)) ?
                $password :
                Hash::make($password);
    }

    /**
     * @return string
     */
    public function getActiveLabelAttribute()
    {
        if ($this->active) {
            return '<span class="badge badge-success"> ACTIVE </span>';
        }

        return '<span class="badge badge-danger"> INACTIVE </span>';
    }

    /**
     * @return bool
     */
    public function getCanSendApplicationAttribute()
    {
        return $this->canSendApplication();
    }

    /**
     * @return bool
     */
    public function getCanUpdateApplicationAttribute()
    {
        return $this->canUpdateApplication();
    }

    /**
     * @return bool
     */
    public function getCanApproveApplicationAttribute()
    {
        return $this->canApproveApplication();
    }

    /**
     * @return bool
     */
    public function getCanDisapproveApplicationAttribute()
    {
        return $this->canDisapproveApplication();
    }

    /**
     * @return bool
     */
    public function getCanViewOtherApplicationAttribute()
    {
        return $this->canViewOtherApplication();
    }

    /**
     * @return bool
     */
    public function getCanViewDiscussionAttribute()
    {
        return $this->canViewDiscussion();
    }

    /**
     * @return bool
     */
    public function getCanEndorseApplicationAttribute()
    {
        return $this->canEndorseApplication();
    }

    /**
     * @return bool
     */
    public function getCanViewVoteAttribute()
    {
        return $this->canViewVote();
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
    public function getCanManageUserAttribute()
    {
        return $this->canManageUser();
    }
}
