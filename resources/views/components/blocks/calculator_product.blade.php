<div class="block3">
    <div class="titles">Рассчитайте необходимое количество кирпича</div>
    <div class="box">
        <div class="box_left">
            <div class="box_title">1. Выберите тип проекта</div>
            <div class="box_row">
                @php $calculatorSettings = app(\App\Filament\Settings\CalculatorSettings::class) @endphp
                <div class="type radio">
                    <input type="radio" name="type" value="1" checked>
                    <label>{{ $calculatorSettings->getType(0) }}</label>
                </div>
                <div class="type radio disabled" title="Расчет рядового кирпича доступен только в расширенной версии калькулятора">
                    <input type="radio" name="type" value="2">
                    <label title='Расчет рядового кирпича доступен только в расширенной версии калькулятора'>{{ $calculatorSettings->getType(1) }}</label>
                </div>
            </div>
            <div class="box_title">2. Выберите размер кирпича</div>
            <div class="box_row size_box">
                @if($sizes->isNotEmpty())
                    @foreach($sizes as $key => $size)
                        @php($counter = $key + 1)
                        <div class="size radio {{ $counter == 1 ? 'active' : '' }}">
                            <input type="radio" name="size" value="{{$counter}}00"
                                   data-value="{{ $size->value }}"
                                   data-kg="{{ $size->weight }}"
                                   @if($counter == 1) checked @endif>
                            <img src="{{ $size->getRealFormat('image') }}" alt="{{ $size->name }}" loading="lazy">
                            <div class="text">{{ $size->name }}</div>
                        </div>
                    @endforeach
                @endif
            </div>
            <div class="box_title">3. Введите размеры строения</div>
            <div class="box_row IWR">
                <div class="inputsWithRadio">
                    <img class="icon" src="{{ asset('images/icons/home.svg') }}" alt="" loading="lazy">
                    <label>Длина всех стен, м <img class="q" src="{{ asset('images/icons/question.svg') }}" title="Lörem ipsum premägon dibev huruvida suv, ultrar rese sur oling sesk nefär sperat pol och masusm, sedan makrorat och mör..."></label>
                    <input class="fi" type="number" value="0" min="0">
                    <div class="item-slider">
                        <div class="ui-slider ui-corner-all ui-slider-horizontal ui-widget ui-widget-content" id="slider-length" data-min="0" data-max="300">
                            <div class="ui-slider-handle ui-corner-all ui-state-default" tabindex="0" style="left: 0%;"></div>
                        </div>
                    </div>
                </div>
                <div class="inputsWithRadio">
                    <img class="icon" src="{{ asset('images/icons/home_2.svg') }}" alt="" loading="lazy">
                    <label>Высота стен по углам, м <img class="q" src="{{ asset('images/icons/question.svg') }}" title="Lörem ipsum premägon dibev huruvida suv, ultrar rese sur oling sesk nefär sperat pol och masusm, sedan makrorat och mör..."></label>
                    <input class="se" type="number" value="0" min="0">
                    <div class="item-slider">
                        <div class="ui-slider ui-corner-all ui-slider-horizontal ui-widget ui-widget-content" id="slider-height" data-min="0" data-max="100">
                            <div class="ui-slider-handle ui-corner-all ui-state-default" tabindex="0" style="left: 0%;"></div>
                        </div>
                    </div>
                </div>
                <div class="inputsWithRadio">
                    <img class="icon" src="{{ asset('images/icons/home_3.svg') }}" alt="" loading="lazy">
                    <label>Длина дверей и окон, м <img class="q" src="{{ asset('images/icons/question.svg') }}" title="Lörem ipsum premägon dibev huruvida suv, ultrar rese sur oling sesk nefär sperat pol och masusm, sedan makrorat och mör..."></label>
                    <input class="th" type="number" value="0" min="0">
                    <div class="item-slider">
                        <div class="ui-slider ui-corner-all ui-slider-horizontal ui-widget ui-widget-content" id="slider-paramWidth" data-min="0" data-max="300">
                            <div class="ui-slider-handle ui-corner-all ui-state-default" tabindex="0" style="left: 0%;"></div>
                        </div>
                    </div>
                </div>
                <div class="inputsWithRadio">
                    <img class="icon" src="{{ asset('images/icons/home_4.svg') }}" alt="" loading="lazy">
                    <label>Высота дверей и окон, м <img class="q" src="{{ asset('images/icons/question.svg') }}" title="Lörem ipsum premägon dibev huruvida suv, ultrar rese sur oling sesk nefär sperat pol och masusm, sedan makrorat och mör..."></label>
                    <input class="fo" type="number" value="0" min="0">
                    <div class="item-slider">
                        <div class="ui-slider ui-corner-all ui-slider-horizontal ui-widget ui-widget-content" id="slider-paramHeight" data-min="0" data-max="100">
                            <div class="ui-slider-handle ui-corner-all ui-state-default" tabindex="0" style="left: 0%;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="box_right">
            <div class="head">
                <div class="head_item active">
                    <img src="{{ asset('images/icons/home.svg') }}" alt="home_w icon" loading="lazy">
                </div>
                <div class="head_item">
                    <img src="{{ asset('images/icons/home_2.svg') }}" alt="home_w_2 icon" loading="lazy">
                </div>
                <div class="head_item">
                    <img src="{{ asset('images/icons/home_3.svg') }}" alt="home_w_3 icon" loading="lazy">
                </div>
                <div class="head_item">
                    <img src="{{ asset('images/icons/home_4.svg') }}" alt="home_w_4 icon" loading="lazy">
                </div>
                <div class="desc">Идеальный выбор для строительства надежных и долговечных стен, фасадов и внутренних перегородок благодаря своей прочности, удобству монтажа и эстетической привлекательности.</div>
            </div>
            <div class="body">
                <div class="body_column">
                    <div class="title">Количество кирпича, шт:</div>
                    <div class="num count kirpichShtuk">34 567</div>
                </div>
                <div class="body_column">
                    <div class="title">Итоговая стоимость, тенге:</div>
                    <div class="num cost kirpichAllCost">123 456 12</div>
                </div>
                <a class="btn" href="{{ route('pages.get', 'calculator') }}">Перейти в расширенную версию</a>
            </div>
        </div>
    </div>
</div>
