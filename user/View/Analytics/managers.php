<?php
/** @var App\User\Contoller\Common\HomeController $controller */
/** @var String $titlePage */
/** @var int $countApplication */
/** @var float $sumSalary */
/** @var float $sumWalrus */
$controller->view('Components/head');

//dd($managerList);

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
                        <button class="btn btn-success me-4" id="download-excel"><i class="bi bi-download"></i> Скачать в Excel</button>
                        <button class="btn btn-theme-white" type="button" data-bs-toggle="modal" data-bs-target="#filterModal">
                            <i class="bi bi-funnel-fill" ></i> Фильтры
                        </button>

                    </div>
                </div>
            </div>
        </div>
    </div>


    <section class="manager__list">
        <div class="wrapper">
            <div class="p-4">
                <h3 class="text-center">Данные за <?php echo $date; ?></h3>
            </div>

            <div class="post-list__header d-flex">
                <div class="post-list__header-item">ФИО</div>
                <div class="post-list__header-item text-right">Кол-во заявок, шт</div>
                <div class="post-list__header-item text-right">ЗП</div>
                <div class="post-list__header-item text-right">Выручка, ₽</div>
            </div>
            <div class="post-list__items">
                <div class="post-list__item item d-flex">
                    <div class="w-100 d-flex">
                        <div class="item__manager align-items-center d-flex">
                            ИТОГ
                        </div>
                        <div class="item__trasportirovka text-right">
                            <?php echo $countApplication; ?>
                        </div>
                        <div class="item__osnovnoe text-right">
                            <?php echo number_format(
                                $sumSalary,
                                2,
                                ',',
                                ' '
                            ); ?> ₽
                        </div>
                        <div class="item__job text-right">
                            <?php echo number_format(
                                $sumWalrus,
                                2,
                                ',',
                                ' '
                            ); ?> ₽
                        </div>
                    </div>

                </div>
            </div>
            <?php foreach ($managerList as $manager): ?>
                <div class="post-list__items">
                    <a class="post-list__item item d-flex" href="/journal-list?app-id=<?php echo $manager['link']; ?>" target="_blank">
                        <div class="w-100 d-flex">
                            <div class="item__manager align-items-center d-flex">
                                <div class="avatar">
                                    <img src="https://secure.gravatar.com/avatar/ad516503a11cd5ca435acc9bb6523536?s=150&amp;d=mm&amp;r=gforcedefault=1" alt="avatar">
                                </div>
                                <?php echo $manager['name']; ?>
                            </div>
                            <div class="item__trasportirovka text-right">
                                <?php echo $manager['count-application']; ?>
                            </div>
                            <div class="item__osnovnoe text-right">
                                <?php echo number_format(
                                    $manager['salary'],
                                    2,
                                    ',',
                                    ' '
                                ); ?> ₽
                            </div>
                            <div class="item__job text-right">
                                <?php echo number_format(
                                    $manager['revenue'],
                                    2,
                                    ',',
                                    ' '
                                ); ?> ₽
                            </div>
                        </div>

                    </a>
                </div>
            <?php endforeach; ?>
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
                <form method="GET" style="overflow: auto; height: 75vh;max-height: 75vh" id="form-filter-analytics-manager">
                    <div class="mb-4">
                        <label for="" class="label-theme">Выберите период</label>
                        <input type="text" name="date" autocomplete="off" value="<?php echo $date; ?>"
                               class="form-control form-control-theme form-control-solid" id="date-picker">
                        <script type="module">
                            new AirDatepicker('#date-picker',{
                                range: true,
                                multipleDatesSeparator: ' - ',
                                buttons: ['clear']
                            });
                        </script>

                    </div>
                    <div class="mb-4">
                        <label for="" class="label-theme">Юр. лицо</label>
                        <select name="customer_id_Client" id="" class="form-select">
                            <option value="">По умолчанию</option>
                            <option value="1" <?php if($controller->request->input('customer_id_Client') == 1) echo 'selected'; ?> >
                                ООО «Либеро Логистика»
                            </option>
                            <option value="2" <?php if($controller->request->input('customer_id_Client') == 2) echo 'selected'; ?>>
                                ИП Беспутин Семён Валерьевич
                            </option>
                            <option value="3" <?php if($controller->request->input('customer_id_Client') == 3) echo 'selected'; ?>>
                                ИП Часовников Александр Вадимович
                            </option>
                        </select>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <div class="d-flex w-100 justify-content-between">
                    <button type="button" class="btn btn-theme btn-success" id="btn-form-filter-analytics-manager">Применить</button>
                    <a href="/analytics/managers" class="btn btn-theme btn-light">Сбросить</a>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $('#download-excel').click(function (){
        let date = $('#date-picker').val();
        $.ajax({
            method: 'POST',
            url: '/analytics/managers/ajax/get-excel?date=' + date,
            success: function (response) {
                let data = JSON.parse(response);

                if(data['status'])
                    download_file('Данные за ' + date +'.xlsx', data['link_file']);
            }
        })
    });
</script>
<script>
    $('#btn-form-filter-analytics-manager').click(function () {
        $('#form-filter-analytics-manager').trigger('submit');
    });

</script>

</body>
