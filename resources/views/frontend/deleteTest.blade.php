@extends('layouts.app')
@section('content')
    <div class="container" style="margin-top: 7%;">
        {{ Form::open(array('url' => '/delete/test/'.$testData->id , 'method' => 'post'))}}

            <p style="text-align: center;">Сигурни ли сте, че искате да изтриете вашия тест?</p>
            <div style="box-sizing: border-box;padding-left: 44.5%;">
                <button class="btn btn-danger" type="submit" style="outline: none;">Да!</button>
                <a href="{{ url('/test/create/'.$testData->id) }}">
                    <button class="btn btn-primary" type="button" style="outline: none;">Не!</button>
                </a>
            </div>
        {{ Form::close() }}
    </div>
@endsection
