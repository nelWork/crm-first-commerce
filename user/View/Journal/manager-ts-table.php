<table class="table table-striped table-bordered mt-4 d-none" id="table-3">
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
        <th class="table-col-5">Экспедитор</th>
        <th class="table-col-6">Номер счета / Номер УПД</th>
        <th class="table-col-7">Общая сумма</th>
        <th class="table-col-8">Доп. прибыль</th>
        <th class="table-col-12">Доп. расходы</th>
        <th class="table-col-9">Факт. оплата</th>
        <!--        <th class="table-col-14">Доход / Маржа з.п.</th>-->
        <th class="table-col-17 d-none">
            Невыполненные условия
            <i class="bi bi-question-circle" data-bs-toggle="tooltip" data-bs-placement="top"
               data-bs-custom-class="custom-tooltip"
               data-bs-title='Условия которые осталось выполнить, чтобы заявка перешла в статус "Закрытые под расчет" '></i>
        </th>
        <!--        <th class="table-col-16">Комментарии</th>-->
    </tr>
    </thead>
    <tbody>

    <?php foreach ($listTSApplication as $application): ?>
        <tr class=" tr-application"
            data-type-app="2"
            data-app-date="<?php echo date('Y-m-d', strtotime($application['date'])); ?>"
            data-app-section-journal="<?php echo $application['application_section_journal']; ?>"
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
                <a href="/prr/prr_application?id=<?php echo $application['id']; ?>" target="_blank" style="color: black;text-decoration: unset;">
                    <?php echo $application['application_number'];?>
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
                    <select name="" data-id-application="<?php echo $application['id']; ?>"
                            data-num-application="<?php echo $application['application_number']; ?>"
                            data-type-app="ts"
                            class="form-select form-select-sm js-change-application-journal-status" <?php if ($application['application_section_journal'] > 1) echo 'disabled'; ?>>
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
            </td>
            <td class="table-col table-col-4">
                <!-- ТТН -->
            </td>

            <?php
            $carrierPaymentEvent = false;
            $clientPaymentEvent = false;
            ?>

            <td class="table-col table-col-5 col-client
                    <?php if($clientPaymentEvent) echo 'event-payment-' .$clientPaymentEvent['status']; ?> "
                <?php if($clientPaymentEvent) echo 'data-id-event="'.$clientPaymentEvent['id'] .'"'; ?>
                data-type="client"
            >
                <!-- Название клиента -->
                <?php echo $application['forwarder_data']['name']; ?>
            </td>
            <td class="table-col table-col-6 col-client <?php if($clientPaymentEvent) echo 'event-payment-' .$clientPaymentEvent['status']; ?> "
                <?php if($clientPaymentEvent) echo 'data-id-event="'.$clientPaymentEvent['id'] .'"'; ?>
                data-type="client">
                <!-- Номер счета / Номер УПД -->
                <div>
                    <?php echo $application['account_number']; ?>
                    <?php  if($application['account_number'] !== '' AND $application['upd_number'] !== '') echo ' / '; ?>
                    <?php echo $application['upd_number']; ?>
                </div>
            </td>
            <td class="table-col table-col-7 col-client <?php if($clientPaymentEvent) echo 'event-payment-' .$clientPaymentEvent['status']; ?> "
                <?php if($clientPaymentEvent) echo 'data-id-event="'.$clientPaymentEvent['id'] .'"'; ?>
                data-type="client">
                <!-- Общая сумма -->
                <div><?php echo number_format(
                        $application['transportation_cost'],
                        0,
                        ',',
                        ' '
                    ); ?> ₽</div>
                <span class="text-secondary"><?php echo $application['taxation_type']; ?></span>
            </td>
            <td class="table-col table-col-8 col-client <?php if($clientPaymentEvent) echo 'event-payment-' .$clientPaymentEvent['status']; ?> "
                <?php if($clientPaymentEvent) echo 'data-id-event="'.$clientPaymentEvent['id'] .'"'; ?>
                data-type="client">
                <!--                Доп прибыль-->
                <div class="text-success" data-bs-toggle="collapse" href="#collapseProfit2-<?php echo $application['id']; ?>" role="button" aria-expanded="false" aria-controls="collapseProfit">
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
                <div class="collapse" id="collapseProfit2-<?php echo $application['id']; ?>">
                    <?php foreach ($application['additional_profit'] as $profit): ?>
                        <div class="profit small">
                            <?php echo $profit['type'] ." (" .number_format($profit['sum'],0, ',',' ') ."₽)"; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </td>

            <td class="table-col table-col-12 col-carrier <?php if($carrierPaymentEvent) echo 'event-payment-' .$carrierPaymentEvent['status']; ?> "
                <?php if($carrierPaymentEvent) echo 'data-id-event="'.$carrierPaymentEvent['id'] .'"'; ?>
                data-type="carrier">
                <!-- Доп. расходы -->
                <div class="text-danger" data-bs-toggle="collapse" href="#collapseExpenses2-<?php echo $application['id']; ?>" role="button" aria-expanded="false" aria-controls="collapseExpenses">
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
                <div class="collapse" id="collapseExpenses2-<?php echo $application['id']; ?>">
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
            <td class="table-col table-col-9 col-client <?php if($clientPaymentEvent) echo 'event-payment-' .$clientPaymentEvent['status']; ?> "
                <?php if($clientPaymentEvent) echo 'data-id-event="'.$clientPaymentEvent['id'] .'"'; ?>
                data-type="client">
                <!-- Фактическая сумма оплаты -->
                <?php echo number_format(
                    $application['actual_payment'],
                    0,
                    ',',
                    ' '
                ); ?> ₽
                <div class="">
                    <?php if($application['full_payment_date']): ?>
                        (<?php echo date('d.m.Y', strtotime($application['full_payment_date'])); ?>)
                    <?php endif; ?>
                </div>
            </td>


            <td class="table-col-17 d-none">
                <?php if(isset($application['unfulfilledConditions'])): ?>
                    <?php foreach ($application['unfulfilledConditions'] as $unfulfilledCondition): ?>
                        <div>- <?php echo $unfulfilledCondition; ?></div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>


    </tbody>
</table>