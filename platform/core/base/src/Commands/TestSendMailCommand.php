<?php

namespace Botble\Base\Commands;

use Botble\Base\Supports\EmailHandler;
use Illuminate\Console\Command;

class TestSendMailCommand extends Command
{
    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'cms:mail:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test send mail';

    /**
     * @var EmailHandler
     */
    protected $mailer;

    /**
     * TestSendMailCommand constructor.
     * @param EmailHandler $mailer
     */
    public function __construct(EmailHandler $mailer)
    {
        parent::__construct();

        $this->mailer = $mailer;
    }

    /**
     * Execute the console command.
     * @author Sang Nguyen
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     * @throws \Throwable
     */
    public function handle()
    {
        $content = file_get_contents(core_path('setting/resources/email-templates/test.tpl'));
        $this->mailer->send($content, 'Test mail!', null, [], true);

        $this->info('Done!');
    }
}
