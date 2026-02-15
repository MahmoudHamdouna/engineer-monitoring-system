<?php

namespace App\Observers;

use App\Models\User;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        $this->syncRolePermissions($user);
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        $roleName = $user->getRoleNames()->first();

        if ($roleName && $user->role !== $roleName) {
            $user->role = $roleName;
            $user->saveQuietly();
        }

        $this->syncRolePermissions($user);
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        //
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }

    protected function syncRolePermissions(User $user)
    {
        $defaults = config('default_permissions');
        $roleName = $user->role;

        if (!$roleName) return;

        // Sync role
        $user->syncRoles([$roleName]);

        // Sync permissions
        if (isset($defaults[$roleName])) {
            $user->syncPermissions($defaults[$roleName]);
        }
    }
}
