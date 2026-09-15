<?php


namespace App\Filters\App\Applicant;

use App\Filters\FilterBuilder;
use App\Models\App\Recruitment\HiringTeam;
use App\Repositories\Core\Status\StatusRepository;

class ApplicantFilter extends FilterBuilder
{
    public function appliedDate($qry)
    {
        $date = json_decode(htmlspecialchars_decode(request()->get('applied_date_range')), true);

        $qry->when($date, function ($qry) use ($date) {
            return $qry->whereBetween(\DB::raw('DATE(created_at)'), array_values($date));
        });

        return $this;
    }

    public function applicantJobPost($qry)
    {
        $user = auth()->user();

        // Caso 1: Candidate com permissão
        if ($user->hasRole('Candidate') && $user->can('can_view_applicant')) {
            $user->load('applicant');
            $qry->where('applicant_id', $user->applicant->id ?? null);
            return $this;
        }

        // Caso 2: Não é Admin nem Manager
        if (!$user->isAppAdmin() && !$user->isAppManager()) {
            if ($user->can('can_view_applicant')) {
                $qry->whereHas('recruiters', function ($qIn) use ($user) {
                    $qIn->where('recruiter_id', $user->id);
                });
                $qry->when(request('job'), function ($qry) {
                    return $qry->where('job_post_id', request('job'));
                });
            }
            return $this;
        }

        // Caso 3: Admin ou Manager
        $qry->when(request('job'), function ($qry) {
            return $qry->where('job_post_id', request('job'));
        });

        return $this;
    }

    public function jobApplicantStatus($qry)
    {
        $qry->when(request()->get('status'), function ($qry) {
            return $qry->where('status_id', request('status'));
        },
            function ($qry) {
                return $qry->where('status_id', '!=', resolve(StatusRepository::class)
                    ->getStatusId('job_applicant', 'status_disqualified'))->latest('id');
            }
        );

        return $this;
    }

    public function jobApplicantReview($qry)
    {
        $qry->when((isset(request()['review']) && request()->get('review') !== null), function ($qry) {
            return $qry->where('review', request()->get('review'));
        });

        return $this;
    }


}
