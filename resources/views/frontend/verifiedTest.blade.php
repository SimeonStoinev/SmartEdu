@extends('layouts.app')
@section('content')
    <div class="container" style="margin-top: 6%;">
        {{ Form::open(array('url' => '/verified/test/'.$testData->id , 'method' => 'post', 'id' => 'verifyTest'))}}
        <h1 class="off_heading">{{$testData->title}}</h1>
        <label style="margin: 0 0 1em 3.8em;">
            <span>{{$testData->personal_data}} - {{$testData->grade}}{{$testData->sub_class}} клас - </span>
            <a onclick="$(this).fadeOut(0);$(this).next().fadeIn(550)" style="cursor: pointer;font-weight: normal;">Виж линк за проверка</a>
            <a style="display: none;color: rgb(90, 185, 234);">{{ url('/check/test/'.$testData->student_link) }}</a>
        </label>
        <ol id="task_keeper">
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
                    <br>
                    @if($question_type[$c] == 'closed')
                        <?php $c_q++;?>
                        <label class="test_answers">
                            А) <span> @if($given_ca[$c_q] == $answers[0 + $answer_index]) <span class="given_answer">{{$answers[0 + $answer_index]}}</span> @else {{$answers[0 + $answer_index]}} @endif </span>
                            <br>
                            Б) <span> @if($given_ca[$c_q] == $answers[1 + $answer_index]) <span class="given_answer">{{$answers[1 + $answer_index]}}</span> @else {{$answers[1 + $answer_index]}} @endif </span>
                            <br>
                            В) <span> @if($given_ca[$c_q] == $answers[2 + $answer_index]) <span class="given_answer">{{$answers[2 + $answer_index]}}</span> @else {{$answers[2 + $answer_index]}} @endif </span>
                            <br>
                            Г) <span> @if($given_ca[$c_q] == $answers[3 + $answer_index]) <span class="given_answer">{{$answers[3 + $answer_index]}}</span> @else {{$answers[3 + $answer_index]}} @endif </span>
                        </label>
                        <br>
                        <label>
                            <span>Верен отговор: {{$right_answers[$c_q]}} - </span>
                            <span> @if($given_ca[$c_q] == $right_answers[$c_q]) @if($points[$c] == 1) {{$points[$c]}} точка @else {{$points[$c]}} точки @endif @else 0 точки @endif</span>
                        </label>
                        <?php $answer_index += 4;?>
                    @else
                        <?php $c_oa++;?>
                        <label class="test_answers">
                            <span>{{$given_oa[$c_oa]}}</span>
                        </label>
                        <label style="margin-left: 1em;">
                            <input type="number" name="points[]" placeholder="Брой точки" min="0" max="{{$points[$c]}}" value="{{$oa_points[$c_oa]}}">
                        </label>
                    @endif
                </li>
            @endforeach
            <label>
                <textarea type="text" name="suggestions" placeholder="Напишете препоръка или коментар към ученика" maxlength="500" cols="50" rows="5" style="outline-color: rgb(132, 206, 235);margin: 1em 0 0 1em;">{{$testData->suggestions}}</textarea>
            </label>
            <br>
            <button class="verifyBut" type="submit">Обнови проверката</button>
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
            {{--<button type="button">Валидирай тест</button>--}}
        </ol>
        {{ Form::close() }}
    </div>
    <style>
        .score{
            display: inline-block;
            margin: .5em 0 0 .5em;
            font-size: 1.2em;
            color: #333;
        }
    </style>
@endsection