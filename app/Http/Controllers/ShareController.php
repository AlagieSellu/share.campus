<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Share;
use App\User;
use App\Folder;
use App\File;
use Auth;

class ShareController extends Controller
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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

        $this->validate($request, [
            'user_email' => 'required|email|exists:users,email',
        ]);

        $errors = array();

        $user_id = User::where('email', $request['user_email'])->first()->id;

        if(Auth::user()->email == $request['user_email']){
            return redirect(route('folders.show', $request['folder_id']))->withErrors([
                'Cannot share with yourself.',
            ]);
        }

        foreach (json_decode($request['objects'], true) as $object){

            $can_share = true;

            if($object[1]){
                if($object[2]){
                    $obj = File::find($object[0]);
                }else{
                    $obj = Folder::find($object[0]);
                }

                foreach ($obj->shares as $share){
                    if($share->user->email == $request['user_email']){
                        array_push($errors, 'Already shared '.$obj->name.' with '.$share->user->email);
                        $can_share = false;
                    }
                }

                if($can_share){
                    Share::create([
                        'object_id' => $object[0],
                        'is_file' => $object[2],
                        'user_id' => $user_id,
                    ]);
                }
            }
        }

        return redirect(route('folders.show', $request['folder_id']))->with(
            'status', 'Share successfully created.'
        )->withErrors($errors);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $share = Share::findOrFail($id);
        $auth = Auth::user();

        if($share->user_id != $auth->id){
            abort(404);
        }

        if($share->is_file){
            return view('shares.file', [
                'share' => $share,
                'file' => $share->object,
            ]);
        }

        return view('shares.show', [
            'share' => $share,
        ]);
    }

    public function file($id, $file)
    {
        $share = Share::findOrFail($id);
        $file = File::findOrFail($file);
        $auth = Auth::user();

        if($share->user_id != $auth->id || $file->folder->id != $share->object_id){
            abort(404);
        }

        return view('shares.file', [
            'share' => $share,
            'file' => $file,
        ]);
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
        $share = Share::findOrFail($id);
        $auth = Auth::user();

        if($share->user_id != $auth->id && $share->auth->id != $auth->id){
            abort(404);
        }

        $share->delete();

        return back()->with(
            'status', 'Share successfully deleted.'
        );
    }
}
