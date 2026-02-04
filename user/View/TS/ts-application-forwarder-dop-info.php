<?php
/** @var App\User\Model\Application\ApplicationPage $application */
/** @var App\Model\User\User $userManager */
/** @var App\Model\TSApplication\Forwarder $forwarder */
/** @var App\Model\TSApplication\TSApplication $TSApplication */

$TSApplicationData = $TSApplication->get();
$forwarderData = $forwarder->get();
//dd($item['loading_method']);
?>
<div class="side-block-dop-info">
    <div class="dop-info-item">
        <div class="w-100 dop-info-collapse-header" data-bs-toggle="collapse" data-bs-target="#collapse-carrier-1"
             aria-expanded="false" aria-controls="collapse-carrier-1">
            <span>Реквизиты экспедитора</span>
            <div class="icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16">
                    <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                </svg>
            </div>
        </div>
        <div class="collapse dop-info-collapse" id="collapse-carrier-1">
            <div class="mb-4">
                <span class="side-tab-span">ИНН</span>
                <span class="side-main-span"><?php echo $forwarderData['inn']; ?></span>
            </div>
            <div class="mb-4">
                <span class="side-tab-span">Юр. адрес</span>
                <span class="side-main-span"><?php echo $forwarderData['legal_address']; ?></span>
            </div>
            <div class="mb-4">
                <span class="side-tab-span">Контактное лицо</span>
                <span class="side-main-span"><?php echo $forwarderData['contact']; ?></span>
            </div>
        </div>
    </div>

    <div class="dop-info-item">
        <div class="w-100 dop-info-collapse-header" data-bs-toggle="collapse" data-bs-target="#collapse-carrier-3"
             aria-expanded="false" aria-controls="collapse-carrier-3">
            <span>Транспортировка</span>
            <div class="icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16">
                    <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                </svg>
            </div>
        </div>
        <div class="collapse dop-info-collapse" id="collapse-carrier-3">
            <?php $cnt = 0;
                foreach ($TSApplication->getTransportationList() as $item): ?>
                    <div class="w-100 dop-info-collapse-header" data-bs-toggle="collapse" data-bs-target="#collapse-carrier-3-<?php echo $cnt;?>"
                         aria-expanded="false" aria-controls="collapse-carrier-3-<?php echo $cnt;?>">
                        <span><?php if($item['direction'] == 0) echo 'Куда'; else echo 'Откуда'; ?></span>
                        <div class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16">
                                <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="collapse dop-info-collapse" id="collapse-carrier-3-<?php echo $cnt;?>">
                        <div class="mb-4">
                            <span class="side-tab-span">Город</span>
                            <span class="side-main-span"><?php echo $item['city']; ?></span>
                        </div>
                        <div class="mb-4">
                            <span class="side-tab-span">Адрес</span>
                            <span class="side-main-span"><?php echo $item['address']; ?></span>
                        </div>
                        <div class="mb-4">
                            <span class="side-tab-span">Дата</span>
                            <span class="side-main-span"><?php echo $item['date']; ?></span>
                        </div>
                        <div class="mb-4">
                            <span class="side-tab-span">Время</span>
                            <span class="side-main-span"><?php echo $item['time']; ?></span>
                        </div>
                        <div class="mb-4">
                            <span class="side-tab-span">Контактное лицо</span>
                            <span class="side-main-span"><?php echo $item['contact']; ?></span>
                        </div>
                        <div class="mb-4">
                            <span class="side-tab-span">Номер</span>
                            <span class="side-main-span"><?php echo $item['phone']; ?></span>
                        </div>
                        <div class="mb-4">
                            <span class="side-tab-span">Способ 
                                <?php if ($item['direction']) echo 'погрузки'; else echo 'выгрузки'; ?>
                            </span>
                            <span class="side-main-span"><?php echo $item['loading_method']; ?></span>
                        </div>
                    </div>
            <?php   $cnt++;
                endforeach;
            ?>
            <div class="mt-4">
                <span class="side-tab-span">Актуальная дата выгрузки</span>
                <span class="side-main-span"><?php //echo $application->carrier['transportation']['application_date_actual_unloading']; ?></span>
            </div>
            <div class="mt-4">
                <span class="side-tab-span">Стоимость перевозки</span>
                <span class="side-main-span"><?php echo number_format(
                        $TSApplicationData['transportation_cost'],
                        0,
                        '.',
                        ' '
                    ); ?> ₽</span>
            </div>
        </div>
    </div>

    <div class="dop-info-item">
        <div class="w-100 dop-info-collapse-header" data-bs-toggle="collapse" data-bs-target="#collapse-carrier-4"
             aria-expanded="false" aria-controls="collapse-carrier-4">
            <span>Водитель</span>
            <div class="icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16">
                    <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                </svg>
            </div>
        </div>
        <div class="collapse dop-info-collapse" id="collapse-carrier-4">
            <div class="mb-4">
                <span class="side-tab-span">Марка, гос.номер машины</span>
                <span class="side-main-span"><?php echo $TSApplicationData['car_brand'];?>, <?php echo $TSApplicationData['government_number']; ?></span>
            </div>
            <div class="mb-4">
                <span class="side-tab-span">Водитель</span>
                <span class="side-main-span"><?php echo $TSApplication->getDriverInfo()['name']; ?></span>
            </div>
            <div class="mb-4">
                <span class="side-tab-span">Номер телефона</span>
                <span class="side-main-span"><?php echo $TSApplication->getDriverInfo()['phone']; ?></span>
            </div>
            <div class="mb-4">
                <span class="side-tab-span">Водительское удостоверение</span>
                <span class="side-main-span"><?php echo $TSApplication->getDriverInfo()['driver_license']; ?></span>
            </div>
            <div class="mb-4">
                <span class="side-tab-span">Паспортные данные</span>
                <span class="side-main-span">
                    <?php echo $passport =
                        $TSApplication->getDriverInfo()['passport_serial_number'] .", "
                        .date('d.m.Y',strtotime($TSApplication->getDriverInfo()['issued_date']))
                        .' ' .$TSApplication->getDriverInfo()['issued_by']
                        .' ' .$TSApplication->getDriverInfo()['department_code']
                    ;?>
                </span>
            </div>
        </div>
    </div>

    <div class="dop-info-item">
        <div class="w-100 dop-info-collapse-header" data-bs-toggle="collapse" data-bs-target="#collapse-carrier-5"
             aria-expanded="false" aria-controls="collapse-carrier-5">
            <span>Условия</span>
            <div class="icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16">
                    <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                </svg>
            </div>
        </div>
        <div class="collapse dop-info-collapse" id="collapse-carrier-5">
            <div class="mb-4">
                <span class="side-tab-span">Оплата</span>
                <span class="side-main-span">
                    <?php echo $TSApplicationData['terms_payment'] ?>
                </span>
            </div>
            <div class="mb-4">
                <span class="side-tab-span">Особые</span>
                <span class="side-main-span">
                    <?php echo $TSApplicationData['special_conditions'] ?>
                </span>
            </div>
        </div>
    </div>

    <div class="dop-info-item">
        <div class="w-100 dop-info-collapse-header" data-bs-toggle="collapse" data-bs-target="#collapse-carrier-8"
             aria-expanded="false" aria-controls="collapse-carrier-8">
            <span>Дополнительные затраты</span>
            <div class="icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16">
                    <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                </svg>
            </div>
        </div>
        <div class="collapse dop-info-collapse" id="collapse-carrier-8">
            <?php
            $cnt = 1;
            foreach ($TSApplication->getAdditionalExpensesList() as $expense):
                ?>
                <div class="w-100 dop-info-collapse-header" data-bs-toggle="collapse" data-bs-target="#collapse-carrier-8-<?php echo $cnt; ?>"
                     aria-expanded="false" aria-controls="collapse-carrier-8-<?php echo $cnt; ?>">
                    <span>Дополнительные затраты <?php echo $cnt; ?></span>
                    <div class="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16">
                            <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                        </svg>
                    </div>
                </div>
                <div class="collapse dop-info-collapse" id="collapse-carrier-8-<?php echo $cnt; ?>">
                    <div class="mb-4">
                        <span class="side-tab-span">Вид затрат</span>
                        <span class="side-main-span">
                        <?php echo $expense['type_expenses']; ?>
                        </span>
                    </div>
                    <div class="mb-4">
                        <span class="side-tab-span">Сумма</span>
                        <span class="side-main-span">
                        <?php echo $expense['sum']; ?>
                        </span>
                    </div>
                    <div class="mb-4">
                        <span class="side-tab-span">Вид налогооблажения</span>
                        <span class="side-main-span">
                        <?php echo $expense['type_payment']; ?>
                    </span>
                    </div>
                    <div class="">
                        <span class="side-tab-span">Комментарии</span>
                        <span class="side-main-span">
                        <?php echo $expense['comment']; ?>
                    </span>
                    </div>
                </div>

                <?php $cnt++;
            endforeach; ?>
        </div>
    </div>

</div>