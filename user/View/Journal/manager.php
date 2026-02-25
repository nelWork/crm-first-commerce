<?php
/** @var App\User\Contoller\Common\HomeController $controller */
/** @var String $titlePage */
/** @var String $link */
/** @var String $linkStatus */
/** @var array $listApplication */
/** @var array $condition */
/** @var array $listClients */
/** @var array $listManager */
/** @var array $listCarriers */
/** @var array $dopSetting */
/** @var bool $fullCRMAccess */
/** @var bool $isROPJournal */
/** @var bool $isClosedJournal */

$controller->view('Components/head');

//dd($listApplication);
?>

<body>
<?php $controller->view('Components/header'); ?>

<style>
    .months-picker{
        text-align: center;
        padding: 1rem;
        cursor: pointer;
        border: 1px solid white;
        margin-bottom: 0.5rem;
    }
    .months-picker:hover,
    .months-picker.active{
        border: 1px solid orange;
    }
    .fillter-date-container{
        width: 100%;
        background: orange;
        padding: 6px 12px;
    }
    .label-fillter-date{
        margin: 0 1rem;
    }
    .btn-fillter-date,
    .btn-fillter-all,
    .btn-setting-table,
    .btn-fillter-submit{
        margin-left: 1rem;
        border: 1px solid grey;
        background-color: white;
        padding: 0 0.5rem;
        margin-right: 1rem;
        text-decoration: none;
        color: black;
    }

    .table-setting-container .form-check{
        margin-bottom: 0.25rem;
    }
    .table-setting-container .form-check-label{
        color: black;
        font-size: 16px;
    }
    .table-setting-container .form-check-input{
        width: 1.125em;
        height: 1.125em;
        margin-top: 0.325em;
    }
    .table-setting-container .form-check:not(.form-switch) .form-check-input[type=checkbox]{
        background-size: 100%;
    }
    .modal {
        position: fixed;
        top: 0;
        z-index: 100;
        left: 0;
        transform: unset;
        background: #00000038;
        border-radius: 10px;
        padding: 0;
        overflow: scroll !important;
    }
    .modal-backdrop.show{
        display: none !important;
    }
    .year-picker{
        font-size: 1.5rem;
    }
    .modal-dialog{
        max-width: 40%;
    }
    .js-col-comment{
        /*            cursor: pointer;*/
    }
    .td-comment-logist-full{
        /*            display: none;*/
    }
    #table-1,#table-2,#table-3{
        font-size: 0.95rem;
    }
    .dt-scroll-head{
        font-size: 0.95rem;
    }
    .js-tab-task{
        color: #0a58ca;
        margin-right: 1rem;
    }
    .event-payment-0{
        animation: event 6s ease 0s infinite;
        cursor: pointer;
    }
    .event-payment-1{
        background-color: rgba(45, 226, 118, 0.52) !important;
    }

    @keyframes event {
        0%,
        50%,
        100% {
            background-color: rgba(45, 226, 118, 0.7);
        }

        25%,
        75% {
            background-color: rgba(146, 246, 185, 0.45);
        }
    }
    @media (max-width:1440px) {
        #table-1,#table-2,#table-3{
            font-size: 0.9rem;
        }
        .dt-scroll-head{
            font-size: 0.9rem;
        }
    }
    .js-change-application-journal-status{
        padding-top: .125rem;
        padding-bottom: .125rem;
    }
    .application-closed-manually{
        background-color: rgb(254 255 0 / 30%) !important;
    }
    .application-closed-manually-2{
        background-color: rgba(126, 67, 4, 0.4) !important;
    }
    .application-closed-manually .event-payment-1,
    .application-closed-manually-2 .event-payment-1{
        background-color: unset !important;
    }
    .js-tab-months.inactive{
        color: grey;
    }
    .js-tab-months.inactive.active{
        background-color: grey;
    }
    .js-tab-type-app,.js-tab-type-app:hover,.js-tab-type-app:active{
        color: #3f0ecc
    }
    .js-tab-type-app.active{
        background-color: #3f0ecc!important;
    }


</style>
<main class="journal container-fluid p-4 table-col">
    <h1 class="text-center">
        <?php echo $titlePage; ?>
        <?php if($isROPJournal) echo 'РОП'; ?>

        <?php if($isClosedJournal) echo 'закрытых поездок'; ?>
    </h1>
    <?php if($isROPJournal): ?>
        <h4 class="text-center" id="name-logist"></h4>
    <?php endif;?>

    <?php if(!$isClosedJournal): ?>
        <?php $controller->view('Journal/list-customers'); ?>
        <?php $controller->view('Journal/list-section'); ?>
    <?php endif; ?>

    <ul class="nav nav-pills justify-content-center w-100 mt-4 mb-5 d-none <?php if($isClosedJournal) echo 'd-none'; ?>">
        <li class="nav-item">
            <a class="nav-link js-tab-task js-tab-type-app active"
               aria-current="page" href="#" data-type-app="1">
                Экспедирование
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link js-tab-task js-tab-type-app"
               href="#" data-type-app="2">
                ПРР
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link js-tab-task js-tab-type-app"
               href="#" data-type-app="3">
                ТС
            </a>
        </li>
    </ul>


    <ul class="nav nav-pills justify-content-center w-100 mt-5 mb-5 <?php if(!$isClosedJournal) echo 'd-none'; ?> ">
        <li class="nav-item">
            <a class="nav-link position-relative js-tab-task <?php if($year == 2024) echo 'active'; ?>"
               aria-current="page" href="<?php echo $link; ?>&year=2024">
                2024
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link position-relative js-tab-task <?php if($year == 2025) echo 'active'; ?>"
               aria-current="page" href="<?php echo $link; ?>&year=2025">
                2025
            </a>
        </li>
    </ul>

    <ul class="nav nav-pills justify-content-center w-100 mt-5 mb-5 <?php if(!$isClosedJournal) echo 'd-none'; ?> ">
        <li class="nav-item">
            <a class="nav-link position-relative js-tab-task js-tab-months <?php if(date('m') == '01') echo 'active'; ?>"
               aria-current="page" href="#" data-num-month="01">
                Январь
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="badge-month-01">
                    0
                </span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link position-relative js-tab-task js-tab-months <?php if(date('m') == '02') echo 'active'; ?>"
               href="#" data-num-month="02">
                Февраль
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="badge-month-02">
                    0
                </span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link position-relative js-tab-task js-tab-months <?php if(date('m') == '03') echo 'active'; ?>"
               href="#" data-num-month="03">
                Март
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="badge-month-03">
                    0
                </span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link position-relative js-tab-task js-tab-months <?php if(date('m') == '04') echo 'active'; ?>"
               href="#" data-num-month="04">
                Апрель
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="badge-month-04">
                    0
                </span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link position-relative js-tab-task js-tab-months <?php if(date('m') == '05') echo 'active'; ?>"
               href="#" data-num-month="05">
                Май
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="badge-month-05">
                    0
                </span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link position-relative js-tab-task js-tab-months <?php if(date('m') == '06') echo 'active'; ?>"
               href="#" data-num-month="06">
                Июнь
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="badge-month-06">
                    0
                </span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link position-relative js-tab-task js-tab-months <?php if(date('m') == '07') echo 'active'; ?>"
               href="#" data-num-month="07">
                Июль
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="badge-month-07">
                    0
                </span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link position-relative js-tab-task js-tab-months <?php if(date('m') == '08') echo 'active'; ?>"
               href="#" data-num-month="08">
                Август
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="badge-month-08">
                    0
                </span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link position-relative js-tab-task js-tab-months <?php if(date('m') == '09') echo 'active'; ?>"
               href="#" data-num-month="09">
                Сентябрь
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="badge-month-09">
                    0
                </span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link position-relative js-tab-task js-tab-months <?php if(date('m') == '10') echo 'active'; ?>"
               href="#" data-num-month="10">
                Октябрь
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="badge-month-10">
                    0
                </span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link position-relative js-tab-task js-tab-months <?php if(date('m') == '11') echo 'active'; ?>"
               href="#" data-num-month="11">
                Ноябрь
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="badge-month-11">
                    0
                </span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link position-relative js-tab-task js-tab-months <?php if(date('m') == '12') echo 'active'; ?>"
               href="#" data-num-month="12">
                Декабрь
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="badge-month-12">
                    0
                </span>
            </a>
        </li>
    </ul>

    <?php if($fullCRMAccess AND $isClosedJournal): ?>
    <div class="mb-4">
        <select name="logist" id="userTo" class="form-select mb-4 js-chosen js-closed-journal-select-user"
                data-placeholder="Выберите логиста">
            <option value="0">Выберите логиста</option>
            <?php foreach ($listManager as $manager): ?>
                <option value="<?php echo $manager['id']; ?>"
                    <?php if($manager['id'] == $selectedUser) echo 'selected'; ?>
                >
                    <?php echo $manager['surname'] .' ' .$manager['name'] .' ' .$manager['lastname']; ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <?php endif; ?>

    <form action="" class="<?php if($isClosedJournal) echo 'd-none'; ?>">
        <?php if($isROPJournal):?>
            <input type="hidden" name="rop" value="1">
        <?php endif; ?>
        <input type="hidden" name="type" value="">
        <div class="fillter-date-container mb-4">
            <input type="date" name="date-start" id="fillter-date-start" value="<?php echo $condition['dateField']['start']; ?>">
            <label for="" class="label-fillter-date">—</label>
            <input type="date" name="date-end" id="fillter-date-end" value="<?php echo $condition['dateField']['end']; ?>">
            <button class="btn btn-fillter-date" type="button" data-bs-toggle="modal" data-bs-target="#modalFillterDate">...</button>
            <button class="btn btn-fillter-all" id="btn-fillter-all"  type="button">Фильтры</button>
            <button class="btn btn-fillter-submit" id="btn-dop-setting" type="button" data-bs-toggle="modal" data-bs-target="#modalDopSetting">Расширенная настройка</button>
            <button class="btn btn-fillter-submit" id="btn-fillter-submit">Применить</button>
            <a href="/journal/manager<?php if($isROPJournal) echo '?rop=1'; ?>" class="btn btn-fillter-submit">Сбросить фильтры</a>
        </div>

        <div class="fillter-all-container d-none" data-status="0">
            <?php if($fullCRMAccess): ?>
                <label for="">Выберите логиста</label>
                <select name="logist[]" id="userTo" class="form-select mb-2 js-chosen" data-placeholder="Выберите логиста" multiple>
                <?php foreach ($listManager as $manager): ?>
                    <option value="<?php echo $manager['id']; ?>"
                        <?php foreach ($condition['id_user'] as $selectedId):
                            if($selectedId == $manager['id'])
                                echo 'selected';
                        endforeach; ?>
                    >
                        <?php echo $manager['surname'] .' ' .$manager['name'] .' ' .$manager['lastname']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <?php endif; ?>
            <label for="" class="mt-3">Выберите клиента</label>
            <select name="client[]" id="" class="form-select mb-2 js-chosen" data-placeholder="Выберите клиента" multiple>
                <?php foreach ($listClients as $client): ?>
                    <option value="<?php echo $client['id']; ?>"
                        <?php foreach ($condition['client_id_Client'] as $selectedId):
                                if($selectedId == $client['id'])
                                    echo 'selected';
                        endforeach; ?>
                    >
                        <?php echo str_replace(['"',"'", '«','»'],'',$client['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="" class="mt-3">Выберите перевозчика</label>
            <select name="carrier[]" id="" class="form-select mb-2 js-chosen" data-placeholder="Выберите перевозчика" multiple>
                <?php foreach ($listCarriers as $carrier): ?>
                    <option value="<?php echo $carrier['id']; ?>"
                        <?php foreach ($condition['carrier_id_Carrier'] as $selectedId):
                            if($selectedId == $carrier['id'])
                                echo 'selected';
                        endforeach; ?>>
                        <?php echo str_replace(['"',"'", '«','»'],'',$carrier['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>


    </form>
    <?php if($isROPJournal): ?>
        <div class="mb-2">
            <select name="" id="select-rop-logist" class="form-select">
                <option value="">Выберите логиста</option>
                <?php foreach ($arraySubordinates as $subordinate): ?>
                    <option value="<?php echo $subordinate['id'];?>"><?php echo $subordinate['name'];?></option>
                <?php endforeach; ?>
            </select>
        </div>
    <?php endif; ?>
    <div class=''>
        <input type="text" class="form-control" id="search-journal" placeholder="Введите данные для поиска...">
    </div>
    <table class="table table-striped table-bordered mt-4" id="table-1">
        <thead>
            <tr>
                <?php if($fullCRMAccess OR $isROPJournal): ?>
                    <th class="table-col-1">
                        Логист
                    </th>
                <?php endif; ?>
<!--                    <th class="table-col-1"><b>Комментарий по отмене</b></th>-->
                <th class="table-col-1">№ заявки / Направление</th>
                <th class="table-col-2">Дата заявки</th>
                <th class="table-col-3">Дата погрузки / <div>Дата разгрузки </div></th>
                <th class="table-col-4">ТТН </th>
                <th class="table-col-5">Клиент</th>
                <th class="table-col-6">Номер счета / Номер УПД</th>
                <th class="table-col-7">Общая сумма</th>
                <th class="table-col-8">Доп. прибыль</th>
                <th class="table-col-9">Факт. оплата</th>
                <th class="table-col-10">Перевозчик / контакты</th>
                <th class="table-col-11">Общая сумма</th>
                <th class="table-col-12">Доп. расходы</th>
                <th class="table-col-13">Факт. оплата</th>
                <th class="table-col-14">Доход / Маржа з.п.</th>
                <th class="table-col-17">
                    Невыполненные условия
                    <i class="bi bi-question-circle" data-bs-toggle="tooltip" data-bs-placement="top"
                        data-bs-custom-class="custom-tooltip"
                        data-bs-title='Условия которые осталось выполнить, чтобы заявка перешла в статус "Закрытые под расчет" '></i>
                </th>
                <th class="table-col-16">Комментарии</th>
            </tr>
        </thead>
        <tbody id="tbody-application">
            <?php foreach ($listApplication as $application): if($application['application_status'] == 'На проверке') continue;  ?>
        <?php $text = '<b>Л</b>';  if($application['application_section_journal'] == 2 AND
                $application['application_status_journal'] == 'Выгрузился' AND isset($application['unfulfilledConditions'])){
                foreach ($application['unfulfilledConditions'] as $item) {
                    if (stripos($item, $text) !== false) {
                        $application['application_section_journal'] = 1;
                        $application['unfulfilledConditions'] = array_filter($application['unfulfilledConditions'], function($item) {
                            return strpos($item, '(<b>Б</b>)') === false;
                        });
                        break;
                    }
                }
            } ?>

            <?php if($application['application_section_journal'] == 4) $application['application_section_journal'] = 5; ?>
            
            <tr class=" tr-application"
                data-type-app="1"
                data-app-date="<?php echo date('Y-m-d', strtotime($application['date'])); ?>"
                data-app-section-journal="<?php echo $application['application_section_journal']; ?>"
                data-app-status-journal="<?php echo $application['application_status_journal']; ?>"
                data-app-id-customer="<?php echo $application['id_customer']; ?>"
                data-app-logist-id="<?php echo $application['id_user']; ?>"
                data-app-month="<?php echo date('m', strtotime($application['date'])); ?>"
            >
                    <!-- Комментарий по отменене -->
<!--                    <td class="table-col table-col-1">-->
<!--                        <b>--><?php //echo $application['comment_cancel']; ?><!--</b>-->
<!--                    </td>-->
                <?php if($fullCRMAccess OR $isROPJournal): ?>
                    <td class="table-col table-col-1">
                        <?php echo $application['manager']; ?>
                    </td>
                <?php endif; ?>
                <td class="table-col table-col-1">
                    <!-- № заявки перевозчик / клиент -->
                    <a href="/application?id=<?php echo $application['id']; ?>" target="_blank" style="color: black;text-decoration: unset;">
                        <?php if($application['application_number'] < 500)
                            echo $application['application_number'].'-Т';
                        else echo $application['application_number'];?>

                        <?php if($application['application_number_Client'])
                            echo '/ ' .$application['application_number_Client']; ?>

                    </a>
                    <div class="">
                        <?php $textTransportation = '';
                            foreach ($application['transportation_list'] as $transportation) {
                                $city = explode(',',$transportation['city']);
                                $textTransportation .= $city[count($city) - 1].' - ';
                            }
                            $textTransportation = trim($textTransportation, ' - ');
                            echo $textTransportation;
                        ?>
                        <span class="<?php if(!$dopSetting['gruz']) echo 'd-none'; ?> text-secondary span-dop-setting-gruz" ><?php echo $application['nature_cargo_Carrier']; ?></span>

                    </div>
                </td>
                <td class="table-col table-col-2">
                    <!-- Дата заявки -->
                    <?php echo date('d.m.Y', strtotime($application['date'])); ?>
                    <span style="font-size: 12px; color: #0d6efd" class="section-application d-none">
                        <?php
                            switch ($application['application_section_journal']):
                                case 1:
                                    echo '(Актуальные)';
                                    break;
                                case 2:
                                    echo '(Завершенные)';
                                    break;
                                case 3:
                                    echo '(Закрытые под расчет)';
                                    break;
                                case 4:
                                    echo '(Срывы)';
                                    break;
                                case 5:
                                    echo '(Отмененные)';
                                    break;
                            endswitch;
                        ?>
                    </span>
                    <div style="font-size: 12px; color: #0d6efd" class="<?php if(!$isClosedJournal) echo 'section-application d-none'; ?> ">
                        <?php echo $customers[$application['id_customer'] - 1]['name']; ?>

                    </div>
                </td>
                <td class="table-col table-col-3">
                    <!-- Дата погрузки / Дата разгрузки -->
                    <?php foreach ($application['transportation_list'] as $item): ?>
                        <div><?php echo $item['date']; ?></div>
                    <?php endforeach; ?>
                    <div class="">
                        <div class="">
                            <select name="" data-id-application="<?php echo $application['id']; ?>"
                                    data-num-application="<?php echo $application['application_number']; ?>"
                                    class="form-select form-select-sm js-change-application-journal-status" <?php if ($application['application_section_journal'] > 1 OR $application['application_status_journal'] == 'Выгрузился') echo 'disabled'; ?>>
                                <option value="Е.Н.П"
                                        <?php if($application['application_status_journal'] == 'Е.Н.П') echo 'selected'; ?>
                                >Е.Н.П</option>
                                <option value="В пути"
                                    <?php if($application['application_status_journal'] == 'В пути') echo 'selected'; ?>
                                >В пути</option>

                                <option value="Выгрузился"
                                    <?php if($application['application_status_journal'] == 'Выгрузился') echo 'selected'; ?>
                                >Выгрузился</option>
                            </select>
                    </div>
                    </div>
                </td>
                <td class="table-col table-col-4">
                    <!-- ТТН -->
                </td>

                <?php
                    $carrierPaymentEvent = false;
                    $clientPaymentEvent = false;

                    foreach ($application['events_application'] as $event) {
                        if($event['event'] == 'client_payment_status')
                            $clientPaymentEvent = ['id' => $event['id'] ,'status' => $event['status']];
                        if($event['event'] == 'carrier_payment_status')
                            $carrierPaymentEvent = ['id' => $event['id'] ,'status' => $event['status']];
                    }

                ?>

                <td class="table-col table-col-5 col-client
                    <?php if($clientPaymentEvent) echo 'event-payment-' .$clientPaymentEvent['status']; ?> "
                    <?php if($clientPaymentEvent) echo 'data-id-event="'.$clientPaymentEvent['id'] .'"'; ?>
                        data-type="client"
                >
                    <!-- Название клиента -->
                    <?php echo $application['client_data']['name']; ?>
                </td>
                <td class="table-col table-col-6 col-client <?php if($clientPaymentEvent) echo 'event-payment-' .$clientPaymentEvent['status']; ?> "
                    <?php if($clientPaymentEvent) echo 'data-id-event="'.$clientPaymentEvent['id'] .'"'; ?>
                    data-type="client">
                    <!-- Номер счета / Номер УПД -->
                        <div>
                            <?php echo $application['account_number_Client']; ?>
                            <?php  if($application['account_number_Client'] !== '' AND $application['upd_number_Client'] !== '') echo ' / '; ?>
                            <?php echo $application['upd_number_Client']; ?>
                        </div>
                </td>
                <td class="table-col table-col-7 col-client <?php if($clientPaymentEvent) echo 'event-payment-' .$clientPaymentEvent['status']; ?> "
                    <?php if($clientPaymentEvent) echo 'data-id-event="'.$clientPaymentEvent['id'] .'"'; ?>
                    data-type="client">
                    <!-- Общая сумма -->
                    <div><?php echo number_format(
                            $application['transportation_cost_Client'],
                            0,
                            ',',
                            ' '
                        ); ?> ₽</div>
                    <span class="text-secondary"><?php echo $application['taxation_type_Client']; ?></span>
                </td>
                <td class="table-col table-col-8 col-client <?php if($clientPaymentEvent) echo 'event-payment-' .$clientPaymentEvent['status']; ?> "
                    <?php if($clientPaymentEvent) echo 'data-id-event="'.$clientPaymentEvent['id'] .'"'; ?>
                    data-type="client">
                    <!-- Доп прибыль -->
                    <div class="text-success" data-bs-toggle="collapse" href="#collapseProfit<?php echo $application['id']; ?>" role="button" aria-expanded="false" aria-controls="collapseProfit">
                        <?php echo number_format(
                            $application['additional_profit_sum_Client'] + $application['fines_sum'],
                            0,
                            ',',
                            ' '
                        ); ?> ₽
                        <?php if(count($application['additional_profit'])): ?>
                            <i class="bi bi-caret-down-fill text-dark"></i>
                        <?php endif; ?>
                    </div>
                    <div class="collapse" id="collapseProfit<?php echo $application['id']; ?>">
                        <?php foreach ($application['additional_profit'] as $profit): ?>
                            <div class="profit small">
                                <?php echo $profit['type'] ." (" .number_format($profit['sum'],0, ',',' ') ."₽)"; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </td>
                <td class="table-col table-col-9 col-client <?php if($clientPaymentEvent) echo 'event-payment-' .$clientPaymentEvent['status']; ?> "
                    <?php if($clientPaymentEvent) echo 'data-id-event="'.$clientPaymentEvent['id'] .'"'; ?>
                    data-type="client">
                    <!-- Фактическая сумма оплаты -->
                    <?php echo number_format(
                        $application['actual_payment_Client'],
                        0,
                        ',',
                        ' '
                    ); ?> ₽
                    <div class="">
                        <?php if($application['full_payment_date_Client']): ?>
                            (<?php echo date('d.m.Y', strtotime($application['full_payment_date_Client'])); ?>)
                        <?php endif; ?>
                    </div>
                </td>
                <td class="table-col table-col-10 col-carrier <?php if($carrierPaymentEvent) echo 'event-payment-' .$carrierPaymentEvent['status']; ?> "
                    <?php if($carrierPaymentEvent) echo 'data-id-event="'.$carrierPaymentEvent['id'] .'"'; ?>
                    data-type="carrier">
                    <!-- Название перевозчика / контактная информация -->
                    <div><?php echo $application['carrier_data']['name']; ?></div>
                    <span class="text-secondary">
                        <?php
                            $patternEmail = "/\b[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Z]{1,}\b/i";
                            $patternATI = "/(код в АТИ) [0-9]{3,10},/i";

                            $replacementEmail = "<span class='span-dop-setting-carrier-email'>\$0</span>";

                            $replacementATI = "<span class='span-dop-setting-carrier-ati'>\$0</span>";

                            if (!$dopSetting['carrier-email'])
                                $replacementEmail = "<span class='span-dop-setting-carrier-email d-none'>\$0</span>";
                            $result = preg_replace($patternEmail, $replacementEmail, $application['carrier_chosen_info']);

                            if (!$dopSetting['carrier-ati'])
                                $replacementATI = "<span class='span-dop-setting-carrier-ati d-none'>\$0</span>";
                            $result = preg_replace($patternATI, $replacementATI, $result);
                            echo $result;
                        ?>
                    </span>
                    <div>
                        <span>
                            Вод. <?php echo $application['driver_info']; ?>
                            <span class='span-dop-setting-driver-car <?php if (!$dopSetting['driver-car']) echo 'd-none'; ?> '>
                                <?php echo $application['car_info']; ?>
                            </span>
                            <div class='span-dop-setting-driver-number <?php if (!$dopSetting['driver-number']) echo 'd-none'; ?> '>
                                <?php echo $application['driver_number']; ?>
                            </div>

                        </span>
                    </div>
                </td>
                <td class="table-col table-col-11 col-carrier <?php if($carrierPaymentEvent) echo 'event-payment-' .$carrierPaymentEvent['status']; ?> "
                    <?php if($carrierPaymentEvent) echo 'data-id-event="'.$carrierPaymentEvent['id'] .'"'; ?>
                    data-type="carrier">
                    <!-- Общая сумма  -->
                    <div>
                        <?php echo number_format(
                                $application['transportation_cost_Carrier'],
                                0,
                                ',',
                                ' '
                        ); ?> ₽</div>
                    <span class="text-secondary"><?php echo $application['taxation_type_Carrier']; ?></span>

                </td>
                <td class="table-col table-col-12 col-carrier <?php if($carrierPaymentEvent) echo 'event-payment-' .$carrierPaymentEvent['status']; ?> "
                    <?php if($carrierPaymentEvent) echo 'data-id-event="'.$carrierPaymentEvent['id'] .'"'; ?>
                    data-type="carrier">
                    <!-- Доп. расходы -->
                    <div class="text-danger" data-bs-toggle="collapse" href="#collapseExpenses<?php echo $application['id']; ?>" role="button" aria-expanded="false" aria-controls="collapseExpenses">
                        - <?php echo number_format(
                            $application['additional_expenses_sum_Carrier'],
                            0,
                            ',',
                            ' '
                        ); ?> ₽
                        <?php if(count($application['additional_expenses'])): ?>
                            <i class="bi bi-caret-down-fill text-dark"></i>
                        <?php endif; ?>
                    </div>
                    <div class="collapse" id="collapseExpenses<?php echo $application['id']; ?>">
                        <?php foreach ($application['additional_expenses'] as $expenses): ?>
                            <div class="expenses small">
                                <?php
                                if(is_float($expenses['sum'])):
                                    echo $expenses['type_expenses'] ."  <br> (" .$expenses['type_payment']
                                        .' - '   .number_format($expenses['sum'],0, ',',' ') ."₽)";
                                else:
                                    echo $expenses['type_expenses'] ." <br> (" .$expenses['type_payment']
                                        .' - '    .$expenses['sum'] ."₽)";
                                endif;
                                ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </td>
                <td class="table-col table-col-13 col-carrier <?php if($carrierPaymentEvent) echo 'event-payment-' .$carrierPaymentEvent['status']; ?> "
                    <?php if($carrierPaymentEvent) echo 'data-id-event="'.$carrierPaymentEvent['id'] .'"'; ?>
                    data-type="carrier">
                    <!-- Фактическая сумма оплаты -->
                    <?php echo number_format(
                        $application['actual_payment_Carrier'],
                        0,
                        ',',
                        ' '
                    ); ?> ₽
                </td>
                <td class="table-col table-col-14">
                    <!-- Доход -->
                    <div> <?php echo number_format(
                            $application['application_walrus'],
                            0,
                            ',',
                            ' '
                        ); ?> ₽</div>
                    <!-- Маржа з.п. -->
                    <?php if($application['manager_share'] > 0): ?>
                    <span class="text-success">+ <?php echo number_format(
                            $application['manager_share'],
                            0,
                            ',',
                            ' '
                        ); ?> ₽</span>
                    <?php else: ?>
                        <span class="text-danger"><?php echo number_format(
                                $application['manager_share'],
                                0,
                                ',',
                                ' '
                            ); ?> ₽</span>
                    <?php endif; ?>
                </td>
                <!-- <td class="table-col-15"> -->
                <!-- Маржа з.п. -->

                <!-- </td> -->

                <td class="table-col-17">
                    <?php if(isset($application['unfulfilledConditions'])): ?>
                        <?php foreach ($application['unfulfilledConditions'] as $unfulfilledCondition): ?>
                            <div>- <?php echo $unfulfilledCondition; ?></div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </td>

                <td class="table-col-16 js-col-comment not-edit">

                    <div class="td-comment-logist-reduction">
                        <?php
                            $comment = $application['manager_comment'] ?? '';
                            if(mb_strlen($comment) > 70) {
                                echo mb_substr($comment, 0, 70) . '...';
                            }
                            else {
                                echo $comment;
                            }
                        ?>
                    </div>
                    <div class="td-comment-logist-full d-none">
                        <?php echo $comment; ?>
                    </div>


                    <div class="td-comment-logist-edit d-none">
                        <textarea name="" id="" data-id-application="<?php echo $application['id']; ?>" cols="30"
                                  rows="10" class="form-control js-text-area-logist-comment"><?php echo $comment; ?></textarea>
                    </div>
                    <!-- Комментарии -->



                </td>
            </tr>
        <?php endforeach; ?>


        </tbody>
    </table>
    <?php $controller->view('Journal/manager-prr-table'); ?>
    <?php $controller->view('Journal/manager-ts-table'); ?>
    <button type="button" class="d-none" id="btn-actual-date-unloading-modal" data-bs-toggle="modal" data-bs-target="#modalDateUnloading">кнопка</button>
</main>

<div class="modal fade" id="modalDopSetting" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalDopSettingLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modalDopSetting">Расширенные настройки</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="check-dop-setting-gruz"
                    <?php if ($dopSetting['gruz']) echo 'checked' ;?> >
                    <label class="form-check-label" for="check-dop-setting-gruz">
                        Номенклатура груза
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="check-dop-setting-email"
                    <?php if ($dopSetting['carrier-email']) echo 'checked';?> >
                    <label class="form-check-label" for="check-dop-setting-email">
                        Почта перевозчика
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="check-dop-setting-ati"
                        <?php if ($dopSetting['carrier-ati']) echo 'checked';?> >
                    <label class="form-check-label" for="check-dop-setting-ati">
                        Код в АТИ
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="check-dop-setting-driver-car"
                        <?php if ($dopSetting['driver-car']) echo 'checked';?> >
                    <label class="form-check-label" for="check-dop-setting-driver-car">
                        Машина водителя
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="check-dop-setting-driver-number"
                        <?php if ($dopSetting['driver-number']) echo 'checked';?> >
                    <label class="form-check-label" for="check-dop-setting-driver-number">
                        Номер водителя
                    </label>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalFillterDate" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalFillterDateLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modalFillterDate">Выбор периода</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="1c-datepicker-container">

                    <div class="year-picker bg-warning p-4 text-center mb-4" id="year-picker" data-year="2024">
                        <button id="prev-year"><</button>
                        <label for="" id="label-year" class="mx-4">2024</label>
                        <button id="next-year">></button>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="months-picker" data-type="year" style="font-size: 1.5rem;">Год</div>
                        </div>
                        <div class="col-12">
                            <div class="row m-0">
                                <div class="col-4 months-picker" data-type="month" data-month="01">январь</div>
                                <div class="col-4 months-picker" data-type="month" data-month="02">февраль</div>
                                <div class="col-4 months-picker" data-type="month" data-month="03">март</div>
                                <div class="col-4 months-picker" data-type="month" data-month="04">апрель</div>
                                <div class="col-4 months-picker" data-type="month" data-month="05">май</div>
                                <div class="col-4 months-picker" data-type="month" data-month="06">июнь</div>
                                <div class="col-4 months-picker" data-type="month" data-month="07">июль</div>
                                <div class="col-4 months-picker" data-type="month" data-month="08">август</div>
                                <div class="col-4 months-picker" data-type="month" data-month="09">сентябрь</div>
                                <div class="col-4 months-picker" data-type="month" data-month="10">октябрь</div>
                                <div class="col-4 months-picker" data-type="month" data-month="11">ноябрь</div>
                                <div class="col-4 months-picker" data-type="month" data-month="12">декабрь</div>
                            </div>
                        </div>
                        <div class="col-3 d-none" style="border-right: 2px solid green; border-left: 2px solid green;">
                            <div class="months-picker" data-type="quarter" data-quarter="1">1 квартал</div>
                            <div class="months-picker" data-type="quarter" data-quarter="2">2 квартал</div>
                            <div class="months-picker" data-type="quarter" data-quarter="3">3 квартал</div>
                            <div class="months-picker" data-type="quarter" data-quarter="4">4 квартал</div>
                        </div>
                        <div class="col-3 d-none">
                            <div class="months-picker" data-type="day">день...</div>
                            <div class="months-picker" data-type="half-year">полугодие</div>
                            <div class="months-picker" data-type="9-months">9 месяцев</div>
                            <div class="months-picker" data-type="year">год</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalDateUnloading" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalDateUnloadingLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="headerModalDateUnloading">Дата актуальной разгрузки </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" id="form-actual-unloading">
                    <input type="hidden" name="id" value="" id="actual_unloading_date-id_application">
                    <input type="hidden" name="status" value="Выгрузился">
                    <input type="hidden" name="type" value="" id="actual_unloading_type_app">
                    <div class="mb-3">
                        <label for="" class="mb-2">Укажите актуальную дату разгрузки заявки № <span id="actual_unloading_date-num_application"></span></label>
                        <input required type="date" class="form-control" name="actual_date_unloading" max="<?php echo date('Y-m-d'); ?>">
                    </div>
                    <button class="btn btn-primary w-100" id="form-actual-unloading-btn">Подтвердить</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        <?php if($controller->session->get('journalCustomer')): ?>
        $('.js-tab-task[data-id-customer="<?php echo $controller->session->get('journalCustomer') ?>"]').trigger('click');
        <?php endif; ?>

        <?php if($controller->session->get('journalSection')): ?>
        $('.js-tab-task[data-section="<?php echo $controller->session->get('journalSection') ?>"]').trigger('click');
        <?php endif; ?>

        <?php if($controller->session->get('journalTypeApp')): ?>
        $('.js-tab-task[data-type-app="<?php echo $controller->session->get('journalTypeApp') ?>"]').trigger('click');
        <?php endif; ?>
    })
</script>
<script>
    $('.js-closed-journal-select-user').change(function (){
        let id = $(this).val();

        if (id > 0)
            document.location.href = '/journal/manager?closed-months=1&user=' + id;
    })
</script>
<script>
    function countNumberApplication(){
        let arrayNumMonths = ['01','02','03','04','05','06','07','08','09','10','11','12'];
        $.each(arrayNumMonths, function(index, item){
            let tempNum = $('.tr-application[data-app-month="' + item + '"]').length;
            $('#badge-month-' + item).text(tempNum);

            if(tempNum === 0){
                $('.js-tab-months').has($('#badge-month-' + item)).addClass('inactive');
            }
        })
    }
    countNumberApplication();
</script>
<script>
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
</script>
<script>
    $('#search-journal').on('change', function (){

        let search = $(this).val();
        $('.tr-application').hide();
        $('.section-application').addClass('d-none');
        $('.js-tab-task').removeClass('active');

        

        if(search == ''){
            $('.js-tab-customer[data-id-customer="1"]').addClass('active');
            <?php if($isClosedJournal): ?>
                $('.js-tab-section[data-section="3"]').addClass('active');
                $('.js-tab-months[data-num-month="01"]').addClass('active');
                filterTable(1,3);
            <?php else: ?>

                $('.js-tab-section[data-section="1"]').addClass('active');
                filterTable(1,1);
            <?php endif; ?>

        }
        else {
            let lengthSearch = 0;
            $('.tr-application:contains(' + search + ')').show();
            $('.tr-application:contains(' + search + ')').find('.section-application').removeClass('d-none');
            lengthSearch = $('.tr-application:contains(' + search + ')').find('.section-application').length;


            if(lengthSearch == 0){
                $.ajax({
                    method: 'POST',
                    url: '/journal/manager/ajax/search-application-number',
                    data: {'search': search},
                    success: function(response){
                        $('#tbody-application').append(response);
                        $('#search-journal').trigger('change');
                    }
                });
            }
            
        }
        console.log(search);

    });

    $('.js-tab-type-app').click(function () {
        $('.js-tab-type-app').removeClass('active');
        $(this).addClass('active');

        let typeApp = $(this).data('type-app');

        $.ajax({
            method: 'POST',
            url: '/journal/ajax/set-session-filter',
            data: {type: 'type-app', 'type-app': typeApp}
        });

        $('table').addClass('d-none');

        console.log(typeApp)

        $('#table-' + typeApp).removeClass('d-none');

    })
    $('.js-tab-customer').click(function (){

        let idCustomer = $(this).data('id-customer');

        $.ajax({
            method: 'POST',
            url: '/journal/ajax/set-session-filter',
            data: {type: 'customer', idCustomer: idCustomer},
            success: function (response) {
                console.log(response)
            }
        });


        $('.js-tab-customer').removeClass('active');
        $(this).addClass('active');
        filterTable();
    });

    $('.js-tab-months').click(function (){

        $('.js-tab-months').removeClass('active');
        $(this).addClass('active');
        filterTable();
    });


    $('.js-tab-section').click(function (){

        let section = $(this).data('section');

        $.ajax({
            method: 'POST',
            url: '/journal/ajax/set-session-filter',
            data: {type: 'section', section: section}
        });

        $('.js-tab-section').removeClass('active');
        $(this).addClass('active');
        filterTable();

    });

    <?php if($isROPJournal): ?>

        $('#select-rop-logist').change(function () {
            let idUser = $(this).val();
            filterTable();
            console.log(idUser)
        })

    <?php endif; ?>

    function filterTable(numCustomer = 0, numSection = 0, month = '11'){
        let idCustomer = $('.js-tab-customer.active').data('id-customer');
        if(numCustomer > 0)
            idCustomer = numCustomer;
        let section = $('.js-tab-section.active').data('section');
        if(numSection > 0)
            section = numSection;

        // if(section === 2){
        //     $('.table-col-17').removeClass('d-none');
        // }
        // else{
        //     $('.table-col-17').addClass('d-none');
        // }

        month = $('.js-tab-months.active').data('num-month');

        let condition = {'section-journal': section, 'id-customer': idCustomer}

        <?php if($isClosedJournal): ?>
            condition = {'section-journal': section, 'month': month}
        <?php endif; ?>

        <?php if($isROPJournal): ?>
            let logist = $('#select-rop-logist').val();

            if(logist)
                condition = {'section-journal': section, 'id-customer': idCustomer, 'logist-id': logist}

        <?php endif; ?>


        let search = $('#search-journal').val();
        $('.tr-application').hide();
        let filterText = '';
        let filterArray = [];
        $.each(condition, function(index, item){
            console.log(index, item)
            filterText += `[data-app-${index}="${item}"]`;
            filterArray.push(`[data-app-${index}="${item}"]`);
            if(index === 'section-journal' && item === 3) {
                filterText += `,[data-app-${index}="6"]`;
                filterArray[0] += `,[data-app-${index}="6"]`;

            }

        })

        try {
            let user = $('#select-rop-logist').val();
            if(user > 0)
                filterText += `[data-logist-id="${user}"]`;

        }
        catch (e){
            console.log(e)
        }
        console.log(filterArray)

        let tr;
        if(search != '') {
            tr = $('.tr-application:contains(' + search + ')');

        }
        else{
            tr = $('.tr-application');
        }

        $.each(filterArray, function (index, item) {

            tr = tr.filter(item);
        });

        tr.show();


    }

    <?php if($isClosedJournal): ?>
        filterTable(1,3);
    <?php else: ?>
        filterTable(1,1);
    <?php endif; ?>

    function ajaxLoadApplications(section){
        var startTime = performance.now()
        console.log(section)
        $.ajax({
            method: 'POST',
            url: '/journal/ajax/load-applications',
            data: {section: section},
            success: function (response) {
                // console.log(response);

                response = JSON.parse(response);
                // console.log(response);

                let html = ``;

                response.forEach(function (item){

                })
                var endTime = performance.now()
                console.log(`Call to doSomething took ${endTime - startTime} milliseconds`)
                if(section < 5){
                    ajaxLoadApplications(section+1);
                }
            }
        })
    }

    // ajaxLoadApplications(1);

    // filterTable({'section-journal': 1, 'id-customer': 1});
    // table.columns.adjust().draw(false);

    $('#form-actual-unloading').submit(function (e) {
        e.preventDefault();

        let data = $(this).serializeArray();
        $('#form-actual-unloading-btn').attr('disabled',true);
        $.ajax({
            method: 'POST',
            url: '/journal/ajax/change-status-application-journal',
            data: data,
            success: function (response) {
                console.log(response)
                response = JSON.parse(response);

                $('#modalDateUnloading').find('.btn-close').trigger('click');
                $('#form-actual-unloading-btn').attr('disabled',false);

            }

        })
    })

    $('.js-change-application-journal-status').change(function () {
        let id = $(this).data('id-application');
        let status = $(this).val();
        let numApp = $(this).data('num-application');
        let $this = $(this);

        let type = $(this).data('type-app');


        if(status === "Выгрузился"){
            $('#btn-actual-date-unloading-modal').trigger('click');
            $('#actual_unloading_date-id_application').val(id);
            $('#actual_unloading_type_app').val(type);
            $('#actual_unloading_date-num_application').text(numApp);
            return;
        }

        if(! type)
            type = '';

        console.log(type)

        $.ajax({
            method: 'POST',
            url: '/journal/ajax/change-status-application-journal',
            data: {id: id, status: status,type: type},
            success: function (response){
                console.log(response)
                if(status == "Выгрузился") {
                    $('.tr-application ').has($this).data('app-status-journal', "Выгрузился");
                    $('.tr-application ').has($this).data('app-section-journal', 2);
                    filterTable();

                    console.log($('.tr-application ').has($this).data('app-section-journal'));


                }
            }
        })
    })
    $('.event-payment-0').click(function () {
        let id = $(this).data('id-event');
        let type = $(this).data('type');

        let $this = $(this);
        $.ajax({
            method: 'POST',
            url: '/journal/ajax/change-event-status',
            data: {id: id},
            success: function (response) {
                // response = JSON.parse(response);

                $('tr').has($this).find('.col-' + type).removeClass('event-payment-0');
                $('tr').has($this).find('.col-' + type).addClass('event-payment-1');
            }
        })
    })
    function changeDopSetting(name, status){
        $.ajax({
            method: 'POST',
            url: '/journal/ajax/change-dop-setting',
            data: {name: name, status: status},
            success: function (response){
                response = JSON.parse(response);
                console.log(response);

            }

        })
    }
    $('#check-dop-setting-ati').change(function () {
        $('.span-dop-setting-carrier-ati').addClass('d-none');
        let status = 0;

        if($(this).is(':checked')) {
            $('.span-dop-setting-carrier-ati').removeClass('d-none');
            status = 1;
        }

        changeDopSetting('carrier-ati', status)
    });
    $('#check-dop-setting-driver-car').change(function () {
        $('.span-dop-setting-driver-car').addClass('d-none');
        let status = 0;

        if($(this).is(':checked')) {
            $('.span-dop-setting-driver-car').removeClass('d-none');
            status = 1;
        }

        changeDopSetting('driver-car', status)
    });

    $('#check-dop-setting-driver-number').change(function () {
        $('.span-dop-setting-driver-number').addClass('d-none');
        let status = 0;

        if($(this).is(':checked')) {
            $('.span-dop-setting-driver-number').removeClass('d-none');
            status = 1;
        }

        changeDopSetting('driver-number', status)
    });

    $('#check-dop-setting-email').change(function () {
        $('.span-dop-setting-carrier-email').addClass('d-none');
        let status = 0;

        if($(this).is(':checked')) {
            $('.span-dop-setting-carrier-email').removeClass('d-none');
            status = 1;
        }

        changeDopSetting('carrier-email', status)
    });
    $('#check-dop-setting-gruz').change(function(){
        $('.span-dop-setting-gruz').addClass('d-none');
        let status = 0;

        if($(this).is(':checked')) {
            $('.span-dop-setting-gruz').removeClass('d-none');
            status = 1;
        }
        changeDopSetting('gruz', status)
    });
    $('.js-text-area-logist-comment').change(function(){
        let id = $(this).data('id-application');
        let comment = $(this).val();
        let $this = $(this);

        console.log({id:id,comment:comment})

        $.ajax({
            url:'/journal/ajax/save-manager-comment',
            method: "POST",
            data: {id:id,comment:comment},
            success: function(response){
                console.log(response);
                $('.js-col-comment').has($this).addClass('not-edit');

                if(comment.length > 70){
                    $('.js-col-comment').has($this).find('.td-comment-logist-reduction').text(comment.substr(0,70) + "...");
                }
                else{
                    $('.js-col-comment').has($this).find('.td-comment-logist-reduction').text(comment);
                }

                $('.js-col-comment').has($this).find('.td-comment-logist-full').text(comment);
                $(document).trigger('click');

                // table.columns.adjust().draw(false);
            }
        })
    })
    $('body').click(function(){
        $('.td-comment-logist-full').addClass('d-none');
        $('.td-comment-logist-edit').addClass('d-none');
        $('.td-comment-logist-reduction').removeClass('d-none');
    })
    $('.td-comment-logist-edit').on('click', function (event) {
        event.stopPropagation();
    })
    $('body').click(function(){
        $('.js-col-comment.not-edit .td-comment-logist-full').addClass('d-none');
        $('.js-col-comment.not-edit .td-comment-logist-edit').addClass('d-none')
        $('.js-col-comment.not-edit .td-comment-logist-reduction').removeClass('d-none');
    });
    $('.js-col-comment').dblclick(function(e){
        $('.td-comment-logist-full').addClass('d-none');
        $(this).removeClass('not-edit');
        e.stopPropagation();
        $('.td-comment-logist-reduction').addClass('d-none');
        $('.td-comment-logist-edit').addClass('d-none');
        $(this).find('.td-comment-logist-edit').removeClass('d-none');
        $(this).find('.td-comment-logist-reduction').addClass('d-none');
        $(this).find('.td-comment-logist-full').addClass('d-none');
    });

    $('body').on('.js-col-comment.not-edit','click' ,function(e){
        e.stopPropagation();
        console.log('js-col-comment.not-edit');
        $('.td-comment-logist-full').addClass('d-none');
        $('.td-comment-logist-edit').addClass('d-none');
        $('.td-comment-logist-reduction').removeClass('d-none');
        $(this).find('.td-comment-logist-full').removeClass('d-none');
        $(this).find('.td-comment-logist-reduction').addClass('d-none');
    });
    // var table = new DataTable('#table', {
    //     language: {
    //
    //         url: '//cdn.datatables.net/plug-ins/2.0.3/i18n/ru.json',
    //     },
    //     pageLength: 100,
    //     scrollX: true,
    //     // columnDefs: [{
    //     //     target: 2,
    //     //     render: DataTable.render.datetime( "DD.MM.YYYY" )
    //     // }],
    //     "columnDefs": [
    //         { "orderable": false, "targets": 1 },
    //         { "orderable": false, "targets": 2 },
    //         { "orderable": false, "targets": 3 },
    //         { "orderable": false, "targets": 5 },
    //         { "orderable": false, "targets": 14 },
    //     ]
    //     // { type: 'formatted-num', targets:  11}
    //     // pagingType: 'simple_numbers'
    // });
    $('.js-chosen').chosen({
        width: '100%',
        no_results_text: 'Совпадений не найдено',
        placeholder_text_single: 'Выберите сотрудника'
    });

    $('#fillter-date-start').change(function(){
        let minDate = $(this).val();

        $('#fillter-date-end').attr('min',minDate);
    });

    $('#fillter-date-end').change(function(){
        let maxDate = $(this).val();

        $('#fillter-date-start').attr('max',maxDate);
    });
    $('.fillter-date').hide();

    $('#fillter-date-select').change(function(){
        let select = $(this).val();
        $('.fillter-date').hide();

        if(select > 1 && select < 5){
            $('#fillter-date-' + select).show();
        }
        console.log(select);
    });

    $('#btn-fillter-date').click(function(){
        $('.1c-datepicker-container').removeClass('d-none');
    });

    $('.months-picker').click(function(){
        let type = $(this).data('type');

        let dateStart = '';
        let dateEnd = '';

        let valueYear = $('#year-picker').data('year');


        switch(type){
            case 'month':
                let valueMonth = $(this).data(type);
                let daysInCurrentMonth=new Date(valueYear, valueMonth, 0).getDate();

                dateStart = valueYear + '-' + valueMonth + '-01';
                dateEnd = valueYear + '-' + valueMonth + '-' + daysInCurrentMonth;

                break;
            case 'quarter':
                let valueQuarter = $(this).data(type);

                switch(valueQuarter){
                    case 1:
                        dateStart = valueYear + '-01-01';
                        dateEnd = valueYear + '-03-31';
                        break;
                    case 2:
                        dateStart = valueYear + '-04-01';
                        dateEnd = valueYear + '-06-30';
                        break;
                    case 3:
                        dateStart = valueYear + '-07-01';
                        dateEnd = valueYear + '-09-30';
                        break;
                    case 4:
                        dateStart = valueYear + '-10-01';
                        dateEnd = valueYear + '-12-31';
                        break;
                }



                break;
            case 'day':

                break;
            case 'half-year':
                dateStart = valueYear + '-01-01';
                dateEnd = valueYear + '-06-30';

                break;
            case '9-months':
                dateStart = valueYear + '-01-01';
                dateEnd = valueYear + '-09-30';

                break;
            case 'year':
                dateStart = valueYear + '-01-01';
                dateEnd = valueYear + '-12-31';

                break;

        }

        $('#fillter-date-start').val(dateStart);
        $('#fillter-date-end').val(dateEnd);

        $('.months-picker').removeClass('active');
        $(this).addClass('active');

        // $('.1c-datepicker-container').addClass('d-none');

        console.log(dateStart + " - " + dateEnd);
    });

    $('#prev-year').click(function(){
        let yearNew = $('#year-picker').data('year') - 1;
        $('#year-picker').data('year', yearNew);
        $('#label-year').text(yearNew);

    });

    $('#next-year').click(function(){
        let yearNew = $('#year-picker').data('year') + 1;
        $('#year-picker').data('year', yearNew);
        $('#label-year').text(yearNew);
    });

    $('#btn-fillter-all').click(function(){
        if($('.fillter-all-container').data('status') == 0){
            $('.fillter-all-container').data('status', 1);
            $('.fillter-all-container').removeClass('d-none');
        }
        else{
            $('.fillter-all-container').data('status', 0);
            $('.fillter-all-container').addClass('d-none');
        }
    });
</script>
</body>