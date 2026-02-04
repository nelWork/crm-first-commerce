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
                        <button class="btn btn-primary" id="download-report-excel">Скачать Отчет</button>
                        <form action="/journal-list" method="post">
                            <textarea name="app-id" style="display:none"><?php foreach ($report['listIdApplication'] as $id) echo $id .','; ?></textarea>
                            <input type="hidden" name="name" value="Отчет P&L за <?php echo $date; ?>">
                            <input type="hidden" name="type" value="report">

                            <button type="submit" class="btn btn-success mx-4" >
                                Открыть в журнале (Экспедирование)
                            </button>
                        </form>

                        <form action="/journal-list" method="post">
                            <textarea name="app-id" style="display:none"><?php foreach ($report['listIdApplicationPRR'] as $id) echo $id .','; ?></textarea>
                            <input type="hidden" name="name" value="Отчет P&L за <?php echo $date; ?>">
                            <input type="hidden" name="type" value="prr">
                            <button type="submit" class="btn btn-success mx-4" >
                                Открыть в журнале (ПРР)
                            </button>
                        </form>
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
            <h3 class="text-center mb-2">
                P&L за <?php echo $date; ?>

            </h3>
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
            <table class="table table-bordered mb-5">
                <thead>
                    <tr>

                        <th>Количество заявок</th>
                        <th>Сумма прихода</th>
                        <th>Сумма расхода</th>
                        <th>Операционная прибыль</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?php echo $report['sum']['quantity']; ?></td>
                        <td><?php echo $report['sum']['income_amount']; ?></td>
                        <td><?php echo $report['sum']['expense_amount']; ?></td>
                        <td><?php echo $report['sum']['application_net_profit']; ?></td>
                    </tr>
                    <tr>
                        <td> <?php echo $report['applications']['COUNT(id)']; ?> (Экспедирование)</td>
                        <td><?php echo $report['applications']['SUM(transportation_cost_Client)']; ?></td>
                        <td><?php echo $report['applications']['SUM(transportation_cost_Carrier)']; ?></td>
                        <td><?php echo $report['applications']['SUM(application_net_profit)']; ?></td>
                    </tr>
                    <tr>
                        <td><?php echo $report['applicationPRR']['COUNT(id)']; ?> (ПРР)</td>
                        <td><?php echo $report['applicationPRR']['SUM(cost_Client)']; ?></td>
                        <td><?php echo $report['applicationPRR']['SUM(cost_Prr)']; ?></td>
                        <td><?php echo $report['applicationPRR']['SUM(application_net_profit)']; ?></td>
                    </tr>

                </tbody>
            </table>

            

            <table class="table table-bordered mb-5">
                <tbody>
                    <tr>
                        <td colspan="2" class="td-color-main">
                            ДОХОДЫ
                        </td>
                    </tr>
                    <tr onclick="toggleDetails('incomeApplications')" style="cursor:pointer;">
                        <td>ЭКСПЕДИРОВАНИЕ</td>
                        <td class="text-right w-25">
                            <?php echo number_format($dataPL['income']['applications']['sum'], 2,',',' '); ?>
                        </td>
                    </tr>

                    <tr id="incomeApplications" style="display:none;">
                        <td colspan="2">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>№ заявки</th>
                                            <th>Клиент</th>
                                            <th>Общая сумма</th>
                                            <th>Вид налогообложения</th>
                                            <th>Сумма Б/НДС</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($dataPL['income']['applications']['list'] as $item): ?>
                                            <tr>
                                                <td>
                                                    <a href="/application?id=<?php echo $item['id']; ?>" target="_blank">
                                                        <?php echo $item['application_number']; ?>
                                                    </a>
                                                </td>
                                                <td><?php echo $item['client']; ?></td>
                                                <td><?php echo $item['transportation_cost']; ?></td>
                                                <td><?php echo $item['taxation_type']; ?></td>
                                                <td><?php echo $item['cost_without_taxation']; ?></td>
                                            </tr>
                                        <?php endforeach ?>
                                    </tbody>
                                </table>
                                    
                        </td>
                    </tr>


                    <tr onclick="toggleDetails('incomeApplicationsPrr')" style="cursor:pointer;">
                        <td>ПРР</td>
                        <td class="text-right">
                            <?php echo number_format($dataPL['income']['applicationsPrr']['sum'], 2, ',',' '); ?>
                        </td>
                    </tr>

                    <tr id="incomeApplicationsPrr" style="display:none;">
                        <td colspan="2">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>№ заявки</th>
                                            <th>Клиент</th>
                                            <th>Общая сумма</th>
                                            <th>Вид налогообложения</th>
                                            <th>Сумма Б/НДС</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($dataPL['income']['applicationsPrr']['list'] as $item): ?>
                                            <tr>
                                                <td>
                                                    <a href="/prr/prr_application?id=<?php echo $item['id']; ?>" target="_blank">
                                                        <?php echo $item['application_number']; ?>
                                                    </a>
                                                </td>
                                                <td><?php echo $item['client']; ?></td>
                                                <td><?php echo $item['cost']; ?></td>
                                                <td><?php echo $item['taxation_type']; ?></td>
                                                <td><?php echo $item['cost_without_taxation']; ?></td>
                                            </tr>
                                        <?php endforeach ?>
                                    </tbody>
                                </table>
                                    
                        </td>
                    </tr>
                    <tr>
                        <td class="td-color-main">ВЫРУЧКА</td>
                        <td class="td-color-main text-right">
                            <?php echo number_format($dataPL['income']['applicationsPrr']['sum'] + $dataPL['income']['applications']['sum'], 2, ',',' '); ?>
                        </td>
                    </tr>
                </tbody>
            </table>


            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <td colspan="2" class="td-color-main">
                            РАСХОДЫ
                        </td>
                    </tr>
                    <tr onclick="toggleDetails('expensesApplications')" style="cursor:pointer;">
                        <td>Транспортные услуги</td>
                        <td class="text-right w-25">
                            <?php echo number_format($dataPL['expenses']['applications']['sum'], 2,',',' '); ?>
                        </td>
                    </tr>
                    
                    <tr id="expensesApplications" style="display:none;">
                        <td colspan="2">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>№ заявки</th>
                                            <th>Перевозчик</th>
                                            <th>Общая сумма</th>
                                            <th>Вид налогообложения</th>
                                            <th>Сумма Б/НДС</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($dataPL['expenses']['applications']['list'] as $item): ?>
                                            <tr>
                                                <td>
                                                    <a href="/application?id=<?php echo $item['id']; ?>" target="_blank">
                                                        <?php echo $item['application_number']; ?>
                                                    </a>
                                                </td>
                                                <td><?php echo $item['carrier']; ?></td>
                                                <td><?php echo $item['transportation_cost']; ?></td>
                                                <td><?php echo $item['taxation_type']; ?></td>
                                                <td><?php echo $item['cost_without_taxation']; ?></td>
                                            </tr>
                                        <?php endforeach ?>
                                    </tbody>
                                </table>
                                    
                        </td>
                    </tr>
                    <tr>
                        <td>Доп. расходы ПРР и прочие</td>
                        <td class="text-right">
                            <?php echo number_format($dataPL['expenses']['additionalExpenses']['sum'], 2, ',',' '); ?>
                        </td>
                    </tr>

                    <tr id="expensesAdditionalExpenses" style="display:none;">
                        <td colspan="2">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>№ заявки</th>
                                            <th>Перевозчик</th>
                                            <th>Общая сумма</th>
                                            <th>Вид налогообложения</th>
                                            <th>Сумма Б/НДС</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($dataPL['expenses']['applications']['list'] as $item): ?>
                                            <tr>
                                                <td>
                                                    <a href="/application?id=<?php echo $item['id']; ?>" target="_blank">
                                                        <?php echo $item['application_number']; ?>
                                                    </a>
                                                </td>
                                                <td><?php echo $item['carrier']; ?></td>
                                                <td><?php echo $item['transportation_cost']; ?></td>
                                                <td><?php echo $item['taxation_type']; ?></td>
                                                <td><?php echo $item['cost_without_taxation']; ?></td>
                                            </tr>
                                        <?php endforeach ?>
                                    </tbody>
                                </table>
                                    
                        </td>
                    </tr>

                    <tr>
                        <td>Бонусы</td>
                        <td class="text-right">
                            <?php echo number_format($dataPL['expenses']['bonus']['sum'], 2, ',',' '); ?>
                        </td>
                    </tr>

                    <tr>
                        <td>Страхование</td>
                        <td class="text-right">
                            <?php echo number_format($dataPL['expenses']['insurance']['sum'], 2, ',',' '); ?>
                        </td>
                    </tr>

                    <tr onclick="toggleDetails('expensesApplicationsPrr')" style="cursor:pointer;">
                        <td>Расходы ПРР</td>
                        <td class="text-right">
                            <?php echo number_format($dataPL['expenses']['applicationsPrr']['sum'], 2, ',',' '); ?>
                        </td>
                    </tr>

                    <tr id="expensesApplicationsPrr" style="display:none;">
                        <td colspan="2">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>№ заявки</th>
                                            <th>ПРР</th>
                                            <th>Общая сумма</th>
                                            <th>Вид налогообложения</th>
                                            <th>Сумма Б/НДС</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($dataPL['expenses']['applicationsPrr']['list'] as $item): ?>
                                            <tr>
                                                <td>
                                                    <a href="/prr/prr_application?id=<?php echo $item['id']; ?>" target="_blank">
                                                        <?php echo $item['application_number']; ?>
                                                    </a>
                                                </td>
                                                <td><?php echo $item['prr']; ?></td>
                                                <td><?php echo $item['cost']; ?></td>
                                                <td><?php echo $item['taxation_type']; ?></td>
                                                <td><?php echo $item['cost_without_taxation']; ?></td>
                                            </tr>
                                        <?php endforeach ?>
                                    </tbody>
                                </table>
                                    
                        </td>
                    </tr>
                    
                    <tr onclick="toggleDetails('expensesAdditionalExpensesPrr')" style="cursor:pointer;">
                        <td>Доп.затраты ПРР</td>
                        <td class="text-right">
                            <?php echo number_format($dataPL['expenses']['additionalExpensesPrr']['sum'], 2, ',',' '); ?>
                        </td>
                    </tr>

                    <tr id="expensesAdditionalExpensesPrr" style="display:none;">
                        <td colspan="2">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>№ заявки</th>
                                            <th>Вид затрат</th>
                                            <th>Общая сумма</th>
                                            <th>Вид налогообложения</th>
                                            <th>Сумма Б/НДС</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($dataPL['expenses']['additionalExpensesPrr']['list'] as $item): ?>
                                            <tr>
                                                <td>
                                                    <?php echo $item['application_number']; ?>
                                                </td>
                                                <td><?php echo $item['prr']; ?></td>
                                                <td><?php echo $item['cost']; ?></td>
                                                <td><?php echo $item['taxation_type']; ?></td>
                                                <td><?php echo $item['cost_without_taxation']; ?></td>
                                            </tr>
                                        <?php endforeach ?>
                                    </tbody>
                                </table>
                                    
                        </td>
                    </tr>

                    <tr>
                        <td colspan="2" style="height: 22px;"> </td>
                    </tr>

                    <tr>
                        <td>KPI отдела продаж</td>
                        <td class="text-right">
                            <?php echo number_format($dataPL['expenses']['salesKPI']['sum'], 2, ',',' '); ?>
                        </td>
                    </tr>
                    <tr>
                        <td>KPI логисты</td>
                        <td class="text-right">
                            <?php echo number_format($dataPL['expenses']['managersKPI']['sum'], 2, ',',' '); ?>
                        </td>
                    </tr>

                    <tr>
                        <td class="">ИТОГО</td>
                        <td class="text-right">
                            <?php echo number_format(
                            $dataPL['expenses']['managersKPI']['sum'] + 
                            $dataPL['expenses']['salesKPI']['sum'] + 
                            $dataPL['expenses']['additionalExpensesPrr']['sum'] + 
                            $dataPL['expenses']['applicationsPrr']['sum'] + 
                            $dataPL['expenses']['insurance']['sum'] + 
                            $dataPL['expenses']['bonus']['sum'] + 
                            $dataPL['expenses']['additionalExpenses']['sum'] + 
                            $dataPL['expenses']['applications']['sum'],
                            2, ',',' '); ?>
                        </td>
                    </tr>

                    <tr>
                        <td class="td-color-main">МАРЖА</td>
                        <td class="td-color-main text-right">
                            <?php echo number_format(
                                $dataPL['income']['applications']['sum'] + $dataPL['income']['applicationsPrr']['sum'] -
                                ($dataPL['expenses']['managersKPI']['sum'] + 
                                $dataPL['expenses']['salesKPI']['sum'] + 
                                $dataPL['expenses']['additionalExpensesPrr']['sum'] + 
                                $dataPL['expenses']['applicationsPrr']['sum'] + 
                                $dataPL['expenses']['insurance']['sum'] + 
                                $dataPL['expenses']['bonus']['sum'] + 
                                $dataPL['expenses']['additionalExpenses']['sum'] + 
                                $dataPL['expenses']['applications']['sum']),
                                2, ',',' '); 
                            ?>
                        </td>
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
    $('#download-report-excel').click(function(){
            $.ajax({
            url: '/analytics/ajax/download-report-excel',
            type: 'POST',
            data: {'date': '<?php echo $date;?>'},
            success: function (data) {
                console.log(data)
                download_file('P&L за <?php echo $date; ?>.xlsx', '/doc/journal.xlsx');

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
