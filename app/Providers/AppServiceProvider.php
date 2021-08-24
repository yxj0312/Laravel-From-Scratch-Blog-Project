<?php

namespace App\Providers;

use App\Models\User;
use App\Services\MailChimpNewsletter;
use App\Services\Newsletter;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use MailchimpMarketing\ApiClient;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        app()->bind(Newsletter::class, function () {
            $client = (new ApiClient)->setConfig([
                'apiKey' => config('services.mailchimp.key'),
                'server' => 'us6'
            ]);

            return new MailChimpNewsletter($client);
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Paginator::useBootstrap()
        Model::unguard();


        Gate::define('admin', function (User $user) {
            return $user->username === 'JeffreyWay';
        });

        Blade::if('admin', function () {
            return request()->user()?->can('admin');
        });
    }
}
