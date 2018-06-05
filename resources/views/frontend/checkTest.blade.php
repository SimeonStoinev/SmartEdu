@extends('layouts.app')
@section('content')
    <div class="container">
        @if(isset($data))
            <div class="table-responsive row">
                <p style="font-size: 32px;text-align: center;top: 50%;color: #111111;margin-top: 22%;border-radius: 45%;background: rgba(255,255,255,.5)">{{$data}}</p>
            </div>
        @else
            {{ Form::open(array('url' => '/view/test/'.$testData->slug , 'method' => 'get', 'id' => 'solveTest'))}}
            <h1>{{$testData->title}}</h1>
            <label class="longerQuestions" style="margin-left: 1em;">
                <input class="col-md-12 personal_data" type="text" name="personal_data" value="{{$testData->personal_data}}" disabled>
            </label>
            <ol id="task_keeper">
                @if( isset($testData->title) )
                    <?php $c=-1;?>
                    <?php $c_oa=-1;?>
                    <?php $c_q=-1;?>
                    <?php $answer_index = 0;?>
                    @foreach(json_decode($testData->questions) as $row)
                        <?php $c++;?>
                        <li class="col-md-12 task">
                            <label>
                                <span>{{$questions[$c]}}</span>
                            </label>
                            @if($question_type[$c] == 'closed')
                                <?php $c_q++;?>
                                <label>
                                    <span class="points">- @if($given_ca[$c_q] == $right_answers[$c_q]) @if($points[$c_q] != 1) {{$points[$c_q]}} точки @else {{$points[$c_q]}} точка @endif @else 0 точки @endif </span>
                                </label>
                                <br>
                                <label class="test_answers">
                                    <input type="checkbox" name="closed_answers[]" value="{{$answers[0 + $answer_index]}}" @if($given_ca[$c_q] == $answers[0 + $answer_index]) checked @endif disabled> @if($answers[0 + $answer_index] == $right_answers[$c_q])<span class="checkTestRA">А) {{$answers[0 + $answer_index]}} - ✔ </span> @else <span>А) {{$answers[0 + $answer_index]}}</span> @endif
                                    <br>
                                    <input type="checkbox" name="closed_answers[]" value="{{$answers[1 + $answer_index]}}" @if($given_ca[$c_q] == $answers[1 + $answer_index]) checked @endif disabled> @if($answers[1 + $answer_index] == $right_answers[$c_q])<span class="checkTestRA">Б) {{$answers[1 + $answer_index]}} - ✔ </span> @else <span>Б) {{$answers[1 + $answer_index]}}</span> @endif
                                    <br>
                                    <input type="checkbox" name="closed_answers[]" value="{{$answers[2 + $answer_index]}}" @if($given_ca[$c_q] == $answers[2 + $answer_index]) checked @endif disabled> @if($answers[2 + $answer_index] == $right_answers[$c_q])<span class="checkTestRA">В) {{$answers[2 + $answer_index]}} - ✔ </span> @else <span>В) {{$answers[2 + $answer_index]}}</span> @endif
                                    <br>
                                    <input type="checkbox" name="closed_answers[]" value="{{$answers[3 + $answer_index]}}" @if($given_ca[$c_q] == $answers[3 + $answer_index]) checked @endif disabled> @if($answers[3 + $answer_index] == $right_answers[$c_q])<span class="checkTestRA">Г) {{$answers[3 + $answer_index]}} - ✔ </span> @else <span>Г) {{$answers[3 + $answer_index]}}</span> @endif
                                </label>
                                <?php $answer_index += 4;?>
                            @else
                                <?php $c_oa++;?>
                                <label>
                                    <span class="points">- @if($oa_points[$c_oa] != 1) {{$oa_points[$c_oa]}} точки @else {{$oa_points[$c_oa]}} точка @endif</span>
                                </label>
                                <br>
                                <label class="test_answers longerQuestions">
                                    <input type="text" name="open_answers[]" value="{{$given_oa[$c_oa]}}" disabled>
                                </label>
                            @endif
                        </li>
                    @endforeach

                    @if($testData->suggestions == null)

                    @else
                        <h4>Коментар от учителя: {{$testData->suggestions}}</h4>
                    @endif
                    <br>
                    @if($eval_grid[0] <= $final_points && $eval_grid[1] >= $final_points)
                        <p class="score"> Слаб(2) - {{$final_points}} точки </p>
                    @elseif($eval_grid[2] <= $final_points && $eval_grid[3] >= $final_points)
                        <p class="score"> Среден(3) - {{$final_points}} точки </p>
                    @elseif($eval_grid[4] <= $final_points && $eval_grid[5] >= $final_points)
                        <p class="score"> Добър(4) - {{$final_points}} точки </p>
                    @elseif($eval_grid[6] <= $final_points && $eval_grid[7] >= $final_points)
                        <p class="score"> Много добър(5) - {{$final_points}} точки </p>
                    @elseif($eval_grid[8] <= $final_points && $eval_grid[9] >= $final_points)
                        <p class="score"> Отличен(6) - {{$final_points}} точки </p>
                    @endif
                @endif
            </ol>
            {{ Form::close() }}
        @endif
    </div>
    <style>
        .score{
            display: inline-block;
            margin: .5em 0 0 1em;
            font-size: 1.2em;
            color: #333;
        }
        @media only screen and (max-width: 1024px) {
            #solveTest{
                margin-top: 15%;
            }
        }
    </style>
@endsection
