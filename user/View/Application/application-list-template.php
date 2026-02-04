<div class="post-list post-list-search-application mt-0 <?php if (empty($applicationList)) echo 'd-none'; ?>">
    <div class="post-list__header">
        <div class="post-list__header-item">№ Заявки</div>
        <div class="post-list__header-item">Клиент / водитель</div>
        <div class="post-list__header-item">Маршрут</div>
        <div class="post-list__header-item text-center">Оплата Клиентом/№ Счета</div>
        <div class="post-list__header-item text-center">Оплата Перевозчику</div>
        <div class="post-list__header-item"></div>
    </div>
    <div class="post-list__items">
        <?php $datePrev = ''; ?>
        <?php foreach ($applicationList as $item): ?>
            <?php if ($datePrev != $item['date']): ?>
                <!-- <div class="data-history d-flex justify-content-center"
                    style="position: relative; top: -10px; font-weight: 600; font-size: 13px; color: black">
                    <?php echo $item['date'];
                    $datePrev = $item['date']; ?>
                </div> -->
                <div class="date-divider">
                    <span class="date-label">
                        <?php echo $item['date'];
                            $datePrev = $item['date']; ?>
                    </span>
                </div>
            <?php endif; ?>
            <div class="post-list__item-container">
                <a class="post-list__item item" data-post-id="<?php echo $item['id']; ?>">
                    <div class="item__number-zayavka d-flex go-application-page">
                        <span style="margin-right: 9.5px;">
                            <svg width="10" height="13" viewBox="0 0 10 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M8.375 0.5C8.98438 0.5 9.5 1.01562 9.5 1.625V11.375C9.5 12.0078 8.98438 12.5 8.375 12.5H6.125V10.625C6.125 10.0156 5.60938 9.5 5 9.5C4.36719 9.5 3.875 10.0156 3.875 10.625V12.5H1.625C0.992188 12.5 0.5 12.0078 0.5 11.375V1.625C0.5 1.01562 0.992188 0.5 1.625 0.5H8.375ZM2 6.875C2 7.08594 2.16406 7.25 2.375 7.25H3.125C3.3125 7.25 3.5 7.08594 3.5 6.875V6.125C3.5 5.9375 3.3125 5.75 3.125 5.75H2.375C2.16406 5.75 2 5.9375 2 6.125V6.875ZM4.625 5.75C4.41406 5.75 4.25 5.9375 4.25 6.125V6.875C4.25 7.08594 4.41406 7.25 4.625 7.25H5.375C5.5625 7.25 5.75 7.08594 5.75 6.875V6.125C5.75 5.9375 5.5625 5.75 5.375 5.75H4.625ZM6.5 6.875C6.5 7.08594 6.66406 7.25 6.875 7.25H7.625C7.8125 7.25 8 7.08594 8 6.875V6.125C8 5.9375 7.8125 5.75 7.625 5.75H6.875C6.66406 5.75 6.5 5.9375 6.5 6.125V6.875ZM2.375 2.75C2.16406 2.75 2 2.9375 2 3.125V3.875C2 4.08594 2.16406 4.25 2.375 4.25H3.125C3.3125 4.25 3.5 4.08594 3.5 3.875V3.125C3.5 2.9375 3.3125 2.75 3.125 2.75H2.375ZM4.25 3.875C4.25 4.08594 4.41406 4.25 4.625 4.25H5.375C5.5625 4.25 5.75 4.08594 5.75 3.875V3.125C5.75 2.9375 5.5625 2.75 5.375 2.75H4.625C4.41406 2.75 4.25 2.9375 4.25 3.125V3.875ZM6.875 2.75C6.66406 2.75 6.5 2.9375 6.5 3.125V3.875C6.5 4.08594 6.66406 4.25 6.875 4.25H7.625C7.8125 4.25 8 4.08594 8 3.875V3.125C8 2.9375 7.8125 2.75 7.625 2.75H6.875Z" fill="#A1A5B7"></path>
                            </svg>
                        </span>
                        <div>
                            <?php echo $item['application_number']; ?> <br>
                            <span><?php echo $item['date']; ?></span>
                            <div style="color:black;font-size: 12px"><?php echo $item['customer']; ?></div>
                        </div>
                    </div>
                    <div class="item__osnovnoe go-application-page">
                        <?php echo $item['client']; ?>
                        <div style="color:blue; margin-bottom: 0.5rem;">(<?php echo $item['client_inn']; ?>)</div>
                        <?php echo $item['carrier']; ?>
                        <div style="color:blue">(<?php echo $item['carrier_inn']; ?>)</div>
                        <span><?php echo $item['driver']; ?><br>
                            <?php echo $item['driver_phone']; ?>
                        </span>
                    </div>
                    <div class="item__trasportirovka go-application-page">
                        <?php if (count($item['route_client'])): ?>
                            <?php foreach ($item['route_client'] as $route): ?>
                                <div class="item__trasportirovka-item">
                                    <?php echo $route['city']; ?>
                                    <span> · <?php echo  $route['date']; ?></span>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>

                    </div>
                    <div class="item__statusy-oplata_klientom go-application-page text-center">
                        <?php echo $item['actual_payment_client']; ?> из <?php echo $item['transportation_cost_client']; ?> ₽
                        <div>(<?php echo $item['taxation_type_client']; ?>)</div>
                        <div><?php echo $item['account_number_client']; ?></div>
                    </div>
                    <div class="item__statusy-oplata_perevozchiku go-application-page text-center">
                        <?php echo $item['actual_payment_carrier']; ?> из <?php echo $item['transportation_cost_carrier']; ?> ₽
                        <div>(<?php echo $item['taxation_type_carrier']; ?>)</div>
                    </div>
                    <div class="item__info d-flex">
                        <div class="avatar">
                            <img alt="аватар" src="<?php echo $item['user']['avatar']; ?>" class="avatar avatar-28 photo my-class-1 super-gravarar" height="28" width="28" style="top:-5px;" title="Аватарка" decoding="async">
                            <span><?php echo $item['user']['name']; ?></span>
                        </div>
                        <div class="item__select-status" style="width: 110px;">
                            <div class="user-custom-select">
                                <div class="user-custom-select-selected
                                    <?php
                                        switch ($item['application_status']):
                                            case 'Актуальная':
                                                echo 'blue';
                                                break;
                                            case 'Завершенная':
                                                echo 'orange';
                                                break;
                                            case 'Закрытая под расчет':
                                            case 'Закрытая под документы':
                                                echo 'purple';
                                                break;
                                            default:
                                                echo 'grey';
                                                break;
                                        endswitch;
                                    ?>">
                                    <span class="status"><?php echo $item['application_status']; ?></span>
                                </div>
                                <div class="user-custom-option-container">
                                    <div class="user-custom-option" data-color="blue">Актуальная</div>
                                    <div class="user-custom-option" data-color="orange">Завершенная</div>
                                    <div class="user-custom-option" data-color="green">Закрытая под расчет</div>
                                    <div class="user-custom-option" data-color="green">Закрытая под документы</div>
                                    <div class="user-custom-option" data-color="grey">Срыв</div>
                                    <div class="user-custom-option" data-color="grey">Отмененная</div>
                                </div>
                            </div>
                        </div>

                        <div class="comment-link js-comment-link" data-bs-toggle="modal" data-bs-target="#commentsModal">
                            <?php if (count($item['comments'])): ?>
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
                                <ul class="dropdown-menu dropdown-menu-theme" style="padding: 0; border: unset">
                                    <?php if ($controller->auth->user()->fullCRM()): ?>
                                        <li>
                                            <div class="dropdown-item js-open-application-journal bg-secondary text-white mb-2"
                                                data-id-app="<?php echo $item['id']; ?>">
                                                <i class="bi bi-box-arrow-up-right" style="margin-right: 6px;"></i> Открыть в Журнале
                                            </div>
                                        </li>
                                    <?php endif; ?>
                                    <li>
                                        <div class="dropdown-item js-copy-application bg-success text-white mb-2"
                                            data-id-app="<?php echo $item['id']; ?>">
                                            <i class="bi bi-copy" style="margin-right: 6px;"></i> Копировать
                                        </div>
                                    </li>

                                    <li>
                                        <div class="dropdown-item js-create-prr  bg-warning text-dark mb-2" data-id-app="<?php echo $item['id']; ?>">
                                            <i class="bi bi-plus-circle"></i> Создать ПРР
                                        </div>
                                    </li>
                                    <li>
                                        <div class="dropdown-item js-edit bg-primary text-white mb-2"
                                            data-id-app="<?php echo $item['id']; ?>">
                                            <i class="bi bi-pencil-square" style="margin-right: 6px;"></i> Редактировать
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<script>
    function countApplication() {
        $('#span-quantity-application').text(<?php echo count($applicationList); ?>);
    }
    countApplication();

    $('body').on('click', '.post-list-search-application .js-edit', function() {
        let id = $(this).data('id-app');

        document.location.href = '/application/edit?id=' + id;
    });

    $('body').on('click', '.post-list-search-application .js-create-prr', function() {
        let id = $(this).data('id-app');

        document.location.href = '/prr/add?id-application=' + id;
    });

    $('body').on('click', '.post-list-search-application .js-open-application-journal', function() {
        let id = $(this).data('id-app');

        document.location.href = '/journal-list?app-id=' + id;

    });
    $('body').on('click', '.post-list-search-application .js-copy-application', function() {
        let id = $(this).data('id-app');

        $.ajax({
            url: "/application/ajax/copy-application",
            method: "POST",
            data: {
                id: id
            },
            success: function(response) {
                console.log(response)
                document.location.href = '/application?copy=1&id=' + response;
            }
        });
    });
    $('body').on('click mousedown', '.post-list-search-application .go-application-page', function(e) {
        const $item = $('.post-list__item').has(this);
        const id = $item.data('post-id');
        const url = '/application?id=' + id;

        // Средняя кнопка мыши или Ctrl/Cmd + ЛКМ — открыть в новой вкладке
        // if (e.which === 2 || e.ctrlKey || e.metaKey) {
        window.open(url, '_blank');
        // } else if (e.type === 'click' && e.which === 1) {
        // Обычный левый клик — переход в текущей вкладке
        // document.location.href = url;
        // }
    });
</script>

<script>
    function highlightWordsInContainer(containerSelector) {
        // массив из PHP → JS
        let words = <?php echo json_encode($words, JSON_UNESCAPED_UNICODE); ?>;
        if (!words || !words.length) return;

        const container = document.querySelector(containerSelector);
        if (!container) return;

        // рекурсивно обходим все текстовые ноды
        function walk(node) {
            if (node.nodeType === 3) { // текст
                let text = node.nodeValue;
                words.forEach(word => {
                    if (word.trim() === '') return;
                    const regex = new RegExp('(' + word.replace(/[.*+?^${}()|[\]\\]/g, '\\$&') + ')', 'gi');
                    text = text.replace(regex, '<div class="highlight">$1</div>');
                });
                if (text !== node.nodeValue) {
                    const span = document.createElement('div');
                    span.innerHTML = text;
                    node.parentNode.replaceChild(span, node);
                }
            } else if (node.nodeType === 1 && node.tagName !== "SCRIPT" && node.tagName !== "STYLE") {
                for (let i = 0; i < node.childNodes.length; i++) {
                    walk(node.childNodes[i]);
                }
            }
        }

        walk(container);
    }
    highlightWordsInContainer('.post-list-search-application');
    // document.addEventListener("DOMContentLoaded", function() {

    // });
</script>

<style>
    .highlight {
        font-weight: bold;
        background: yellow;
        /* можно поменять на любой стиль */
        display: inline;
    }

    .post-list-search-application.post-list .post-list__header .post-list__header-item:nth-child(4),
    .post-list-search-application.post-list .post-list__item .item__statusy-oplata_perevozchiku {
        width: 180px !important;
        padding-left: 5px;
        flex: 0 0 180px !important;
    }

    .post-list-search-application.post-list .post-list__header .post-list__header-item:nth-child(5),
    .post-list-search-application.post-list .post-list__item .item__statusy-oplata_klientom {
        width: 180px !important;
        flex: 0 0 180px !important;
        padding-left: 5px;
    }

    .post-list-search-application.post-list .post-list__header .post-list__header-item:nth-child(1),
    .post-list-search-application.post-list .post-list__item .item__number-zayavka {
        flex: 0 0 140px !important;
    }
</style>