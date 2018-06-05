<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class TestResults extends Model
{
    protected $table = 'test_results';
    protected $fillable = [
        'id', 'personal_data', 'grade', 'sub_class', 'finish_code', 'shuffled_answers', 'shuffled_ra', 'closed_answers', 'open_answers', 'student_link', 'suggestions', 'verified', 'ip_address', 'user_agent', 'test_id', 'points', 'open_answers_points'
    ];

    public function scopeGetTestResultsById($query, $id){
        return $query->where('id', $id)->where('verified', false);
    }

    public function scopeGetTestResultsByStudentId($query, $id){
        return $query->where('id', $id);
    }

    public function scopeGetAllTestResultsByTestId($query, $id){
        return $query->where('test_id', $id)->get();
    }

    public function scopeGetTestResultsByStudentLink($query, $student_link){
        return $query->where('student_link', $student_link);
    }

    public function scopeGetTestByStudentLink($query, $student_link){
        return $query->where('student_link', $student_link)->leftJoin('tests', 'tests.id', '=', 'test_id');
    }

    public function scopeGetVerificationTest($query, $id){
        return $query->where('test_results.id', $id)->where('verified', false)->where('tests.creator_id', Auth::user()['id'])->leftJoin('tests', 'tests.id', '=', 'test_id')->select('test_results.id', 'title', 'personal_data', 'closed_answers', 'open_answers', 'questions', 'question_type', 'tests.points', 'test_results.shuffled_answers as answers', 'test_results.shuffled_ra as right_answers', 'test_results.grade', 'test_results.sub_class');
    }

    public function scopeGetVerifiedTest($query, $id){
        return $query->where('test_results.id', $id)->where('verified', true)->where('tests.creator_id', Auth::user()['id'])->leftJoin('tests', 'tests.id', '=', 'test_id')->select('test_results.id', 'title', 'personal_data', 'closed_answers', 'open_answers', 'questions', 'question_type', 'tests.points', 'test_results.shuffled_answers as answers', 'test_results.shuffled_ra as right_answers', 'suggestions', 'open_answers_points', 'test_results.points as finalPoints', 'tests.eval_grid', 'student_link', 'test_results.grade', 'test_results.sub_class');
    }

    public function scopeGetAllTestsForVrfcn($query){
        return $query->where('verified', false)->where('tests.creator_id', Auth::user()['id'])->leftJoin('tests', 'tests.id', '=', 'test_id')->select('test_results.id', 'personal_data', 'title', 'test_results.created_at', 'test_results.grade', 'test_results.sub_class');
    }

    public function scopeGetAllVerifiedTests($query){
        return $query->where('verified', true)->where('tests.creator_id', Auth::user()['id'])->leftJoin('tests', 'tests.id', '=', 'test_id')->select('test_results.id', 'personal_data', 'title', 'test_results.updated_at', 'test_results.grade', 'test_results.sub_class');
    }
}
