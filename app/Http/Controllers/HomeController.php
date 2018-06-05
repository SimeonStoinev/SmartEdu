<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Folder;
use App\Poll;
use App\Test;
use App\Helper\Paging;

class HomeController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index($page=1)
    {
        $allItems = Folder::getMineZeroLvlFolders()->count();
        $paging = Paging::create($allItems, 12, $page, url('/home/'), 2);
        $currPage = $page;
        $allPages = ceil( $allItems / 12 );
        $data = Folder::getMineZeroLvlFolders()->offset($paging['skip'])->limit($paging['perPage'])->get();

        return view('home', ['data' => $data, 'currUser' => Auth::user(), 'paging' => $paging['paging'], 'mainPage' => 1, 'currPage' => $currPage, 'allPages' => $allPages]);
    }

    public function viewFolder($id){
        $folderInfo = Folder::folderInfo($id)->first();
        $folderPolls = Folder::viewFolderPolls($id)->get();
        $folderTests = Folder::viewFolderTests($id)->get();
        $thisLevelFolders = Folder::getFoldersByLevel($folderInfo->id)->get();
        $foldLevelInfo = Folder::getFolderInfoByFoldLevel($folderInfo->folder_level)->first();

        return view('frontend.folder', ['folderData' => $folderInfo, 'id' => $folderInfo->id, 'folderPollsContent' => json_decode($folderPolls), 'folderTestsContent' => json_decode($folderTests), 'folders' => $thisLevelFolders, 'folderBack' => json_decode($foldLevelInfo)]);
    }

    public function createFolder(Request $request){
        $data = $request->except('_token');

        if(array_key_exists('newFoldTitle', $data) && array_key_exists('folderId', $data)){
            Folder::where('id', $data['folderId'])->update(['title' => $data['newFoldTitle']]);
        }
        else{
            Folder::create(['title' => $data['title'], 'folder_level' => $data['folder_level'], 'creator_id' => Auth::user()['id']]);
        }

        return redirect($data['currentUrl']);
    }

    public function deleteFolder(Request $request){
        $data = $request->except('_token');
        $thisFolder_polls = Folder::viewFolderPolls($data['id'])->get();
        $thisFolder_tests = Folder::viewFolderTests($data['id'])->get();
        $thisFolder_folders = Folder::getFoldersByLevel($data['id'])->get();
        Folder::folderInfo($data['id'])->delete();
        foreach($thisFolder_polls as $row){
            Poll::where('folder_id', $row->folder_id)->update(['folder_id' => null]);
        }
        foreach($thisFolder_tests as $row){
            Test::where('folder_id', $row->folder_id)->update(['folder_id' => null]);
        }
        foreach($thisFolder_folders as $row){
            Folder::folderInfo($row->id)->delete();
        }

        return redirect('/home');
    }
}
