<?php
AddEventHandler("sale", "OnSaleComponentOrderDeliveriesCalculated", "createObjectOrder");
    function createObjectOrder(){
           $message = "Пользователь <".$_COOKIE['BITRIX_SM_LOGIN']." > \n";
            $message .= "Последние 10 просмотренных товаров: \n";
            foreach ($_SESSION['sales'] as $key => $item) {
             $message .= "ID - ".$item[1]."\n";
             $message .= "НАЗВАНИЕ ТОВАРА - ".$item[2]."\n";
             $message .="\n";
            }
        mail('КАКОЙ-ТО ЭМЕЙЛ', 'Список просмотренных товаров' ,$message);
    }