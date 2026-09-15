<?php

namespace Database\Factories\App\JobPost;

use App\Models\App\Company\CompanyLocation;
use App\Models\App\Company\Department;
use App\Models\App\JobPost\ExperienceLevel;
use App\Models\App\JobPost\JobPost;
use App\Models\App\JobPost\JobType;
use App\Models\Core\Auth\User;
use App\Models\Core\Setting\Setting;
use App\Repositories\Core\Status\StatusRepository;
use Illuminate\Database\Eloquent\Factories\Factory;

class JobPostFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = JobPost::class;
    private $index = -1;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $this->index++;
        if($this->index > 11) {
            $this->index = 0;
        }
        $jobNames = [
            'Node Js Developer',
            'Software Engineer',
            'Dev Ops Engineer',
            'Senior SE',
            'Mid Level SE',
            'Junior SE',
            'UI/UX designer',
            'Team Lead',
            'Project Manager',
            'Software QA',
            'Laravel Developer',
            'JavaScript Ninja'
        ];
        $setting = Setting::where('name', 'application_form')->where('context', 'app')->where('public', 1)->first();
        return [
             'company_location_id' => $this->faker->randomElement(CompanyLocation::query()->pluck('id')->toArray()),
             'department_id' => $this->faker->randomElement(Department::query()->pluck('id')->toArray()),
             'job_type_id' => $this->faker->randomElement(JobType::query()->pluck('id')->toArray()),
             'experience_level_id' => $this->faker->randomElement(ExperienceLevel::query()->pluck('id')->toArray()),
             'status_id' => $this->faker->randomElement(resolve(StatusRepository::class)->statuses('job_post')->pluck('id')->toArray()),
             'posted_by' => $this->faker->randomElement(User::query()->pluck('id')->toArray()),
             'name' => $jobNames[$this->index],
             'working_arrangement' =>  $this->faker->randomElement(['on_site', 'hybrid','remote']),
             'slug' => $this->faker->uuid,
             'stages' => 'new,shortlist,interview,task phase,negotiation,hired,disqualified',
             'salary' => $this->faker->numberBetween(20000, 80000),
             'vacancy_count' => $this->faker->numberBetween(1, 10),
             'description' => $this->faker->text,
             'responsibilities' => $this->faker->text,
             'job_post_settings' => '{
              "content": {
                "title": "Job Title",
                "subtitle": "Job subtitle or short description",
                "details": "Job type - Location",
                "bodySection": [
                  {
                    "headings": "About Job",
                    "description": "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum"
                  },
                  {
                    "headings": "About Employee",
                    "description": "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum"
                  },
                  {
                    "headings": "About requirement",
                    "description": "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum"
                  },
                  {
                    "headings": "Extended Heading",
                    "description": "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry industry standard dummy text ever since the 1500s"
                  }
                ]
              },
              "pageBlocks": {
                "defaultView": {
                  "header": true,
                  "body": true,
                  "footer": true,
                  "logo": true
                },
                "mobileView": {
                  "header": true,
                  "body": true,
                  "footer": true,
                  "logo": true
                }
              },
              "pageStyle": {
                "defaultView": [
                  {
                    "name": "Title",
                    "key": "title",
                    "fontSize": 50,
                    "fontWeight": 700,
                    "letterSpacing": 1,
                    "color": "#313131"
                  },
                  {
                    "name": "Subtitle",
                    "key": "sub-title",
                    "fontSize": 30,
                    "fontWeight": 300,
                    "letterSpacing": 1,
                    "color": "#afb1b6"
                  },
                  {
                    "name": "Details",
                    "key": "details",
                    "fontSize": 20,
                    "fontWeight": 300,
                    "letterSpacing": 1,
                    "color": "#3758b3"
                  },
                  {
                    "name": "Headings",
                    "key": "headings",
                    "fontSize": 27,
                    "fontWeight": 600,
                    "letterSpacing": 0,
                    "color": "#313131"
                  },
                  {
                    "name": "Description",
                    "key": "description",
                    "fontSize": 19,
                    "fontWeight": 300,
                    "letterSpacing": 0,
                    "color": "#313131"
                  }
                ],
                "mobileView": [
                  {
                    "name": "Title",
                    "key": "title",
                    "fontSize": 40,
                    "fontWeight": 700,
                    "letterSpacing": 1,
                    "color": "#313131"
                  },
                  {
                    "name": "Subtitle",
                    "key": "sub-title",
                    "fontSize": 25,
                    "fontWeight": 300,
                    "letterSpacing": 1,
                    "color": "#afb1b6"
                  },
                  {
                    "name": "Details",
                    "key": "details",
                    "fontSize": 16,
                    "fontWeight": 300,
                    "letterSpacing": 1,
                    "color": "#3758b3"
                  },
                  {
                    "name": "Headings",
                    "key": "headings",
                    "fontSize": 20,
                    "fontWeight": 600,
                    "letterSpacing": 0,
                    "color": "#313131"
                  },
                  {
                    "name": "Description",
                    "key": "description",
                    "fontSize": 18,
                    "fontWeight": 300,
                    "letterSpacing": 0,
                    "color": "#313131"
                  }
                ]
              }
            }',
             'apply_form_settings' => $setting->value,
             'last_submission_date' => $this->faker->dateTimeBetween('now', '+30 days'),
        ];
    }
}
