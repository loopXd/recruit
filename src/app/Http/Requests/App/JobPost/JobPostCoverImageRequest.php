<?php

namespace App\Http\Requests\App\JobPost;

use App\Http\Requests\App\AppRequest;
use App\Models\App\JobPost\JobPost;

class JobPostCoverImageRequest extends AppRequest
{
    public function rules()
    {
        return [
            'job_post_cover' => 'required|image|max:2048'
        ];
    }
    public function attributes()
    {
        return [
            'job_post_cover' => 'job post cover image'
        ];
    }
}
