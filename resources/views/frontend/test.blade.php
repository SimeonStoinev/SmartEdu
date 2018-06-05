@extends('layouts.app')
@section('content')
    <div id="whitebox">
        <p id="closeImage" onclick="$(this).parent().css('visibility', 'hidden')">X</p>
        <img src=""/>
    </div>
    <div class="container">
        @guest
            {{ Form::open(array('url' => '/view/test/'.$testData->slug , 'method' => 'post', 'id' => 'solveTest'))}}
                <h1>{{$testData->title}}</h1>
                <label class="longerQuestions" id="randomizeTasks">
                    <input class="col-md-8 personal_data" type="text" name="personal_data[]" maxlength="45" placeholder="Име и фамилия" required style="width:65%;margin-left: 1em;">
                    <input class="col-md-2 personal_data" type="number" name="personal_data[]" min="1" placeholder="№ в клас" required style="margin-left: .5em;">
                </label>
                <ol id="task_keeper">
                    @if( isset($testData->title) )
                        <?php $c=-1;?>
                        <?php $c_ra=0;?>
                        <?php $answer_index = 0;?>
                        @foreach(json_decode($testData->questions) as $row)
                            <?php $c++;?>
                            <li class="col-md-12 task">
                                <label>
                                    <span>{{$questions[$c]}}</span>
                                </label>
                                @if($question_type[$c] == 'closed')
                                    <label>
                                        <input type="hidden" name="points[]" value="{{$points[$c]}}"><span class="points">@if($points[$c] != 1) {{$points[$c]}} точки @else {{$points[$c]}} точка @endif</span>
                                    </label>
                                @else
                                    <label>
                                        <span class="points">@if($points[$c] != 1) {{$points[$c]}} точки @else {{$points[$c]}} точка @endif</span>
                                    </label>
                                @endif
                                <br>
                                @if($question_type[$c] == 'closed')
                                    <label class="test_answers">
                                        <span class="shuffleAns">
                                            <input type="checkbox" name="closed_answers[]" value="{{$answers[0 + $answer_index]}}" onclick="selectAnswer($(this))">
                                            <span class="taskAns">А) </span><span>{{$answers[0 + $answer_index]}}</span>
                                            <input type="hidden" name="shuffled_answers[]" value="{{$answers[0 + $answer_index]}}">
                                            <br>
                                        </span>
                                        <span class="shuffleAns">
                                            <input type="checkbox" name="closed_answers[]" value="{{$answers[1 + $answer_index]}}" onclick="selectAnswer($(this))">
                                            <span class="taskAns">Б) </span><span>{{$answers[1 + $answer_index]}}</span>
                                            <input type="hidden" name="shuffled_answers[]" value="{{$answers[1 + $answer_index]}}">
                                            <br>
                                        </span>
                                        <span class="shuffleAns">
                                            <input type="checkbox" name="closed_answers[]" value="{{$answers[2 + $answer_index]}}" onclick="selectAnswer($(this))">
                                            <span class="taskAns">В) </span><span>{{$answers[2 + $answer_index]}}</span>
                                            <input type="hidden" name="shuffled_answers[]" value="{{$answers[2 + $answer_index]}}">
                                            <br>
                                        </span>
                                        <span class="shuffleAns">
                                            <input type="checkbox" name="closed_answers[]" value="{{$answers[3 + $answer_index]}}" onclick="selectAnswer($(this))">
                                            <span class="taskAns">Г) </span><span>{{$answers[3 + $answer_index]}}</span>
                                            <input type="hidden" name="shuffled_answers[]" value="{{$answers[3 + $answer_index]}}">
                                            <br>
                                        </span>
                                        <input type="hidden" name="shuffled_ra[]" value="{{ $right_answers[$c_ra] }}">
                                        <?php $c_ra++;?>
                                    </label>
                                    <input type="hidden" name="closed_answers[]" value="{{ null }}">
                                    @if(isset($images[$c]))
                                        <img onclick="viewImage($(this))" class="taskImages" src="/{{$images[$c]}}" style="height: 6.3em;">
                                    @endif
                                    <?php $answer_index += 4;?>
                                @else
                                    <label class="test_answers openAnswers">
                                        <textarea style="resize: none;outline-color: rgb(132, 206, 235);" name="open_answers[]" cols="60" rows="6" maxlength="500"></textarea>
                                    </label>
                                    @if(isset($images[$c]))
                                        <img onclick="viewImage($(this))" class="taskImages" style="height: 9.9em;" src="/{{$images[$c]}}">
                                    @endif
                                @endif
                            </li>
                        @endforeach
                        @if(Session::has('access_code'))
                            <input type="hidden" name="finish_code" value="{{ Session::get('access_code') }}">
                            <input type="hidden" name="ip_address" value="{{ Session::get('ip_address') }}">
                            <input type="hidden" name="user-agent" value="{{ Session::get('user-agent') }}">
                        @endif
                        <button type="submit" style="margin-left: 1em;">Предай</button>
                    @endif
                </ol>
            {{ Form::close() }}
        @else
            {{ Form::open(array('url' => '/view/test/'.$testData->slug , 'method' => 'get', 'id' => 'solveTest'))}}
                <h1>{{$testData->title}}</h1>
                <label class="longerQuestions" style="margin-left: 1em;">
                    <input class="personal_data" type="text" name="personal_data" placeholder="Име и № в клас" disabled>
                </label>
                <ol id="task_keeper">
                    @if( isset($testData->title) )
                        <?php $c=-1;?>
                        <?php $answer_index = 0;?>
                        @foreach(json_decode($testData->questions) as $row)
                            <?php $c++;?>
                            <li class="col-md-12 task">
                                <label>
                                    <span>{{$questions[$c]}}</span>
                                </label>
                                @if($question_type[$c] == 'closed')
                                    <label>
                                        <input type="hidden" name="points[]" value="{{$points[$c]}}"><span class="points">- @if($points[$c] != 1) {{$points[$c]}} точки @else {{$points[$c]}} точка @endif</span>
                                    </label>
                                @else
                                    <label>
                                        <span class="points">- @if($points[$c] != 1) {{$points[$c]}} точки @else {{$points[$c]}} точка @endif</span>
                                    </label>
                                @endif
                                <br>
                                @if($question_type[$c] == 'closed')
                                    <label class="test_answers">
                                        <input type="checkbox" name="closed_answers[]" disabled> <span>А) {{$answers[0 + $answer_index]}}</span>
                                        <input type="hidden" name="shuffled_answers[]" value="{{$answers[0 + $answer_index]}}">
                                        <br>
                                        <input type="checkbox" name="closed_answers[]" disabled> <span>Б) {{$answers[1 + $answer_index]}}</span>
                                        <input type="hidden" name="shuffled_answers[]" value="{{$answers[0 + $answer_index]}}">
                                        <br>
                                        <input type="checkbox" name="closed_answers[]" disabled> <span>В) {{$answers[2 + $answer_index]}}</span>
                                        <input type="hidden" name="shuffled_answers[]" value="{{$answers[0 + $answer_index]}}">
                                        <br>
                                        <input type="checkbox" name="closed_answers[]" disabled> <span>Г) {{$answers[3 + $answer_index]}}</span>
                                        <input type="hidden" name="shuffled_answers[]" value="{{$answers[0 + $answer_index]}}">
                                    </label>
                                    @if(isset($images[$c]))
                                        <img onclick="viewImage($(this))" class="taskImages" src="/{{$images[$c]}}" style="height: 6.3em;">
                                    @endif
                                    <?php $answer_index += 4;?>
                                @else
                                    <label class="test_answers">
                                        <textarea style="resize: none;outline-color: rgb(132, 206, 235);" name="open_answers[]" cols="63" rows="6" maxlength="500"></textarea>
                                    </label>
                                    @if(isset($images[$c]))
                                        <img onclick="viewImage($(this))" class="taskImages" src="/{{$images[$c]}}" style="height: 9.9em;" >
                                    @endif
                                @endif
                            </li>
                        @endforeach
                        <button type="button" style="margin-left: 1em;width: auto" onclick="window.print();">Принтирай</button>
                    @endif
                </ol>
            {{ Form::close() }}
        @endguest
    </div>
    <style>
        #solveTest input[type=number]{
            outline: none;
            border: none;
            border-bottom: 1px dashed #777;
            background: transparent;
            padding-left: .25em;
            padding-right: 0;
        }
        .taskImages{
            vertical-align: initial;
            border: 1px solid;
            cursor: pointer;
            transition: all .2s;
            max-width: 45%;
        }
        .test_answers{
            margin-right: .8em;
        }
        #whitebox {
            width: 100%;
            height: 100%;
            visibility: hidden;
            background: rgba(8, 8, 8, .8);
            position: fixed;
            z-index: 10;
        }
        #whitebox img {
            width: 60%;
            position: absolute;
            max-width: 75%;
            top: 50%;
            left: 50%;
            transform: translateX(-50%) translateY(-50%);
            display: inline-block;
            border-radius: 6px;
        }
        #closeImage{
            position: absolute;
            top: 16%;
            right: 5%;
            font-size: 3em;
            color: rgb(132, 206, 235);
            cursor: pointer;
            transition: all .4s;
        }
        #closeImage:hover{
            color: rgba(132, 206, 235, .7);
        }
        @media only screen and (max-width: 1024px) {
            #solveTest{
                margin-top: 15%;
            }
        }
    </style>
@endsection
