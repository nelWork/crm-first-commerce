<?php

/** @var App\User\Contoller\Common\HomeController $controller */
/** @var String $titlePage */
$controller->view('Components/head');
?>


    <body>
        <?php $controller->view('Components/header'); ?>
        <main class="applications">
            <div class="sub-header" style="padding-bottom: 0;">
                <div class="wrapper">
                    <?php $controller->view('Components/breadcrumbs'); ?>

                    <h3 class="sub-header-title"><?php echo $titlePage; ?></h3>

                    <div class="row" style="margin-top: 40px;">
                        <div class="col-8">
                            <ul class="nav nav-underline nav-subheader">
                                <li class="nav-item">
                                    <a href="#" class="nav-link active">Основные</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">Архив</a>
                                </li>
                            </ul>
                        </div>
                        <div class="col">

                        </div>
                    </div>
                </div>
            </div>
            <div class="wrapper">
                <div class="post-list">
                    <div class="post-list__header">
                        <div class="post-list__header-item">№ Заявки</div>
                        <div class="post-list__header-item">Клиент / водитель</div>
                        <div class="post-list__header-item">Маршрут</div>
                        <div class="post-list__header-item">Оплата Клиентом</div>
                        <div class="post-list__header-item">Оплата Перевозчику</div>
                        <div class="post-list__header-item">документы с Клиентом</div>
                        <div class="post-list__header-item">док... с Перевозчиком</div>
                        <div class="post-list__header-item"></div>
                    </div>
                    <div class="post-list__items">
                        <div class="post-list__item-container">
                            <a href="#" class="post-list__item item" post-id="26058" id="0">
                                <div class="item__number-zayavka d-flex">
                                    <span style="margin-right: 9.5px;">
                                        <svg width="10" height="13" viewBox="0 0 10 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M8.375 0.5C8.98438 0.5 9.5 1.01562 9.5 1.625V11.375C9.5 12.0078 8.98438 12.5 8.375 12.5H6.125V10.625C6.125 10.0156 5.60938 9.5 5 9.5C4.36719 9.5 3.875 10.0156 3.875 10.625V12.5H1.625C0.992188 12.5 0.5 12.0078 0.5 11.375V1.625C0.5 1.01562 0.992188 0.5 1.625 0.5H8.375ZM2 6.875C2 7.08594 2.16406 7.25 2.375 7.25H3.125C3.3125 7.25 3.5 7.08594 3.5 6.875V6.125C3.5 5.9375 3.3125 5.75 3.125 5.75H2.375C2.16406 5.75 2 5.9375 2 6.125V6.875ZM4.625 5.75C4.41406 5.75 4.25 5.9375 4.25 6.125V6.875C4.25 7.08594 4.41406 7.25 4.625 7.25H5.375C5.5625 7.25 5.75 7.08594 5.75 6.875V6.125C5.75 5.9375 5.5625 5.75 5.375 5.75H4.625ZM6.5 6.875C6.5 7.08594 6.66406 7.25 6.875 7.25H7.625C7.8125 7.25 8 7.08594 8 6.875V6.125C8 5.9375 7.8125 5.75 7.625 5.75H6.875C6.66406 5.75 6.5 5.9375 6.5 6.125V6.875ZM2.375 2.75C2.16406 2.75 2 2.9375 2 3.125V3.875C2 4.08594 2.16406 4.25 2.375 4.25H3.125C3.3125 4.25 3.5 4.08594 3.5 3.875V3.125C3.5 2.9375 3.3125 2.75 3.125 2.75H2.375ZM4.25 3.875C4.25 4.08594 4.41406 4.25 4.625 4.25H5.375C5.5625 4.25 5.75 4.08594 5.75 3.875V3.125C5.75 2.9375 5.5625 2.75 5.375 2.75H4.625C4.41406 2.75 4.25 2.9375 4.25 3.125V3.875ZM6.875 2.75C6.66406 2.75 6.5 2.9375 6.5 3.125V3.875C6.5 4.08594 6.66406 4.25 6.875 4.25H7.625C7.8125 4.25 8 4.08594 8 3.875V3.125C8 2.9375 7.8125 2.75 7.625 2.75H6.875Z" fill="#A1A5B7"></path>
                                        </svg>
                                    </span>
                                    <div>
                                        4260
                                        <span>20.05.2024</span>
                                    </div>


                                </div>
                                <div class="item__osnovnoe">
                                    ООО  «ЮНИОН ПОЛИМЕР ТЕХНОЛОДЖИ»
                                    <span>Макаров Иван Евгеньевич<br>
                                          8 (917)465-37-42
                                    </span>
                                </div>
                                <div class="item__trasportirovka">

                                    <div class="item__trasportirovka-item">
                                        Московская обл, г.о. Пушкинский, рп Софрино
                                        <span> · 20.05.2024</span>
                                    </div>
                                    <div class="item__trasportirovka-item">
                                        Респ Башкортостан, р-н Туймазинский, г Туймазы
                                        <span> · 22.05.2024</span>
                                    </div>

                                </div>

                                <div class="item__statusy-oplata_klientom">
                                    <span class="status-type grey">Ожидается счет</span>
                                </div>
                                <div class="item__statusy-oplata_perevozchiku">
                                    <span class="status-type grey">Ожидается счет</span>
                                </div>
                                <div class="item__statusy-dokumenty_s_klientom">
                                    <span class="status-type grey">Ожидается счет</span>
                                </div>
                                <div class="item__statusy-oplata_klientom doc-carrier ">
                                    <span class="status-type grey">Ожидается счет</span>
                                </div>
                                <div class="item__info d-flex">
                                    <div class="avatar">
                                        <img alt="аватар" src="https://secure.gravatar.com/avatar/ad516503a11cd5ca435acc9bb6523536?s=150&amp;d=mm&amp;r=gforcedefault=1" srcset="https://secure.gravatar.com/avatar/ad516503a11cd5ca435acc9bb6523536?s=150&amp;d=mm&amp;r=gforcedefault=1 2x" class="avatar avatar-28 photo my-class-1 super-gravarar" height="28" width="28" style="top:-5px;" title="Миша в горах" decoding="async">                                                                                        <span>Вадим Г.</span>
                                    </div>
                                    <div class="item__select-status">
                                        <select class="list-select select-status select2-hidden-accessible" name="" id="" value="В работе" onchange="changeStatus(jQuery(this).val(), 26058)" tabindex="-1" aria-hidden="true" data-select2-id="select2-data-4454-ma55">
                                            <option index-color="1" value="В работе" selected="" data-select2-id="select2-data-4456-jub6">В работе</option>
                                            <option index-color="2" value="В пути">В пути</option>
                                            <option index-color="3" value="Выгрузился">Выгрузился</option>
                                            <option index-color="4" value="Завершена (оплата)">Завершена (оплата)</option>
                                            <option index-color="5" value="Завершена (док.)">Завершена (док.)</option>
                                            <option index-color="6" value="Завершена">Завершена</option>
                                        </select><span class="select2 select2-container select2-container--bootstrap5" dir="ltr" data-select2-id="select2-data-4455-wjsi" style="width: 100%;"><span class="selection"><span class="select2-selection select2-selection--single list-select select-status" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-disabled="false" aria-labelledby="select2--container" aria-controls="select2--container" style="
                             border: 1px solid #3E97FF;
                             color: #3E97FF;
                            "><span class="select2-selection__rendered" id="select2--container" role="textbox" aria-readonly="true" title="В работе" style="
                            color: #3E97FF;
                            ">В работе</span><span class="select2-selection__arrow" role="presentation" style="
                            background: #3E97FF;
                            "><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span>
                                    </div>
                                    <div class="comment-link" data-modal="сomment-modal" post-id="26058">
                                        <svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path opacity="0.3" d="M20 3.5H4C2.89543 3.5 2 4.39543 2 5.5V16.5C2 17.6046 2.89543 18.5 4 18.5H4.5C5.05228 18.5 5.5 18.9477 5.5 19.5V22.0052C5.5 22.6441 6.21212 23.0253 6.74376 22.6708L11.4885 19.5077C12.4741 18.8506 13.6321 18.5 14.8167 18.5H20C21.1046 18.5 22 17.6046 22 16.5V5.5C22 4.39543 21.1046 3.5 20 3.5Z" fill="#B5B5C3"></path>
                                            <path d="M12 12.5H7C6.44772 12.5 6 12.9477 6 13.5C6 14.0523 6.44772 14.5 7 14.5H12C12.5523 14.5 13 14.0523 13 13.5C13 12.9477 12.5523 12.5 12 12.5Z" fill="#B5B5C3"></path>
                                            <path d="M17 7.5H7C6.44772 7.5 6 7.94772 6 8.5C6 9.05228 6.44772 9.5 7 9.5H17C17.5523 9.5 18 9.05228 18 8.5C18 7.94772 17.5523 7.5 17 7.5Z" fill="#B5B5C3"></path>
                                        </svg>
                                    </div>

                                    <div class="settings">
                                        <div class="settings-icon" onclick="jQuery(this).parent().find('.settings-menu').toggleClass('active');">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path opacity="0.3" d="M17 2H7C4.23858 2 2 4.23858 2 7V17C2 19.7614 4.23858 22 7 22H17C19.7614 22 22 19.7614 22 17V7C22 4.23858 19.7614 2 17 2Z" fill="#B5B5C3"></path>
                                                <path d="M13 12C13 11.4477 12.5523 11 12 11C11.4477 11 11 11.4477 11 12C11 12.5523 11.4477 13 12 13C12.5523 13 13 12.5523 13 12Z" fill="#B5B5C3"></path>
                                                <path d="M13 16C13 15.4477 12.5523 15 12 15C11.4477 15 11 15.4477 11 16C11 16.5523 11.4477 17 12 17C12.5523 17 13 16.5523 13 16Z" fill="#B5B5C3"></path>
                                                <path d="M13 8C13 7.44772 12.5523 7 12 7C11.4477 7 11 7.44772 11 8C11 8.55228 11.4477 9 12 9C12.5523 9 13 8.55228 13 8Z" fill="#B5B5C3"></path>
                                            </svg>
                                        </div>
                                        <div class="settings-menu">
                                            <span class="settings-menu-edit" onclick="document.location='https://pegas.best/zayavka/4260/?edit'">Редактирование</span>

                                            <span class="settings-menu-edit" onclick="arhiv(26058);jQuery(this).parent().parent().parent().parent().remove()">В архив</span>
                                            <span class="settings-menu-edit" onclick="copy('26058')">Копировать</span>
                                            <span class="settings-menu-remove" onclick="deletePost(26058); jQuery(this).parent().parent().parent().parent().remove()">Удалить</span>


                                        </div>
                                    </div>
                                </div>

                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </main>



    </body>

</html>