<?php

namespace Botble\Member\Models;

use Botble\Member\Notifications\MemberResetPassword;
use Botble\Member\Supports\Gravatar;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class Member extends Authenticatable
{
    use Notifiable;
    use HasApiTokens;

    /**
     * @var string
     */
    protected $table = 'members';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'avatar',
        'dob',
        'phone',
        'confirmed_at',
        'description',
        'gender',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Send the password reset notification.
     *
     * @param  string $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new MemberResetPassword($token));
    }

    /**
     * @return string
     */
    public function getAvatarAttribute()
    {
        if (!$this->attributes['avatar']) {
            return (new Gravatar())->image($this->attributes['email']);
        }
        return url($this->attributes['avatar']);
    }

    /**
     * Always capitalize the first name when we retrieve it
     * @param string $value
     * @return string
     * @author Sang Nguyen
     */
    public function getFirstNameAttribute($value)
    {
        return ucfirst($value);
    }

    /**
     * Always capitalize the last name when we retrieve it
     * @param string $value
     * @return string
     * @author Sang Nguyen
     */
    public function getLastNameAttribute($value)
    {
        return ucfirst($value);
    }

    /**
     * @return string
     * @author Sang Nguyen
     */
    public function getFullName()
    {
        return ucfirst($this->first_name) . ' ' . ucfirst($this->last_name);
    }

    /**
     * @return string
     * @author Sang Nguyen
     */
    public function getProfileImage()
    {
        return $this->getAvatarAttribute();
    }

    /**
     * @return MorphTo
     */
    public function posts()
    {
        return $this->morphMany('Botble\Blog\Models\Post', 'author');
    }
}
