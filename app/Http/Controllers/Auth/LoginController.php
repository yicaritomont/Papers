<?php

namespace App\Http\Controllers\Auth;
use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Request;
use Illuminate\Http\Session;
use App\User;
use Carbon\Carbon;
use DateTime;
use App\ChangePasswordDay;
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    /*public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }*/

    public function login(Request $request)
    {
        // Know a name or usnea name is comming
        print_r($_POST);
        
        // Get data form
        $data = [
            'email'     => $_POST['email'],
            'password'  => $_POST['password'],            
            'status'    => 1
        ];

        // Validate user Exists
        if ( !$user = User::where('email',$_POST['email'])->first())
        {
            // Devuelvo mensaje alertando que el usuario no existe
            flash()->error(trans('auth.failed'));
            return redirect()->back();
        }
        else
        {
            // The user exists.. but  is important to know the attemtps
            /*if ($user->login_attempts > 2 && $user->login_attempts <5)
            {
                $now = Carbon::now();
                $last_login_attempt = new Carbon($user->last_login_attempt);   
                if ($now->diffInSeconds($last_login_attempt) < 60)
                {
                    // Devuelvo un mensaje indicando que debe esperar para volver a intentar
                    flash()->error( trans('auth.throttle').' '.trans('auth.warning'));
                    return redirect()->back();
                }

                $user->login_attempts = 0;
            }*/

            $user->login_attempts++;

            // the user made 5 tride to session,  inactive a user.
            if($user->login_attempts == $user->number_tride)
            {
                $user->status = 0;
                $user->save();
                flash()->error( trans('auth.locked'));
                return redirect()->back();
            }

            //
            if ( ! Auth::attempt($data, isset($_POST['remember'])))
            {
                $user->last_login_attempt = Carbon::now();

                $user->save();

                // Arrojo mensaje de alerta indicando que la password es incorrecta
                flash()->error( 'Clave Incorrecta');
                return redirect()->back();
                
            }
            else
            {
                $change_password_days = ChangePasswordDay::where('state_id', 1)->first();
                $created = new Carbon($user->update_password);
                $now = Carbon::now();
                $days = $change_password_days->days - $created->diffInDays($now);

                $user->login_attempts = 0;
                $user->last_session_id = session()->getId();
                $user->last_ip_session = Request::getClientIp();
                $user->last_login = new DateTime();
                $user->save();
     
                if ($days <= 10) 
                {
                    return redirect()->intended('/home')->with('warning_message', '¡Su contraseña expirará en '.$days.' dias!');

                } 
                else 
                {
                    return redirect()->intended('/home');                  
                }
            }
        }        
    }
}
