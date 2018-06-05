@extends('layouts.app')
@section('content')
    <div class="container" style="margin-top: 6%;">
        <h1 class="off_heading">Моите анкети</h1>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default" style="padding: 0 2%;">

                    <ul class="grid">
                        <li class="grid-sizer"></li>
                        <li class="gutter-sizer"></li>
                        @foreach($data as $row)
                            <li class="eachpoll grid-item">
                                <form class="poll" action="{{url('/')}}" method="post">
                                    <div class="itemPool">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <section style="display: block;text-align: center;">
                                            <a class="poll_url" href="{{url('/poll/'.$row->slug)}}">/poll/{{$row->slug}}</a>
                                            <button class="copyPollLink" type="button" onclick="$(this).next().fadeIn(150)"><img src="{{ url('css/images/copy.png') }}"></button>
                                            <span onclick="$(this).fadeOut(150)">Линкът е копиран!</span>
                                        </section>
                                        <section class="front">
                                            <h2 class="question">{{ $row->question }}</h2>
                                            <?php $c = 0; ?>
                                            @foreach(json_decode($row->answers) as $row2)
                                                <?php $c++; ?>
                                                <label class="input-group-addon">
                                                    <div class="gradient">
                                                        <div><div></div></div>
                                                    </div>
                                                    <span>
                                                   <input type="radio" name="radiob" value="a{{ $c-1 }}" disabled/>
                                                </span>
                                                    <span class="after_radio" style="white-space: normal;">{{ $row2 }}</span>
                                                </label>
                                            @endforeach
                                            <div style="width: 100%;position: relative;margin-top: 15px;">
                                                @if($row->creator_id == Auth::id())

                                                @else
                                                    <button class="get_id" name="submit" type="button" onclick="submit_vote($(this).parent().parent().parent().parent() , <?php echo $row->id ?>)">Гласувай</button>
                                                @endif
                                                <button class="view_results" type="button" style="width: auto;" onclick="get_results($(this).parent().parent().parent() ,<?php echo $row->id ?>)">Виж резултатите</button>
                                                <button class="del_poll" style="width: auto;" type="button" onclick="del_poll(<?php echo $row->id?>)">Изтрий</button>
                                                <a href="{{ url('/poll/create/'.$row->id) }}" style="color: #555;">Редактирай</a>
                                            </div>
                                        </section>
                                    </div>
                                </form>
                            </li>
                        @endforeach
                    </ul>
                    <div class="paging" style="margin-bottom: 25px;">
                        @if($currPage < 2)

                        @else
                            <a href="{{ $currPage-1 }}"><span class="pageDown"></span></a>
                        @endif

                        <?php echo $paging ?>

                        @if($allPages <= $currPage)

                        @else
                            <a href="{{ $currPage+1 }}"><span class="pageUp"></span></a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
    <script src="https://unpkg.com/masonry-layout@4/dist/masonry.pkgd.min.js"></script>
    <script>
        $('.grid').masonry({
            itemSelector: '.grid-item',
            columnWidth: '.grid-sizer',
            gutter:'.gutter-sizer',
            fitWidth: true,
            percentPosition: true
        });
    </script>
    <style>
        .copyPollLink{
            border: none;
            outline: none;
            background: transparent;
            font-size: initial;
            transition: all .4s;
            border-radius: 50%;
        }
        .copyPollLink + span{
            position: absolute;
            right: 0;
            top: 2.7em;
            font-size: .8em;
            border-bottom: 1px solid;
            cursor: pointer;
            display: none;
        }
        .copyPollLink + span::before{
            border-color: transparent transparent #888 transparent;
            border-image: none;
            border-style: dashed;
            border-width: .5em;
            content: " ";
            display: block;
            right: 2.25em;
            top: -0.8em;
            position: absolute;
        }
        .copyPollLink:hover{
            background: #e0e0e0;
        }
        .poll .poll_url{
            font-size: 9px;
            text-decoration: none;
            color: #8860D0;
            text-align: center;
            display: inline-block;
        }
    </style>
@endsection
