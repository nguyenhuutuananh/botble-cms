<?php

namespace Botble\ACL\Commands;

use AclManager;
use Botble\ACL\Repositories\Interfaces\UserInterface;
use Exception;
use Illuminate\Console\Command;
use Validator;

class UserCreateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cms:user:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create super user for Botble CMS';

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
     *
     * @author Sang Nguyen
     */
    public function handle()
    {
        $this->createSuperUser();
    }

    /**
     * Create a superuser.
     *
     * @return bool
     * @author Sang Nguyen
     */
    protected function createSuperUser()
    {
        $this->info('Creating a Super User...');

        $user = $this->userRepository->getModel();
        $user->first_name = $this->askWithValidate('Enter first name', 'required|min:2|max:60');
        $user->last_name = $this->askWithValidate('Enter last name', 'required|min:2|max:60');
        $user->email = $this->askWithValidate('Enter email address', 'required|email|unique:users,email');
        $user->username = $this->askWithValidate('Enter username', 'required|min:4|max:60|unique:users,username');
        $user->password = bcrypt($this->askWithValidate('Enter password', 'required|min:6|max:60'));
        $user->super_user = 1;
        $user->manage_supers = 1;
        $user->profile_image = config('core.acl.general.avatar.default');

        try {
            $this->userRepository->createOrUpdate($user);
            if (AclManager::activate($user)) {
                $this->info('Super user is created.');
            }
        } catch (Exception $exception) {
            $this->error('User could not be created.');
            $this->error($exception->getMessage());
        }

        return true;
    }

    /**
     * @param $message
     * @param string $rules
     * @author Sang Nguyen
     */
    protected function askWithValidate($message, string $rules)
    {
        do {
            $input = $this->ask($message);
            $validate = $this->validate(compact('input'), ['input' => $rules]);
            if ($validate['error']) {
                $this->error($validate['message']);
            }
        } while ($validate['error']);

        return $input;
    }

    /**
     * @param array $data
     * @param array $rules
     * @return array
     * @author Sang Nguyen
     */
    protected function validate(array $data, array $rules)
    {
        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            return [
                'error'   => true,
                'message' => $validator->messages()->first(),
            ];
        }

        return [
            'error' => false,
        ];
    }
}
