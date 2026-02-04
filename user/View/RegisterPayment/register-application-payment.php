<?php
/** @var App\User\Contoller\Common\HomeController $controller */
/** @var String $titlePage */
$controller->view('Components/head');

//dd($listApplication);
?>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <style>
        .link-application{
            cursor: pointer;
        }
        .link-application:hover{
            color: #0d6efd;
        }
        .active-select{
            background-color: orange;
        }
        .application-background-color{
            background-color: #fa9a90!important;
        }
        .display-color-none{
            display: none!important;
        }
    </style>
    <body>
    <?php $controller->view('Components/header'); ?>
    <main class="analytics">

        <section class="analytics-applications__list mb-5">
            <div class="container-fluid p-2 mb-5" style="background-color: #f5f5f5">
                <h1 class="text-center mb-5">Реестр на оплату</h1>

                <style>
                    .form-check .form-check-input{
                        margin-left: -1.25em;
                    }
                    #contextMenu {
                        background-color: #ffffff;
                        border: 1px solid #dee2e6;
                        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
                        border-radius: 0.25rem;
                        display: none;
                    }

                    #contextMenu .dropdown-item {
                        cursor: pointer;
                    }

                    #contextMenu .dropdown-item:hover {
                        background-color: #f8f9fa;
                    }

                    #colorOptions {
                        display: none;
                        position: absolute;
                        top: 100%;
                        left: 0;
                        padding: 5px 0;
                    }

                    #colorOptions .dropdown-item {
                        cursor: pointer;
                    }

                    #colorOptions .dropdown-item:hover {
                        background-color: #f1f1f1;
                    }

                    tr.selected {
                        background-color: #d1ecf1 !important; /* Цвет заливки строки по умолчанию */
                    }
                    .filter-icon {
                        font-size: 16px; /* Размер иконки */
                        color: #007bff; /* Цвет иконки (синий) */
                        cursor: pointer; /* Курсор при наведении */
                        transition: color 0.3s ease; /* Плавный переход цвета */
                    }

                    /* Эффект при наведении */
                    .filter-icon:hover {
                        color: #0056b3; /* Цвет при наведении */
                    }

                    .filter-menu {
                        display: none;
                        position: absolute;
                        z-index: 9999;
                        background: white;
                        border: 1px solid #dee2e6;
                        padding: 10px;
                        box-shadow: 0 0.5rem 1rem rgba(0,0,0,.15);
                        border-radius: 0.5rem;
                        max-width: 300px; /* Максимальная ширина для предотвращения слишком больших меню */
                        width: auto; /* Ширина будет зависеть от содержимого */
                        height: auto; /* Высота будет зависеть от содержимого */
                    }

                    .filter-scroll {
                        max-height: 200px; /* Прокручиваемая часть */
                        overflow-y: auto;
                        overflow-x: hidden;
                        padding-right: 10px; /* Убираем скроллбар */
                    }

                    .sticky-bottom {
                        position: sticky;
                        bottom: 0;
                        background-color: white;
                        padding-top: 10px;
                    }
                    thead th {
                        position: sticky;
                        top: 0;
                        background-color: white !important;
                        z-index: 10;
                    }
                    thead td {
                        position: sticky;
                        top: 75px;
                        background-color: darkorange !important;
                        z-index: 10;
                    }
                    thead td.sorting_disabled{
                        position: sticky!important;
                    }
                    .table-container {
                        width: 100%;
                        max-width: 100%;
                        max-height: 80vh;
                        min-height: 80vh;
                        overflow: auto;
                    }
                </style>
                <style>
                    /* Границы для всей таблицы */
                    table {
                        border-collapse: collapse; /* Это объединяет соседние границы */
                        width: 100%;
                    }

                    /* Границы для заголовков и ячеек */
                    th, td {
                        border: 1px solid #dee2e6; /* Устанавливаем границу для всех ячеек */
                        padding: 8px; /* Добавляем отступы для улучшения видимости */
                        text-align: left; /* Выравнивание текста по левому краю */
                    }

                    /* Тема для таблицы с улучшенной видимостью */
                    table.table-bordered {
                        border: 1px solid #dee2e6;
                    }

                    /* Улучшение стиля при наведении */
                    tr:hover {
                        background-color: #f8f9fa;
                    }

                    th {
                        background-color: #f1f1f1; /* Фон для заголовков */
                        width: 12.5%;
                    }

                </style>
                <style>
                    /* Убираем обводку и изменяем фон при фокусе */
                    textarea.form-control:focus {
                        outline: none;  /* Убираем стандартную обводку при фокусе */
                        background-color: transparent; /* Делаем фон прозрачным при фокусе */
                        border: none;  /* Убираем границу */
                        box-shadow: none;  /* Убираем тени */

                    }

                    /* Убираем фон и границу в обычном состоянии */
                    textarea.form-control {
                        background-color: transparent; /* Фон прозрачный */
                        border: none; /* Убираем границу */
                        box-shadow: none;  /* Убираем тени */
                    }

                    /* Чтобы контент в textarea выравнивался и не было отступов */
                    textarea.form-control {
                        width: 100%;
                        height: 100%;
                        resize: none;  /* Отключаем изменение размера */
                        padding: 0;  /* Убираем отступы */
                        font-family: inherit;  /* Шрифт как у остальных элементов */
                        font-size: inherit;  /* Размер шрифта такой же, как у остальных элементов */
                    }

                    /* Плавный переход при изменении фокуса */
                    textarea.form-control:focus {
                        transition: background-color 0.3s ease, border 0.3s ease;
                    }
                </style>

                <style>
                    .filter-sort-buttons {
                        display: flex;
                        gap: 5px;
                        margin-bottom: 5px;
                    }
                    .filter-sort-buttons button {
                        flex: 1;
                        cursor: pointer;
                    }
                    .filter-icon.active {
                        color: #28a745 !important; /* Зеленый цвет для активного фильтра */
                    }
                </style>
                <style>
                    .filtered-column {
                        background-color: #ffeeba; /* нежный жёлтый фон */
                        font-weight: bold;
                        position: relative;
                    }
                    .filtered-column::after {
                        content: '🔎'; /* или любой другой значок */
                        position: absolute;
                        right: 5px;
                        top: 50%;
                        transform: translateY(-50%);
                        font-size: 14px;
                    }
                </style>
                <div class="table-container">
                    <div class="">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="" id="show-application-with-color">
                                        <label class="form-check-label" for="show-application-with-color">
                                            Показать только выделенные
                                        </label>
                                    </div>
                                </div>
                    <table id="appTable" class="display">
                        <thead>
                        <tr>
                            <th>
                                Логист 
                                <i class="fas fa-filter filter-icon" data-column="0"></i>
                                
                            </th>
                            <th>Номер заявки <i class="fas fa-filter filter-icon" data-column="1"></i></th>
                            <th>Юр. лицо <i class="fas fa-filter filter-icon" data-column="2"></i></th>
                            <th>Комментарий о полученных док. <i class="fas fa-filter filter-icon" data-column="3"></i></th>
                            <th>Дата получения всех док. <i class="fas fa-filter filter-icon" data-column="4"></i></th>
                            <th>Сумма, остаток <i class="fas fa-filter filter-icon" data-column="5"></i></th>
                            <th>Оплата сегодня <i class="fas fa-filter filter-icon" data-column="6"></i></th>
                            <th style="width: 20%;">Комментарий <i class="fas fa-filter filter-icon" data-column="7"></i></th>
                            <th>Дата платежа <i class="fas fa-filter filter-icon" data-column="8"></i></th>
                            <th>Перевозчик <i class="fas fa-filter filter-icon" data-column="9"></i></th>
                            <th style="max-width: 10%">Контакты перевозчика</th>
                            <th>НП и Претензии на АТИ</th>
                            <th>Досудебные претензии</th>
                        </tr>
                        <tr style="background-color: darkorange" class="tr-statistics">
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td id="td-sum"></td>
                            <td id="pay-today"></td>
                            <td style="width: 20%;"></td>
                            <td></td>
                            <td></td>
                            <td style="max-width: 10%"></td>
                            <td></td>
                            <td></td>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($listApplication as $application):  ?>
                            <?php if($application['type'] == 1): ?>
                                <tr class="tr-application" data-id-app="<?php echo $application['id'];?>">
                                    <td><?php echo $application['user'];?></td>
                                    <td class="link-application" data-id="<?php echo $application['id']; ?>"
                                        data-type="<?php echo $application['type']; ?>">
                                        <?php echo $application['application_number'];?>
                                    </td>
                                    <td>
                                        <?php echo $application['customer'];?>
                                    </td>
                                    <td><?php echo $application['comment_doc'];?></td>
                                    <td><?php echo $application['date_receipt_all_documents']; ?></td>
                                    <td class="td-remainder
                                    <?php $remainder = str_replace(' ','',$application['remainder']);
                                    if($remainder >= 90000) echo 'bg-danger'; ?>">
                                        <?php echo $application['remainder'];?> ₽
                                    </td>
                                    <td>
                                    <textarea class="form-control comment-field pay-today comment-field-history"
                                              data-id="<?php echo $application['id']; ?>"><?php echo $application['last_register_payment_comment']; ?></textarea>
                                    </td>
                                    <td style="width: 20%;">
                                        <?php foreach ($application['list_comment'] as $comment): ?>
                                            <div class="">
                                                - <?php echo $comment['comment']; ?> (<?php echo $comment['user'] .' ' .$comment['datetime']; ?>)
                                            </div>
                                        <?php endforeach; ?>
                                    </td>
                                    <td data-bs-toggle="tooltip" data-bs-html="true"
                                        data-bs-title='<?php echo $application['terms_payment_Carrier']; ?>' style="cursor:help " >
                                        <?php echo $application['date_payment']; ?>
                                    </td>
                                    <td><?php echo str_replace(['"',"'"],'',$application['carrier']); ?></td>
                                    <td style="max-width: 10%"><?php echo $application['carrier_chosen_info']; ?></td>
                                    <td>
                                        <textarea class="form-control comment-field pay-today comment-claims"
                                              data-id="<?php echo $application['id']; ?>" data-type="ati"><?php echo $application['ati_claims']; ?></textarea>
                                    </td>
                                    <td>
                                        <textarea class="form-control comment-field pay-today comment-claims"
                                              data-id="<?php echo $application['id']; ?>" data-type="pretrials"><?php echo $application['pretrials_claims']; ?></textarea>
                                    </td>
                                </tr>
                            <?php elseif ($application['type'] == 2): ?>
                                <tr class="tr-application" data-id-app="<?php echo $application['id'];?>">
                                    <td><?php echo $application['user'];?></td>
                                    <td class="link-application" data-id="<?php echo $application['id']; ?>"
                                        data-type="<?php echo $application['type']; ?>">
                                        <?php echo $application['application_number'];?>
                                    </td>
                                    <td>
                                        <?php echo $application['customer'];?>
                                    </td>
                                    <td><?php //echo $application['comment_doc'];?></td>
                                    <td><?php echo $application['date_receipt_all_documents']; ?></td>
                                    <td class="td-remainder"><?php echo $application['remainder'];?> ₽</td>
                                    <td>
                                    <textarea class="form-control comment-field pay-today comment-field-history"
                                              data-id="<?php echo $application['id']; ?>"><?php echo $application['last_register_payment_comment']; ?></textarea>
                                    </td>
                                    <td style="width: 20%;">
                                        <?php foreach ($application['list_comment'] as $comment): ?>
                                            <div class="">
                                                - <?php echo $comment['comment']; ?> (<?php echo $comment['user'] .' ' .$comment['datetime']; ?>)
                                            </div>
                                        <?php endforeach; ?>
                                    </td>
                                    <td data-bs-toggle="tooltip" data-bs-html="true"
                                        data-bs-title='<?php echo $application['terms_payment_Prr']; ?> ' style="cursor:help" >
                                        <?php echo $application['date_payment']; ?>
                                    </td>
                                    <td><?php echo str_replace(['"',"'"],'',$application['prr']); ?></td>
                                    <td style="max-width: 10%"><?php echo $application['chosen_contact_Prr']; ?></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <!-- Контейнер для динамического фильтра -->
                <div class="filter-menu" id="filterMenu">
                    <div class="filter-sort-buttons mb-2">
                        <button type="button" class="btn btn-sm btn-outline-primary sort-asc">От А до Я</button>
                        <button type="button" class="btn btn-sm btn-outline-primary sort-desc">От Я до А</button>
                    </div>

                    <!-- Кнопки "Выбрать все" и "Очистить все" -->
                    <div class="d-flex justify-content-between mb-2">
                        <button id="selectAll" class="btn btn-sm btn-outline-primary">Выбрать все</button>
                        <button id="clearAll" class="btn btn-sm btn-outline-secondary">Очистить все</button>
                    </div>

                    <!-- Поиск -->
                    <input type="text" id="filterSearch" class="form-control mb-2" placeholder="Поиск...">

                    <!-- Список чекбоксов -->
                    <div id="filterOptions" class="filter-scroll mb-2"></div>

                    <!-- Кнопка сброса -->
                    <div class="sticky-bottom mt-2 bg-white pt-2 border-top">
                        <button id="clearFilter" class="btn btn-sm btn-outline-danger w-100">Сбросить фильтр</button>
                    </div>
                </div>

                <!-- Кастомное контекстное меню -->
                <div id="contextMenu" class="dropdown-menu" style="display: none; position: absolute; z-index: 9999;">
                   <!-- <a class="dropdown-item" href="#" id="highlightRow">Выделить строку
                       <div id="colorOptions" class="dropdown-menu" style="display: none; position: absolute; top: 100%; left: 0;">
                           <a class="dropdown-item color-option" href="#" data-color="#76a5af">Голубой</a>
                           <a class="dropdown-item color-option" href="#" data-color="#cc4125">Красный</a>
                           <a class="dropdown-item color-option" href="#" data-color="#d4edda">Зеленый</a>
                           <a class="dropdown-item color-option" href="#" data-color="#fff3cd">Желтый</a>
                           <a class="dropdown-item color-option" href="#" data-color="#cce5ff">Синий</a>
                       </div>
                   </a> -->
                   <a class="dropdown-item" id="add-comment" data-bs-toggle="modal" data-bs-target="#exampleModal">
                        Добавить комментарий
                    </a>
                    <div class="dropdown-item" id="set-color-application">
                        Выделить заявку
                    </div>
                    
                </div>

            </div>
        </section>
    </main>
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Добавить комментарий</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                </div>
                <div class="modal-body">
                    <form action="" id="form-add-comment-register-payment">
                        <input type="hidden" value="0" name="id" id="input-id-application" data-type="application">
                        <div class="mb-4">
                            <label for="" class="mb-2">Введите ваш комментарий</label>
                            <input type="text" class="form-control" name="comment">
                        </div>
                        <button class="btn btn-success">
                            Добавить комментарий
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        $('body').on('change', '#show-application-with-color',function(){
            if($(this).is(':checked')){
                $('.tr-application').addClass('display-color-none');
                $('.tr-application.application-background-color').removeClass('display-color-none');
                countSumWithColor();
            }
            else{
                $('.tr-application').removeClass('display-color-none');
                countSum()
            }
        });

        function countSumWithColor(){
            let sum = 0;
            let payTodaySum = 0;

            $('.tr-application.application-background-color .td-remainder').each(function () {
                let text = $(this).text();

                if (text !== '') {
                    sum += parseInt(text.replace(/[^0-9]/g, ''));
                }
            });

            $('.tr-application.application-background-color .pay-today').each(function () {
                let text = $(this).text();

                if (text !== '') {
                    payTodaySum += parseInt(text.replace(/[^0-9]/g, ''));
                }
            });

            $('#td-sum').text(sum.toLocaleString('ru-RU') + ' ₽')
            $('#pay-today').text(payTodaySum.toLocaleString('ru-Ru') + ' ₽');
        }
        function updateApplicationColor(){
            $('.tr-application').removeClass('application-background-color');
            $.ajax({
                method: 'POST',
                url: '/register-payment/ajax/get-color-application',
                success: function(response){
                    console.log(response);
                    let data = JSON.parse(response);
                    console.log(data);

                    $.each(data, function(index, value){
                        $('.tr-application[data-id-app="' + value['id_application'] + '"').addClass('application-background-color');
                    });
                }
            })
        }

        updateApplicationColor();

        $('#form-add-comment-register-payment').submit(function (e) {
            e.preventDefault();
            $('.btn').attr('disabled', true);

            let form = $(this).serializeArray();

            $.ajax({
                method: 'POST',
                url: '/register-payment/ajax/add-comment-application',
                data: form,
                success: function (response) {
                    location.reload();
                    $('.btn').attr('disabled', true);

                }
            });
        });
        $(document).on('click', function () {
            $('#contextMenu').hide();
            // $('.tr-application').removeClass('active-select');
        });
        $('#add-comment').click(function () {
            let id = $('.active-select').data('id-app');

            $('#input-id-application').val(id);
        });

        $('#set-color-application').click(function(){
            let id = $('.active-select').data('id-app');

            $('.active-select').addClass('application-background-color');

            $.ajax({
                method: 'POST',
                url: '/register-payment/ajax/set-color-application',
                data: {id: id},
                success: function (response) {
                    // location.reload();
                    // $('.btn').attr('disabled', true);
                    updateApplicationColor();
                    console.log(response);

                }
            });
        });

        $('.tr-application').on('contextmenu',function (e){
            e.preventDefault(); // Отменяем стандартное контекстное меню
            $('.tr-application').removeClass('active-select');

            $(this).addClass('active-select');

            if($('.active-select').hasClass('application-background-color')){
                $('#set-color-application').text('Снять выделение');
                
            }
            else{
                $('#set-color-application').text('Выделить заявку');
            }

            const $row = $(this);

            // Сохраняем строку, к которой открыто меню
            $('#contextMenu').data('targetRow', $row);

            // Показываем меню
            $('#contextMenu').css({
                display: 'block',
                left: e.pageX,
                top: e.pageY
            });

            // Прячем вложенное меню
            $('#colorOptions').hide();
        });
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
        const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
        $('.link-application').click(function () {
            var id = $(this).data('id');
            var type = $(this).data('type');

            var url = '/application?id=' + id;

            if(type == 2)
                url = '/prr/prr_application?id=' + id;

            window.open(url, '_blank'); // Откроет в новой вкладке, если нужно в текущей — используйте window.location.href
        });

    

        function countSum() {

            let sum = 0;
            let payTodaySum = 0;

            $('.td-remainder').each(function () {
                let text = $(this).text();

                if (text !== '') {
                    sum += parseInt(text.replace(/[^0-9]/g, ''));
                }
            });

            $('.pay-today').each(function () {
                let text = $(this).text();

                if (text !== '') {
                    payTodaySum += parseInt(text.replace(/[^0-9]/g, ''));
                }
            });

            $('#td-sum').text(sum.toLocaleString('ru-RU') + ' ₽')
            $('#pay-today').text(payTodaySum.toLocaleString('ru-Ru') + ' ₽');
        }

        countSum()

        $('.comment-field-register-payment-comment').change(function () {
            let id = $(this).data('id');
            let comment = $(this).val();
            console.log({
                id: id,
                comment: comment
            });

            $.ajax({
                url: '/register-payment/ajax/change-application-comment',
                type: 'POST',
                data: {
                    id: id,
                    comment: comment
                },
                success: function (data) {
                    console.log(data);
                }
            });
        });
        $('.comment-field-history').change(function () {
            let id = $(this).data('id');
            let comment = $(this).val();
            console.log({
                id: id,
                comment: comment
            });

            $.ajax({
                url: '/register-payment/ajax/change-register-payment-history',
                type: 'POST',
                data: {
                    id: id,
                    comment: comment
                },
                success: function (data) {
                    console.log(data);
                }
            });
        });

        $('.comment-claims').change(function () {
            let id = $(this).data('id');
            let comment = $(this).val();
            let type = $(this).data('type');
            console.log({
                id: id,
                comment: comment
            });

            $.ajax({
                url: '/register-payment/ajax/change-register-payment-claims',
                type: 'POST',
                data: {
                    id: id,
                    comment: comment,
                    type: type
                },
                success: function (data) {
                    console.log(data);
                }
            });
        });

        let previousOrder = []; // сюда сохраним порядок сортировки до фильтрации


        $(document).ready(function () {
            // Кастомный тип сортировки для формата d.m.Y
            jQuery.extend(jQuery.fn.dataTable.ext.type.order, {
                "date-eu-pre": function (dateStr) {
                    if (!dateStr) return 0;
                    const parts = dateStr.split('.');
                    // Обратим порядок: YYYYMMDD как число
                    return new Date(parts[2], parts[1] - 1, parts[0]).getTime();
                },
                "date-eu-asc": function (a, b) {
                    return a - b;
                },
                "date-eu-desc": function (a, b) {
                    return b - a;
                }
            });

            jQuery.extend(jQuery.fn.dataTable.ext.type.order, {
                "price-num-pre": function (data) {
                    if (!data) return 0;
                    return parseFloat(data.replace(/[^\d]/g, '')) || 0;
                },
                "price-num-asc": function (a, b) {
                    return a - b;
                },
                "price-num-desc": function (a, b) {
                    return b - a;
                }
            });


            // Регистрируем тип сортировки "comment-date-newfirst"
            jQuery.extend(jQuery.fn.dataTable.ext.type.order, {
                "comment-date-newfirst-pre": function(data) {
                    // Если данных нет — ставим дату очень старую
                    if (!data) return new Date(1900, 0, 1).getTime();
                    // Создаем временный контейнер для парсинга HTML
                    const tempDiv = document.createElement('div');
                    tempDiv.innerHTML = data;

                    // Берем первый div с комментарием
                    const firstDiv = tempDiv.querySelector('div');
                    if (!firstDiv) return new Date(1900, 0, 1).getTime();

                    const firstDivText = firstDiv.textContent || '';

                    // Ищем дату в формате dd.mm.yyyy
                    const match = firstDivText.match(/(\d{2})\.(\d{2})\.(\d{4})/);
                    if (!match) return new Date(1900, 0, 1).getTime();

                    // Собираем дату в ISO формат для корректного создания объекта Date
                    const isoDate = `${match[3]}-${match[2]}-${match[1]}`;
                    const timestamp = new Date(isoDate).getTime();

                    return isNaN(timestamp) ? new Date(1900, 0, 1).getTime() : timestamp;
                },

                "comment-date-newfirst-asc": function(a, b) {
                    // Сортируем так, чтобы новые даты шли первыми (по возрастанию — новее впереди)
                    return b - a;
                },

                "comment-date-newfirst-desc": function(a, b) {
                    // Обратный порядок — старые впереди
                    return a - b;
                }
            });



            const filterableColumns = [];

            $('.filter-block').each(function() {
                const columnIndex = $(this).data('column-index');
                if (typeof columnIndex !== 'undefined') {
                    filterableColumns.push(columnIndex);
                }
            });




            const table = $('#appTable').DataTable({
                fixedHeader: true,
                "language": {
                    "sEmptyTable": "Нет данных в таблице",
                    "sInfo": "Показаны с _START_ по _END_ из _TOTAL_ записей",
                    "sInfoEmpty": "Показаны 0 записей",
                    "sInfoFiltered": "(отфильтровано из _MAX_ записей)",
                    "sInfoPostFix": "",
                    "sLengthMenu": "Показать _MENU_ записей",
                    "sLoadingRecords": "Загрузка...",
                    "sProcessing": "Обработка...",
                    "sSearch": "Поиск:",
                    "sZeroRecords": "Совпадений не найдено",
                    "oPaginate": {
                        "sFirst": "Первая",
                        "sPrevious": "Предыдущая",
                        "sNext": "Следующая",
                        "sLast": "Последняя"
                    },
                    "oAria": {
                        "sSortAscending": ": активировать для сортировки столбца по возрастанию",
                        "sSortDescending": ": активировать для сортировки столбца по убыванию"
                    }
                },
                "paging": false,// Отключаем пагинацию
                "ordering": true, // Включаем возможность сортировки
                "order": [], // Убираем сортировку по умолчанию (например, без первой сортировки)
                "columnDefs": [
                    {
                        "targets": [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12], // Здесь указываем номера столбцов, по которым не нужна сортировка
                        "orderable": false // Отключаем сортировку для этих столбцов
                    },
                    {
                        targets: [8],
                        type: 'date-eu',
                        orderable: true
                    },
                    {
                        targets: [5],
                        type: 'price-num',
                        orderable: true
                    },
                    {
                        targets: [7], // номер колонки с комментариями
                        type: 'comment-date-newfirst',
                        orderable: true,
                        render: function(data, type) {
                            // Для сортировки и типа возвращаем исходный HTML,
                            // для отображения — тоже можно оставить оригинал
                            if (type === 'sort' || type === 'type') {
                                return data;
                            }
                            return data;
                        }
                    }
                ],
            });
            let currentColumn;

            // Открытие фильтра
            $('.filter-icon').on('click', function (e) {
                currentColumn = $(this).data('column');
                const menu = $('#filterMenu');
                const optionsContainer = $('#filterOptions');
                const searchInput = $('#filterSearch');
                optionsContainer.empty();

                // Проверяем, видим ли столбец
                if (!table.column(currentColumn).visible()) {
                    return;  // Если столбец не видим, прекращаем выполнение
                }

                // Получаем уникальные значения из столбца
                let values = [];
                table.column(currentColumn).data().each(function (val) {
                    val = val.replace(/(<([^>]+)>)/gi, "").trim();
                    if (!values.includes(val)) values.push(val);
                });
                values.sort();

                // Получаем текущие выбранные фильтры
                const currentFilter = table.column(currentColumn).search();
                const activeValues = currentFilter ? currentFilter.split('|').map(v => v.replace(/^\^|\$$/g, '')) : [];

                // Рендерим чекбоксы
                let rowIndex = 0;
                values.forEach(val => {
                    rowIndex++;
                    const safeId = val.replace(/[^a-zA-Z0-9]/g, '_');
                    const isChecked = activeValues.includes(val) ? 'checked' : '';
                    optionsContainer.append(`
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="${val}" id="check-${currentColumn}-${rowIndex}" ${isChecked}>
                <label class="form-check-label" for="check-${currentColumn}-${rowIndex}">
                    ${val}
                </label>
            </div>
        `);
                });

                // Позиционирование фильтра
                const iconOffset = $(this).offset();
                menu.css({
                    top: iconOffset.top + 20,
                    left: iconOffset.left
                }).show();

                // Фильтрация по поиску
                searchInput.off().on('keyup', function () {
                    const searchVal = $(this).val().toLowerCase();

                    $('#filterOptions label').each(function () {
                        const label = $(this).text().toLowerCase();
                        $(this).parent().toggle(label.includes(searchVal));
                    });
                });

                // Автофильтрация при выборе чекбокса
                optionsContainer.on('change', 'input[type="checkbox"]', function () {
                    const selected = [];
                    $('#filterOptions input:checked').each(function () {
                        selected.push('^' + $.fn.dataTable.util.escapeRegex($(this).val()) + '$');
                    });
                    table.column(currentColumn).search(selected.join('|'), true, false).draw();

                    // === Изменяем цвет иконки фильтра ===
                    const icon = $('.filter-icon[data-column="' + currentColumn + '"]');
                    if (selected.length > 0) {
                        icon.addClass('active'); // Добавляем класс, если есть выбранные фильтры
                    } else {
                        icon.removeClass('active'); // Убираем класс, если ничего не выбрано
                    }
                    countSum()
                });
            });


            // "Выбрать все" — выбирает все чекбоксы
            $('#selectAll').on('click', function () {
                // 1. Просто ставим все галочки без триггера событий
                $('#filterOptions input[type="checkbox"]').prop('checked', true);

                // 2. Сами собираем все выбранные значения
                const selectedOptions = $('#filterOptions input[type="checkbox"]:checked')
                    .map(function () { return this.value; })
                    .get();

                // 3. Применяем фильтр вручную одним запросом
                const searchValue = selectedOptions.length ? selectedOptions.join('|') : '';
                table.column(currentColumn).search(searchValue, true, false).draw(false); // draw(false) для ускорения

                // 4. Делаем активной иконку фильтра
                const icon = $('.filter-icon[data-column="' + currentColumn + '"]');
                if (selectedOptions.length > 0) {
                    icon.addClass('active');
                } else {
                    icon.removeClass('active');
                }

                countSum();
                // updateFilterOptions();

            });


            // "Очистить все" — очищает все чекбоксы
            $('#clearAll').on('click', function () {
                // 1. Снять все галочки
                $('#filterOptions input[type="checkbox"]').prop('checked', false);

                // 2. Очистить фильтр
                table.column(currentColumn).search('', true, false).draw(false);

                // 3. Снять активность иконки фильтра
                const icon = $('.filter-icon[data-column="' + currentColumn + '"]');
                icon.removeClass('active');

                countSum();
                // updateFilterOptions();

            });


            // "Сбросить фильтр" — сбрасывает фильтр чекбоксов и сортировку столбца
            $('#clearFilter').on('click', function () {
                // 1. Снимаем все галки без триггера событий
                $('#filterOptions input[type="checkbox"]').prop('checked', false);

                // 2. Очищаем фильтр по колонке без промежуточных лишних отрисовок
                table.column(currentColumn).search('').draw(false); // draw(false) без полной перерисовки страницы

                // 3. Убираем активность с иконки
                const icon = $('.filter-icon[data-column="' + currentColumn + '"]');
                icon.removeClass('active');

                // 4. Восстанавливаем сортировку, если нужно
                if (previousOrder.length) {
                    table.order(previousOrder).draw(false); // опять же без полной перерисовки страницы
                } else {
                    table.order([]).draw(false);
                }

                // 5. Скрываем меню фильтра
                $('#filterMenu').hide();

                countSum();

                // updateFilterOptions();
            });





            // Скрыть меню при клике вне
            $(document).on('click', function (e) {
                if (!$(e.target).closest('#filterMenu, .filter-icon').length) {
                    $('#filterMenu').hide();
                }
            });

            // Сортировка "От A до Я" — сортировка таблицы по выбранному столбцу
            $('.sort-asc').on('click', function () {
                previousOrder = table.order(); // сохраняем текущее состояние сортировки
                table.order([currentColumn, 'asc']).draw();
            });


            // Сортировка "От Я до A" — сортировка таблицы по выбранному столбцу
            $('.sort-desc').on('click', function () {
                previousOrder = table.order(); // сохраняем текущее состояние сортировки
                table.order([currentColumn, 'desc']).draw();
            });


            function updateFilterOptions() {
                // Перебираем все фильтруемые столбцы
                filterableColumns.forEach(function(columnIndex) {
                    const uniqueValues = new Set(); // Множество для уникальных значений текущего столбца

                    // Проходим по всем видимым строкам таблицы
                    $('table tbody tr:visible').each(function() {
                        const cellValue = $(this).find('td').eq(columnIndex).text().trim(); // Получаем значение ячейки в нужном столбце
                        if (cellValue) {
                            uniqueValues.add(cellValue); // Добавляем уникальные значения
                        }
                    });

                    // Найдём контейнер для фильтров данного столбца
                    const filterBlock = $('.filter-block[data-column-index="' + columnIndex + '"]');
                    const checkboxContainer = filterBlock.find('.checkbox-container');

                    // Очищаем контейнер, чтобы добавить новые чекбоксы
                    checkboxContainer.empty();

                    // Добавляем чекбоксы для каждого уникального значения
                    uniqueValues.forEach(function(value) {
                        const checkbox = $('<label><input type="checkbox" value="' + value + '"> ' + value + '</label><br>');
                        checkboxContainer.append(checkbox);
                    });
                });
            }


            function applyFilters() {
                // Сохраняем выбранные фильтры для каждого столбца
                const activeFilters = {};

                filterableColumns.forEach(function(columnIndex) {
                    const selectedValues = [];

                    // Собираем все значения чекбоксов для каждого столбца
                    $('.filter-block[data-column-index="' + columnIndex + '"] input[type="checkbox"]:checked').each(function() {
                        selectedValues.push($(this).val());
                    });

                    if (selectedValues.length > 0) {
                        activeFilters[columnIndex] = selectedValues;
                    }
                });

                // Проходим по всем строкам таблицы
                $('table tbody tr').each(function() {
                    const row = $(this);
                    let showRow = true;

                    // Для каждого фильтра проверяем, соответствует ли значение в ячейке строки выбранным фильтрам
                    Object.keys(activeFilters).forEach(function(columnIndex) {
                        const cellValue = row.find('td').eq(columnIndex).text().trim();
                        if (!activeFilters[columnIndex].includes(cellValue)) {
                            showRow = false;
                        }
                    });

                    // Показываем или скрываем строку в зависимости от фильтра
                    row.toggle(showRow);
                });
            }


            // Включаем обработчики для чекбоксов
            $(document).ready(function() {
                $(document).on('change', '.filter-scroll .form-check input[type="checkbox"]', function() {
                    console.log("Чекбокс изменен, значение: " + $(this).val());
                    updateFilterOptions(); // Обновляем доступные фильтры
                    applyFilters();        // Применяем выбранные фильтры
                });
            });
        });

        $(document).ready(function () {
            // Функция для обновления высоты ячейки в зависимости от содержимого
            function adjustTextareaHeight() {
                $('textarea.form-control').each(function() {
                    // Сбрасываем высоту перед измерением
                    $(this).css('height', 'auto');
                    var newHeight = $(this)[0].scrollHeight; // Высота содержимого
                    $(this).css('height', newHeight + 'px');  // Устанавливаем новую высоту
                    $(this).closest('td').css('height', newHeight + 'px');  // Устанавливаем высоту ячейки
                });
            }

            // Применяем к textarea при изменении содержимого
            $(document).on('change', 'textarea.form-control', function () {
                adjustTextareaHeight();  // Применяем изменения
            });

            // При загрузке страницы проверим высоту всех textarea
            adjustTextareaHeight();
        });
    </script>
    </body>
<?php
