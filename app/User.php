<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'storage', 'admin',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function juniors()
    {
        return User::where('admin', '>', $this->admin)->orWhere('admin', null)->get();
    }

    public function admin()
    {
        return $this->is_admin() ? 'Level '.$this->admin : 'None';
    }

    public function is_admin()
    {
        return isset($this->admin);
    }

    public function get_folder($id)
    {
        $folder = Folder::findOrFail($id);
        if ($folder->user_id != $this->id){
            abort(404);
        }
        return $folder;
    }

    public function get_box($id)
    {
        $box = Box::findOrFail($id);
        if ($box->user_id != $this->id){
            abort(404);
        }
        return $box;
    }

    public function storage()
    {
        return Fun::bytesToHuman($this->storage);
    }

    public function available_storage()
    {
        return Fun::bytesToHuman($this->available_storage_bytes());
    }

    public function available_storage_bytes()
    {
        return $this->storage - $this->consumed_storage_bytes();
    }

    public function consumed_storage()
    {
        return Fun::bytesToHuman($this->consumed_storage_bytes());
    }

    public function consumed_storage_bytes()
    {
        $size = 0;
        foreach ($this->boxes as $box){
            $size += $box->size;
        }
        return $this->home_folder()->storage_bytes() + $size;
    }

    public function folders()
    {
        return $this->hasMany(Folder::class)->where('for_box', 0);
    }

    public function box_folders()
    {
        return $this->hasMany(Folder::class)->where('for_box', 1);
    }

    public function home_folder()
    {
        $folder = Folder::where([
            ['user_id', $this->id],
            ['folder_id', null],
        ])->first();

        if ($folder == null){
            $folder =  Folder::create([
                'name' => 'Home',
                'user_id' => $this->id,
                'folder_id' => null,
            ]);
        }
        return $folder;
    }

    public function files()
    {
        return $this->hasManyThrough(File::class, Folder::class);
    }

    public function shares()
    {
        return $this->hasMany(Share::class)->orderBy('id', 'desc');
    }

    public function boxes()
    {
        return $this->hasMany(Box::class)->orderBy('id', 'desc');
    }
}
