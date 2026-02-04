<?php

/** @var App\User\Contoller\Common\HomeController $controller */
/** @var String $titlePage */
/** @var array $applicationList */
/** @var array $userList */
/** @var array $carrierList */
/** @var array $clientList */
/** @var array $condition */
/** @var array $searchData */
/** @var int $countPage */
/** @var int $page */
/** @var string $link */
/** @var bool $isFullCRMAccess */

$controller->view('Components/head');
//dd($prrList);
?>

    <body>
        <?php $controller->view('Components/header'); ?>
        <main class="applications applications-prr">
            <div class="sub-header" style="padding-bottom: 0;">
                <div class="wrapper">
                    <?php $controller->view('Components/breadcrumbs'); ?>

                    <h3 class="sub-header-title"><?php echo $titlePage; ?></h3>

                    <div class="row" style="margin-top: 40px;">
                        <div class="col-8 d-flex">
                            <ul class="nav nav-underline nav-subheader">
                                <li class="nav-item">
                                    <a href="/applications-list"
                                       class="nav-link">Основные</a>
                                </li>
                                <li class="nav-item">
                                    <a href="/prr/list" class="nav-link active">ПРР</a>
                                </li>
                                <?php if($isFullCRMAccess OR in_array($controller->auth->user()->id(),[4,7])): ?>
                                    <li class="nav-item">
                                        <a href="/ts/list" class="nav-link">ТС</a>
                                    </li>
                                <?php endif; ?>
                                <li class="nav-item d-none">
                                    <a href="/applications-list?rop=1"
                                       class="nav-link">РОП</a>
                                </li>
                                <li class="nav-item">
                                    <a href="/applications-list?cancelled=1"
                                       class="nav-link">Отмененные</a>
                                </li>
                            </ul>

                        </div>
                        <div class="col">
                            <div class="d-flex justify-content-end">
                                <div class="dropdown me-3">
                                    <button class="btn btn-theme-white dropdown-toggle dropdown-toggle-without-arrow" type="button"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-sort-alpha-down" style="font-size: 16px"></i> Сначала новые
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-theme">
                                        <li><a class="dropdown-item" href="?order=DESC">Сначала новый</a></li>
                                        <li><div class="dropdown-item">Кастомный</div></li>
                                        <li><a class="dropdown-item" href="?order=ASC">Сначала старые</a></li>
                                    </ul>
                                </div>
<!--                                <button class="btn btn-theme-white me-3" id="" data-bs-toggle="modal" data-bs-target="#filterModal">-->
<!--                                    <i class="bi bi-funnel-fill" ></i> Фильтры-->
<!--                                </button>-->
                                <div class="dropdown">
                                    <button class="btn btn-theme-success dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        + Добавить
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="/application/add">Экспедирование</a></li>
                                        <li><a class="dropdown-item" href="/prr/add">ПРР</a></li>
                                        <li><a class="dropdown-item" href="/ts/add">ТС</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="wrapper">
                <div class="search search-page-block search-application d-none">
                    <form action="/prr/list?<?php echo $link; ?>" class="d-flex">
                        <input type="text" class="form-control" name="search" placeholder="Поиск..." value="<?php echo $searchData['search']; ?>">
                        <a class="btn btn-danger btn-cancel" href="/prr/list">Сбросить</a>
                    </form>
                </div>

                <div class="post-list applications-prr-list">
                    <div class="post-list__header">
                        <div class="post-list__header-item">№ Заявки</div>
                        <div class="post-list__header-item">Клиент / ПРР</div>
                        <div class="post-list__header-item">Место погрузки/разгрузки</div>
                        <div class="post-list__header-item">Характер груза</div>
                        <div class="post-list__header-item">Детали</div>
                        <div class="post-list__header-item"></div>
                    </div>
                    <div class="post-list__items">
                    <?php $datePrev = ''; ?>
                    <?php foreach($prrList as $item): ?>
                            <?php if($datePrev != $item['date']): ?>
                                <div class="data-history d-flex justify-content-center"
                                     style="position: relative; top: -10px; font-weight: 600; font-size: 13px; color: black">
                                    <?php echo $item['date']; $datePrev = $item['date']; ?>
                                </div>
                            <?php endif; ?>
                            <div class="post-list__item-container">
                                <a class="post-list__item item" data-post-id="<?php echo $item['id']; ?>">
                                    <div class="item__number-zayavka d-flex go-application-page" >
                                        <span style="margin-right: 9.5px;">
                                            <svg width="10" height="13" viewBox="0 0 10 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M8.375 0.5C8.98438 0.5 9.5 1.01562 9.5 1.625V11.375C9.5 12.0078 8.98438 12.5 8.375 12.5H6.125V10.625C6.125 10.0156 5.60938 9.5 5 9.5C4.36719 9.5 3.875 10.0156 3.875 10.625V12.5H1.625C0.992188 12.5 0.5 12.0078 0.5 11.375V1.625C0.5 1.01562 0.992188 0.5 1.625 0.5H8.375ZM2 6.875C2 7.08594 2.16406 7.25 2.375 7.25H3.125C3.3125 7.25 3.5 7.08594 3.5 6.875V6.125C3.5 5.9375 3.3125 5.75 3.125 5.75H2.375C2.16406 5.75 2 5.9375 2 6.125V6.875ZM4.625 5.75C4.41406 5.75 4.25 5.9375 4.25 6.125V6.875C4.25 7.08594 4.41406 7.25 4.625 7.25H5.375C5.5625 7.25 5.75 7.08594 5.75 6.875V6.125C5.75 5.9375 5.5625 5.75 5.375 5.75H4.625ZM6.5 6.875C6.5 7.08594 6.66406 7.25 6.875 7.25H7.625C7.8125 7.25 8 7.08594 8 6.875V6.125C8 5.9375 7.8125 5.75 7.625 5.75H6.875C6.66406 5.75 6.5 5.9375 6.5 6.125V6.875ZM2.375 2.75C2.16406 2.75 2 2.9375 2 3.125V3.875C2 4.08594 2.16406 4.25 2.375 4.25H3.125C3.3125 4.25 3.5 4.08594 3.5 3.875V3.125C3.5 2.9375 3.3125 2.75 3.125 2.75H2.375ZM4.25 3.875C4.25 4.08594 4.41406 4.25 4.625 4.25H5.375C5.5625 4.25 5.75 4.08594 5.75 3.875V3.125C5.75 2.9375 5.5625 2.75 5.375 2.75H4.625C4.41406 2.75 4.25 2.9375 4.25 3.125V3.875ZM6.875 2.75C6.66406 2.75 6.5 2.9375 6.5 3.125V3.875C6.5 4.08594 6.66406 4.25 6.875 4.25H7.625C7.8125 4.25 8 4.08594 8 3.875V3.125C8 2.9375 7.8125 2.75 7.625 2.75H6.875Z" fill="#A1A5B7"></path>
                                            </svg>
                                        </span>
                                            <div>
                                                <?php echo $item['application_number']; ?>
                                                <span><?php echo $item['date']; ?></span>
                                            </div>
                                    </div>
                                    <div class="item__osnovnoe go-application-page" >
                                        <?php echo $item['client']; ?>
                                        <span><?php echo $item['prr']; ?><br>
                                          <?php echo $item['chosen_contact_Prr']; ?>
                                    </span>
                                    </div>
                                    <div class="item__trasportirovka go-application-page" >
                                        <?php if(count($item['place_client'])): ?>
                                            <?php foreach ($item['place_client'] as $place): ?>
                                                <div class="item__trasportirovka-item">
                                                    <div style="text-align: center">
                                                        <span >
                                                            <?php if($place['direction']) echo 'Погрузка';
                                                            else echo 'Разгрузка'; ?>
                                                        </span>
                                                    </div>
                                                    <?php echo $place['city']; ?>
                                                    <span> · <?php echo  $place['date']; ?></span>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php endif; ?>

                                    </div>
                                    <div class="item__cargo go-application-page" >
                                        <div class="" style="font-weight: 600">
                                            <?php echo $item['nature_cargo']; ?>
                                        </div>
                                    </div>
                                    <div class="item__trasportirovka go-application-page" >
                                        <div class="" style="font-weight: 600">
                                            <?php echo $item['number_loaders']; ?>
                                        </div>
                                        <div class="" style="font-weight: 600">
                                            <?php echo $item['weight']; ?>
                                        </div>

                                    </div>
                                    <div class="item__info d-flex">
                                        <div class="avatar">
                                            <img alt="аватар" src="<?php echo $item['user']['avatar']; ?>"
                                                 class="avatar avatar-28 photo my-class-1 super-gravarar" height="28" width="28"
                                                 style="top:-5px;" decoding="async">
                                            <span><?php echo $item['user']['name']; ?></span>
                                        </div>

                                        <div class="settings">
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
                                                    <li><div class="dropdown-item js-copy-application" data-id-app="<?php echo $item['id']; ?>">Копировать</div></li>
                                                    <li><div class="dropdown-item js-edit" data-id-app="<?php echo $item['id']; ?>"> Редактировать</div></li>

                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                    <?php endforeach; ?>
                    </div>

                    <?php if(count($prrList) == 0): ?>

                    <?php endif; ?>


                    <?php $controller->view('Components/pagination'); ?>

                </div>



            </div>
        </main>

        <script>
            $('.js-chosen').chosen({
                width: '100%',
                no_results_text: 'Совпадений не найдено',
                placeholder_text_single: 'Выберите сотрудника'
            });
        </script>
        <script>

            $('.js-edit').click(function () {
                let id = $(this).data('id-app');

                document.location.href = '/prr/edit?id=' + id;
            });

        </script>
        <script>
            $('.go-application-page').on('click mousedown', function (e) {
                const $item = $('.post-list__item').has(this);
                const id = $item.data('post-id');
                const url = '/prr/prr_application?id=' + id;

                // Средняя кнопка мыши или Ctrl/Cmd + ЛКМ — открыть в новой вкладке
                if (e.which === 2 || e.ctrlKey || e.metaKey) {
                    window.open(url, '_blank');
                } else if (e.type === 'click' && e.which === 1) {
                    // Обычный левый клик — переход в текущей вкладке
                    document.location.href = url;
                }
            });

            $('.js-copy-application').click(function () {
                let id = $(this).data('id-app');

                $.ajax({
                    url: "/prr/ajax/copy-prr-application",
                    method: "POST",
                    data: {id: id},
                    success: function (response){
                        console.log(response)
                        document.location.href = '/prr/prr_application?id=' + response;
                    }
                });
            })
        </script>
    </body>

</html>