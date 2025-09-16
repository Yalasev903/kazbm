@extends('layouts.app')
@section('page_title', 'Страница 404')
@section('content')
    <main class="errorPage">
        <div id="left"><img src="{{ asset('images/icons/leftError.svg') }}" alt="left-error image"></div>
        <div id="right"><img src="{{ asset('images/icons/rightError.svg') }}" alt="right-error image"></div>
        <div class="mobile_pic1"><img src="{{ asset('images/icons/error_1.svg') }}" alt="top-error image"></div>
        <div class="mobile_pic2"><img src="{{ asset('images/icons/error_2.svg') }}" alt="bottom-error image"></div>
        <div class="container">
            <div class="block1">
                <div class="title">Ошибка 404</div>
                <div class="desc">Извините, страница не найдена</div>
                <div class="btns">
                    <a class="btn red" href="/">На главную</a>
                    <a class="btn" href="{{ route('pages.get', 'catalog') }}">Перейти в каталог</a>
                </div>
            </div>
            @include('layouts.footer')
        </div>
    </main>
@endsection
