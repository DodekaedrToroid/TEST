<?php
    if (!empty($arResult['ID']) && !empty($arResult['NAME'])) {
        if (count($_SESSION['sales']) > 10) {
            array_shift($_SESSION['sales']);
        }
        $temp[] = $_COOKIE['BITRIX_SM_LOGIN']; //логин пользователя
        $temp[] = $arResult['ID']; //id товара
        $temp[] = $arResult['NAME']; //имя товара
        array_push($_SESSION['sales'], $temp);
    }
?>