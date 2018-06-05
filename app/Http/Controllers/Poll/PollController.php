<?php

namespace App\Http\Controllers\Poll;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Poll;
use App\Folder;
use App\Helper\Paging;

class PollController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function viewCreatePoll($id=0){
        $availableFolders = Folder::getMineFolders()->get();
        $selectedFolder = [];
        $folderHierarchy = [];
        $pollData = null;

        foreach ($availableFolders as $row){
            if($row->folder_level == null){
                $folderHierarchy[] = null;
            }
            else{
                $temp = Folder::folderInfo($row->folder_level)->first();
                $folderHierarchy[] = $temp->title;
            }
        }

        if($id != 0){
            $pollData = Poll::getMyPollById($id)->where('creator_id', Auth::user()['id'])->first();
            $selectedFolder = Folder::folderInfo($pollData->folder_id)->first();
        }

        return view('frontend.createPoll', ['folders' => $availableFolders, 'folderHierarchy' => $folderHierarchy, 'pollData' => $pollData, 'selectedFolder' => $selectedFolder]);
    }

    public function createPoll(Request $request, $id=0){
        $data = $request->except('_token','');
        $slug_2ndPart = '-'.Auth::user()['id'].'-'.str_random(32);
        $jsonAnswers = $data['answers'];

        if($id==0 && Auth::check()){
            Poll::create([
                'question' => $data['question'],
                'answers' => json_encode($jsonAnswers),
                'slug' => date('d-m-Y').$slug_2ndPart,
                'creator_id' => Auth::user()['id'],
                'folder_id' => $data['folder']
            ]);
        }
        else{
            Poll::getMyPollById($id)->update([
                'question' => $data['question'],
                'answers' => json_encode($jsonAnswers),
                'slug' => date('d-m-Y').$slug_2ndPart,
                'folder_id' => $data['folder']
            ]);
        }


        return redirect('poll/'.date('d-m-Y').$slug_2ndPart);
    }

    public function deletePoll(Request $request){
        $data = $request->except('_token');
        Poll::getPollById($data['id'])->delete();
    }

    public function myPolls($page=1)
    {
        $allItems = Poll::getMinePolls()->count();
        $paging = Paging::create($allItems, 6, $page, url('/poll/mine/'), 2);
        $currPage = $page;
        $allPages = ceil( $allItems / 6 );
        $data = Poll::getMinePolls()->offset($paging['skip'])->limit($paging['perPage'])->get();

        return view('frontend.myPolls', ['data' => $data, 'currUser' => Auth::user(), 'paging' => $paging['paging'], 'mainPage' => 1, 'currPage' => $currPage, 'allPages' => $allPages]);
    }
}
