<?php
/** @var App\User\Contoller\Common\HomeController $controller */
/** @var String $titlePage */
/** @var array $report */
$controller->view('Components/head');

// dd($dataPL);
?>

<body>
<?php $controller->view('Components/header'); ?>
<style>
    .td-color-main{
        background-color: #efa75263!important;
        font-weight: bold;
    }
</style>
<style>
    .nav-pills .nav-link{
        color: #0d6efd;
    }
    
</style>
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
                        <button class="btn btn-primary" id="save-debrot-credirot-excel">
                            Скачать Отчет
                        </button>
                        <form action="/journal-list" method="post">
                            <textarea name="app-id" style="display:none"><?php echo $data['debtor']['id_text_list'] .',' .$data['creditor']['id_text_list']; ?></textarea>
                            <textarea name="prr-app-id" style="display:none"><?php echo $data['debtor']['id_prr_text_list'] .',' .$data['creditor']['id_prr_text_list']; ?></textarea>
                            <input type="hidden" name="name" value="Дебиторка и Кредиторка">
                                <!-- <input type="hidden" name="type" value="report"> -->

                            <button type="submit" class="btn btn-success mx-4" >
                                Открыть в журнале (Полную таблицу)
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="analytics-applications__list mb-5">
        <div class="wrapper p-2 pt-4 mb-5">
            <h3 class="text-center mb-2">
                <?php echo $titlePage; ?>

            </h3>
            <ul class="nav nav-pills mb-5 justify-content-center w-100" id="tabMenu" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="debit-tab" data-bs-toggle="tab" data-bs-target="#debit" type="button" role="tab" aria-controls="debit" aria-selected="true">Дебит</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="credit-tab" data-bs-toggle="tab" data-bs-target="#credit" type="button" role="tab" aria-controls="credit" aria-selected="false">Кредит</button>
                </li>
            </ul>
            <div class="tab-content" id="tabContent">   
            <div class="tab-pane fade show active" id="debit" role="tabpanel" aria-labelledby="debit-tab">
                <div class="table-container">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Наименование клиента</th>
                                <th>Кол-во заявок</th>
                                <th>Сумма</th>
                                <th style="width: 17%"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="bg-warning">
                                <td>Итого</td>
                                <td><?php echo $data['debtor']['quantityApplication']; ?></td>
                                <td><?php echo number_format($data['debtor']['sum'],2,',',' '); ?></td>
                                <td>
                                    <form action="/journal-list" method="post">
                                        <textarea name="app-id" style="display:none"><?php echo $data['debtor']['id_text_list']; ?></textarea>
                                        <textarea name="prr-app-id" style="display:none"><?php echo $data['debtor']['id_prr_text_list']; ?></textarea>
                                        <input type="hidden" name="name" value="Дебиторка">
                                        <!-- <input type="hidden" name="type" value="report"> -->

                                        <button type="submit" class="btn btn-success mx-4" >
                                            Открыть в журнале
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            <?php foreach($data['debtor']['list'] as $nameCustomer => $customer): ?>
                            <tr>
                                <td>
                                    <?php echo $nameCustomer; ?>
                                </td>
                                <td>
                                    <?php echo count($customer['items']); ?>
                                </td>
                                <td>
                                    <?php echo number_format($customer['sum'], 2, ',', ' '); ?>
                                </td>
                                <td>
                                    <form action="/journal-list" method="post">
                                        <textarea name="app-id" style="display:none"><?php echo $customer['id_text_list']; ?></textarea>
                                        <textarea name="prr-app-id" style="display:none"><?php echo $customer['id_prr_text_list']; ?></textarea>
                                        <input type="hidden" name="name" value="Дебиторка - <?php echo $nameCustomer; ?>">
                                        <!-- <input type="hidden" name="type" value="report"> -->

                                        <button type="submit" class="btn btn-success mx-4" >
                                            Открыть в журнале
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="tab-pane fade" id="credit" role="tabpanel" aria-labelledby="credit-tab">
                <div class="table-container"><table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Наименование перевозчика</th>
                            <th>Кол-во заявок</th>
                            <th>Сумма</th>
                            <th style="width: 17%"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="bg-warning">
                            <td>Итого</td>
                            <td><?php echo $data['creditor']['quantityApplication']; ?></td>
                            <td><?php echo number_format($data['creditor']['sum'], 2,',',' '); ?></td>
                            <td>
                                <form action="/journal-list" method="post">
                                    <textarea name="app-id" style="display:none"><?php echo $data['creditor']['id_text_list']; ?></textarea>
                                    <textarea name="prr-app-id" style="display:none"><?php echo $data['creditor']['id_prr_text_list']; ?></textarea>
                                    <input type="hidden" name="name" value="Кредиторка">
                                    <!-- <input type="hidden" name="type" value="report"> -->

                                    <button type="submit" class="btn btn-success mx-4" >
                                        Открыть в журнале
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php foreach($data['creditor']['list'] as $nameCustomer => $customer): ?>
                        <tr>
                            <td>
                                <?php echo $nameCustomer; ?>
                            </td>
                            <td>
                                <?php echo count($customer['items']); ?>
                            </td>
                            <td>
                                <?php echo number_format($customer['sum'], 2, ',', ' '); ?>
                            </td>
                            <td>
                                <form action="/journal-list" method="post">
                                    <textarea name="app-id" style="display:none"><?php echo $customer['id_text_list']; ?></textarea>
                                    <textarea name="prr-app-id" style="display:none"><?php echo $customer['id_prr_text_list']; ?></textarea>
                                    <input type="hidden" name="name" value="Кредиторка - <?php echo $nameCustomer; ?>">
                                    <!-- <input type="hidden" name="type" value="report"> -->

                                    <button type="submit" class="btn btn-success mx-4" >
                                        Открыть в журнале
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                </div>
            </div>
            </div>
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


                    <div class="mb-4">
                        <label for="">По юр.лицу</label>
                        <select name="customer" class="form-select">
                            <option value="">По умолчанию</option>
                            <option value="1" <?php if(isset($condition['customer']) AND $condition['customer'] == 1) echo 'selected'; ?>>ООО Либеро Логистика</option>
                            <option value="2" <?php if(isset($condition['customer']) AND $condition['customer'] == 2) echo 'selected'; ?>>ИП Беспутин Семён Валерьевич</option>
                            <option value="3" <?php if(isset($condition['customer']) AND $condition['customer'] == 3) echo 'selected'; ?>>ИП Часовников Александр Вадимович</option>
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
    $('#save-debrot-credirot-excel').click(function(){
            $.ajax({
            url: '/analytics/ajax/download-debtor-creditor-report-excel',
            type: 'POST',
            success: function (data) {
                console.log(data)
                download_file('Дебиторка и кредиторка за <?php echo date('d-m-Y'); ?>.xlsx', '/doc/journal.xlsx');
            }
        });
    });
    function toggleDetails(id) {
        let el = document.getElementById(id);
        el.style.display = el.style.display === "none" ? "" : "none";
    }
</script>
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
