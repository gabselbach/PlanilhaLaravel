<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('access.token', function () {
    return cache()->remember('spreadsheet', 60 * 24, function () {
        putenv('GOOGLE_APPLICATION_CREDENTIALS=' . storage_path('app/credentials.json'));
        $client = new \Google_Client;
        $client->useApplicationDefaultCredentials();

        $client->setApplicationName('App Test');
        $client->setScopes([
            'https://spreadsheets.google.com/feeds'
        ]);

        if ($client->isAccessTokenExpired()) {
            $client->refreshTokenWithAssertion();
        }

        return $client->fetchAccessTokenWithAssertion()['access_token'];
    });
});
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
