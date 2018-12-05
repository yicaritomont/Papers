<?php

namespace App\Policies;

use App\User;
use App\Company;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Log;

class CompanyPolicy
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
        if( $user->hasRole('Admin') ){
            return true;
        }
    }

    public function validateCompany(User $user, Company $companyAuth)
    {
        // return $user->id === $company->user_id;
        Log::info('*******************Policy*****************************');
        Log::info('Company Auth '.$companyAuth);
        Log::info('Relacion compañias '.$user->companies->find($companyAuth->id));
        Log::info($user->companies->find($companyAuth->id)->id === $companyAuth->id);

        return($user->companies->find($companyAuth->id)->id === $companyAuth->id);

        foreach($user->companies as $company)
        {
            Log::info('Company'. $company);
            if($company->id == $companyAuth->id)
            {
                Log::info('Retorno true');
                return true;
            }
        }

        return false;
    }
}
