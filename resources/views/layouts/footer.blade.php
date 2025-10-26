@php use Illuminate\Support\Facades\App;
         $currentCity = app('currentCity');
         $isOblicSection = request()->is('*oblicovochnyy-kirpich*');
        @endphp
<footer class="footer">
    <div class="footer_inner">
        <div class="footer_top">
            <div class="left">
                <div class="left_bottom">
                    <img class="logo" src="{{ $generalSettings->getRealFormat('logo') }}" alt="footer logo">
                    <div class="item">
                        <div class="item_top">{{__("Адрес")}}:</div>
                        <div class="item_bottom">
                            @if($footerCity->slug === 'pavlodar')
                                {{ $generalSettings->{'address_' . App::getLocale()} }}
                            @else
                                {{ $footerCity->name }}
                            @endif
                        </div>
                    </div>
                    <div class="item">
                        <div class="item_top">{{__("Телефон")}}:</div>
                        <a class="item_bottom tel" href="tel:+{{ $generalSettings->getPhone() }}">
                            {{ $generalSettings->phone }}
                        </a>
                    </div>
                    <div class="item">
                        <div class="item_top">{{__("Электронная почта")}}:</div>
                        <a class="item_bottom email" href="mailto:{{ $generalSettings->email }}">
                            {{ $generalSettings->email }}
                        </a>
                    </div>
                    <iframe class="menu" id="medium_light_70000001081123847" frameborder="0" width="150px" height="50px" sandbox="allow-modals allow-forms allow-scripts allow-same-origin allow-popups allow-top-navigation-by-user-activation"></iframe><script type="text/javascript">((r,p)=>{const l=document.getElementById(r);l.contentWindow.document.open(),l.contentWindow.document.write(decodeURIComponent(escape(atob(p)))),l.contentWindow.document.close()})("medium_light_70000001081123847", "PGhlYWQ+PHNjcmlwdCB0eXBlPSJ0ZXh0L2phdmFzY3JpcHQiPgogICAgd2luZG93Ll9fc2l6ZV9fPSdtZWRpdW0nOwogICAgd2luZG93Ll9fdGhlbWVfXz0nbGlnaHQnOwogICAgd2luZG93Ll9fYnJhbmNoSWRfXz0nNzAwMDAwMDEwODExMjM4NDcnCiAgICB3aW5kb3cuX19vcmdJZF9fPScnCiAgIDwvc2NyaXB0PjxzY3JpcHQgY3Jvc3NvcmlnaW49ImFub255bW91cyIgdHlwZT0ibW9kdWxlIiBzcmM9Imh0dHBzOi8vZGlzay4yZ2lzLmNvbS93aWRnZXQtY29uc3RydWN0b3IvYXNzZXRzL2lmcmFtZS5qcyI+PC9zY3JpcHQ+PGxpbmsgcmVsPSJtb2R1bGVwcmVsb2FkIiBjcm9zc29yaWdpbj0iYW5vbnltb3VzIiBocmVmPSJodHRwczovL2Rpc2suMmdpcy5jb20vd2lkZ2V0LWNvbnN0cnVjdG9yL2Fzc2V0cy9kZWZhdWx0cy5qcyI+PGxpbmsgcmVsPSJzdHlsZXNoZWV0IiBjcm9zc29yaWdpbj0iYW5vbnltb3VzIiBocmVmPSJodHRwczovL2Rpc2suMmdpcy5jb20vd2lkZ2V0LWNvbnN0cnVjdG9yL2Fzc2V0cy9kZWZhdWx0cy5jc3MiPjwvaGVhZD48Ym9keT48ZGl2IGlkPSJpZnJhbWUiPjwvZGl2PjwvYm9keT4=")</script>

                </div>

                <div class="menu">
                    <div class="menu_left">
                        @if($socials = $generalSettings->socials)
                            @foreach($socials as $i => $item)
                                <a class="link" href="{{ $item['link'] ?? '#' }}">
                                    <img src="{{ \App\Helpers\Common::getImage($item['photo']) }}"
                                         alt="social icon{{$i}}">
                                </a>
                            @endforeach
                        @endif
                    </div>
                    <div class="menu_right">
                    {{-- Ссылка "О компании" в зависимости от раздела --}}
                    @if($isOblicSection)
                        <a class="menu_link link" href="{{ city_route('oblic.about.city', ['city' => $currentCity->slug ?? '']) }}">{{__("О компании")}}</a>
                    @else
                        <a class="menu_link link" href="{{ city_route('about.city') }}">{{__("О компании")}}</a>
                    @endif

                    {{-- Ссылка "Каталог" в зависимости от раздела --}}
                    @if($isOblicSection)
                        <a class="link" href="{{ city_route('pages.city.get', ['slug' => 'oblicovochnyy-kirpich/catalog']) }}">{{__("Каталог")}}</a>
                    @else
                        <a class="link" href="{{ city_route('pages.city.get', ['slug' => 'catalog']) }}">{{__("Каталог")}}</a>
                    @endif

                    <a class="link" href="{{ city_route('calculator.city') }}">{{__("Калькулятор")}}</a>
                    <a class="link" href="{{ city_route('pages.city.get', ['slug' => 'articles']) }}">{{__("Статьи")}}</a>

                    {{-- Ссылка "Контакты" в зависимости от раздела --}}
                    @if($isOblicSection)
                        <a class="menu_link link" href="{{ city_route('oblic.contacts.city', ['city' => $currentCity->slug ?? '']) }}">{{__("Контакты")}}</a>
                    @else
                        <a class="menu_link link" href="{{ city_route('contacts.city') }}">{{__("Контакты")}}</a>
                    @endif

                    {{-- Ссылка на облицовочный кирпич показываем только в основном разделе --}}
                    @if($isOblicSection)
                            <a class="dropdown_link link" href="{{ city_route('home.city') }}">
                                {{ __("Главная страница") }}
                            </a>
                        @else
                            {{-- Ссылка на облицовочный кирпич показываем только в основном разделе --}}
                            <a class="dropdown_link link" href="{{ city_route('oblic.city', ['city' => $currentCity->slug ?? '']) }}">
                                {{ __("Облицовочный кирпич") }}
                            </a>
                        @endif
                    </div>
                </div>

            </div>
            <div class="right">
                <div class="form">
                    <div class="form_title">{{__("Остались вопросы? Оставьте заявку и мы свяжемся с вами")}}</div>
                    <input class="footerName" placeholder="{{__("Ваше имя")}}">
                    <input class="footerEmail" placeholder="{{__("Адрес электронной почты")}}">
                    <button onclick="Sender.footerForm()">{{__("Отправить")}}</button>
                    <div class="privacy">
                      {{__("Нажимая на кнопку, вы даёте согласие на обработку персональных данных и соглашаетесь с")}}
                        <a href="{{ city_route('pages.city.get', ['slug' => 'privacy-policy']) }}">{{__("политикой конфиденциальности")}}</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer_bottom">
            <a class="confidence" href="{{ city_route('pages.city.get', ['slug' => 'privacy-policy']) }}">{{__("Политика конфиденциальности")}}</a>
            <div class="privacy">{{__("Все права защищены")}}</div>

        </div>
    </div>
</footer>
