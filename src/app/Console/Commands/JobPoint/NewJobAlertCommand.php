<?php


namespace App\Console\Commands\JobPoint;


use App\Jobs\JobAlertEmailSendJob;
use App\Models\App\JobPost\JobPost;
use App\Models\Core\Auth\User;
use App\Repositories\Core\Status\StatusRepository;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class NewJobAlertCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'job:alert';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send candidate new job alert';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $twentyFourHoursAgo = Carbon::now()->subHours(24);

        $recentJobPosts = JobPost::with(['experienceLevel', 'jobType', 'department'])
            ->where('status_id', resolve(StatusRepository::class)->getStatusId('job_post', 'status_open'))
            ->whereDate('created_at', '>=', $twentyFourHoursAgo)
            ->take(5)
            ->get();

        if ($recentJobPosts->isEmpty()) {
            return; // No recent job posts, no need to continue processing.
        }

        $candidates = User::with(['jobAlert', 'roles'])
            ->whereHas('jobAlert', function ($q) {
                $q->where('is_active', 1);
            })
            ->whereHas('roles', function ($q) {
                $q->where('name', 'Candidate');
            })
            ->get();

        $candidates->each(function ($candidate) use ($recentJobPosts) {
            $jobTypeInterest = array_intersect($candidate->jobAlert->job_type, $recentJobPosts->pluck('job_type_id')->toArray());
            $workingArrangementInterest = array_intersect($candidate->jobAlert->working_arrangement, $recentJobPosts->pluck('working_arrangement')->toArray());
            $departmentInterest = array_intersect($candidate->jobAlert->department, $recentJobPosts->pluck('department_id')->toArray());
            $experienceLevelInterest = array_intersect($candidate->jobAlert->experience_level, $recentJobPosts->pluck('experience_level_id')->toArray());

            if (!empty($jobTypeInterest) || !empty($workingArrangementInterest) || !empty($departmentInterest) || !empty($experienceLevelInterest)) {
                JobAlertEmailSendJob::dispatch($candidate, $recentJobPosts)->onQueue('high');
            }
        });
    }

}