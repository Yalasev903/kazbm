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

            <div class="titles">Рассчитайте необходимое количество кирпича</div>
            <div class="block1" id="calc">
                <div class="block1_left">
                    <div class="item">
                        <div class="item_row">
                            <div class="title">1. Выберите тип проекта</div>
                        </div>
                        <div class="item_row inputs typeProject">
                            <div class="input">
                                <input type="radio" id="id0" name="radio1" value="0" checked="">
                                <label for="id0">Облицовка дома</label>
                            </div>
                            <div class="input">
                                <input type="radio" id="id1" name="radio1" value="1">
                                <label for="id1">Строительство нового дома</label>
                            </div>
                        </div>
                    </div>
                    <div class="item">
                        <div class="item_row">
                            <div class="title">2. Этажность дома:</div>
                            <div class="number floorNumber">
                                <button class="number-minus" type="button" onclick="this.nextElementSibling.stepDown();">-</button>
                                <input type="number" step="1" min="1" value="1">
                                <button class="number-plus" type="button" onclick="this.previousElementSibling.stepUp();">+</button>
                            </div>
                        </div>
                    </div>
                    <div class="item floors">
                        <div class="item_row">
                            <div class="title">3. Введите размеры строения</div>
                        </div>
                    </div>
                    <div class="item">
                        <div class="item_row">
                            <div class="title">4. Введите размеры окон и дверей</div>
                        </div>
                        <div class="box mirrorOrDoor"></div>
                        <div class="btns">
                            <div class="btn" onclick="addMirror()">Добавить другое окно</div>
                            <div class="btn" onclick="addDoor()">Добавить другую дверь</div>
                        </div>
                    </div>
                    <div class="item">
                        <div class="item_row">
                            <div class="title">5. Тип кирпича</div>
                        </div>
                        <div class="type">
                            <div class="size radio active">
                                <input class="inputPalet" type="hidden" value="300">
                                <input class="inputSize" type="radio" name="size" value="340" data-w="0.25" data-h="0.065" data-d="0.12" data-kg="3.7" checked="">
                                <img src="images/01HYFV02ZQC8KH2ZHSEZS52FMJ.svg" alt="250х120х65" loading="lazy">
                                <div class="text">250х120х65</div>
                            </div>
                            <div class="size radio">
                                <input class="inputPalet" type="hidden" value="600">
                                <input class="inputSize" type="radio" name="size" value="180" data-w="0.25" data-h="0.065" data-d="0.06" data-kg="1.85">
                                <img src="images/01HYFV122W02E8A4R0ZDW2P5GQ.svg" alt="250х60х65" loading="lazy">
                                <div class="text">250х60х65</div>
                            </div>
                            <div class="size radio">
                                <input class="inputPalet" type="hidden" value="300">
                                <input class="inputSize" type="radio" name="size" value="150" data-w="0.25" data-h="0.088" data-d="0.12" data-kg="4.5">
                                <img src="images/01HYFV1XPTQV37ZZRRW5JRQ7SN.svg" alt="250х120х88" loading="lazy">
                                <div class="text">250х120х88</div>
                            </div>
                            <div class="size radio custom-brick">
                                <input class="inputPalet" type="hidden" value="300">
                                <input class="inputSize" type="radio" name="size" value="0" data-w="0.25" data-h="0.065" data-d="0.12" data-kg="3.7">
                                <div class="checkbox">
                                    <input type="checkbox" id="customBrick" name="customBrick">
                                    <label for="customBrick">Свой размер кирпича</label>
                                </div>
                                <div class="custom-brick-inputs" style="display: none;">
                                    <div class="input-row">
                                        <label>Длина (мм):</label>
                                        <input type="number" class="custom-w" min="1" value="250">
                                    </div>
                                    <div class="input-row">
                                        <label>Ширина (мм):</label>
                                        <input type="number" class="custom-d" min="1" value="120">
                                    </div>
                                    <div class="input-row">
                                        <label>Высота (мм):</label>
                                        <input type="number" class="custom-h" min="1" value="65">
                                    </div>
                                    <div class="input-row">
                                        <label>Вес (кг):</label>
                                        <input type="number" class="custom-kg" min="0.1" step="0.1" value="3.7">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="item">
                        <div class="item_row">
                            <div class="title">6. Толщина шва (10мм по умолчанию)</div>
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
                            <div class="title">7. Способ кладки</div>
                        </div>
                        <div class="box kladka">
                            <div class="left">
                                <div class="item active">
                                    <img src="images/01HYFMDCG0CSV86EVH9J9ZZZK0.svg" alt="В полкирпича" loading="lazy">
                                </div>
                                <div class="item">
                                    <img src="images/01HYFMDCG1NNT0TTJ5KC2ZW1HP.svg" alt="В один кирпич" loading="lazy">
                                </div>
                                <div class="item">
                                    <img src="images/01HYFMDCG1NNT0TTJ5KC2ZW1HQ.svg" alt="В полтора кирпича" loading="lazy">
                                </div>
                                <div class="item">
                                    <img src="images/01HYFMDCG2M2VQYWTKPK13YMGS.svg" alt="В два кирпича" loading="lazy">
                                </div>
                                <div class="item">
                                    <img src="images/01J0QRJDSNRNJY7S503MGHWG8X.svg" alt="В два с половиной кирпича" loading="lazy">
                                </div>
                            </div>
                            <div class="inputs">
                                <div class="input">
                                    <input type="radio" id="kladka1" name="kladka" checked="" data-value="0.5">
                                    <label for="kladka1" data-value="0.5">В полкирпича</label>
                                </div>
                                <div class="input">
                                    <input type="radio" id="kladka2" name="kladka" data-value="1">
                                    <label for="kladka2" data-value="1">В один кирпич</label>
                                </div>
                                <div class="input">
                                    <input type="radio" id="kladka3" name="kladka" data-value="1.5">
                                    <label for="kladka3" data-value="1.5">В полтора кирпича</label>
                                </div>
                                <div class="input">
                                    <input type="radio" id="kladka4" name="kladka" data-value="2">
                                    <label for="kladka4" data-value="2">В два кирпича</label>
                                </div>
                                <div class="input">
                                    <input type="radio" id="kladka5" name="kladka" data-value="2.5">
                                    <label for="kladka5" data-value="2.5">В два с половиной кирпича</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="item">
                        <div class="item_row">
                            <div class="title">8. Раствор</div>
                        </div>
                        <div class="box mortar">
                            <div class="box_item">
                                <div class="box_row left117">
                                    <img class="left" src="/images/icons/calc_4.svg" alt="">
                                    <div class="inputsWithRadio">
                                        <label>Стоимость раствора, ₸</label>
                                        <input class="mortarCostInput" type="number" value="0" min="0">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="item">
                        <div class="item_row">
                            <div class="title">9. Доставка</div>
                        </div>
                        <div class="box delivery">
                            <div class="box_item">
                                <div class="box_row left117">
                                    <img class="left" src="/images/icons/calc_1.svg" alt="">
                                    <div class="inputsWithRadio">
                                        <label>Город доставки</label>
                                        <select class="deliveryCity">
                                            <option value="pavlodar">Павлодар</option>
                                            <option value="astana">Астана</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="box_row left117">
                                    <img class="left" src="/images/icons/calc_2.svg" alt="">
                                    <div class="inputsWithRadio">
                                        <label>Расстояние, км</label>
                                        <input class="deliveryDistance" type="number" value="0" min="0" readonly>
                                    </div>
                                </div>
                                <div class="box_row left117">
                                    <img class="left" src="/images/icons/calc_3.svg" alt="">
                                    <div class="inputsWithRadio">
                                        <label>Тип транспорта</label>
                                        <select class="deliveryTransport">
                                            <option value="20ton">20-тонник</option>
                                            <option value="10ton">10-тонник</option>
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
                            <div class="item_title">Количество кирпича, шт:</div>
                            <div class="item_desc kirpichShtuk">0</div>
                        </div>
                        <div class="item">
                            <div class="item_title">Количество паллет, шт:</div>
                            <div class="item_desc kirpichPalet">0</div>
                        </div>
                        <div class="item">
                            <div class="item_title">Вес, кг:</div>
                            <div class="item_desc kirpichWess">0</div>
                        </div>
                        <div class="item">
                            <div class="item_title">Стоимость кирпича, ₸:</div>
                            <div class="item_desc kirpichAllCost">0</div>
                        </div>
                        <div class="item">
                            <div class="item_title">Раствор, м³:</div>
                            <div class="item_desc mortarVolume">0</div>
                        </div>
                        <div class="item">
                            <div class="item_title">Стоимость раствора, ₸:</div>
                            <div/Ag class="item_desc mortarCost">0</div>
                    </div>
                    <div class="item">
                        <div class="item_title">Стоимость доставки, ₸:</div>
                        <div class="item_desc deliveryCost">0</div>
                    </div>
                    <div class="item">
                        <div class="item_title">Итоговая стоимость, ₸:</div>
                        <div class="item_desc totalCost">0</div>
                    </div>
                    <a class="btn" href="https://kazbm.backend.imarketingtest.kz/catalog">Перейти в каталог</a>
                </div>
            </div>
        </div>
        </div>
    </main>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="{{ asset('js/calc.js') }}"></script>
@endsection
