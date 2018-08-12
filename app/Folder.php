<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Folder extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'name', 'folder_id', 'user_id', 'for_box',
    ];

    public function storage()
    {
        return Fun::bytesToHuman($this->storage_bytes());
    }

    public function storage_bytes()
    {
        $storage = 0;

        foreach ($this->files as $file){
            $storage += $file->size;
        }

        foreach ($this->folders as $folder){
            $storage += $folder->storage_bytes();
        }

        return $storage;
    }

    public function count()
    {
        return count($this->folders)+count($this->files);
    }

    public function name()
    {
        return '/'.$this->name;
    }

    public function path()
    {
        $path = '';

        $folder = $this;

        while($folder->id != ''){
            $path = $folder->name().$path;
            $folder = $folder->folder;
        }

        return $path;
    }

    public function folder()
    {
        return $this->belongsTo(Folder::class)->withDefault([
            'id' => '',
            'name' => '',
        ]);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function files()
    {
        return $this->hasMany(File::class)->orderBy('id', 'desc');
    }

    public function folders()
    {
        return $this->hasMany(Folder::class)->orderBy('id', 'desc');
    }

    public function shares()
    {
        return $this->hasMany(Share::class, 'object_id')->where('is_file', 0)->orderBy('id', 'desc');
    }

    public function delete_folder(){

        foreach ($this->files as $file){
            $file->delete_file();
        }
        foreach ($this->folders as $folder){
            $folder->delete_folder();
        }

        return 1;
    }
}
