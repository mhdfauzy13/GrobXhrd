<?php

namespace App\Events;

use App\Models\Role;
use Illuminate\Broadcasting\Channel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Request;

class RoleCreated
{
    use Dispatchable, SerializesModels;

    public $role;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Role $role)
    {
        $this->role = $role;
    }

    public function createRole(Request $request)
{
    $role = Role::create([
        'name' => $request->input('name'),
    ]);

    event(new RoleCreated($role));
}

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('channel-name');
    }
}
