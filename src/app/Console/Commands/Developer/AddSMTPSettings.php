<?php

namespace App\Console\Commands\Developer;

use App\Services\Core\Setting\DeliverySettingService;
use Illuminate\Console\Command;

class AddSMTPSettings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'set:email-config';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will store smtp setting to database';
    /**
     * Create a new command instance.
     *
     * @return void
     */
    protected DeliverySettingService $service;

    public function __construct(DeliverySettingService $service)
    {
        $this->service = $service;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('setting...');
        foreach (['from_name' => 'Gain HQ', 'from_email' => 'shakib@gain.media'] as $key => $value) {
            $this->service
                ->update($key, $value, 'default_mail_email_name');
        }
        $context = 'smtp';
        $data = ['smtp_host' => 'smtp.mailtrap.io', 'smtp_port' => 2525, 'provider' => 'smtp', 'smtp_encryption' => 'tls', 'smtp_user_name' => 'e7918d01631835', 'smtp_password' => 'd74c16cad5a904'];
        foreach ($data as $key => $value) {
            $this->service
                ->update($key, $value, $context);
        }
        $this->service->setDefaultSettings('default_mail', $context);
        $this->info('Done');
        return 1;
    }
}