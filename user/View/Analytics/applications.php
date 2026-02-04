<?php
/** @var App\User\Contoller\Common\HomeController $controller */
/** @var String $titlePage */
/** @var array $applicationList */
/** @var array $userList */
/** @var int $countPage */
/** @var int $page */
/** @var string $link */
$controller->view('Components/head');

//dd($condition);
?>

<body>
<?php $controller->view('Components/header'); ?>

<main class="analytics">
    <div class="sub-header" style="padding-bottom: 0;">
        <div class="wrapper">
            <?php $controller->view('Components/breadcrumbs'); ?>

            <h3 class="sub-header-title"><?php echo $titlePage; ?></h3>

            <div class="row" style="margin-top: 40px;">
                <div class="col-8">
                    <?php $controller->view('Analytics/sub-header'); ?>
                </div>
                <div class="col">
                    <div class="d-flex justify-content-end">
                        <div class="dropdown me-3">
                            <button class="btn btn-theme-white dropdown-toggle dropdown-toggle-without-arrow" type="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-sort-alpha-down" style="font-size: 16px"></i> По умолчанию
                            </button>
                            <ul class="dropdown-menu dropdown-menu-theme">
                                <li><a href="analytics/applications" class="dropdown-item">По умолчанию</a></li>
                                <li><a href="?order=DESC" class="dropdown-item">Сначала новые</a></li>
                                <li><a href="?order=ASC" class="dropdown-item">Сначала старые</a></li>
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


    <section class="analytics-applications__list">
        <div class="wrapper">
            <div class="post-list__header d-flex">
                <div class="post-list__header-item">№ ЗАЯВКИ</div>
                <div class="post-list__header-item">МАРШРУТ</div>
                <div class="post-list__header-item">КЛИЕНТ / ВОДИТЕЛЬ</div>
                <div class="post-list__header-item">МЕНЕДЖЕР</div>
                <div class="post-list__header-item">ВЫРУЧКА, ₽</div>
                <div class="post-list__header-item">ДОХОД, ₽</div>
            </div>
            <?php foreach ($applicationList as $application): ?>

                <div class="post-list__items">
                    <a href="/application?id=<?php echo $application['id'];?>" class="post-list__item item d-flex">
                        <div class="w-100 d-flex">
                            <div class="item__number-application align-items-center d-flex">
                            <span style="margin-right: 9.5px;">
                                <svg width="10" height="13" viewBox="0 0 10 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M8.375 0.5C8.98438 0.5 9.5 1.01562 9.5 1.625V11.375C9.5 12.0078 8.98438 12.5 8.375 12.5H6.125V10.625C6.125 10.0156 5.60938 9.5 5 9.5C4.36719 9.5 3.875 10.0156 3.875 10.625V12.5H1.625C0.992188 12.5 0.5 12.0078 0.5 11.375V1.625C0.5 1.01562 0.992188 0.5 1.625 0.5H8.375ZM2 6.875C2 7.08594 2.16406 7.25 2.375 7.25H3.125C3.3125 7.25 3.5 7.08594 3.5 6.875V6.125C3.5 5.9375 3.3125 5.75 3.125 5.75H2.375C2.16406 5.75 2 5.9375 2 6.125V6.875ZM4.625 5.75C4.41406 5.75 4.25 5.9375 4.25 6.125V6.875C4.25 7.08594 4.41406 7.25 4.625 7.25H5.375C5.5625 7.25 5.75 7.08594 5.75 6.875V6.125C5.75 5.9375 5.5625 5.75 5.375 5.75H4.625ZM6.5 6.875C6.5 7.08594 6.66406 7.25 6.875 7.25H7.625C7.8125 7.25 8 7.08594 8 6.875V6.125C8 5.9375 7.8125 5.75 7.625 5.75H6.875C6.66406 5.75 6.5 5.9375 6.5 6.125V6.875ZM2.375 2.75C2.16406 2.75 2 2.9375 2 3.125V3.875C2 4.08594 2.16406 4.25 2.375 4.25H3.125C3.3125 4.25 3.5 4.08594 3.5 3.875V3.125C3.5 2.9375 3.3125 2.75 3.125 2.75H2.375ZM4.25 3.875C4.25 4.08594 4.41406 4.25 4.625 4.25H5.375C5.5625 4.25 5.75 4.08594 5.75 3.875V3.125C5.75 2.9375 5.5625 2.75 5.375 2.75H4.625C4.41406 2.75 4.25 2.9375 4.25 3.125V3.875ZM6.875 2.75C6.66406 2.75 6.5 2.9375 6.5 3.125V3.875C6.5 4.08594 6.66406 4.25 6.875 4.25H7.625C7.8125 4.25 8 4.08594 8 3.875V3.125C8 2.9375 7.8125 2.75 7.625 2.75H6.875Z" fill="#A1A5B7"></path>
                                </svg>
                            </span>
                                <div>
                                    <?php echo $application['application_number']; ?>
                                    <span><?php echo $application['date']; ?></span>
                                </div>
                            </div>
                            <div class="item__trasportirovka">
                                <?php if(count($application['route_client'])): ?>
                                    <?php for ($i = 0; $i < count($application['route_client']); $i++): ?>
                                        <?php echo $application['route_client'][$i]['city']; ?>
                                        <?php if($i < count($application['route_client']) -1 ) echo ' - '; ?>
                                    <?php endfor; ?>
                                <?php endif; ?>
                            </div>
                            <div class="item__osnovnoe">
                                <?php echo $application['client']; ?>
                                <span><?php echo $application['driver']; ?><br>
                            </div>
                            <div class="item__manager">
                                <div class="avatar d-flex w-100">
                                    <img alt="аватар" src="<?php echo $application['user']['avatar']; ?>"  class="avatar avatar-28 photo my-class-1 super-gravarar" height="28" width="28" style="top:-5px;" title="Миша в горах" decoding="async">
                                    <span><?php echo $application['user']['name']; ?></span>
                                </div>
                            </div>
                            <div class="item__job">
                                <?php echo number_format(
                                    $application['application_walrus'],
                                    2,
                                    ',',
                                    '.'
                                ); ?> ₽
                            </div>
                            <div class="item__job">
                                <?php echo number_format(
                                    $application['manager_share'],
                                    2,
                                    ',',
                                    '.'
                                ); ?> ₽
                            </div>
                        </div>

                    </a>
                </div>
            <?php endforeach; ?>

            <?php if($countPage > 1): ?>
                <?php $controller->view('Components/pagination'); ?>
            <?php endif; ?>

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
                <form action="" style="overflow: auto; height: 75vh;max-height: 75vh" id="form-filter-analytics-app">
                    <div class="mb-4">
                        <label for="" class="label-theme">Выберите период</label>
                        <input type="text" name="date" id="date-picker" class="form-control form-control-theme form-control-solid"
                               value="<?php if(isset($condition['date'])) echo $condition['date']; ?>">
                    </div>

                    <script>
                        new AirDatepicker('#date-picker',{
                            range: true,
                            multipleDatesSeparator: ' - ',
                        });
                    </script>
                    <?php if($isFullCRMAccess): ?>
                        <div class="mb-4">
                            <label for="" class="label-theme">Менеджер:</label>
                            <select name="manager" id="" class="form-select" >
                                <option value="null">По умолчанию</option>
                                <?php foreach ($userList as $user): ?>
                                    <option value="<?php echo $user->id();?>"
                                        <?php if($user->id() == $condition['manager']) echo 'selected'; ?>>
                                        <?php echo $user->fullName();?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php endif; ?>
                    <div class="mb-4">
                        <label for="" class="label-theme">Клиент:</label>
                        <select name="client" id="" class="form-select js-chosen" >
                            <option value="null">По умолчанию</option>
                            <?php foreach ($listClients as $client): ?>
                                <option value="<?php echo $client['id']; ?>"
                                    <?php if(isset($condition['client']) AND $condition['client'] == $client['id']) echo 'selected'; ?>
                                ><?php echo str_replace(['«','»'],'', $client['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="" class="label-theme">Перевозчик:</label>
                        <select name="carrier" id="" class="form-select js-chosen" >
                            <option value="null">По умолчанию</option>
                            <?php foreach ($listCarriers as $carrier): ?>
                                <option value="<?php echo $carrier['id']; ?>"
                                        <?php if(isset($condition['carrier']) AND $condition['carrier'] == $carrier['id']) echo 'selected'; ?>
                                ><?php echo str_replace(['«','»'],'',$carrier['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="" class="label-theme">Юр.лицо:</label>
                        <select name="customer" id="" class="form-select" >
                            <option value="null">По умолчанию</option>
                            <option value="1" <?php if(isset($condition['customer']) AND $condition['customer'] == 1) echo 'selected'; ?>>ООО «Либеро Логистика»</option>
                            <option value="2" <?php if(isset($condition['customer']) AND $condition['customer'] == 2) echo 'selected'; ?>>ИП Беспутин Семён Валерьевич</option>
                            <option value="3" <?php if(isset($condition['customer']) AND $condition['customer'] == 3) echo 'selected'; ?>>ИП Часовников Александр Вадимович</option>
                        </select>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <div class="d-flex w-100 justify-content-between">
                    <button type="button" class="btn btn-theme btn-success" id="btn-filter-analytics-app">Применить</button>
                    <a href="/analytics/applications" class="btn btn-theme btn-light">Сбросить</a>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $('#btn-filter-analytics-app').click(function () {
        $('#form-filter-analytics-app').trigger('submit');
    })
</script>
</body>
