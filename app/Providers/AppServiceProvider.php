<?php

namespace App\Providers;

use App\Models\Group;
use App\Models\Itinerary;
use App\Policies\GroupPolicy;
use App\Policies\ItineraryPolicy;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Gate::policy(Group::class, GroupPolicy::class);
        Gate::policy(Itinerary::class, ItineraryPolicy::class);

        Paginator::useTailwind();
    }
}
