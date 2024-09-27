<?php

namespace App\Models\Relationships;

use App\Models\Application;
use App\Models\User;

/**
 * Class CommentRelationship.
 */
trait CommentRelationship
{
    /**
     * @return mixed
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return mixed
     */
    public function application()
    {
        return $this->belongsTo(Application::class);
    }
}
