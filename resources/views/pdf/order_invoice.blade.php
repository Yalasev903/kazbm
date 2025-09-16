<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>KAZBM.KZ PDF DOCUMENT</title>
    <style>
        body {
            font-family: 'DejaVu Sans', inter, sans-serif;
        }
        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
        }
    </style>
</head>
<body>
    <img src="{{ public_path('images/icons/logo.svg') }}" alt="logo icon">
    <p> Внимание! Оплата данного счета означает согласие с условиями поставки товара. Уведомление об оплате
        обязательно, в противном случае не гарантируется наличие товара на складе. Товар отпускается по факту  прихода денег на р/с Поставщика, самовывозом, при наличии доверенности и документов удостоверяющих личность.</p>

    <br>
    <p>Образец платежного поручения</p>
    <table style="border: none" width="100%">
        <tr>
            <th>Бенефициар:</th>
            <th>ИИК</th>
            <th>Кбе</th>
        </tr>
        <tr>
            <td>Товарищество с ограниченной ответственностью "КАZBM - КАZAKHSTAN BUILDING MIXTURES"</td>
            <td rowspan="2">KZ966017361000000330</td>
            <td rowspan="2">17</td>
        </tr>
        <tr>
            <td>БИН: 140540020951</td>
        </tr>
        <tr>
            <td>Банк бенефициара:</td>
            <td>БИК</td>
            <td>Код назначения платежа</td>
        </tr>
        <tr>
            <td>АО "Народный Банк Казахстана" г. г. Алматы</td>
            <td>HSBKKZKX</td>
            <td>710</td>
        </tr>
    </table>
    <h1>Счет на оплату № {{ $order->getOrderNumber() }} от {{ $order->getCreatedAt() }}г.</h1>
    <p>Поставщик:  БИН / ИИН 140540020951,Товарищество с ограниченной ответственностью "КАZBM - КАZAKHSTAN BUILDING MIXTURES",Республика Казахстан, Павлодарская обл., г. Павлодар, Промышленная зона Северная, строение 361</p>
    <p>Покупатель:  БИН / ИИН {{ $order->getData('org_bin') }}, {{ $order->getData('org_name') }}, {{ $order->getData('org_address') }}</p>
    <p>Договор: без договора</p>
    <table>
        <tr>
            <th>№</th>
            <th>Наименование</th>
            <th>Кол-во</th>
            <th>Ед.</th>
            <th>Цена</th>
            <th>Сумма</th>
        </tr>
        @php($counter = 1)
        @foreach($order->products as $product)
            @php($per_piece = $product['associatedModel']['per_piece'])
            <tr>
                <td>{{ $counter }}</td>
                <td>{{ $product['name'] }}</td>
                <td>{{ $product['quantity'] }}</td>
                <td>{{ \App\Enums\ProductPriceEnum::labels()[$per_piece] ?? '' }}</td>
                <td>{{ $product['price'] }},00</td>
                <td>{{ $product['price'] * $product['quantity'] }},00</td>
            </tr>
            @php($counter++)
        @endforeach
    </table>
    <p>Итого: {{ $order->getTotalAmount() }},00</p>
    <p>В том числе НДС: {{ $order->getVatAmount() }},00</p>
    <p>Всего наименований {{ count($order->products) }}, на сумму {{ $order->getTotalAmount() }},00 KZT</p>
    <p>Всего к оплате:</p>
    <p>Исполнитель ,,,, Бухгалтер ,,,,</p>
</body>
</html>
