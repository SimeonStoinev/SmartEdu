@extends('layouts.app')

@section('content')
    <div class="container">
        <?php $id_item =''; if($pollData != null) { $id_item = $pollData->id; } ?>
        {{ Form::open(array('url' => '/poll/create/'.$id_item , 'method' => 'post', 'id' => 'createPoll_form'))}}

            <h2 class="off_heading">Създай анкета</h2>

            @if($pollData != null)
                <div class="row">
                    <label class="col-md-11" style="padding: 0;">
                        <input class="col-md-12" type="text" name="question" placeholder="Въпрос" value="{{ $pollData->question }}" pattern=".{6,}" title="Въпросът трябва да съдържа 6 или повече символа." required/>
                    </label>
                </div>
                <ol id="answers">
                    @foreach(json_decode($pollData->answers) as $row)
                        <li class="col-md-12 keeper sortable">
                            <label class="col-md-11 create_answers">
                                <input class="col-md-12" type="text" name="answers[]" placeholder="Отговор 1" value="{{ $row }}" oninvalid="invalidInput($(this))" required/>
                            </label>
                            <button class="delPoll" type="button" onclick="deleteAnswer($(this))">X</button>
                        </li>
                    @endforeach
                </ol>
            @else
                <div class="row">
                    <label class="col-md-11" style="padding: 0;">
                        <input class="col-md-12" type="text" name="question" placeholder="Въпрос" pattern=".{6,}" title="Въпросът трябва да съдържа 6 или повече символа." oninvalid="invalidInput($(this))" required/>
                    </label>
                </div>
                <ol id="answers">
                    <li class="col-md-12 keeper sortable">
                        <label class="col-md-11 create_answers">
                            <input class="col-md-12" type="text" name="answers[]" placeholder="Отговор 1" oninvalid="invalidInput($(this))" required/>
                        </label>
                        <button class="delPoll" type="button">X</button>
                    </li>

                    <li class="col-md-12 keeper sortable">
                        <label class="col-md-11 create_answers">
                            <input class="col-md-12" type="text" name="answers[]" placeholder="Отговор 2" oninvalid="invalidInput($(this))" required/>
                        </label>
                        <button class="delPoll" type="button">X</button>
                    </li>
                </ol>
            @endif

            <br/><br/>

            @if($pollData == null)
                <button class="core-btn" type="submit">Създай</button>
            @else
                <button class="core-btn" type="submit">Съхрани</button>
            @endif
            <button class="core-btn" type="reset">Изчисти</button>
            <select name="folder" id="selectFolder">
                @if(isset($pollData->folder_id))
                    <option value="{{$pollData->folder_id}}" selected>{{$selectedFolder->title}}</option>
                @else
                    <option value="" selected>Без папка</option>
                @endif

                <?php $c = 0;?>
                @foreach($folders as $row)
                    @if(isset($pollData->folder_id))
                        @if($selectedFolder->id != $row->id)
                            <option value="{{$row->id}}">{{ $folderHierarchy[$c] == null ? '' : $folderHierarchy[$c].'/' }}{{$row->title}}</option>
                        @endif
                    @else
                        <option value="{{$row->id}}">{{ $folderHierarchy[$c] == null ? '' : $folderHierarchy[$c].'/' }}{{$row->title}}</option>
                    @endif
                    <?php $c++;?>
                @endforeach
            </select>
            <button class="glyphicon glyphicon-plus" type="button"></button>

            {{ Form::close() }}
    </div>
    <style>
        #answers{
            list-style-type: none;
            padding: 0;
            margin: 0;
        }
        .keeper:last-of-type{
            margin-bottom: 1em;
        }
        .delPoll {
            display: none;
        }
        .showMinus .delPoll{
            display: inline-block;
        }
        .create_answers{
            padding-left: 0;
            padding-right: .5em;
            box-sizing: content-box;
            width: 88.8%;
        }
        .keeper{
            padding: 0;
        }
        .core-btn, .core-btn + select{
            margin-right: .25em !important;
        }
    </style>
@endsection