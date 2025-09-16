@extends('layouts.app')

@section('page_title', (strlen($page->title) > 1 ? $page->title : ''))
@section('seo_title', (strlen($page->seo_title) > 1 ? $page->seo_title : ''))
@section('meta_keywords', (strlen($page->meta_keywords) > 1 ? $page->meta_keywords : ''))
@section('meta_description', (strlen($page->meta_description) > 1 ? $page->meta_description : ''))

@section('content')
<main class="checkoutPage">
    <div class="container">
        <form class="box orderForm" id="orderForm">
            <div class="box_left">
                <div class="titles">{{ $page->sub_title ?: $page->title }}</div>
                <div class="form">
                    <div class="form_row liso">
                        <div class="fiz">
                            <input type="radio" id="id1" name="ip_account" value="0" checked>
                            <label for="id1">Физическое лицо</label>
                        </div>
                        <div class="ur">
                            <input type="radio" id="id2" name="ip_account" value="1">
                            <label for="id2">Юридическое лицо</label>
                        </div>
                    </div>

                    @php($user = $cart->user())
                    <div class="form_row grid">
                        <div class="input">
                            <label for="name">Имя*</label>
                            <input id="name" type="text" placeholder="Имя" name="name" value="{{ $user->name ?? null }}">
                        </div>
                        <div class="input">
                            <label for="surname">Фамилия</label>
                            <input id="surname" type="text" placeholder="Фамилия" name="surname" value="{{ $user->surname ?? null }}">
                        </div>
                        <div class="input">
                            <label for="phone">Номер телефона*</label>
                            <input id="phone" type="tel" placeholder="Номер телефона" name="phone" value="{{ $user->phone ?? null }}">
                        </div>
                        <div class="input">
                            <label for="email">Адрес электронной почты*</label>
                            <input id="email" type="email" placeholder="Адрес электронной почты" name="email" value="{{ $user->email ?? null }}">
                        </div>

                        {{-- Юридические поля --}}
                        <div class="input iin ur-fields" style="display: none;">
                            <label for="org_bin">БИН/ИИН*</label>
                            <input id="org_bin" type="text" placeholder="БИН/ИИН" name="data[org_bin]">
                        </div>
               


                        <div class="input">
    <label for="org_address" id="org_address_label">Адрес организации*</label>
    <input id="org_address" type="text" placeholder="Адрес" name="data[org_address]">
</div>

                    </div>

                    <div class="miniTitle">Выберите способ доставки:</div>
                    @php($counter = 1)
                    @foreach(\App\Enums\OrderDeliveryEnum::labels() as $type => $delivery)
                        <div class="dostavka">
                            <input type="radio" id="dos{{$counter}}" name="delivery" value="{{$type}}" @if($counter == 1) checked @endif>
                            <label for="dos{{$counter}}">
                                <div class="top">
                                    <div class="top_left">{{ $delivery['title'] }}</div>
                                    <div class="top_right">
                                        @if($delivery['price'] == 0)
                                            {{ 'Бесплатно' }}
                                        @else
                                            {{ $delivery['price'] }} <div class="currency">₸</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="bottom">{{ $delivery['desc'] }}</div>
                            </label>
                        </div>
                        @php($counter++)
                    @endforeach

                    <div class="input address">
                        <label for="city">Город*</label>
                        <input name="city" type="text" placeholder="Город" id="city">
                    </div>
                    <div class="input address">
                        <label for="street">Улица*</label>
                        <input id="street" type="text" placeholder="Улица" name="street">
                    </div>
                    <div class="input address">
                        <label for="house">Дом</label>
                        <input id="house" type="text" placeholder="Дом" name="house">
                    </div>
                </div>
            </div>

            <div class="box_right">
                <div class="miniTitle">Ваш заказ</div>
                <div class="zakaz_row head">
                    <div class="l">Товар</div>
                    <div class="r">Стоимость</div>
                </div>

                @php($items = $cart->getContent())
                @if($items->isNotEmpty())
                    <div class="items">
                        @foreach($items as $item)
                            <div class="item">
                                <div class="item_left">{{ $item->name }}
                                    <div class="count">х{{ $item->quantity }}</div>
                                </div>
                                <div class="item_right">{{ $item->price * $item->quantity }} <div class="currency">₸</div></div>
                            </div>
                        @endforeach
                    </div>
                    <input type="hidden" name="cart_items" value="1">
                @endif

                <div class="items">
                    <div class="item">
                        <div class="item_left">Всего товаров</div>
                        <div class="item_right">{{ $cart->getTotal() }}
                            <input type="hidden" name="data[total]" value="{{ $cart->getTotal() }}">
                            <div class="currency">₸</div>
                        </div>
                    </div>
                    <div class="item">
                        <div class="item_left">Доставка</div>
                        <div class="item_right">0
                            <input type="hidden" name="data[delivery_price]" value="0">
                            <div class="currency">₸</div>
                        </div>
                    </div>
                </div>
                <div class="items">
                    <div class="item">
                        <div class="item_left">Итого</div>
                        <div class="item_right">{{ $cart->getTotal() + 0 }}
                            <div class="currency">₸</div>
                        </div>
                    </div>
                </div>

                <div class="sposob">
                    <div class="sposob_title">Способ оплаты</div>
                    @php($count = 1)
                    @foreach(\App\Enums\OrderPaymentEnum::labels() as $val => $payment)
                        <div class="sposob_item">
                            <div class="radio">
                                <input type="radio" id="sp{{$count}}" name="payment" value="{{$val}}" @if($count == 1) checked @endif>
                                <label for="sp{{$count}}">
                                    <img src="{{ asset($payment['icon']) }}" alt="payment icon{{$count}}">
                                    {{ $payment['title'] }}
                                </label>
                            </div>
                            <p>{{ $payment['desc'] }}</p>
                        </div>
                        @php($count++)
                    @endforeach
                </div>

                <div class="privacy">
                    <div class="checkbox">
                        <input id="privacy" type="checkbox" name="privacy">
                        <label class="privacy" for="privacy">
                            Я даю согласие на обработку моих персональных данных, в соответствии с&nbsp;
                            <a href="{{ route('pages.get', 'privacy-policy') }}">Политикой</a>, и соглашаюсь с&nbsp;
                            <a href="{{ route('pages.get', 'privacy-policy') }}">Правилами</a>.
                        </label>
                    </div>
                </div>

                <button type="submit" class="btn">Оформить заказ</button>
            </div>
        </form>

        @include('layouts.footer')
    </div>
</main>
@endsection

@section('scripts')
<script>
    $(function () {
        function toggleUrFields() {
    let isUr = $('input[name="ip_account"]:checked').val() === '1';
    $('.ur-fields').toggle(isUr);

    // Меняем заголовок адреса
    $('#org_address_label').text(isUr ? 'Адрес организации*' : 'Адрес доставки*');
}


        toggleUrFields(); // при загрузке

        $('input[name="ip_account"]').on('change', function () {
            toggleUrFields();
        });

        $(".orderForm").on('submit', function (e) {
            e.preventDefault();
            $('.modal_bloor, .loadingZakaz, .loadingZakaz .loading .loading_box').addClass('active');

            let form = $(this);
            $.ajax({
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                url: '{{ route('ajax.order.create') }}',
                type: "POST",
                data: form.serialize(),
                success: function(response) {
                    console.log(response);
                    form[0].reset();

                    if (response.invoice_url && response.invoice_url !== '') {
                        window.location.href = response.invoice_url;
                        return;
                    }

                    $('.loadingZakaz .loading .loading_box').removeClass('active');
                    $('.loadingZakaz .loading .succes').addClass('active');

                    if (response.is_guest === false) {
                        $('.text_havntUser').removeClass('active');
                        $('.text_haveUser').addClass('active');
                        setTimeout(() => window.location.href = '/profile/history', 5000);
                    } else {
                        $('.text_haveUser').removeClass('active');
                        $('.text_havntUser').addClass('active');
                        setTimeout(() => window.location.reload(), 3000);
                    }
                },
                error: function(data) {
                    console.log(data);
                    if (data.status === 422) {
                        let errors = data.responseJSON.errors;
                        for (var key in errors) {
                            $("input[name='" + key + "']").addClass('error');
                        }
                    }
                    if (!$('#privacy').is(':checked')) {
                        $('#privacy').parent().addClass('error');
                    } else {
                        $('#privacy').parent().removeClass('error');
                    }
                    $('.loadingZakaz .loading .loading_box').removeClass('active');
                    $('.loadingZakaz .loading .error').addClass('active');
                    setTimeout(() => closeModal(), 2000);
                }
            });
        });
    });
</script>
@endsection
