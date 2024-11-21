<?php /** @noinspection PhpUnnecessaryFullyQualifiedNameInspection */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Exceptions\PostTooLargeException;
use Illuminate\Http\Request;

class HandlePostTooLargeException
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            return $next($request);
        } catch (PostTooLargeException) {
            return redirect()->back()->withErrors(['file' => 'The uploaded file is too large.']);
        }
    }
}