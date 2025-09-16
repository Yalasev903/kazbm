@extends('layouts.app')
@section('page_title', __('Личный кабинет'))
@section('content')
    <main class="profilePage">
        <div class="container">
            @include('.components.breadcrumbs', ['title' => __("Личный кабинет")])
            <div class="titles">{{__("Личный кабинет")}}</div>
            <div class="box">
                <div class="box_left">
                    <div class="links">
                        <a class="link {{ request()->is('*/index') ? 'active' : '' }}" href="{{ route('profile.index') }}">
                            {{__("Настройки профиля")}}
                        </a>
                        <a class="link {{ request()->is('*/history') ? 'active' : '' }}" href="{{ route('profile.history') }}">
                            {{__("История заказов")}}
                        </a>
                        <div class="link" onclick="showModal(4)">{{__("Выход из системы")}}</div>
                    </div>
                </div>
                <div class="box_right">
                    @yield('profile')
                </div>
            </div>
            @include('layouts.footer')
        </div>
    </main>
@endsection
@section('scripts')
    <script>
        $(function () {

            $(".logoutBtn").on('click', function (e) {
                e.preventDefault();
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '/ajax/user/logout',
                    type: "POST",
                    success: function(response){
                        console.log(response)
                        window.location.href = '/';
                    },
                    error: function(data) {
                        console.log(data)
                    }
                });
            })
        })
    </script>
@endsection
