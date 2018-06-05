@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="table-responsive row">
            <p class="studentLink">Вие можете да видите вашия тест на адрес
                <br><a href="{{ url('/check/test/'.$link) }}">{{ url('/check/test/'.$link )}}</a>,
                <br>след като бъде проверен.</p>
        </div>
    </div>
@endsection