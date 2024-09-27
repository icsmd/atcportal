<?php

namespace App\Models;

use App\Models\Attributes\CommentAttribute;
use App\Models\Relationships\CommentRelationship;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Comment extends Model implements Auditable
{
    use HasFactory,
        CommentAttribute,
        CommentRelationship,
        \OwenIt\Auditing\Auditable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'application_id',
        'body',
    ];

    /**
     * @var string[]
     */
    protected $with = [
        'user',
    ];
}
