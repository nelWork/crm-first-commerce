<?php
/** @var App\User\Contoller\Common\HomeController $controller */
/** @var String $titlePage */
/** @var array $applicationList */
/** @var array $managerList */
/** @var bool $isFullCRMAccess */
/** @var int $idUser */
/** @var string $link */
$controller->view('Components/head');

//dd($applicationList);
?>

<style>
    .context-menu {
        display: none;
        position: absolute;
        z-index: 1000;
        background: #fff;
        border: 1px solid #ccc;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        padding: 10px;
        border-radius: 5px;
    }

    .context-menu ul {
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .context-menu li {
        padding: 8px 12px;
        cursor: pointer;
        transition: background 0.3s;
    }

    .context-menu li:hover {
        background: #f0f0f0;
    }

</style>
<body>
<?php $controller->view('Components/header'); ?>

<main class="analytics mb-5">
    <div class="sub-header" style="padding-bottom: 0;">
        <div class="wrapper" >
            <?php $controller->view('Components/breadcrumbs'); ?>

            <h3 class="sub-header-title"><?php echo $titlePage; ?></h3>

            <div class="row" style="margin-top: 40px;">
                <div class="col-7">
                    <?php $controller->view('Analytics/sub-header'); ?>
                </div>
                <div class="col">
                    <div class="d-flex justify-content-end">
                        <?php if($isFullCRMAccess): ?>
                            <button class="btn btn-success p-2 w-50 me-2" id="excel-application-list-btn">Список заявок Excel</button>
                        <select name="" id="select-manager-salary" class="js-chosen w-50">
                            <?php foreach ($managerList as $manager): ?>
                                <option value="<?php echo $manager['id'] ?>" <?php
                                    if($manager['id'] == $idUser) echo  'selected="selected"';
                                ?>>
                                    <?php echo $manager['name'] .' ' .$manager['surname']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="context-menu" class="context-menu">
        <ul>
            <?php if($isFullCRMAccess): ?>
            <li data-action="add-advance" data-bs-toggle="modal" data-bs-target="#addAdvance">Добавить аванс</li>
            <li data-action="add-official-5" data-bs-toggle="modal" data-bs-target="#addOfficial5" >Добавить официальную 5 числа</li>
            <li data-action="add-official-20" data-bs-toggle="modal" data-bs-target="#addOfficial20">Добавить официальную 20 числа</li>
            <li data-action="add-fine" data-bs-toggle="modal" data-bs-target="#addFine">Добавить штраф</li>
            <?php endif; ?>
            <li data-action="detailed-statistics">Подробная статистика</li>
        </ul>
    </div>

    <section class="analytics-applications__list " style="padding-bottom: 200px;">
        <div class="wrapper">
            <!-- <a href="/analytics/salary?year=2026" class="btn btn-warning mb-4 mx-auto">2026</a> -->
            <div class="post-list__header d-flex">

                <div class="post-list__header-item">Зарплата (оклад + проценты)</div>
                <div class="post-list__header-item">Авансы</div>
                <div class="post-list__header-item">Официальная 5 числа</div>
                <div class="post-list__header-item">Официальная 20 числа</div>
                <div class="post-list__header-item">Штрафы</div>
                <div class="post-list__header-item">Закрытые заявки</div>
            </div>
            <?php foreach ($salaryList as $salary): ?>
                <div class="post-list__items item w-100 color-<?php echo $salary['color']; ?>" style="<?php if($salary['color'] == 1) echo 'background-color: rgba(0,0,0,0.22)';
                if ($salary['color'] == 2) echo 'background-color: rgba(4,255,4,0.22)';
                ?>" >
                    <div class="w-100 d-flex" style="font-weight: 600">
                        <div class="item__salary-salary" style="font-weight: 600 " data-id-salary="<?php echo $salary['id']; ?>"
                             >
                            <div><?php echo $salary['salary']; ?> ₽</div>
                            <span class="small">(<?php echo $salary['date_start'] .' - ' . $salary['date_end']; ?>)</span>
                        </div>
                        <div class="item__salary-advance">
                            <?php echo $salary['advance']; ?> ₽
                        </div>
                        <div class="item__salary-official_5">
                            <?php echo $salary['official5']; ?> ₽
                        </div>
                        <div class="item__salary-official_20">
                            <?php echo $salary['official20']; ?> ₽
                        </div>
                        <div class="item__salary-fines">
                            <?php echo $salary['fines']; ?> ₽
                        </div>
                        <div class="item__salary-closed_applications">
                            <p>
                                <?php echo $salary['closed_applications']; ?>
                            </p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

        </div>
    </section>

</main>
<div class="modal fade" id="addAdvance" tabindex="-1" aria-labelledby="addAdvanceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="filterModalLabel">Аванс</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
            </div>
            <div class="modal-body">
                <form action="" style="" id="form-add-advance">
                    <input type="hidden" name="id-salary" id="id-salary-advance" value="0">
                    <input type="hidden" name="id-manager" value="<?php echo $idUser; ?>">
                    <input type="hidden" name="type" value="0">
                    <div class="mb-3">
                        <label for="">Размер аванса</label>
                        <input type="number" class="form-control" name="quantity" min="1">
                    </div>
                    <button class="btn btn-success w-100">Добавить</button>
                </form>
            </div>

        </div>
    </div>
</div>
<div class="modal fade" id="addOfficial5" tabindex="-1" aria-labelledby="addOfficial20ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="filterModalLabel">Официальная зарплата 5 числа</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
            </div>
            <div class="modal-body">
                <form action="" style="" id="form-add-official5">
                    <input type="hidden" name="id-salary" id="id-salary-official5" value="0">
                    <input type="hidden" name="id-manager" value="<?php echo $idUser; ?>">
                    <input type="hidden" name="type" value="1">
                    <div class="mb-3">
                        <label for="">Размер официальной зарплаты 5 числа</label>
                        <input type="number" class="form-control" required name="quantity" min="1">
                    </div>
                    <button class="btn btn-success w-100">Добавить</button>
                </form>
            </div>

        </div>
    </div>
</div>
<div class="modal fade" id="addOfficial20" tabindex="-1" aria-labelledby="addOfficial20ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="filterModalLabel">Официальная зарплата 20 числа</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
            </div>
            <div class="modal-body">
                <form action="" style="" id="form-add-official20">
                    <input type="hidden" name="id-salary" id="id-salary-official20" value="0">
                    <input type="hidden" name="id-manager" value="<?php echo $idUser; ?>">
                    <input type="hidden" name="type" value="2">
                    <div class="mb-3">
                        <label for="">Размер официальной зарплаты 20 числа</label>
                        <input type="number" class="form-control" required name="quantity" min="1">
                    </div>
                    <button class="btn btn-success w-100">Добавить</button>

                </form>
            </div>

        </div>
    </div>
</div>
<div class="modal fade" id="addFine" tabindex="-1" aria-labelledby="addFineModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="filterModalLabel">Добавить штраф</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
            </div>
            <div class="modal-body">
                <form action="" style="" id="form-add-fine">
                    <input type="hidden" name="id-salary" id="id-salary-fine" value="0">
                    <input type="hidden" name="id-manager" value="<?php echo $idUser; ?>">
                    <div class="mb-3">
                        <label for="">Размер штрафа</label>
                        <input type="number" class="form-control" required name="quantity" min="1">
                    </div>
                    <div class="mb-3">
                        <label for="">Описание</label>
                        <textarea name="description" class="form-control" ></textarea>
                    </div>
                    <button class="btn btn-success w-100">Добавить</button>

                </form>
            </div>

        </div>
    </div>
</div>


<script>
    $('#excel-application-list-btn').click(function () {
        let idManager = $('#select-manager-salary').val();
        let selectedText = $('#select-manager-salary').find(':selected').text();
        selectedText = selectedText.replaceAll('  ','')
        $.ajax({
            method: 'POST',
            url: '/analytics/get-list-manager-application-excel',
            data: {'id-manager': idManager},
            success: function (response) {
                let data = JSON.parse(response);

                if(data['status'])
                    download_file('Список заявок ' +selectedText +'.xlsx', data['link_file']);
            }
        })
    })
</script>

<script>
    $(document).ready(function () {
        const $contextMenu = $("#context-menu");

        // Показать контекстное меню
        $(".analytics-applications__list").on("contextmenu", ".item.color-2,.item.color-1", function (e) {
            e.preventDefault();

            // Сохраняем текущий элемент для дальнейшей обработки
            const $currentItem = $(this);
            $contextMenu
                .data("currentItem", $currentItem)
                .css({
                    top: e.pageY,
                    left: e.pageX,
                })
                .show();
        });

        // Обработка кликов на пункты меню
        $contextMenu.on("click", "li", function () {
            const action = $(this).data("action");
            const $item = $contextMenu.data("currentItem");

            let idSalary = $item.find(".item__salary-salary").data('id-salary');

            console.log(idSalary);

            // Действия в зависимости от выбора
            switch (action) {
                case "add-advance":
                    $('#id-salary-advance').val(idSalary);
                    break;
                case "add-fine":
                    $('#id-salary-fine').val(idSalary);
                    break;
                case "add-official-5":
                    $('#id-salary-official5').val(idSalary);
                    break;
                case "add-official-20":
                    $('#id-salary-official20').val(idSalary);
                    break;
                case "detailed-statistics":
                    document.location.href = '/analytics/salary/statistics?id=' + idSalary;
            }

            // Скрыть меню после выбора
            $contextMenu.hide();
        });

        // Скрытие меню при клике вне его
        $(document).on("click", function () {
            $contextMenu.hide();
        });

        // Скрытие меню при скролле
        $(window).on("scroll", function () {
            $contextMenu.hide();
        });
    });

</script>
<script>
    $('#select-manager-salary').change(function () {
        let id = $(this).val();

        document.location.href = '/analytics/salary?id-user=' + id;
    });

    $('#form-add-advance').submit(function (e){
        e.preventDefault();

        let data = $(this).serializeArray();
        ajaxAddPaymentsManager(data);

    });

    $('#form-add-fine').submit(function (e){
        e.preventDefault();

        let data = $(this).serializeArray();

        $.ajax({
            method: 'POST',
            url: '/analytics/ajax/add-fine-manager',
            data: data,
            success: function (response) {
                console.log(response)
                document.location.reload();
            }
        })

    })

    function ajaxAddPaymentsManager(data){
        $.ajax({
            method: 'POST',
            url: '/analytics/ajax/add-payments-manager',
            data: data,
            success: function (response) {
                console.log(response)
                document.location.reload();
            }
        })
    }
</script>
<script>
    $('#btn-filter-analytics-app').click(function () {
        $('#form-filter-analytics-app').trigger('submit');
    })
</script>
</body>
