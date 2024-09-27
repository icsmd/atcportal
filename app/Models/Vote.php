<?php

namespace App\Models;

use App\Models\Attributes\VoteAttribute;
use App\Models\Relationships\VoteRelationship;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Vote extends Model implements Auditable
{
    use HasFactory,
        VoteAttribute,
        VoteRelationship,
        \OwenIt\Auditing\Auditable;

    public const APPROVED = 'approved';

    public const DISAPPROVED = 'disapproved';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'application_id',
        'message',
        'status',
    ];

    /**
     * @var string[]
     */
    protected $with = [
        'user',
    ];
}
