@extends('layouts.app')
@section('content')
    <div class="container">
        <ul class="single_page_poll">
            <li class="eachpoll grid-item">
                <form class="poll" action="{{url('/poll/')}}" method="post">
                    <div class="itemPool">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                        <section class="front">
                            <h2 class="question">{{ $pollData->question }}</h2>
                            <?php $c = 0; ?>
                            @foreach(json_decode($pollData->answers) as $row)
                                <?php $c++; ?>
                                <label class="input-group-addon">
                                    <div class="gradient">
                                        <div><div></div></div>
                                    </div>
                                    <span>
                                       <input type="radio" name="radiob" value="a{{ $c-1 }}"/>
                                    </span>
                                    <span class="after_radio" style="white-space: normal;">{{ $row }}</span>
                                </label>
                            @endforeach
                            @if($pollData->creator_id == $auth_id)

                            @else
                                <button style="margin-top: 15px;" class="get_id" name="submit" type="button" onclick="submit_vote($(this).parent().parent().parent() , <?php echo $pollData->id ?>)">Гласувай</button>
                            @endif
                            <button style="margin-top: 15px; width: auto;" class="view_results" type="button" onclick="get_results($(this).parent().parent().parent() , <?php echo $pollData->id ?>)">Виж резултатите</button>
                        </section>

                        <section class="back">

                        </section>
                    </div>
                </form>
            </li>
        </ul>
    </div>
    <script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
    <script src="https://cdn.rawgit.com/nnattawat/flip/master/dist/jquery.flip.min.js"></script>
    <style>
        .single_page_poll{
            margin-top: 10%;
        }
        .eachpoll{
            transform: translateX(-50%) translateY(-50%);
            top: 50%;
            left: 50%;
            position: absolute;
        }
        @media only screen and (max-width: 1024px) {
            .eachpoll{
                margin-top: 5%;
            }
        }

    </style>
@endsection