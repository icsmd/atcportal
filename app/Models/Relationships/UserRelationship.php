<?php

namespace App\Models\Relationships;

use App\Models\Application;

/**
 * Class UserRelationship.
 */
trait UserRelationship
{
    /**
     * @return mixed
     */
    public function applications()
    {
        return $this->hasMany(Application::class);
    }
}
