<?php

namespace UnknowSk\Core\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function () {
            if ($domains = config('tenancy.central_domains', [])) {
                foreach ($domains as $domain) {
                    if (file_exists(base_path('routes/api.php'))) {
                        Route::prefix('api')
                            ->domain($domain)
                            ->middleware('api')
                            ->group(base_path('routes/api.php'));
                    }

                    if (file_exists(base_path('routes/web.php'))) {
                        Route::middleware('web')
                            ->domain($domain)
                            ->group(base_path('routes/web.php'));
                    }
                }
            } else {
                if (file_exists(base_path('routes/api.php'))) {
                    Route::prefix('api')
                        ->middleware('api')
                        ->group(base_path('routes/api.php'));
                }

                if (file_exists(base_path('routes/web.php'))) {
                    Route::middleware('web')
                        ->group(base_path('routes/web.php'));
                }
            }
        });
    }
}
