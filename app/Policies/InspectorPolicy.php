<?php

namespace App\Policies;

use App\User;
use App\Inspector;
use Illuminate\Auth\Access\HandlesAuthorization;

class InspectorPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function before($user, $ability){
        if( !$user->hasRole('Inspector') ){
            return true;
        }
    }

    public function validateId(User $user, Inspector $inspector)
    {
        return $user->id === $inspector->user_id;
    }

    /* public function destroy(User $user, Inspector $inspector)
    {
        return $user->id === $inspector->user_id;
    }

    public function edit(User $user, Inspector $inspector)
    {
        return $user->id === $inspector->user_id;
    }

    public function show(User $user, Inspector $inspector)
    {
        return $user->id === $inspector->user_id;
    } */
}
