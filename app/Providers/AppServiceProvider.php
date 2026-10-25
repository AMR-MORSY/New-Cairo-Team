<?php

namespace App\Providers;

use App\Models\User;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentColor;
use Filament\Support\Facades\FilamentView;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        /*
         * Super admins clear every authorization check.
         *
         * Gate::before runs ahead of every ability and policy: true skips the
         * policy, null falls through to it. That covers `handle_users_activities`
         * (the users / roles / permissions screens and the Filament panel),
         * which therefore needs no Gate::define of its own -- with no ability
         * and no policy behind the name, Gate resolves it to false for
         * everyone else, which is what the definition used to return.
         *
         * It also clears the abilities the policies close unconditionally:
         * delete / restore / forceDelete on sites, modifications and purchase
         * orders. Note modifications.action_owner is ON DELETE CASCADE.
         *
         * The User typehint is load-bearing. Laravel skips a before callback
         * when the user is null and the first parameter neither is nullable
         * nor defaults to null (Gate::parameterAllowsGuests), so guests never
         * reach this. Making the parameter nullable would call it with null
         * and fatal on every page carrying a @can.
         */
        Gate::before(fn (User $user) => $user->isSuperAdmin() ?: null);

        /*
         * The admin panel sets its own colours, but only while it is serving
         * /admin. Filament components embedded in the app (dashboard widgets,
         * action modals) would otherwise fall back to Filament's amber.
         */
        FilamentColor::register([
            'primary' => Color::hex('#ff7900'),
        ]);

        /*
         * The app moves between pages with wire:navigate, and Livewire re-runs
         * every <script> in the new page's <body> unless it is marked
         * data-navigate-once. Filament only adds that mark in SPA mode, which
         * its panel switches on for /admin alone. Without it, each navigation
         * ran support.js again, which set up a second copy of Filament's
         * on-demand component loader after Alpine had already started. That copy
         * was never wired in, so the dashboard's x-load chart components never
         * loaded until a full refresh. The panel sets its own mode when it
         * boots, so /admin is unaffected.
         */
        FilamentView::spa();
    }
}
