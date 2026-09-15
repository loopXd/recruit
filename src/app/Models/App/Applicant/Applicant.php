<?php

namespace App\Models\App\Applicant;

use App\Models\App\Applicant\Traits\Relationship\ApplicantRelationship;
use App\Models\App\Applicant\Traits\Rules\ApplicantRules;
use App\Models\App\AppModel;
use App\Models\Core\Auth\Profile;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Applicant extends AppModel
{
    use HasFactory, ApplicantRules, ApplicantRelationship;

    protected $fillable = [
        'first_name',
        'last_name',
        'mother_name',
        'father_name',
        'rg',
        'cpf',
        'email',
        'phone',
        'date_of_birth',
        'user_id',
        'gender',
    ];

    protected $casts = [
        'date_of_birth' => 'datetime:Y-m-d'
    ];

    protected $appends = ['full_name'];

    public function getFullNameAttribute()
    {
        return $this->last_name
            ? $this->first_name . ' ' . $this->last_name
            : $this->first_name;
    }

    public function getNameAttribute()
    {
        return $this->full_name;
    }

    public function profile()
    {
        return $this->hasOne(Profile::class, 'user_id', 'user_id');
    }
}
