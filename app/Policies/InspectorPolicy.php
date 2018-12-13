<?php

namespace App\Policies;

use App\User;
use App\Inspector;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Log;

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
}
