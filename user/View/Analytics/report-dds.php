<?php
/** @var App\User\Contoller\Common\HomeController $controller */
/** @var String $titlePage */
/** @var array $report */
$controller->view('Components/head');
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
                        <button class="btn btn-theme-white" type="button" data-bs-toggle="modal" data-bs-target="#filterModal">
                            <i class="bi bi-funnel-fill" ></i> Фильтры
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
.
    <section class="analytics-applications__list mb-5">
        <div class="wrapper p-2 pt-4 mb-5">
            <h3 class="text-center mb-2">Отчет ДДС за <?php echo $date; ?></h3>
            <h5 class="text-center mb-5">
                <?php if($isManagerSelect): foreach ($managerList as $manager): ?>
                    <?php foreach ($controller->request->input('id-user') as $idUser){
                            if($idUser == $manager['id']) {
                                echo '(' .$manager['surname']." ".$manager['name'] .')';
                                break;
                            }
                    } ?>
                <?php endforeach; endif; ?>

                <?php if($isRopSelect): foreach ($managerList as $manager):
                    if($controller->request->input('id-rop') == $manager['id']) {
                        echo 'Отдел - ' .$manager['surname']." ".$manager['name'];
                        break;
                    }
                 endforeach; endif; ?>
            </h5>
            <table class="table table-bordered">
                <thead>
                    <tr>

                        <th>Количество заявок</th>
                        <th>Сумма прихода</th>
                        <th>Сумма расхода</th>
                        <th>Разница</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?php // echo $report['sum']['quantity']; ?></td>
                        <td><?php echo $report['client']['sum(quantity)']; ?></td>
                        <td><?php echo $report['carrier']['sum(quantity)']; ?></td>
                        <td><?php echo $report['client']['sum(quantity)'] - $report['carrier']['sum(quantity)']; ?></td>
                    </tr>

                </tbody>
            </table>
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
                <form method="GET" style="overflow: auto; height: 75vh;max-height: 75vh" id="form-filter-analytics-app">
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
                        <label for="">Менеджер</label>
                        <select name="id-user[]" class="form-select js-chosen" id="select-manager"
                                multiple data-placeholder="Выберите менеджера">
                            <?php foreach ($managerList as $manager): ?>
                                <option value="<?php echo $manager['id']; ?>">
                                    <?php echo $manager['surname'] .' ' . $manager['name']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="">По отделам</label>
                        <select name="id-rop" class="form-select js-chosen" id="select-rop" multiple data-placeholder="Выберите РОПа">
                            <?php foreach ($ropList as $rop): ?>
                                <option value="<?php echo $rop['id']; ?>">
                                    <?php echo $rop['surname'] .' ' . $rop['name']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <div class="d-flex w-100 justify-content-between">
                    <button type="button" class="btn btn-theme btn-success" id="btn-filter-analytics-app">Применить</button>
                    <a href="/analytics/report" class="btn btn-theme btn-light">Сбросить</a>
                </div>
            </div>
        </div>
    </div>
</div>
<script>

    $('#select-rop').change(function (){
        if($(this).val() != ''){
            $('#select-manager').prop('disabled', true).trigger("chosen:updated");
        }
        else{
            $('#select-manager').prop('disabled', false).trigger("chosen:updated");
            $('#select-manager').val('');
        }
    })
    $('#btn-filter-analytics-app').click(function () {
        $('#form-filter-analytics-app').trigger('submit');
    })
</script>

</body>
