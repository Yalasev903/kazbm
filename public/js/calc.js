$(document).ready(function() {
    var calcArray = {
        typeProject: 0,
        floorCount: 1,
        floors: [{ num: 1, width: 0, height: 0, checked: false }],
        mirrorOrDoor: [
            { format: 'mirror', width: 0, height: 0, count: 1 },
            { format: 'door', width: 0, height: 0, count: 1 }
        ],
        tolshina: [{ width: 10 }],
        sizes: {
            cost: 340,
            w: 0.25,
            h: 0.065,
            d: 0.12,
            palletCount: 300,
            weight: 3.7
        },
        kladka: 0.5,
        mortar: { volume: 0, cement: 0, sand: 0, water: 0, cost: 0 },
        delivery: {
            city: '',
            costPerTrip: 0,
            trips: 0,
            cost: 0
        }
    };

    var deliveryCosts = {};

    fetch('/ajax/delivery-costs')
        .then(response => response.json())
        .then(data => {
            // Заполняем объект deliveryCosts
            Object.keys(data).forEach(city => {
                deliveryCosts[city.toLowerCase()] = { costPerTrip: data[city] };
            });

            console.log('Данные о доставке загружены:', deliveryCosts);

            // После загрузки данных обновляем список городов и стоимость доставки
            renderDeliveryCities();
            updateDeliveryCost();
        })
        .catch(error => console.error('Ошибка загрузки данных:', error));

    function updateDeliveryCost() {
        let city = calcArray.delivery.city.toLowerCase();
        if (deliveryCosts[city]) {
            calcArray.delivery.costPerTrip = deliveryCosts[city].costPerTrip;
            console.log('Обновлена стоимость доставки:', calcArray.delivery.costPerTrip);
        }
    }

// Функция рендера списка городов в select
    function renderDeliveryCities() {
        const $select = $('.calcPage .deliveryCity');
        $select.empty();

        for (let city in deliveryCosts) {
            const cityName = city.charAt(0).toUpperCase() + city.slice(1);
            $select.append(`<option value="${city}">${cityName}</option>`);
        }

        // Устанавливаем значение по умолчанию
        $select.val(calcArray.delivery.city);

        // Добавляем обработчик на изменение города
        $select.on('change', function () {
            calcArray.delivery.city = $(this).val();
            updateDeliveryCost();
        });
    }


    function sliderUpdate() {
        $('.calcSlider').each(function(i, el) {
            $(el).slider({
                orientation: "horizontal",
                range: "min",
                min: parseInt($(el).data('min')),
                max: parseInt($(el).data('max')),
                value: parseInt($(".inputsWithRadio .calcSliderInput").eq(i).val()) || 0,
                slide: function(event, ui) {
                    $('.calcSliderInput').eq(i).val(ui.value);
                    updateCalcArrayFromSlider(i, ui.value);
                    calced();
                }
            });
            $('.calcSliderInput').eq(i).on('input', function() {
                $(el).slider("value", parseInt($(this).val()) || 0);
                updateCalcArrayFromSlider(i, $(this).val());
                calced();
            });
        });
    }

    function updateCalcArrayFromSlider(index, value) {
        if ($('.calcSliderInput').eq(index).closest('.floors').length) {
            let floorIndex = $('.calcSliderInput').eq(index).closest('.box').index();
            if ($('.calcSliderInput').eq(index).parent().hasClass('width')) {
                calcArray.floors[floorIndex].width = parseFloat(value) || 0;
            } else if ($('.calcSliderInput').eq(index).parent().hasClass('height')) {
                calcArray.floors[floorIndex].height = parseFloat(value) || 0;
            }
        } else if ($('.calcSliderInput').eq(index).closest('.mirrorOrDoor').length) {
            let mdIndex = $('.calcSliderInput').eq(index).closest('.box_item').index();
            if ($('.calcSliderInput').eq(index).parent().hasClass('width')) {
                calcArray.mirrorOrDoor[mdIndex].width = parseFloat(value) || 0;
            } else if ($('.calcSliderInput').eq(index).parent().hasClass('height')) {
                calcArray.mirrorOrDoor[mdIndex].height = parseFloat(value) || 0;
            }
        } else if ($('.calcSliderInput').eq(index).parent().hasClass('tolshina')) {
            calcArray.tolshina[0].width = parseInt(value) || 10;
        }
    }

    function firstGenerateFloors() {
        $('.calcPage .floors').empty();
        for (let i = 0; i < calcArray.floors.length; i++) {
            let floorHtml = `
            <div class="box">
              <div class="box_title">Этаж ${i + 1}</div>
              <div class="box_item">
                <div class="box_row left117">
                  <img class="left" src="/images/icons/calc_1.svg" alt="">
                  <div class="inputsWithRadio width">
                    <label>Длина всех стен, м</label>
                    <input class="calcSliderInput" type="number" value="${calcArray.floors[i].width}" min="0">
                    <div class="item-slider">
                      <div class="calcSlider" data-min="0" data-max="250"></div>
                    </div>
                  </div>
                </div>
                <div class="box_row left117">
                  <img class="left" src="/images/icons/calc_2.svg" alt="">
                  <div class="inputsWithRadio height">
                    <label>Высота стен по углам, м</label>
                    <input class="calcSliderInput" type="number" value="${calcArray.floors[i].height}" min="0">
                    <div class="item-slider">
                      <div class="calcSlider" data-min="0" data-max="100"></div>
                    </div>
                  </div>
                </div>
                ${i > 0 ? `
                <div class="box_row left117">
                  <div class="checkbox">
                    <input type="checkbox" id="divNum${i}" ${calcArray.floors[i].checked ? 'checked' : ''}>
                    <label for="divNum${i}">Как на предыдущем этаже</label>
                  </div>
                </div>` : ''}
              </div>
            </div>`;
            $('.calcPage .floors').append(floorHtml);
        }
        sliderUpdate();
        floorInputs();
    }
    firstGenerateFloors();

    function editFloors() {
        let countFloors = calcArray.floorCount;
        while (calcArray.floors.length < countFloors) {
            calcArray.floors.push({ num: calcArray.floors.length + 1, width: 0, height: 0, checked: false });
        }
        while (calcArray.floors.length > countFloors) {
            calcArray.floors.pop();
        }
        firstGenerateFloors();
    }

    function floorInputs() {
        $('.calcPage .floors .box').each(function(i, el) {
            $(el).find('.width input').on('keyup', function() {
                calcArray.floors[i].width = parseFloat($(this).val()) || 0;
                calced();
            });
            $(el).find('.height input').on('keyup', function() {
                calcArray.floors[i].height = parseFloat($(this).val()) || 0;
                calced();
            });
            $(el).find('.checkbox input').on('change', function() {
                calcArray.floors[i].checked = $(this).is(':checked');
                if (calcArray.floors[i].checked && i > 0) {
                    calcArray.floors[i].width = calcArray.floors[i - 1].width;
                    calcArray.floors[i].height = calcArray.floors[i - 1].height;
                    $(el).find('.width input').val(calcArray.floors[i].width);
                    $(el).find('.height input').val(calcArray.floors[i].height);
                }
                calced();
            });
        });
    }

    function firstGenerateMirrorOrDoor() {
        $('.calcPage .mirrorOrDoor').empty();
        calcArray.mirrorOrDoor.forEach((item, i) => {
            let html = item.format === 'mirror' ? `
            <div class="box_item mirror">
              <img class="trash" src="/images/icons/redTrash.svg" alt="">
              <div class="left">
                <div class="box_row left117">
                  <img class="left" src="/images/icons/calc_3.svg" alt="">
                  <div class="inputsWithRadio width">
                    <label>Длина, см</label>
                    <input class="calcSliderInput" type="number" value="${item.width}" min="0">
                    <div class="item-slider">
                      <div class="calcSlider" data-min="0" data-max="10000"></div>
                    </div>
                  </div>
                </div>
                <div class="box_row left117">
                  <img class="left" src="/images/icons/calc_4.svg" alt="">
                  <div class="inputsWithRadio height">
                    <label>Высота, см</label>
                    <input class="calcSliderInput" type="number" value="${item.height}" min="0">
                    <div class="item-slider">
                      <div class="calcSlider" data-min="0" data-max="10000"></div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="right">
                <div class="right_x">х</div>
                <div class="inputsWithRadio count">
                  <input type="number" value="${item.count}" min="1">
                </div>
              </div>
            </div>` : `
            <div class="box_item door">
              <img class="trash" src="/images/icons/redTrash.svg" alt="">
              <div class="left">
                <div class="box_row left117">
                  <img class="left" src="/images/icons/calc_5.svg" alt="">
                  <div class="inputsWithRadio height">
                    <label>Высота, см</label>
                    <input class="calcSliderInput" type="number" value="${item.height}" min="0">
                    <div class="item-slider">
                      <div class="calcSlider" data-min="0" data-max="10000"></div>
                    </div>
                  </div>
                </div>
                <div class="box_row left117">
                  <img class="left" src="/images/icons/calc_6.svg" alt="">
                  <div class="inputsWithRadio width">
                    <label>Длина, см</label>
                    <input class="calcSliderInput" type="number" value="${item.width}" min="0">
                    <div class="item-slider">
                      <div class="calcSlider" data-min="0" data-max="10000"></div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="right">
                <div class="right_x">х</div>
                <div class="inputsWithRadio count">
                  <input type="number" value="${item.count}" min="1">
                </div>
              </div>
            </div>`;
            $('.calcPage .mirrorOrDoor').append(html);
        });
        sliderUpdate();
        mirrorOrDoorInputs();
        $('.calcPage .mirrorOrDoor .trash').on('click', function() {
            let index = $(this).parent().index();
            calcArray.mirrorOrDoor.splice(index, 1);
            firstGenerateMirrorOrDoor();
            calced();
        });
    }
    firstGenerateMirrorOrDoor();

    function addMirror() {
        calcArray.mirrorOrDoor.push({
            format: 'mirror',
            width: 0,
            height: 0,
            count: 1
        });
        firstGenerateMirrorOrDoor();
        calced();
    }

    function addDoor() {
        calcArray.mirrorOrDoor.push({
            format: 'door',
            width: 0,
            height: 0,
            count: 1
        });
        firstGenerateMirrorOrDoor();
        calced();
    }

    $('.btn-add-mirror').on('click', addMirror);
    $('.btn-add-door').on('click', addDoor);

    function mirrorOrDoorInputs() {
        $('.calcPage .mirrorOrDoor .box_item').each(function(i, el) {
            $(el).find('.width input').on('keyup', function() {
                calcArray.mirrorOrDoor[i].width = parseFloat($(this).val()) || 0;
                calced();
            });
            $(el).find('.height input').on('keyup', function() {
                calcArray.mirrorOrDoor[i].height = parseFloat($(this).val()) || 0;
                calced();
            });
            $(el).find('.count input').on('keyup', function() {
                calcArray.mirrorOrDoor[i].count = parseFloat($(this).val()) || 1;
                calced();
            });
        });
    }
    $('input[name="wallLength"]').on('input', function() {
        calcArray.floors[0].width = parseFloat($(this).val()) || 0;
        calced();
    });

    $('input[name="wallHeight"]').on('input', function() {
        calcArray.floors[0].height = parseFloat($(this).val()) || 0;
        calced();
    });

    $('input[name="windowDoorLength"]').on('input', function() {
        calcArray.mirrorOrDoor[0].width = parseFloat($(this).val()) || 0;
        calced();
    });

    $('input[name="windowDoorHeight"]').on('input', function() {
        calcArray.mirrorOrDoor[0].height = parseFloat($(this).val()) || 0;
        calced();
    });
    $('.calcPage .typeProject input').on('change', function() {
        calcArray.typeProject = parseInt($(this).val());
        showElements();
    });
    $('.calcPage .floorNumber .number-minus').off('click').on('click', function() {
        const input = $(this).next('input');
        input[0].stepDown();
        calcArray.floorCount = parseInt(input.val()) || 1;
        editFloors();
        calced();
    });

    $('.calcPage .floorNumber .number-plus').off('click').on('click', function() {
        const input = $(this).prev('input');
        input[0].stepUp();
        calcArray.floorCount = parseInt(input.val()) || 1;
        editFloors();
        calced();
    });


    $('.size.radio').each(function(i, el) {
        if ($(el).hasClass('active')) {
            updateBrickSizes(el);
        }
        $(el).on('click', function() {
            if (!$(el).hasClass('disabled')) {
                $('.size.radio').removeClass('active');
                $(el).addClass('active');
                updateBrickSizes(el);
                calced();
            }
        });
    });

    function updateBrickSizes(el) {
        calcArray.sizes.cost = parseFloat($(el).find('.inputSize').val());
        calcArray.sizes.w = parseFloat($(el).find('.inputSize').data('w'));
        calcArray.sizes.h = parseFloat($(el).find('.inputSize').data('h'));
        calcArray.sizes.d = parseFloat($(el).find('.inputSize').data('d') || 0.12);
        calcArray.sizes.palletCount = parseFloat($(el).find('.inputPalet').val());
        calcArray.sizes.weight = parseFloat($(el).find('.inputSize').data('kg'));
    }

    $('.calcPage .kladka input').on('change', function() {
        calcArray.kladka = parseFloat($(this).data('value'));
        $('.calcPage .kladka .left .item').removeClass('active');
        $('.calcPage .kladka .left .item').eq($(this).parent().index()).addClass('active');
        calced();
    });

    $('.calcPage .tolshina input').on('keyup', function() {
        calcArray.tolshina[0].width = parseFloat($(this).val()) || 10;
        calced();
    });

    $('.calcPage .deliveryCity').on('change', function() {
        calcArray.delivery.city = $(this).val();
        calcArray.delivery.costPerTrip = deliveryCosts[calcArray.delivery.city].costPerTrip || 50000;
        calced(); // Пересчитываем стоимость доставки при смене города
    });
    $('.calcPage .mortarCostInput').on('change', function() {
        calcArray.mortar.cost  = $(this).val();
        calced(); // Пересчитываем стоимость доставки при смене города
    });
    function calced() {
        let wallArea = 0;
        for (let i = 0; i < calcArray.floors.length; i++) {
            wallArea += (parseFloat(calcArray.floors[i].width || 0) * parseFloat(calcArray.floors[i].height || 0));
        }

        let windowsDoorsArea = 0;
        for (let i = 0; i < calcArray.mirrorOrDoor.length; i++) {
            windowsDoorsArea += (calcArray.mirrorOrDoor[i].width / 100) * (calcArray.mirrorOrDoor[i].height / 100) * (calcArray.mirrorOrDoor[i].count || 1);
        }

        let seamThickness = calcArray.tolshina[0].width / 1000;
        let effectiveBrickWidth = calcArray.sizes.w + seamThickness;
        let effectiveBrickHeight = calcArray.sizes.h + seamThickness;
        let effectiveBrickArea = effectiveBrickWidth * effectiveBrickHeight;
        let brickVolume = calcArray.sizes.w * calcArray.sizes.h * calcArray.sizes.d;

        let brickCount;
        if (calcArray.kladka === 0.5) {
            let brickCountTotal = wallArea / effectiveBrickArea;
            let brickCountWindowsDoors = windowsDoorsArea / effectiveBrickArea;
            brickCount = brickCountTotal - brickCountWindowsDoors;
        } else {
            let wallVolume = wallArea * (calcArray.sizes.d * calcArray.kladka);
            let brickCountTotal = wallVolume / brickVolume;
            let brickCountWindowsDoors = (windowsDoorsArea * calcArray.sizes.d) / brickVolume;
            brickCount = brickCountTotal - brickCountWindowsDoors;
        }

        let palletCount = Math.ceil(brickCount / calcArray.sizes.palletCount);
        let brickWeight = brickCount * calcArray.sizes.weight;
        let brickCost = brickCount * calcArray.sizes.cost;

        let sTop = calcArray.sizes.w * calcArray.sizes.d;
        let sSide = calcArray.sizes.h * calcArray.sizes.d;
        let sEnd = calcArray.sizes.w * calcArray.sizes.h;
        let mortarVolumePerBrick = calcArray.kladka === 0.5
            ? (sTop + sSide) * seamThickness
            : (sTop + sSide) * seamThickness + (sEnd * seamThickness + calcArray.sizes.d * calcArray.sizes.w * seamThickness) * (calcArray.kladka - 0.5);
        let totalMortarVolume = mortarVolumePerBrick * brickCount;

        let cementRatio = 1, sandRatio = 3, waterRatio = 0.5;
        let totalParts = cementRatio + sandRatio + waterRatio;
        let cementVolume = (cementRatio / totalParts) * totalMortarVolume;
        let sandVolume = (sandRatio / totalParts) * totalMortarVolume;
        let waterVolume = (waterRatio / totalParts) * totalMortarVolume;

        let totalMortarCost = calcArray.mortar.cost * totalMortarVolume;

        let cementWeight = cementVolume * 1300;
        let sandWeight = sandVolume * 1600;
        let waterWeight = waterVolume * 1000;

        calcArray.mortar.volume = totalMortarVolume;
        calcArray.mortar.cement = cementWeight;
        calcArray.mortar.sand = sandWeight;
        calcArray.mortar.water = waterWeight;

        let trips = palletCount / 17 || 1;
        trips = (trips % 1) <= 0.2 ? Math.floor(trips) : Math.ceil(trips);
        trips = Math.max(trips, 1);

        console.log(trips)
        calcArray.delivery.trips = trips;
        let deliveryCost = trips * calcArray.delivery.costPerTrip;
        calcArray.delivery.cost = deliveryCost;
        console.log(deliveryCost)

        // Выводим результаты
        $(".kirpichShtuk").html(Math.round(brickCount).format());
        $(".calcPage .kirpichPalet").html(palletCount.format());
        $(".calcPage .kirpichWess").html(Math.ceil(brickWeight).format());
        $(".kirpichAllCost").html(Math.round(brickCost).format());
        $(".calcPage .mortarVolume").html(totalMortarVolume.toFixed(2));
        $(".calcPage .mortarCost").html(Math.round(totalMortarCost).toLocaleString());
        $(".calcPage .deliveryCost").html(Math.round(deliveryCost).format());
        $(".calcPage .totalCost").html(Math.round(brickCost + totalMortarCost + deliveryCost).format());
    }

    function showElements() {
        if (calcArray.typeProject === 0) {
            $('.kladkaItem').hide();
            calcArray.kladka = 0.5;
            $('.calcPage .kladka input[data-value="0.5"]').prop('checked', true);
            $('.calcPage .kladka .left .item').removeClass('active').eq(0).addClass('active');
        } else {
            $('.kladkaItem').show();
            if (calcArray.kladka === 0.5) {
                calcArray.kladka = 1;
                $('.calcPage .kladka input[data-value="1"]').prop('checked', true);
                $('.calcPage .kladka .left .item').removeClass('active').eq(1).addClass('active');
            }
        }
        calced();
    }

    Number.prototype.format = function() {
        return this.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
    };

    showElements();
    calced();
});
