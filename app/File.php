<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Storage;

class File extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'name', 'address', 'size', 'folder_id', 'downloads', 'is_doc'
    ];

    public function user()
    {
        return $this->folder->user();
    }

    public function name()
    {
        return $this->name;
    }

    public function size()
    {
        return Fun::bytesToHuman($this->size);
    }

    public function extension()
    {
        $exploded = explode('.', $this->address);
        return $exploded[count($exploded)-1];
    }

    public function path()
    {
        return $this->folder->path().'/'.$this->name;
    }

    public function folder()
    {
        return $this->belongsTo(Folder::class);
    }

    public function shares()
    {
        return $this->hasMany(Share::class, 'object_id')->where('is_file', 1)->orderBy('id', 'desc');
    }

    public function delete_file(){
        return Storage::delete($this->address);
    }
}
