<?php


namespace App\Http\Controllers\App\Settings;


use App\Http\Controllers\Controller;
use App\Http\Requests\App\Integration\ImapSettingServiceRequest;
use App\Repositories\Core\Setting\SettingRepository;
use App\Services\App\Integration\ImapSettingService;

class ImapSettingController extends Controller
{
    public function __construct(ImapSettingService $imapSettingService)
    {
        $this->service = $imapSettingService;
    }

    public function update(ImapSettingServiceRequest $request)
    {
        $this->service
            ->imapSettingUpdate();

        return updated_responses('imap_settings');
    }

    public function imapSettings()
    {
        return resolve(SettingRepository::class)->getDeliverySettingLists('imap_config');
    }

}