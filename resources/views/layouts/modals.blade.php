<div class="modal_bloor" onclick="closeModal()"></div>

<div class="modal zhakazZvanok">
    <svg class="modal_close" width="38" height="36" viewBox="0 0 38 36" fill="none" xmlns="http://www.w3.org/2000/svg" onclick="closeModal()">
        <path d="M20.5644 17.5856L36.6129 2.34542C37.1779 1.80885 37.1779 0.93892 36.6129 0.402424C36.0479 -0.134073 35.1318 -0.134141 34.5668 0.402424L18.5183 15.6426L2.46982 0.402424C1.9048 -0.134141 0.98872 -0.134141 0.423768 0.402424C-0.141184 0.938989 -0.141256 1.80892 0.423768 2.34542L16.4722 17.5856L0.423768 32.8258C-0.141256 33.3623 -0.141256 34.2323 0.423768 34.7687C0.706244 35.037 1.07654 35.1711 1.44683 35.1711C1.81712 35.1711 2.18734 35.037 2.46989 34.7687L18.5183 19.5286L34.5668 34.7687C34.8492 35.037 35.2195 35.1711 35.5898 35.1711C35.9601 35.1711 36.3303 35.037 36.6129 34.7687C37.1779 34.2322 37.1779 33.3623 36.6129 32.8258L20.5644 17.5856Z" fill="#3B3535"></path>
    </svg>
    <div class="title">{{ __("Заказать звонок") }}</div>
    <div class="box">
        <div class="input">
            <label>{{ __("Имя") }}*</label>
            <input class="zakazatZvanokName" type="text" placeholder="{{ __('Имя') }}">
        </div>
        <div class="input">
            <label>{{ __("Телефон") }}*</label>
            <input class="zakazatZvanokPhoneNumber" type="tel" placeholder="{{ __('Телефон') }}">
        </div>
        <div class="input">
            <label>{{ __("Комментарий") }}</label>
            <textarea class="zakazatZvanokMessage" placeholder="{{ __('Комментарий') }}"></textarea>
        </div>
        <div class="btn" onclick="Sender.modalForm1()">{{ __("Отправить") }}</div>
        <div class="text">
            <p>
                {{ __("Нажимая на кнопку, вы даёте согласие на обработку персональных данных и соглашаетесь с") }}&nbsp;
                <a href="{{ route('pages.get', 'privacy-policy') }}">{{ __("политикой конфиденциальности") }}</a>
            </p>
        </div>
    </div>
</div>

<div class="modal signIn">
    <svg class="modal_close" width="38" height="36" viewBox="0 0 38 36" fill="none" xmlns="http://www.w3.org/2000/svg" onclick="closeModal()">
        <path d="M20.5644 17.5856L36.6129 2.34542C37.1779 1.80885 37.1779 0.93892 36.6129 0.402424C36.0479 -0.134073 35.1318 -0.134141 34.5668 0.402424L18.5183 15.6426L2.46982 0.402424C1.9048 -0.134141 0.98872 -0.134141 0.423768 0.402424C-0.141184 0.938989 -0.141256 1.80892 0.423768 2.34542L16.4722 17.5856L0.423768 32.8258C-0.141256 33.3623 -0.141256 34.2323 0.423768 34.7687C0.706244 35.037 1.07654 35.1711 1.44683 35.1711C1.81712 35.1711 2.18734 35.037 2.46989 34.7687L18.5183 19.5286L34.5668 34.7687C34.8492 35.037 35.2195 35.1711 35.5898 35.1711C35.9601 35.1711 36.3303 35.037 36.6129 34.7687C37.1779 34.2322 37.1779 33.3623 36.6129 32.8258L20.5644 17.5856Z" fill="#3B3535"></path>
    </svg>
    <div class="title">{{ __("Вход") }}</div>
    <form class="box">
        <div class="input">
            <label>{{ __("Email") }}</label>
            <input class="signInEmail" type="email" placeholder="{{ __("Email") }}">
        </div>
        <div class="input">
            <label>{{ __("Пароль") }}</label>
            <input class="signInPassword" type="password" autocomplete="off" placeholder="{{ __("Пароль") }}">
        </div>
        <div class="tx_row">
            <div class="tx error">{{ __("Неверный email или пароль.") }}</div>
            <div class="tx linked" onclick="closeModal();showModal(5);">{{ __("Забыли пароль?") }}</div>
        </div>
        <div class="btn" onclick="Sender.modalForm2()">{{ __("Войти") }}</div>
        <div class="link">
            <p>{{ __("Нет аккаунта?") }}</p>
            <span onclick="closeModal();showModal(3);">{{ __("Зарегистрироваться") }}</span>
        </div>
    </form>
</div>

<div class="modal signUp">
    <svg class="modal_close" width="38" height="36" viewBox="0 0 38 36" fill="none" xmlns="http://www.w3.org/2000/svg" onclick="closeModal()">
        <path d="M20.5644 17.5856L36.6129 2.34542C37.1779 1.80885 37.1779 0.93892 36.6129 0.402424C36.0479 -0.134073 35.1318 -0.134141 34.5668 0.402424L18.5183 15.6426L2.46982 0.402424C1.9048 -0.134141 0.98872 -0.134141 0.423768 0.402424C-0.141184 0.938989 -0.141256 1.80892 0.423768 2.34542L16.4722 17.5856L0.423768 32.8258C-0.141256 33.3623 -0.141256 34.2323 0.423768 34.7687C0.706244 35.037 1.07654 35.1711 1.44683 35.1711C1.81712 35.1711 2.18734 35.037 2.46989 34.7687L18.5183 19.5286L34.5668 34.7687C34.8492 35.037 35.2195 35.1711 35.5898 35.1711C35.9601 35.1711 36.3303 35.037 36.6129 34.7687C37.1779 34.2322 37.1779 33.3623 36.6129 32.8258L20.5644 17.5856Z" fill="#3B3535"></path>
    </svg>
    <div class="title">{{ __("Регистрация") }}</div>
    <div class="box">
        <div class="input">
            <label>{{ __("Email") }}</label>
            <input class="signUpEmail" type="email" placeholder="{{ __("Email") }}">
        </div>
        <div class="input">
            <label>{{ __("Пароль") }}</label>
            <input class="signUpPassword" type="password" autocomplete="off" placeholder="{{ __("Пароль") }}">
        </div>
        <div class="input">
            <label>{{ __("Повторите пароль") }}*</label>
            <input class="signUpPassword2" type="password" autocomplete="off" placeholder="{{ __("Пароль") }}">
        </div>
        <div class="input">
            <label>{{ __("Имя") }}*</label>
            <input class="signUpName" type="text" placeholder="{{ __("Имя") }}">
        </div>
        <div class="input">
            <label>{{ __("Телефон") }}</label>
            <input class="signUpPhone" type="tel" placeholder="{{ __("Телефон") }}">
        </div>
        <div class="tx password error">{{ __("Пароль должен содержать не менее 6 символов!") }}</div>
        <div class="tx email error">{{ __("Email уже зарегистрирован!") }}</div>
        <div class="btn" onclick="Sender.modalForm3()">{{ __("Зарегистрироваться") }}</div>
        <div class="link">
            <p>{{ __("Уже есть аккаунт?") }}</p>
            <span onclick="closeModal();showModal(2);">{{ __("Войти") }}</span>
        </div>
    </div>
</div>

<div class="modal">
    <svg class="modal_close" width="38" height="36" viewBox="0 0 38 36" fill="none" xmlns="http://www.w3.org/2000/svg" onclick="closeModal()">
        <path d="M20.5644 17.5856L36.6129 2.34542C37.1779 1.80885 37.1779 0.93892 36.6129 0.402424C36.0479 -0.134073 35.1318 -0.134141 34.5668 0.402424L18.5183 15.6426L2.46982 0.402424C1.9048 -0.134141 0.98872 -0.134141 0.423768 0.402424C-0.141184 0.938989 -0.141256 1.80892 0.423768 2.34542L16.4722 17.5856L0.423768 32.8258C-0.141256 33.3623 -0.141256 34.2323 0.423768 34.7687C0.706244 35.037 1.07654 35.1711 1.44683 35.1711C1.81712 35.1711 2.18734 35.037 2.46989 34.7687L18.5183 19.5286L34.5668 34.7687C34.8492 35.037 35.2195 35.1711 35.5898 35.1711C35.9601 35.1711 36.3303 35.037 36.6129 34.7687C37.1779 34.2322 37.1779 33.3623 36.6129 32.8258L20.5644 17.5856Z" fill="#3B3535"></path>
    </svg>
    <div class="title">{{ __("Выход") }}</div>
    <div class="box">
        <div class="desc">{{ __("Уверены, что хотите выйти?") }}</div>
        <div class="btns">
            <div class="btn logoutBtn">{{ __("Выйти") }}</div>
            <div class="btn" onclick="closeModal()">{{ __("Остаться") }}</div>
        </div>
    </div>
</div>

<div class="modal vostanovit">
    <svg class="modal_close" width="38" height="36" viewBox="0 0 38 36" fill="none" xmlns="http://www.w3.org/2000/svg" onclick="closeModal()">
        <path d="M20.5644 17.5856L36.6129 2.34542C37.1779 1.80885 37.1779 0.93892 36.6129 0.402424C36.0479 -0.134073 35.1318 -0.134141 34.5668 0.402424L18.5183 15.6426L2.46982 0.402424C1.9048 -0.134141 0.98872 -0.134141 0.423768 0.402424C-0.141184 0.938989 -0.141256 1.80892 0.423768 2.34542L16.4722 17.5856L0.423768 32.8258C-0.141256 33.3623 -0.141256 34.2323 0.423768 34.7687C0.706244 35.037 1.07654 35.1711 1.44683 35.1711C1.81712 35.1711 2.18734 35.037 2.46989 34.7687L18.5183 19.5286L34.5668 34.7687C34.8492 35.037 35.2195 35.1711 35.5898 35.1711C35.9601 35.1711 36.3303 35.037 36.6129 34.7687C37.1779 34.2322 37.1779 33.3623 36.6129 32.8258L20.5644 17.5856Z" fill="#3B3535"></path>
    </svg>
    <div class="title">{{ __("Восстановление пароля") }}</div>
    <div class="box">
        <div class="input">
            <label>{{ __("Email") }}</label>
            <input class="vostanovitEmail" type="email" placeholder="{{ __("Email") }}">
        </div>
        <div class="tx_row">
            <div class="tx error">{{ __("Email не зарегистрирован!") }}</div>
            <div class="tx">{{ __("Мы вышлем вам на почту ссылку для смены пароля") }}</div>
        </div>
        <div class="btn" onclick="Sender.modalForm4()">{{ __("Отправить") }}</div>
        <div class="link">
            <p>{{ __("Нет аккаунта?") }}</p>
            <span onclick="closeModal();showModal(3);">{{ __("Зарегистрироваться") }}</span>
        </div>
    </div>
</div>

<div class="modal loadingModal loadingZakazZvonok">
    <div class="title">{{ __("Заказать звонок") }}</div>
    <div class="loading">
        <div class="loading_box">
            <div class="text">
                <div class="sending">{{ __("Отправляется") }}</div>
            </div>
        </div>
        <div class="succes">
            <div class="text">
                <div class="send">{{ __("Отправлено") }}</div>
            </div>
        </div>
        <div class="error">
            <div class="text">
                <div class="send">{{ __("Заполните форму") }}</div>
            </div>
        </div>
    </div>
</div>

<div class="modal loadingModal loadingSignIn">
    <div class="title">{{ __("Вход") }}</div>
    <div class="loading">
        <div class="loading_box">
            <div class="text">
                <div class="sending">{{ __("Отправляется") }}</div>
            </div>
        </div>
        <div class="succes">
            <div class="text">
                <div class="send">{{ __("Вход с учетной записью выполнен успешно") }}</div>
            </div>
        </div>
    </div>
</div>

<div class="modal loadingModal loadingSignUp">
    <svg class="modal_close" width="38" height="36" viewBox="0 0 38 36" fill="none" xmlns="http://www.w3.org/2000/svg" onclick="closeModal()">
        <path d="M20.5644 17.5856L36.6129 2.34542C37.1779 1.80885 37.1779 0.93892 36.6129 0.402424C36.0479 -0.134073 35.1318 -0.134141 34.5668 0.402424L18.5183 15.6426L2.46982 0.402424C1.9048 -0.134141 0.98872 -0.134141 0.423768 0.402424C-0.141184 0.938989 -0.141256 1.80892 0.423768 2.34542L16.4722 17.5856L0.423768 32.8258C-0.141256 33.3623 -0.141256 34.2323 0.423768 34.7687C0.706244 35.037 1.07654 35.1711 1.44683 35.1711C1.81712 35.1711 2.18734 35.037 2.46989 34.7687L18.5183 19.5286L34.5668 34.7687C34.8492 35.037 35.2195 35.1711 35.5898 35.1711C35.9601 35.1711 36.3303 35.037 36.6129 34.7687C37.1779 34.2322 37.1779 33.3623 36.6129 32.8258L20.5644 17.5856Z" fill="#3B3535"></path>
    </svg>
    <div class="title">{{ __("Регистрация") }}</div>
    <div class="loading">
        <div class="loading_box">
            <div class="text">
                <div class="sending">{{ __("Отправляется") }}</div>
            </div>
        </div>
        <div class="succes">
            <div class="text">
                <div class="send_text">{{ __("Регистрация прошла успешно") }}</div>
                <div class="send link" onclick="closeModal();showModal(2)">{{ __("Войти в аккаунт") }}</div>
            </div>
        </div>
        <div class="error">
            <div class="text">
                <div class="send">{{ __("Email уже зарегистрирован!") }}</div>
            </div>
        </div>
    </div>
</div>

<div class="modal loadingModal loadingVosParol">
    <div class="title">{{ __("Восстановление пароля") }}</div>
    <div class="loading">
        <div class="loading_box">
            <div class="text">
                <div class="sending">{{ __("Отправляется") }}</div>
            </div>
        </div>
        <div class="succes">
            <div class="text">
                <div class="send">{{ __("Ссылка для восстановления пароля отправлена на ваш Email") }}</div>
            </div>
        </div>
        <div class="error error1">
            <div class="text">
                <div class="send">{{ __("Email не зарегистрирован!") }}</div>
            </div>
        </div>
        <div class="error error2">
            <div class="text">
                <div class="send">{{ __("Попробуйте еще раз через 60 сек!") }}</div>
            </div>
        </div>
    </div>
</div>

<div class="modal loadingModal loadingZakaz">
    <svg class="modal_close" width="38" height="36" viewBox="0 0 38 36" fill="none" xmlns="http://www.w3.org/2000/svg" onclick="closeModal()">
        <path d="M20.5644 17.5856L36.6129 2.34542C37.1779 1.80885 37.1779 0.93892 36.6129 0.402424C36.0479 -0.134073 35.1318 -0.134141 34.5668 0.402424L18.5183 15.6426L2.46982 0.402424C1.9048 -0.134141 0.98872 -0.134141 0.423768 0.402424C-0.141184 0.938989 -0.141256 1.80892 0.423768 2.34542L16.4722 17.5856L0.423768 32.8258C-0.141256 33.3623 -0.141256 34.2323 0.423768 34.7687C0.706244 35.037 1.07654 35.1711 1.44683 35.1711C1.81712 35.1711 2.18734 35.037 2.46989 34.7687L18.5183 19.5286L34.5668 34.7687C34.8492 35.037 35.2195 35.1711 35.5898 35.1711C35.9601 35.1711 36.3303 35.037 36.6129 34.7687C37.1779 34.2322 37.1779 33.3623 36.6129 32.8258L20.5644 17.5856Z" fill="#3B3535"></path>
    </svg>
    <div class="title">{{ __("оформление заказа") }}</div>
    <div class="loading">
        <div class="loading_box">
            <div class="text">
                <div class="sending">{{ __("Отправляется") }}</div>
            </div>
        </div>
        <div class="succes">
            <div class="text text_haveUser">
                <div class="send_text">{{ __("Заказ успешно оформлен!") }}</div>
                <a class="send link" href="{{ route('profile.history') }}">{{ __("Перейти в профиль") }}</a>
            </div>
            <div class="text text_havntUser">
                <div class="send">{{ __("Заказ успешно оформлен!") }}</div>
                <div class="send_text">{{ __("Наш менеджер свяжется с вами в ближайшее время") }}</div>
            </div>
        </div>
        <div class="error">
            <div class="text">
                <div class="send">{{ __("Заполните все необходимые поля!") }}</div>
            </div>
        </div>
    </div>
</div>
<div class="language">
    <a href="{{ route('lang.change', ['lang' => 'kk']) }}">
        <img src="{{ asset('images/icons/kaz.svg') }}" alt="kk" class="language__img {{ app()->getLocale() == 'kk' ? 'language__img_active' : '' }}">
    </a>
    <a href="{{ route('lang.change', ['lang' => 'ru']) }}">
        <img src="{{ asset('images/icons/rus.svg') }}" alt="ru" class="language__img {{ app()->getLocale() == 'ru' ? 'language__img_active' : '' }}">
    </a>
</div>

