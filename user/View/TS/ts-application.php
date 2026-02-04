<?php
/**
 * @var App\User\Contoller\Common\ApplicationController $controller
 */
/** @var App\User\Model\Application\ApplicationPage $application */
/** @var array $listManager */
/** @var App\Model\User\User $userManager */
/** @var App\Model\TSApplication\Forwarder $forwarder */
/** @var App\Model\TSApplication\TSApplication $TSApplication */


$controller->view('Components/head');

$TSApplicationData = $TSApplication->get();
//dd($TSApplication->getAdditionalExpensesList());
?>

<body data-id-application="<?php echo $TSApplicationData['id']; ?>">
    <?php $controller->view('Components/header'); ?>

    <div class="card-application">

        <div class="card-wrapper wrapper">
            <div class="card__header row align-items-center">
                <div class="card__header-title col-10 d-flex align-items-center">
                    № <?php echo $TSApplicationData['application_number']; ?>
                    <span style="margin-left: 9.5px; color:#A1A5B7;">
                        <svg width="10" height="13" viewBox="0 0 10 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M8.375 0.5C8.98438 0.5 9.5 1.01562 9.5 1.625V11.375C9.5 12.0078 8.98438 12.5 8.375 12.5H6.125V10.625C6.125 10.0156 5.60938 9.5 5 9.5C4.36719 9.5 3.875 10.0156 3.875 10.625V12.5H1.625C0.992188 12.5 0.5 12.0078 0.5 11.375V1.625C0.5 1.01562 0.992188 0.5 1.625 0.5H8.375ZM2 6.875C2 7.08594 2.16406 7.25 2.375 7.25H3.125C3.3125 7.25 3.5 7.08594 3.5 6.875V6.125C3.5 5.9375 3.3125 5.75 3.125 5.75H2.375C2.16406 5.75 2 5.9375 2 6.125V6.875ZM4.625 5.75C4.41406 5.75 4.25 5.9375 4.25 6.125V6.875C4.25 7.08594 4.41406 7.25 4.625 7.25H5.375C5.5625 7.25 5.75 7.08594 5.75 6.875V6.125C5.75 5.9375 5.5625 5.75 5.375 5.75H4.625ZM6.5 6.875C6.5 7.08594 6.66406 7.25 6.875 7.25H7.625C7.8125 7.25 8 7.08594 8 6.875V6.125C8 5.9375 7.8125 5.75 7.625 5.75H6.875C6.66406 5.75 6.5 5.9375 6.5 6.125V6.875ZM2.375 2.75C2.16406 2.75 2 2.9375 2 3.125V3.875C2 4.08594 2.16406 4.25 2.375 4.25H3.125C3.3125 4.25 3.5 4.08594 3.5 3.875V3.125C3.5 2.9375 3.3125 2.75 3.125 2.75H2.375ZM4.25 3.875C4.25 4.08594 4.41406 4.25 4.625 4.25H5.375C5.5625 4.25 5.75 4.08594 5.75 3.875V3.125C5.75 2.9375 5.5625 2.75 5.375 2.75H4.625C4.41406 2.75 4.25 2.9375 4.25 3.125V3.875ZM6.875 2.75C6.66406 2.75 6.5 2.9375 6.5 3.125V3.875C6.5 4.08594 6.66406 4.25 6.875 4.25H7.625C7.8125 4.25 8 4.08594 8 3.875V3.125C8 2.9375 7.8125 2.75 7.625 2.75H6.875Z" fill="#A1A5B7"></path>
                        </svg>
                    </span>

                </div>
                <div class="row col-2 justify-content-end align-items-center">
                    <div class="card__header-data col-5 p-0"><?php echo date('d.m.Y', strtotime($TSApplicationData['date'])); ?></div>
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
                                <li><a href="/ts/edit?id=<?php echo $TSApplicationData['id'];?>" class="dropdown-item">Редактировать</a></li>
<!--                                <li><div class="dropdown-item js-delete-fines">В архив</div></li>-->
<!--                                <li><div class="dropdown-item js-delete-fines">Удалить</div></li>-->
                                <li><div class="dropdown-item js-copy-application" data-id-app="<?php echo $TSApplicationData['id'];?>">Копировать</div></li>
                                <li><div class="dropdown-item js-change-manager" data-bs-toggle="modal" data-bs-target="#modalChangeManager">Сменить менеджера</div></li>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>

            <div class="card__content d-flex">
                <div class="card__content-main d-flex flex-column">
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
                                        <span class="type-format-doc" data-link="agreement-application-client">Договор-заявка с клиентом</span>
                                        <div class="form-check form-check-custom form-check-solid form-check-sm align-items-start">

                                            <input checked="" class="form-check-input seal" data-name="seal"
                                                   id="check-dogovor-perevozhik" type="checkbox" value="">
                                            <label for="check-dogovor-perevozhik">С печатью</label>
                                        </div>
                                    </div>

                                    <span class="type-format-doc" onclick="addTask(<?php echo $TSApplicationData['id'];  ?>);">Информация о счете</span>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card__content-main-harakter_gruza">
                        <p><?php echo $TSApplicationData['nature_cargo'];?></p>
                    </div>
                    <div class="card__content-main__header  d-flex">

                        <div class="form__header-menu-list d-flex w-100">
                            <a class="form__header-menu-item">
                                <div class="menu-item-click active js-link-tab-change main-header-js-link-tab-change"
                                     data-container=".card__content-tab" data-tab-app="#tab-1" data-prefix-tab=".main-header">
                                    Задачи
                                </div>
                            </a>

                            <a class="mr-6 form__header-menu-item ">
                                <div class="menu-item-click js-link-tab-change main-header-js-link-tab-change"
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
                    <div class="card__content-tab active" id="tab-1">
                        <div class="quest__table">
                            <div class="quest__table-header d-flex">
                                <span>Название</span>
                                <span>Крайний срок</span>
                                <span>Дата выполнения</span>
                                <span>Комментарий</span>
                                <span>Исполнитель</span>
                                <span>Статус</span>
                                <div class="quest__table-task-status-btns unvisible" style="    visibility: hidden;">
                                    <button>gg</button>
                                </div>
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
                    <div class="card__content-tab" id="tab-2">
                        <?php $controller->view('TS/ts-application-doc'); ?>
                    </div>
                    <div class="card__content-tab" id="tab-3">
                        <?php $controller->view('TS/ts-application-comment');  ?>
                    </div>
                    <?php if($controller->auth->user()->fullCRM()): ?>
                        <div class="card__content-tab" id="tab-4">

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
                                    Экспедитор
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
                                         src="<?php echo $userManager->avatar(); ?>"
                                         class="avatar avatar-28 photo my-class-1 super-gravarar" height="28" width="28" style="top:-5px;border-radius: 100%">
                                    <span class="side-main-span"><?php echo $userManager->fullName(); ?></span>
                                </div>
                            </div>
                            <div class="mb-4">
                                <span class="side-tab-span">Маршрут</span>
                                <span class="side-main-span">
                                    <?php
                                        $textRoute = '';
                                        foreach ($TSApplication->getTransportationList() as $item):
                                            $textRoute .= $item['city'] .' - ';
                                        endforeach;
                                        $textRoute = trim($textRoute,' - ');

                                        echo $textRoute;
                                    ?>
                                </span>
                            </div>
                            <div class="mb-4">
                                <span class="side-tab-span">Экспедитор</span>
                                <span class="side-main-span"><?php echo $forwarder->get()['name']; ?></span>
                            </div>
                            <div class="mb-4">
                            <span class="side-tab-span">Водитель</span>
                            <span class="side-main-span"><?php echo $TSApplication->getDriverInfo()['name']; ?></span>
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
                                         src="<?php echo $userManager->avatar(); ?>"
                                         class="avatar avatar-28 photo my-class-1 super-gravarar" height="28" width="28" style="top:-5px;border-radius: 100%">
                                    <span class="side-main-span"><?php echo $userManager->fullName(); ?></span>
                                </div>
                            </div>
                            <div class="mb-4">
                                <span class="side-tab-span">Экспедитор</span>
                                <span class="side-main-span"><?php echo $forwarder->get()['name']; ?></span>
                            </div>
                            <div class="mb-4">
                                <span class="side-tab-span">Маршрут</span>
                                <span class="side-main-span">
                                    <?php
                                        $textRoute = '';
                                        foreach ($TSApplication->getTransportationList() as $item):
                                            $textRoute .= $item['city'] .' - ';
                                        endforeach;
                                        $textRoute = trim($textRoute,' - ');

                                        echo $textRoute;
                                    ?>
                                </span>
                            </div>

                            <div class="mb-4">
                                <span class="side-tab-span">Вид налогообложения</span>
                                <span class="side-main-span"><?php echo $TSApplicationData['taxation_type']; ?></span>
                            </div>
                        </div>

                        <?php $controller->view('TS/ts-application-forwarder-dop-info'); ?>

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
                                data-placeholder="Выберите менеджера" data-id-application="<?php echo $TSApplicationData['id']; ?>">
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
    <script>
        $('.add-comment-input').click(function (){
            $('.comment__add-input').has(this).find('.comment__buttons').addClass('active')
        });

        $('body').on('click','.js-change-important', function () {
            let $this = $(this);

            let id = $this.data('id-comment');

            $.ajax({
                url:'/ts-application/ajax/change-important-comment',
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
                url:'/ts-application/ajax/delete-comment',
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
                url: '/ts-application/ajax/load-comment',
                method: 'POST',
                data: {id_application: <?php echo $TSApplicationData['id']; ?>},
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
                url: '/ts-application/ajax/add-comment',
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
                url: '/ts-application/ajax/edit-comment',
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
    </script>
    <script>
        const nameDocumentApplicationCarrier = '';
        const nameDocumentApplicationClient = '';
        const nameDocumentAttorneyDriver = '';
        const nameDocumentForwardingReceipt = '';
        const nameDocumentReceiptServices = 'Расписка об оказании услуг ';
        const nameDocumentInsurance = '';
        const nameDocumentInfoDrive = ''

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

            if(name === 'Текстовое описание'){
                dop = {
                    id_application: $('body').data('id-application'),
                };
            }

            if(name === 'Договор-заявка с клиентом'){
                dop = {
                    id_application: $('body').data('id-application'),
                    seal: $('.item-doc-fotmat').has(this).find('.seal').is(':checked'),
                };
            }


            console.log('/prr/ajax/document/' + link)

            $.ajax({
                url: '/ts/ajax/document/' + link,
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
                            nameFile = 'Договор-заявка с клиентом';
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
                    }


                    if(name === 'Текстовое описание' && data['status']){
                        $('#description').html(data['text']);
                        return 0;
                    }

                    if(data['result'])
                        download_file(nameFile + '.' + data['extension'], data['link_file']);
                }
            })
        })
    </script>
    <script>
        function openCommentModal(id){
            console.log($('.task-comment_'+id).html());
            $('#modal-comment-text').html($('.task-comment_'+id).html());
        }
        function addTask(applicationId){
            $.ajax({
                url: "/ts/ajax/addTask",
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
    </script>

    <script>
        $('#setting-menu,#btn-doc-menu').on('click', function (event) {
            event.stopPropagation();
        });
        $('#btn-doc-menu').click(function () {
            $('#setting-menu').toggleClass('active');
        });
        $('body').click(function(){
            $('#setting-menu').removeClass('active');
        });
    </script>
    <script>
        $(".application-comment-document").change(function () {
            let idApp = $(this).data('id-application');
            let numDoc = $(this).data('num-document');
            let name = $(this).data('name');
            let comment = $(this).val();

            $.ajax({
                method: 'POST',
                url: '/ts/application/ajax/comment-document',
                data: {idApp: idApp, numDoc: numDoc, name: name, comment: comment},
                success: function (response){
                    console.log(response)
                }
            })

            console.log({idApp: idApp, numDoc: numDoc, name: name, comment: comment});
        })
        function deleteFile(id){
            $.ajax({
                url: "/ts/application/ajax/deleteFile",
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
                        console.log(response)
                        alert("Ошибка! Файл не был удален!");
                    }
                }
            });
        }
        function setupTriggerInputFile(){
            $('.doc-upload-btn').on('click', function (){
                $(this).prev('input').trigger('click');
            });

        }

        setupTriggerInputFile();
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

            formData.append('application_id', <?php echo $TSApplicationData['id'];?>);
            formData.append('doc_id', doc_id);

            let this_ = $(this);

            $.ajax({
                url: '/ts/application/ajax/uploadFiles',
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
    </script>
    <script>
        $('.js-link-tab-change').click(function () {
            let tabId = $(this).data('tab-app');
            let container = $(this).data('container');
            let prefix = $(this).data('prefix-tab');
            $(prefix + '-js-link-tab-change').removeClass('active');
            $(this).addClass('active');
            $(container).removeClass('active');
            $(tabId).addClass('active');
        });
    </script>
</body>
</html>