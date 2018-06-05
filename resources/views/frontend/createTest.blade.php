@extends('layouts.app')

@section('content')
    <div class="container">
        <?php $id_item =''; if(isset($testData->id)) { $id_item = $testData->id; } ?>
        {{ Form::open(array('url' => '/test/create/'.$id_item , 'method' => 'post', 'id' => 'createTest_form', 'enctype' => 'multipart/form-data'))}}
            <h1 class="off_heading">Създай тест</h1>

            @if(!empty($accessToken))
                <input type="hidden" name="securityToken" value="{{ $accessToken }}">
            @endif
            @if(Session::has('failure'))
                <p class="errorMsg" style="text-align: center;color: #ff0000;">{{ Session::get('failure') }}</p>
            @endif

            @if(Session::has('accessTokenMissing'))
                <p class="errorMsg" style="text-align: center;color: #ff0000;">{{ Session::get('accessTokenMissing') }}</p>
            @endif

            @if($status != null && $status == 'active')

                <div class="row">
                    <h3 style="margin-left: 1.1em;">{{$testData->title}}</h3>
                </div>

                <ol id="task_keeper">
                    @if( isset($testData->title) )
                        <?php $c=-1;?>
                        <?php $answer_index = 0;?>
                        <?php $ra_index = 0;?>
                        @foreach(json_decode($testData->questions) as $row)
                            <?php $c++;?>
                            <li class="col-md-12 task">
                                <label>
                                    <h4>{{$questions[$c]}} - @if($points[$c] != 1) {{$points[$c]}} точки @else {{$points[$c]}} точка @endif</h4>
                                </label>
                                @if($question_type[$c] == 'closed')
                                    <br>
                                    <label class="test_answers">
                                        <span class="closed_answers">А) {{$answers[0 + $answer_index]}}</span>
                                        <br>
                                        <span class="closed_answers">Б) {{$answers[1 + $answer_index]}}</span>
                                        <br>
                                        <span class="closed_answers">В) {{$answers[2 + $answer_index]}}</span>
                                        <br>
                                        <span class="closed_answers">Г) {{$answers[3 + $answer_index]}}</span>
                                    </label>
                                    <?php $answer_index += 4;?>
                                    <?php $ra_index += 1;?>
                                @endif
                            </li>
                        @endforeach
                    @endif
                </ol>

                <br/><br/>

                <div>
                    <a href="{{ url('/delete/test/'.$testData->id) }}"><button class="delete_button" type="button">Изтрий</button></a>
                    <button class="closeButton" type="button" onclick="closeTest(<?php echo $testData->id;?>)">Затвори Тест</button>
                    <span id="activatedInfo">Вашият тест е активиран и готов за решаване. <br> Вие можете да го принтирате или да видите как ще изглежда от <a class="accessCode" href="{{ url('/view/test/'.$testData->slug) }}">тук</a> и неговите кодове за достъп от <a class="accessCode" href="{{ url('/view/test/codes/'.$testData->slug) }}">тук</a>.</span>
                </div>

            @else

                <div class="row">
                    <label class="longerQuestions">
                        <input class="col-md-12" type="text" name="title" placeholder="Заглавие" @if(isset($testData->title)) value="{{$testData->title}}" @endif  oninvalid="invalidInput($(this))" required="required"/>
                    </label>

                    <img src="{{url('css/images/info.png')}}" id="trigger_info" style="position: absolute;right: 2.5em;">
                    <ul id="createTestInfo">
                        <li>За да започнете създаването на тест, трябва да зададете неговото име в полето „Заглавие“.</li>
                        <li>В долната част на екрана се намира лентата с командите.</li>
                        <li>Бутона „Създай“ се използва когато изработвате тест за първи път.</li>
                        <li>Бутона „Съхрани“ се използва когато работите по вече създаден тест и искате да запазите промените.</li>
                        <li>При клик върху бутона „Добави затворен въпрос“, се появява шаблон на затворен въпрос, където трябва да напишето условието на задачата, да попълните четирите възможности за отговор, да изберете само един верен от тях и да зададете броя точки, които ученикът ще получи при верен отговор.</li>
                        <li>При клик върху бутона „Добави отворен въпрос“ трябва единствено да зададете условието на задачата и максималния брой точки за този въпрос. Този тип въпроси не се проверяват от системата, а от учителя след като теста е предаден.</li>
                        <li>Всяка задача може да бъде размествана, докато теста не е активиран, чрез клик на нейния номер и влачене до желаната позиция.</li>
                        <li>От падащото меню можете да изберете в коя от вашите папки да съхраните теста.</li>
                        <li>В полето „Брой ученици“ трябва да въведете броя ученици, които ще решават теста, за да не може един ученик да решава даден тест два пъти.</li>
                        <li>За да е възможно решаването на даден тест, той трябва да бъде активиран от бутона "Активирай", който се появява след като теста е създаден.</li>
                    </ul>
                </div>

                <ol id="task_keeper">

                    <li class="col-md-12 task closedTask">
                        <button type="button" class="handle"></button>
                        <label class="longerQuestions">
                            <input type="text" name="questions[]" placeholder="Задайте въпрос" oninvalid="invalidInput($(this))" required disabled>
                            <input type="hidden" name="question_type[]" value="closed" disabled>
                        </label>
                        <button class="del_task" type="button" onclick="delete_task($(this))">X</button>
                        <br>
                        <label class="test_answers">
                            <input class="closed_answers" type="text" name="answers[]" placeholder="А) Отговор 1" onchange="changeInputText($(this))" oninvalid="invalidInput($(this))" required disabled>
                            <br>
                            <input class="closed_answers" type="text" name="answers[]" placeholder="Б) Отговор 2" onchange="changeInputText($(this))" oninvalid="invalidInput($(this))" required disabled>
                            <br>
                            <input class="closed_answers" type="text" name="answers[]" placeholder="В) Отговор 3" onchange="changeInputText($(this))" oninvalid="invalidInput($(this))" required disabled>
                            <br>
                            <input class="closed_answers" type="text" name="answers[]" placeholder="Г) Отговор 4" onchange="changeInputText($(this))" oninvalid="invalidInput($(this))" required disabled>
                            <br>
                            <span>Верен отговор:</span>
                            <select name="right_answers[]" class="select_ra" oninvalid="invalidInput($(this))" required disabled>
                                <option value="" disabled>А) Отговор 1</option>
                                <option value="" disabled>Б) Отговор 2</option>
                                <option value="" disabled>В) Отговор 3</option>
                                <option value="" disabled>Г) Отговор 4</option>
                            </select>
                            <input type="number" min="1" max="100" name="points[]" onchange="changeMaxScorePerTask()" placeholder="Брой точки" required disabled>
                            <label style="margin: .8em 0 0 1em;">
                                <button style="font-weight: normal; height: 2.2em; color: #111111;" type="button" onclick="$(this).next().click();">Избери снимка</button>
                                <input style="display: none;" type="file" name="images[]" accept="image/*" onchange="getImageName($(this))" disabled>
                                <span></span>
                                <input type="hidden" name="blankImages[]" value="{{ null }}" disabled>
                            </label>
                        </label>
                    </li>

                    <li class="col-md-12 task openTask">
                        <button type="button" class="handle"></button>
                        <label class="longerQuestions">
                            <input type="text" name="questions[]" placeholder="Задайте въпрос" oninvalid="invalidInput($(this))" required disabled>
                            <input type="hidden" name="question_type[]" value="open" disabled>
                            <input type="hidden" name="answers[]" disabled>
                            <input type="hidden" name="right_answers[]" disabled>
                        </label>
                        <button class="del_task" type="button" onclick="delete_task($(this))">X</button>
                        <br>
                        <label>
                            <input type="number" min="1" max="100" name="points[]" onchange="changeMaxScorePerTask()" placeholder="Брой точки" required disabled>
                        </label>
                        <label style="margin: .8em 0 0 1em;">
                            <button style="font-weight: normal; height: 2.2em; color: #111111;" type="button" onclick="$(this).next().click();">Избери снимка</button>
                            <input style="display: none;" type="file" name="images[]" accept="image/*" onchange="getImageName($(this))" disabled>
                            <span></span>
                            <input type="hidden" name="blankImages[]" value="{{ null }}" disabled>
                        </label>
                    </li>

                    @if( isset($testData->title) )
                        <?php $c=-1;?>
                        <?php $answer_index = 0;?>
                        <?php $ra_index = 0;?>
                        <?php $trueAnswer = ["А)", "Б)", "В)", "Г)"];?>
                        @foreach(json_decode($testData->questions) as $row)
                            <?php $c++;?>
                            <li class="col-md-12 task">
                                <button type="button" class="handle"></button>
                                <label class="longerQuestions">
                                    <input type="text" name="questions[]" placeholder="Задайте въпрос" value="{{$questions[$c]}}" oninvalid="invalidInput($(this))" required>
                                    <input type="hidden" name="question_type[]" value="{{$question_type[$c]}}">
                                    @if($question_type[$c] == 'open')
                                        <input class="removeDisabled" type="hidden" name="answers[]">
                                        <input class="removeDisabled" type="hidden" name="right_answers[]">
                                    @endif
                                </label>
                                <button class="del_task" type="button" onclick="delete_task($(this))">X</button>
                                @if($question_type[$c] == 'closed')
                                    <br>
                                    <label class="test_answers">
                                        <input class="closed_answers" type="text" name="answers[]" placeholder="А) Отговор 1" value="{{$answers[0 + $answer_index]}}" oninvalid="invalidInput($(this))" onchange="changeInputText($(this))" required>
                                        <br>
                                        <input class="closed_answers" type="text" name="answers[]" placeholder="Б) Отговор 2" value="{{$answers[1 + $answer_index]}}" oninvalid="invalidInput($(this))" onchange="changeInputText($(this))" required>
                                        <br>
                                        <input class="closed_answers" type="text" name="answers[]" placeholder="В) Отговор 3" value="{{$answers[2 + $answer_index]}}" oninvalid="invalidInput($(this))" onchange="changeInputText($(this))" required>
                                        <br>
                                        <input class="closed_answers" type="text" name="answers[]" placeholder="Г) Отговор 4" value="{{$answers[3 + $answer_index]}}" oninvalid="invalidInput($(this))" onchange="changeInputText($(this))" required>
                                        <br>
                                        <span>Верен отговор:</span>
                                        <select name="right_answers[]" class="select_ra" oninvalid="invalidInput($(this))" required>
                                            <option value="{{ $answers[0 + $answer_index] }}" @if($right_answers[$ra_index] == $answers[0 + $answer_index]) selected @endif>А) Отговор 1</option>
                                            <option value="{{ $answers[1 + $answer_index] }}" @if($right_answers[$ra_index] == $answers[1 + $answer_index]) selected @endif>Б) Отговор 2</option>
                                            <option value="{{ $answers[2 + $answer_index] }}" @if($right_answers[$ra_index] == $answers[2 + $answer_index]) selected @endif>В) Отговор 3</option>
                                            <option value="{{ $answers[3 + $answer_index] }}" @if($right_answers[$ra_index] == $answers[3 + $answer_index]) selected @endif>Г) Отговор 4</option>
                                        </select>
                                        <input type="number" min="1" max="100" name="points[]" onchange="changeMaxScorePerTask()" placeholder="Брой точки" value="{{$points[$c]}}" required>
                                        <label style="margin: .8em 0 0 1em;">
                                            <button style="font-weight: normal; height: 2.2em; color: #111111;" type="button" onclick="$(this).next().click();">Избери снимка</button>
                                            <input style="display: none;" type="file" name="images[]" accept="image/*" onchange="getImageName($(this))">
                                            <span>{{ $images[$c] }}</span>
                                            <input type="hidden" name="blankImages[]" value="{{ $images[$c] }}">
                                        </label>
                                    </label>
                                    <?php $answer_index += 4;?>
                                    <?php $ra_index += 1;?>
                                @endif
                                @if($question_type[$c] == 'open')
                                    <br>
                                    <label>
                                        <input type="number" min="1" max="100" name="points[]" onchange="changeMaxScorePerTask()" placeholder="Брой точки" value="{{$points[$c]}}" required>
                                    </label>
                                    <label style="margin: .8em 0 0 1em;">
                                        <button style="font-weight: normal; height: 2.2em; color: #111111;" type="button" onclick="$(this).next().click();">Избери снимка</button>
                                        <input style="display: none;" type="file" name="images[]" accept="image/*" onchange="getImageName($(this))">
                                        <span>{{ $images[$c] }}</span>
                                        <input type="hidden" name="blankImages[]" value="{{ $images[$c] }}">
                                    </label>
                                @endif
                            </li>
                        @endforeach
                    @endif
                </ol>

                <br/><br/>
                @if($status == null || $status == 'closed')
                    <button class="core-btn" type="submit">Създай</button>
                @else
                    <button class="core-btn" type="submit">Съхрани</button>
                @endif

                <button class="core-btn" type="button" onclick="$(this).siblings('.addBtnsWrap').fadeIn(400);">Добави въпрос</button>
                @if($status != null)
                    @if($status == 'sketch')
                        <button class="activateButton" type="button" onclick="activateTest(<?php echo $testData->id;?>)">Активирай</button>
                    @endif
                    <a href="{{ url('/delete/test/'.$testData->id) }}"><button class="delete_button" type="button" style="height: 2.3em;">Изтрий</button></a>
                @endif

                <select name="folder" id="selectFolder">
                    @if(isset($testData->folder_id))
                        <option value="{{$testData->folder_id}}" selected>{{$selectedFolder->title}}</option>
                    @else
                        <option value="" selected>Без папка</option>
                    @endif

                    <?php $c = 0;?>
                    @foreach($folders as $row)
                        @if(isset($testData->folder_id))
                            @if($selectedFolder->id != $row->id)
                                <option value="{{$row->id}}">{{ $folderHierarchy[$c] == null ? '' : $folderHierarchy[$c].'/' }}{{$row->title}}</option>
                            @endif
                        @else
                            <option value="{{$row->id}}">{{ $folderHierarchy[$c] == null ? '' : $folderHierarchy[$c].'/' }}{{$row->title}}</option>
                        @endif
                        <?php $c++;?>
                    @endforeach
                </select>
                @if( isset($testData) && count($testData) > 0 )
                    <section style="position: relative; display: inline-block;">
                        <label>
                            <input id="students_number" type="number" min="1" max="100" name="students_number" placeholder="Брой ученици" @if(isset($testData->students) && $testData->students != 0) value="{{$testData->students}}" @endif>
                        </label>
                            <label>
                            <input id="grade" type="number" min="1" max="12" name="grade" placeholder="Клас" @if(isset($testData->grade)) value="{{$testData->grade}}" @endif style="width: 5em;">
                        </label>
                        <label>
                            <input id="sub_class" type="text" max="1" name="sub_class" placeholder="Паралелка" @if(isset($testData->sub_class)) value="{{$testData->sub_class}}" @endif style="width: 7em;">
                        </label>
                        <label id="evaluationGrid">
                            <input type="text" placeholder="Слаб(2)" disabled>&nbsp;<input class="evalGrid" type="number" name="eval_grid[]" placeholder="От:" @if(isset($eval_grid)) value="{{ $eval_grid[0] }}" @endif min="0"> - <input class="evalGrid" type="number" name="eval_grid[]" placeholder="До:" @if(isset($eval_grid)) value="{{ $eval_grid[1] }}" @endif min="0">
                            <br>
                            <input type="text" placeholder="Среден(3)" disabled>&nbsp;<input class="evalGrid" type="number" name="eval_grid[]" placeholder="От:" @if(isset($eval_grid)) value="{{ $eval_grid[2] }}" @endif min="0"> - <input class="evalGrid" type="number" name="eval_grid[]" placeholder="До:" @if(isset($eval_grid)) value="{{ $eval_grid[3] }}" @endif min="0">
                            <br>
                            <input type="text" placeholder="Добър(4)" disabled>&nbsp;<input class="evalGrid" type="number" name="eval_grid[]" placeholder="От:" @if(isset($eval_grid)) value="{{ $eval_grid[4] }}" @endif min="0" > - <input class="evalGrid" type="number" name="eval_grid[]" placeholder="До:" @if(isset($eval_grid)) value="{{ $eval_grid[5] }}" @endif min="0">
                            <br>
                            <input type="text" placeholder="Много добър(5)" disabled>&nbsp;<input class="evalGrid" type="number" name="eval_grid[]" placeholder="От:" @if(isset($eval_grid)) value="{{ $eval_grid[6] }}" @endif min="0"> - <input class="evalGrid" type="number" name="eval_grid[]" placeholder="До:" @if(isset($eval_grid)) value="{{ $eval_grid[7] }}" @endif min="0">
                            <br>
                            <input type="text" placeholder="Отличен(6)" disabled>&nbsp;<input class="evalGrid" type="number" name="eval_grid[]" placeholder="От:" @if(isset($eval_grid)) value="{{ $eval_grid[8] }}" @endif min="0"> - <input class="evalGrid" type="number" name="eval_grid[]" placeholder="До:" @if(isset($eval_grid)) value="{{ $eval_grid[9] }}" @endif min="0" max="{{array_sum($points)}}">
                        </label>
                    </section>
                @endif
                <section class="addBtnsWrap">
                    <button class="core-btn addTaskBtn" type="button" onclick="add_closedTask($(this).parent())">Добави затворен въпрос</button>
                    <button class="core-btn addTaskBtn" type="button" onclick="add_openTask($(this).parent())">Добави отворен въпрос</button>
                </section>
                <br><br><br>
            @endif

        {{ Form::close() }}
    </div>
    <style>
        .openTask, .closedTask{
            display: none;
        }
        .handle{
            float: left;
            position: absolute;
            border-radius: 50%;
            margin: .2em 0.3em 0 -2.75em;
            height: 2em;
            width: 2em;
            background: transparent;
            outline: none;
            border: 1px solid #999;
        }
        .dragged{
            position: absolute;
            opacity: .8;
            -webkit-transform: scale(1.05);
            -moz-transform: scale(1.05);
            -ms-transform: scale(1.05);
            -o-transform: scale(1.05);
            transform: scale(1.05);
        }
        #createTestInfo{
            z-index: 8;
            width: 60em;
            height: auto;
            padding: 12px;
            display: none;
            opacity: 0;
            position: absolute;
            right: 2.5em;
            top: 13.4em;
            border-radius: 5px;
            font-size: 14px;
            border: 1px solid;
            font-weight: 600;
            background-color: #f2f2f2;
            transition: all .6s;
            color: #111;
            list-style-type: none;
        }
        #createTestInfo::before{
            border-color: transparent transparent #333 transparent;
            border-image: none;
            border-style: solid;
            border-width: 10px;
            content: " ";
            display: block;
            right: .3em;
            top: -1.5em;
            position: absolute;
        }
        #trigger_info:hover + #createTestInfo{
            display: block;
            opacity: 1;
        }
        .closed_answers{
            width: 50em;
        }
        .displayTrueAns{
            margin: .5em 1em 0 .5em;
        }
        .addBtnsWrap{
            display: none;
        }
        #evaluationGrid{
            position: absolute;
            margin: .5em 0 3em 0;
        }
        #evaluationGrid > input[disabled]{
            width: 9em;
        }
        #evaluationGrid > input[type="number"]{
            width: 4em;
        }
        .accessCode{
            text-decoration: none !important;
            font-weight: 600;
            text-shadow: .025em .025em .05em rgb(132, 206, 235);
            color: #111 !important;
            border-bottom: 1px solid transparent;
            transition: all .5s;
        }
        .accessCode:hover{
            border-bottom: 1px solid #111;
        }
        .core-btn, .core-btn ~ button, .core-btn ~ select, .core-btn ~ a, #selectFolder{
            margin: 1em .3em 1em 0 !important;
        }
        #students_number, #grade, #sub_class{
            margin: 1em .3em 0 0;
        }
        .select_ra{
            outline: none;
            border: none;
            height: 2.15em;
            border-bottom: 1px dashed #777;
            background: transparent;
            margin: 1em .1em 1em 0;
        }
        .closeButton{
            background: #98a888;
            outline: none;
            border: 1px solid #ebebeb;
            color: #000;
            border-radius: 3px;
            height: 2.5em;
            transition: all .4s;
            margin-left: .25em;
        }
        .closeButton:hover{
            background: #7e8c71;
            border: 1px solid #999;
        }
    </style>
@endsection