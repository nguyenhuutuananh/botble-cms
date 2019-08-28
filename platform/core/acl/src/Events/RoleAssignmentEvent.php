<?php

namespace Botble\ACL\Events;

use Botble\ACL\Models\Role;
use Botble\ACL\Models\User;
use Event;
use Illuminate\Queue\SerializesModels;

class RoleAssignmentEvent extends Event
{
    use SerializesModels;

    /**
     * @var Role
     */
    public $role;

    /**
     * @var User
     */
    public $user;

    /**
     * RoleAssignmentEvent constructor.
     *
     * @param Role $role
     * @param User $user
     * @author Sang Nguyen
     */
    public function __construct(Role $role, User $user)
    {
        $this->role = $role;
        $this->user = $user;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     * @author Sang Nguyen
     */
    public function broadcastOn()
    {
        return [];
    }
}
