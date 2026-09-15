<?php


namespace App\Services\App\JobPost;


use App\Helpers\Core\Traits\FileHandler;
use App\Models\App\JobPost\JobPost;
use App\Services\App\AppService;

class SocialSharableService extends AppService
{
    use FileHandler;

    public function __construct(JobPost $jobPost)
    {
        $this->model = $jobPost;
    }

    public function storeAttachment()
    {
        if (request()->hasFile('thumbnail_image')){

            $this->deleteImage(optional(request()->thumbnail_image)->path);

            $file_path = $this->withOriginalName()->storeFile(request()->thumbnail_image, 'job_post');

            $this->model->jobPostThumbnail()->updateOrCreate([
                'type' => 'job_post_thumbnail_image'
            ], [
                'path' => $file_path
            ]);
        }

        return $this;
    }
}