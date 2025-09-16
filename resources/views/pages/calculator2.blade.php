@extends('layouts.app')
@section('page_title',(strlen($page->title) > 1 ? $page->title : ''))
@section('seo_title', (strlen($page->seo_title) > 1 ? $page->seo_title : ''))
@section('meta_keywords',(strlen($page->meta_keywords) > 1 ? $page->meta_keywords : ''))
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
                    <input class="inputPalet" type="hidden" value="500">
                    <div class="item">
                        <div class="item_row">
                            <div class="title">{{ __('Выберите тип проекта') }}</div>
                        </div>
                        <div class="item_row inputs typeProject">
                            @php $calculatorSettings = app(\App\Filament\Settings\CalculatorSettings::class) @endphp
                            @if($projectTypes = $calculatorSettings->types)
                                @foreach($projectTypes as $i => $type)
                                    <div class="input">
                                        <input type="radio" id="id{{$i}}" name="radio1" value="{{$i}}" @if($i == 0) checked @endif>
                                        <label for="id{{$i}}">{{ __($type['name']) }}</label>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <div class="item">
                        <div class="item_row">
                            <div class="title">{{ __('Этажность дома:') }}</div>
                            <div class="number floorNumber">
                                <button class="number-minus" type="button" onclick="this.nextElementSibling.stepDown();">-</button>
                                <input type="number" step="1" min="1" value="1">
                                <button class="number-plus" type="button" onclick="this.previousElementSibling.stepUp();">+</button>
                            </div>
                        </div>
                    </div>
                    <div class="item floors">
                        <div class="item_row">
                            <div class="title">{{ __('Введите размеры строения') }}</div>
                        </div>
                        <!-- Динамически добавляемые этажи будут здесь -->
                    </div>
                    <div class="item">
                        <div class="item_row">
                            <div class="title">{{ __('Введите размеры окон и дверей') }}</div>
                        </div>
                        <div class="box mirrorOrDoor">
                            <!-- Динамически добавляемые окна и двери будут здесь -->
                        </div>
                        <div class="btns">
                            <div class="btn" onclick="addMirror()">{{ __('Добавить другое окно') }}</div>
                            <div class="btn" onclick="addDoor()">{{ __('Добавить другую дверь') }}</div>
                        </div>
                    </div>
                    <div class="item">
                        <div class="item_row">
                            <div class="title">{{ __('Тип кирпича') }}</div>
                        </div>
                        <div class="type">
                            @if($sizes->isNotEmpty())
                                @foreach($sizes as $key => $size)
                                    @php $counter = $key + 1 @endphp
                                    <div class="size radio {{ $counter == 1 ? 'active' : '' }}">
                                        <input type="radio" name="size" value="{{$counter}}00"
                                               data-value="{{ $size->value }}"
                                               data-kg="{{ $size->weight }}"
                                               @if($counter == 1) checked @endif>
                                        <img src="{{ $size->getRealFormat('image') }}" alt="{{ __($size->name) }}" loading="lazy">
                                        <div class="text">{{ __($size->name) }}</div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
                <div class="block1_right">
                    <div class="box">
                        <div class="item">
                            <div class="item_title">{{ __('Количество кирпича, шт:') }}</div>
                            <div class="item_desc kirpichShtuk">0</div>
                        </div>
                        <div class="item">
                            <div class="item_title">{{ __('Количество паллет, шт:') }}</div>
                            <div class="item_desc kirpichPalet">0</div>
                        </div>
                        <div class="item">
                            <div class="item_title">{{ __('Вес, кг:') }}</div>
                            <div class="item_desc kirpichWess">0</div>
                        </div>
                        <div class="item">
                            <div class="item_title">{{ __('Итоговая стоимость, тенге:') }}</div>
                            <div class="item_desc kirpichAllCost">0</div>
                        </div>
                        <a class="btn" href="{{ route('pages.get', 'catalog') }}">{{ __('Перейти в каталог') }}</a>
                    </div>
                </div>
            </div>
            @include('layouts.footer')
        </div>
    </main>
@endsection

