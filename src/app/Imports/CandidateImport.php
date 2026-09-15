<?php

namespace App\Imports;


use App\Models\App\Applicant\Applicant;
use App\Models\App\Applicant\JobApplicant;
use App\Models\App\JobPost\JobPost;
use App\Models\App\Recruitment\JobStage;
use App\Repositories\Core\Status\StatusRepository;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;

class CandidateImport extends DefaultValueBinder implements ToModel, WithHeadingRow, WithValidation, WithBatchInserts, WithChunkReading, SkipsOnFailure, WithCustomValueBinder
{
    use Importable, SkipsFailures;

    public function bindValue(Cell $cell, $value) {
        // the 'F' column is the one that stores the date_of_birth values
        // the purpose for trimming is as follows, microsoft excel mutates the date values and changes the format which
        // then messes up this import even further as the application expects a specific format, the client sends a
        // hardcoded set of date strings formatted in the sample file with a space which makes the cells immune to excel's mutation 
        // That space is being trimmed here

        if ($cell->getColumn() !== 'F')
            return parent::bindValue($cell, $value);

        $trimmedValue = trim($value);
        $cell->setValueExplicit($trimmedValue, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        return true;
    }

    public function model(array $row)
    {
        $applicant = Applicant::where('email', $row['email'])->first();
        if ($applicant){
            return true;
        }

        $applicant = Applicant::query()->create([
            'first_name' => $row['first_name'],
            'last_name' => $row['last_name'],
            'email' => $row['email'],
            'phone' => $row['phone'],
            'gender' => $row['gender'],
            'date_of_birth' => $row['date_of_birth']
        ]);

        $jobPost = JobPost::query()->first();
        if ($jobPost){
            $stage = JobStage::query()->where('name', 'new')->where('job_post_id', $jobPost->id)->first();
            JobApplicant::query()->create([
                'applicant_id' => $applicant->id,
                'job_post_id' => $jobPost->id,
                'current_stage_id' => optional($stage)->id,
                'apply_form_setting' => '',
                'status_id' => app(StatusRepository::class)->getStatusId('job_applicant', 'status_new'),
                'slug' => Str::uuid(),
            ]);
        }

    }

    public array $requiredHeading = [
        "first_name",
        "last_name",
        "email",
        "phone",
        "gender",
        "date_of_birth",
    ];

    public function rules(): array
    {
        return [
            "*.first_name" => ['required', 'string'],
            "*.last_name" => ['required', 'string'],
            "*.email" => ['required', 'email', 'unique:applicants'],
            "*.gender" => ['required', Rule::in(['male', 'female', 'other'])],
            "*.date_of_birth" => ['nullable', 'date', 'date_format:Y-m-d'],
        ];
    }

    public function batchSize(): int
    {
        return 100;
    }

    public function chunkSize(): int
    {
        return 100;
    }

}
