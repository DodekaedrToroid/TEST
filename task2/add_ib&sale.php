<?php
    require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

    CModule::IncludeModule('iblock');
    CModule::IncludeModule('sale');

    $ciBlockElement = new CIBlockElement;

// Добавляем товар-родитель, у которго будут торг. предложения
    $product_id = $ciBlockElement->Add(
        array(
            'IBLOCK_ID' => 2, // IBLOCK товаров
            'NAME' => "Товар 1",
            "ACTIVE" => "Y",
            // Прочие параметры товара
        )
    );
// проверка на ошибки
    if (!empty($ciBlockElement->LAST_ERROR)) {
        echo "Ошибка добавления товара: ". $ciBlockElement->LAST_ERROR;
        die();
    }
// добавляем нужное кол-во торговых предложений
    $arLoadProductArray = array(
        "IBLOCK_ID"      => 5, // IBLOCK торговых предложений
        "NAME"           => "Торговое предложение 1",
        "ACTIVE"         => "Y",
        'PROPERTY_VALUES' => array(
            'CML2_LINK' => $product_id, // Свойство типа "Привязка к товарам (SKU)", связываем торг. предложение с товаром
        )
        // Прочие параметры товара
    );
    $product_offer_id = $ciBlockElement->Add($arLoadProductArray);
// проверка на ошибки
    if (!empty($ciBlockElement->LAST_ERROR)) {
        echo "Ошибка добавления торгового предложения: ". $ciBlockElement->LAST_ERROR;
        die();
    }
// Добавляем параметры к торг. предложению

  CCatalogProduct::Add(
        array(
            "ID" => $product_offer_id,
            "QUANTITY" => 100
        )
    );
// Добавляем цены к торг. предложению
   CPrice::Add(
        array(
            "CURRENCY" => "RUB",
            "PRICE" => 55000,
            "CATALOG_GROUP_ID" => 1,
            "PRODUCT_ID" => $product_offer_id,
        )
    );