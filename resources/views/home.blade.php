@extends('layouts.app')

@section('content')
<div id="dashboard" class="container" style="margin-top: 8%;">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <h1 class="welcomingHeading">Добре дошли!</h1>

            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif
            {{ Form::open(array('url' => '/home/', 'method' => 'post', 'id' => 'createFolder'))}}

                <section>
                    @foreach($data as $row)
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
                <input type="hidden" name="folder_level" value="{{ null }}">
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

            <div class="paging" style="margin-bottom: 25px;">
                @if($currPage < 2)

                @else
                    <a href="https://si-st-code.com/home/{{ $currPage-1 }}"><span class="pageDown"></span></a>
                @endif

                <?php echo $paging ?>

                @if($allPages <= $currPage)

                @else
                    <a href="https://si-st-code.com/home/{{ $currPage+1 }}"><span class="pageUp"></span></a>
                @endif
            </div>

        </div>
    </div>
</div>
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
