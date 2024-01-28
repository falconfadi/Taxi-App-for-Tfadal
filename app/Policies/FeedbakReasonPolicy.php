<?php

namespace App\Policies;

use App\Models\Feedbak_reason;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FeedbakReasonPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Feedbak_reason  $feedbakReason
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Feedbak_reason $feedbakReason)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Feedbak_reason  $feedbakReason
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Feedbak_reason $feedbakReason)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Feedbak_reason  $feedbakReason
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Feedbak_reason $feedbakReason)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Feedbak_reason  $feedbakReason
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Feedbak_reason $feedbakReason)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Feedbak_reason  $feedbakReason
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Feedbak_reason $feedbakReason)
    {
        //
    }
}
