<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use Storage;
use App\Box;
use App\Fun;
use Carbon\Carbon;

class BoxController extends Controller
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
        $auth = Auth::user();
        if($auth->storage > config('sys.max_upload')){
            $max = Fun::bytes_kilos(config('sys.max_upload'));
        }else{
            $max = Fun::bytes_kilos($auth->storage);
        }
        $this->validate($request, [
            'file' => 'file|mimes:zip|max:'.$max,
            'lifespan' => 'numeric|min:1|max:'.config('sys.box_max_days'),
        ]);

        $file = $request->file('file');

        $extension = '.'.$file->getClientOriginalExtension();

        $link = str_random(10);
        $file_exists = Box::where('address', $link)->first();

        while($file_exists != null)
        {
            $link = str_random(10).$extension;
            $file_exists = Box::where('address', $link)->first();
        }

        $file->storeAs('boxes', $link.'.zip');

        Box::create([
            'name' => $file->getClientOriginalName(),
            'address' => $link,
            'size' => $file->getSize(),
            'lifespan' => $request['lifespan'],
            'user_id' => $auth->id,
        ]);

        return back()->with(
            'status', 'Box successfully uploaded.'
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
        $box = Auth::user()->get_box($id);

        if($box->expired()){
            abort(404);
        }

        return view('boxes.show', [
            'box' => $box,
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
        $box = Auth::user()->get_box($id);

        Storage::delete('boxes/'.$box->address.'.zip');
        $box->delete();

        return redirect(route('home'))->with(
            'status', 'Box successfully deleted.'
        );
    }

    public function search(Request $request)
    {
        $this->validate($request, [
            'box_id' => 'required|exists:boxes,address',
        ]);


        $box = Box::where('address', $request['box_id'])->first();

        return view('boxes.show', [
            'box' => $box,
        ]);
    }

    public function download($id){
        $box = Box::findOrFail($id);
        $box->increment('downloads');
        return response()->download('storage/boxes/'.$box->address.'.zip', $box->name);
    }
}
