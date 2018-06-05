<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\createTestRequest;
use Illuminate\Support\Facades\Auth;
use App\Folder;
use App\Test;
use App\TestResults;
use Illuminate\Support\Facades\Cache;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;

class TestController extends Controller
{
    public function viewTest($slug){
        if(Auth::check()){
            $testData = Test::getTestBySlug($slug)->first();

            $questions = $this->convStrIntoArray($testData, 'questions');
            $question_type = $this->convStrIntoArray($testData, 'question_type');
            $points = $this->convStrIntoArray($testData, 'points');
            $answers = array_values(array_filter($this->convStrIntoArray($testData, 'answers')));

            if($testData->status == 'active'){
                return view('frontend.test', [
                    'testData' => $testData,
                    'questions' => $questions,
                    'question_type' => $question_type,
                    'answers' => $answers,
                    'points' => $points,
                    'images' => json_decode($testData->images)
                ]);
            }
        }
        else{
            if(Session::has('access_code')){
                $testData = Test::getTestBySlug($slug)->first();

                if($testData != null){
                    $questions = $this->convStrIntoArray($testData, 'questions');
                    $question_type = $this->convStrIntoArray($testData, 'question_type');
                    $points = $this->convStrIntoArray($testData, 'points');
                    $answers = array_values(array_filter($this->convStrIntoArray($testData, 'answers')));
                    $right_answers = array_values(array_filter(json_decode($testData->right_answers)));

                    if($testData->status == 'active'){
                        return view('frontend.test', [
                            'testData' => $testData,
                            'questions' => $questions,
                            'question_type' => $question_type,
                            'answers' => $answers,
                            'right_answers' => $right_answers,
                            'points' => $points,
                            'images' => json_decode($testData->images)
                        ]);
                    }
                    else{
                        return view('errors.missingTest');
                    }
                }
                else{
                    return view('errors.missingTest');
                }
            }
            else{
                return redirect('/');
            }
        }
    }

    public function finishTest(Request $request, $slug){
        $data = $request->except('_token');
        if($data['ip_address'] != $request->ip() || $data['user-agent'] != $request->header('User-Agent')){
            return view('errors.cheater');
        }

        $testData = Test::getTestBySlug($slug)->first();
        $right_answers = array_values(array_filter($data['shuffled_ra']));
        $c = 0;
        $autoPoints = 0;

        if(array_key_exists('open_answers', $data)){
            $openAnswers = json_encode($data['open_answers']);
        }
        else{
            $openAnswers = '';
        }

        if(array_key_exists('closed_answers', $data)){
            foreach($data['closed_answers'] as $row){
                if($row == $right_answers[$c]){
                    $autoPoints += $data['points'][$c];
                }
                $c++;
            }
            $closedAnswers = json_encode($data['closed_answers']);
        }
        else{
            $closedAnswers = '';
        }

        $newData = TestResults::create([
            'personal_data' => $data['personal_data'][0].' '.'№'.' '.$data['personal_data'][1],
            'grade' => $testData->grade,
            'sub_class' => $testData->sub_class,
            'finish_code' => $data['finish_code'],
            'shuffled_answers' => json_encode($data['shuffled_answers']),
            'shuffled_ra' => json_encode($right_answers),
            'closed_answers' => $closedAnswers,
            'open_answers' => $openAnswers,
            'student_link' => date('d-m').'-'.$data['personal_data'][0].'-'.rand(0,9).rand(0,9),
            'verified' => false,
            'ip_address' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
            'test_id' => $testData->id,
            'points' => $autoPoints
        ]);

        if($testData->finish_code == null){
            $finish_code = [];
            $finish_code[] = $data['finish_code'];
        }
        else{
            $finish_code = json_decode($testData->finish_code);
            $finish_code[] = $data['finish_code'];
        }

        $testData->update(['finish_code' => json_encode($finish_code)]);

        return redirect('/student/link')->with(['link' => $newData->student_link]);
    }

    public function studentLink(){
        $link = '';
        if(Session::has('link')){
            $link = Session::get('link');
        }
        return view('frontend.studentLink', ['link' => $link]);
    }

    public function testsForVerification(){
        $data = TestResults::getAllTestsForVrfcn()->get();
        return view('frontend.testsForVrfcn', ['data' => $data]);
    }

    public function verifiedTests(){
        $data = TestResults::getAllVerifiedTests()->get();
        return view('frontend.allVerifiedTests', ['data' => $data]);
    }

    public function verifyTest(Request $request, $id){
        $dataRequest = $request->except('_token');
        $testData = TestResults::getVerificationTest($id)->first();
        $testResultsData = TestResults::getTestResultsById($id)->first();
        $questions = $this->convStrIntoArray($testData, 'questions');
        $question_type = $this->convStrIntoArray($testData, 'question_type');
        $points = $this->convStrIntoArray($testData, 'points');
        $answers = array_values(array_filter($this->convStrIntoArray($testData, 'answers')));
        $right_answers = $this->convStrIntoArray($testData, 'right_answers');
        $additionalPts = 0;

        if(array_key_exists('points', $dataRequest)){
            $c = 0;
            foreach($dataRequest['points'] as $row){
                $additionalPts += $dataRequest['points'][$c];
                $c++;
            }
            $oa_points = json_encode($dataRequest['points']);
        }
        else{
            $oa_points = 0;
        }

        if($dataRequest != []){
            TestResults::getTestResultsById($id)->update([
                'suggestions' => $dataRequest['suggestions'],
                'verified' => true,
                'points' => $testResultsData->points + $additionalPts,
                'open_answers_points' => $oa_points
            ]);
            return redirect('/test/verify');
        }
        else{
            return view('frontend.verifyTest', [
                'testData' => $testData,
                'questions' => $questions,
                'question_type' => $question_type,
                'points' => $points,
                'answers' => $answers,
                'right_answers' => $right_answers,
                'given_ca' => json_decode($testData->closed_answers),
                'given_oa' => json_decode($testData->open_answers)
            ]);
        }
    }

    public function verifiedTest(Request $request, $id){
        $dataRequest = $request->except('_token');
        $testData = TestResults::getVerifiedTest($id)->first();
        $testResultsData = TestResults::getTestResultsByStudentId($id)->first();
        $questions = $this->convStrIntoArray($testData, 'questions');
        $question_type = $this->convStrIntoArray($testData, 'question_type');
        $points = $this->convStrIntoArray($testData, 'points');
        $answers = array_values(array_filter($this->convStrIntoArray($testData, 'answers')));
        $right_answers = $this->convStrIntoArray($testData, 'right_answers');
        $additionalPts = 0;

        if(array_key_exists('points', $dataRequest)){
            $c = 0;
            foreach($dataRequest['points'] as $row){
                $additionalPts += $dataRequest['points'][$c];
                $c++;
            }
            $oa_points = json_encode($dataRequest['points']);
        }
        else{
            $oa_points = 0;
        }

        if(is_array(json_decode($testData->open_answers_points))){
            $pointsUpUntil = array_sum(json_decode($testData->open_answers_points));
        }
        else{
            $pointsUpUntil = $testData->open_answers_points;
        }

        if($dataRequest != []){
            TestResults::where('id', $id)->update([
                'suggestions' => $dataRequest['suggestions'],
                'points' => $testData->finalPoints - $pointsUpUntil + $additionalPts,
                'open_answers_points' => $oa_points
            ]);
            return redirect('/test/verified');
        }
        else{
            return view('frontend.verifiedTest', [
                'testData' => $testData,
                'questions' => $questions,
                'question_type' => $question_type,
                'points' => $points,
                'answers' => $answers,
                'right_answers' => $right_answers,
                'final_points' => $testResultsData->points,
                'eval_grid' => json_decode($testData->eval_grid),
                'given_ca' => json_decode($testData->closed_answers),
                'given_oa' => json_decode($testData->open_answers),
                'oa_points' => json_decode($testData->open_answers_points)
            ]);
        }
    }

    public function viewCreateTest($id=0){
        $data = [];
        $questions = [];
        $question_type = [];
        $points = [];
        $images = [];
        $answers = [];
        $right_answers = [];
        $selectedFolder = [];
        $status = [];
        $access_codes = [];
        $eval_grid = [];
        $selectedFolder = [];
        $folderHierarchy = [];
        $availableFolders = Folder::getMineFolders()->get();

        $accessToken = str_random(60).'_'.Auth::id();
        Cache::store('file')->put($accessToken, $accessToken, 120);

        foreach ($availableFolders as $row){
            if($row->folder_level == null){
                $folderHierarchy[] = null;
            }
            else{
                $temp = Folder::folderInfo($row->folder_level)->first();
                $folderHierarchy[] = $temp->title;
            }
        }

        if($id!=0){
            $accessToken = '';
            $data = Test::where('id', $id)->where('creator_id', Auth::user()['id'])->first();
            $questions = $this->convStrIntoArray($data, 'questions');
            $question_type = $this->convStrIntoArray($data, 'question_type');
            $points = $this->convStrIntoArray($data, 'points');
            $images =  $this->convStrIntoArray($data, 'images',true);
            $answers = array_values($this->convStrIntoArray($data, 'answers'));
            $right_answers = array_values($this->convStrIntoArray($data, 'right_answers'));
            $eval_grid = json_decode($data->eval_grid);
            $selectedFolder = Folder::folderInfo($data->folder_id)->first();
            $status = $data->status;
            $access_codes = json_decode($data->access_code);
        }

        return view('frontend.createTest', [
            'testData' => $data,
            'folders' => $availableFolders,
            'folderHierarchy' => $folderHierarchy,
            'selectedFolder' => $selectedFolder,
            'questions' => $questions,
            'question_type' => $question_type,
            'points' => $points,
            'images' => $images,
            'answers' => $answers,
            'right_answers' => $right_answers,
            'status' => $status,
            'eval_grid' => $eval_grid,
            'access_code' => $access_codes,
            'accessToken' => $accessToken
        ]);
    }

    public function createTest(Request $request, $id=0){
        if(!Auth::check()){
            abort(404);
        }

        $data = $request->except('_token');

        if(!array_key_exists('answers', $data) || !array_key_exists('questions', $data) || !array_key_exists('question_type', $data) || !array_key_exists('points', $data) || !array_key_exists('right_answers', $data) || !array_key_exists('blankImages', $data)){
            return redirect()->back()->with('failure', 'Вие нямате зададени въпроси и отговори във вашия тест.');
        }

        $slug_2ndPart = '-'.Auth::user()['id'].'-'.str_random(48);
        $test = Test::getTestById($id)->first();
        $images = [];

        if($request->hasFile('images')){
            $imagesArray = array_values($request->file('images'));
            for($i = 0; $i < count($imagesArray) ; $i++){
                array_push($images, Storage::putFile('images', $imagesArray[$i]));
                Image::make($imagesArray[$i])->resize(null, 150, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($images[$i]);
            }

            $c = 0;
            for ($i = 0; $i < count($data['blankImages']); $i++){
                if ($data['blankImages'][$i] == '1'){
                    $data['blankImages'][$i] = $images[$c];
                    $c++;
                }
            }
        }

        if($id==0){
            if(Cache::has($data['securityToken'])){
                $newTest = Test::create([
                    'title' => $data['title'],
                    'questions' => json_encode($data['questions']),
                    'question_type' => json_encode($data['question_type']),
                    'answers' => json_encode($data['answers']),
                    'right_answers' => json_encode($data['right_answers']),
                    'points' => json_encode($data['points']),
                    'slug' => date('d-m-Y').$slug_2ndPart,
                    'images' => json_encode($data['blankImages']),
                    'status' => 'sketch',
                    'creator_id' => Auth::user()['id'],
                    'folder_id' => $data['folder']
                ]);

                if($newTest){
                    Cache::forget($data['securityToken']);
                }

                $id = $newTest->id;
            }
            else{
                return redirect()->back()->with('accessTokenMissing', 'Вие не можете да създадете този тест.');
            }
        }
        elseif($test['creator_id'] == Auth::user()['id'] && $test->status == 'sketch'){
            Test::getTestById($id)->update([
                'title' => $data['title'],
                'questions' => json_encode($data['questions']),
                'question_type' => json_encode($data['question_type']),
                'answers' => json_encode($data['answers']),
                'right_answers' => json_encode($data['right_answers']),
                'points' => json_encode($data['points']),
                'images' => json_encode($data['blankImages']),
                'status' => 'sketch',
                'students' => $data['students_number'],
                'grade' => $data['grade'],
                'sub_class' => $data['sub_class'],
                'eval_grid' => json_encode($data['eval_grid']),
                'folder_id' => $data['folder']
            ]);
        }
        elseif($test['creator_id'] == Auth::user()['id'] && $test->status == 'closed'){
            $newTest = Test::create([
                'title' => $data['title'],
                'questions' => json_encode($data['questions']),
                'question_type' => json_encode($data['question_type']),
                'answers' => json_encode($data['answers']),
                'right_answers' => json_encode($data['right_answers']),
                'points' => json_encode($data['points']),
                'slug' => date('d-m-Y').$slug_2ndPart,
                'images' => json_encode($data['blankImages']),
                'status' => 'sketch',
                'students' => $data['students_number'],
                'grade' => $data['grade'],
                'sub_class' => $data['sub_class'],
                'eval_grid' => json_encode($data['eval_grid']),
                'creator_id' => Auth::user()['id'],
                'folder_id' => $data['folder']
            ]);
            $id = $newTest->id;
        }

        return redirect('test/create/'.$id);
    }

    public function myTests(){
        $data = Test::getMyTests()->get();
        return view('frontend.myTests', ['myTests' => $data]);
    }

    public function viewDeleteTest($id){
        $testData = Test::getTestById($id)->first();
        return view('frontend.deleteTest', ['testData' => $testData]);
    }

    public function deleteTest($id){
        $testData = Test::getTestById($id)->first();
        if($testData->creator_id == Auth::user()['id']){
            $testData->delete();
            if(json_decode(TestResults::getAllTestResultsByTestId($id)) != []){
                TestResults::getAllTestResultsByTestId($id)->delete();
            }
        }
        return redirect('/home');
    }

    public function activateTest(Request $request){
        $data = $request->except('_token');
        $data['access_code'] = [];

        for($i = 0;$i< $data['students'];$i++ ){
            if(strlen($data['id']) == 1){
                $data['access_code'][] = substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 5).$data['id'];
            }
            elseif(strlen($data['id']) == 2){
                $data['access_code'][] = substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 4).$data['id'];
            }
            elseif(strlen($data['id']) == 3){
                $data['access_code'][] = substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 3).$data['id'];
            }
            elseif(strlen($data['id']) == 4){
                $data['access_code'][] = substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 2).$data['id'];
            }
            elseif(strlen($data['id']) == 5){
                $data['access_code'][] = substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 1).$data['id'];
            }
            else{
                $data['access_code'][] = $data['id'];
            }
        }

        $eval_grid = [
            $data['evalGrid0'],
            $data['evalGrid1'],
            $data['evalGrid2'],
            $data['evalGrid3'],
            $data['evalGrid4'],
            $data['evalGrid5'],
            $data['evalGrid6'],
            $data['evalGrid7'],
            $data['evalGrid8'],
            $data['evalGrid9'],
        ];

        Test::getTestById($data['id'])->update([
                'status' => 'active',
                'students' => $data['students'],
                'grade' => $data['grade'],
                'sub_class' => $data['sub_class'],
                'eval_grid' => json_encode($eval_grid),
                'access_code' => json_encode($data['access_code'])
            ]
        );
    }

    public function closeTest(Request $request){
        $data = $request->except('_token');
        Test::getTestById($data['id'])->update(['status' => 'closed']);
    }

    public function viewTestCodes($slug){
        $data = Test::getTestBySlug($slug)->first();

        return view('frontend.accessCodes', ['data' => $data]);
    }

    public function checkTest($student_link=0){
        $testData = TestResults::getTestByStudentLink($student_link)->first();
        $testResultsData = TestResults::getTestResultsByStudentLink($student_link)->first();

        if($testData != null){
            if($testData->verified == true){
                $questions = $this->convStrIntoArray($testData, 'questions');
                $question_type = $this->convStrIntoArray($testData, 'question_type');
                $points = $this->convStrIntoArray($testData, 'points');
                $answers = $this->convStrIntoArray($testResultsData, 'shuffled_answers');

                return view('frontend.checkTest', [
                    'testData' => $testData,
                    'questions' => $questions,
                    'question_type' => $question_type,
                    'answers' => $answers,
                    'right_answers' => json_decode($testData->shuffled_ra),
                    'oa_points' => json_decode($testData->open_answers_points),
                    'points' => $points,
                    'final_points' => $testResultsData->points,
                    'eval_grid' => json_decode($testData->eval_grid),
                    'images' => $testData->images,
                    'given_ca' => json_decode($testData->closed_answers),
                    'given_oa' => json_decode($testData->open_answers)
                ]);
            }
            else{
                $notVerifiedMsg = 'Вашият тест не е проверен от учителя.';
                return view('frontend.checkTest', ['data' => $notVerifiedMsg]);
            }
        }
        else{
            return view('errors.missingTest');
        }
    }



    private function convStrIntoArray($data, $convertedInfo, $returnNull = false){
        $result = [];
        foreach (json_decode($data->$convertedInfo) as $row){
            $result[] = $row;
        }

        if (!$returnNull) {
            for($i = 0; $i < count($result); $i++){
                if( is_null( $result[$i] )){
                    unset($result[$i]);
                }
            }
        }

        return $result;
    }
}
