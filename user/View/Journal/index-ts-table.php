<?php //dump($listTSApplication); ?>

<table class="table display table-striped table-bordered d-none" id="table-3">
    <thead >
    <tr>
        <th class="table-col-1">
            <div class="header-table-filter">
                <div class="filter-head">
                    Логист <i class="bi bi-caret-down-fill"></i>
                </div>
                <div class="filter-body">
                    <div class="search-in-filter-container mb-2">
                        <input type="text" class="form-control form-control-sm js-search-in-filter"
                               placeholder="Введите имя чтобы найти логиста">
                    </div>
                    <?php foreach ($listManager as $manager): ?>
                        <div class="form-check">
                            <input class="form-check-input js-filter-header-table" data-name-col="id-user"
                                   data-type-filter="id" type="checkbox" name="filter-header-table-1"
                                   value="<?php echo $manager['id']; ?>" id="flexCheckDefault<?php echo $manager['id']; ?>">
                            <label class="form-check-label" for="flexCheckDefault<?php echo $manager['id']; ?>">
                                <?php echo mb_strtoupper($manager['surname'] .' ' .$manager['name'] .' ' .$manager['lastname']); ?>
                            </label>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

        </th>
        <th class="table-col-2">
            <div class="header-table-filter">
                <div class="filter-head">
                    № заявки<i class="bi bi-caret-down-fill"></i>
                </div>
                <div class="filter-body">
                    <div class="search-in-filter-container mb-2">
                        <input type="text" class="form-control form-control-sm js-search-in-filter">
                    </div>

                    <?php foreach ($uniqueData['application_number'] as $appNumber): ?>
                        <div class="form-check">
                            <input class="form-check-input js-filter-header-table" data-name-col="application-number"
                                   data-type-filter="id" type="checkbox" name="filter-header-table-2"
                                   value="<?php echo $appNumber;?>" id="flexCheckDefault-app-number-<?php echo $appNumber;?>">
                            <label class="form-check-label" for="flexCheckDefault-app-number-<?php echo $appNumber;?>">
                                <?php echo $appNumber;?>
                            </label>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </th>
        <th class="table-col-3">№ заявки, клиент</th>
        <th class="table-col-condition " style="text-decoration: underline">
            Невыполненные условия
            <i class="bi bi-question-circle" data-bs-toggle="tooltip" data-bs-placement="top"
               data-bs-custom-class="custom-tooltip"
               data-bs-title='Условия которые осталось выполнить, чтобы заявка перешла в следующий статус'></i>
        </th>
        <th class="table-col-4">
            <div class="header-table-filter">
                <div class="filter-head">
                    Дата заявки <i class="bi bi-caret-down-fill"></i>
                </div>
                <div class="filter-body">
                    <?php foreach ($uniqueData['date'] as $date): ?>
                        <div class="form-check">
                            <input class="form-check-input js-filter-header-table" data-name-col="app-date"
                                   data-type-filter="id" type="checkbox" name="filter-header-table-2"
                                   value="<?php echo $date;?>" id="flexCheckDefault-date-<?php echo $date;?>">
                            <label class="form-check-label" for="flexCheckDefault-date-<?php echo $date;?>">
                                <?php echo $date;?>
                            </label>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

        </th>
        <th class="table-col-5">Дата погрузки</th>
        <th class="table-col-6">
            <div class="header-table-filter">
                <div class="filter-head">
                    Дата разгрузки <i class="bi bi-caret-down-fill"></i>
                </div>
                <div class="filter-body">
                    <?php foreach ($uniqueData['date_unloading_str'] as $date): ?>
                        <div class="form-check">
                            <input class="form-check-input js-filter-header-table" data-name-col="date-unloading"
                                   data-type-filter="id" type="checkbox" name="filter-header-table-2"
                                   value="<?php echo $date;?>" id="flexCheckDefault-date-actual-unloading-<?php echo $date;?>">
                            <label class="form-check-label" for="flexCheckDefault-date-actual-unloading-<?php echo $date;?>">
                                <?php echo $date;?>
                            </label>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </th>
        <th class="table-col-6-1">
            <div class="header-table-filter">
                <div class="filter-head">
                    Актуальная дата разгрузки <i class="bi bi-caret-down-fill"></i>
                </div>
                <div class="filter-body">
                    <?php foreach ($uniqueData['application_date_actual_unloading'] as $date): ?>
                        <div class="form-check">
                            <input class="form-check-input js-filter-header-table" data-name-col="date-actual-unloading"
                                   data-type-filter="id" type="checkbox" name="filter-header-table-2"
                                   value="<?php echo $date;?>" id="flexCheckDefault-date-actual-unloading-<?php echo $date;?>">
                            <label class="form-check-label" for="flexCheckDefault-date-actual-unloading-<?php echo $date;?>">
                                <?php echo $date;?>
                            </label>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

        </th>
        <th class="table-col-7">ТТН</th>
        <th class="table-col-8">ТТН отправлено</th>
        <th class="table-col-9">
            <div class="header-table-filter">
                <div class="filter-head">
                    Название клиента <i class="bi bi-caret-down-fill"></i>
                </div>
                <div class="filter-body">

                    <div class="search-in-filter-container mb-2">
                        <input type="text" class="form-control form-control-sm js-search-in-filter" placeholder="Введите название чтобы найти клиента">
                    </div>

                    <?php foreach ($uniqueData['client'] as $arrayClient): ?>
                        <div class="form-check">
                            <input class="form-check-input js-filter-header-table" data-name-col="client-id"
                                   data-type-filter="id" type="checkbox" name="filter-header-table-3"
                                   value="<?php echo $arrayClient['id'];?>" id="flexCheckDefault-client-id-<?php echo $arrayClient['id'];?>">
                            <label class="form-check-label" for="flexCheckDefault-client-id-<?php echo $arrayClient['id'];?>">
                                <?php echo mb_strtoupper(str_replace(['"',"'","«","»"],'',$arrayClient['name']));?>
                            </label>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </th>
        <th class="table-col-10">
            <div class="dropdown ">
                <div class="dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Номер счета <br> и дата
                </div>
                <div class="dropdown-menu ">
                    <div class="ps-2">
                        <div class="form-check">
                            <input class="form-check-input js-filter-header-table"  data-name-col="app-isset-account-number-client"
                                   type="checkbox" checked
                                   name="filter-account-number-client" value="2" id="flexCheckIndeterminate-1">
                            <label class="form-check-label" for="flexCheckIndeterminate-1">
                                Все
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input js-filter-header-table" data-name-col="app-isset-account-number-client"
                                   type="checkbox" name="filter-account-number-client"
                                   value="0" id="flexCheckIndeterminate-2">
                            <label class="form-check-label" for="flexCheckIndeterminate-2">
                                Пустые
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input js-filter-header-table" data-name-col="app-isset-account-number-client"
                                   type="checkbox" name="filter-account-number-client" value="1" id="flexCheckIndeterminate-3">
                            <label class="form-check-label" for="flexCheckIndeterminate-3">
                                Со счетом
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </th>
        <th class="table-col-11">Номер УПД и дата</th>
        <th class="table-col-12">
            <div class="header-table-filter">
                <div class="filter-head">
                    Общая сумма <i class="bi bi-caret-down-fill"></i>
                </div>
                <div class="filter-body">
                    <?php foreach ($uniqueData['cost'] as $cost): ?>
                        <div class="form-check">
                            <input class="form-check-input js-filter-header-table" data-name-col="transportation-cost-client"
                                   data-type-filter="id" type="checkbox" name="filter-header-table-2"
                                   value="<?php echo $cost;?>" id="flexCheckDefault-cost-client-<?php echo $cost;?>">
                            <label class="form-check-label" for="flexCheckDefault-cost-client-<?php echo $cost;?>">
                                <?php echo number_format($cost,0,'.',' '); ?> ₽
                            </label>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

        </th>
        <th class="table-col-13">Сумма без НДС</th>
        <th class="table-col-14">НДС</th>
        <th class="table-col-15">
            <div class="header-table-filter">
                <div class="filter-head">
                    Факт. сумма оплаты <i class="bi bi-caret-down-fill"></i>
                </div>
                <div class="filter-body">
                    <div class="form-check" style="display: block!important;">
                        <input class="form-check-input js-filter-header-table"  data-name-col="actual-payment-client"
                               data-type-filter="id" type="checkbox" name="filter-header-table-2"
                               value="*" id="flexCheckDefault-actual-payment-client">
                        <label class="form-check-label" for="flexCheckDefault-actual-payment-client">
                            Все
                        </label>
                    </div>
                    <?php foreach ($uniqueData['actual_payment'] as $cost): ?>
                        <div class="form-check">
                            <input class="form-check-input js-filter-header-table" data-name-col="actual-payment-client"
                                   data-type-filter="id" type="checkbox" name="filter-header-table-2"
                                   value="<?php echo $cost;?>" id="flexCheckDefault-actual-payment-client-<?php echo $cost;?>">
                            <label class="form-check-label" for="flexCheckDefault-actual-payment-client-<?php echo $cost;?>">
                                <?php echo number_format($cost,0,'.',' '); ?> ₽
                            </label>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </th>
        <th class="table-col-15-1">
            <div class="header-table-filter">
                <div class="filter-head">
                    Доп. прибыль <i class="bi bi-caret-down-fill"></i>
                </div>
            </div>
        </th>
        <th class="table-col-23">
            <div class="header-table-filter">
                <div class="filter-head">
                    Доп. расходы <i class="bi bi-caret-down-fill"></i>
                </div>
                <div class="filter-body">
                    <div class="form-check">
                        <input class="form-check-input js-filter-header-table" data-name-col="additional-expenses-gruz"
                               data-type-filter="id" type="checkbox" name="filter-header-table-2"
                               value="1" id="flexCheckDefault-additional-expenses-gruz">
                        <label class="form-check-label" for="flexCheckDefault-additional-expenses-gruz">
                            Грузчики
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input js-filter-header-table" data-name-col="additional-expenses-deduction"
                               data-type-filter="id" type="checkbox" name="filter-header-table-2"
                               value="1" id="flexCheckDefault-additional-expenses-deduction">
                        <label class="form-check-label" for="flexCheckDefault-additional-expenses-deduction">
                            Вычет
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input js-filter-header-table" data-name-col="additional-expenses-point"
                               data-type-filter="id" type="checkbox" name="filter-header-table-2"
                               value="1" id="flexCheckDefault-additional-expenses-point">
                        <label class="form-check-label" for="flexCheckDefault-additional-expenses-point">
                            Доп. точка
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input js-filter-header-table" data-name-col="additional-expenses-downtime"
                               data-type-filter="id" type="checkbox" name="filter-header-table-2"
                               value="1" id="flexCheckDefault-additional-expenses-downtime">
                        <label class="form-check-label" for="flexCheckDefault-additional-expenses-downtime">
                            Простои
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input js-filter-header-table" data-name-col="additional-expenses-overload"
                               data-type-filter="id" type="checkbox" name="filter-header-table-2"
                               value="1" id="flexCheckDefault-additional-expenses-overload">
                        <label class="form-check-label" for="flexCheckDefault-additional-expenses-overload">
                            Перегруз
                        </label>
                    </div>
                </div>
            </div>
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
    <tr id="tr-sum-3" style="background-color: darkorange">
        <td></td>
        <td></td>
        <td></td>
        <td class="table-col-condition"></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td class="table-col-12">1</td>
        <td class="table-col-13">2</td>
        <td class="table-col-14">3</td>
        <td class="table-col-15">4</td>
        <td class="table-col-15-1"></td>
        <td class=""></td>
    </tr>
    <?php foreach ($listTSApplication as $application): ?>

        <?php
        $carrierPaymentEvent = false;
        $clientPaymentEvent = false;

        foreach ($eventsTs as $event) {
            if($event['event'] == 'client_payment_status' && $event['application_id'] == $application['id'])
                $clientPaymentEvent = ['id' => $event['id'] ,'status' => $event['status']];
            if($event['event'] == 'carrier_payment_status' && $event['application_id'] == $application['id'])
                $carrierPaymentEvent = ['id' => $event['id'] ,'status' => $event['status']];
        }
        ?>
        <tr class="js-tr-application tr-application tr-ts-application"
            data-actual-payment-client="<?php echo $application['actual_payment']; ?>"
            data-transportation-cost-client="<?php echo $application['transportation_cost']; ?>"
            data-id-user="<?php echo $application['id_user']; ?>"
            data-application-number="<?php echo $application['application_number']; ?>"
            data-date-actual-unloading="<?php if($application['application_date_actual_unloading']) echo date('d.m.Y', strtotime($application['application_date_actual_unloading'])); else echo 'не указана';?>"
            data-client-id="<?php echo $application['id_forwarder'];  ?>"
            data-app-date="<?php echo date('d.m.Y', strtotime($application['date'])); ?>"
            data-app-section-journal="<?php echo $application['application_section_journal']; ?>"
            data-app-status-journal="<?php echo $application['application_status_journal']; ?>"
            data-app-id-customer="<?php echo $application['id_customer']; ?>"
            data-active="0" data-id-application="<?php echo $application['id']; ?>"
            data-app-isset-account-number-client="<?php if($application['account_number'] == '') echo 0; else echo 1; ?>"
        >
            <td class="table-col-1">
                <!-- Логист -->
                <?php echo $application['manager']; ?>

            </td>
            <td class="table-col-2 table-col-application-number-carrier">
                <!-- Номер заявки, перевозчик -->
                <a href="/ts/application?id=<?php echo $application['id']; ?>" target="_blank" style="color: black;text-decoration: unset;">
                    <?php echo $application['application_number'];?>
                </a>
            </td>
            <td class="table-col-3 table-col-application-number-client">
                <!-- Номер заявки, клиента -->
                <?php
                if($application['application_number']) echo $application['application_number'];
                else echo '<div class="text-center">—</div>';
                ?>

            </td>
            <td class="table-col-condition">
                <!-- Невыполненные условия -->
                <?php if(isset($application['unfulfilledConditions'])): ?>
                    <?php foreach ($application['unfulfilledConditions'] as $unfulfilledCondition): ?>
                        <div>- <?php echo $unfulfilledCondition; ?></div>
                    <?php endforeach; ?>
                <?php endif; ?>

            </td>
            <td class="table-col-4">
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
                <div style="font-size: 12px; color: #0d6efd" class="section-application d-none">
                    <?php echo $customers[$application['id_customer'] - 1]['name']; ?>
                </div>
            </td>
            <td class="table-col-5">
                <!-- Дата погрузки -->
                <?php foreach ($application['transportation_list'] as $item): if($item['direction']): ?>
                    <div><?php echo $item['date']; ?></div>
                <?php endif; endforeach; ?>
            </td>
            <td class="table-col-6">
                <!-- Дата разгрузки -->
                <?php foreach ($application['transportation_list'] as $item): if(!$item['direction']): ?>
                    <div><?php echo $item['date']; ?></div>
                <?php endif; endforeach; ?>
            </td>
            <td class="table-col-6-1">
                <!-- Актуальная дата разгрузки -->
                <?php if($application['application_date_actual_unloading'])
                    echo date('d.m.Y', strtotime($application['application_date_actual_unloading'])); ?>
            </td>
            <td class="table-col-7">
                <!-- ТТН -->
            </td>
            <td class="table-col-8">
                <!-- ТТН отправлено -->
                <div class="text-center" style="font-weight: 600">
                    <?php // if($application['ttn_sent']) echo 'ОТПРАВЛЕНО'; else echo 'НЕ ОТПРАВЛЕНО'; ?>
                </div>
            </td>
            <td class="table-col-9 col-client
                    <?php if($clientPaymentEvent) echo 'event-payment-' .$clientPaymentEvent['status']; ?> "
                <?php if($clientPaymentEvent) echo 'data-id-event="'.$clientPaymentEvent['id'] .'"'; ?>
                data-type="client">
                <!-- Название клиента -->
                <?php echo $application['forwarder_data']['name']; ?>
                <div><span class="inn text-secondary"><?php echo $application['forwarder_data']['inn']; ?></span></div>
<!--                --><?php //if ($application['forwarder_data']['format_work']): ?>
<!--                    <div class="">(--><?php //echo $application['forwarder_data']['format_work']; ?><!--)</div>-->
<!--                --><?php //endif; ?>
            </td>
            <td class="table-col-10 col-client table-col-account-number-client
                    <?php if($clientPaymentEvent) echo 'event-payment-' .$clientPaymentEvent['status']; ?> "
                <?php if($clientPaymentEvent) echo 'data-id-event="'.$clientPaymentEvent['id'] .'"'; ?>
                data-type="client">
                <!-- Номер счета и дата  -->
                <input id="account-number-client-<?php echo $application['id']; ?>" data-name-span="span-account-number-client"
                          data-id-application="<?php echo $application['id'] ?>" data-name-info="account_number"
                          class="d-none form-control textarea-change-application-info-acc-number" data-type="ts" value="<?php echo $application['account_number']; ?>">
                <span class="span-info" id="span-account-number-client-<?php echo $application['id']; ?>">
                        <?php echo $application['account_number']; ?>
                    </span>

                <div class="">
                    (<?php
                    if($application['account_status']) echo 'Отправлен ' . date('d.m.Y', strtotime($application['date_invoice']));
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
                          data-id-application="<?php echo $application['id'] ?>" data-name-info="upd_number"
                          class="d-none form-control textarea-change-application-info"><?php echo $application['upd_number']; ?></textarea>
                <span class="span-info" id="span-upd-number-client-<?php echo $application['id']; ?>">
                        <?php echo $application['upd_number']; ?>
                    </span>
            </td>
            <td class="table-col-12 col-client
                    <?php if($clientPaymentEvent) echo 'event-payment-' .$clientPaymentEvent['status']; ?> "
                <?php if($clientPaymentEvent) echo 'data-id-event="'.$clientPaymentEvent['id'] .'"'; ?>
                data-type="client" data-cost="<?php echo $application['transportation_cost']; ?>">
                <!-- Общая сумма -->
                <div><?php echo number_format($application['transportation_cost'],0,'.',' '); ?> ₽</div>
                <span class="text-secondary"><?php echo $application['taxation_type']; ?></span>
            </td>
            <td class="table-col-13 col-client
                    <?php if($clientPaymentEvent) echo 'event-payment-' .$clientPaymentEvent['status']; ?> "
                <?php if($clientPaymentEvent) echo 'data-id-event="'.$clientPaymentEvent['id'] .'"'; ?>
                data-type="client" data-cost="<?php if($application['taxation_type'] == 'С НДС') echo $application['transportation_cost'] / 1.2; else echo $application['transportation_cost']; ?>">
                <!-- Сумма без НДС -->
                <?php
                if($application['taxation_type'] == 'С НДС')
                    echo number_format($application['transportation_cost'] / 1.2,0,'.',' ');
                else
                    echo number_format($application['transportation_cost'],0,'.',' ');
                ?> ₽
            </td>
            <td class="table-col-14 col-client
                    <?php if($clientPaymentEvent) echo 'event-payment-' .$clientPaymentEvent['status']; ?> "
                <?php if($clientPaymentEvent) echo 'data-id-event="'.$clientPaymentEvent['id'] .'"'; ?>
                data-type="client" data-cost="<?php if($application['taxation_type'] == 'С НДС') echo $application['transportation_cost'] / 6; else echo 0; ?>">
                <!-- НДС -->
                <?php
                if($application['taxation_type'] == 'С НДС')
                    echo number_format($application['transportation_cost'] / 6,0,'.',' ');
                else
                    echo number_format(0,0,'.',' ');
                ?> ₽
            </td>
            <td class="table-col-15 col-client
                    <?php if($clientPaymentEvent) echo 'event-payment-' .$clientPaymentEvent['status']; ?> "
                <?php if($clientPaymentEvent) echo 'data-id-event="'.$clientPaymentEvent['id'] .'"'; ?>
                data-type="client" data-cost="<?php echo $application['actual_payment']; ?>">
                <!-- Фактическая сумма оплаты -->
                <input type="number" min="0" id="actual-payment-client-<?php echo $application['id']; ?>" data-name-span="span-actual-payment-client"
                       data-id-application="<?php echo $application['id'] ?>" data-name-info="actual_payment_Client"
                       class="d-none form-control textarea-change-application-info" data-type="ts">

                <div data-bs-toggle="collapse" class="span-actual-payment-client"
                     id="span-actual-payment-client-<?php echo $application['id']; ?>" href="#collapseHistoryPayment<?php echo $application['id']; ?>"
                     role="button" aria-expanded="false" aria-controls="collapseHistoryPayment">
                    <?php echo number_format(
                        $application['actual_payment'],
                        0,
                        ',',
                        ' '
                    ); ?> ₽
                    <div class="">
                        <?php if($application['full_payment_date']): ?>
                            (<span class="span-date-payment"><?php echo date('d.m.Y', strtotime($application['full_payment_date'])); ?></span>
                            <?php if($accessChangePayment): ?><i class="bi bi-pencil-square js-change-payment-date"></i> <?php endif; ?>)
                            <?php if($accessChangePayment): ?><input type="date" class="form-control input-change-date d-none" data-side="Client" data-id-app="<?php echo $application['id']; ?>"
                                                                     value="<?php echo date('d.m.Y', strtotime($application['full_payment_date'])); ?>">
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
<!--                    --><?php //if(count($application['history_payment'])): ?>
<!--                        <i class="bi bi-caret-down-fill text-dark"></i>-->
<!--                    --><?php //endif; ?>
                </div>
<!--                <div class="collapse" id="collapseHistoryPayment--><?php //echo $application['id']; ?><!--">-->
<!--                    --><?php //foreach ($application['history_payment'] as $history): ?>
<!--                        <div class="expenses small">-->
<!--                            --><?php //echo number_format($history['quantity'],0, ',',' ') ."₽ ("
//                                . date('d.m.Y', strtotime($history['date'])) .')'; ?>
<!--                        </div>-->
<!--                    --><?php //endforeach; ?>
<!--                </div>-->

            </td>
            <td class="table-col-15-1">
                <div class="text-success" data-bs-toggle="collapse" href="#collapseProfit3-<?php echo $application['id']; ?>" role="button" aria-expanded="false" aria-controls="collapseProfit">
                    <?php echo number_format(
                        $application['additional_profit_sum'],
                        0,
                        ',',
                        ' '
                    ); ?> ₽
                    <?php if(count($application['additional_profit'])): ?>
                        <i class="bi bi-caret-down-fill text-dark"></i>
                    <?php endif; ?>
                </div>
                <div class="collapse" id="collapseProfit3-<?php echo $application['id']; ?>">
                    <?php foreach ($application['additional_profit'] as $profit): ?>
                        <div class="profit small">
                            <?php echo $profit['type'] ." (" .$profit['type_payment'] .' - ' .number_format($profit['sum'],0, ',',' ') ."₽)"; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </td>

            <td class="table-col-23" >
                <!-- Доп. расходы -->
                <div class="text-danger" data-bs-toggle="collapse" href="#collapseExpenses3-<?php echo $application['id']; ?>" role="button" aria-expanded="false" aria-controls="collapseExpenses">
                    - <?php echo number_format(
                        $application['additional_expenses_sum'],
                        0,
                        ',',
                        ' '
                    ); ?> ₽
                    <?php if(count($application['additional_expenses'])): ?>
                        <i class="bi bi-caret-down-fill text-dark"></i>
                    <?php endif; ?>
                </div>
                <div class="collapse" id="collapseExpenses3-<?php echo $application['id']; ?>">
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


        </tr>
    <?php endforeach; ?>
    </tbody>
</table>