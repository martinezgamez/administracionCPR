<?php

namespace App\Http\Controllers;

use App\User, Auth, Redirect, View, Validator, Input, Hash;

class HomeController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/

	public function getIndex()
	{
        if(Auth::check()){
            return View::make("index");
        }
		return Redirect::to('login');
	}

    public function getLogin(){

        //$creds = array( 'email' => 'admin@cpr_melilla.com', 'password' =>  'password');
        //return (Auth::attempt($creds) ? "OK" : "No OK");
        //return (Hash::check("password","bdf0a9f327c12dd358418e0f78e2d8d7630cf980") ? "Same" : "Not the same" );
        //return Hash::make("password");

        // Verificamos que el usuario no esté autenticado
        if (Auth::check())
        {
            // Si está autenticado lo mandamos a la raíz donde estara el mensaje de bienvenida.
            return Redirect::to('/');
        }
        // Mostramos la vista login.blade.php (Recordemos que .blade.php se omite.)
        return View::make('login');

    }

    public function postLogin(){

        // Valido el formulario
        $rules = array(
            'email' => 'required',
        );
        $messages = array(
            'required' => 'El campo :attribute es obligatorio.',
        );

        $validator = Validator::make(Input::all(), $rules, $messages);

        if ($validator->fails())
        {
            return Redirect::to('login')->withErrors($validator);
        }

        // Guardamos en un array los datos del usuario.
        $userdata = array(
            'email' => Input::get('email'),
            'password'=> Input::get('password')
        );

        // Obtengo los datos de usuario
        $user = User::where('email', $userdata['email'])->first();

        if(isset($user) && ($user->account_id == 1 || $user->account_id == 2)){
            // Validamos los datos y además mandamos como un segundo parámetro la opción de recordar el usuario.
            if( $this->hashAndCheckUser($user, $userdata['password']) ){

                if(Auth::login($user))
                {
                    // De ser datos válidos nos mandara a la bienvenida
                    return Redirect::to('/');
                }
            }
        }
        // En caso de que la autenticación haya fallado manda un mensaje al formulario de login y también regresamos los valores enviados con withInput().
        return Redirect::to('login')
            ->with('mensaje_error', 'Tus datos son incorrectos')
            ->withInput();

    }

    public function getLogout(){
        Auth::logout();
        return Redirect::to('login')
            ->with('mensaje_error', 'Tu sesión ha sido cerrada.');
    }

    private function hashAndCheckUser($userObject, $password){

        // Obtengo el salt de la cadena del password
        $tmp = explode("$", $userObject->password_hash);
        $salt = $tmp[1];

        // Contruyo el hash con la contraseña insertada por el usuario y el salt y lo comparo
        return Hash::check($password, $userObject->password_hash, array('salt' => $salt));
    }
}
