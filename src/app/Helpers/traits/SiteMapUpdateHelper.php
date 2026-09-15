<?php

namespace App\Helpers\traits;

use App\Exceptions\GeneralException;
use App\Repositories\Core\Setting\SettingRepository;
use Google_Client;
use Illuminate\Support\Facades\Artisan;

trait SiteMapUpdateHelper
{
    public function updateSiteMap()
    {
        Artisan::call('sitemap:generate');
    }

    public function notifyGoogleIndex($jobPost, $type): ?int
    {
        $notifyType = [
            'delete' => 'URL_DELETED',
            'update' => 'URL_UPDATED'
        ];

        $url = env('APP_URL') . "/$jobPost->slug";
        $client = new Google_Client();

        // service_account_file.json is the private key that you created for your service account.
        try {
            $google_service_account_file = resolve(SettingRepository::class)->basicQuery('google_service_account_file_update')->first();
            if (!$google_service_account_file){
                return null;
            }
            $path = public_path($google_service_account_file->value);
            $path = str_replace('public/storage','storage/app/public',$path);

            $client->setAuthConfig($path);
        } catch (\Exception $e) {
            throw new GeneralException(__t('google_authentication_file_not_uploaded'), 401);
        }

        $client->addScope('https://www.googleapis.com/auth/indexing');

        // Get a Guzzle HTTP Client
        $httpClient = $client->authorize();
        $endpoint = 'https://indexing.googleapis.com/v3/urlNotifications:publish';

        // Define contents here. The structure of the content is described in the next step.
        $content = [
            'url' => $url,
            'type' => $notifyType[$type],
        ];

        $response = $httpClient->post($endpoint, ['body' => json_encode($content)]);

//        if($response->getStatusCode() != '200'){
//            throw new GeneralException('Authentication Failed', $response->getStatusCode());
//        }
        return $response->getStatusCode();
    }
}