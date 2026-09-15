<?php


namespace App\Http\Controllers\App\Integration;


use App\Helpers\Core\Traits\FileHandler;
use App\Http\Controllers\Controller;
use App\Services\App\Integration\GoogleJobSearchService;
use Illuminate\Http\Request;

class GoogleJobSearchIntegrationController extends Controller
{
    use FileHandler;

    public function __construct(GoogleJobSearchService $googleJobSearchService)
    {
        $this->service = $googleJobSearchService;
    }
    public function googleOwnershipFileUpdate(Request $request)
    {
        $request->validate([
            'uploaded_file' => 'required|file'
        ]);

        if (request()->hasFile('uploaded_file')){

            $file_path = $this->storeFileMoveToRoot(request()->uploaded_file, 'google_job_search');

            resolve(GoogleJobSearchService::class)
                ->update('google_ownership_file_update', $file_path, 'google_ownership_file_update');

            return updated_responses('google_ownership_file');
        }
    }

    public function googleServiceAccountCredentialUpdate(Request $request)
    {
        $request->validate([
            'uploaded_file' => 'required|mimes:json'
        ]);

        if (request()->hasFile('uploaded_file')){

            $this->deleteImage(optional(request()->uploaded_file)->path);

            $file_path = $this->withOriginalName()->storeFile(request()->uploaded_file, 'public/google_job_search');

            resolve(GoogleJobSearchService::class)
                ->update('google_service_account_file_update', $file_path, 'google_service_account_file_update');

            return updated_responses('google_service_account_file');
        }
    }

}