<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class EnsureSurveyCompleted
{
    public function handle($request, Closure $next)
    {
        if (Auth::check() && Auth::user()->survey_taken === 0 && !$request->is('survey')) {
            return redirect()->route('survey.show');
        }

        return $next($request);
    }
}
