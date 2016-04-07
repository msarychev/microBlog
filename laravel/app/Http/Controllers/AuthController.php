<?php namespace App\Http\Controllers;

class AuthController extends Controller {

    public function postLogin()
    {
        $name = \Input::get('name');

//        setcookie('name', $name);
        return redirect()->route('home')->withCookie(cookie('name', $name));
    }
    public function getLogin()
    {
        return view('auth.login');
    }
    public function getLogout()
    {
        return redirect()->route('home')->withCookie(cookie('name', "", time() - 3600));
    }
}
