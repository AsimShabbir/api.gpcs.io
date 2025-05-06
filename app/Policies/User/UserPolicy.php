<?php

namespace App\Policies\User;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\User;
use App\Traits\RoleTrait;
class UserPolicy
{
    use HandlesAuthorization, RoleTrait;
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $this->isAuthorizedUser();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): bool
    {
        return $this->isAuthorizedUser();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, User $model): bool
    {
        return $this->isUser();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
        return $this->isUser();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        return $this->isAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $model): bool
    {
        return $this->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model): bool
    {
        return $this->isAdmin();
    }
}
