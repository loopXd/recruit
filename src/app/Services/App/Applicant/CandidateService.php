<?php

namespace App\Services\App\Applicant;

use App\Helpers\Core\Traits\FileHandler;
use App\Helpers\Core\Traits\HasWhen;
use App\Helpers\Core\Traits\Helpers;
use App\Models\App\Applicant\Applicant;
use App\Models\App\Applicant\ApplicationAnswer;
use App\Services\App\AppService;
use App\Services\Core\Auth\Traits\HasUserActions;
use Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class CandidateService extends AppService
{
    use FileHandler, Helpers, HasWhen, HasUserActions;

    public function __construct(Applicant $applicant)
    {
        $this->model = $applicant;
    }

    public function validateApplicant(): static
    {
        $basicInformation = json_decode(request()->basic_information, true);

        // Define validation rules for the JSON data
        $rules = [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email:rfc',
            'gender' => 'nullable',
            'phone' => 'nullable|string',
            'date_of_birth' => 'nullable|date|date_format:Y-m-d',
        ];

        $validator = Validator::make($basicInformation, $rules);

        if ($validator->fails()) {
            throw_if(
                $validator->fails(),
                ValidationException::withMessages([
                    'errors' => $validator->errors()->getMessages()
                ])
            );
        }

        return $this;

    }

    public function storeApplicant(): \Illuminate\Database\Eloquent\Model
    {
        $basicInformation = json_decode(request()->basic_information, true);

        // Normaliza o gênero
        $basicInformation['gender'] = match ($basicInformation['gender'] ?? null) {
            'Masculino' => 'male',
            'Feminino' => 'female',
            default => 'other'
        };

        $email = $basicInformation['email'] ?? Auth::user()->email;

        return Applicant::updateOrCreate(
            ['email' => $email],
            [
                'first_name'   => $basicInformation['first_name'] ?? Auth::user()->name,
                'last_name'    => $basicInformation['last_name'] ?? '',
                'mother_name'  => $basicInformation['mother_name'] ?? null,
                'father_name'  => $basicInformation['father_name'] ?? null,
                'rg'           => $basicInformation['rg'] ?? null,
                'cpf'          => $basicInformation['cpf'] ?? null,
                'email'        => $email,
                'phone'        => $basicInformation['phone'] ?? null,
                'date_of_birth'=> $basicInformation['date_of_birth'] ?? null,
                'user_id' => Auth::user()->id,
                'gender'       => $basicInformation['gender'],
            ]
        );
    }
}
