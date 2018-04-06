<?php
namespace App\Http\Middleware;

use Closure, Session, Auth;

class Locale {

    /**
     * The availables languages.
     *
     * @array $languages
     */
    protected $languages = ['en','it'];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        error_log(Session::has('locale')?'true':'false');
        if(!Session::has('locale'))
        {
            Session::put('locale', $request->getPreferredLanguage($this->languages));
            Session::save();
        }

        if($request->has('lang'))
        {
            $lang = $request->get('lang');
            if (in_array($lang, $this->languages)){
                Session::put('locale', $lang);
                Session::save();
            }
        }

        app()->setLocale(Session::get('locale'));

        return $next($request);
    }

}