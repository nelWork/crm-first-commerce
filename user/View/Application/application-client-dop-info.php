<?php
/** @var App\User\Model\Application\ApplicationPage $application */
?>
<div class="side-block-dop-info">
    <div class="dop-info-item">
        <div class="w-100 dop-info-collapse-header" data-bs-toggle="collapse" data-bs-target="#collapse-client-1"
             aria-expanded="false" aria-controls="collapse-client-1">
            <span>Реквизиты клиента</span>
            <div class="icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16">
                    <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                </svg>
            </div>
        </div>
        <div class="collapse dop-info-collapse" id="collapse-client-1">
            <div class="mb-4">
                <span class="side-tab-span">ИНН</span>
                <span class="side-main-span"><?php echo $application->client['inn']; ?></span>
            </div>
            <div class="mb-4">
                <span class="side-tab-span">Юр. адрес</span>
                <span class="side-main-span"><?php echo $application->client['legal_address']; ?></span>
            </div>
            <div class="mb-4">
                <span class="side-tab-span">Контактное лицо</span>
                <span class="side-main-span"><?php echo $application->client['phone']; ?></span>
            </div>
        </div>
    </div>

    <div class="dop-info-item">
        <div class="w-100 dop-info-collapse-header" data-bs-toggle="collapse" data-bs-target="#collapse-client-2"
             aria-expanded="false" aria-controls="collapse-client-2">
            <span>Реквизиты перевозчика</span>
            <div class="icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16">
                    <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                </svg>
            </div>
        </div>
        <div class="collapse dop-info-collapse" id="collapse-client-2">
            <div class="mb-4">
                <span class="side-tab-span">ИНН</span>
                <span class="side-main-span"><?php echo $application->carrier['inn']; ?></span>
            </div>
            <div class="mb-4">
                <span class="side-tab-span">Почтовый адрес</span>
                <span class="side-main-span"><?php echo $application->carrier['legal_address']; ?></span>
            </div>
            <div class="mb-4">
                <span class="side-tab-span">Контактное лицо</span>
                <span class="side-main-span">ООО «ТрансКомпани»</span>
                <span class="side-tab-span">8 (906)121-41-91, код в АТИ 2142206log5@timinvest.ru</span>
            </div>
        </div>
    </div>


    <div class="dop-info-item">
        <div class="w-100 dop-info-collapse-header" data-bs-toggle="collapse" data-bs-target="#collapse-client-3"
             aria-expanded="false" aria-controls="collapse-client-3">
            <span>Транспортировка</span>
            <div class="icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16">
                    <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                </svg>
            </div>
        </div>
        <div class="collapse dop-info-collapse" id="collapse-client-3">
            <?php $cnt = 0;
            foreach ($application->client['transportation']['routes'] as $item): ?>
                <div class="w-100 dop-info-collapse-header" data-bs-toggle="collapse" data-bs-target="#collapse-client-3-<?php echo $cnt;?>"
                     aria-expanded="false" aria-controls="collapse-client-3-<?php echo $cnt;?>">
                    <span><?php if($item['direction'] == 0) echo 'Куда'; else echo 'Откуда'; ?></span>
                    <div class="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16">
                            <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                        </svg>
                    </div>
                </div>
                <div class="collapse dop-info-collapse" id="collapse-client-3-<?php echo $cnt;?>">
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
                        <span class="side-tab-span">Способ погрузки</span>
                        <span class="side-main-span"><?php echo $item['loading_method']; ?></span>
                    </div>
                </div>
                <?php   $cnt++;
            endforeach;
            ?>
            <div class="mt-4">
                <span class="side-tab-span">Актуальная дата выгрузки</span>
                <span class="side-main-span"><?php echo $application->client['transportation']['application_date_actual_unloading']; ?></span>
            </div>
            <div class="mt-4 mb-4">
                <span class="side-tab-span">Стоимость перевозки</span>
                <span class="side-main-span"><?php echo $application->client['transportation']['transportation_cost']; ?> ₽</span>
            </div>
            <?php if($application->client['transportation']['cargo_cost'] > 0): ?>
            <div class="">
                <span class="side-tab-span">Стоимость груза</span>
                <span class="side-main-span"><?php echo $application->client['transportation']['cargo_cost']; ?> ₽</span>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="dop-info-item">
        <div class="w-100 dop-info-collapse-header" data-bs-toggle="collapse" data-bs-target="#collapse-client-4"
             aria-expanded="false" aria-controls="collapse-client-4">
            <span>Водитель</span>
            <div class="icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16">
                    <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                </svg>
            </div>
        </div>
        <div class="collapse dop-info-collapse" id="collapse-client-4">
            <div class="mb-4">
                <span class="side-tab-span">Марка, гос.номер машины</span>
                <span class="side-main-span"><?php echo $application->client['driver']['transport'];?></span>
            </div>
            <div class="mb-4">
                <span class="side-tab-span">Водитель</span>
                <span class="side-main-span"><?php echo $application->client['driver']['name']; ?></span>
            </div>
            <div class="mb-4">
                <span class="side-tab-span">Номер телефона</span>
                <span class="side-main-span"><?php echo $application->client['driver']['phone']; ?></span>
            </div>
            <div class="mb-4">
                <span class="side-tab-span">Водительское удостоверение</span>
                <span class="side-main-span"><?php echo $application->client['driver']['driver_license']; ?></span>
            </div>
            <div class="mb-4">
                <span class="side-tab-span">Паспортные данные</span>
                <span class="side-main-span">
                    <?php echo $application->client['driver']['passport']; ?>
                </span>
            </div>
        </div>
    </div>

    <div class="dop-info-item">
        <div class="w-100 dop-info-collapse-header" data-bs-toggle="collapse" data-bs-target="#collapse-client-5"
             aria-expanded="false" aria-controls="collapse-client-5">
            <span>Условия</span>
            <div class="icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16">
                    <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                </svg>
            </div>
        </div>
        <div class="collapse dop-info-collapse" id="collapse-client-5">
            <div class="mb-4">
                <span class="side-tab-span">Оплата</span>
                <span class="side-main-span">
                    <?php echo $application->client['conditions']['terms_payment'] ?>
                </span>
            </div>
            <div class="mb-4">
                <span class="side-tab-span">Особые</span>
                <span class="side-main-span">
                    <?php echo $application->client['conditions']['special_conditions'] ?>
                </span>
            </div>
        </div>
    </div>



    <div class="dop-info-item">
        <div class="w-100 dop-info-collapse-header" data-bs-toggle="collapse" data-bs-target="#collapse-client-6"
             aria-expanded="false" aria-controls="collapse-client-6">
            <span>Дополнительные затраты</span>
            <div class="icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16">
                    <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                </svg>
            </div>
        </div>
        <div class="collapse dop-info-collapse" id="collapse-client-6">
            <?php
            $cnt = 1;
            foreach ($application->client['expenses'] as $expense):
                ?>
                <div class="w-100 dop-info-collapse-header" data-bs-toggle="collapse" data-bs-target="#collapse-client-6-<?php echo $cnt; ?>"
                     aria-expanded="false" aria-controls="collapse-client-6-<?php echo $cnt; ?>">
                    <span>Дополнительные затраты <?php echo $cnt; ?></span>
                    <div class="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16">
                            <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                        </svg>
                    </div>
                </div>
                <div class="collapse dop-info-collapse" id="collapse-client-6-<?php echo $cnt; ?>">
                    <div class="mb-4">
                        <span class="side-tab-span">Вид затрат</span>
                        <span class="side-main-span">
                        <?php echo $expense['type']; ?>
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