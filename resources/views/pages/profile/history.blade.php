@extends('pages.profile.page')
@section('profile')
    <div class="zakaz">
        <div class="zakaz_title">{{ __("Заказы") }}</div>
        <div class="items">
            @if($orders->isNotEmpty())
                @foreach($orders as $order)
                    <div class="item">
                        <div class="item_top">
                            <div class="left">{{ __("Заказ от") }} {{ $order->getCreatedAt() }}</div>
                            <div class="right">{{ $order->getTotalAmount() }}
                                <div class="currency"> ₸</div>
                            </div>
                        </div>
                        <div class="item_bottom">
                            <div class="left">
                                <div class="left_number">
                                    <div class="title">{{ __("Номер заказа") }}</div>
                                    <div class="desc">{{ $order->getOrderNumber() }}</div>
                                </div>
                                <div class="left_status">
                                    <div class="title">
                                        {{ __("Способ доставки -") }} {{ \App\Enums\OrderDeliveryEnum::label($order->delivery) }}
                                    </div>
                                    <div class="desc">{{ $order->getStatusName() }}</div>
                                </div>
                                @if($order->isPaymentInvoice() && $order->orderInvoice)
                                    <a href="{{ route('order.invoice.show', $order->orderInvoice->id_hash) }}" class="schot" target="_blank">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <g clip-path="url(#clip0_963_12271)">
                                                <path d="M7.07812 20.9531V20.6854C7.74703 20.5414 8.25 19.9456 8.25 19.2344V18.4531C8.25 17.6347 7.58409 16.9688 6.76561 16.9688H5.98434C5.94127 16.9688 5.9062 16.9337 5.9062 16.8906V16.1094C5.9062 16.0663 5.94127 16.0312 5.98434 16.0312H6.76561C6.80869 16.0312 6.84375 16.0663 6.84375 16.1094C6.84375 16.4977 7.15856 16.8125 7.54688 16.8125C7.93519 16.8125 8.25 16.4977 8.25 16.1094C8.25 15.3981 7.74703 14.8023 7.07812 14.6584V14.3906C7.07812 14.0023 6.76331 13.6875 6.375 13.6875C5.98669 13.6875 5.67188 14.0023 5.67188 14.3906V14.6584C5.00297 14.8023 4.5 15.3981 4.5 16.1094V16.8906C4.5 17.7091 5.16591 18.375 5.98439 18.375H6.76566C6.80873 18.375 6.8438 18.4101 6.8438 18.4531V19.2344C6.8438 19.2774 6.80873 19.3125 6.76566 19.3125H5.98439C5.94131 19.3125 5.90625 19.2774 5.90625 19.2344C5.90625 18.846 5.59144 18.5312 5.20312 18.5312C4.81481 18.5312 4.5 18.846 4.5 19.2344C4.5 19.9456 5.00297 20.5414 5.67188 20.6854V20.9531C5.67188 21.3414 5.98669 21.6562 6.375 21.6562C6.76331 21.6562 7.07812 21.3414 7.07812 20.9531Z" fill="#3B3535"/>
                                                <path d="M23.7472 4.89356L19.0599 0.205969C18.928 0.0740625 18.7492 0 18.5627 0H7.78125C6.61814 0 5.67188 0.946266 5.67188 2.10938V11.383C2.51222 11.7339 0.046875 14.4202 0.046875 17.6719C0.046875 21.1612 2.88567 24 6.375 24H21.8438C23.0069 24 23.9531 23.0537 23.9531 21.8906V5.39077C23.9531 5.20425 23.8791 5.02547 23.7472 4.89356ZM19.2656 2.40061L20.4091 3.54413L21.5525 4.6875H19.2656V2.40061ZM1.45312 17.6719C1.45312 14.958 3.66108 12.75 6.375 12.75C9.08892 12.75 11.2969 14.958 11.2969 17.6719C11.2969 20.3858 9.08892 22.5938 6.375 22.5938C3.66108 22.5938 1.45312 20.3858 1.45312 17.6719ZM21.8438 22.5938H10.3476C11.6115 21.5716 12.4752 20.0738 12.6639 18.375H19.9688C20.3571 18.375 20.6719 18.0602 20.6719 17.6719C20.6719 17.2836 20.3571 16.9688 19.9688 16.9688H12.6638C12.5492 15.9366 12.1855 14.9785 11.6341 14.1562H19.9688C20.3571 14.1562 20.6719 13.8414 20.6719 13.4531C20.6719 13.0648 20.3571 12.75 19.9688 12.75H10.3476C9.43256 12.01 8.30803 11.5196 7.07812 11.383V2.10938C7.07812 1.72167 7.39355 1.40625 7.78125 1.40625H17.8594V5.39062C17.8594 5.77894 18.1742 6.09375 18.5625 6.09375H22.5469V21.8906C22.5469 22.2783 22.2315 22.5938 21.8438 22.5938Z" fill="#3B3535"/>
                                                <path d="M19.9688 8.53125H9.65625C9.26794 8.53125 8.95312 8.84606 8.95312 9.23438C8.95312 9.62269 9.26794 9.9375 9.65625 9.9375H19.9688C20.3571 9.9375 20.6719 9.62269 20.6719 9.23438C20.6719 8.84606 20.3571 8.53125 19.9688 8.53125Z" fill="#3B3535"/>
                                            </g>
                                            <defs>
                                                <clipPath id="clip0_963_12271">
                                                    <rect width="24" height="24" fill="white"/>
                                                </clipPath>
                                            </defs>
                                        </svg>
                                        {{ __("Скачать счет") }}
                                    </a>
                                @endif
                                <div class="left_btn" onclick="openDetailModal({{ $order->id }})">{{ __("Детали") }}</div>
                            </div>
                            <div class="right">
                                @foreach($order->products as $product)
                                    <a href="javascript:void(0)">
                                        <picture>
                                            @if($photoWebp = $product['attributes']['image_webp'])
                                                <source srcset="{{$photoWebp}}" type="image/webp">
                                                <source srcset="{{$photoWebp}}" type="image/pjp2">
                                            @endif
                                            <img src="{{ $product['attributes']['image'] }}" alt="{{ $product['name'] }}">
                                        </picture>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                {{ __("У вас пока нет заказов") }}
            @endif
        </div>
    </div>

    @if($orders->isNotEmpty())
        @foreach($orders as $order)
            <div class="detailModal_bloor" onclick="closeDetailModal()"></div>
            <div class="detailModal orderModal{{ $order->id }}">
                <svg class="detailModal_close" width="38" height="36" viewBox="0 0 38 36" fill="none" xmlns="http://www.w3.org/2000/svg" onclick="closeDetailModal()">
                    <path d="M20.5644 17.5856L36.6129 2.34542C37.1779 1.80885 37.1779 0.93892 36.6129 0.402424C36.0479 -0.134073 35.1318 -0.134141 34.5668 0.402424L18.5183 15.6426L2.46982 0.402424C1.9048 -0.134141 0.98872 -0.134141 0.423768 0.402424C-0.141184 0.938989 -0.141256 1.80892 0.423768 2.34542L16.4722 17.5856L0.423768 32.8258C-0.141256 33.3623 -0.141256 34.2323 0.423768 34.7687C0.706244 35.037 1.07654 35.1711 1.44683 35.1711C1.81712 35.1711 2.18734 35.037 2.46989 34.7687L18.5183 19.5286L34.5668 34.7687C34.8492 35.037 35.2195 35.1711 35.5898 35.1711C35.9601 35.1711 36.3303 35.037 36.6129 34.7687C37.1779 34.2322 37.1779 33.3623 36.6129 32.8258L20.5644 17.5856Z" fill="black"></path>
                </svg>
                <div class="detailModal_title">{{ __("Детали заказа") }}</div>
                <div class="detailModal_desc">{{ __("Заказ от") }} {{ $order->getCreatedAt() }}</div>
                <div class="zakaz_number">
                    <div class="title">{{ __("Номер заказа") }}</div>
                    <div class="desc">{{ $order->getOrderNumber() }}</div>
                </div>
                <div class="zakaz_status">
                    <div class="title">
                        {{ __("Способ доставки -") }} {{ \App\Enums\OrderDeliveryEnum::label($order->delivery) }}
                    </div>
                </div>
                <div class="zakaz_row head">
                    <div class="l">{{ __("Товар") }}</div>
                    <div class="r">{{ __("Стоимость") }}</div>
                </div>
                <div class="items">
                    @foreach($order->products as $product)
                        <div class="item cartItem"
                             data-id="{{ $product['associatedModel']['id'] }}"
                             data-qty="{{ $product['quantity'] }}">
                            <div class="item_left">{{ $product['name'] }}
                                <div class="count">х{{ $product['quantity'] }}</div>
                            </div>
                            <div class="item_right">{{ $product['price'] }}
                                <div class="currency"> ₸</div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="items">
                    <div class="item">
                        <div class="item_left">{{ __("Всего товаров") }}</div>
                        <div class="item_right">{{ $order->getData('total') }}
                            <div class="currency"> ₸</div>
                        </div>
                    </div>
                    @if($deliveryPrice = $order->getData('delivery_price'))
                        <div class="item">
                            <div class="item_left">{{ __("Доставка") }}</div>
                            <div class="item_right">{{ $deliveryPrice }}
                                <div class="currency"> ₸</div>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="items">
                    <div class="item">
                        <div class="item_left">{{ __("Итого") }}</div>
                        <div class="item_right">{{ $order->getTotalAmount() }}
                            <div class="currency"> ₸</div>
                        </div>
                    </div>
                </div>
                <div class="btn repeatBtn">{{ __("Повторить заказ") }}</div>
            </div>
        @endforeach
    @endif

@endsection
@section('scripts')
    <script>
        $(function () {

            $(".repeatBtn").on('click', function (e) {
                e.preventDefault();

                let productIds = [], quantities = [];
                $(".detailModal.active .cartItem").each(function (index, item) {
                    productIds.push($(item).data('id'))
                    quantities.push($(item).data('qty'))
                })

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '{{ route('ajax.cart.copy') }}',
                    type: "POST",
                    data: {
                        productIds: productIds.join(','),
                        quantities: quantities.join(','),
                    },
                    success: function(response){
                        window.location.href = response.url;
                    },
                    error: function(data) {
                        console.log(data)
                    }
                });
            })
        })
    </script>
@endsection
