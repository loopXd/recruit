<?php

namespace App\Models\App\JobPost;

use App\Models\App\JobPost\Traits\Rules\ExperienceRules;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExperienceLevel extends Model
{
    use HasFactory, ExperienceRules;

    protected $fillable = ['name'];

    public function jobPost()
    {
        return $this->hasMany(JobPost::class, 'experience_level_id');
    }
}
