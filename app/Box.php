<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use Storage;

class Box extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'name', 'address', 'size', 'lifespan', 'user_id', 'downloads',
    ];

    public function size()
    {
        return Fun::bytesToHuman($this->size);
    }

    public function folder()
    {
        return $this->belongsTo(Folder::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function expire_at()
    {
        return $this->created_at->addDays($this->lifespan);
    }

    public function expired(){
        $expired = Carbon::now()->diffInSeconds($this->expire_at(), 0) < 0;
        if($expired){
            Storage::delete('boxes/'.$this->address.'.zip');
            $this->delete();
            return true;
        }
        return false;
    }
}
