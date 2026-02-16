<?php namespace App\Http\Controllers\Auth;

use Auth;
use App\User;
use Validator;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class AuthController extends Controller {

	public function loginIndex(){
		return view('auth.login');
	}

	public function registerIndex(){
		return view('auth.register');
	}

	public function register(Request $request){
        $this->validate($request,[
            'name' =>'required',
            'email' => 'required|unique:users|email|max:255',
            'password' => 'required'
            ]);

        User::create([
            'id' => sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
                mt_rand(0, 0xffff), mt_rand(0, 0xffff),
                mt_rand(0, 0xffff),
                mt_rand(0, 0x0fff) | 0x4000,
                mt_rand(0, 0x3fff) | 0x8000,
                mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
            ),
            'name' => $request->input('name'),
            'email'    => $request->input('email'),
            'password' => bcrypt($request->input('password'))
            ]);

        return redirect()->route('auth.login')->with('info','You have successfully created your account.');
	}

	public function login(Request $request)
    {
        $this->validate($request,[
            'email'=>'required|email',
            'password'=>'required']);
        if (!Auth::attempt($request->only('email', 'password')))
        {
            return redirect()->route('auth.login')->with('info','We could not log you in with those details.');
        }
        return redirect()->route('movies.index')->with('info','You have successfully logged in.');
    }

	public function logout()
	{
		Auth::logout();
		return redirect()->route('auth.login')->with('info','You have successfully logged out.');
	}


}
