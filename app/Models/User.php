<?php

namespace App\Models;

use App\Models\Attributes\UserAttribute;
use App\Models\Methods\UserMethod;
use App\Models\Relationships\UserRelationship;
use App\Models\Scopes\UserGlobalScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements Auditable, HasMedia
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use InteractsWithMedia;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use HasRoles;
    use UserAttribute;
    use UserRelationship;
    use UserMethod;
    use \OwenIt\Auditing\Auditable;

    public const FONT_SMALL = 'small';

    public const FONT_MEDIUM = 'medium';

    public const FONT_LARGE = 'large';

    public const FONT_HUGE = 'huge';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'tel',
        'password',
        'font_size',
        'active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'active_label',
        'profile_photo_url',
        'can_send_application',
        'can_update_application',
        'can_approve_application',
        'can_disapprove_application',
        'can_endorse_application',
        'can_provide_resolution',
        'can_manage_user',
        'can_view_discussion',
        'can_view_other_application',
        'can_view_vote',
    ];

    /**
     * @var string[]
     */
    protected $with = [
        'roles',
        'roles.permissions',
        'permissions',
    ];

    /**
     * Scope a query to only include restricted view users.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRestrictedUser($query)
    {
        return $query->whereHas('permissions', function (Builder $query) {
            $query->where('name', config('atc.access.permission.restrict_view'));
        });
    }

    /**
     * Scope a query to only include users that has view disucssion permission.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeViewDiscussionUser($query)
    {
        return $query->whereHas('permissions', function (Builder $query) {
            $query->where('name', config('atc.access.permission.view_discussion'));
        });
    }

    /**
     * Scope a query to only include users that has view vote permission.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeViewVoteUser($query)
    {
        return $query->whereHas('permissions', function (Builder $query) {
            $query->where('name', config('atc.access.permission.view_vote'));
        });
    }

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope(new UserGlobalScope);
    }

    /**
     * Return the SMS notification routing information.
     *
     * @param  \Illuminate\Notifications\Notification|null  $notification
     * @return mixed
     */
    public function routeNotificationForSms(?Notification $notification = null)
    {
        return $this->tel;
    }
}
