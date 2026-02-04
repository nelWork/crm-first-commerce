<?php
/** @var App\User\Contoller\Common\HomeController $controller */
/** @var String $titlePage */
/** @var String $link */
/** @var array $listPayment */
/** @var array $condition */
/** @var array $listClients */
/** @var array $listManager */
/** @var array $listCarriers */
/** @var array $listFilesBankStatement */
/** @var bool $fullCRMAccess */

$sumTo = 0;
$sumFrom = 0;

foreach ($listPayment['arrayTo'] as $item){
    $sumTo += $item['Сумма'];
}

foreach ($listPayment['arrayFrom'] as $item){
    $sumFrom += $item['Сумма'];
}

//dd($listPayment,$sumTo,$sumFrom);





$controller->view('Components/head');
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
    .js-tr-application{
        cursor: pointer;
    }
    .js-tr-application.active td{
        background-color: rgba(255, 255, 0, 0.5) !important;;
    }
    .js-tab-task{
        color: #0a58ca
    }
    .custom-menu{
        position: absolute;
        z-index: 10000000;
        background-color: white;
        box-shadow: 5px 10px 10px rgba(0, 0, 0, 0.29);
        list-style: none;
        border-radius: 4px;
        padding: 4px;
        width: 200px;
    }
    .custom-menu li{
        padding: 8px;
        cursor: pointer;
        font-weight: 500;
    }
    .custom-menu li:hover{
        background-color: whitesmoke;
    }
    .dropright-menu-container{
        position: relative;
    }
    .dropright-menu{
        box-shadow: 5px 10px 10px rgba(0, 0, 0, 0.29);
        padding: 4px;
        position: absolute;
        right: -160px;
        top: -5px;
        display: none;
        background-color: white;
    }
    .dropright-menu .item{
        padding: 8px;
    }
    .dropright-menu .item:hover{
        background-color: #e3e3e3;
    }
    .dropright-menu .item.inactive{
        background-color: whitesmoke;
        cursor: default;
        display: none;
    }
    .dropright-menu-container:hover .dropright-menu,
    .dropright-menu:hover{
        display: block;
    }
    .loader-page{
        position: absolute;

        width: calc(100% - 30px);
        height: 70%;
        background-color: rgba(10, 10, 10, 0.64);
        z-index: 1111111;
    }
    .loader-page .spinner-border{
        width: 10rem;
        height: 10rem;
    }
    td{
        position: relative;
    }
    .inn{
        cursor: copy;
    }
    .inn:hover{
        color: #0A58CAFF !important;
    }
    .copy-success{
        position: absolute;
        background-color: #373737;
        font-size: 12px;
        color: white;
        padding: 4px;
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
    .table-container{
        width: 100%;
        max-width: 100%;
        max-height: 65vh;
        min-height: 65vh;
        overflow: scroll;
    }
    .table{
        width: 140%;
    }
    .filter-header-table{
        cursor: pointer;
        text-align: center;
    }
    .header-table-filter{
        cursor: pointer;
        position: relative;
    }
    .filter-body{
        padding: 10px;
        position: absolute;
        display: none;
        z-index: 100;
        background-color: white;
        margin-left: -6px;
        margin-top: 6px;
        max-height: 300px;
        overflow: auto;
        min-width: 400px;
    }
    .filter-body.active{
        display: block;
    }
    thead th {
        position: sticky; /* Делаем шапку таблицы "липкой" */
        top: 0; /* Привязываем к верхней части контейнера */
        background-color: white !important; /* Цвет фона для шапки */
        z-index: 10; /* Обеспечиваем, чтобы шапка была над остальными ячейками */

    }
    .table-col-9,.table-col-16{
        max-width: 200px;
    }
    .table-col-6-1,.table-col-1,.table-col-3{
        max-width: 120px;
    }
    .bg-inactive td{
        background-color: rgb(239, 190, 113) !important;
    }
    .js-tr-application.active.bg-inactive td {
        background-color: rgb(239, 190, 113)!important;
    }
    .square{
        width: 20px;
        height: 20px;
        content: '';
        border: 1px solid black;
        margin-right: 8px;
    }
    .table-col-2,.table-col-4{
        width: 75px;
    }
</style>
<main class="journal container-fluid p-4">
    <h1 class="text-center mb-4"><?php echo $titlePage; ?>
        <button class="btn btn-warning" id="btn-open-files" data-bs-toggle="modal" data-bs-target="#exampleModal">Открыть список загруженных файлов</button></h1>
    <div class="d-flex justify-content-between">
        <div class="my-4 w-100 p-2" style="border: 1px solid black">
            <h4 class="text-center">Памятка</h4>
            <div class="d-flex mb-2">
                <div class="square" style="background-color: green"></div>
                <div class="" style="font-weight: 600"> - При нажатии на сумму под факт. суммой оплаты, данная сумма добавится к факт. сумме оплате</div>
            </div>
            <div class="d-flex">
                <div class="square" style="background-color: rgb(239, 190, 113)"></div>
                <div class="" style="font-weight: 600"> - Заявки с этим цветом показывает что, либо заявка оплачена полностью, либо к ней уже применили действие из выписки</div>
            </div>
        </div>
    </div>
    
    <div class="table-container">
        <table class="table display table-striped table-bordered" id="table">
        <thead >
            <tr style="border: 1px solid black">
<!--                <th class="table-col-1">-->
<!--                    Логист-->
<!--                </th>-->
                <th class="table-col-2">
                    № заявки, <br> перевозчик
                    (№ заявки, клиент)
                </th>
                <th class="table-col-4">
                    Дата заявки
                </th>
                <th class="table-col-5">Дата погрузки</th>
                <th class="table-col-6">Дата разгрузки</th>
                <th class="table-col-6-1">
                    Акт. дата разгрузки
                </th>
                <th class="table-col-9">
                    Название клиента
                </th>
                <th class="table-col-10">
                    Номер счета <br> и дата
                </th>
                <th class="table-col-11">Номер УПД и дата</th>
                <th class="table-col-12">
                    Общая сумма
                </th>
                <th class="table-col-13">Сумма без НДС</th>
                <th class="table-col-14">НДС</th>
                <th class="table-col-15">
                    Факт. сумма оплаты
                </th>
                <th>
                    Основание операции (назначение платежа)
                </th>
                <th class="table-col-16">
                    Название перевозчика
                </th>
                <th class="table-col-19">
                    Общая сумма
                </th>
                <th class="table-col-20">Сумма без НДС</th>
                <th class="table-col-21">НДС</th>
                <th class="table-col-22">
                    Факт. сумма оплаты
                </th>

                <th>
                    Основание операции (назначение платежа)
                </th>

            </tr>
        </thead>
        <tbody style="position: relative; padding-top: 40px;">
        <div class="loader-page d-none">
            <div class="d-flex w-100 h-100 justify-content-center align-items-center">
                <div class="spinner-border text-light" role="status">
                    <span class="visually-hidden">Загрузка...</span>
                </div>
            </div>
        </div>
        <tr style="border: 1px solid black; background-color: darkorange">
            <td class="table-col-2">
            </td>
            <td class="table-col-4">

            </td>
            <td class="table-col-5"></td>
            <td class="table-col-6"></td>
            <td class="table-col-6-1">

            </td>
            <td class="table-col-9">

            </td>
            <td class="table-col-10">

            </td>
            <td class="table-col-11"></td>
            <td class="table-col-12">

            </td>
            <td class="table-col-13"></td>
            <td class="table-col-14"></td>
            <td class="table-col-15">

            </td>
            <td>
                <?php echo number_format($sumTo, 2, '.', ' '); ?> ₽
            </td>
            <td class="table-col-16">

            </td>
            <td class="table-col-19">

            </td>
            <td class="table-col-20"></td>
            <td class="table-col-21"></td>
            <td class="table-col-22">

            </td>

            <th>
                <?php echo number_format($sumFrom, 2, '.', ' '); ?> ₽
            </th>
        </tr>

        <?php foreach($listPayment['arrayFrom'] as $payment): $application = $payment['applicationData'];  ?>

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
            <tr style="border: 1px solid black;" class="js-tr-application tr-application
            <?php if($application['fullPaymentCarrier']) echo 'bg-inactive'; ?> <?php if($application['id'] <= 844) echo 'application-closed-manually'; ?>"
                data-application-walrus="<?php echo $application['application_walrus']; ?>"
                data-actual-payment-client="<?php echo $application['actual_payment_Client']; ?>"
                data-actual-payment-carrier="<?php echo $application['actual_payment_Carrier']; ?>"
                data-transportation-cost-client="<?php echo $application['transportation_cost_Client']; ?>"
                data-transportation-cost-carrier="<?php echo $application['transportation_cost_Carrier']; ?>"
                data-id-user="<?php echo $application['id_user']; ?>"
                data-application-number="<?php echo $application['application_number']; ?>"
                data-date-actual-unloading="<?php if($application['application_date_actual_unloading']) echo date('d.m.Y', strtotime($application['application_date_actual_unloading'])); else echo 'не указана';?>"
                data-client-id="<?php echo $application['client_id_Client'];  ?>"
                data-carrier-id="<?php echo $application['carrier_id_Carrier'];  ?>"
                data-app-date="<?php echo date('d.m.Y', strtotime($application['date'])); ?>"
                data-app-section-journal="<?php echo $application['application_section_journal']; ?>"
                data-app-status-journal="<?php echo $application['application_status_journal']; ?>"
                data-app-id-customer="<?php echo $application['id_customer']; ?>"
                data-marginality="<?php echo $application['marginality']; ?>"
                data-active="0" data-id-application="<?php echo $application['id']; ?>"
                data-app-isset-account-number-client="<?php if($application['account_number_Client'] == '') echo 0; else echo 1; ?>"
            >
<!--                <td class="table-col-1">-->
<!--                    --><?php //// echo $application['manager']; ?>
<!---->
<!--                </td>-->
                <td class="table-col-2 table-col-application-number-carrier">
                    <!-- Номер заявки, перевозчик -->
                    <a href="/application?id=<?php echo $application['id']; ?>" target="_blank" style="color: black;text-decoration: unset;">
                        <?php if($application['application_number'] < 500)
                            echo $application['application_number'].'-Т';
                        else echo $application['application_number'];?>
                    </a>
                    <?php
                    if($application['application_number_Client'])
                        echo '(' .$application['application_number_Client'] .')';
                    else
                        echo '(—)';
                    ?>
                </td>
                <td class="table-col-4">
                    <!-- Дата заявки -->
                    <?php echo date('d.m.Y', strtotime($application['date'])); ?>
                    <span style="font-size: 12px; color: #0d6efd" class="section-application">
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
                    <div style="font-size: 12px; color: #0d6efd" class="section-application">
                        <?php echo $customers[$application['id_customer'] - 1]['name']; ?>
                    </div>
                </td>
                <td class="table-col-5">
                    <!-- Дата погрузки -->
                    <?php foreach ($application['transportation_list'] as $item): if($item['direction']): ?>
                        <div><?php echo $item['date']; ?> </div>
                    <?php endif; endforeach; ?>
                </td>
                <td class="table-col-6">
                    <!-- Дата разгрузки -->
                    <?php foreach ($application['transportation_list'] as $item): if(!$item['direction']): ?>
                        <div><?php echo $item['date']; ?> </div>
                    <?php endif; endforeach; ?>
                </td>
                <td class="table-col-6-1">
                    <!-- Актуальная дата разгрузки -->
                    <?php if($application['application_date_actual_unloading'])
                        echo date('d.m.Y', strtotime($application['application_date_actual_unloading'])); ?>
                </td>
                <td class="table-col-9 col-client
                    <?php if($clientPaymentEvent) echo 'event-payment-' .$clientPaymentEvent['status']; ?> "
                    <?php if($clientPaymentEvent) echo 'data-id-event="'.$clientPaymentEvent['id'] .'"'; ?>
                    data-type="client">
                    <!-- Название клиента -->
                    <?php echo $application['client_data']['name']; ?>
                    <div><span class="inn text-secondary"><?php echo $application['client_data']['inn']; ?></span></div>
                    <?php if ($application['client_data']['format_work']): ?>
                        <div class="">(<?php echo $application['client_data']['format_work']; ?>)</div>
                    <?php endif; ?>
                </td>
                <td class="table-col-10 col-client table-col-account-number-client
                    <?php if($clientPaymentEvent) echo 'event-payment-' .$clientPaymentEvent['status']; ?> "
                    <?php if($clientPaymentEvent) echo 'data-id-event="'.$clientPaymentEvent['id'] .'"'; ?>
                    data-type="client">
                    <!-- Номер счета и дата  -->
                    <textarea id="account-number-client-<?php echo $application['id']; ?>" data-name-span="span-account-number-client"
                              data-id-application="<?php echo $application['id'] ?>" data-name-info="account_number_Client"
                              class="d-none form-control textarea-change-application-info"><?php echo $application['account_number_Client']; ?></textarea>
                    <span class="span-info" id="span-account-number-client-<?php echo $application['id']; ?>">
                        <?php echo $application['account_number_Client']; ?>
                    </span>

                    <div class="">
                        (<?php
                                if($application['account_status_Client']) echo 'Отправлен ' . date('d.m.Y', strtotime($application['date_invoice_Client']));
                                else echo 'Не отправлен';
                            ?>)
                    </div>

                </td>
                <td class="table-col-11 col-client
                    <?php if($clientPaymentEvent) echo 'event-payment-' .$clientPaymentEvent['status']; ?> "
                    <?php if($clientPaymentEvent) echo 'data-id-event="'.$clientPaymentEvent['id'] .'"'; ?>
                    data-type="client">
                    <!-- Номер УПД и дата -->
                    <textarea id="upd-number-client-<?php echo $application['id']; ?>" data-name-span="span-upd-number-client"
                              data-id-application="<?php echo $application['id'] ?>" data-name-info="upd_number_Client"
                              class="d-none form-control textarea-change-application-info"><?php echo $application['upd_number_Client']; ?></textarea>
                    <span class="span-info" id="span-upd-number-client-<?php echo $application['id']; ?>">
                        <?php echo $application['upd_number_Client']; ?>
                    </span>
                </td>
                <td class="table-col-12 col-client
                    <?php if($clientPaymentEvent) echo 'event-payment-' .$clientPaymentEvent['status']; ?> "
                    <?php if($clientPaymentEvent) echo 'data-id-event="'.$clientPaymentEvent['id'] .'"'; ?>
                    data-type="client" data-cost="<?php echo $application['transportation_cost_Client']; ?>">
                    <!-- Общая сумма -->
                    <div><?php echo number_format($application['transportation_cost_Client'],0,'.',' '); ?> ₽</div>
                    <span class="text-secondary"><?php echo $application['taxation_type_Client']; ?></span>
                </td>
                <td class="table-col-13 col-client
                    <?php if($clientPaymentEvent) echo 'event-payment-' .$clientPaymentEvent['status']; ?> "
                    <?php if($clientPaymentEvent) echo 'data-id-event="'.$clientPaymentEvent['id'] .'"'; ?>
                    data-type="client" data-cost="<?php if($application['taxation_type_Client'] == 'С НДС') echo $application['transportation_cost_Client'] / 1.2; else echo $application['transportation_cost_Client']; ?>">
                    <!-- Сумма без НДС -->
                    <?php
                    if($application['taxation_type_Client'] == 'С НДС')
                        echo number_format($application['transportation_cost_Client'] / 1.2,0,'.',' ');
                    else
                        echo number_format($application['transportation_cost_Client'],0,'.',' ');
                    ?> ₽
                </td>
                <td class="table-col-14 col-client
                    <?php if($clientPaymentEvent) echo 'event-payment-' .$clientPaymentEvent['status']; ?> "
                    <?php if($clientPaymentEvent) echo 'data-id-event="'.$clientPaymentEvent['id'] .'"'; ?>
                    data-type="client" data-cost="<?php if($application['taxation_type_Client'] == 'С НДС') echo $application['transportation_cost_Client'] / 6; else echo 0; ?>">
                    <!-- НДС -->
                    <?php
                    if($application['taxation_type_Client'] == 'С НДС')
                        echo number_format($application['transportation_cost_Client'] / 6,0,'.',' ');
                    else
                        echo number_format(0,0,'.',' ');
                    ?> ₽
                </td>
                <td class="table-col-15 col-client
                    <?php if($clientPaymentEvent) echo 'event-payment-' .$clientPaymentEvent['status']; ?> "
                    <?php if($clientPaymentEvent) echo 'data-id-event="'.$clientPaymentEvent['id'] .'"'; ?>
                    data-type="client" data-cost="<?php echo $application['actual_payment_Client']; ?>">
                    <!-- Фактическая сумма оплаты -->
                    <input type="number" min="0" id="actual-payment-client-<?php echo $application['id']; ?>" data-name-span="span-actual-payment-client"
                              data-id-application="<?php echo $application['id'] ?>" data-name-info="actual_payment_Client"
                              class="d-none form-control textarea-change-application-info">

                    <div data-bs-toggle="collapse" class="span-actual-payment-client"
                          id="span-actual-payment-client-<?php echo $application['id']; ?>" href="#collapseHistoryPayment<?php echo $application['id']; ?>"
                         role="button" aria-expanded="false" aria-controls="collapseHistoryPayment">
                        <?php echo number_format(
                            $application['actual_payment_Client'],
                            0,
                            ',',
                            ' '
                        ); ?> ₽
                        <div class="">
                            <?php if($application['full_payment_date_Client']): ?>
                                (<span class="span-date-payment"><?php echo date('d.m.Y', strtotime($application['full_payment_date_Client'])); ?></span>
                                <?php if($accessChangePayment): ?><i class="bi bi-pencil-square js-change-payment-date"></i> <?php endif; ?>)
                                <?php if($accessChangePayment): ?><input type="date" class="form-control input-change-date d-none" data-side="Client" data-id-app="<?php echo $application['id']; ?>"
                                       value="<?php echo date('d.m.Y', strtotime($application['full_payment_date_Client'])); ?>">
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                        <?php if(count($application['history_payment_Client'])): ?>
                            <i class="bi bi-caret-down-fill text-dark"></i>
                        <?php endif; ?>
                    </div>
                    <div class="collapse" id="collapseHistoryPayment<?php echo $application['id']; ?>">
                        <?php foreach ($application['history_payment_Client'] as $history): ?>
                            <div class="expenses small">
                                <?php echo number_format($history['quantity'],0, ',',' ') ."₽ ("
                                    . date('d.m.Y', strtotime($history['date'])) .')'; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>

                </td>
                <td>

                </td>
                <td class="table-col-16 col-carrier <?php if($carrierPaymentEvent) echo 'event-payment-' .$carrierPaymentEvent['status']; ?> "
                    <?php if($carrierPaymentEvent) echo 'data-id-event="'.$carrierPaymentEvent['id'] .'"'; ?>
                    data-type="carrier">
                    <!-- Название перевозчика -->
                    <?php echo $application['carrier_data']['name']; ?>
                    <div><span class="inn text-secondary"><?php echo $application['carrier_data']['inn']; ?></span></div>
                </td>
                <td class="table-col-19 col-carrier <?php if($carrierPaymentEvent) echo 'event-payment-' .$carrierPaymentEvent['status']; ?> "
                    <?php if($carrierPaymentEvent) echo 'data-id-event="'.$carrierPaymentEvent['id'] .'"'; ?>
                    data-type="carrier" data-cost="<?php echo $application['transportation_cost_Carrier']; ?>">
                    <!-- Общая сумма  -->
                    <div><?php echo number_format($application['transportation_cost_Carrier'],0,'.',' '); ?> ₽</div>
                    <span class="text-secondary"><?php echo $application['taxation_type_Carrier']; ?></span>
                </td>
                <td class="table-col-20 col-carrier <?php if($carrierPaymentEvent) echo 'event-payment-' .$carrierPaymentEvent['status']; ?> "
                    <?php if($carrierPaymentEvent) echo 'data-id-event="'.$carrierPaymentEvent['id'] .'"'; ?>
                    data-type="carrier" data-cost="<?php if($application['taxation_type_Carrier'] == 'С НДС') echo $application['transportation_cost_Carrier'] / 1.2; else echo $application['transportation_cost_Carrier']; ?>">
                    <!-- Сумма без НДС -->
                    <?php
                    if($application['taxation_type_Carrier'] == 'С НДС')
                        echo number_format($application['transportation_cost_Carrier'] / 1.2,0,'.',' ');
                    else
                        echo number_format($application['transportation_cost_Carrier'],0,'.',' ');
                    ?> ₽
                </td>
                <td class="table-col-21 col-carrier <?php if($carrierPaymentEvent) echo 'event-payment-' .$carrierPaymentEvent['status']; ?> "
                    <?php if($carrierPaymentEvent) echo 'data-id-event="'.$carrierPaymentEvent['id'] .'"'; ?>
                    data-type="carrier" data-cost="<?php if($application['taxation_type_Carrier'] == 'С НДС') echo $application['transportation_cost_Carrier'] / 6; else echo 0; ?>">
                    <!-- НДС -->
                    <?php
                    if($application['taxation_type_Carrier'] == 'С НДС')
                        echo number_format($application['transportation_cost_Carrier'] / 6,0,'.',' ');
                    else
                        echo number_format(0,0,'.',' ');
                    ?> ₽
                </td>

                <td class="table-col-22 col-carrier <?php if($carrierPaymentEvent) echo 'event-payment-' .$carrierPaymentEvent['status']; ?> "
                    <?php if($carrierPaymentEvent) echo 'data-id-event="'.$carrierPaymentEvent['id'] .'"'; ?>
                    data-type="carrier" data-cost="<?php echo $application['actual_payment_Carrier']; ?>">
                    <!-- Фактическая сумма оплаты -->
                    <input id="actual-payment-carrier-<?php echo $application['id']; ?>" data-name-span="span-actual-payment-carrier"
                              data-id-application="<?php echo $application['id'] ?>" data-name-info="actual_payment_Carrier"
                              class="d-none form-control textarea-change-application-info" value="<?php echo (float)$payment['Сумма']; ?>">
                    <div  data-bs-toggle="collapse" class="span-actual-payment-carrier" id="span-actual-payment-carrier-<?php echo $application['id']; ?>" href="#collapseHistoryPaymentCarrier-<?php echo $application['id']; ?>"
                          role="button" aria-expanded="false" aria-controls="collapseHistoryPaymentCarrier">
                        <?php echo number_format(
                            $application['actual_payment_Carrier'],
                            0,
                            ',',
                            ' '
                        ); ?> ₽
                        <div class="">
                            <?php if($application['full_payment_date_Carrier']): ?>
                                (<span class="span-date-payment">
                                    <?php echo date('d.m.Y', strtotime($application['full_payment_date_Carrier'])); ?>
                                </span>
                                <?php if($accessChangePayment): ?><i class="bi bi-pencil-square js-change-payment-date"></i> <?php endif; ?>)
                                <?php if($accessChangePayment): ?>
                                    <input type="date" class="form-control input-change-date d-none"
                                           data-side="Carrier" data-id-app="<?php echo $application['id']; ?>"
                                        value="<?php echo date('d.m.Y', strtotime($application['full_payment_date_Carrier'])); ?>">
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                        <?php if(count($application['history_payment_Carrier'])): ?>
                            <i class="bi bi-caret-down-fill text-dark"></i>
                        <?php endif; ?>
                    </div>

                    <div class="collapse" id="collapseHistoryPaymentCarrier-<?php echo $application['id']; ?>">
                        <?php foreach ($application['history_payment_Carrier'] as $history): ?>
                            <div class="expenses small">
                                <?php echo number_format($history['quantity'],0, ',',' ') ."₽ ("
                                    . date('d.m.Y', strtotime($history['date'])) .')'; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <?php if(! $application['fullPaymentCarrier']): ?>
                    <div class="js-accept-payment" data-app-id="<?php echo $application['id']; ?>"
                         data-num-doc="<?php echo $payment['Номер']; ?>"
                         data-sum="<?php echo $payment['Сумма']; ?>"
                         style="background-color: <?php if($application['MARKER']) echo 'rgb(63, 14, 204)'; else echo 'green'; ?>; color: white; cursor: pointer; font-weight: 600">
                        + <?php echo number_format($payment['Сумма'],2,'.',' '); ?>
                    </div>
                    <?php endif; ?>
                </td>

                <td style="max-width: 250px">
                    <h5 class="text-center">Сумма- <?php echo number_format($payment['Сумма'],2,'.',' '); ?>₽</h5>
                    <?php echo $payment['НазначениеПлатежа']; ?>
                </td>


            </tr>
        <?php endforeach; ?>


<!--        -->


        <?php foreach($listPayment['arrayTo'] as $payment):
            $applicationsList = $payment['applicationDataList']; foreach ($applicationsList as $key => $application ):
            $count = count($applicationsList);


        ?>

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
            <tr style="border: 1px solid black; <?php if($key < $count - 1) echo 'border-bottom:unset;'; if($key > 0) echo 'border-top:unset;';  ?>"
                class="js-tr-application tr-application
                <?php if($application['fullPaymentClient']) echo 'bg-inactive'; ?>

                <?php if($application['id'] <= 844) echo 'application-closed-manually'; ?>"
                data-application-walrus="<?php echo $application['application_walrus']; ?>"
                data-actual-payment-client="<?php echo $application['actual_payment_Client']; ?>"
                data-actual-payment-carrier="<?php echo $application['actual_payment_Carrier']; ?>"
                data-transportation-cost-client="<?php echo $application['transportation_cost_Client']; ?>"
                data-transportation-cost-carrier="<?php echo $application['transportation_cost_Carrier']; ?>"
                data-id-user="<?php echo $application['id_user']; ?>"
                data-application-number="<?php echo $application['application_number']; ?>"
                data-date-actual-unloading="<?php if($application['application_date_actual_unloading']) echo date('d.m.Y', strtotime($application['application_date_actual_unloading'])); else echo 'не указана';?>"
                data-client-id="<?php echo $application['client_id_Client'];  ?>"
                data-carrier-id="<?php echo $application['carrier_id_Carrier'];  ?>"
                data-app-date="<?php echo date('d.m.Y', strtotime($application['date'])); ?>"
                data-app-section-journal="<?php echo $application['application_section_journal']; ?>"
                data-app-status-journal="<?php echo $application['application_status_journal']; ?>"
                data-app-id-customer="<?php echo $application['id_customer']; ?>"
                data-marginality="<?php echo $application['marginality']; ?>"
                data-active="0" data-id-application="<?php echo $application['id']; ?>"
                data-app-isset-account-number-client="<?php if($application['account_number_Client'] == '') echo 0; else echo 1; ?>"
            >
<!--                <td class="table-col-1">-->
<!--                    --><?php //// echo $application['manager']; ?>
<!---->
<!--                </td>-->
                <td class="table-col-2 table-col-application-number-carrier">
                    <!-- Номер заявки, перевозчик -->
                    <a href="/application?id=<?php echo $application['id']; ?>" target="_blank" style="color: black;text-decoration: unset;">
                        <?php if($application['application_number'] < 500)
                            echo $application['application_number'].'-Т';
                        else echo $application['application_number'];?>
                    </a>
                    <?php
                        if($application['application_number_Client'])
                            echo '(' .$application['application_number_Client'] .')';
                        else
                            echo '(—)';
                    ?>
                </td>
                <td class="table-col-4">
                    <!-- Дата заявки -->
                    <?php echo date('d.m.Y', strtotime($application['date'])); ?>
                    <span style="font-size: 12px; color: #0d6efd" class="section-application">
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
                    <div style="font-size: 12px; color: #0d6efd" class="section-application">
                        <?php echo $customers[$application['id_customer'] - 1]['name']; ?>
                    </div>
                </td>
                <td class="table-col-5">
                    <!-- Дата погрузки -->
                    <?php foreach ($application['transportation_list'] as $item): if($item['direction']): ?>
                        <div><?php echo $item['date']; ?> </div>
                    <?php endif; endforeach; ?>
                </td>
                <td class="table-col-6">
                    <!-- Дата разгрузки -->
                    <?php foreach ($application['transportation_list'] as $item): if(!$item['direction']): ?>
                        <div><?php echo $item['date']; ?> </div>
                    <?php endif; endforeach; ?>
                </td>
                <td class="table-col-6-1">
                    <!-- Актуальная дата разгрузки -->
                    <?php if($application['application_date_actual_unloading'])
                        echo date('d.m.Y', strtotime($application['application_date_actual_unloading'])); ?>
                </td>
                <td class="table-col-9 col-client
                    <?php if($clientPaymentEvent) echo 'event-payment-' .$clientPaymentEvent['status']; ?> "
                    <?php if($clientPaymentEvent) echo 'data-id-event="'.$clientPaymentEvent['id'] .'"'; ?>
                    data-type="client">
                    <!-- Название клиента -->
                    <?php echo $application['client_data']['name']; ?>
                    <div><span class="inn text-secondary"><?php echo $application['client_data']['inn']; ?></span></div>
                    <?php if ($application['client_data']['format_work']): ?>
                        <div class="">(<?php echo $application['client_data']['format_work']; ?>)</div>
                    <?php endif; ?>
                </td>
                <td class="table-col-10 col-client table-col-account-number-client
                    <?php if($clientPaymentEvent) echo 'event-payment-' .$clientPaymentEvent['status']; ?> "
                    <?php if($clientPaymentEvent) echo 'data-id-event="'.$clientPaymentEvent['id'] .'"'; ?>
                    data-type="client">
                    <!-- Номер счета и дата  -->
                    <textarea id="account-number-client-<?php echo $application['id']; ?>" data-name-span="span-account-number-client"
                              data-id-application="<?php echo $application['id'] ?>" data-name-info="account_number_Client"
                              class="d-none form-control textarea-change-application-info"><?php echo $application['account_number_Client']; ?></textarea>
                    <span class="span-info" id="span-account-number-client-<?php echo $application['id']; ?>">
                        <?php echo $application['account_number_Client']; ?>
                    </span>

                    <div class="">
                        (<?php
                        if($application['account_status_Client']) echo 'Отправлен ' . date('d.m.Y', strtotime($application['date_invoice_Client']));
                        else echo 'Не отправлен';
                        ?>)
                    </div>

                </td>
                <td class="table-col-11 col-client
                    <?php if($clientPaymentEvent) echo 'event-payment-' .$clientPaymentEvent['status']; ?> "
                    <?php if($clientPaymentEvent) echo 'data-id-event="'.$clientPaymentEvent['id'] .'"'; ?>
                    data-type="client">
                    <!-- Номер УПД и дата -->
                    <textarea id="upd-number-client-<?php echo $application['id']; ?>" data-name-span="span-upd-number-client"
                              data-id-application="<?php echo $application['id'] ?>" data-name-info="upd_number_Client"
                              class="d-none form-control textarea-change-application-info"><?php echo $application['upd_number_Client']; ?></textarea>
                    <span class="span-info" id="span-upd-number-client-<?php echo $application['id']; ?>">
                        <?php echo $application['upd_number_Client']; ?>
                    </span>
                </td>
                <td class="table-col-12 col-client
                    <?php if($clientPaymentEvent) echo 'event-payment-' .$clientPaymentEvent['status']; ?> "
                    <?php if($clientPaymentEvent) echo 'data-id-event="'.$clientPaymentEvent['id'] .'"'; ?>
                    data-type="client" data-cost="<?php echo $application['transportation_cost_Client']; ?>">
                    <!-- Общая сумма -->
                    <div><?php echo number_format($application['transportation_cost_Client'],0,'.',' '); ?> ₽</div>
                    <span class="text-secondary"><?php echo $application['taxation_type_Client']; ?></span>
                </td>
                <td class="table-col-13 col-client
                    <?php if($clientPaymentEvent) echo 'event-payment-' .$clientPaymentEvent['status']; ?> "
                    <?php if($clientPaymentEvent) echo 'data-id-event="'.$clientPaymentEvent['id'] .'"'; ?>
                    data-type="client" data-cost="<?php if($application['taxation_type_Client'] == 'С НДС') echo $application['transportation_cost_Client'] / 1.2; else echo $application['transportation_cost_Client']; ?>">
                    <!-- Сумма без НДС -->
                    <?php
                    if($application['taxation_type_Client'] == 'С НДС')
                        echo number_format($application['transportation_cost_Client'] / 1.2,0,'.',' ');
                    else
                        echo number_format($application['transportation_cost_Client'],0,'.',' ');
                    ?> ₽
                </td>
                <td class="table-col-14 col-client
                    <?php if($clientPaymentEvent) echo 'event-payment-' .$clientPaymentEvent['status']; ?> "
                    <?php if($clientPaymentEvent) echo 'data-id-event="'.$clientPaymentEvent['id'] .'"'; ?>
                    data-type="client" data-cost="<?php if($application['taxation_type_Client'] == 'С НДС') echo $application['transportation_cost_Client'] / 6; else echo 0; ?>">
                    <!-- НДС -->
                    <?php
                    if($application['taxation_type_Client'] == 'С НДС')
                        echo number_format($application['transportation_cost_Client'] / 6,0,'.',' ');
                    else
                        echo number_format(0,0,'.',' ');
                    ?> ₽
                </td>
                <td class="table-col-15 col-client
                    <?php if($clientPaymentEvent) echo 'event-payment-' .$clientPaymentEvent['status']; ?> "
                    <?php if($clientPaymentEvent) echo 'data-id-event="'.$clientPaymentEvent['id'] .'"'; ?>
                    data-type="client" data-cost="<?php echo $application['actual_payment_Client']; ?>">
                    <!-- Фактическая сумма оплаты -->
                    <input type="number" min="0" id="actual-payment-client-<?php echo $application['id']; ?>" data-name-span="span-actual-payment-client"
                           data-id-application="<?php echo $application['id'] ?>" data-name-info="actual_payment_Client"
                           value="<?php if(!$application['inComposition']) echo (float)$payment['Сумма'];
                           else echo (float)$application['money_received']; ?>"
                           class="d-none form-control textarea-change-application-info">

                    <div data-bs-toggle="collapse" class="span-actual-payment-client"
                         id="span-actual-payment-client-<?php echo $application['id']; ?>" href="#collapseHistoryPayment<?php echo $application['id']; ?>"
                         role="button" aria-expanded="false" aria-controls="collapseHistoryPayment">
                        <?php echo number_format(
                            $application['actual_payment_Client'],
                            0,
                            ',',
                            ' '
                        ); ?> ₽
                        <div class="">
                            <?php if($application['full_payment_date_Client']): ?>
                                (<span class="span-date-payment"><?php echo date('d.m.Y', strtotime($application['full_payment_date_Client'])); ?></span>
                                <?php if($accessChangePayment): ?><i class="bi bi-pencil-square js-change-payment-date"></i> <?php endif; ?>)
                                <?php if($accessChangePayment): ?><input type="date" class="form-control input-change-date d-none" data-side="Client" data-id-app="<?php echo $application['id']; ?>"
                                                                         value="<?php echo date('d.m.Y', strtotime($application['full_payment_date_Client'])); ?>">
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                        <?php if(count($application['history_payment_Client'])): ?>
                            <i class="bi bi-caret-down-fill text-dark"></i>
                        <?php endif; ?>
                    </div>
                    <div class="collapse" id="collapseHistoryPayment<?php echo $application['id']; ?>">
                        <?php foreach ($application['history_payment_Client'] as $history): ?>
                            <div class="expenses small">
                                <?php echo number_format($history['quantity'],0, ',',' ') ."₽ ("
                                    . date('d.m.Y', strtotime($history['date'])) .')'; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <?php if(! $application['fullPaymentClient']): ?>
                    <div class="js-accept-payment-client" data-app-id="<?php echo $application['id']; ?>" data-num-doc="<?php echo $payment['Номер']; ?>"
                         data-sum="<?php if(!$application['inComposition']) echo $payment['Сумма']; else echo $application['money_received']; ?>"
                         style="background-color: green; color: white; cursor: pointer; font-weight: 600">
                        + <?php if(!$application['inComposition']) echo number_format($payment['Сумма'],2,'.',' ');
                        else echo number_format($application['money_received'],2,'.',' '); ?>
                    </div>
                    <?php endif; ?>

                </td>
                <?php if($key == 0): ?>
                    <td rowspan="<?php echo $count; ?>" style="vertical-align: middle; max-width: 250px">
                        <h5 class="text-center">Сумма- <?php echo number_format($payment['Сумма'],2,'.',' '); ?>₽</h5>

                        <?php echo $payment['НазначениеПлатежа']; ?>
                    </td>
                <?php endif; ?>
                <td class="table-col-16 col-carrier <?php if($carrierPaymentEvent) echo 'event-payment-' .$carrierPaymentEvent['status']; ?> "
                    <?php if($carrierPaymentEvent) echo 'data-id-event="'.$carrierPaymentEvent['id'] .'"'; ?>
                    data-type="carrier">
                    <!-- Название перевозчика -->
                    <?php echo $application['carrier_data']['name']; ?>
                    <div><span class="inn text-secondary"><?php echo $application['carrier_data']['inn']; ?></span></div>
                </td>
                <td class="table-col-19 col-carrier <?php if($carrierPaymentEvent) echo 'event-payment-' .$carrierPaymentEvent['status']; ?> "
                    <?php if($carrierPaymentEvent) echo 'data-id-event="'.$carrierPaymentEvent['id'] .'"'; ?>
                    data-type="carrier" data-cost="<?php echo $application['transportation_cost_Carrier']; ?>">
                    <!-- Общая сумма  -->
                    <div><?php echo number_format($application['transportation_cost_Carrier'],0,'.',' '); ?> ₽</div>
                    <span class="text-secondary"><?php echo $application['taxation_type_Carrier']; ?></span>
                </td>
                <td class="table-col-20 col-carrier <?php if($carrierPaymentEvent) echo 'event-payment-' .$carrierPaymentEvent['status']; ?> "
                    <?php if($carrierPaymentEvent) echo 'data-id-event="'.$carrierPaymentEvent['id'] .'"'; ?>
                    data-type="carrier" data-cost="<?php if($application['taxation_type_Carrier'] == 'С НДС') echo $application['transportation_cost_Carrier'] / 1.2; else echo $application['transportation_cost_Carrier']; ?>">
                    <!-- Сумма без НДС -->
                    <?php
                    if($application['taxation_type_Carrier'] == 'С НДС')
                        echo number_format($application['transportation_cost_Carrier'] / 1.2,0,'.',' ');
                    else
                        echo number_format($application['transportation_cost_Carrier'],0,'.',' ');
                    ?> ₽
                </td>
                <td class="table-col-21 col-carrier <?php if($carrierPaymentEvent) echo 'event-payment-' .$carrierPaymentEvent['status']; ?> "
                    <?php if($carrierPaymentEvent) echo 'data-id-event="'.$carrierPaymentEvent['id'] .'"'; ?>
                    data-type="carrier" data-cost="<?php if($application['taxation_type_Carrier'] == 'С НДС') echo $application['transportation_cost_Carrier'] / 6; else echo 0; ?>">
                    <!-- НДС -->
                    <?php
                    if($application['taxation_type_Carrier'] == 'С НДС')
                        echo number_format($application['transportation_cost_Carrier'] / 6,0,'.',' ');
                    else
                        echo number_format(0,0,'.',' ');
                    ?> ₽
                </td>

                <td class="table-col-22 col-carrier <?php if($carrierPaymentEvent) echo 'event-payment-' .$carrierPaymentEvent['status']; ?> "
                    <?php if($carrierPaymentEvent) echo 'data-id-event="'.$carrierPaymentEvent['id'] .'"'; ?>
                    data-type="carrier" data-cost="<?php echo $application['actual_payment_Carrier']; ?>">
                    <!-- Фактическая сумма оплаты -->
                    <input id="actual-payment-carrier-<?php echo $application['id']; ?>" data-name-span="span-actual-payment-carrier"
                           data-id-application="<?php echo $application['id'] ?>" data-name-info="actual_payment_Carrier"
                           class="d-none form-control textarea-change-application-info" >
                    <div  data-bs-toggle="collapse" class="span-actual-payment-carrier" id="span-actual-payment-carrier-<?php echo $application['id']; ?>" href="#collapseHistoryPaymentCarrier-<?php echo $application['id']; ?>"
                          role="button" aria-expanded="false" aria-controls="collapseHistoryPaymentCarrier">
                        <?php echo number_format(
                            $application['actual_payment_Carrier'],
                            0,
                            ',',
                            ' '
                        ); ?> ₽
                        <div class="">
                            <?php if($application['full_payment_date_Carrier']): ?>
                                (<span class="span-date-payment">
                                    <?php echo date('d.m.Y', strtotime($application['full_payment_date_Carrier'])); ?>
                                </span>
                                <?php if($accessChangePayment): ?><i class="bi bi-pencil-square js-change-payment-date"></i> <?php endif; ?>)
                                <?php if($accessChangePayment): ?>
                                    <input type="date" class="form-control input-change-date d-none"
                                           data-side="Carrier" data-id-app="<?php echo $application['id']; ?>"
                                           value="<?php echo date('d.m.Y', strtotime($application['full_payment_date_Carrier'])); ?>">
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                        <?php if(count($application['history_payment_Carrier'])): ?>
                            <i class="bi bi-caret-down-fill text-dark"></i>
                        <?php endif; ?>
                    </div>

                    <div class="collapse" id="collapseHistoryPaymentCarrier-<?php echo $application['id']; ?>">
                        <?php foreach ($application['history_payment_Carrier'] as $history): ?>
                            <div class="expenses small">
                                <?php echo number_format($history['quantity'],0, ',',' ') ."₽ ("
                                    . date('d.m.Y', strtotime($history['date'])) .')'; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>

                </td>
                <?php if($key == 0): ?>
                    <td rowspan="<?php echo $count; ?>" style="vertical-align: middle; max-width: 250px">
                    </td>
                <?php endif; ?>





            </tr>
        <?php endforeach; endforeach; ?>

        </tbody>
    </table>
    </div>

</main>


<ul id="custom-menu-tr-application" class="custom-menu" style="display:none;">
    <?php if($accessChangePayment): ?>
    <li class="js-change-payment-client dropright-menu-container">
        Оплата клиентом <i class="bi bi-caret-right-fill"></i>
        <div class="dropright-menu">
            <div class="item inactive">Ожидается счет</div>
            <div class="item inactive">Запрошен</div>
            <div class="item inactive">Сформирован</div>
            <div class="item inactive">Отправлено клиенту</div>
            <div class="item js-paid-cancel text-danger" data-name="client">Отменить оплату</div>
            <div class="item js-paid-full " data-name="client">Оплачено полностью</div>
            <div class="item single js-change-application-info" data-name="actual-payment-client">Оплачено частично</div>
        </div>
    </li>
    <?php endif; ?>
    <li class="js-change-application-info single" data-name="account-number-client">Ввести номер счета клиента</li>
    <li class="js-change-application-info single" data-name="upd-number-client">Ввести номер УПД клиента</li>
    <?php if($accessChangePayment): ?>
    <li class="js-change-payment-carrier dropright-menu-container">
        Оплата перевозчику <i class="bi bi-caret-right-fill"></i>
        <div class="dropright-menu">
            <div class="item inactive">Ожидается счет</div>
            <div class="item inactive">Запрошен</div>
            <div class="item inactive">Сформирован</div>
            <div class="item inactive">Отправлено клиенту</div>
            <div class="item js-paid-cancel text-danger" data-name="carrier">Отменить оплату</div>
            <div class="item js-paid-full" data-name="carrier">Оплачено полностью</div>
            <div class="item single js-change-application-info" data-name="actual-payment-carrier">Оплачено частично</div>
        </div>
    </li>
    <?php endif; ?>
<!--    <li class="js-change-application-info single" data-name="account-number-carrier">Ввести номер счета перевозчика</li>-->
<!--    <li class="js-change-application-info single" data-name="upd-number-carrier">Ввести номер УПД перевозчика</li>-->
</ul>


<style>
    .file-container {
        display: flex;
        flex-direction: column;
        margin: auto;
        max-height: 80vh;
        overflow: auto;
    }
    .file {
        display: flex;
        align-items: center;
        padding: 10px;
        border-bottom: 1px solid #ddd;
        cursor: pointer;
    }
    .file.active{
        background-color: darkorange;
    }
    .file:hover{
        background-color: whitesmoke;
    }
    .file-icon {
        font-size: 2.5rem;
        margin-right: 0.5rem;
    }
    .file-info {
        flex-grow: 1;
    }
    .file-name {
        font-weight: bold;
        font-size: 1.25rem;
    }
    .file-dates {
        font-size: 1em;
        font-weight: 600;
        color: gray;
    }
    .dropzone {
        border: 2px dashed #007bff;
        padding: 20px;
        text-align: center;
        cursor: pointer;
        margin-bottom: 15px;
        transition: 0.3s;
    }
    .dropzone.dragover {
        background-color: #e9ecef;
    }

    /* Стилизация прогресса */
    .progress {
        height: 10px;
        margin-top: 10px;
    }
    .progress-bar {
        background-color: #28a745;
    }
</style>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen" style="max-width: 100%">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Загруженные файлы</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="dropzone" class="dropzone">
                    Перетащите файл сюда для загрузки
                </div>

                <!-- Прогресс загрузки -->
                <div id="uploadProgress" class="progress" style="display: none;">
                    <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                </div>

                <div class="file-container">
                    <?php foreach ($listFilesBankStatement as $file): ?>
                        <div class="file js-file-export <?php if($controller->request->input('id') == $file['id']) echo 'active'; ?>" data-id-file="<?php echo $file['id']; ?>">
                            <i class="bi bi-file-earmark-font file-icon"></i>
                            <div class="file-info">
                                <div class="file-name"><?php echo $file['name']; ?>.txt</div>
                                <div class="file-dates">Загружено:
                                    <?php echo date('d.m.Y H:i:s',strtotime($file['datetime_upload'])); ?>
                                    | Последнее открытие: <?php echo date('d.m.Y H:i:s',strtotime($file['datetime_last_open'])); ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        let dropzone = $("#dropzone");

        // События Drag & Drop
        dropzone.on("dragover", function(event) {
            event.preventDefault();
            $(this).addClass("dragover");
        });

        dropzone.on("dragleave", function(event) {
            event.preventDefault();
            $(this).removeClass("dragover");
        });

        dropzone.on("drop", function(event) {
            event.preventDefault();
            $(this).removeClass("dragover");

            let files = event.originalEvent.dataTransfer.files;
            uploadFile(files[0]);
        });

        function uploadFile(file) {
            let formData = new FormData();
            formData.append("file", file);

            // Показываем прогресс загрузки
            $("#uploadProgress").show();
            $(".progress-bar").css("width", "0%");

            $.ajax({
                url: "/journal/parse-txt", // Файл загрузки на сервере
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                xhr: function() {
                    let xhr = new window.XMLHttpRequest();
                    xhr.upload.addEventListener("progress", function(evt) {
                        if (evt.lengthComputable) {
                            let percentComplete = (evt.loaded / evt.total) * 100;
                            $(".progress-bar").css("width", percentComplete + "%");
                        }
                    }, false);
                    return xhr;
                },
                success: function(response) {
                    $("#uploadProgress").hide();
                    // Обновляем список файлов

                    let data = JSON.parse(response);

                    let htmlFile = `<div class="file js-file-export" data-id-file="${data['id']}">
                            <i class="bi bi-file-earmark-font file-icon"></i>
                            <div class="file-info">
                                <div class="file-name">${data['name']}.txt</div>
                                <div class="file-dates">Загружено:
                                    ${data['datetime_upload']}
                                    | Последнее открытие: ${data['datetime_last_open']}
                                </div>
                            </div>
                        </div>`;

                    $(".file-container").prepend(htmlFile);
                },
                error: function() {
                    alert("Ошибка загрузки файла!");
                    $("#uploadProgress").hide();
                }
            });
        }
    });
</script>
<script>
    $('body').on('dblclick','.js-file-export',function () {
        let id = $(this).data('id-file');

        document.location.href = '/journal/parse-txt?id=' + id;
    })
</script>

<script>

    $('.js-accept-payment-client').click(function () {
        if(confirm('Подтвердите своё действие')){
            let idApp = $(this).data('app-id');
            let numDoc = $(this).data('num-doc');
            $('#actual-payment-client-' + idApp).trigger('change');
            $('.js-tr-application').has(this).addClass('bg-inactive');



            $(this).addClass('d-none');

            // $.ajax({
            //     url: '/journal/ajax/add-num-doc-payment-parser',
            //     method: 'POST',
            //     data:{numDoc:numDoc},
            //     success: function (response) {
            //         console.log(response)
            //     }
            // });
        }
    })
    $('.js-accept-payment').click(function () {
        if(confirm('Подтвердите своё действие')){
            let idApp = $(this).data('app-id');
            let numDoc = $(this).data('num-doc');
            $('#actual-payment-carrier-' + idApp).trigger('change');
            $('.js-tr-application').has(this).addClass('bg-inactive');
            $(this).addClass('d-none');

            $.ajax({
                url: '/journal/ajax/add-num-doc-payment-parser',
                method: 'POST',
                data:{numDoc:numDoc},
                success: function (response) {
                    console.log(response)
                }
            });
        }
    })
</script>
<script>
    $('.js-change-payment-date').click(function () {
        $('td').has(this).find('.input-change-date').removeClass('d-none');
    });
    $('.input-change-date').change(function () {
        let id = $(this).data('id-app');
        let side = $(this).data('side');
        let value = $(this).val();


        let $this = $(this);

        $.ajax({
            method: 'POST',
            url: '/journal/ajax/change-payment-date',
            data: {id:id, side: side, value: value},
            success: function (response) {
                console.log(response)
                response = JSON.parse(response);

                if(response['status']){
                    $('td').has($this).find('.span-date-payment').text(response['date']);
                    $this.addClass('d-none');
                }
            }
        })


        console.log({id:id,side: side, value: value})
    })
</script>
<script>
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
</script>
<script>
    $('.filter-head').click(function (){
        $('.filter-body').removeClass('active');
        $('.header-table-filter').has(this).find('.filter-body').toggleClass('active');
    });

    $('.js-filter-header-table').change(function () {

        filterHeaderTable()
    });
    $('.js-search-in-filter').on('input', function () {
        $('.filter-body').has(this).find('.form-check').hide();
        let search = $(this).val();
        $('.filter-body').has(this).find('.form-check:contains(' + search.toUpperCase() + ')').show();

    });
    // todo закрытые фильтра столбцов
    $(document).on('click', function(event) {
        if ($(event.target).closest('.filter-body.active').length || $(event.target).is('.filter-head')){ console.log($(event.target)); return}; // Ignore clicks on the element itself
        console.log($(event.target));
        $('.filter-body').removeClass('active');
    });

    function updateFilterVisibility(normal) {
        // Для каждого фильтра
        $('.js-filter-header-table').each(function() {
            let filterName = $(this).data('name-col');
            let filterValue = $(this).val();

            // Проверяем, есть ли хотя бы одна строка с таким значением, и если нет, скрываем чекбокс фильтра
            let hasActiveValues = false;

            // Проходим по строкам таблицы и проверяем, есть ли строки с таким значением
            normal.each(function() {
                if ($(this).data(filterName) == filterValue && $(this).data('active') !== "0") {
                    hasActiveValues = true;
                }
            });

            if (hasActiveValues) {
                $(this).closest('.form-check').show(); // Показываем фильтр
            } else {
                $(this).closest('.form-check').hide(); // Скрываем фильтр
            }
        });
    }
    // updateFilterVisibility();

    function filterHeaderTable(changeSection = 0){

        let arrayNameCol = [
            'application-number','client-id',
            'carrier-id','app-date',
            'date-actual-unloading','id-user',
            'transportation-cost-client', 'transportation-cost-carrier',
            'actual-payment-client', 'actual-payment-carrier', 'app-isset-account-number-client',
            'application-walrus','marginality'
        ];

        let array = [];

        $.each(arrayNameCol,function (index,item) {
            let value = [];
            let typeFilter = '';
            $('.js-filter-header-table[data-name-col="' + item +'"]:checked').each(function () {
                value.push($(this).val());
                typeFilter = $(this).data('type-filter')
            });
            if(value.length > 0)
                array.push({
                    nameCol: item,
                    typeFilter: typeFilter,
                    value: value
                });
        });

        let arrayFilter = [];

        $.each(array, function (index, item) {
            let filter = '';
            $.each(item['value'], function (indexVal, itemVal) {
                filter += `[data-${item['nameCol']}="${itemVal}"],`;
            });
            filter = filter.slice(0, -1);
            arrayFilter.push(filter);
        });

        console.log(arrayFilter);


        $('.js-tr-application').hide();


        let idCustomer = $('.js-tab-customer.active').data('id-customer');
        let section = $('.js-tab-section.active').data('section');

        let normal = $(`.js-tr-application[data-app-id-customer="${idCustomer}"][data-app-section-journal="${section}"]`);

        // let normal = $(`.js-tr-application`);
        console.log(arrayFilter);
        $.each(arrayFilter, function (index, item) {
            normal = normal.filter(item);
        });

        // normal.find('.section-application').removeClass('d-none');
        normal.show().find('.section-application').removeClass('d-none');

        countAllSum();
        if(changeSection)
            updateFilterVisibility(normal);
    }

    $('#reset-search').click(function () {
        $('#search-journal').val('');
        $('#select-search').val('0');
        $('#search-journal').trigger('input');
    });
    $('#search-journal').on('change', function (){

        let search = $(this).val();
        let selectSearch = $('#select-search').val();
        let find = 'application_number';

        $('.tr-application').hide();
        $('.section-application').addClass('d-none');
        $('.js-tab-task').removeClass('active');

        if(search == ''){
            $('.js-tab-customer[data-id-customer="1"]').addClass('active');
            $('.js-tab-section[data-section="1"]').addClass('active');
            filterTable(1,1);
        }
        else {
            let lengthSearch = 0;
            switch (selectSearch){
                case '0':
                    $('.tr-application:contains(' + search + ')').show();
                    $('.tr-application:contains(' + search + ')').find('.section-application').removeClass('d-none');
                    lengthSearch = $('.tr-application:contains(' + search + ')').find('.section-application').length;
                    break;

                case '1':
                    $('.tr-application .table-col-application-number-carrier:contains(' + search + ')').show().find('.section-application').removeClass('d-none');
                    lengthSearch = $('.tr-application .table-col-application-number-carrier:contains(' + search + ')').find('.section-application').length;
                    break;

                case '2':
                    $('.tr-application .table-col-application-number-client:contains(' + search + ')').show().find('.section-application').removeClass('d-none');
                    lengthSearch = $('.tr-application .table-col-application-number-client:contains(' + search + ')').find('.section-application').length;
                    find = 'application_number_client';
                    break;
                case '3':
                    $('.tr-application .table-col-account-number-client:contains(' + search + ')').show();
                    $('.tr-application .table-col-account-number-client:contains(' + search + ')').find('.section-application').removeClass('d-none');
                    lengthSearch = $('.tr-application .table-col-account-number-client:contains(' + search + ')').find('.section-application').length;
                    find = 'account_number_Client';
                    break;
            }


            if(! lengthSearch){
                $.ajax({
                    method: 'POST',
                    url: '/journal/ajax/search-application-number',
                    data: {search: search,field: find},
                    success: function (response){
                        console.log(response);

                        let datas = JSON.parse(response);

                        if(datas['status']){
                            datas = datas['applications'];
                            console.log(datas)
                            $.each(datas, function (index,item){
                                console.log(item)
                                 let data = item;

                            let htmlNewApplication = ` <tr class="js-tr-application tr-application"
                                data-app-date="${data['date']}"
                                data-app-section-journal="${data['application_section_journal']}"
                                data-app-status-journal="${data['application_status_journal']}"
                                data-app-id-customer="${data['id_customer']}"
                                data-active="0" data-id-application="${data['id']}"
                                data-app-isset-account-number-client=""
                            >
                                <td class="table-col-1">
                                    <!-- Логист -->
                                    ${data['manager']}
                                </td>
                                <td class="table-col-2">
                                    <!-- Номер заявки, перевозчик -->
                                    <a href="/application?id=${data['id']}" target="_blank" style="color: black;text-decoration: unset;">
                                        ${data['application_number']}
                                    </a>
                                </td>
                                <td class="table-col-3">
                                    <!-- Номер заявки, клиента -->
                                    ${data['application_number_Client']}

                                </td>
                                <td class="table-col-4">
                                    <!-- Дата заявки -->
                                    ${data['date']}
                                    <span style="font-size: 12px; color: #0d6efd" class="section-application">
                                        ${data['application_section_journal_name']}
                                    </span>
                                    <div style="font-size: 12px; color: #0d6efd" class="section-application">
                                        ${data['id_customer_name']}
                                    </div>
                                </td>
                                <td class="table-col-5">
                                    <!-- Дата погрузки -->
                                    ${data['date-loading']}
                                </td>
                                <td class="table-col-6">
                                    <!-- Дата разгрузки -->
                                    ${data['date-unloading']}
                                </td>
                                <td class="table-col-6-1">
                                    <!-- Актуальная дата разгрузки -->
                                    ${data['application_date_actual_unloading']}
                                </td>
                                <td class="table-col-7">
                                    <!-- ТТН -->
                                </td>
                                <td class="table-col-8">
                                    <!-- ТТН отправлено -->
                                </td>
                                <td class="table-col-9 col-client ${data['event_class_Client']} "
                                    <?php if($clientPaymentEvent) echo 'data-id-event="'.$clientPaymentEvent['id'] .'"'; ?>
                                    data-type="client">
                                    <!-- Название клиента -->
                                    ${data['client_data']['name']}
                                    <div><span class="inn text-secondary">${data['client_data']['inn']}</span></div>
                                    <div class="">(${data['client_data']['format_work']})</div>
                                </td>
                                <td class="table-col-10 col-client ${data['event_class_Client']} "
                                    <?php if($clientPaymentEvent) echo 'data-id-event="'.$clientPaymentEvent['id'] .'"'; ?>
                                    data-type="client">
                                    <!-- Номер счета и дата  -->
                                    <textarea id="account-number-client-${data['id']}" data-name-span="span-account-number-client"
                                              data-id-application="${data['id']}" data-name-info="account_number_Client"
                                              class="d-none form-control textarea-change-application-info">${data['account_number_Client']}</textarea>
                                    <span class="span-info" id="span-account-number-client-${data['id']}">
                                        ${data['account_number_Client']}
                                    </span>
                                </td>
                                <td class="table-col-11 col-client  ${data['event_class_Client']}" data-type="client">
                                    <!-- Номер УПД и дата -->
                                    <textarea id="upd-number-client-${data['id']}" data-name-span="span-upd-number-client"
                                              data-id-application="${data['id']}" data-name-info="upd_number_Client"
                                              class="d-none form-control textarea-change-application-info">${data['upd_number_Client']}</textarea>
                                    <span class="span-info" id="span-upd-number-client-${data['id']}">
                                        ${data['upd_number_Client']}
                                    </span>
                                </td>
                                <td class="table-col-12 col-client ${data['event_class_Client']} "
                                    <?php if($clientPaymentEvent) echo 'data-id-event="'.$clientPaymentEvent['id'] .'"'; ?>
                                    data-type="client" data-cost="${data['transportation_cost_Client_normal']}">
                                    <!-- Общая сумма -->
                                    <div>${data['transportation_cost_Client']} ₽</div>
                                    <span class="text-secondary">${data['taxation_type_Client']}</span>
                                </td>
                                <td class="table-col-13 col-client ${data['event_class_Client']} "
                                    <?php if($clientPaymentEvent) echo 'data-id-event="'.$clientPaymentEvent['id'] .'"'; ?>
                                    data-type="client" data-cost="${data['transportation_cost_Client_without_VAT']}">
                                    <!-- Сумма без НДС -->
                                    ${data['transportation_cost_Client_without_VAT']} ₽
                                </td>
                                <td class="table-col-14 col-client ${data['event_class_Client']} "
                                    <?php if($clientPaymentEvent) echo 'data-id-event="'.$clientPaymentEvent['id'] .'"'; ?>
                                    data-type="client" data-cost="${data['transportation_VAT_Client']}">
                                    <!-- НДС -->
                                    ${data['transportation_VAT_Client']} ₽
                                </td>
                                <td class="table-col-15 col-client ${data['event_class_Client']} "
                                    <?php if($clientPaymentEvent) echo 'data-id-event="'.$clientPaymentEvent['id'] .'"'; ?>
                                    data-type="client" data-cost="${data['actual_payment_Client_normal']}">
                                    <!-- Фактическая сумма оплаты -->
                                    <input id="actual-payment-client-${data['id']}" data-name-span="span-actual-payment-client"
                                              data-id-application="${data['id']}" data-name-info="actual_payment_Client"
                                              class="d-none form-control textarea-change-application-info">
                                    <span class="span-actual-payment-client" id="span-actual-payment-client-${data['id']}">
                                        ${data['actual_payment_Client']} ₽
                                    </span>

                                </td>
                                <td class="table-col-16 col-carrier ${data['event_class_Carrier']} "
                                    <?php if($carrierPaymentEvent) echo 'data-id-event="'.$carrierPaymentEvent['id'] .'"'; ?>
                                    data-type="carrier">
                                    <!-- Название перевозчика -->
                                    ${data['carrier_data']['name']}
                                    <div><span class="inn text-secondary">${data['carrier_data']['inn']}</span></div>
                                </td>
                                <td class="table-col-19 col-carrier ${data['event_class_Carrier']} "
                                    <?php if($carrierPaymentEvent) echo 'data-id-event="'.$carrierPaymentEvent['id'] .'"'; ?>
                                    data-type="carrier" data-cost="${data['transportation_cost_Carrier_normal']}">
                                    <!-- Общая сумма  -->
                                    <div>${data['transportation_cost_Carrier']} ₽</div>
                                    <span class="text-secondary">${data['taxation_type_Carrier']}</span>
                                </td>
                                <td class="table-col-20 col-carrier ${data['event_class_Carrier']} "
                                    <?php if($carrierPaymentEvent) echo 'data-id-event="'.$carrierPaymentEvent['id'] .'"'; ?>
                                    data-type="carrier" data-cost="${data['transportation_cost_Carrier_without_VAT']}">
                                    <!-- Сумма без НДС -->
                                    ${data['transportation_cost_Carrier_without_VAT']} ₽
                                </td>
                                <td class="table-col-21 col-carrier ${data['event_class_Carrier']} "
                                    <?php if($carrierPaymentEvent) echo 'data-id-event="'.$carrierPaymentEvent['id'] .'"'; ?>
                                    data-type="carrier" data-cost="${data['transportation_VAT_Carrier']}">
                                    <!-- НДС -->
                                    ${data['transportation_VAT_Carrier']} ₽
                                </td>
                                <td class="table-col-22 col-carrier ${data['event_class_Carrier']} "
                                    <?php if($carrierPaymentEvent) echo 'data-id-event="'.$carrierPaymentEvent['id'] .'"'; ?>
                                    data-type="carrier" data-cost="${data['actual_payment_Carrier_normal']}">
                                    <!-- Фактическая сумма оплаты -->
                                    <input id="actual-payment-carrier-${data['id']}" data-name-span="span-actual-payment-carrier"
                                              data-id-application="${data['id']}" data-name-info="actual_payment_Carrier"
                                              class="d-none form-control textarea-change-application-info">
                                    <span class="span-actual-payment-carrier" id="span-actual-payment-carrier-${data['id']}">
                                        ${data['actual_payment_Carrier']} ₽
                                    </span>
                                </td>
                                <td></td>
                                <td class="table-col-24" data-cost="${data['application_walrus_normal']}">
                                    <!-- Доход -->
                                    <div>${data['application_walrus']}  ₽</div>
                                </td>
                                <td class="table-col-25" data-cost="${data['application_net_profit_normal']}">
                                    <!-- Чистая прибыль -->
                                    <div>${data['application_net_profit']}  ₽</div>
                                </td>
                                <td class="table-col-26" data-cost="${data['manager_share_normal']}">
                                    <!-- Маржа з.п. -->
                                   <span>${data['manager_share']} ₽</span>

                                </td>
                            </tr>`
                            $('tbody').append(htmlNewApplication);
                            })
                            countAllSum();
                        }
                        else{

                        }
                    }
                })
            }

        }
        countAllSum();
        console.log(search);

    });
    $('.js-tab-customer').click(function (){

        $('.js-tab-customer').removeClass('active');
        $(this).addClass('active');
        filterTable();
    });

    $('.js-tab-section').click(function (){
        $('.js-tab-section').removeClass('active');
        $(this).addClass('active');
        filterTable();

    });

    const formatter = new Intl.NumberFormat('ru-RU', {
        style: 'decimal', // Можно использовать 'currency' для валюты

    });
    function countAllSum(){
        let arrayNumCol = {12:0, 13:0, 14:0, 15:0, 19:0, 20:0, 21:0, 22:0, 23:0, 24:0, 25:0, 26:0};

        $(".tr-application:visible").each(function () {
            var $this = $(this);
            $.each(arrayNumCol, function (indexNumCol,itemNumCol) {
                var number = parseInt($this.find('.table-col-' + indexNumCol).data('cost'));

                arrayNumCol[indexNumCol] += number;
            })

        });

        $.each(arrayNumCol, function (indexNumCol,itemNumCol) {
            $('#tr-sum').find('.table-col-' + indexNumCol).text(formatter.format(itemNumCol) + ' ₽');
        })
        console.log(arrayNumCol)
    }


    function filterTable(numCustomer = 0, numSection = 0, accountNumber = null){
        let idCustomer = $('.js-tab-customer.active').data('id-customer');
        if(numCustomer > 0)
            idCustomer = numCustomer;
        let section = $('.js-tab-section.active').data('section');
        if(numSection > 0)
            section = numSection;

        if(section === 2 || section === 3){
            $('.table-col-condition').removeClass('d-none');
        }
        else{
            $('.table-col-condition').addClass('d-none');
        }

        let condition = {'section-journal': section, 'id-customer': idCustomer}
        if(accountNumber != null) {
            condition = {
                'section-journal': section,
                'id-customer': idCustomer,
                'isset-account-number-client': accountNumber
            }
        }
        else{
            $('#flexCheckIndeterminate-1').trigger('click')
        }

        console.log(condition)
        let search = $('#search-journal').val();
        $('.tr-application').hide();
        let filterText = '';
        $.each(condition, function(index, item){
            console.log(index, item)
            if(item != undefined)
                filterText += `[data-app-${index}="${item}"]`;
        })
        if(search != '') {
            $('.tr-application:contains(' + search + ')').show();
            console.log('.tr-application:contains(' + search + ')' + filterText);
            countAllSum();
            return false;
        }
        else
            $('.tr-application' + filterText).show();
        console.log(filterText);

        $('.js-filter-header-table').prop('checked', false);



        filterHeaderTable(1);

    }
    // filterTable(1,1);
    $('.js-filter-account-number-client').change(function () {
        let value = $(this).val();

        switch (value){
            case '0':
                filterTable();
                console.log(value)
                break;

            case '1':
                filterTable(0,0,0);
                break;

            case '2':
                filterTable(0,0,1);
                break;

        }

    });
</script>
<script>
    $('body').on('click','.inn', function (event){
        event.stopPropagation();
        var text = $(this).text();
        let $this = $(this);
        navigator.clipboard.writeText(text).then(function() {
            $('td').has($this).append(`<div class="copy-success">Скопировано</div>`);

            setTimeout(function (){
                $('.copy-success').remove();
            }, 500)

            console.log('Text copied to clipboard');
        }, function(err) {
            console.error('Error copying text to clipboard', err);
        });
    });

    $('.js-paid-cancel').click(function () {
        let activeApplication = $('.js-tr-application.active');
        let arrayIdActiveApplication = [];
        activeApplication.each(function (index) {
            arrayIdActiveApplication.push($(this).data('id-application'));
        })
        let name = $(this).data('name');

        console.log(name)

        $.ajax({
            method: 'POST',
            url:'/journal/ajax/change-payment-status-cancel',
            data:{id:arrayIdActiveApplication,name: name},
            success: function (response){
                console.log(response)

                response = JSON.parse(response);

                if(response['result']) {
                    activeApplication.find('.' + name + '-actual-payment').text(0);
                    activeApplication.find('.col-' + name).removeClass('event-payment-0');
                    activeApplication.find('.col-' + name).removeClass('event-payment-1');
                    $("#custom-menu-tr-application").hide();
                    $('.js-tr-application.active').removeClass('active');
                    activeApplication.find('.span-actual-payment-' + name).text(0);
                    console.log('#' + name + '-actual-payment');
                }
            }
        })
    })
    $('.js-paid-full').click(function () {
        let activeApplication = $('.js-tr-application.active');
        let arrayIdActiveApplication = [];
        activeApplication.each(function (index) {
            arrayIdActiveApplication.push($(this).data('id-application'));
        })
        let name = $(this).data('name');

        $.ajax({
            method: 'POST',
            url:'/journal/ajax/change-payment-status-full',
            data:{id:arrayIdActiveApplication,name: name},
            success: function (response){
                console.log(response)

                response = JSON.parse(response);

                if(response['result']) {
                    activeApplication.find('.' + name + '-actual-payment').text(response['cost']);
                    activeApplication.find('.span-actual-payment-' + name).text(response['cost']);
                    activeApplication.find('.col-' + name).addClass('event-payment-0');
                    $("#custom-menu-tr-application").hide();
                    $('.js-tr-application.active').removeClass('active');
                    console.log('#' + name + '-actual-payment');
                }
            }
        })

    })

    $('body').click(function(){
        $('.textarea-change-application-info').addClass('d-none');
        $('.span-info').removeClass('d-none')
    });

    $('body').on('keypress','textarea.textarea-change-application-info', function(event) {
        if (event.which === 13) { // Enter key
            $(this).trigger('change');
            $('.js-tr-application').removeClass('active');
        }
    });
    $('body').on('change','.textarea-change-application-info',function () {
        let id = $(this).data('id-application');
        let side = $(this).data('side');
        let nameInfo = $(this).data('name-info');
        let info = $(this).val();

        if(info === '')
            return false;

        let nameSpan = $(this).data('name-span');

        let $this = $(this);
        $(this).val('');

        $.ajax({
            method:'POST',
            url: '/journal/ajax/change-application-info',
            data: {id: id, side: side, nameInfo: nameInfo, info: info},
            success: function (response) {
                console.log(response)

                response = JSON.parse(response);

                if(response['status']){
                    $this.addClass('d-none');
                    $('#' + nameSpan + '-' + id).text(info);
                    $('#' + nameSpan + '-' + id).removeClass('d-none');

                    if(nameSpan === 'span-account-number-client'){
                        $('#span-upd-number-client-'+id).text(info)
                    }

                    if(response['event']){
                        if(nameInfo === 'actual_payment_Client'){

                            $('tr').has($this).find('.col-client').addClass('event-payment-0');
                        }
                        if(nameInfo === 'actual_payment_Carrier'){

                            $('tr').has($this).find('.col-carrier').addClass('event-payment-0');
                        }
                    }

                    if(response['cost']){
                        $('#' + nameSpan + '-' + id).text(formatter.format(response['cost']) + ' ₽');
                    }

                }
            }
        })

    })

    $('.js-change-application-info').click(function () {
        let id = $('.js-tr-application.active').data('id-application');
        let name = $(this).data('name');

        console.log(name)

        $('#span-' + name +'-' + id).addClass('d-none');
        console.log('#span-' + name +'-' + id)
        $('#' + name + '-' + id).removeClass('d-none');
        $('#' + name + '-' + id).focus();

        $("#custom-menu-tr-application").hide();

    });

    $('.js-tab-customer').click(function (){
        $('.js-tab-customer').removeClass('active');
        $(this).addClass('active');
        // ajaxLoadApplications();
    });

    $('.js-tab-section').click(function (){
        $('.js-tab-section').removeClass('active');
        $(this).addClass('active');
        // ajaxLoadApplications();
    });
</script>

<script>
    $(document).ready(function() {
        $('body').on("contextmenu", '.js-tr-application', function(event) {
            $('.js-tr-application').removeClass('active');
            $(this).addClass('active');
            event.preventDefault();
            $("#custom-menu-tr-application").css({
                top: event.pageY + "px",
                left: event.pageX + "px"
            }).show();

            let activeApplication = $('.js-tr-application.active');

            if(activeApplication.length > 1){
                $('.single').addClass('d-none');
            }
            else{
                $('.single').removeClass('d-none');
            }

        });

        $(document).bind("click", function(event) {
            if ($(event.target).parents("#custom-menu-tr-application").length === 0) {
                $("#custom-menu-tr-application").hide();
            }
        });
    });

    $('body').on('click', '.js-change-payment-carrier' ,function () {
        let activeApplication = $('.js-tr-application.active');
        let arrayIdActiveApplication = [];
        activeApplication.each(function (index) {
            arrayIdActiveApplication.push($(this).data('id-application'));
        })

        $.ajax({
            method: 'POST',
            url: '/journal/ajax/change-payment-status-carrier',
            data: {id_applications: arrayIdActiveApplication},
            success: function (response) {
                console.log(response);
                response = JSON.parse(response);

                if(response['result']) {
                    $("#custom-menu-tr-application").hide();
                    $('.js-tr-application.active').removeClass('active');
                }
            }
        })

    })
</script>
<script>
    $(document).on('click', function(event) {
        if ($(event.target).is('.js-tr-application') || $(event.target).is('#custom-menu-tr-application')) return; // Ignore clicks on the element itself
        $('.js-tr-application').removeClass('active');
    });

    $('#journal-excel').click(function () {
        let $this = $(this);
        $this.attr('disabled', true);
        let section = $('.js-tab-section.active').data('section');
        let customer = $('.js-tab-customer.active').data('id-customer');
        let accountFilterNumberClient = $('.js-filter-account-number-client:checked').val();

        let linkGet = '?<?php echo $linkForExcelTable; ?>';

        $.ajax({
            url: '/journal/ajax/excel' + linkGet,
            method: 'POST',
            data: {section: section, customer: customer,accountFilterNumberClient: accountFilterNumberClient},
            success: function (response){
                $this.attr('disabled', false);
                console.log(response);
                let data = JSON.parse(response);

                download_file('journal.xlsx', data['link_file']);
            }
        })
    })

    // var table = new DataTable('#table', {
    //     language: {
    //         url: '//cdn.datatables.net/plug-ins/2.0.3/i18n/ru.json',
    //     },
    //     pageLength: 100,
    //     scrollX: true,
    // });


    $('#custom-menu-tr-application').click(function(event){
        event.stopPropagation();
    });
    $('body').on('click', '.js-tr-application', function(event){
        event.stopPropagation();
        $("#custom-menu-tr-application").hide();
        $('.js-tr-application').removeClass('active');
        $(this).toggleClass('active')

    });

    $('#create-register').click(function(){
        let arrayApplicationRegister = [];
        $('.js-tr-application.active').each(function(index){
            arrayApplicationRegister.push($(this).data('id-application'));
        })

        console.log(arrayApplicationRegister);

        $.ajax({
            url: '/journal/ajax/register',
            method: 'POST',
            data: {applications:arrayApplicationRegister},
            success: function(response){
                // download_file('Реестр.xlsx','../../wp-content/themes/pegas/ajax/journal/register.xlsx');
                console.log(response)
            }
        })
    })

    $('#testTableChange').click(function(){
        $('.table-col-1').hide();
    });

    $('.js-change-table').change(function(){
        let num = $(this).val();

        table.columns(num-1).visible($(this).is(':checked'));
        table.columns.adjust().draw(false);
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

    $('#btn-setting-table').click(function(){
        if($('.table-setting-container').data('status') == 0){
            $('.table-setting-container').removeClass('d-none');
            $('.table-setting-container').data('status', 1);

            $('.fillter-all-container').data('status', 0);
            $('.fillter-all-container').addClass('d-none');
        }
        else{
            $('.table-setting-container').addClass('d-none');
            $('.table-setting-container').data('status', 0);

            $('.fillter-all-container').addClass('d-none');
        }

    });

    $('#reset-all-setting-table').click(function(){
        $('.js-change-table').each(function(index){
            table.columns(index).visible(false);
            table.columns.adjust().draw(false);
        })
        $('.js-change-table').removeAttr('checked');
    });

    

</script>
</body>
