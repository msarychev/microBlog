<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model {

    protected $fillable = ['user', 'text'];

    public function attachments()
    {
        return $this->hasMany('App\Models\Attachment');
    }

    public function likes()
    {
        return $this->hasMany('App\Models\Like');
    }

}
