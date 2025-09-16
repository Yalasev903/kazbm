<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class PageTemplatesEnum extends Enum
{

    const TEMPLATE_HOME = 'home';
    const TEMPLATE_ARTICLE = 'articles.index';
    const TEMPLATE_CONTACTS = 'contacts';
    const TEMPLATE_ABOUT = 'about';
    const TEMPLATE_CALCULATOR = 'calculator';
    const TEMPLATE_CATALOG = 'catalog.index';
    const TEMPLATE_SEARCH = 'search';
    const TEMPLATE_CART = 'cart';
    const TEMPLATE_CHECKOUT = 'checkout';
    const TEMPLATE_OUR_PRODUCTS = 'our_products';
    const TEMPLATE_SIMPLE = 'simple';

    public static function labels(): array
    {
        return [
            self::TEMPLATE_SIMPLE => 'По умолчанию',
            self::TEMPLATE_HOME => 'Главная страница',
            self::TEMPLATE_ARTICLE => 'Статьи',
            self::TEMPLATE_CONTACTS => 'Контакты',
            self::TEMPLATE_ABOUT => 'О компании',
            self::TEMPLATE_CALCULATOR => 'Калькулятор',
            self::TEMPLATE_CATALOG => 'Каталог',
            self::TEMPLATE_SEARCH => 'Поиск',
            self::TEMPLATE_CART => 'Корзина',
            self::TEMPLATE_CHECKOUT => 'Оформление заказа',
            self::TEMPLATE_OUR_PRODUCTS => 'Наша продукция',
        ];
    }
}
