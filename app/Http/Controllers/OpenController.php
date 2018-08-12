<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use Illuminate\Support\Facades\Hash;
use App\Folder;
use App\File;
use Storage;

class OpenController extends Controller
{
    public function register(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users|regex:/(.*)'.config('sys.email_domain'),
        ]);

        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make(str_random(10)),
            'storage' => config('sys.new_storage'),
        ]);

        $folder = Folder::create([
            'name' => 'Home',
            'user_id' => $user->id,
        ]);

        $link = str_random(40).'.docx';

        $file_exists = File::where('address', 'docxs/'.$link)->first();

        while($file_exists != null)
        {
            $link = str_random(40).'.docx';
            $file_exists = File::where('address', 'docxs/'.$link)->first();
        }

        Storage::copy('guide.docx', 'docxs/'.$link);

        File::create([
            'name' => 'Read Me System Guide',
            'address' => 'docxs/'.$link,
            'is_doc' => 1,
            'size' => 0,
            'folder_id' => $folder->id,
        ]);

        return view('auth.passwords.email', [
            'email' => $request['email'],
        ]);
    }
}
