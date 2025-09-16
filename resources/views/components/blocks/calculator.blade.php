<div class="block3 ">
    <div class="titles">{{ __("Рассчитайте необходимое количество кирпича") }}</div>
    <div class="box">
        <div class="box_left">
            <div class="box_title">1. {{ __("Выберите тип проекта") }}</div>
            <div class="box_row">
                @php $calculatorSettings = app(\App\Filament\Settings\CalculatorSettings::class) @endphp
                <div class="type radio">
                    <input type="radio" name="type" value="1" checked>
                    <label>{{ $calculatorSettings->getType(0) }}</label>
                </div>
                <div class="type radio disabled" title="{{ __("Расчет рядового кирпича доступен только в расширенной версии калькулятора") }}">
                    <input type="radio" name="type" value="2">
                    <label title='{{ __("Расчет рядового кирпича доступен только в расширенной версии калькулятора") }}'>{{ $calculatorSettings->getType(1) }}</label>
                </div>
            </div>
            <div class="box_title">2.{{ __("Выберите размер кирпича") }}</div>
            <div class="box_row size_box">
                @if($sizes->isNotEmpty())
                    @foreach($sizes as $key => $size)
                        @php($counter = $key + 1)
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
            <div class="box_title">3. {{ __("Введите размеры строения") }}</div>
            <div class="box_row IWR">
                <div class="inputsWithRadio">
                    <img class="icon" src="{{ asset('images/icons/'.$icon_name.'_1'.'.svg') }}" alt="" loading="lazy">
                    <label>{{ __("Длина всех стен, м") }} <img class="q" src="{{ asset('images/icons/question.svg') }}" title="{{ __("Измерьте длину всех внешних стен.") }}"></label>
                    <input class="fi" name="wallLength" type="number" value="0" min="0">
                </div>
                <div class="inputsWithRadio">
                    <img class="icon" src="{{ asset('images/icons/'.$icon_name.'_2'.'.svg') }}" alt="" loading="lazy">
                    <label>{{ __("Высота стен по углам, м") }} <img class="q" src="{{ asset('images/icons/question.svg') }}" title="{{ __("Измерьте высоту стен в углах здания.") }}"></label>
                    <input class="se" type="number" name="wallHeight" value="0" min="0">
                </div>
                <div class="inputsWithRadio">
                    <img class="icon" src="{{ asset('images/icons/'.$icon_name.'_3'.'.svg') }}" alt="" loading="lazy">
                    <label>{{ __("Длина дверей и окон, см") }} <img class="q" src="{{ asset('images/icons/question.svg') }}" title="{{ __("Введите суммарную длину всех дверей и окон.") }}"></label>
                    <input class="th" type="number" name="windowDoorLength" value="0" min="0">
                </div>
                <div class="inputsWithRadio">
                    <img class="icon" src="{{ asset('images/icons/'.$icon_name.'_4'.'.svg') }}" alt="" loading="lazy">
                    <label>{{ __("Высота дверей и окон, см") }} <img class="q" src="{{ asset('images/icons/question.svg') }}" title="{{ __("Введите суммарную высоту всех дверей и окон.") }}"></label>
                    <input class="fo" name="windowDoorHeight" type="number" value="0" min="0">
                </div>
            </div>
        </div>
        <div class="box_right">
            <div class="head">
                @for($i = 1; $i <= 4; $i++)
                    <div class="head_item {{ $i == 1 ? 'active' : '' }}">
                        <img src="{{ asset('images/icons/'.$icon_name.'_'.$i.'.svg') }}" alt="{{$icon_name}}_{{$i}} icon" loading="lazy">
                    </div>
                @endfor
                <div class="desc">{{ __("Идеальный выбор для строительства надежных и долговечных стен, фасадов и внутренних перегородок благодаря своей прочности, удобству монтажа и эстетической привлекательности.") }}</div>
            </div>
            <div class="body">
                <div class="body_column">
                    <div class="title">{{ __("Количество кирпича, шт:") }}</div>
                    <div class="num count kirpichShtuk">34 567</div>
                </div>
                <div class="body_column">
                    <div class="title">{{ __("Итоговая стоимость, тенге:") }}</div>
                    <div class="num cost kirpichAllCost">123 456 12</div>
                </div>
                <a class="btn" href="{{ route('pages.get', 'calculator') }}">{{ __("Перейти в расширенную версию") }}</a>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="{{ asset('js/calc.js') }}"></script>
