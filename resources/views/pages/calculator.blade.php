    @php $calculatorSettings = app(\App\Filament\Settings\CalculatorSettings::class) @endphp
    
    
    @extends('layouts.app')
    @section('page_title', (strlen($page->title) > 1 ? $page->title : ''))
    @section('seo_title', (strlen($page->seo_title) > 1 ? $page->seo_title : ''))
    @section('meta_keywords', (strlen($page->meta_keywords) > 1 ? $page->meta_keywords : ''))
    @section('meta_description', (strlen($page->meta_description) > 1 ? $page->meta_description : ''))
    @section('content')
        <main class="calcPage">
            <div class="right_elm">
                <div id="right">
                    <img src="{{ asset('images/icons/calc_p.svg') }}">
                </div>
            </div>
            <div class="container">
                @include('components.breadcrumbs')
                <div class="titles">{{ __($page->sub_title ?: $page->title) }}</div>
    
                <div class="block1" id="calc">
                    <div class="block1_left">
                        <div class="item">
                            <div class="item_row">
                                <div class="title">1. {{__("Выберите тип проекта")}}</div>
                            </div>
                            <div class="item_row inputs typeProject">
                                <div class="input">
                                    <input type="radio" id="id0" name="radio1" value="0" checked="">
                                    <label for="id0">{{__("Облицовка дома")}}</label>
                                </div>
                                <div class="input">
                                    <input type="radio" id="id1" name="radio1" value="1">
                                    <label for="id1">{{__("Строительство нового дома")}}</label>
                                </div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="item_row">
                                <div class="title">2. {{__("Этажность дома:")}}</div>
                                <div class="number floorNumber">
                                    <button class="number-minus" type="button">-</button>
                                    <input type="number" step="1" min="1" value="1">
                                    <button class="number-plus" type="button">+</button>
                                </div>
                            </div>
                        </div>
                        <div class="item floors">
                            <div class="item_row">
                                <div class="title">3.{{__('Введите размеры строения')}}</div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="item_row">
                                <div class="title">4. {{__("Введите размеры окон и дверей")}}</div>
                            </div>
                            <div class="box mirrorOrDoor"></div>
                            <div class="btns">
                                <div class="btn btn-add-mirror">{{__("Добавить другое окно")}}</div>
                                <div class="btn btn-add-door">{{__("Добавить другую дверь")}}</div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="item_row">
                                <div class="title">5. {{ __('Тип кирпича') }}</div>
                            </div>
                            <div class="type">
                                @if($sizes->isNotEmpty())
                                    @foreach($sizes as $size)
                                        <div class="size radio {{ $loop->first ? 'active' : '' }}">
                                            <input class="inputPalet" type="hidden" value="{{ $size->pallet_count ?? 300 }}">
                                            <input class="inputSize" type="radio" name="size" value="{{ $size->value }}"
                                                   data-w="{{ $size->width ?? 0 }}"
                                                   data-h="{{ $size->height ?? 0 }}"
                                                   data-d="{{ $size->depth ?? 0 }}"
                                                   data-kg="{{ $size->weight ?? 0 }}"
                                                   @if($loop->first) checked @endif>
                                            <img src="{{ $size->image ? $size->getRealFormat('image') : asset('images/default.svg') }}"
                                                 alt="{{ __($size->name) }}" loading="lazy">
                                            <div class="text">{{ __($size->name) }}</div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
    
    
                        </div>
    
                        <div class="item">
                            <div class="item_row">
                                <div class="title">6. {{__("Толщина шва (10мм по умолчанию)")}}</div>
                            </div>
                            <div class="box width">
                                <div class="inputsWithRadio tolshina">
                                    <input class="calcSliderInput" type="number" value="10" min="5" max="15">
                                    <div class="item-slider">
                                        <div class="calcSlider" data-min="5" data-max="15"></div>
                                    </div>
                                </div>
                                <div class="comment">мм</div>
                            </div>
                        </div>
                        <div class="item kladkaItem">
                            <div class="item_row">
                                <div class="title">7. {{__("Способ кладки")}}</div>
                            </div>
    
                            @if(!empty($calculatorSettings->methods))
                                <div class="box kladka">
                                    <div class="left">
                                        @foreach($calculatorSettings->methods as $index => $method)
                                            <div class="item {{ $loop->first ? 'active' : '' }}">
                                                <img src="{{ asset("/storage/" . $method['icon']) }}" alt="{{ $method['text'] }}" loading="lazy">
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="inputs">
                                        @foreach($calculatorSettings->methods as $index => $method)
                                            <div class="input">
                                                <input type="radio" id="kladka{{ $index + 1 }}" name="kladka"
                                                       value="{{ $method['value'] }}" {{ $loop->first ? 'checked' : '' }}  data-value="{{ $method['value'] }}">
                                                <label for="kladka{{ $index + 1 }}">{{ __($method['text']) }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
    
    
                        </div>
                        <div class="item">
                            <div class="item_row">
                                <div class="title">8. {{__("Раствор")}}</div>
                            </div>
                            <div class="box mortar">
                                <div class="box_item">
                                    <div class="box_row left117">
                                        <img class="left" src="{{ asset('/images/icons/calc_motor.png') }}" alt="" style="width: 90px; height: auto;">
                                        <div class="inputsWithRadio">
                                            <label>{{__("Стоимость раствора за м³")}}</label>
                                            <input class="mortarCostInput" type="number" value="0" min="0">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="item_row">
                                <div class="title">9. {{__("Доставка")}}</div>
                            </div>
                            <div class="box mortar">
                                <div class="box_item">
                                    <div class="box_row left117">
                                        <img class="left" src="{{ asset('/images/icons/delivery.png') }}" alt="" style="width: 90px; height: auto;">
                                        <div class="inputsWithRadio">
                                            <label>{{__("Доставка")}}</label>
                                            <select class="deliveryCity" style="
                                                width: 100%;
                                                height: 45px;
                                                text-align: center;
                                                border-radius: 10px;
                                                background: #d8d6d4;
                                                margin-top: 10px;
                                                color: var(--color-2);
                                                font-size: 18px;
                                                font-style: normal;
                                                font-weight: 500;
                                                line-height: normal;
                                                border: none;
                                                outline: none;
                                                padding: 0 10px;
                                            ">
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
    
                    </div>
                    <div class="block1_right">
                        <div class="box">
                            <div class="item">
                                <div class="item_title">{{ __("Количество кирпича, шт:") }}</div>
                                <div class="item_desc kirpichShtuk">0</div>
                            </div>
                            <div class="item">
                                <div class="item_title">{{ __("Количество паллет, шт:") }}</div>
                                <div class="item_desc kirpichPalet">0</div>
                            </div>
                            <div class="item">
                                <div class="item_title">{{ __("Вес, кг:") }}</div>
                                <div class="item_desc kirpichWess">0</div>
                            </div>
                            <div class="item">
                                <div class="item_title">{{ __("Стоимость кирпича, ₸:") }}</div>
                                <div class="item_desc kirpichAllCost">0</div>
                            </div>
                            <div class="item">
                                <div class="item_title">{{ __("Раствор, м³:") }}</div>
                                <div class="item_desc mortarVolume">0</div>
                            </div>
                            <div class="item">
                                <div class="item_title">{{ __("Стоимость раствора, ₸:") }}</div>
                                <div class="item_desc mortarCost">0</div>
                            </div>
                            <div class="item">
                                <div class="item_title">{{ __("Стоимость доставки, ₸:") }}</div>
                                <div class="item_desc deliveryCost">0</div>
                            </div>
                            <div class="item">
                                <div class="item_title">{{ __("Итоговая стоимость, ₸:") }}</div>
                                <div class="item_desc totalCost">0</div>
                            </div>
                            <a class="btn" href="https://kazbm.backend.imarketingtest.kz/catalog">{{ __("Перейти в каталог") }}</a>
                        </div>
                    </div>
                </div>
                @include('layouts.footer')
    
            </div>
        </main>
    
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
        <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <script src="{{ asset('js/calc.js') }}"></script>
    @endsection
