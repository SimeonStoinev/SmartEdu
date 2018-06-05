@extends('layouts.app')
@section('content')
    <div class="container" style="margin-top: 7%;">
        <h3 style="text-align: center;">@if($folderData->folder_level == null)<a href="/home">Начало</a> @else<a href="/home/folder/{{$folderData->folder_level}}">{{$folderBack->title}}</a> @endif / <span style="border-bottom: 1px solid;">{{$folderData->title}}</span></h3>
        <br>
        @if(get_object_vars($folderPollsContent[0])['slug'] != null)
            <p class="folderPolls">Анкети:
                @foreach($folderPollsContent as $row)
                    <a href="{{'/poll/'.$row->slug}}"> {{$row->question}}</a>
                    <br>
                @endforeach
            </p>
        @endif
        @if(get_object_vars($folderTestsContent[0])['title'] != null)
            <p class="folderTests">Тестове:
                @foreach($folderTestsContent as $row)
                    <a href="{{'/test/create/'.$row->id}}"> {{$row->title}} - @if($row->status == 'sketch')Скица@elseif($row->status == 'active')Активен@endif</a>
                    <br>
                @endforeach
            </p>
        @endif
        @if(get_object_vars($folderPollsContent[0])['slug'] == null && get_object_vars($folderTestsContent[0])['title'] == null && get_object_vars($folders) == []) <p style="text-align: center;font-size: 18px;">Тази папка не съдържа тестове или анкети.</p><br> @endif

        <?php $id_item =''; if(isset($id)) { $id_item = $id; } ?>
        {{ Form::open(array('url' => '/home/'.$id_item, 'method' => 'post', 'id' => 'createFolder'))}}
            <section>
                @foreach($folders as $row)
                    <section class="folderWrap">
                        <section class="renameFolder">
                            <label>
                                <input type="text" name="newFoldTitle" value="{{$row->title}}" disabled>
                                <input type="hidden" value="{{$row->id}}" name="folderId" disabled>
                            </label>
                            <button class="submitRename" type="submit" style="margin: 0">✔</button>
                            <button class="cancelRename" type="button" onclick="closeFolderRename($(this))">X</button>
                        </section>
                        <a class="folders" href="{{'/home/folder/'.$row->id}}"> {{$row->title}} </a>
                        <button class="renameFoldBtn" type="button" onclick="renameFolder($(this))"><img src="{{ url('css/images/pen.png') }}"></button>
                        <section>
                            <button class="delFolder" type="button" onclick="delete_folder({{$row->id}})">X</button>
                            <a href="{{'/home/folder/'.$row->id}}"><img src="{{ url('css/images/flat_folder128.png') }}"></a>
                        </section>
                    </section>
                @endforeach
            </section>

            <input type="hidden" name="folder_level" value="{{$id_item}}">
            <input class="currentUrl" type="hidden" name="currentUrl">
            <div class="addFold_info">Добави папка:</div>
            <button id="addFolder" class="glyphicon glyphicon-plus" type="button" onclick="create_folder($(this))"></button>
            <p class="showCreateFold">
                <label>
                    <input type="text" name="title" placeholder="Заглавие на папката" style="outline-color: rgb(132, 206, 235) !important;" required disabled>
                </label>
                <button id="createFold">Създай</button>
                <button class="cancelRename" type="button" onclick="closeCreateFolder($(this))">X</button>
            </p>
        {{ Form::close() }}
    </div>
    <script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
    <style>
        .showCreateFold{
            display: none;
            text-align: center;
            margin-top: 1em;
        }
        .showCreateFold > #createFold{
            border-radius: .35em;
            outline: none !important;
            transition: all .35s;
            background-color: rgb(132, 206, 235);
            color: #fff;
            text-shadow: 1px 1px 1px rgba(0,0,0,.5);
            border: none !important;
            height: 2em;
        }
        .showCreateFold > #createFold:hover{
            background-color: rgb(117, 188, 216);
        }
        .showCreateFold > #createFold:focus, .showCreateFold > #createFold:active{
            background-color: rgb(117, 188, 216);
        }
        h3>a{
            font-size: .8em;
            text-decoration: none !important;
            color: #222222;
            transition: all .3s;
            outline: none;
        }
        h3>a:hover, h3>a:focus{
            color: #444;
        }
        .cancelRename{
            background: #ff9696;
            border-radius: 50%;
            border: 1px solid #ebebeb;
            outline: none;
            color: #000;
            font-weight: bold;
            vertical-align: middle;
            transition: all .3s;
            width: 2em;
            height: 2em;
        }
        .cancelRename{
            height: auto;
            border: 1px transparent;
            width: 1.6em;
        }
        .cancelRename:hover{
            background: #ff6d6d;
        }
        .renameFoldBtn:hover{
            background: #e0e0e0;
        }
        .renameFoldBtn{
            border: none;
            outline: none;
            background: transparent;
            font-size: initial;
            transition: all .4s;
            position: absolute;
            top: -.25em;
        }
        .folderWrap{
            width: 11em;
            position: relative;
            display: inline-block;
            margin: .5em .9em 0 0.9em;
            text-align: center;
            transition: all .5s;
        }
        .folders{
            display: inline-block;
            text-decoration: none;
            color: #555;
            width: 85%;
            height: auto;
            box-sizing: initial;
        }
        .delFolder{
            position: absolute;
            right: 0.65em;
            margin-top: 1em;
        }
        .delFolder:hover{
            background: #ff6d6d;
            border: 1px solid #333;
        }
        .delFolder:active{
            background: #fc5050;
            border: 1px solid #000;
        }
        .renameFolder{
            display: none;
        }
        .submitRename{
            border: none;
            outline: none;
            background: #8eff8e;
            border-radius: 50%;
            color: #555;
            margin: 0 .3em .1em .3em;
            vertical-align: bottom;
        }
        .submitRename:hover{
            color: #000;
            background: #44ce44;
        }
    </style>
@endsection