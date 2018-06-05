<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Folder extends Model
{
    protected $table = 'folders';
    protected $fillable = [
        'id', 'title', 'folder_level', 'creator_id'
    ];

    public function scopeFolderInfo($query, $id){
        return $query->where('id', $id)->where('creator_id', Auth::user()['id']);
    }

    public function scopeViewFolderPolls($query, $id){
        return $query->where('folders.id', $id)->where('folders.creator_id', Auth::user()['id'])->leftJoin('polls', 'polls.folder_id', '=', 'folders.id')->select('polls.slug', 'polls.question');
    }

    public function scopeViewFolderTests($query, $id){
        return $query->where('folders.id', $id)->where('folders.creator_id', Auth::user()['id'])->leftJoin('tests', 'tests.folder_id', '=', 'folders.id')->select('tests.id', 'tests.title', 'status');
    }

    public function scopeGetMineFolders($query){
        return $query->where('creator_id', Auth::user()['id']);
    }

    public function scopeGetMineZeroLvlFolders($query){
        return $query->where('creator_id', Auth::user()['id'])->where('folder_level', null);
    }

    public function scopeGetFoldersByLevel($query, $folder_level){
        return $query->where('folder_level', $folder_level);
    }

    public function scopeGetFolderInfoByFoldLevel($query, $folder_level){
        return $query->where('id', $folder_level);
    }
}
