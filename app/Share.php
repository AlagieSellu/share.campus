<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Share extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'object_id', 'user_id', 'is_file',
    ];

    public function object()
    {
        if ($this->is_file)
            return $this->belongsTo(File::class);
        else
            return $this->belongsTo(Folder::class);
    }

    public function auth()
    {
        return $this->object->user();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
