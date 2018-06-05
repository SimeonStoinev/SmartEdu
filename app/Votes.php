<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class Votes extends Model
{
    protected $table = 'votes';
    protected $fillable = [
        'id', 'user_id', 'poll_id', 'a0', 'a1', 'a2', 'a3', 'a4', 'a5', 'a6', 'a7', 'ip_address', 'user_agent'
    ];

    public function scopeGetVotes($query, $pollId){
        return $query->where('poll_id', $pollId)->select( DB::raw(" sum(a0) as a0, sum(a1) as a1 , sum(a2) as a2, sum(a3) as a3, sum(a4) as a4, sum(a5) as a5, sum(a6) as a6 , sum(a7) as a7 , sum(a0 + a1 + a2 +  a3  + a4 +  a5  + a6 + a7) as allItems " ))->first();
    }
}
