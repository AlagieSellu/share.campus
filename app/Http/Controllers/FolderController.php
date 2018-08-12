<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Folder;

class FolderController extends Controller
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

    public function home()
    {
        return redirect(route('folders.show', Auth::user()->home_folder()->id));
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
        $except = '';
        foreach (Auth::user()->get_folder($request['folder_id'])->folders as $folder){
            $except .= $folder->name.',';
        }
        $this->validate($request, [
            'name' => 'required|string|min:2|max:50|not_in:'.$except,
        ]);

        $folder = Folder::create([
            'folder_id' => $request['folder_id'],
            'name' => $request['name'],
            'user_id' => Auth::user()->id,
        ]);

        return redirect(route('folders.show', $request['folder_id']))->with(
            'status', 'New folder created.'
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
        $folder = Auth::user()->get_folder($id);
        return view('folders.show', [
            'folder' => $folder,
            'folders' => $folder->folders,
            'files' => $folder->files,
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
        $folder = Folder::find($id);
        $except = '';
        foreach ($folder->folder->folders as $_folder){
            $except .= $_folder->name.',';
        }
        $this->validate($request, [
            'rename' => 'required|string|min:2|max:50|not_in:'.$except,
        ]);

        $folder->update([
            'name' => $request['rename'],
        ]);

        return redirect(route('folders.show', $id))->with(
            'status', 'Folder successfully updated.'
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $folder = Folder::find($id);
        $folder_up = $folder->folder;

        $folder->delete_folder();
        $folder->delete();

        if($folder->folder_id == null){
            return redirect(route('folders.home'))->with(
                'status', 'Folder successfully deleted.'
            );
        }

        return redirect(route('folders.show', $folder_up->id))->with(
            'status', 'Folder successfully deleted.'
        );
    }
}
