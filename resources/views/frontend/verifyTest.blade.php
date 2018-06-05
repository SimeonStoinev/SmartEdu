@extends('layouts.app')
@section('content')
    <div class="container" style="margin-top: 6%;">
        {{ Form::open(array('url' => '/verify/test/'.$testData->id , 'method' => 'post', 'id' => 'verifyTest'))}}
        <h1 class="off_heading">{{$testData->title}}</h1>
        <label style="margin: 0 0 1em 3.8em;">
            <span>{{$testData->personal_data}} - {{$testData->grade}}{{$testData->sub_class}} клас</span>
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
                            <input type="number" name="points[]" placeholder="Брой точки" min="0" max="{{$points[$c]}}" oninvalid="invalidInput($(this))" required>
                        </label>
                    @endif
                </li>
            @endforeach
            <label>
                <textarea type="text" name="suggestions" placeholder="Напишете препоръка или коментар към ученика" maxlength="500" cols="50" rows="5" style="outline-color: rgb(132, 206, 235);margin: 1em 0 0 1em;"></textarea>
            </label>
            <br>
            <button class="verifyBut" type="submit">Завърши проверката</button>
            {{--<button type="button">Валидирай тест</button>--}}
        </ol>
        {{ Form::close() }}
    </div>
@endsection