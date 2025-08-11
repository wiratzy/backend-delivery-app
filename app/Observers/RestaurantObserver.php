<?php

namespace App\Observers;

use App\Models\Restaurant;

class RestaurantObserver
{
    /**
     * Handle the Restaurant "created" event.
     */
     public function creating(Restaurant $restaurant): void
    {
        if ($restaurant->owner_id && $restaurant->owner) {
            $restaurant->phone = $restaurant->owner->phone;
        }
    }

    // Akan dijalankan SEBELUM restoran di-update
    public function updating(Restaurant $restaurant): void
    {
        // Cek jika owner_id berubah
        if ($restaurant->isDirty('owner_id')) {
            if ($restaurant->owner_id && $restaurant->owner) {
                $restaurant->phone = $restaurant->owner->phone;
            } else {
                $restaurant->phone = null;
            }
        }
    }
    /**
     * Handle the Restaurant "deleted" event.
     */
    public function deleted(Restaurant $restaurant): void
    {
        //
    }

    /**
     * Handle the Restaurant "restored" event.
     */
    public function restored(Restaurant $restaurant): void
    {
        //
    }

    /**
     * Handle the Restaurant "force deleted" event.
     */
    public function forceDeleted(Restaurant $restaurant): void
    {
        //
    }
}
