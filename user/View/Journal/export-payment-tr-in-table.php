
<?php foreach ($listPayment as $payment): $application = $payment['applicationData']; $accessChangePayment = true; ?>

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
    <tr class="js-tr-application tr-application <?php if($application['id'] <= 844) echo 'application-closed-manually'; ?>"
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
        data-active="0" data-id-application="<?php echo $application['id']; ?>"
        data-app-isset-account-number-client="<?php if($application['account_number_Client'] == '') echo 0; else echo 1; ?>"
    >
        <td class="table-col-1">
            <!-- Логист -->
            <?php // echo $application['manager']; ?>

        </td>
        <td class="table-col-2 table-col-application-number-carrier">
            <!-- Номер заявки, перевозчик -->
            <a href="/application?id=<?php echo $application['id']; ?>" target="_blank" style="color: black;text-decoration: unset;">
                <?php if($application['application_number'] < 500)
                    echo $application['application_number'].'-Т';
                else echo $application['application_number'];?>
            </a>
        </td>
        <td class="table-col-3 table-col-application-number-client">
            <!-- Номер заявки, клиента -->
            <?php
            if($application['application_number_Client']) echo $application['application_number_Client'];
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
                   class="d-none form-control textarea-change-application-info">
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
        <td class="table-col-23" data-cost="<?php echo $application['application_walrus']; ?>">
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
        <td class="table-col-24" data-cost="<?php echo $application['application_walrus']; ?>">
            <!-- Доход -->
            <div> <?php echo number_format(
                    $application['application_walrus'],
                    0,
                    ',',
                    ' '
                ); ?> ₽</div>
        </td>

        <td class="table-col-25" data-cost="<?php echo $application['manager_share']; ?>">
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
        <td class="table-col-26" data-cost="<?php echo $application['application_net_profit']; ?>">
            <!-- Чистая прибыль -->
            <div> <?php echo number_format(
                    $application['application_net_profit'],
                    0,
                    ',',
                    ' '
                ); ?> ₽</div>
        </td>
        <td class="table-col-27">
            <!-- Маржинальность-->
            <div> <?php echo $application['marginality']; ?>%</div>
        </td>
    </tr>
<?php endforeach; ?>