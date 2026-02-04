<?php
/** @var App\User\Contoller\Common\HomeController $controller */
/** @var String $titlePage */
$controller->view('Components/head');
?>

<body>
<?php $controller->view('Components/header'); ?>

<main class="history">
    <div class="sub-header" style="padding-bottom: 0;">
        <div class="wrapper">
            <?php $controller->view('Components/breadcrumbs'); ?>

            <h3 class="sub-header-title"><?php echo $titlePage; ?></h3>

            <div class="row" style="margin-top: 40px;">
                <div class="col-8">
                </div>
                <div class="col">
                    <div class="d-flex justify-content-end">
                        <div class="dropdown me-3">
                            <button class="btn btn-theme-white dropdown-toggle dropdown-toggle-without-arrow" type="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-sort-alpha-down" style="font-size: 16px"></i> По умолчанию
                            </button>
                            <ul class="dropdown-menu dropdown-menu-theme">
                                <li><div class="dropdown-item">По умолчанию</div></li>
                                <li><div class="dropdown-item">Сначала новые</div></li>
                                <li><div class="dropdown-item">Сначала старые</div></li>
                            </ul>
                        </div>
                        <button class="btn btn-theme-white" type="button" data-bs-toggle="modal" data-bs-target="#filterModal">
                            <i class="bi bi-funnel-fill" ></i> Фильтры
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <section class="history__list">
        <div class="wrapper">
            <div class="post-list__header d-flex">
                <div class="post-list__header-item">№ ЗАЯВКИ</div>
                <div class="post-list__header-item">МЕНЕДЖЕР</div>
                <div class="post-list__header-item">МАРШРУТ</div>
                <div class="post-list__header-item">КЛИЕНТ</div>
                <div class="post-list__header-item">ДАТА ИЗМЕНЕНИЯ</div>
            </div>
            <?php
                foreach($data as $item){?>
                    <div class="post-list__items">
                        <a href="/application?id=<?php echo $item["id"];?>" class="post-list__item item d-flex">
                            <div class="w-100 d-flex">
                                <div class="item__number-application align-items-center d-flex">
                            <span style="margin-right: 9.5px;">
                                <svg width="10" height="13" viewBox="0 0 10 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M8.375 0.5C8.98438 0.5 9.5 1.01562 9.5 1.625V11.375C9.5 12.0078 8.98438 12.5 8.375 12.5H6.125V10.625C6.125 10.0156 5.60938 9.5 5 9.5C4.36719 9.5 3.875 10.0156 3.875 10.625V12.5H1.625C0.992188 12.5 0.5 12.0078 0.5 11.375V1.625C0.5 1.01562 0.992188 0.5 1.625 0.5H8.375ZM2 6.875C2 7.08594 2.16406 7.25 2.375 7.25H3.125C3.3125 7.25 3.5 7.08594 3.5 6.875V6.125C3.5 5.9375 3.3125 5.75 3.125 5.75H2.375C2.16406 5.75 2 5.9375 2 6.125V6.875ZM4.625 5.75C4.41406 5.75 4.25 5.9375 4.25 6.125V6.875C4.25 7.08594 4.41406 7.25 4.625 7.25H5.375C5.5625 7.25 5.75 7.08594 5.75 6.875V6.125C5.75 5.9375 5.5625 5.75 5.375 5.75H4.625ZM6.5 6.875C6.5 7.08594 6.66406 7.25 6.875 7.25H7.625C7.8125 7.25 8 7.08594 8 6.875V6.125C8 5.9375 7.8125 5.75 7.625 5.75H6.875C6.66406 5.75 6.5 5.9375 6.5 6.125V6.875ZM2.375 2.75C2.16406 2.75 2 2.9375 2 3.125V3.875C2 4.08594 2.16406 4.25 2.375 4.25H3.125C3.3125 4.25 3.5 4.08594 3.5 3.875V3.125C3.5 2.9375 3.3125 2.75 3.125 2.75H2.375ZM4.25 3.875C4.25 4.08594 4.41406 4.25 4.625 4.25H5.375C5.5625 4.25 5.75 4.08594 5.75 3.875V3.125C5.75 2.9375 5.5625 2.75 5.375 2.75H4.625C4.41406 2.75 4.25 2.9375 4.25 3.125V3.875ZM6.875 2.75C6.66406 2.75 6.5 2.9375 6.5 3.125V3.875C6.5 4.08594 6.66406 4.25 6.875 4.25H7.625C7.8125 4.25 8 4.08594 8 3.875V3.125C8 2.9375 7.8125 2.75 7.625 2.75H6.875Z" fill="#A1A5B7"></path>
                                </svg>
                            </span>
                                    <div>
                                        <?php echo $item["application_number"].'-Т';?>
                                        <span><?php echo $item["application_date"];?></span>
                                    </div>
                                </div>
                                <div class="item__manager">
                                    <div class="avatar">
                                        <img src="<?php echo $item["manager"]["avatar"];?>" alt="avatar">
                                    </div>
                                    <?php echo $item["manager"]["name"];?>
                                </div>
                                <div class="item__trasportirovka">
                                    <?php echo $item["marshrut"];?>
                                </div>
                                <div class="item__osnovnoe ">
                                    <?php echo $item["client_name"];?>
                                </div>

                                <div class="item__date">
                                    <?php echo $item["change_datetime"];?>
                                </div>
                            </div>

                        </a>
                    </div>
                <?php }
            ?>
            <?php $controller->view('Components/pagination'); ?>
        </div>
    </section>

</main>
<div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="margin-right: 50px">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="filterModalLabel">Параметры фильтрации</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
            </div>
            <div class="modal-body">
                <form action="" style="overflow: auto; height: 75vh;max-height: 75vh">
                    <div class="mb-4">
                        <label for="" class="label-theme">Выберите период</label>
                        <input type="text" class="form-control form-control-theme form-control-solid">
                    </div>
                    <div class="mb-4">
                        <label for="" class="label-theme">Менеджер:</label>
                        <select name="manager[]" id="" class="form-select js-chosen" data-placeholder="Выберите менеджера" multiple>
                            <?php foreach ($userList as $user): ?>
                                <option value="<?php echo $user->id();?>"
                                    <?php if($user->id() == $condition['manager']) echo 'selected'; ?>
                                >
                                    <?php echo $user->get()['name'] ." " . $user->get()['surname'];?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="" class="label-theme">Клиент:</label>
                        <select name="client" id="" class="form-select js-chosen">
                            <option value="null">По умолчанию</option>
                            <?php foreach ($clientList as $client): ?>
                                <option value="<?php echo $client['id']; ?>"
                                    <?php if((int)$condition['client'] == $client['id']) echo 'selected'; ?> >
                                    <?php echo str_replace(['«','»','&#171;','&#187;'],'', $client['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="" class="label-theme">Перевозчик:</label>
                        <select name="carrier" id="" class="form-select js-chosen">
                            <option value="null">По умолчанию</option>
                            <?php foreach ($carrierList as $carrier): ?>
                                <option value="<?php echo $carrier['id']; ?>"
                                    <?php if((int)$condition['carrier'] == $carrier['id']) echo 'selected'; ?> >
                                    <?php echo str_replace(['«','»','&#171;','&#187;'],'', $carrier['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <div class="d-flex w-100 justify-content-between">
                    <button type="button" class="btn btn-theme btn-success">Применить</button>
                    <button type="button" class="btn btn-theme btn-light" data-bs-dismiss="modal">Сбросить</button>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
