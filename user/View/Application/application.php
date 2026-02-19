<?php
/**
 * @var App\User\Contoller\Common\ApplicationController $controller
 */
/** @var App\User\Model\Application\ApplicationPage $application */
/** @var array $listManager */
/** @var App\Model\User\User $manager */

//dd($application);
$controller->view('Components/head');
?>

<body data-id-application="<?php echo $application->id; ?>">
    <?php $controller->view('Components/header'); ?>

    <div class="card-application">
        <div class="d-flex justify-content-center">
            <?php if($controller->request->input('copy') == 1): ?>
                <div class="alert alert-success w-100 text-center" role="alert" style="font-weight: 600;font-size: 16px; max-width: 1408px">
                    Заявка удачно скопирована!
                </div>
            <?php endif; ?>
        </div>
        <div class="card-wrapper wrapper">
            <div class="card__header row align-items-center">
                <div class="card__header-title col-2 d-flex align-items-center">
                    № <?php echo $application->numApp; ?>
                    <span style="margin-left: 9.5px; color:#A1A5B7;">
                        <svg width="10" height="13" viewBox="0 0 10 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M8.375 0.5C8.98438 0.5 9.5 1.01562 9.5 1.625V11.375C9.5 12.0078 8.98438 12.5 8.375 12.5H6.125V10.625C6.125 10.0156 5.60938 9.5 5 9.5C4.36719 9.5 3.875 10.0156 3.875 10.625V12.5H1.625C0.992188 12.5 0.5 12.0078 0.5 11.375V1.625C0.5 1.01562 0.992188 0.5 1.625 0.5H8.375ZM2 6.875C2 7.08594 2.16406 7.25 2.375 7.25H3.125C3.3125 7.25 3.5 7.08594 3.5 6.875V6.125C3.5 5.9375 3.3125 5.75 3.125 5.75H2.375C2.16406 5.75 2 5.9375 2 6.125V6.875ZM4.625 5.75C4.41406 5.75 4.25 5.9375 4.25 6.125V6.875C4.25 7.08594 4.41406 7.25 4.625 7.25H5.375C5.5625 7.25 5.75 7.08594 5.75 6.875V6.125C5.75 5.9375 5.5625 5.75 5.375 5.75H4.625ZM6.5 6.875C6.5 7.08594 6.66406 7.25 6.875 7.25H7.625C7.8125 7.25 8 7.08594 8 6.875V6.125C8 5.9375 7.8125 5.75 7.625 5.75H6.875C6.66406 5.75 6.5 5.9375 6.5 6.125V6.875ZM2.375 2.75C2.16406 2.75 2 2.9375 2 3.125V3.875C2 4.08594 2.16406 4.25 2.375 4.25H3.125C3.3125 4.25 3.5 4.08594 3.5 3.875V3.125C3.5 2.9375 3.3125 2.75 3.125 2.75H2.375ZM4.25 3.875C4.25 4.08594 4.41406 4.25 4.625 4.25H5.375C5.5625 4.25 5.75 4.08594 5.75 3.875V3.125C5.75 2.9375 5.5625 2.75 5.375 2.75H4.625C4.41406 2.75 4.25 2.9375 4.25 3.125V3.875ZM6.875 2.75C6.66406 2.75 6.5 2.9375 6.5 3.125V3.875C6.5 4.08594 6.66406 4.25 6.875 4.25H7.625C7.8125 4.25 8 4.08594 8 3.875V3.125C8 2.9375 7.8125 2.75 7.625 2.75H6.875Z" fill="#A1A5B7"></path>
                        </svg>
                    </span>

                </div>
                <div class="col d-flex align-items-center">


                </div>
                <div class="row col-2 justify-content-end align-items-center">
                    <div class="card__header-data col-5 p-0"><?php echo $application->date; ?></div>
                    <div class="settings col-2 p-0 row justify-content-end">
                        <div class="dropdown">
                            <div class="dropdown-toggle dropdown-toggle-without-arrow" data-bs-toggle="dropdown" aria-expanded="false">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path opacity="0.3" d="M17 2H7C4.23858 2 2 4.23858 2 7V17C2 19.7614 4.23858 22 7 22H17C19.7614 22 22 19.7614 22 17V7C22 4.23858 19.7614 2 17 2Z" fill="#B5B5C3"></path>
                                    <path d="M13 12C13 11.4477 12.5523 11 12 11C11.4477 11 11 11.4477 11 12C11 12.5523 11.4477 13 12 13C12.5523 13 13 12.5523 13 12Z" fill="#B5B5C3"></path>
                                    <path d="M13 16C13 15.4477 12.5523 15 12 15C11.4477 15 11 15.4477 11 16C11 16.5523 11.4477 17 12 17C12.5523 17 13 16.5523 13 16Z" fill="#B5B5C3"></path>
                                    <path d="M13 8C13 7.44772 12.5523 7 12 7C11.4477 7 11 7.44772 11 8C11 8.55228 11.4477 9 12 9C12.5523 9 13 8.55228 13 8Z" fill="#B5B5C3"></path>
                                </svg>
                            </div>
                            <ul class="dropdown-menu dropdown-menu-theme" style="border: unset; padding: 0">
                                <?php if($controller->auth->user()->fullCRM()): ?>
                                <li><div class="dropdown-item js-open-application-journal bg-secondary text-white mb-2"
                                         data-id-app="<?php echo $application->id; ?>">
                                        <i class="bi bi-box-arrow-up-right" style="margin-right: 6px;"></i> Открыть в Журнале
                                    </div>
                                </li>
                                <?php endif; ?>

<!--                                <li><div class="dropdown-item js-delete-fines">В архив</div></li>-->
<!--                                <li><div class="dropdown-item js-delete-fines">Удалить</div></li>-->
                                <li>
                                    <div class="dropdown-item js-copy-application bg-success text-white mb-2"
                                         data-id-app="<?php echo $application->id; ?>">
                                        <i class="bi bi-copy" style="margin-right: 6px;"></i> Копировать
                                    </div>
                                </li>

                                <li><div class="dropdown-item js-create-prr  bg-warning text-dark mb-2" data-id-app="<?php echo $application->id; ?>">
                                        <i class="bi bi-plus-circle"></i> Создать ПРР
                                    </div></li>
                                <?php if($application->status['application-status'] !== 'На проверке' OR $controller->auth->user()->fullCRM()): ?>
                                <li>
                                    <a href="/application/edit?id=<?php echo $application->id;?>"
                                       class="dropdown-item bg-primary text-white mb-2">
                                        <i class="bi bi-pencil-square" style="margin-right: 6px;"></i> Редактировать</a>
                                </li>
                                <?php endif; ?>
                                <?php if($controller->auth->user()->fullCRM() OR in_array($controller->auth->user()->id(),[17,31])): ?>
                                <li>
                                    <div class="dropdown-item js-change-manager bg-secondary text-white"
                                         data-bs-toggle="modal" data-bs-target="#modalChangeManager">
                                        <i class="bi bi-person-fill-up" style="margin-right: 6px;"></i> Сменить менеджера
                                    </div>
                                </li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>

            <div class="card__content d-flex">
                <div class="card__content-main d-flex flex-column">
                    <?php if($application->status['application-status'] !== 'На проверке' OR $controller->auth->user()->fullCRM()): ?>
                    <div class="card__content-main__buttons d-flex">
                        <div class="card__content-select-status">

                        </div>
                        <div class="settings format-doc me-4" style="position: relative">
                                <div class="">
                                    <div class="btn btn-add-light format-document" id="btn-doc-menu" >
                                        Сформировать документ
                                    </div>
                                <div class="settings-menu" id="setting-menu">
                                    <div class="item-doc-fotmat">
                                        <span class="type-format-doc" data-link="agreement-application-carrier">Договор-заявка с перевозчиком</span>
                                        <div class="form-check form-check-custom form-check-solid form-check-sm align-items-start">

                                            <input checked="" class="form-check-input seal" data-name="seal" id="check-dogovor-perevozhik" type="checkbox" value="">
                                            <label for="check-dogovor-perevozhik">С печатью</label>
                                        </div>
                                    </div>
                                    <div class="item-doc-fotmat">
                                        <span class="type-format-doc" data-link="agreement-application-client">Договор-заявка с клиентом</span>
                                        <div class="form-check form-check-custom  -check-solid form-check-sm align-items-start">

                                            <input checked="" class="form-check-input seal" id="check-dogovor-client" type="checkbox" value="">
                                            <label for="check-dogovor-client">С печатью</label>
                                        </div>
                                    </div>
                                    <div class="item-doc-fotmat">
                                        <span class="type-format-doc" data-link='driver-attorney'>Доверенность на водителя</span>
                                        <div class="form-check form-check-custom form-check-solid form-check-sm align-items-start">

                                            <input checked="" class="form-check-input seal" id="check-attorney-pechat" type="checkbox" value="">
                                            <label for="check-attorney-pechat">С печатью</label>
                                        </div>
                                        <div class="form-check form-check-custom form-check-solid form-check-sm align-items-start">

                                            <input class="form-check-input driver-signature" id="check-attorney-podpis" data-name="driver-signature" type="checkbox" value="">
                                            <label for="check-attorney-podpis">С подписью</label>
                                        </div>
                                        <div class="form-check form-check-custom form-check-solid form-check-sm align-items-start">

                                            <input class="form-check-input docx" id="check-attorney-docx" data-name="docx" type="checkbox" value="">
                                            <label for="check-attorney-docx">docx</label>
                                        </div>
                                    </div>

                                    <div class="item-doc-fotmat">
                                        <span class="type-format-doc" data-link="forwarding-receipt">Экспедиторская расписка</span>
                                        <div class="form-check form-check-custom form-check-solid form-check-sm align-items-start">

                                            <input checked="" class="form-check-input seal" id="check-er" data-name="seal" type="checkbox" value="">
                                            <label for="check-er">С печатью</label>
                                        </div>
                                    </div>
                                    <div class="item-doc-fotmat">
                                        <span class="type-format-doc" data-link='receipt-services'>Расписка об оказании услуг</span>

                                        <div class="form-check form-check-custom form-check-solid form-check-sm align-items-start" style="visibility:hidden;">

                                            <input checked class="form-check-input docx" id="check-receipt-services-docx" data-name="docx" type="checkbox" value="">
                                            <label for="check-receipt-services-docx">docx</label>
                                        </div>
                                    </div>
                                    <div class="item-doc-fotmat">
                                        <span class="type-format-doc" data-link='attorney-m2'>Доверенность на водителя (М2)</span>

                                        <div class="form-check form-check-custom form-check-solid form-check-sm align-items-start" style="visibility:hidden;">

                                        </div>
                                    </div>
                                    <span class="type-format-doc d-none" data-link="insurance">Страховка груза</span>
                                    <span class="type-format-doc" onclick="addTask(<?php echo $application->id;  ?>);">Информация о счете</span>
                                    <span class="type-format-doc" data-link="info-driver">Информация по водителю</span>

                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                    <div class="card__content-main-harakter_gruza">
                        <p><?php echo $application->natureCargo; ?></p>
                    </div>
                    <div class="card__content-main__header  d-flex">

                        <div class="form__header-menu-list d-flex w-100">
                            <a class="form__header-menu-item d-none">
                                <div class="menu-item-click active js-link-tab-change main-header-js-link-tab-change"
                                     data-container=".card__content-tab" data-tab-app="#tab-1" data-prefix-tab=".main-header">
                                    Задачи
                                </div>
                            </a>

                            <a class="mr-6 form__header-menu-item ">
                                <div class="menu-item-click active js-link-tab-change main-header-js-link-tab-change"
                                     data-container=".card__content-tab"  data-tab-app="#tab-2" data-prefix-tab=".main-header">
                                    Документы
                                </div>
                            </a>

                            <a class="mr-6 form__header-menu-item  ">
                                <div class="menu-item-click js-link-tab-change main-header-js-link-tab-change"
                                     data-container=".card__content-tab"  data-tab-app="#tab-3" data-prefix-tab=".main-header">
                                    Комментарии
                                </div>
                            </a>
                            <?php if($controller->auth->user()->fullCRM()): ?>
                            <a class="mr-6 form__header-menu-item ">
                                <div class="menu-item-click js-link-tab-change main-header-js-link-tab-change"
                                     data-container=".card__content-tab" data-tab-app="#tab-4" data-prefix-tab=".main-header">
                                    Лента активности
                                </div>
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="card__content-tab d-none" id="tab-1">
                        <button class="btn btn-theme-success">+ Добавить</button>

                        <div class="quest__table">
                            <div class="quest__table-header d-flex">
                                <span>Название</span>
                                <span>Крайний срок</span>
                                <span>Дата выполнения</span>
                                <span>Комментарий</span>
                                <span>Исполнитель</span>
                                <span>Статус</span>
                            </div>
                            <div class="quest__table-list quest__table-tasks">
                                <?php foreach($listTasks as $task){?>
                                    <div class="quest__table-list quest__table-tasks">
                                        <div class="quest__table-task">
                                            <div class="quest__table-task-name">
                                                <div class="name">
                                                    <?php echo $task['name']?>
                                                </div>
                                                <div class="date">
                                                    <?php echo $task['create_datetime']?>
                                                </div>
                                            </div>
                                            <div class="quest__table-task-deadline">
                                                <?php echo $task['deadline']?>
                                            </div>
                                            <div class="quest__table-task-date_completion">
                                                <span class='task-date_completion orange'> Не сделано </span>
                                            </div>
                                            <div class="quest__table-task-comment">
                                                <span onclick="openCommentModal(<?php echo $task['id']?>);" data-bs-toggle="modal" data-bs-target="#modalComment">ПРОСЬБА ...</span>
                                            </div>
                                            <div class="quest__table-task-full-comment d-none task-comment_<?php echo $task['id']; ?>">
                                                <?php echo $task['comment']?>
                                            </div>
                                            <div class="quest__table-task-executor d-flex">
                                                <div class="avatar">
                                                    <img src="/assets/img/empty_avatar.png" alt="">
                                                </div>
                                                <div class="name">

                                                    <br>
                                                    <span class="gray">Бухгалтер</span>
                                                </div>
                                            </div>
                                            <div class="quest__table-task-status">
                                                <select name="task_status" id="status" value="Сделать"
                                                        class="select-status-light status-type blue select2-hidden-accessible"
                                                        tabindex="-1" aria-hidden="true" data-select2-id="select2-data-status">
                                                    <option value="В работе" selected="" data-select2-id="select2-data-69-ubkx">В работе</option>
                                                    <option value="В пути">В пути</option>
                                                    <option value="Выгрузился">Выгрузился</option>
                                                    <option value="Завершена (оплата)">Завершена (оплата)</option>
                                                    <option value="Завершена (док.)">Завершена (док.)</option>
                                                    <option value="Завершена">Завершена</option>
                                                </select>
                                            </div>
                                            <div class="quest__table-task-status-btns">
                                                <div class="settings col-2 p-0 row justify-content-end">
                                                    <div class="dropdown">
                                                        <div class="dropdown-toggle dropdown-toggle-without-arrow" data-bs-toggle="dropdown" aria-expanded="false">
                                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path opacity="0.3" d="M17 2H7C4.23858 2 2 4.23858 2 7V17C2 19.7614 4.23858 22 7 22H17C19.7614 22 22 19.7614 22 17V7C22 4.23858 19.7614 2 17 2Z" fill="#B5B5C3"></path>
                                                                <path d="M13 12C13 11.4477 12.5523 11 12 11C11.4477 11 11 11.4477 11 12C11 12.5523 11.4477 13 12 13C12.5523 13 13 12.5523 13 12Z" fill="#B5B5C3"></path>
                                                                <path d="M13 16C13 15.4477 12.5523 15 12 15C11.4477 15 11 15.4477 11 16C11 16.5523 11.4477 17 12 17C12.5523 17 13 16.5523 13 16Z" fill="#B5B5C3"></path>
                                                                <path d="M13 8C13 7.44772 12.5523 7 12 7C11.4477 7 11 7.44772 11 8C11 8.55228 11.4477 9 12 9C12.5523 9 13 8.55228 13 8Z" fill="#B5B5C3"></path>
                                                            </svg>
                                                        </div>
                                                        <ul class="dropdown-menu dropdown-menu-theme">
                                                            <li><div class="dropdown-item js-edit-fines">Редактировать</div></li>
                                                            <li><div class="dropdown-item js-delete-fines">Удалить</div></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                <?php }?>
                            </div>

                        </div>
                    </div>
                    <div class="card__content-tab active" id="tab-2">
                        <?php $controller->view('Application/application-doc'); ?>
                    </div>
                    <div class="card__content-tab" id="tab-3">
                        <?php $controller->view('Application/application-comment');  ?>
                    </div>
                    <?php if($controller->auth->user()->fullCRM()): ?>
                        <div class="card__content-tab" id="tab-4">
                        <?php foreach ($activityFeed as $item){ ?>
                            <div class="w-100 activity-feed-item d-flex">
                                <div class="col-1">
                                    <div style="width: 28px;height: 28px;border-radius: 50px;overflow: hidden;">
                                        <img class="avatar" src="<?php echo $item['user_avatar']; ?>" alt="" style="width: 100%;height: 100%;object-fit: contain;">
                                    </div>
                                </div>
                                <div class="col d-flex flex-column" style="max-width: 94%;">
                                    <div class="info d-flex flex-column">
                                        <div class="name">
                                            <?php echo $item['user_name']; ?> <span class="gray"> <?php echo $item['user_role']; ?></span>
                                        </div>
                                        <div class="datetime">
                                            <span class="gray">
                                                <?php echo date('d.m.Y H:i',strtotime($item["datetime"])); ?>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="changes w-100">
                                        <?php
                                            $changes = json_decode($item["changes"], true);
                                            foreach ($changes as $change){?>
                                                <div class="change d-flex flex-column mt-3 mb-4">
                                                    <?php if ($change["key"] === "file_delete"){?>
                                                        <div class="col d-flex mt-3 flex-column danger-bg">
                                                            <div class="col">
                                                                <?php
                                                                echo "Удалил файл ".$config->get("applicationFieldsNames.application_document_".$change["document_id"]."_type");?>
                                                            </div>
                                                            <div class="col">
                                                                <?php
                                                                echo "<span style='color: #f60909;'>" .$change["name"]."</span>";?>
                                                            </div>
                                                        </div>
                                                    <?php }
                                                    else if($change["key"] === "file_upload"){?>
                                                        <div class="col d-flex mt-3 flex-column success-bg">
                                                            <div class="col">
                                                                Загрузил файл(ы)
                                                            </div>
                                                            <?php foreach ($change["files"] as $changeFile) {?>
                                                                <div class="col mb-3">
                                                                    <?php
                                                                        echo $config->get("applicationFieldsNames.application_document_".$changeFile["document_id"]."_type"). "<br>".
                                                                            "<span style = 'color:#00a200;'>" .$changeFile["name"]."</span>";
                                                                    ?>

                                                                </div>
                                                            <?php }?>
                                                        </div>
                                                    <?php }
                                                    else{?>
                                                        <div class="col">
                                                            <?php
                                                            echo "Изменил ".$config->get("applicationFieldsNames.".$change["key"]);?>
                                                        </div>
                                                        <div class="col d-flex mt-3 flex-column" style=" max-width: 100%">
                                                            <div class="col d-flex flex-column gap-3 danger-bg">
                                                                <div class="col">
                                                                    Старое значение
                                                                </div>
                                                                <div class="col">
                                                                    <?php
                                                                    if ($change["key"] !== "transportation" && $change["key"] !== "fines" && $change["key"] !== "additional_expenses"){
                                                                        if ($change["key"] === "customer_id_Carrier" || $change["key"] === "customer_id_Client"){
                                                                            echo $customersIdToName[$change["oldValue"]];
                                                                        }
                                                                        elseif($change["key"] === "carrier_id_Carrier"){
                                                                            echo $carriersIdToName[$change["oldValue"]];
                                                                        }
                                                                        elseif($change["key"] === "client_id_Client"){
                                                                            echo $clientsIdToName[$change["oldValue"]];
                                                                        }
                                                                        elseif($change["key"] === "driver_id_Carrier" || $change["key"] === "driver_id_Client"){
                                                                            echo $driversIdToName[$change["oldValue"]];
                                                                        }
                                                                        else{
                                                                            echo $change["oldValue"];
                                                                        }
                                                                    }
                                                                    else{
                                                                        if ($change["key"] === "transportation"){
                                                                            foreach ($change["oldValue"] as $item) {
                                                                                echo '<div class="marshrut-list__item row marshrut-list__item mt-3 mb-3" style="width: 90%;margin-left: auto;margin-right: auto;padding: 5px;padding-top: 20px;border-radius: 20px;background: rgba(255, 255, 255, 0.4);">
                <div class="col-2">
                    <label for="">'.$item["direction"].'</label>
                    <span class="marshrut-address">'.$item["city"].$item["address"].'</span>
                </div>
                <div class="col-2">
                    <label for="">Дата</label>
                    <span class="marshrut-date">'.$item["date"].'</span>
                </div>
                <div class="col">
                    <label for="">Время</label>
                    <span class="marshrut-time">'.$item["time"].'</span>
                </div>
                <div class="col">
                    <label for="">Контактное лицо</label>
                    <span class="marshrut-contact-face">'.$item["contact"].'</span>
                </div>
                <div class="col">
                    <label for="">Номер</label>
                    <span class="marshrut-number">'.$item["phone"].'</span>
                </div>
                <div class="col">
                    <label for="">Способ</label>
                    <span class="marshrut-method">'.$item["loading_method"].'</span>
                </div>
            </div>';
                                                                            }
                                                                        }
                                                                        elseif($change["key"] === "fines"){
                                                                            foreach ($change["oldValue"] as $item) {
                                                                                echo '<div class="fines-item mt-3 mb-3" style="width: 90%;margin-left: auto;margin-right: auto;padding: 5px;padding-top: 20px;border-radius: 20px;background: rgba(255, 255, 255, 0.4);">
                                                                                        <div class="row">
                                                                                            <div class="col">
                                                                                                <label for="">Сумма, ₽</label>
                                                                                                <span class="fine-sum">'.$item["sum"].'</span>
                                                                                            </div>
                                                                                            <div class="col">
                                                                                                <label for="">Описание</label>
                                                                                                <span class="fine-description_${fineIndex}">'.$item["description"].'</span>
                                                                                            </div>
                                                                                            <div class="col">
                                                                                                <label for="">Дата оплаты</label>
                                                                                                <span class="fine-date_${fineIndex}">'.$item["datetime"].'</span>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>';
                                                                            }
                                                                        }
                                                                        elseif($change["key"] === "additional_expenses"){
                                                                            foreach ($change["oldValue"] as $item) {
                                                                                echo '<div class="expenses-item mt-3 mb-3" style="width: 90%;margin-left: auto;margin-right: auto;padding: 5px;padding-top: 20px;border-radius: 20px;background: rgba(255, 255, 255, 0.4);">
                <div class="row">
                    <div class="col">
                        <label for="">Вид затрат</label>
                        <span class="expense-type-expenses">'.$item["type_expenses"].'</span>
                    </div>
                    <div class="col">
                        <label for="">Сумма</label>
                        <span class="expense-sum">'.$item["sum"].'</span>
                    </div>
                    <div class="col">
                        <label for="">Вид налогооблажения</label>
                        <span class="expense-type-payment_${expenseIndex}">'.$item["type_payment"].'</span>
                    </div>
                    <div class="col">
                        <label for="">Комментарий</label>
                        <span class="expense-comment_${expenseIndex}">'.$item["comment"].'</span>
                    </div>
                </div>
            </div>';
                                                                            }
                                                                        }
                                                                    }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                            <div class="col d-flex flex-column gap-3 success-bg">
                                                                <div class="col">
                                                                    Новое значение
                                                                </div>
                                                                <div class="col">
                                                                    <?php
                                                                    if ($change["key"] !== "transportation" && $change["key"] !== "fines" && $change["key"] !== "additional_expenses"){
                                                                        if ($change["key"] === "customer_id_Carrier" || $change["key"] === "customer_id_Client"){
                                                                            echo $customersIdToName[$change["newValue"]];
                                                                        }
                                                                        elseif($change["key"] === "carrier_id_Carrier"){
                                                                            echo $carriersIdToName[$change["newValue"]];
                                                                        }
                                                                        elseif($change["key"] === "client_id_Client"){
                                                                            echo $clientsIdToName[$change["newValue"]];
                                                                        }
                                                                        elseif($change["key"] === "driver_id_Carrier" || $change["key"] === "driver_id_Client"){
                                                                            echo $driversIdToName[$change["newValue"]];
                                                                        }
                                                                        else{
                                                                            echo $change["newValue"];
                                                                        }
                                                                    }
                                                                    else{
                                                                        if ($change["key"] === "transportation"){
                                                                            foreach ($change["newValue"] as $item) {
                                                                                echo '<div class="marshrut-list__item row marshrut-list__item mt-3 mb-3" style="width: 90%;margin-left: auto;margin-right: auto;padding: 5px;padding-top: 20px;border-radius: 20px;background: rgba(255, 255, 255, 0.4);">
                <div class="col-2">
                    <label for="">'.$item["direction"].'</label>
                    <span class="marshrut-address">'.$item["city"].$item["address"].'</span>
                </div>
                <div class="col-2">
                    <label for="">Дата</label>
                    <span class="marshrut-date">'.$item["date"].'</span>
                </div>
                <div class="col">
                    <label for="">Время</label>
                    <span class="marshrut-time">'.$item["time"].'</span>
                </div>
                <div class="col">
                    <label for="">Контактное лицо</label>
                    <span class="marshrut-contact-face">'.$item["contact"].'</span>
                </div>
                <div class="col">
                    <label for="">Номер</label>
                    <span class="marshrut-number">'.$item["phone"].'</span>
                </div>
                <div class="col">
                    <label for="">Способ</label>
                    <span class="marshrut-method">'.$item["loading_method"].'</span>
                </div>
            </div>';
                                                                            }
                                                                        }
                                                                        elseif($change["key"] === "fines"){
                                                                            foreach ($change["newValue"] as $item) {
                                                                                echo '<div class="fines-item mt-3 mb-3" style="width: 90%;margin-left: auto;margin-right: auto;padding: 5px;padding-top: 20px;border-radius: 20px;background: rgba(255, 255, 255, 0.4);">
                <div class="row">
                    <div class="col">
                        <label for="">Сумма, ₽</label>
                        <span class="fine-sum">'.$item["sum"].'</span>
                    </div>
                    <div class="col">
                        <label for="">Описание</label>
                        <span class="fine-description_${fineIndex}">'.$item["description"].'</span>
                    </div>
                    <div class="col">
                        <label for="">Дата оплаты</label>
                        <span class="fine-date_${fineIndex}">'.$item["datetime"].'</span>
                    </div>
                </div>
            </div>';
                                                                            }
                                                                        }
                                                                        elseif($change["key"] === "additional_expenses"){
                                                                            foreach ($change["newValue"] as $item) {
                                                                                echo '<div class="expenses-item mt-3 mb-3" style="width: 90%;margin-left: auto;margin-right: auto;padding: 5px;padding-top: 20px;border-radius: 20px;background: rgba(255, 255, 255, 0.4);">
                <div class="row">
                    <div class="col">
                        <label for="">Вид затрат</label>
                        <span class="expense-type-expenses">'.$item["type_expenses"].'</span>
                    </div>
                    <div class="col">
                        <label for="">Сумма</label>
                        <span class="expense-sum">'.$item["sum"].'</span>
                    </div>
                    <div class="col">
                        <label for="">Вид налогооблажения</label>
                        <span class="expense-type-payment_${expenseIndex}">'.$item["type_payment"].'</span>
                    </div>
                    <div class="col">
                        <label for="">Комментарий</label>
                        <span class="expense-comment_${expenseIndex}">'.$item["comment"].'</span>
                    </div>
                </div>
            </div>';
                                                                            }
                                                                        }
                                                                    }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?}?>
                                                </div>
                                            <?php }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        <?php }?>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="card__content-side col-4">
                    <div class="card__content-side-header d-flex">
                        <div class="form__header-menu-list d-flex w-100">
                            <a class="form__header-menu-item ">
                                <div class="menu-item-click active js-link-tab-change side-js-link-tab-change"
                                     data-container=".card__content-side-tab" data-tab-app="#side-tab-1" data-prefix-tab=".side">
                                    Основное
                                </div>
                            </a>

                            <a class="mr-6 form__header-menu-item ">
                                <div class="menu-item-click js-link-tab-change side-js-link-tab-change"
                                     data-container=".card__content-side-tab" data-tab-app="#side-tab-2" data-prefix-tab=".side">
                                    Перевозчик
                                </div>
                            </a>

                            <a class="mr-6 form__header-menu-item ">
                                <div class="menu-item-click js-link-tab-change side-js-link-tab-change"
                                     data-container=".card__content-side-tab" data-tab-app="#side-tab-3" data-prefix-tab=".side">
                                    Клиент
                                </div>
                            </a>

                        </div>
                    </div>
                    <div class="card__content-side-tab active" id="side-tab-1">
                        <div class="side-block-info">
                            <div class="mb-4">
                                <span class="side-tab-span">Менеджер</span>
                                <div class="">
                                    <img alt="аватар"
                                         src="<?php echo $application->manager['img']; ?>"
                                         class="avatar avatar-28 photo my-class-1 super-gravarar" height="28" width="28" style="top:-5px;border-radius: 100%">
                                    <span class="side-main-span"><?php echo $application->manager['name']; ?></span>
                                </div>
                            </div>
                            <div class="mb-4">
                                <span class="side-tab-span">Маршрут</span>
                                <span class="side-main-span">
                                    <?php
                                        $textRoute = '';
                                        foreach ($application->carrier['transportation']['routes'] as $item):
                                            $textRoute .= $item['city'] .' - ';
                                        endforeach;
                                        $textRoute = trim($textRoute,' - ');

                                        echo $textRoute;
                                    ?>
                                </span>
                            </div>
                            <div class="mb-4">
                                <span class="side-tab-span">Клиент</span>
                                <span class="side-main-span"><?php echo $application->client['name']; ?></span>
                            </div>
                            <div class="mb-4">
                                <span class="side-tab-span">Перевозчик</span>
                                <span class="side-main-span"><?php echo $application->carrier['name']; ?></span>
                            </div>
                            <div class="mb-4">
                            <span class="side-tab-span">Водитель</span>
                            <span class="side-main-span"><?php echo $application->client['driver']['name']; ?></span>
                        </div>
                        </div>
                        <div class="side-block-status " style="visibility: hidden">
                            <h3>Статусы</h3>
                            <div class="status-item">
                                <span>Оплата клиентом</span>
                                <select name="" id="" class="form-select form-select-status w-50" data-name="client_payment_status" data-id="<?php echo $application->id; ?>">
                                    <option value="Ожидается счет" <?php if($application->status['client-payment-status'] == 'Ожидается счет') echo 'selected'; ?>>Ожидается счет</option>
                                    <option value="Запрошен" <?php if($application->status['client-payment-status'] == 'Запрошен') echo 'selected'; ?>>Запрошен</option>
                                    <option value="Сформирован" <?php if($application->status['client-payment-status'] == 'Сформирован') echo 'selected'; ?>>Сформирован</option>
                                    <option value="Отправлен клиенту" <?php if($application->status['client-payment-status'] == 'Отправлен клиенту') echo 'selected'; ?>>Отправлен клиенту</option>
                                    <option value="Оплачено частично" <?php if($application->status['client-payment-status'] == 'Оплачено частично') echo 'selected'; ?>>Оплачено частично</option>
                                    <option value="Оплачено полностью" <?php if($application->status['client-payment-status'] == 'Оплачено полностью') echo 'selected'; ?>>Оплачено полностью</option>
                                </select>
                            </div>
                            <div class="status-item">
                                <span>Оплата перевозчику</span>
                                <select name="" id="" class="form-select form-select-status w-50" data-name="carrier_payment_status" data-id="<?php echo $application->id; ?>">
                                    <option value="Ожидается счет" <?php if($application->status['carrier-payment-status'] == 'Ожидается счет') echo 'selected'; ?>>Ожидается счет</option>
                                    <option value="Запрошен" <?php if($application->status['carrier-payment-status'] == 'Запрошен') echo 'selected'; ?>>Запрошен</option>
                                    <option value="Сформирован" <?php if($application->status['carrier-payment-status'] == 'Сформирован') echo 'selected'; ?>>Сформирован</option>
                                    <option value="Отправлен клиенту" <?php if($application->status['carrier-payment-status'] == 'Отправлен клиенту') echo 'selected'; ?>>Отправлен клиенту</option>
                                    <option value="Оплачено частично" <?php if($application->status['carrier-payment-status'] == 'Оплачено частично') echo 'selected'; ?>>Оплачено частично</option>
                                    <option value="Оплачено полностью" <?php if($application->status['carrier-payment-status'] == 'Оплачено полностью') echo 'selected'; ?>>Оплачено полностью</option>
                                </select>
                            </div>
                            <div class="status-item">
                                <span>Документы с клиентом</span>
                                <select name="" id="" class="form-select form-select-status w-50" data-name="client_documents_status" data-id="<?php echo $application->id; ?>">
                                    <option value="Ожидается счет" <?php if($application->status['client-documents-status'] == 'Ожидается счет') echo 'selected'; ?>>Ожидается счет</option>
                                    <option value="Собраны оригиналы" <?php if($application->status['client-documents-status'] == 'Собраны оригиналы') echo 'selected'; ?>>Собраны оригиналы</option>
                                    <option value="Сформированы" <?php if($application->status['client-documents-status'] == 'Сформированы') echo 'selected'; ?>>Сформированы</option>
                                    <option value="Отправлены оригиналы" <?php if($application->status['client-documents-status'] == 'Отправлены оригиналы') echo 'selected'; ?>>Отправлены оригиналы</option>
                                </select>
                            </div>
                            <div class="status-item">
                                <span>Документы с перевозчиком</span>
                                <select name="" id="" class="form-select form-select-status w-50" data-name="carrier_documents_status" data-id="<?php echo $application->id; ?>">
                                    <option value="Ожидается счет" <?php if($application->status['carrier-documents-status'] == 'Ожидается счет') echo 'selected'; ?>>Ожидается счет</option>
                                    <option value="Собраны оригиналы" <?php if($application->status['carrier-documents-status'] == 'Собраны оригиналы') echo 'selected'; ?>>Собраны оригиналы</option>
                                    <option value="Сформированы" <?php if($application->status['carrier-documents-status'] == 'Сформированы') echo 'selected'; ?>>Сформированы</option>
                                    <option value="Отправлены оригиналы" <?php if($application->status['carrier-documents-status'] == 'Отправлены оригиналы') echo 'selected'; ?>>Отправлены оригиналы</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="card__content-side-tab" id="side-tab-2">
                        <div class="side-block-info">
                            <div class="mb-4">
                                <span class="side-tab-span">Менеджер</span>
                                <div class="">
                                    <img alt="аватар"
                                         src="<?php echo $application->manager['img']; ?>"
                                         class="avatar avatar-28 photo my-class-1 super-gravarar" height="28" width="28" style="top:-5px;border-radius: 100%">
                                    <span class="side-main-span"><?php echo $application->manager['name']; ?></span>
                                </div>
                            </div>
                            <div class="mb-4">
                                <span class="side-tab-span">Перевозчик</span>
                                <span class="side-main-span"><?php echo $application->carrier['name']; ?></span>
                            </div>
                            <div class="mb-4">
                                <span class="side-tab-span">Маршрут</span>
                                <span class="side-main-span">
                                    <?php
                                        $textRoute = '';
                                        foreach ($application->carrier['transportation']['routes'] as $item):
                                            $textRoute .= $item['city'] .' - ';
                                        endforeach;
                                        $textRoute = trim($textRoute,' - ');

                                        echo $textRoute;
                                    ?>
                                </span>
                            </div>

                            <div class="mb-4">
                                <span class="side-tab-span">Вид налогообложения</span>
                                <span class="side-main-span"><?php echo $application->carrier['type_taxation']; ?></span>
                            </div>
                        </div>

                        <?php $controller->view('Application/application-carrier-dop-info'); ?>

                    </div>
                    <div class="card__content-side-tab" id="side-tab-3" >
                        <div class="side-block-info">
                            <div class="mb-4">
                                <span class="side-tab-span">Менеджер</span>
                                <div class="">
                                    <img alt="аватар"
                                         src="<?php echo $application->manager['img']; ?>"
                                         class="avatar avatar-28 photo my-class-1 super-gravarar" height="28" width="28" style="top:-5px;border-radius: 100%">
                                    <span class="side-main-span"><?php echo $application->manager['name']; ?></span>
                                </div>
                            </div>
                            <div class="mb-4">
                                <span class="side-tab-span">Клиент</span>
                                <span class="side-main-span"><?php echo $application->client['name']; ?></span>
                            </div>
                            <div class="mb-4">
                                <span class="side-tab-span">Маршрут</span>
                                <span class="side-main-span">
                                    <?php
                                        $textRoute = '';
                                        foreach ($application->carrier['transportation']['routes'] as $item):
                                            $textRoute .= $item['city'] .'-';
                                        endforeach;
                                        $textRoute = trim($textRoute,' - ');

                                        echo $textRoute;
                                    ?>
                                </span>
                            </div>

                            <div class="mb-4">
                                <span class="side-tab-span">Вид налогообложения</span>
                                <span class="side-main-span"><?php echo $application->client['type_taxation']; ?></span>
                            </div>
                        </div>
                        <?php $controller->view('Application/application-client-dop-info'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal modal-add-application fade" id="modalComment" tabindex="-1" aria-labelledby="modalComment" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title title-base">Комментарий</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                </div>
                <div class="modal-body">
                    <div class="modal-comment-text" id="modal-comment-text">

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal modal-add-application fade" id="modalChangeManager" tabindex="-1" aria-labelledby="modalChangeManager" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title title-base">Выберите менеджера</h3>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                </div>
                <div class="modal-body">
                    <div class="modal-comment-text" id="modal-comment-text">
                        <label for="select-change-manager" class="mb-2">Менеджеры</label>
                        <select name="" id="select-change-manager" class="form-select js-chosen"
                                data-placeholder="Выберите менеджера" data-id-application="<?php echo $application->id; ?>">
                            <?php foreach ($listManager as $manager): ?>
                                <option value="<?php echo $manager->id(); ?>"><?php echo $manager->FIO(); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                    <button type="button" class="btn btn-primary" id="btn-change-manager">Сменить менеджера</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        $("#checkbox-prepayment-carrier").change(function () {
            let id = $(this).data('id-app');

            let status = $(this).is(':checked');

            console.log({
                id: id,
                status: status
            })

            $.ajax({
                url: '/application/ajax/change-prepayment-carrier',
                type: 'POST',
                data: {
                    id: id,
                    status: status
                },
                success: function (data) {
                    console.log(data);
                }
            })
        });
    </script>
    <script>
        $('.js-open-application-journal').click(function () {
            let id = $(this).data('id-app');

            document.location.href = '/journal-list?app-id=' + id;

        });
        $('#date-picker-receipt-all-documents').change(function (){
            let id = $(this).data('id-application');
            let date = $(this).val();

            $.ajax({
                url: '/application/ajax/change-date-receipt-all-documents',
                type: 'POST',
                data: {
                    id: id,
                    date: date
                },
                success: function (data) {
                    console.log(data);
                }
            })
        });

        $('.js-create-prr').click(function (){
            let id = $(this).data('id-app');

            document.location.href = '/prr/add?id-application=' + id;
        })
        <?php if($canDeleteFiles): ?>
        $(".application-comment-document").change(function () {
            let idApp = $(this).data('id-application');
            let numDoc = $(this).data('num-document');
            let name = $(this).data('name');
            let side = $(this).data('side');
            let comment = $(this).val();

            $.ajax({
                method: 'POST',
                url: '/application/ajax/comment-document',
                data: {idApp: idApp, numDoc: numDoc, name: name, side: side, comment: comment},
                success: function (response){
                    console.log(response)
                }
            })

            console.log({idApp: idApp, numDoc: numDoc, name: name, side: side, comment: comment});
        })
        <?php endif; ?>
    </script>
    <script>
        $('.list-item__type-document').on('dragover', function (e) {
            e.preventDefault();
            e.stopPropagation();
            $(this).addClass('active');
        });

        $('.list-item__type-document').on('dragleave', function (e) {
            e.preventDefault();
            e.stopPropagation();
            $(this).removeClass('active');
        });

        $('.list-item__type-document').on('drop', function (e) {
            // e.preventDefault();
            // e.stopPropagation();
            let $this = $(this);
            if(e.originalEvent.dataTransfer && e.originalEvent.dataTransfer.files.length) {
                console.log(e);
                e.preventDefault();
                e.stopPropagation();
                $('.quest__table-list-item').has($this).find('.upload_file_input').prop('files', e.originalEvent.dataTransfer.files);
                $('.quest__table-list-item').has(this).find('.upload_file_input').trigger('change');
            }

            $(this).removeClass('active');
        });

        $('#setting-menu,#btn-doc-menu').on('click', function (event) {
            event.stopPropagation();
        });
        $('body').click(function(){
            $('#setting-menu').removeClass('active');
        });
        const nameDocumentApplicationCarrier = '<?php echo $application->documentsName['application-carrier']; ?>';
        const nameDocumentApplicationClient = '<?php echo $application->documentsName['application-client']; ?>';
        const nameDocumentAttorneyDriver = '<?php echo $application->documentsName['attorney-driver']; ?>';
        const nameDocumentForwardingReceipt = '<?php echo $application->documentsName['forwarding-receipt']; ?>';
        const nameDocumentReceiptServices = '<?php echo $application->documentsName['receipt-services']; ?>';
        const nameDocumentInsurance = '<?php echo $application->documentsName['insurance']; ?>';
        const nameDocumentInfoDrive = '<?php echo $application->documentsName['info-driver']; ?>';

        const nameDocmentAttorneyM2 = '<?php echo $application->documentsName['attorney-m2']; ?>';


        $('#btn-change-manager').click(function () {
            let select = $('#select-change-manager');

            let id_application = select.data('id-application');
            let id_user = select.val();

            $.ajax({
                url:'/application/ajax/change-manager',
                method: 'POST',
                data: {id_application: id_application, id_user: id_user},
                success: function (response) {
                    let data = JSON.parse(response);

                    if(data['result']){
                        document.location.reload();
                    }
                }
            });
        });

        $('.add-comment-input').click(function (){
            $('.comment__add-input').has(this).find('.comment__buttons').addClass('active')
        });

        $('body').on('click','.js-change-important', function () {
            let $this = $(this);

            let id = $this.data('id-comment');

            $.ajax({
                url:'/application/ajax/change-important-comment',
                method: 'POST',
                data: {id: id},
                success: function (response) {
                    let data = JSON.parse(response);

                    if(data['result']){
                        $('.comment-item').has($this).find('.icon-important-comment').toggleClass('d-none');
                    }
                }
            });

        });

        $('body').on('click', '.js-delete-comment', function () {

            let id = $(this).data('id-comment');
            $('.comment-item').has(this).remove();

            $.ajax({
                url:'/application/ajax/delete-comment',
                method: 'POST',
                data: {id: id},
                success: function (response) {
                    let data = JSON.parse(response);
                }
            })
        });

        $('body').on('click', '.js-edit-comment', function () {

            let id = $(this).data('id-comment');

            let comment = $('.comment-item').has(this).find('.text-comment').text();

            $('#comment__edit-input').data('id-comment', id);

            $('#edit-comment-input').val(comment);

            $('#comment__edit-input').removeClass('d-none');
            $('#comment__add-input').addClass('d-none');
            $('#edit-comment-input').trigger('focus');

        });
        loadComment();
        function loadComment(){
            $.ajax({
                url: '/application/ajax/load-comment',
                method: 'POST',
                data: {id_application: <?php echo $application->id; ?>},
                success: function (response) {
                    console.log(response)
                    let data = JSON.parse(response);
                    console.log(data);

                    let htmlDefaultComment = ``;
                    let htmlCarrierFilesComment = ``;
                    let htmlClientFilesComment = ``;

                    for(let i = 0; i < data.length; i++){
                        let importantHide = 'd-none';
                        if(data[i]['important'])
                            importantHide = '';
                        let btn = `<div class="btn-comment"><div class="dropdown">
                                    <div class="dropdown-toggle dropdown-toggle-without-arrow" data-bs-toggle="dropdown" aria-expanded="false">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path opacity="0.3" d="M17 2H7C4.23858 2 2 4.23858 2 7V17C2 19.7614 4.23858 22 7 22H17C19.7614 22 22 19.7614 22 17V7C22 4.23858 19.7614 2 17 2Z" fill="#B5B5C3"></path>
                                            <path d="M13 12C13 11.4477 12.5523 11 12 11C11.4477 11 11 11.4477 11 12C11 12.5523 11.4477 13 12 13C12.5523 13 13 12.5523 13 12Z" fill="#B5B5C3"></path>
                                            <path d="M13 16C13 15.4477 12.5523 15 12 15C11.4477 15 11 15.4477 11 16C11 16.5523 11.4477 17 12 17C12.5523 17 13 16.5523 13 16Z" fill="#B5B5C3"></path>
                                            <path d="M13 8C13 7.44772 12.5523 7 12 7C11.4477 7 11 7.44772 11 8C11 8.55228 11.4477 9 12 9C12.5523 9 13 8.55228 13 8Z" fill="#B5B5C3"></path>
                                        </svg>
                                    </div>
                                    <ul class="dropdown-menu dropdown-menu-theme">
                                        <li><div class="dropdown-item js-delete-comment" data-id-comment="${data[i]['id']}">Удалить</div></li>
                                        <li><div class="dropdown-item js-edit-comment" data-id-comment="${data[i]['id']}">Редактировать</div></li>
                                        <li><div class="dropdown-item js-change-important" data-id-comment="${data[i]['id']}">Сделать важным</div></li>
                                    </ul>
                                </div></div>`;

                        if(! data[i]['edit_access'])
                            btn = '';
                        if(data[i]['type_comment'] === 0) {
                            htmlDefaultComment += `
                            <div class="w-100 d-flex comment-item align-items-start">
                                <div class="icon-important-comment ${importantHide}">
                                    <i class="bi bi-exclamation-circle text-danger"></i>
                                </div>
                                <img src="${data[i]['user_data']['avatar']}" alt="avatar" class="avatar">
                                <div class="mt-1">
                                    <div class="head-comment">
                                        ${data[i]['user_data']['name']}
                                        <span class="small">${data[i]['user_data']['role']}</span>
                                    </div>
                                    <span class="date-comment">
                                        ${data[i]['date_time']}
                                    </span>
                                    <p class="text-comment">${data[i]['comment']}</p>
                                </div>
                                ${btn}
                            </div>`;
                        }
                        if(data[i]['type_comment'] === 1) {
                            htmlCarrierFilesComment += `
                            <div class="w-100 d-flex comment-item align-items-start">
                                <div class="icon-important-comment ${importantHide}">
                                    <i class="bi bi-exclamation-circle text-danger"></i>
                                </div>
                                <img src="${data[i]['user_data']['avatar']}" alt="avatar" class="avatar">
                                <div class="mt-1">
                                    <div class="head-comment">
                                        ${data[i]['user_data']['name']}
                                        <span class="small">${data[i]['user_data']['role']}</span>
                                    </div>
                                    <span class="date-comment">
                                        ${data[i]['date_time']}
                                    </span>
                                    <p class="text-comment">${data[i]['comment']}</p>
                                </div>
                                ${btn}
                            </div>`;
                        }
                        if(data[i]['type_comment'] === 2) {
                            htmlClientFilesComment += `
                            <div class="w-100 d-flex comment-item align-items-start">
                                <div class="icon-important-comment ${importantHide}">
                                    <i class="bi bi-exclamation-circle text-danger"></i>
                                </div>
                                <img src="${data[i]['user_data']['avatar']}" alt="avatar" class="avatar">
                                <div class="mt-1">
                                    <div class="head-comment">
                                        ${data[i]['user_data']['name']}
                                        <span class="small">${data[i]['user_data']['role']}</span>
                                    </div>
                                    <span class="date-comment">
                                        ${data[i]['date_time']}
                                    </span>
                                    <p class="text-comment">${data[i]['comment']}</p>
                                </div>
                                ${btn}
                            </div>`;
                        }
                    }
                    $('.comments-list').html(htmlDefaultComment);
                    $('.comments-list-1').html(htmlCarrierFilesComment);
                    $('.comments-list-2').html(htmlClientFilesComment);
                }
            })
        }

        $('.close-input-comment').click(function () {
            let container = $('.comment__add-input').has(this);
            container.find('.add-comment-input').val('');
            container.find('.comment__buttons').removeClass('active');

        })

        $('.add-comment-button').click(function () {
            let container = $('.comment__add-input').has(this);

            let appId = container.data('id-app');

            let comment = container.find('.add-comment-input').val();
            container.find('.add-comment-input').val('');

            let typeComment = container.find('.add-comment-input').data('type-comment');

            if(!typeComment)
                typeComment = 0;


            $.ajax({
                url: '/application/ajax/add-comment',
                method: 'POST',
                data: {id_application: appId, comment: comment, type_comment: typeComment},
                success: function (response){
                    let data = JSON.parse(response)
                    console.log(data)
                    loadComment();
                }
            })

        });

        $('.edit-comment-button').click(function () {
            let container = $('.comment__add-input').has(this);

            let appId = container.data('id-app');
            let commentId = container.data('id-comment');

            let comment = container.find('.add-comment-input').val();

            container.find('.add-comment-input').val('');

            console.log({id_application: appId, comment: comment, comment_id: commentId})

            $.ajax({
                url: '/application/ajax/edit-comment',
                method: 'POST',
                data: {id_application: appId, comment: comment, comment_id: commentId},
                success: function (response){

                    let data = JSON.parse(response)
                    if(data['result']){
                        $('#comment__edit-input').addClass('d-none');
                        $('#comment__add-input').removeClass('d-none');
                        loadComment();

                    }
                }
            })

        });

        $('.js-copy-application').click(function () {
            let id = $(this).data('id-app');

            $.ajax({
                url: "/application/ajax/copy-application",
                method: "POST",
                data: {id: id},
                success: function (response){
                    document.location.href = '/application?copy=1&id=' + response;
                }
            });

        })
        $(".gggg").click(function (){
            $.ajax({
                url: "/application/ajax/copy-application",
                method: "POST",
                data: {id: <?php echo $application->id; ?>},
                success: function (response){
                    console.log(response);
                }
            });
        });

        $('.type-format-doc').click(function () {
            let name = $(this).text();

            if(name === 'Информация о счете')
                return false;

            let link = $(this).data('link');

            let dop = {
                seal: $('.item-doc-fotmat').has(this).find('.seal').is(':checked'),
                id_application: $('body').data('id-application')
            };

            console.log(dop)

            if(name === 'Доверенность на водителя')
                dop = {
                    id_application: $('body').data('id-application'),
                    seal: $('.item-doc-fotmat').has(this).find('.seal').is(':checked'),
                    signature: $('.item-doc-fotmat').has(this).find('.driver-signature').is(':checked'),
                    docx: $('.item-doc-fotmat').has(this).find('.docx').is(':checked')
                };

            if(name === 'Расписка об оказании услуг')
                dop = {
                    id_application: $('body').data('id-application'),
                    docx: $('.item-doc-fotmat').has(this).find('.docx').is(':checked')
                };

            if(name === 'Доверенность на водителя (М2)')
                dop = {
                    id_application: $('body').data('id-application'),
                }

            console.log('/application/ajax/document/' + link);

            $.ajax({
                url: '/application/ajax/document/' + link,
                method: 'POST',
                data: dop,
                success: function (response) {
                    console.log(response);

                    let data = JSON.parse(response);

                    console.log(data);

                    let nameFile = nameDocumentApplicationCarrier;

                    switch (name) {
                        case 'Доверенность на водителя':
                            nameFile = nameDocumentAttorneyDriver + data['number_attorney'];
                            break;
                        case 'Расписка об оказании услуг':
                            nameFile = nameDocumentReceiptServices + data['number_receipt_services'];
                            break;
                        case 'Договор-заявка с клиентом':
                            nameFile = nameDocumentApplicationClient;
                            break;
                        case 'Экспедиторская расписка':
                            nameFile = nameDocumentForwardingReceipt;
                            break;
                        case 'Страховка груза':
                            nameFile = nameDocumentInsurance;
                            break;
                        case 'Информация по водителю':
                            nameFile = nameDocumentInfoDrive;
                            break;
                        case 'Доверенность на водителя (М2)':
                            nameFile = nameDocmentAttorneyM2;
                            break;
                    }

                    if(data['result'])
                        download_file(nameFile + '.' + data['extension'], data['link_file']);
                }
            })
        })
        $('#btn-doc-menu').click(function () {
            $('#setting-menu').toggleClass('active');
        })
        $('.form-select-status').change(function (){
            let name = $(this).data('name');
            let value = $(this).val();
            let id = $(this).data('id');
            
            $.ajax({
                url: '/application/ajax/change-status-application',
                method: 'POST',
                data: {name:name, value:value, id:id},
                success: function (response) {
                    console.log(response)
                }
            })

            console.log([name,value,id]);
        })
          
        $('.dop-info-collapse-header').click(function () {
            $(this).find('.icon').toggleClass('active');
        })
        $('.js-link-tab-change').click(function () {
            let tabId = $(this).data('tab-app');
            let container = $(this).data('container');
            let prefix = $(this).data('prefix-tab');
            $(prefix + '-js-link-tab-change').removeClass('active');
            $(this).addClass('active');
            $(container).removeClass('active');
            $(tabId).addClass('active');
        });
        function addTask(applicationId){
            $.ajax({
                url: "/application/ajax/addTask",
                method: "POST",
                data: {application_id: applicationId},
                success: function (response){
                    console.log(response);
                    if (response !== "Error"){
                        let data = JSON.parse(response);
                        console.log(data);

                        let deadline = ""
                        if (data['status'] === '0'){
                            console.log("status = 0");
                            deadline = "<span class='task-date_completion orange'> Не сделано </span>";
                        }

                        $('.quest__table').append(`
                        <div class="quest__table-list quest__table-tasks">
                                <div class="quest__table-task">
                                    <div class="quest__table-task-name">
                                        <div class="name">
                                            ${data['name']}
                                        </div>
                                        <div class="date">
                                            ${data['create_datetime']}
                                        </div>
                                    </div>
                                    <div class="quest__table-task-deadline">
                                        ${data['deadline']}
                                    </div>
                                    <div class="quest__table-task-date_completion">
                                        <span class='task-date_completion orange'> Не сделано </span>
                                    </div>
                                    <div class="quest__table-task-comment">
                                        <span onclick="openCommentModal(${data['id']});" data-bs-toggle="modal" data-bs-target="#modalComment">ПРОСЬБА ...</span>
                                    </div>
                                    <div class="quest__table-task-full-comment d-none task-comment_${data['id']}">
                                        ${data['comment']}
                                    </div>
                                    <div class="quest__table-task-executor d-flex">
                                        <div class="avatar">
                                            <img src="/assets/img/empty_avatar.png" alt="">
                                        </div>
                                        <div class="name">

                                            <br>
                                            <span class="gray">Бухгалтер</span>
                                        </div>
                                    </div>
                                    <div class="quest__table-task-status">
                                        <select name="task_status" id="status" value="Сделать"
                                                class="select-status-light status-type blue select2-hidden-accessible"
                                                tabindex="-1" aria-hidden="true" data-select2-id="select2-data-status">
                                            <option value="В работе" selected="" data-select2-id="select2-data-69-ubkx">В работе</option>
                                            <option value="В пути">В пути</option>
                                            <option value="Выгрузился">Выгрузился</option>
                                            <option value="Завершена (оплата)">Завершена (оплата)</option>
                                            <option value="Завершена (док.)">Завершена (док.)</option>
                                            <option value="Завершена">Завершена</option>
                                        </select>
                                    </div>
                                    <div class="quest__table-task-status-btns">
                                        <div class="settings col-2 p-0 row justify-content-end">
                                            <div class="dropdown">
                                                <div class="dropdown-toggle dropdown-toggle-without-arrow" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path opacity="0.3" d="M17 2H7C4.23858 2 2 4.23858 2 7V17C2 19.7614 4.23858 22 7 22H17C19.7614 22 22 19.7614 22 17V7C22 4.23858 19.7614 2 17 2Z" fill="#B5B5C3"></path>
                                                        <path d="M13 12C13 11.4477 12.5523 11 12 11C11.4477 11 11 11.4477 11 12C11 12.5523 11.4477 13 12 13C12.5523 13 13 12.5523 13 12Z" fill="#B5B5C3"></path>
                                                        <path d="M13 16C13 15.4477 12.5523 15 12 15C11.4477 15 11 15.4477 11 16C11 16.5523 11.4477 17 12 17C12.5523 17 13 16.5523 13 16Z" fill="#B5B5C3"></path>
                                                        <path d="M13 8C13 7.44772 12.5523 7 12 7C11.4477 7 11 7.44772 11 8C11 8.55228 11.4477 9 12 9C12.5523 9 13 8.55228 13 8Z" fill="#B5B5C3"></path>
                                                    </svg>
                                                </div>
                                                <ul class="dropdown-menu dropdown-menu-theme">
                                                    <li><div class="dropdown-item js-edit-fines">Редактировать</div></li>
                                                    <li><div class="dropdown-item js-delete-fines">Удалить</div></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>`);

                    }
                }
            });
        }

        for (let i = 0; i < 20; i++){
            $('#input-file'+i).on('change', function (){
                $('#form_file'+i).trigger('submit');
            });
        }

        $('.form-upload-file').submit(function (e) {
            e.preventDefault();

            let doc_id = $(this).attr('id').replace('form_file', '');

            let form = $(this).serializeArray();

            var formData = new FormData();

            let fileInput = $(this).children('input');
            for (let i = 0; i< fileInput[0].files.length; i++){
                formData.append('file_'+i, fileInput[0].files[i]);

            }

            formData.append('application_id', <?php echo $application->id?>);
            formData.append('doc_id', doc_id);

            let this_ = $(this);

            $.ajax({
                url: '/application/ajax/uploadFiles',
                method: 'POST',
                processData: false,
                contentType: false,
                data: formData,
                success: function (data_json){
                    console.log(data_json)
                    let data = JSON.parse(data_json);
                    console.log(data);

                    this_.parent().prev('.list-item__document-rows').children().remove('.list-item__document-row');
                    this_.children().remove('.doc-upload-btn');

                    for (let i = 0; i < data.length; i++){
                        this_.parent().prev('.list-item__document-rows').append(`
                        <div class="list-item__document-row document-row_${data[i]['id']}">
                            <div class="list-item__file-name">
                                <a href="${data[i]['link']}">${data[i]['name']}</a>
                            </div>
                            <div class="data-and-btn m-0 align-items-center d-flex gap-2">
                                <span class="data-date">${data[i]['date']}</span>
                                <a href="${data[i]['link']}" download="${data[i]['name']}" class="list-item__download-file btn"><i class="bi bi-cloud-download"></i></a>
                                <button class="list-item__delete-file-btn btn" data-file-id = "${data[i]['id']}" onclick="deleteFile(${data[i]['id']});">
                                    <i class="bi bi-file-earmark-x"></i>
                                </button>
                            </div>
                        </div>`);
                    }
                }
            });
        });

        function openCommentModal(id){
            console.log($('.task-comment_'+id).html());
            $('#modal-comment-text').html($('.task-comment_'+id).html());
        }
        <?php if($canDeleteFiles): ?>
        function deleteFile(id){
            $.ajax({
                url: "/application/ajax/deleteFile",
                method: "POST",
                data: {id: id, user_id: <?php echo $controller->auth->user()->get(["id"])["id"]?>},
                success: function (response){
                    if (response === "Success"){
                        if ($('.document-row_'+id).parent('.list-item__document-rows').children().length < 2){
                            $('.document-row_'+id).parent('.list-item__document-rows').append(`<div class="list-item__document-row">
                            <div class="list-item__file-name">

                            </div>
                            <div class="data-and-btn m-0">
                                <span class="file-data-none">Не загружено</span>
                            </div>
                        </div>`);

                            $('.document-row_'+id).parent('.list-item__document-rows').parent('.quest__table-list-item').children('.form-check').children('form').append(`
                            <button type="button" class="btn doc-upload-btn">
                                <i class="bi bi-upload"></i>
                            </button>
                        `);

                            $('input[type=file]').val('');


                            setupTriggerInputFile();
                        }

                        $('.document-row_'+id).remove();
                    }
                    else{
                        alert("Ошибка! Файл не был удален!");
                    }
                }
            });
        }
        <?php endif; ?>
        function setupTriggerInputFile(){
            $('.doc-upload-btn').on('click', function (){
                $(this).prev('input').trigger('click');
            });

        }

        setupTriggerInputFile();
    </script>
</body>
</html>