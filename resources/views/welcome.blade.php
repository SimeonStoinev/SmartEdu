@extends('layouts.app')
@section('content')
        <div class="flex-center position-ref full-height">
            <div class="content">
                {{ Form::open(array('url' => '/', 'method' => 'post'))}}
                    <label>
                        <input type="text" maxlength="6" name="access_code" placeholder="Код за достъп">
                    </label>
                    <button type="submit">Започни тест</button>
                {{ Form::close() }}
            </div>
        </div>
@endsection
