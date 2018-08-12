<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Hash;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function home()
    {
        $auth = Auth::user();
        return view('home', [
            'shares' => $auth->shares()->paginate(25),
            'boxes' => $auth->boxes,
            'auth' => $auth,
        ]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('profile.index', [
            'profile' => Auth::user(),
        ]);
    }

    public function rename(Request $request){

        $this->validate($request, [
            'rename' => 'required|string|min:2|max:50',
        ]);

        Auth::user()->update([
            'name' => $request['rename'],
        ]);

        return back()->with(
            'status', 'Your profile name successfully updated.'
        );
    }

    public function password(Request $request){

        $auth = Auth::user();
        if (!Hash::check($request['password_confirmation'], $auth->password)){
            $request['password_confirmation'] = '';
            return back()->withErrors([
                'password_confirmation' => 'Current password confirmation is invalid.'
            ]);
        }

        $this->validate($request, [
            'password' => 'required|string|min:6',
        ]);

        $auth->update([
            'password' => Hash::make($request['password']),
        ]);

        return back()->with(
            'status', 'Your profile name successfully updated.'
        );
    }
}
