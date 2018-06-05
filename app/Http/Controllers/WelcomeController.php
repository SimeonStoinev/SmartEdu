<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Poll;
use App\Votes;
use App\Test;
use Illuminate\Support\Facades\Auth;

class WelcomeController extends Controller
{
    public function __construct()
    {
        if (Auth::check()) {
            $this->middleware('auth');
        }
    }

    public function index(Request $request){
        $data = $request->except('_token');
        if($data != []){
            $testId = 0;
            for($i=-6; $i<0; $i++){
                if(intval(substr($data['access_code'], $i)) != 0){
                    $testId += intval(substr($data['access_code'], $i));
                    break;
                }
            }
            $getTestUrl = Test::getTestByAccessCodeId($testId)->first();
            if ($getTestUrl != []){

                if($getTestUrl->finish_code == null){
                    $finish_code = [];
                } else {
                    $finish_code = json_decode($getTestUrl->finish_code);
                }

                if($getTestUrl == null){
                    return view('errors.missingTest');
                }
                else{

                    if($getTestUrl->status != 'active'){
                        return view('errors.missingTest');
                    }

                    if(in_array($data['access_code'], json_decode($getTestUrl->access_code))){

                        if(in_array($data['access_code'], $finish_code)){
                            return view('errors.alreadyFinished');
                        } else{
                            return redirect('/view/test/'.$getTestUrl->slug)->with(['access_code' => $data['access_code'], 'ip_address' => $request->ip(), 'user-agent' => $request->userAgent()]);
                        }

                    } else {
                        return view('errors.exceedStudents');
                    }

                }
            }
            else{
                return view('errors.missingTest');
            }
        }
        else{
            return view('welcome');
        }
    }

    public function resultsPollAjax($id = ''){
        if (empty($id)) {
            abort(404);
        }
        $results = json_decode(Votes::getVotes($id));

        return ['results' => $results];
    }

    public function viewPoll($slug = ''){
        $pollData = Poll::getPoll($slug)->first();

        if(!$pollData){
            abort(404);
        }
        else{
            return view('frontend.poll', ['pollData' => $pollData, 'auth_id' => Auth::user()['id']]);
        }
    }

    public function votePoll(Request $request){
        $data = $request->except('_token');

        if( Votes::where('poll_id', $data['id'])->where('ip_address', $request->ip() )->where('user_agent', $request->header('User-Agent'))->count() == 0 ){
            Votes::create(['poll_id' => $data['id'], 'user_id' => 0 , $data['answer'] => 1, 'ip_address' => $request->ip(), 'user_agent' => $request->header('User-Agent')]);
            $results = json_decode(Votes::getVotes($data['id']));
            return ['results' => $results];
        }

        $content = '<p class="pollError">Вие вече сте гласували за тази анкета! <button class="delPoll" type="button">X</button></p>';
        return ['error' => $content];
    }
}
