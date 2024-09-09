<?php

namespace App\Listeners;

use App\Models\Role;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class AssignDefaultRole
{
    /**
     * Handle the event.
     *
     * @param  \App\Events\RoleCreated  $event
     * @return void
     */
    public function handle($event)
    {
        $role = $event->role;

        // Cek apakah role baru tidak termasuk dalam role spesifik
        if (!in_array($role->name, ['superadmin', 'manager'])) {
            // Jika tidak termasuk, tetapkan role sebagai employee
            $role->name = 'employee';
            $role->save();
        }
    }
}

