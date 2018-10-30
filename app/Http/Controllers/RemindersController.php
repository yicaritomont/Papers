<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Password;
use Input;
use Validator;
class RemindersController extends Controller
{
    /**
	 * Display the password reminder view.
	 *
	 * @return Response
	 */
    public function getRemind()
	{
        return view('auth.passwords.email');        
    }
    
    /**
	 * Handle a POST request to remind a user of their password.
	 *
	 * @return Response
	 */
    public function postRemind(Request $request)
	{
        
        $this->validate($request, [
            'email' => 'required|email'
        ]);

    
		switch ($response = Password::sendResetLink(['email' => $request->input('email')], function($message) {
			$message->subject('Password Reminder');
		}))
		{
			case Password::INVALID_USER:
                return redirect()->back()->with('error',':p');
                

			case Password::RESET_LINK_SENT:                
                return redirect()->back()->with('error', '=)');
		}
    }
    
    /**
	 * Display the password reset view for the given token.
	 *
	 * @param  string  $token
	 * @return Response
	 */
    public function getReset($token = null)
	{
		if (is_null($token)) App::abort(404);

		return View::make('auth.password_reset')->with('token', $token);
    }

    /**
	 * Handle a POST request to reset a user's password.
	 *
	 * @return Response
	 */
    public function postReset()
	{
		$credentials = Input::only(
			'password', 'password_confirmation', 'token'
		);

		$token = Input::get('token');

    	$credentials['email'] =  DB::table(Config::get('auth.reminder.table'))
						        ->where('token', $token)
						        ->pluck('email');

		$response = Password::reset($credentials, function($user, $password)
		{
			$user->password = Hash::make($password);

			$user->save();
		});

		switch ($response)
		{
			case Password::INVALID_PASSWORD:
			case Password::INVALID_TOKEN:
			case Password::INVALID_USER:
				return Redirect::back()->with('error_message', Lang::get($response));

			case Password::PASSWORD_RESET:
				return Redirect::to('/')->with('success_message', 'Su password fue cambiada de forma exitosa');
		}
	}
    
}
