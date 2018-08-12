<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Folder;
use App\File;
use App\Fun;
use Auth;
use Storage;

class FileController extends Controller
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
    public function create(Request $request, $id)
    {
        Folder::findOrFail($id);

        $this->validate($request, [
            'document_name' => 'required|string|unique:files,name,NULL,id,folder_id,'.$id,
        ]);

        $link = str_random(40).'.docx';

        $file_exists = File::where('address', 'docs/'.$link)->first();

        while($file_exists != null)
        {
            $link = str_random(40).'.docx';
            $file_exists = File::where('address', 'docs/'.$link)->first();
        }

        Storage::put('docxs/'.$link, '<h1>Title</h1><p>Body</p>');

        $file = File::create([
            'name' => $request['document_name'],
            'address' => 'docxs/'.$link,
            'size' => Storage::size('docxs/'.$link),
            'is_doc' => 1,
            'folder_id' => $id,
        ]);

        return redirect(route('files.show', $file->id))->with(
            'status', 'Document successfully created.'
        );
    }

    public function store_doc(Request $request, $id)
    {
        $file = File::findOrFail($id);

        if ($request['document_content'] == null){
            $request['document_content'] = '<h1>Title</h1><p>Body</p>';
        }

        $this->validate($request, [
            'document_content' => 'string',
        ]);

        Storage::put('temp/'.$file->address, $request['document_content']);

        $temp_size = Storage::size('temp/'.$file->address, $request['document_content']);

        $user_ava = $file->user->available_storage_bytes();

        if ($temp_size > config('sys.doc_max_words')) {
            return back()->withInput()->withErrors([
                'File exceed maximum document size of '.Fun::bytesToHuman(config('sys.doc_max_words')),
            ]);
        }

        if ($temp_size > $user_ava) {
            return back()->withInput()->withErrors([
                'Files failed to save. You out of storage',
            ]);
        }

        Storage::delete($file->address);

        Storage::move('temp/'.$file->address, $file->address);

        $file->update([
            'size' => Storage::size($file->address),
        ]);

        return redirect(route('files.editor', $file->id))->with(
            'status', 'Document successfully saved'
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $folder = Folder::find($request['folder_id']);
        $selected = json_decode($request['selected'], 1);
        $uploaded_files = $request->file('files');
        $selected_files = array();
        $storage = 0;

        foreach (array_keys($selected) as $select)
        {
            if($selected[$select]){
                if($uploaded_files[$select]->getSize() > config('sys.max_upload')){
                    return back()->with(
                        'status', 'Files failed to upload. Cannot upload file bigger than '.Fun::bytesToHuman(config('sys.max_upload')).' MB'
                    );
                }
                array_push($selected_files, $uploaded_files[$select]);
                $storage += $uploaded_files[$select]->getSize();
            }
        }

        if ($storage > $folder->user->available_storage_bytes()) {
            return back()->with(
                'status', 'Files failed to upload. You out of storage'
            );
        }

        foreach ($selected_files as $file)
        {
            $extension = '.'.$file->getClientOriginalExtension();

            $link = str_random(40).$extension;

            $file_exists = File::where('address', 'files/'.$link)->first();

            while($file_exists != null)
            {
                $link = str_random(40).$extension;
                $file_exists = File::where('address', 'files/'.$link)->first();
            }

            $filename = $file->storeAs('files', $link);

            $file = File::create([
                'name' => $file->getClientOriginalName(),
                'address' => $filename,
                'size' => $file->getSize(),
                'folder_id' => $request['folder_id'],
            ]);
        }

        return redirect(route('folders.show', $request['folder_id']))->with(
            'status', 'Files successfully uploaded.'
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
        $file = File::findOrFail($id);
        $auth = Auth::user();

        if($file->folder->user_id != $auth->id){
            abort(404);
        }

        return view('files.show', [
            'file' => $file,
        ]);
    }

    public function editor($id)
    {
        $file = File::findOrFail($id);
        $auth = Auth::user();

        if($file->folder->user_id != $auth->id){
            abort(404);
        }

        return view('files.editor', [
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
        $file = File::find($id);
        $except = '';
        foreach ($file->folder->files as $_file){
            $except .= $_file->name.',';
        }
        $this->validate($request, [
            'rename' => 'required|string|min:2|max:50|not_in:'.$except,
        ]);

        $file->update([
            'name' => $request['rename'],
        ]);

        return redirect(route('files.show', $id))->with(
            'status', 'File successfully updated.'
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
        $file = File::findOrFail($id);
        $folder = $file->folder;

        $file->delete_file();
        $file->delete();

        return redirect(route('folders.show', $folder->id))->with(
            'status', 'File successfully deleted.'
        );
    }

    public function download($id){
        $file = File::findOrFail($id);
        $extension = substr($file->address, 47);
        $name = $file->name;
        if (substr($file->name, strlen($file->name)-strlen($extension)) != $extension){
            $name = str_replace(' ', '_', $file->name).'.'.$extension;
        }
        $file->update([
            'downloads' => $file->downloads + 1
        ]);

        return response()->download('storage/'.$file->address, $name);
    }
}
