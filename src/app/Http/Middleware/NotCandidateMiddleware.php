<?php


namespace App\Http\Middleware;


use App\Exceptions\GeneralException;
use Closure;
use Illuminate\Http\Request;

class NotCandidateMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        throw_if(
            auth()->user()->hasRole('Candidate'),
            new GeneralException(__t('action_not_allowed'))
        );

        return $next($request);
    }

}