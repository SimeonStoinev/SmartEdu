<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Poll extends Model
{
    protected $table = 'polls';
    protected $fillable = [
        'id', 'question', 'answers', 'slug', 'creator_id', 'folder_id'
    ];

    public function scopeGetPoll($query, $slug){
        return $query->where('slug', $slug);
    }

    public function scopeGetPollById($query, $id){
        return $query->where('id', $id);
    }

    public function scopeGetMyPollById($query, $id){
        return $query->where('id', $id)->where('creator_id', Auth::user()['id']);
    }

    public function scopeGetMinePolls($query){
        return $query->where('creator_id', Auth::user()['id']);
    }
}
