<?php

namespace Botble\ACL\Commands;

use Botble\ACL\Repositories\Interfaces\UserInterface;
use DB;
use Illuminate\Console\Command;

class RebuildPermissionsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cms:user:rebuild-permissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rebuild all the user permissions from the users defined roles and the roles defined flags';

    /**
     * @var UserInterface
     */
    protected $userRepository;

    /**
     * Install constructor.
     * @param UserInterface $userRepository
     * @author Sang Nguyen
     */
    public function __construct(UserInterface $userRepository)
    {
        parent::__construct();

        $this->userRepository = $userRepository;
    }

    /**
     * Execute the console command.
     * @param boolean $return
     * @author Sang Nguyen
     * @throws \Exception
     */
    public function handle($return = false)
    {
        // Safety first!

        DB::beginTransaction();

        // Firstly, lets grab out the global roles
        $allRoles = DB::select('SELECT id, name, permissions FROM roles');

        if (empty($allRoles)) {
            $users = $this->userRepository->all();
            foreach ($users as $user) {
                $user->permissions = [
                    'superuser'     => $user->super_user ? true : false,
                    'manage_supers' => $user->manage_supers ? true : false,
                ];
                $this->userRepository->createOrUpdate($user);
            }
        } else {
            // Go and grab all of the permission flags defined on these global roles
            foreach ($allRoles as $role) {

                $permissions = json_decode($role->permissions, '[]');

                $userRoles = DB::select('SELECT user_id, role_id FROM role_users WHERE role_id=' . $role->id);
                foreach ($userRoles as $userRole) {
                    // Insert permission flag
                    $user = DB::select('SELECT super_user, manage_supers FROM users WHERE id=' . $userRole->user_id);
                    if (!empty($user)) {
                        $user = $user[0];
                        $permissions['superuser'] = $user->super_user ? true : false;
                        $permissions['manage_supers'] = $user->manage_supers ? true : false;
                        DB::statement("UPDATE users SET permissions = '" . json_encode($permissions) . "' where id=" . $userRole->user_id);
                    }
                }
            }
        }

        if (!$return) {
            $this->info('Rebuild user permissions successfully!');
        }

        DB::commit();
    }
}
