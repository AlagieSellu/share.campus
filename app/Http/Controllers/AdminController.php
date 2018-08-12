<?php

namespace App\Http\Controllers;

use App\Http\Middleware\AdminMiddleware;
use Illuminate\Http\Request;
use Auth;
use App\User;
use App\Fun;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(AdminMiddleware::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $auth = Auth::user();
        return view('admin', [
            'auth' => $auth,
            'pagination' => 1,
            'users' => User::where('admin', '>', $auth->admin)
                ->orWhere('admin', null)->orderBy('name', 'asc')->paginate(25),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $auth = Auth::user();

        $in = '';

        foreach ($auth->juniors() as $junior){
            $in .= $junior->email.',';
        }

        $user = User::where('email', $request['email'])->first();

        $this->validate($request, [
            'email' => 'required|email|in:'.$in,
            'storage' => 'required|numeric|max:'.(Fun::bytes_gigs($auth->storage)-Fun::bytes_gigs($user->storage)),
        ]);

        $add = $user->storage+Fun::gigs_bytes($request['storage']);

        $user->update([
            'storage' => $add < 0 ? 0 : $add,
        ]);

        if($request['promote'] != null){
            $user->update([
                'admin' => $auth->admin + 1,
            ]);
        }

        if($request['demote'] != null){
            $user->update([
                'admin' => null,
            ]);
        }

        return back()->with(
            'status', 'Promotion successful.'
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function search(Request $request)
    {
        $auth = Auth::user();
        $users = User::where('email', $request['email'])->first();

        if ($users != null && ($users->admin > $auth->admin || $users->admin == null)){
            $ret = [$users];
        }else{
            $ret = [];
        }
        return view('admin', [
            'auth' => $auth,
            'email' => $request['email'],
            'pagination' => 0,
            'users' => $ret,
        ]);
    }
}
