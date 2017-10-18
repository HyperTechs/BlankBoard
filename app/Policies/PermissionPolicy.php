<?php

namespace App\Policies;

use App\Http\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PermissionPolicy
{
    use HandlesAuthorization;

    /**
     * The permission attr name.
     *
     * @var string
     */
    private $name = 'permissions';

    public function before($user, $ability)
    {
        if ($user->hasRole('superadmin') || $user->hasPermission($this->name . '_module')) {
            return true;
        }
            return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Http\Models\User  $user
     * @return mixed
     */
    public function view(User $user)
    {
        if ($user->hasPermission('view_' . $this->name)) {
            return true;
        }

        return abort(403, 'Access denied message.');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Http\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        if ($user->hasPermission('create_' . $this->name)) {
            return true;
        }

        return abort(403, 'Access denied message.');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Http\Models\User  $user
     * @return mixed
     */
    public function update(User $user)
    {
        if ($user->hasPermission('update_' . $this->name)) {
            return true;
        }

        return abort(403, 'Access denied message.');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Http\Models\User  $user
     * @return mixed
     */
    public function delete(User $user)
    {
        if ($user->hasPermission('delete_' . $this->name)) {
            return true;
        }

        return abort(403, 'Access denied message.');
    }
}