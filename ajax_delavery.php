<?php
require_once('bitrix/modules/main/include/prolog_before.php');
// Проверяем, был ли передан параметр "weight" через POST запрос
if (isset($_POST['weight'])) {
    // Присваиваем значение параметра "weight" переменной
    $weight = $_POST['weight'];
    $cityName = getCityData();
    $decodedResponse = getCurlResponse($cityName);

    if (isset($decodedResponse['data'][0]) && isset($decodedResponse['data'][0]['fias'])) {
        $fias = $decodedResponse['data'][0]['fias'];
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://api.esplc.ru/delivery/calculation',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => ['key' => 'bb7339da3450975521478b46dcf355e6',
                'to' => $fias, 'service' => 'delline', 'weight' => $weight],
        ]);

        $response = curl_exec($curl);
        $decodedResponse2 = json_decode($response, true);

        curl_close($curl);
        $cityName = $decodedResponse2['data']['terminals'][0]['name'];
        // Проверка на существование переменной
        if (isset($decodedResponse2['data']['terminals'][0]['name'])) {

            ?>
            <div class="delivery-order__title">Способы получения заказа</div>
            <div class="delivery-order__list">
                <?php
                $terminal = $decodedResponse2['data']['terminals'][0]; ?>
                    <div class="delivery-order__list-item">
                        <div class="delivery-order__list-time">
                            <span>Ближайшая доставка до адреса</span> <?php echo $terminal['time']['value'] . ' ' . $terminal['time']['unit'] . ', ' . $terminal['time']['text']; ?>
                        </div>
                        <div class="delivery-order__list-price">
                            <?php echo $terminal['price']['value'] . ' ' . $terminal['price']['unit']; ?>
                        </div>
                    </div>
                    <div class="delivery-order__list-item">
                        <div class="delivery-order__list-time">
                            <span>Ближайшая доставка до терминала </span> <?php echo $terminal['time']['value'] . ' ' . $terminal['time']['unit'] . ', ' . $terminal['time']['text']; ?>
                        </div>
                        <div class="delivery-order__list-price">
                            <?php echo $terminal['price']['value'] . ' ' . $terminal['price']['unit']; ?>
                        </div>
                    </div>
                <?php

                // Получаем ближайший рабочий день для самовывоза
                $pickupDate = getNextWorkingDay(date('Y-m-d'));
                $pickupDate2 = getNextWorkingDay2(date('Y-m-d'));

                ?>
                <div class="delivery-order__list-item">
                    <div class="delivery-order__list-time">
                        <span>Самовывоз <span><?php echo $pickupDate2; ?></span>,</span> <?php echo $pickupDate; ?>
                    </div>
                    <div class="delivery-order__list-price">Бесплатно</div>
                </div>
            </div>
            <?php
        }
    }
}
function getCityData() {
    global $APPLICATION;
    // Включаем буферизацию вывода
    ob_start();

    // Ваш код с вызовом компонента
    $APPLICATION->IncludeComponent(
        "webdebug.seo:regions.link",
        "only_city",
        array(
            "COMPOSITE_FRAME_MODE" => "A",
            "COMPOSITE_FRAME_TYPE" => "AUTO",
            //"LABEL" => "Ваш город:",
            "POPUP_FOOTER" => "",
            "POPUP_HEIGHT" => "550",
            "POPUP_MIN_HEIGHT" => "400",
            "POPUP_TITLE" => "Выберите Ваш город",
            "POPUP_WIDTH" => "800",
            "COMPONENT_TEMPLATE" => ".default"
        ),
        false
    );

    // Получаем данные из буфера и очищаем его
    $cityName = ob_get_clean();

    // Инициализация cURL-сессии
    $curl = curl_init();

    // Настройка параметров cURL
    curl_setopt_array($curl, [
        CURLOPT_URL => 'https://api.esplc.ru/locality/search',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => [
            'key' => 'bb7339da3450975521478b46dcf355e6',
            'target' => $cityName
        ],
    ]);

    // Выполнение cURL-запроса и получение результата
    $response = curl_exec($curl);
    $decodedResponse = json_decode($response, true);
    // Закрытие cURL-сессии
    curl_close($curl);
    if (isset($decodedResponse['data'][0]) && isset($decodedResponse['data'][0]['fias'])) {
        $fias = $decodedResponse['data'][0]['fias'];
    }else{
        $fias = 'Москва';
    }

    // Возвращаем результат запроса
    return $fias;
}
function getCurlResponse($targetCity) {
    // Инициализация cURL-сессии
    $curl = curl_init();

    // Настройка параметров cURL
    curl_setopt_array($curl, [
        CURLOPT_URL => 'https://api.esplc.ru/locality/search',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => [
            'key' => 'bb7339da3450975521478b46dcf355e6',
            'target' => $targetCity,
        ],
    ]);

    // Выполнение cURL-запроса и получение результата
    $response = curl_exec($curl);
    $decodedResponse = json_decode($response, true);

    // Закрытие cURL-сессии
    curl_close($curl);

    // Возвращаем результат запроса
    return $decodedResponse;
}
// Функция для определения ближайшего рабочего дня
function getNextWorkingDay($currentDate) {
    // Преобразование даты в объект
    $currentDateObj = new DateTime($currentDate);

    // Увеличиваем дату на 1 день
    $currentDateObj->modify('+1 day');

    // Если текущий день - суббота (6), увеличиваем дату еще на 2 дня
    if ($currentDateObj->format('N') == 6) {
        $currentDateObj->modify('+2 days');
    }

    // Возвращаем день и месяц в нужном формате
    $monthTranslations = [
        'January' => 'января',
        'February' => 'февраля',
        'March' => 'марта',
        'April' => 'апреля',
        'May' => 'мая',
        'June' => 'июня',
        'July' => 'июля',
        'August' => 'августа',
        'September' => 'сентября',
        'October' => 'октября',
        'November' => 'ноября',
        'December' => 'декабря',
    ];

    return $currentDateObj->format('d ') . $monthTranslations[$currentDateObj->format('F')];
}

// Функция для определения, когда это: сегодня, завтра, послезавтра или просто дата
function getNextWorkingDay2($currentDate) {
    // Преобразование даты в объект
    $currentDateObj = new DateTime($currentDate);

    // Увеличиваем дату на 1 день
    $currentDateObj->modify('+1 day');

    // Если текущий день - суббота (6), увеличиваем дату еще на 2 дня
    if ($currentDateObj->format('N') == 6) {
        $currentDateObj->modify('+2 days');
    }

    // Определение, когда это: сегодня, завтра, послезавтра или просто дата
    $now = new DateTime();
    $interval = date_diff($now, $currentDateObj);

    $dayString = $currentDateObj->format('d F');

    if ($interval->days + 1 == 0) {
        $dayString = 'Сегодня';
    } elseif ($interval->days + 1 == 1) {
        $dayString = 'Завтра';
    } elseif ($interval->days + 1 == 2) {
        $dayString = 'Послезавтра';
    }else{
        $dayString = '';
    }

    // Возвращаем результат
    return $dayString;
}
