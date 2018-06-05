@extends('layouts.app')
@section('content')
    <div class="container" style="margin-top: 6%;">
        <h1 class="off_heading" style="margin-bottom: 1em;">Кодове за достъп на <span>{{ $data->title }}</span></h1>

        <ol id="testAccessCodes">
            @for($i = 0; $i<count(json_decode($data->access_code)); $i++)
                <li>{{ json_decode($data->access_code)[$i] }}</li>
            @endfor

        </ol>
        <br>
        <section id="printSection">
            <button type="button" class="logButton_alike" onclick="window.print();">Принтирай</button>
        </section>
    </div>
    <style>
        #testAccessCodes{
            margin: 0 auto;
            text-align: center;
            width: 100%;
            padding: 0;
        }
        #testAccessCodes>li{
            font-size: 1.8em;
            font-weight: 600;
            text-shadow: .01em .01em .025em rgb(132, 206, 235);
            color: #111 !important;
            transition: all .5s;
            text-align: center;
            display: block;
            margin-top: .3em;
            list-style-type: none;
        }
        #testAccessCodes>li>span{
            padding: 0 .75em 0 .75em;
        }
        .off_heading>span{
            border-bottom: 1px solid #000;
            color: #000;
        }
        #printSection{
            width: auto;
            margin: 0 auto;
            text-align: center;
            display: block;
            padding-bottom: 3em;
        }
        #printSection > button{
            color: #222;
            height: 2em;
            width: auto;
            border-radius: 0.25em;
            margin-top: 1em;
            text-shadow: none;
        }
        @page

        #printSection > button {
            display: none;
        }
    </style>
@endsection
