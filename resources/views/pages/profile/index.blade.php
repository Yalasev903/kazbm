@extends('pages.profile.page')

@section('profile')
    <div class="setting">
        <div class="setting_title">{{ __("Настройки профиля") }}</div>
{{--        <div class="setting_status">--}}
{{--            <span>{{ __("Статус:") }}</span>--}}
{{--            <div title="{{ __("Вы можете стать оптовым покупателем") }}">{{ $user->getStatus() }}--}}
{{--                <div class="icon" title="{{ __("Вы можете стать оптовым покупателем") }}">--}}
{{--                    <svg width="18" height="23" viewBox="0 0 18 23" fill="none" xmlns="http://www.w3.org/2000/svg">--}}
{{--                        <circle cx="9" cy="9" r="9" fill="white"></circle>--}}
{{--                        <path d="M7.438 12.346V11.95C7.438 11.518 7.486 11.134 7.582 10.798C7.678 10.462 7.828 10.162 8.032 9.898C8.248 9.634 8.428 9.436 8.572 9.304C8.728 9.16 8.956 8.974 9.256 8.746C9.496 8.566 9.676 8.422 9.796 8.314C9.928 8.194 10.072 8.044 10.228 7.864C10.396 7.672 10.516 7.468 10.588 7.252C10.660 7.036 10.696 6.79 10.696 6.514C10.696 6.118 10.6 5.77 10.408 5.47C10.228 5.158 9.976 4.924 9.652 4.768C9.34 4.6 8.986 4.516 8.59 4.516C8.002 4.516 7.492 4.696 7.06 5.056C6.64 5.404 6.418 5.872 6.394 6.46H4.522C4.546 5.284 4.954 4.36 5.746 3.688C6.55 3.016 7.498 2.68 8.59 2.68C9.73 2.68 10.69 3.028 11.47 3.724C12.25 4.42 12.64 5.35 12.64 6.514C12.64 6.946 12.592 7.33 12.496 7.666C12.4 8.002 12.25 8.308 12.046 8.584C11.842 8.848 11.662 9.052 11.506 9.196C11.35 9.34 11.128 9.526 10.84 9.754C10.588 9.958 10.402 10.114 10.282 10.222C10.174 10.318 10.036 10.462 9.868 10.654C9.712 10.846 9.598 11.05 9.526 11.266C9.466 11.47 9.436 11.698 9.436 11.95V12.346H7.438ZM7.33 16V13.696H9.526V16H7.33Z" fill="#3B3535"></path>--}}
{{--                    </svg>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
        <form class="form" method="post" action="{{ route('profile.settings') }}">
            {{ csrf_field() }}
            <div class="form_row">
                <div class="input">
                    <label>{{ __("Имя") }}</label>
                    <input type="text" placeholder="{{ __("Имя") }}" name="name" value="{{ $user->name }}"
                           @if($errors->has('name')) class="error" @endif>
                </div>
                <div class="input">
                    <label>{{ __("Фамилия") }}</label>
                    <input type="text" placeholder="{{ __("Фамилия") }}" name="surname" value="{{ $user->surname }}"
                           @if($errors->has('surname')) class="error" @endif>
                </div>
            </div>
            <div class="form_row">
                <div class="input">
                    <label>{{ __("Отчество") }}</label>
                    <input type="text" placeholder="{{ __("Отчество") }}" name="patronymic" value="{{ $user->patronymic }}"
                           @if($errors->has('patronymic')) class="error" @endif>
                </div>
                <div class="input">
                    <label>{{ __("Телефон") }}</label>
                    <input type="tel" placeholder="{{ __("Телефон") }}" name="phone" value="{{ $user->phone }}"
                           @if($errors->has('phone')) class="error" @endif>
                </div>
            </div>
            <div class="form_row">
                <div class="input">
                    <label>{{ __("Email") }}</label>
                    <input type="email" placeholder="{{ __("Email") }}" name="email" value="{{ $user->email }}"
                           @if($errors->has('email')) class="error" @endif>
                </div>
            </div>
            <div class="form_row">
                <div class="input">
                    <label>{{ __("Пароль") }}</label>
                    <input type="password" placeholder="{{ __("Пароль") }}" autocomplete="off" name="password"
                           @if($errors->has('password')) class="error" @endif>
                </div>
                <div class="input">
                    <label>{{ __("Подтверждение пароля") }}</label>
                    <input type="password" placeholder="{{ __("Подтверждение пароля") }}" autocomplete="off" name="password_confirmation"
                           @if($errors->has('password_confirmation')) class="error" @endif>
                </div>
            </div>
            <div class="form_row">
                <button class="btn" type="submit">{{ __("Отправить") }}</button>
            </div>
        </form>
    </div>
@endsection
