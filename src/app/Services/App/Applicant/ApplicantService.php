<?php

namespace App\Services\App\Applicant;

use App\Helpers\Core\Traits\FileHandler;
use App\Helpers\Core\Traits\HasWhen;
use App\Helpers\Core\Traits\Helpers;
use App\Models\App\Applicant\Applicant;
use App\Models\App\Applicant\ApplicationAnswer;
use App\Services\App\AppService;
use App\Services\Core\Auth\Traits\HasUserActions;

class ApplicantService extends AppService
{
    use FileHandler, Helpers, HasWhen, HasUserActions;

    public function __construct(Applicant $applicant)
    {
        $this->model = $applicant;
    }

    public function uploadResume($resume)
    {
        foreach ($this->model->jobApplicants as $jobApplicant) {

            $this->removePreviousResume($jobApplicant);

            ApplicationAnswer::query()->insert([
                [
                    'job_applicant_id' => $jobApplicant->id,
                    'question' => 'upload_your_resume_here',
                    'attachment' => $this->withOriginalName()->storeFile($resume, 'attachments'),
                ]
            ]);
        }

        return $this;
    }

    public function removePreviousResume($jobApplicant)
    {
        foreach ($jobApplicant->answers as $answer){
            if ($answer->attachment){
                if ($answer->question === 'upload_your_resume_here'){
                    $this->deleteFile($answer->attachment);
                    $answer->delete();
                }
            }
        }
        return $this;
    }
}
