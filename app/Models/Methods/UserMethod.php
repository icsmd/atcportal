<?php

namespace App\Models\Methods;

/**
 * Trait UserMethod.
 */
trait UserMethod
{
    /**
     * @return bool
     */
    public function canSendApplication()
    {
        return optional(auth()->user())->can('send application');
    }

    /**
     * @return bool
     */
    public function canUpdateApplication()
    {
        return optional(auth()->user())->can('update application');
    }

    /**
     * @return bool
     */
    public function canApproveApplication()
    {
        return optional(auth()->user())->can('approve application');
    }

    /**
     * @return bool
     */
    public function canDisapproveApplication()
    {
        return optional(auth()->user())->can('disapprove application');
    }

    /**
     * @return bool
     */
    public function canViewOtherApplication()
    {
        return ! optional(auth()->user())->can('restrict view other application');
    }

    /**
     * @return bool
     */
    public function canViewDiscussion()
    {
        return optional(auth()->user())->can('view discussion');
    }

    /**
     * @return bool
     */
    public function canEndorseApplication()
    {
        return optional(auth()->user())->can('endorse application');
    }

    /**
     * @return bool
     */
    public function canViewVote()
    {
        return optional(auth()->user())->can('view vote');
    }

    /**
     * @return bool
     */
    public function canProvideResolution()
    {
        return optional(auth()->user())->can('provide resolution');
    }

    /**
     * @return bool
     */
    public function canManageUser()
    {
        return optional(auth()->user())->can('manage user');
    }
}
