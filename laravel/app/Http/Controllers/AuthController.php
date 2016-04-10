<?php namespace App\Http\Controllers;

class AuthController extends Controller {

    /**
     * Put username to cookies
     *
     * @return Response
     */
    public function postLogin()
    {
        $name = \Input::get('name');
        return redirect('home')->withCookie(cookie('name', $name));
    }

    /**
     * Get index page
     *
     * @return Response
     */
    public function getLogin()
    {
        return view('auth.login');
    }

    /**
     * Delete username from cookies
     *
     * @return Response
     */
    public function getLogout()
    {
        return redirect('home')->withCookie(cookie('name', "", time() - 3600));
    }
}
