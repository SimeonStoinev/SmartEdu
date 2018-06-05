<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Test extends Model
{
    protected $table = 'tests';
    protected $fillable = [
        'id', 'title', 'questions', 'question_type', 'answers', 'right_answers', 'points', 'slug', 'access_code',
        'images', 'status', 'students', 'creator_id', 'folder_id', 'finish_code', 'grade', 'sub_class', 'eval_grid'
    ];


    public function scopeGetTestById($query, $id){
        return $query->where('id', $id)->where('creator_id', Auth::user()['id']);
    }

    public function scopeGetTestBySlug($query, $slug){
        return $query->where('slug', $slug);
    }

    public function scopeGetTestByAccessCodeId($query, $accessCode){
        return $query->where('id', $accessCode);
    }

    public function scopeGetMyTests($query){
        return $query->where('tests.creator_id', Auth::user()['id'])->leftJoin('folders', 'folders.id', '=', 'tests.folder_id')->select('tests.id', 'tests.title', 'tests.status', 'tests.created_at', 'folder_id', 'folders.title as folderTitles');
    }
}
