<?php

namespace App\Models;

use App\Models\Attributes\ApplicationAttribute;
use App\Models\Methods\ApplicationMethod;
use App\Models\Relationships\ApplicationRelationship;
use App\Models\Scopes\ApplicationGlobalScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Application extends Model implements Auditable, HasMedia
{
    use HasFactory,
        ApplicationMethod,
        ApplicationRelationship,
        ApplicationAttribute,
        InteractsWithMedia,
        \OwenIt\Auditing\Auditable;

    //Application Status
    public const DRAFT = 'draft';

    public const AVAILABLE = 'available';

    public const DISAPPROVED = 'disapproved';

    public const ENDORSING = 'endorsing';

    public const VOTING = 'voting';

    public const APPROVED = 'approved';

    public const EXPIRED = 'expired';

    //Application Content
    public const MALE = 'male';

    public const FEMALE = 'female';

    public const SINGLE = 'single';

    public const MARRIED = 'married';

    public const WIDOWED = 'widowed';

    public const SEPARATED = 'separated';

    public const ELDERLY = 'elderly';

    public const PWD = 'pwd';

    public const PREGNANT_WOMAN = 'pregnant_woman';

    public const WOMAN = 'woman';

    public const CHILDREN = 'children';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'control_number',
        'name',
        'rank',
        'badge_number',
        'unit',
        'office_address',
        'tel',
        'arrested_firstname',
        'arrested_middlename',
        'arrested_lastname',
        'arrested_suffix',
        'arrested_address',
        'arrested_tel',
        'arrested_pob',
        'arrested_dob',
        'arrested_age',
        'arrested_category',
        'arrested_sex',
        'arrested_status',
        'arrested_spouse_name',
        'arrested_spouse_address',
        'arrested_weight',
        'arrested_height',
        'arrested_eyes',
        'arrested_hair',
        'arrested_complexion',
        'arrested_occupation',
        'arrested_nationality',
        'arrested_tribe',
        'arrested_language',
        'arrested_educ_attainment',
        'arrested_school_name',
        'arrested_school_address',
        'arrested_marks',
        'arrested_location_marks',
        'arrested_defect',
        'who',
        'when',
        'where',
        'what',
        'how',
        'why',
        'other_details',
        'is_informed_of_right',
        'mental_condition',
        'physical_condition',
        'extension_reason',
        'approved_remarks',
        'approved_date',
        'disapproved_remarks',
        'disapproved_date',
        'endorsed_remarks',
        'endorsed_date',
        'reason_narration',
        'final_resolution',
        'is_extension',
        'expiration_notified',
        'detention_expiration',
        'date_submitted',
        'posted_date',
        'status',
    ];

    /**
     * @var array
     */
    protected $dates = [
        'arrested_dob',
        'when',
        'date_submitted',
        'posted_date',
        'approved_date',
        'disapproved_date',
        'endorsed_date',
        'detention_expiration',
        'final_resolution',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'arrested_fullname',
        'application_expiration',
        'can_approve',
        'can_comment',
        'can_disapprove',
        'can_endorse',
        'can_update_draft',
        'can_update',
        'can_extend',
        'can_provide_resolution',
        'can_vote',
        'can_comment',
        'status_label',
    ];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope(new ApplicationGlobalScope);
    }

    /**
     * Scope a query to get all expired applications.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeExpiredApplication($query)
    {
        return $query->where('when', '<=', now()->subHours(config('atc.access.hours_remaining')))
                ->whereNotIn('status', [self::DRAFT, self::DISAPPROVED, self::EXPIRED])
                ->whereNull('final_resolution');
    }

    /**
     * Scope a query to get all archived applications.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeArchivedApplication($query)
    {
        return $query->where('detention_expiration', '<=', now())
                ->orWhere('status', self::DISAPPROVED)
                ->orWhere('status', self::EXPIRED);
    }

    /**
     * Scope a query to get all archived applications.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAvailableApplication($query)
    {
        return $query->where('status', self::AVAILABLE)
                    ->where('detention_expiration', '>', now());
    }

    /**
     * Scope a query to get all archived applications.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeReviewApplication($query)
    {
        return $query->where('status', self::ENDORSING)
                    ->where('detention_expiration', '>', now());
    }

    /**
     * Scope a query to get all archived applications.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeVoteApplication($query)
    {
        return $query->where('status', self::VOTING)
                    ->where('detention_expiration', '>', now());
    }
}
