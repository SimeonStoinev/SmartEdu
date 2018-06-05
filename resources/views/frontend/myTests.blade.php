@extends('layouts.app')
@section('content')
    <div class="container" style="margin-top: 6%;">
        <h1 class="off_heading">Моите тестове</h1>
        <section>
            <input type="text" placeholder="Търси..." style="outline-color: rgb(132, 206, 235);"/>
            <button class="logButton_alike" type="button" onclick="searchTest($(this).prev())">Търси</button>
        </section>
        <ul class="listing">
            @foreach($myTests as $row)
                <li>
                    <a href="{{ url('/test/create/'.$row->id) }}">{{$row->title}}</a> - @if($row->status == 'sketch')Скица@elseif($row->status == 'active')Активен@elseЗатворен@endif
                    <?php $tmp = explode(' ', $row->created_at);
                        $tmpDate = explode('-', $tmp[0]);
                        $tmpHour = explode(':', $tmp[1]); ?>
                    - Създаден на {{$tmpDate[2]}}.{{$tmpDate[1]}} в {{$tmpHour[0] + 2}}:{{$tmpHour[1]}} часа.
                    @if($row->folder_id == null)

                    @else
                        <span>- Добавен в папка: <a style="color: #111111;" href="{{ url('/home/folder/'.$row->folder_id) }}">{{ $row->folderTitles }}</a></span>
                    @endif
                </li>
            @endforeach
        </ul>
    </div>
    <style>
        .off_heading + section{
            text-align: center;
            margin: .3em 0 .8em 0;
        }
        .off_heading + section > button{
            height: 2em;
            width: 4.4em;
            border-radius: .2em;
            font-weight: 600;
        }
    </style>
@endsection
