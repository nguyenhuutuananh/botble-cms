<?php

namespace Botble\ACL\Models;

use Auth;
use Botble\ACL\Notifications\ResetPasswordNotification;
use Botble\ACL\Traits\PermissionTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Lab404\Impersonate\Models\Impersonate;
use Laravel\Passport\HasApiTokens;

/**
 * Class User
 * @package Botble\ACL\Models
 * @mixin \Eloquent
 */
class User extends Authenticatable
{
    use PermissionTrait;
    use Notifiable;
    use Impersonate;
    use HasApiTokens;

    /**
     * {@inheritDoc}
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'email',
        'first_name',
        'last_name',
        'address',
        'password',
        'secondary_address',
        'dob',
        'job_position',
        'phone',
        'secondary_phone',
        'secondary_email',
        'gender',
        'website',
        'skype',
        'facebook',
        'twitter',
        'google_plus',
        'youtube',
        'github',
        'interest',
        'about',
        'super_user',
        'profile_image',
        'permissions',
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
     * The date fields for the model.clear
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'dob',
    ];

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
        if (empty($this->profile_image)) {
            return config('core.acl.general.avatar.default');
        }

        return $this->profile_image;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     * @author Sang Nguyen
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_users', 'user_id', 'role_id')->withTimestamps();
    }

    /**
     * @return boolean
     * @author Sang Nguyen
     */
    public function isSuperUser()
    {
        return $this->super_user || $this->hasAccess('superuser');
    }

    /**
     * @param string $permission
     * @return boolean
     * @author Sang Nguyen
     */
    public function hasPermission($permission)
    {
        if ($this->isSuperUser()) {
            return true;
        }

        return $this->hasAccess($permission);
    }

    /**
     * @param array $permissions
     * @return bool
     * @author Sang Nguyen
     */
    public function hasAnyPermission(array $permissions)
    {
        if ($this->isSuperUser()) {
            return true;
        }

        return $this->hasAnyAccess($permissions);
    }

    /**
     * @return array
     */
    public function authorAttributes()
    {
        return [
            'name'   => $this->getFullName(),
            'email'  => $this->email,
            'url'    => $this->website,    // optional
            'avatar' => 'gravatar', // optional
        ];
    }

    /**
     * @param string $date
     * @author Sang Nguyen
     */
    public function setDobAttribute($date)
    {
        if (!empty($date)) {
            $this->attributes['dob'] = Carbon::createFromFormat(config('core.base.general.date_format.date'), $date)->toDateTimeString();
        } else {
            $this->attributes['dob'] = $date;
        }
    }

    /**
     * @param $date
     * @author Sang Nguyen
     * @return string
     */
    public function getDobAttribute($date)
    {
        return date_from_database($date, config('core.base.general.date_format.date'));
    }

    /**
     * @param string $value
     * @return array
     */
    public function getPermissionsAttribute($value)
    {
        try {
            return json_decode($value, true) ?: [];
        } catch (Exception $exception) {
            return [];
        }
    }

    /**
     * Send the password reset notification.
     *
     * @param  string $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    /**
     * Returns the activations relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function activations()
    {
        return $this->hasMany(Activation::class, 'user_id');
    }

    /**
     * Set mutator for the "permissions" attribute.
     *
     * @param array $permissions
     * @return void
     */
    public function setPermissionsAttribute(array $permissions)
    {
        $this->attributes['permissions'] = $permissions ? json_encode($permissions) : '';
    }

    /**
     * {@inheritDoc}
     */
    public function inRole($role)
    {
        $roleId = null;
        if ($role instanceof Role) {
            $roleId = $role->getKey();
        }

        foreach ($this->roles as $instance) {
            /**
             * @var Role $instance
             */
            if ($role instanceof Role) {
                if ($instance->getKey() === $roleId) {
                    return true;
                }
            } elseif ($instance->getKey() == $role || $instance->slug == $role) {
                return true;
            }
        }

        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function delete()
    {
        if ($this->exists) {
            $this->activations()->delete();
            $this->roles()->detach();
        }

        return parent::delete();
    }

    /**
     * @param $username
     * @return \Illuminate\Database\Eloquent\Model|null|object|static
     */
    public function findForPassport($username)
    {
        return self::where('username', $username)->first();
    }
}
