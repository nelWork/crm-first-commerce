<?php
/** @var App\Model\User\User $userManager */
/** @var App\Model\Client\Client $client */
/** @var App\Model\PRR\PRRCompany $prrCompany */
/** @var App\Model\PRR\PRRApplication $prrApplication */
$prrApplicationData = $prrApplication->get();
$prrPlaceList = $prrApplication->getPRRPlaceList();

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
                <span class="side-main-span"><?php echo $client->get()['inn']; ?></span>
            </div>
            <div class="mb-4">
                <span class="side-tab-span">Юр. адрес</span>
                <span class="side-main-span"><?php echo $client->get()['legal_address']; ?></span>
            </div>
            <div class="mb-4">
                <span class="side-tab-span">Контактное лицо</span>
                <span class="side-main-span"><?php echo $client->get()['phone']; ?></span>
            </div>
        </div>
    </div>

    <div class="dop-info-item">
        <div class="w-100 dop-info-collapse-header" data-bs-toggle="collapse" data-bs-target="#collapse-client-2"
             aria-expanded="false" aria-controls="collapse-client-2">
            <span>Реквизиты компании ПРР</span>
            <div class="icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16">
                    <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                </svg>
            </div>
        </div>
        <div class="collapse dop-info-collapse" id="collapse-client-2">
            <div class="mb-4">
                <span class="side-tab-span">ИНН</span>
                <span class="side-main-span"><?php echo $prrCompany->get()['name']; ?></span>
            </div>
            <div class="mb-4">
                <span class="side-tab-span">Почтовый адрес</span>
                <span class="side-main-span"><?php echo $prrCompany->get()['name']; ?></span>
            </div>
            <div class="mb-4">
                <span class="side-tab-span">Контактное лицо</span>
                <span class="side-main-span"></span>
                <span class="side-tab-span"></span>
            </div>
        </div>
    </div>


    <div class="dop-info-item">
        <div class="w-100 dop-info-collapse-header" data-bs-toggle="collapse" data-bs-target="#collapse-client-3"
             aria-expanded="false" aria-controls="collapse-client-3">
            <span>Место погрузки/разгрузки</span>
            <div class="icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16">
                    <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                </svg>
            </div>
        </div>
        <div class="collapse dop-info-collapse" id="collapse-client-3">
            <?php $cnt = 0;
            foreach ($prrPlaceList as $item): if(!$item['type_for']) continue; ?>
                <div class="w-100 dop-info-collapse-header" data-bs-toggle="collapse" data-bs-target="#collapse-client-3-<?php echo $cnt;?>"
                     aria-expanded="false" aria-controls="collapse-client-3-<?php echo $cnt;?>">
                    <span><?php if($item['direction'] == 0) echo 'Разгрузка'; else echo 'Погрузка'; ?></span>
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
                </div>
                <?php   $cnt++;
            endforeach;
            ?>
            <div class="mt-4">
                <span class="side-tab-span">Стоимость</span>
                <span class="side-main-span">
                    <?php echo number_format($prrApplicationData['cost_client'],2 ,'.', ' '); ?> ₽
                </span>
            </div>
        </div>
    </div>

    <div class="dop-info-item">
        <div class="w-100 dop-info-collapse-header" data-bs-toggle="collapse" data-bs-target="#collapse-client-4"
             aria-expanded="false" aria-controls="collapse-client-4">
            <span>Детали разгрузки</span>
            <div class="icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16">
                    <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                </svg>
            </div>
        </div>
        <div class="collapse dop-info-collapse" id="collapse-client-4">
            <div class="mb-4">
                <span class="side-tab-span">Характер груза</span>
                <span class="side-main-span"><?php echo $prrApplicationData['nature_cargo_client']; ?></span>
            </div>
            <div class="mb-4">
                <span class="side-tab-span">Мест</span>
                <span class="side-main-span"><?php echo $prrApplicationData['place_client']; ?></span>
            </div>
            <div class="mb-4">
                <span class="side-tab-span">Вес</span>
                <span class="side-main-span"><?php echo $prrApplicationData['weight_client']; ?></span>
            </div>
            <div class="mb-4">
                <span class="side-tab-span">Кол-во грузчиков</span>
                <span class="side-main-span"><?php echo $prrApplicationData['number_loaders_client']; ?></span>
            </div>
            <div class="mb-4">
                <span class="side-tab-span">Доп. информация</span>
                <span class="side-main-span"><?php echo $prrApplicationData['special_condition_client']; ?></span>
            </div>
        </div>
    </div>


</div>