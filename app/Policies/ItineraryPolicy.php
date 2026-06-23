<?php

namespace App\Policies;

use App\Models\Itinerary;
use App\Models\User;

class ItineraryPolicy
{
    public function view(User $user, Itinerary $itinerary): bool
    {
        return $itinerary->group?->hasMember($user) ?? false;
    }

    public function update(User $user, Itinerary $itinerary): bool
    {
        return $itinerary->group?->canEditItineraries($user) ?? false;
    }

    public function delete(User $user, Itinerary $itinerary): bool
    {
        return $this->update($user, $itinerary);
    }

    public function manageStops(User $user, Itinerary $itinerary): bool
    {
        return $this->update($user, $itinerary);
    }

    public function downloadPdf(User $user, Itinerary $itinerary): bool
    {
        return $this->view($user, $itinerary);
    }
}
