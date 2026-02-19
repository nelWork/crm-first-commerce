<?php /** @var App\User\Contoller\Common\HomeController $controller */
/** @var bool $isFullCRMAccess */
/** @var int $activeHeaderItem */
?>
<div id="app-header" class="app-header">
    <div class="app-container container-xxl d-flex align-items-stretch justify-content-between" id="app-header-container">
        <div class="app-header-home-container">
            <a href="/" class="link"><span>CRM</span></a>
        </div>
        <div class="app-header-wrapper d-flex align-items-center">
            <div class="app-header-menu d-flex">
                <div class="app-header-menu-item">
                    <a href="/applications-list" class="<?php if($activeHeaderItem == 1) echo 'active'; ?>">
                        Заявки
<!--                        --><?php //if($controller->auth->user()->fullCRM()):
//                            $countOverdue = $controller->database->superSelect('applications', [
//                                    'cancelled' => 0,
//                                    'application_section_journal' => [1,2],
//                                    'dateField' => [
//                                        'name' => 'date',
//                                        'start' => '2025-01-01',
//                                        'end' => date('Y-m-d',strtotime('-40 days'))
//                                    ],
//                                ],
//                                [],
//                                1,
//                                ['COUNT(*)']
//                            )[0]['COUNT(*)'] ?? 0;
//
//                            ?>
<!--                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"-->
<!--                        style="transform: translate(-50%, 70%)!important">-->
<!--                            --><?php //echo $countOverdue; ?>
<!--                        <span class="visually-hidden">unread messages</span>-->
<!--                        --><?php //endif; ?>
                    </a>
                </div>
                <div class="app-header-menu-item">
                    <a href="/calculator" class="<?php if($activeHeaderItem == 13) echo 'active'; ?>">Калькулятор</a>
                </div>
                <div class="app-header-menu-item">
                    <a href="/analytics" class="<?php if($activeHeaderItem == 2) echo 'active'; ?>">Аналитика</a>
                </div>

                <?php if($controller->auth->user()->fullCRM() ): ?>
                    <div class="app-header-menu-item">
                        <a href="/history" class="<?php if($activeHeaderItem == 3) echo 'active'; ?>">История</a>
                    </div>
                <?php endif; ?>

                <div class="app-header-menu-item">
                    <a href="/base/clients" class="<?php if($activeHeaderItem == 4) echo 'active'; ?>">Базы</a>
                </div>
                <?php if(!$controller->auth->user()->fullCRM() AND $controller->auth->user()->id() == 46): ?>
                <div class="app-header-menu-item">
                    <a href="/register-payment" class="<?php if($activeHeaderItem == 14) echo 'active'; ?>">Реестр на оплату</a>
                </div>
                <?php endif; ?>
                <?php if($controller->auth->user()->fullCRM()): ?>

                    <div class="app-header-menu-item dropdown" >
                        <a class="dropdown-toggle <?php if(in_array($activeHeaderItem, [5,6,11,10])) echo 'active'; ?>" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Журналы
                        </a>
                        <ul class="dropdown-menu p-2" style="width: 200px;border: none">
                            <li class="mb-2">
                                <div class="app-header-menu-item">
                                    <a href="/journal" class="<?php if($activeHeaderItem == 5) echo 'active'; ?>">Бухг. журнал</a>
                                </div>
                            </li>

                            <li class="mb-2">
                                <div class="app-header-menu-item">
                                    <a href="/journal/parse-txt" class="<?php if($activeHeaderItem == 11) echo 'active'; ?>">
                                        Журнал выписка
                                     </a>
                                </div>
                            </li>
<!--                            <li class="mb-2">-->
<!--                                <div class="app-header-menu-item">-->
<!--                                    <a href="/journal/control-payment" class="--><?php //if($activeHeaderItem == 12) echo 'active'; ?><!--">-->
<!--                                        Контроль выписки-->
<!--                                    </a>-->
<!--                                </div>-->
<!--                            </li>-->
                            <li class="mb-2">
                                <div class="app-header-menu-item">
                                    <a href="/journal/manager" class="<?php if($activeHeaderItem == 6) echo 'active'; ?>">
                                        Логист. журнал
                                    </a>
                                </div>
                            </li>
                            <!-- <li class="mb-2">
                                <div class="app-header-menu-item">
                                    <a href="/journal/sales" class="<?php if($activeHeaderItem == 6) echo 'active'; ?>">
                                        Журнал отдела продаж
                                    </a>
                                </div>
                            </li> -->
                            <!-- <li class="mb-2">
                                <div class="app-header-menu-item">
                                    <a href="/register-payment" class="<?php if($activeHeaderItem == 14) echo 'active'; ?>">
                                    Реестр на оплату
                                    </a>
                                </div>
                            </li> -->
                            <li class="mb-2">
                                <div class="app-header-menu-item">
                                    <a href="/register-application-payment" class="<?php if($activeHeaderItem == 15) echo 'active'; ?>">
                                        Реестр оплаты заявок
                                    </a>
                                </div>
                            </li>
                            <li class="mb-2">
                                <div class="app-header-menu-item">
                                    <a href="/register-additional-expenses" class="<?php if($activeHeaderItem == 16) echo 'active'; ?>">
                                        Реестр доп. затрат
                                    </a>
                                </div>
                            </li>
                            <li class="mb-2">
                                <div class="app-header-menu-item">
                                    <a href="/register-application-consideration" class="<?php if($activeHeaderItem == 17) echo 'active'; ?>">
                                        Реестр заявок на проверке
                                    </a>
                                </div>
                            </li>
                            <!-- <li class="mb-2">
                                <div class="app-header-menu-item">
                                    <a href="/journal-list?no-profit=1">Журнал без прибыли</a>
                                </div>
                            </li> -->
                            <!-- <li>
                                <div class="app-header-menu-item">
                                    <a href="/journal/manager?closed-months=1" class="<?php if($activeHeaderItem == 10) echo 'active'; ?>">Закрытые поездки</a>
                                </div>
                            </li> -->
                        </ul>
                    </div>


<!--                    <div class="app-header-menu-item">-->
<!--                        <a href="/registry" class="--><?php //if($activeHeaderItem == 7) echo 'active'; ?><!--">Реестр</a>-->
<!--                    </div>-->
                <?php else: ?>
                    <div class="app-header-menu-item">
                        <a href="/journal/manager" class="<?php if($activeHeaderItem == 6) echo 'active'; ?>">Журнал</a>
                    </div>
                    <div class="app-header-menu-item d-none">
                        <a href="/journal/manager?closed-months=1" class="<?php if($activeHeaderItem == 10) echo 'active'; ?>">Закрытые поездки</a>
                    </div>
                <?php endif; ?>

                <?php if(count($controller->auth->user()->getSubordinatesList()) > 1 AND $controller->auth->user()->id() != 7): ?>
                    <!-- <div class="app-header-menu-item">
                        <a href="/journal/manager?rop=1" class="<?php if($activeHeaderItem == 9) echo 'active'; ?>">Журнал РОП</a>
                    </div> -->
                <?php endif; ?>

                <?php if($controller->auth->user()->financeAdmin()): ?>
                    <!-- <div class="app-header-menu-item">
                        <a href="/supervisor" class="<?php if($activeHeaderItem == 8) echo 'active'; ?>">Директор</a>
                    </div> -->
                <?php endif; ?>

            </div>
            <div class="app-header-navbar">
                <?php //if($controller->auth->user()->fullCRM() OR count($controller->auth->user()->getSubordinatesList()) > 0): ?>
                <div class="btn btn-secondary d-none"  data-bs-toggle="modal" data-bs-target="#mainSearchModal" style="margin-left: -40px;">
                    <i class="bi bi-search-heart-fill" style="position: unset"></i>
                </div>
                <?php //endif; ?>
                <form action="/applications-list" id="btn-header-search" data-bs-toggle="modal" data-bs-target="#mainSearchModal">
                    <i class="bi bi-search"></i>
                    <input type="text" name="search" id="main-search" class="form-control" placeholder="Поиск...">
                    <input type="hidden" name="search-select" value="all">
                </form>
                <script>
                    $('#main-search').click(function(e){
                        e.preventDefault();
                    })
                </script>
                <div class="notification-header-container dropdown" style="width: 24px;height: 24px; display: flex; align-items: center; justify-content: center">
                    <div class="notification-header" style="cursor: pointer ;font-size: 20px; color: white" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
                        <?php if($controller->auth->user()->hasActiveNotifications()): ?>
                            <div class="position-relative">
                                <span class="bi bi-bell-fill"></span>
                                <span class="position-absolute translate-middle bg-danger border border-light rounded-circle animation-notification" style="padding: 4px; top: 6px; right: -9px">
                                    <span class="visually-hidden">Новые уведомления</span>
                                </span>
                            </div>

                        <?php else: ?>
                            <span class="bi bi-bell"></span>

                        <?php endif; ?>
                    </div>
                    <div class="dropdown-menu p-4 dropdown-notification" style="width: 300px;">
                        <h5>Уведомления</h5>
                        <?php if($controller->auth->user()->hasActiveNotifications()): ?>
                        <div class="list-notification pb-2" >
                            <?php foreach ($controller->auth->user()->getListNotificationsTo() as $notification): if(!$notification['for_client']): ?>
                                <div class="item" data-bs-toggle="collapse" href="#collapseExample-<?php echo $notification['id']; ?>"
                                     role="button" aria-expanded="false" aria-controls="collapseExample-<?php echo $notification['id']; ?>">
                                    <div class="header-notification">
                                        <?php echo $notification['name']; ?>
                                        <a target="_blank" class="js-go-to-application" href="/application?id=<?php echo $notification['id_application']  ?>">
                                            № <?php echo $notification['application_number']; ?>
                                        </a>
                                    </div>
                                    <div class="body-notification collapse" id="collapseExample-<?php echo $notification['id']; ?>">
                                        <p><?php echo $notification['text']; ?></p>
                                        <button class="btn btn-primary w-100 js-set-complete-notification"
                                                data-id-notification="<?php echo $notification['id']; ?>">
                                            Выполнено
                                        </button>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="item" data-bs-toggle="collapse" href="#collapseExample-<?php echo $notification['id']; ?>"
                                     role="button" aria-expanded="false" aria-controls="collapseExample-<?php echo $notification['id']; ?>">
                                    <div class="header-notification">
                                        <?php echo $notification['name']; ?>

                                    </div>
                                    <div class="body-notification collapse" id="collapseExample-<?php echo $notification['id']; ?>">
                                        <a target="_blank" class="js-go-to-application" href="/client?id=<?php echo $notification['id_application']  ?>">
                                            <p><?php echo $notification['text']; ?></p>
                                        </a>
                                        <button class="btn btn-primary w-100 js-set-complete-notification"
                                                data-id-notification="<?php echo $notification['id']; ?>">
                                            Выполнено
                                        </button>
                                    </div>
                                </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                        <?php else: ?>
                            В данный момент нет уведомлений.
                        <?php  endif; ?>
                    </div>
                </div>
                <a href="/profile" class="app-header-profile-link">
                    <img src="<?php echo $controller->auth->user()->avatar();?>" alt="avatar">
                </a>
            </div>
        </div>
    </div>
</div>
<script>
    $('.js-go-to-application').click(function () {
        document.location.href = $(this).attr('href');
    })
    $('.js-set-complete-notification').click(function () {
        let id = $(this).data('id-notification');

        let $this = $(this);

        $.ajax({
            method: 'POST',
            url: '/notifications/ajax/set-complete-notification',
            data: {id: id},
            success: function (response) {
                console.log(response);
                response = JSON.parse(response);

                if(response['status'])
                    $('.item').has($this).remove();
            }
        })
    })
</script>
<style>
    .animation-notification{
        animation: fadeNotification 3s ease infinite;
    }
    @keyframes fadeNotification {
        0%{
            opacity: 1;
        }
        50%{
            opacity: 0;
        }
        100%{
            opacity: 1;
        }
    }
</style>
<?php //if($controller->auth->user()->fullCRM() OR count($controller->auth->user()->getSubordinatesList()) > 0): ?>
<script>
    $(document).ready(function () {
        $('#btn-header-search').click(function(){
            setTimeout(function(){
                document.getElementById('search-input-modal').focus();
            }, 500) 
        });
        let timer;
        $('#search-input-modal').on('input',function(){
            let search = $(this).val().trim();
            clearTimeout(timer);

            if(search.length < 2) {
                $('#application-container, #prr-application-container, #carrier-container, #client-container').empty();
                return;
            }

            timer = setTimeout(function() {
                $.ajax({
                    method: 'POST',
                    url: '/ajax/search',
                    data: {search: search},
                    success: function(response){
                        console.log(response);
                        $('#application-container').html(response);
                    } 
                });

                $.ajax({
                    method: 'POST',
                    url: '/ajax/search/prr',
                    data: {search: search},
                    success: function(response){
                        console.log(response);
                        $('#prr-application-container').html(response);
                    } 
                });
                $.ajax({
                    method: 'POST',
                    url: '/ajax/search/carrier',
                    data: {search: search},
                    success: function(response){
                        console.log(response);
                        $('#carrier-container').html(response);
                    } 
                });
                $.ajax({
                    method: 'POST',
                    url: '/ajax/search/client',
                    data: {search: search},
                    success: function(response){
                        console.log(response);
                        $('#client-container').html(response);
                    } 
                });
            }, 650);
        });
    })
</script>
<?php //endif; ?>
<style>
    .nav-pills-search .nav-link{
        color: #0a58ca;
    }
</style>
<div class="modal fade" id="mainSearchModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-fullscreen">
    <div class="modal-content" style="/*background:#f5f5f51a;*/ background: whitesmoke">
      <div class="modal-body">
        <div class="d-flex align-items-center">
            <input type="text" class="form-control form-control-sm w-100" placeholder="Поиск..." id="search-input-modal">
            <button type="button" class="ms-4 btn btn-danger btn-lg" data-bs-dismiss="modal" aria-label="Close">Закрыть</button>

        </div>
        <div class="container-fluid d-flex justify-content-center p-4">
            <ul class="nav nav-pills nav-pills-search">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="#" data-name-container="application-container">
                        Заявки Экспедирование (<span id="span-quantity-application">0</span>)
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" data-name-container="prr-application-container">
                        Заявки ПРР (<span id="span-quantity-prr-application">0</span>)
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" data-name-container="client-container">
                        Клиенты (<span id="span-quantity-client">0</span>)
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" data-name-container="carrier-container">
                        Перевозчики (<span id="span-quantity-carrier">0</span>)
                    </a>
                </li>
            </ul>
        </div>
        <div id="application-container" class="container container-search m-auto"></div>
        <div id="prr-application-container" class="container container-search m-auto d-none"></div>
        <div id="client-container" class="container container-search m-auto d-none"></div>
        <div id="carrier-container" class="container container-search m-auto d-none"></div>
        </div>
    </div>
  </div>
</div>

<script>
    $('.nav-pills-search .nav-link').click(function(){
        $('.nav-pills-search .nav-link').removeClass('active');
        $('.container-search').addClass('d-none');
        $(this).addClass('active');
        $('#' + $(this).data('name-container')).removeClass('d-none');
    });
</script>