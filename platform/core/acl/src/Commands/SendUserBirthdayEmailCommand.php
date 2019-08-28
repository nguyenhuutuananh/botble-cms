<?php

namespace Botble\ACL\Commands;

use AclManager;
use Botble\ACL\Models\User;
use Botble\ACL\Repositories\Interfaces\UserInterface;
use Botble\Base\Supports\EmailHandler;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendUserBirthdayEmailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cms:user:send-birthday-email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email to user(s) if today is their birthday';

    /**
     * @var UserInterface
     */
    protected $userRepository;

    /**
     * @var EmailHandler
     */
    protected $emailHandler;

    /**
     * RebuildPermissions constructor.
     * @author Sang Nguyen
     * @param UserInterface $userRepository
     * @param EmailHandler $emailHandler
     */
    public function __construct(UserInterface $userRepository, EmailHandler $emailHandler)
    {
        parent::__construct();
        $this->userRepository = $userRepository;
        $this->emailHandler = $emailHandler;
    }

    /**
     * Execute the console command.
     * @author Sang Nguyen
     * @throws \Throwable
     */
    public function handle()
    {
        $users = $this->userRepository->all();
        foreach ($users as $user) {
            /**
             * @var User $user
             */
            if (AclManager::getActivationRepository()->completed($user)) {
                if (!empty($user->dob) && Carbon::parse($user->dob)->diffInDays(now(config('app.timezone'))) == 0) {
                    $this->emailHandler->send(
                        view('core.base::emails.birthday', compact('user'))->render(),
                        trans('core/base::mail.happy_birthday'),
                        $user->email
                    );
                    $this->info('Sent birthday email to user ' . $user->getFullName());
                }
            }
        }
    }
}
