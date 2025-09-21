@extends('layouts.app')
@section('page_title', $page->title)
@section('seo_title', $page->seo_title)
@section('meta_keywords', $page->meta_keywords)
@section('meta_description', $page->meta_description)

@section('content')
<main class="contactsPage">
    <div class="container">
        @include('components.breadcrumbs')

        {{-- H1: добавляем город --}}
        <div class="titles">
            {{ $page->sub_title ?: $page->title }}@if($currentCity) в {{ $currentCity->seo_title }}@endif
        </div>

        <div class="items">
            <div class="item">
                <div class="pic"><img src="{{ asset('images/icons/location.svg') }}" alt="location icon"></div>
                <div class="title">{{ __("Адрес") }}</div>
                <div class="desc">
                    @if($currentCity->slug === 'pavlodar')
                        {{ $generalSettings->{'address_' . App::getLocale()} }}
                    @else
                        {{ $currentCity->name }}
                    @endif
                </div>
            </div>

            <div class="item">
                <div class="pic"><img src="{{ asset('images/icons/email.svg') }}" alt="email icon"></div>
                <div class="title">{{ __("Электронный адрес") }}</div>
                <a class="desc" href="mailto:{{ $generalSettings->email }}">{{ $generalSettings->email }}</a>
            </div>

            <div class="item">
                <div class="pic"><img src="{{ asset('images/icons/tel.svg') }}" alt="tel icon"></div>
                <div class="title">{{ __("Номер телефона") }}</div>
                <a class="desc" href="tel:+{{ $generalSettings->getPhone() }}">{{ $generalSettings->phone }}</a>
            </div>
        </div>

        <div class="contact">
            @if($map_link = $generalSettings->map_link)
                <iframe class="map" src="{{$map_link}}" width="100%" height="400" style="border:none"></iframe>
            @endif

            <div class="form">
                <div class="title">{{ __("Свяжитесь с нами!") }}</div>
                <div class="form_row">
                    <input class="contactName" type="text" placeholder="{{ __("Ваше имя") }}">
                    <input class="contactEmail" type="email" placeholder="{{ __("Адрес электронной почты") }}">
                </div>
                <textarea placeholder="{{ __("Введите сообщение") }}" class="contactMsg" name="message"></textarea>
                <button class="btn" onclick="Sender.contactForm()">{{ __("Отправить") }}</button>
            </div>
        </div>

        @include('layouts.footer')
    </div>
</main>
@endsection
