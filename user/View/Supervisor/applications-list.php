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
//dd($applicationList);

?>
    <body>
        <?php $controller->view('Components/header'); ?>
        <main class="applications">
            <div class="sub-header" style="padding-bottom: 0;">
                <div class="wrapper">
                    <?php $controller->view('Components/breadcrumbs'); ?>

                    <h3 class="sub-header-title"><?php echo $titlePage; ?></h3>

                    <div class="row" style="margin-top: 40px;">
                        <div class="col-8 d-flex">
                            <ul class="nav nav-underline nav-subheader">
                                <li class="nav-item">
                                    <a href="/supervisor" class="nav-link <?php if(! $condition['application-section-journal']) echo 'active';?>">Актуальные</a>
                                </li>
                                <li class="nav-item">
                                    <a href="/supervisor?application-section-journal=6" class="nav-link <?php if($condition['application-section-journal']) echo 'active';?>">Закрытые</a>
                                </li>
                            </ul>

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
                    <?php $datePrev = ''; ?>
                    <?php foreach($applicationList as $item): ?>
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
                                        <span><?php echo $item['driver']; ?><br>
                                          <?php echo $item['driver_phone']; ?>
                                    </span>
                                    </div>
                                    <div class="item__trasportirovka go-application-page" >
                                        <?php if(count($item['route_client'])): ?>
                                            <?php foreach ($item['route_client'] as $route): ?>
                                                <div class="item__trasportirovka-item">
                                                    <?php echo $route['city']; ?>
                                                    <span> · <?php echo  $route['date']; ?></span>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php endif; ?>

                                    </div>

                                    <div class="item__statusy-oplata_klientom go-application-page" >
                                        <span class="status-type grey"><?php echo $item['client_payment_status']; ?></span>
                                    </div>
                                    <div class="item__statusy-oplata_perevozchiku go-application-page"">
                                        <span class="status-type grey"><?php echo $item['carrier_payment_status']; ?></span>
                                    </div>
                                    <div class="item__statusy-dokumenty_s_klientom go-application-page"">
                                        <span class="status-type grey"><?php echo $item['client_documents_status']; ?></span>
                                    </div>
                                    <div class="item__statusy-oplata_klientom doc-carrier go-application-page"">
                                        <span class="status-type grey"><?php echo $item['carrier_documents_status']; ?></span>
                                    </div>
                                    <div class="item__info d-flex">
                                        <div class="avatar">
                                            <img alt="аватар" src="<?php echo $item['user']['avatar']; ?>"  class="avatar avatar-28 photo my-class-1 super-gravarar" height="28" width="28" style="top:-5px;" title="Миша в горах" decoding="async">
                                            <span><?php echo $item['user']['name']; ?></span>
                                        </div>
                                        <div class="item__select-status" style="width: 110px;">
                                            <div class="user-custom-select">
                                                <div class="user-custom-select-selected
                                                    <?php
                                                        switch ($item['application_status']):
                                                            case 'В работе':
                                                                echo 'blue';
                                                                break;
                                                            case 'В пути':
                                                                echo 'orange';
                                                                break;
                                                            case 'Выгрузился':
                                                                echo 'purple';
                                                                break;
                                                            default:
                                                                echo 'green';
                                                                break;
                                                        endswitch;
                                                    ?>
                                                    ">
                                                    <span class="status"><?php echo $item['application_status']; ?></span>
                                                    <i class="bi bi-caret-down-fill"></i>
                                                </div>
                                                <div class="user-custom-option-container">
                                                    <div class="user-custom-option" data-color="blue">В работе</div>
                                                    <div class="user-custom-option" data-color="orange">В пути</div>
                                                    <div class="user-custom-option" data-color="purple">Выгрузился</div>
                                                    <div class="user-custom-option" data-color="green">Завершена (оплата)</div>
                                                    <div class="user-custom-option" data-color="green">Завершена (док.)</div>
                                                    <div class="user-custom-option" data-color="green">Завершена</div>
                                                </div>
                                            </div>
                                            <select class="form-control form-select-status form-control-sm d-none" data-id="<?php echo $item['id']; ?>"
                                                    data-name="application_status" style="width: 110px; font-size: 12px;font-weight: 500; ">>
                                                <option value="В работе" <?php if($item['application_status'] == 'В работе') echo 'selected'; ?> >В работе</option>
                                                <option value="В пути" <?php if($item['application_status'] == 'В пути') echo 'selected'; ?>>В пути</option>
                                                <option value="Выгрузился" <?php if($item['application_status'] == 'Выгрузился') echo 'selected'; ?>>Выгрузился</option>
                                                <option value="Завершена (оплата)" <?php if($item['application_status'] == 'Завершена (оплата)') echo 'selected'; ?>>Завершена (оплата)</option>
                                                <option value="Завершена (док.)" <?php if($item['application_status'] == 'Завершена (док.)') echo 'selected'; ?>>Завершена (док.)</option>
                                                <option value="Завершена" <?php if($item['application_status'] == 'Завершена') echo 'selected'; ?>>Завершена</option>
                                            </select>
                                        </div>
                                        <div class="comment-link js-comment-link" data-bs-toggle="modal" data-bs-target="#commentsModal">
                                            <?php if(count($item['comments'])): ?>
                                                <svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path opacity="0.3" d="M20 3.5H4C2.89543 3.5 2 4.39543 2 5.5V16.5C2 17.6046 2.89543 18.5 4 18.5H4.5C5.05228 18.5 5.5 18.9477 5.5 19.5V22.0052C5.5 22.6441 6.21212 23.0253 6.74376 22.6708L11.4885 19.5077C12.4741 18.8506 13.6321 18.5 14.8167 18.5H20C21.1046 18.5 22 17.6046 22 16.5V5.5C22 4.39543 21.1046 3.5 20 3.5Z" fill="#FF0000"></path>
                                                    <path d="M12 12.5H7C6.44772 12.5 6 12.9477 6 13.5C6 14.0523 6.44772 14.5 7 14.5H12C12.5523 14.5 13 14.0523 13 13.5C13 12.9477 12.5523 12.5 12 12.5Z" fill="#FF0000"></path>
                                                    <path d="M17 7.5H7C6.44772 7.5 6 7.94772 6 8.5C6 9.05228 6.44772 9.5 7 9.5H17C17.5523 9.5 18 9.05228 18 8.5C18 7.94772 17.5523 7.5 17 7.5Z" fill="#FF0000"></path>
                                                </svg>
                                            <?php else: ?>
                                            <svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path opacity="0.3" d="M20 3.5H4C2.89543 3.5 2 4.39543 2 5.5V16.5C2 17.6046 2.89543 18.5 4 18.5H4.5C5.05228 18.5 5.5 18.9477 5.5 19.5V22.0052C5.5 22.6441 6.21212 23.0253 6.74376 22.6708L11.4885 19.5077C12.4741 18.8506 13.6321 18.5 14.8167 18.5H20C21.1046 18.5 22 17.6046 22 16.5V5.5C22 4.39543 21.1046 3.5 20 3.5Z" fill="#B5B5C3"></path>
                                                <path d="M12 12.5H7C6.44772 12.5 6 12.9477 6 13.5C6 14.0523 6.44772 14.5 7 14.5H12C12.5523 14.5 13 14.0523 13 13.5C13 12.9477 12.5523 12.5 12 12.5Z" fill="#B5B5C3"></path>
                                                <path d="M17 7.5H7C6.44772 7.5 6 7.94772 6 8.5C6 9.05228 6.44772 9.5 7 9.5H17C17.5523 9.5 18 9.05228 18 8.5C18 7.94772 17.5523 7.5 17 7.5Z" fill="#B5B5C3"></path>
                                            </svg>
                                            <?php endif; ?>
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
                                                    <li>
                                                        <div class="dropdown-item js-edit"
                                                             data-id-app="<?php echo $item['id']; ?>">
                                                            Редактировать
                                                        </div>
                                                    </li>
                                                    <?php if(! $condition['application-section-journal']) : ?>
                                                        <li>
                                                            <div class="dropdown-item js-complete-application"
                                                                 data-bs-toggle="modal" data-bs-target="#commentCompleteApplication"
                                                                 data-num-app="<?php echo $item['application_number']; ?>"
                                                                 data-id-app="<?php echo $item['id']; ?>">
                                                                Завершить
                                                            </div>
                                                        </li>
                                                    <?php else: ?>
                                                        <li>
                                                            <div class="dropdown-item js-complete-application-comment-show"
                                                                 data-bs-toggle="modal" data-bs-target="#showCommentCompleteApplication"
                                                                 data-id-app="<?php echo $item['id']; ?>"
                                                                 data-num-app="<?php echo $item['application_number']; ?>">
                                                                Просмотреть комментарий
                                                            </div>
                                                        </li>
                                                    <?php endif; ?>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                    <?php endforeach; ?>
                    </div>

                    <?php if(count($applicationList) == 0): ?>

                    <?php endif; ?>


                    <?php $controller->view('Components/pagination'); ?>

                </div>

            </div>
        </main>

        <div class="modal fade" id="commentsModal" tabindex="-1" aria-labelledby="commentsModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" style="max-width: 40%">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="commentsModalLabel">
                            Комментарии к заявке <span id="modal-header-application-num"></span>
                        </h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                    </div>
                    <div class="modal-body">

                        <?php $controller->view('Application/application-comment-modal');  ?>
                    </div>

                </div>
            </div>
        </div>

        <div class="modal fade" id="showCommentCompleteApplication" tabindex="-1" aria-labelledby="showCommentCompleteApplication" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" style="max-width: 30%">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="commentsModalLabel">
                            Комментарий по завершению заявки № <span id="modal-header-complete-comment-application-num"></span>
                        </h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                    </div>
                    <div class="modal-body">

                        <div class="d-flex align-items-end mb-2">
                            <div id="complete-application-comment-user" style="font-size: 16px; font-weight: 500; margin-right: 6px;"></div>
                            <div id="complete-application-comment-datetime" style="font-size: 10px; font-weight: 500"></div>
                        </div>
                        <div id="complete-application-comment" style="font-weight: 500;font-size: 14px"></div>
                    </div>

                </div>
            </div>
        </div>

        <div class="modal fade" id="commentCompleteApplication" tabindex="-1" aria-labelledby="commentCompleteApplication" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" style="max-width: 30%">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="commentsModalLabel">
                            Завершение заявки № <span id="modal-header-complete-application-num"></span>
                        </h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                    </div>
                    <div class="modal-body">
                        <form action="" id="form-complete-application">
                            <input type="hidden" name="id" id="complete-application-id">

                            <div class="mb-4">
                                <label for="">Комментарий</label>
                                <textarea name="comment" id="" placeholder="Комментарий" class="form-control" required></textarea>
                            </div>

                            <button class="btn btn-success w-100">Завершить заявку</button>
                        </form>

                    </div>

                </div>
            </div>
        </div>

        <script>
            $('#form-complete-application').submit(function (e) {
                e.preventDefault();

                let data = $(this).serializeArray();

                $.ajax({
                    method: 'POST',
                    url: '/application/ajax/complete-application-supervisor',
                    data: data,
                    success: function (response) {
                        console.log(response);

                        let data = JSON.parse(response);

                        if(data['result'])
                            document.location.reload();
                    }
                });
            });

            $('.js-complete-application-comment-show').click(function () {
                let id = $(this).data('id-app');

                let numApp = $(this).data('num-app');
                $('#modal-header-complete-comment-application-num').text(numApp);

                $.ajax({
                    method: 'POST',
                    url: '/application/ajax/get-comment-complete-supervisor-application',
                    data: {id: id},
                    success: function (response) {
                        console.log(response);
                        let data = JSON.parse(response);

                        console.log(data);

                        if(data['result']){
                            $('#complete-application-comment').text(data['comment']['comment']);
                            $('#complete-application-comment-datetime').text(data['comment']['datetime']);
                            $('#complete-application-comment-user').text(data['user']['name'] + ' ' + data['user']['lastname']);
                        }
                    }
                })

            });
            $('.js-complete-application').click(function () {
                let id = $(this).data('id-app');
                $('#complete-application-id').val(id);

                let numApp = $(this).data('num-app');
                $('#modal-header-complete-application-num').text(numApp);
            })
        </script>

        <script>
            $('#form-cancelled-application').submit(function (e) {
                e.preventDefault();

                let data = $(this).serializeArray();

                console.log(data)

                $.ajax({
                    method: 'POST',
                    url: '/application/ajax/cancel-application',
                    data: data,
                    success: function (response) {
                        console.log(response);
                        response = JSON.parse(response);

                        if(response['result']){
                            document.location.reload();
                        }
                        else{
                            alert('Произошла ошибка при попытке смены статуса заявки');
                        }
                    }
                })

            })

            $('.js-cancel-application').click(function (){
                let id = $(this).data('id-app');
                let cancelled = $(this).data('cancelled');
                let num = $(this).data('num-app');
                $('#cancel-application-id').val(id);
                $('#modal-header-cancel-application-num').text(num);




                // $.ajax({
                //     url: '/application/ajax/change-cancelled',
                //     method: 'POST',
                //     data: {id_application:id, cancelled: cancelled},
                //     success: function (response) {
                //         console.log(response)
                //         response = JSON.parse(response);
                //
                //         if(response['result']){
                //             document.location.reload();
                //         }
                //         else{
                //             alert('Произошла ошибка при попытке смены статуса заявки');
                //         }
                //
                //     }
                // })

            })
            $("body").click(function () {
                $('.user-custom-option-container').removeClass('active');
            })
            $('.user-custom-select-selected').click(function (event) {
                event.stopPropagation();
                $('.user-custom-select').has(this).find('.user-custom-option-container').toggleClass('active');
            });
            $('.user-custom-option').click(function (event) {
                event.stopPropagation();
                let color = $(this).data('color');
                let status = $(this).text();
                let selected = $('.user-custom-select').has(this).find('.user-custom-select-selected');
                selected.removeClass('green purple orange blue');
                selected.addClass(color);
                selected.html(`<span class="status">${status}</span><i class="bi bi-caret-down-fill"></i>`);
                $('.user-custom-select').has(this).find('.user-custom-option-container').removeClass('active');
                
                let containerStatus = $('.item__select-status').has(this);
                containerStatus.find('.form-select-status').val(status);
                containerStatus.find('.form-select-status').trigger('change');

            });
        </script>
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

                document.location.href = '/application/edit?id=' + id;
            });

            function loadComment(idApplication){
                $.ajax({
                    url: '/application/ajax/load-comment',
                    method: 'POST',
                    data: {id_application: idApplication},
                    success: function (response) {
                        console.log(response)
                        let data = JSON.parse(response);
                        console.log(data);

                        let html = ``;

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

                            html += `
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
                        $('.comments-list').html(html);
                    }
                })
            }
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
            $('.js-comment-link').click(function () {
                let id = $('.post-list__item').has(this).data('post-id');
                $('#comment__add-input').data('id-app', id);
                $('#comment__edit-input').data('id-app', id);

                loadComment(id);
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

                $.ajax({
                    url: '/application/ajax/add-comment',
                    method: 'POST',
                    data: {id_application: appId, comment: comment},
                    success: function (response){
                        let data = JSON.parse(response)
                        console.log(data)
                        loadComment(appId);
                    }
                })

            });

            $('.edit-comment-button').click(function () {
                let container = $('.comment__add-input').has(this);

                let appId = container.data('id-app');
                let commentId = container.data('id-comment');

                let comment = container.find('.add-comment-input').val();

                container.find('.add-comment-input').val('');

                $.ajax({
                    url: '/application/ajax/edit-comment',
                    method: 'POST',
                    data: {id_application: appId, comment: comment, comment_id: commentId},
                    success: function (response){

                        let data = JSON.parse(response)
                        if(data['result']){
                            $('#comment__edit-input').addClass('d-none');
                            $('#comment__add-input').removeClass('d-none');
                            loadComment(appId);

                        }
                    }
                })

            });
        </script>
        <script>
            $('.go-application-page').click(function () {
                let id = $('.post-list__item').has(this).data('post-id');

                document.location.href = '/application?id=' + id;
            })
            $('#btn-filter-app-list').click(function () {
                $('#form-filter-app-list').trigger('submit');
            })
            $('.js-copy-application').click(function () {
                let id = $(this).data('id-app');

                $.ajax({
                    url: "/application/ajax/copy-application",
                    method: "POST",
                    data: {id: id},
                    success: function (response){
                        document.location.href = '/application?id=' + response;
                    }
                });
            })
        </script>
    </body>

</html>