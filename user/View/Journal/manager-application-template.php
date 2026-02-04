<?php foreach ($listApplication as $application): ?>
            <tr class=" tr-application <?php
            if($application['id'] <= 1686) echo 'application-closed-manually-2' ?>"
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
                    <span><b><?php echo $application['manager']; ?></b></span>
                </td>
                <td class="table-col table-col-3">
                    <!-- Дата погрузки / Дата разгрузки -->
                    <?php foreach ($application['transportation_list'] as $item): ?>
                        <div><?php echo $item['date']; ?></div>
                    <?php endforeach; ?>
                    <div class="">
                        <?php if($application['id'] > 844): ?>
                            <select name="" data-id-application="<?php echo $application['id']; ?>"
                                    data-num-application="<?php echo $application['application_number']; ?>"
                                    class="form-select form-select-sm js-change-application-journal-status" <?php if ($application['application_section_journal'] > 1) echo 'disabled'; ?>>
                                <option value="Е.Н.П"
                                        <?php if($application['application_status_journal'] == 'Е.Н.П') echo 'selected'; ?>
                                >Е.Н.П</option>
                                <option value="В пути"
                                    <?php if($application['application_status_journal'] == 'В пути') echo 'selected'; ?>
                                >В пути</option>

                                <option value="Выгрузился"
                                    <?php if($application['application_status_journal'] == 'Выполнена') echo 'selected'; ?>
                                >Выполнена</option>
                            </select>
                        <?php endif; ?>
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

                <td class="table-col-17 d-none">
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