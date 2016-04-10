<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attachment extends Model {
    protected $fillable = ['value', 'message_id', 'type'];
    //

    public function getType()
    {
        return $this->type;
    }

    public function message()
    {
        return $this->belongsTo('App\Models\Message');
    }
}
