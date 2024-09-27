<?php

namespace App\Models\Relationships;

use App\Models\BriefNarrativeHistory;
use App\Models\Comment;
use App\Models\User;
use App\Models\Vote;

/**
 * Class ApplicationRelationship.
 */
trait ApplicationRelationship
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
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * @return mixed
     */
    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    /**
     * @return mixed
     */
    public function narrativeHistories()
    {
        return $this->hasMany(BriefNarrativeHistory::class);
    }
}
