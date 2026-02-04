<?php
/** @var App\User\Contoller\Common\HomeController $controller */
/** @var String $titlePage */
/** @var int $page */
/** @var array $salaryStatisticsArray */
$controller->view('Components/head');

$manager = $salaryStatisticsArray['managerData'];
$salaryData = $salaryStatisticsArray['salaryData'];
$listApplications = $salaryStatisticsArray['listApplications'];
$listPrrApplications = $salaryStatisticsArray['listPrrApplications'];
$listApplicationsSubordinates = $salaryStatisticsArray['listApplicationsSubordinates'] ?? [];
$paymentsManagerListData = $salaryStatisticsArray['listPaymentsManager']['list'];
$fineManagerListData = $salaryStatisticsArray['listFineManager']['list'];
$plan = $salaryStatisticsArray['typePlan'];
$planExecution = $salaryStatisticsArray['planExecution'];
$controller->extract([
    'plan' => $plan,
    'planExecution' => $planExecution,
]);
// dd($salaryStatisticsArray);
// dd($salaryStatisticsArray['managerData']['id'] == 55);

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
    .strange-application{
        background-color: #ca4635a6;
    }

</style>
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
            </div>
        </div>
    </div>


    <section class="analytics-applications__list mb-5">
        <div class="wrapper p-2 mb-5">
            <h2 class="text-center mb-4 mt-2">
                Подробная статистика по зарплате <?php echo $manager['name'] .' ' .$manager['surname']; ?>
                <div class="small">
                    (<?php echo $salaryData['date_start'] .' - ' .$salaryData['date_end'] ; ?>)
                </div>
            </h2>

            <div class="row mb-4">
                <div class="col-md-4 mb-4">
                    <div class="card" style="height: 100%;">
                        <div class="card-body">
                            <h5>Оклад:</h5>
                            <p id="fixed-salary" style="font-weight: 600">
                                <?php echo $salaryStatisticsArray['fixSalary']; ?> ₽
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5>Проценты:</h5>
                            <p style="font-weight: 600" id="total-salary">
                                Экспедирование - <?php echo $salaryStatisticsArray['sumApplicationsManagerShare']; ?> ₽
                                <br>
                                ПРР - <?php echo $salaryStatisticsArray['sumPrrApplicationsManagerShare']; ?> ₽
                                <br>
                                <?php if(isset($salaryStatisticsArray['listApplicationsSubordinates'])): ?>
                                РОП - <?php echo $salaryStatisticsArray['sumApplicationsSubordinates']['manager_share_rop']; ?> ₽
                                <?php endif; ?>
                            </p>
                            
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card" style="height: 100%;">
                        <div class="card-body">
                            <h5>Итоговая зарплата:</h5>
                            <p style="font-weight: 600" id="total-salary">
                                <?php echo $salaryStatisticsArray['salary']; ?> ₽
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5>KPI:</h5>
                            <p style="font-weight: 600" id="manager-name">
                                <?php echo $salaryStatisticsArray['KPI']; ?>
                                (<?php echo $salaryStatisticsArray['KPI_percent']; ?> %)
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5>Сумма выплат:</h5>
                            <p id="fixed-salary" style="font-weight: 600">
                                <?php echo $salaryStatisticsArray['listPaymentsManager']['sumPaymentsManager']; ?> ₽
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5>Сумма штрафов:</h5>
                            <p style="font-weight: 600" id="total-salary">
                                <?php echo $salaryStatisticsArray['listFineManager']['sumFineManager']; ?> ₽
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <h3 class="text-center mt-5 mb-4">
                Статистика по закрытым заявкам <?php if($controller->auth->user()->fullCRM()): ?>
                <span style="color:blue!important; text-decoration: underline; font-size: 14px">
                    <a href="/journal-list?app-id=<?php foreach ($listApplications as $application) echo $application['id'] .','; ?>" target="_blank"
                       class="js-open-journal" data-list-id="<?php foreach ($listApplications as $application) echo $application['id'] .','; ?>">
                    Открыть в журнале
                </a></span>
                <?php endif; ?>
            </h3>


            <table class="table table-bordered 11">
                <thead>
                    <tr>
                        <th>
                            Номер заявки
                        </th>
                        <th>
                            Номер счета
                        </th>
                        <th>Дата заявки</th>
                        <th>
                            Дата закрытия заявки
                            <i class="bi bi-question-circle" style="cursor: pointer" data-bs-toggle="tooltip" data-bs-placement="top"
                               data-bs-custom-class="custom-tooltip"
                               data-bs-title='Дата когда заявка перешла в статус "Закрытые под расчет"'></i>
                        </th>
                        <th>
                            Прибыль с заявки
                            <i class="bi bi-question-circle" style="cursor: pointer" data-bs-toggle="tooltip" data-bs-placement="top"
                                 data-bs-custom-class="custom-tooltip"
                               data-bs-title='Прибыль с заявки с учетом всех расходом'></i>
                        </th>
                        <th>
                            Прибыль с заявки <br> с вычетом налога
                            <i class="bi bi-question-circle" style="cursor: pointer" data-bs-toggle="tooltip" data-bs-placement="top"
                                data-bs-custom-class="custom-tooltip"
                                data-bs-title='Если заявка заключена на Пегас то вычитаем 14%, если на ИП то 7%'></i>
                        </th>
                        <th class="<?php if($salaryStatisticsArray['managerData']['id'] == 55) echo 'd-none'; ?>">
                            Проценты по результатам KPI
                            <i class="bi bi-question-circle" style="cursor: pointer" data-bs-toggle="tooltip" data-bs-placement="top"
                               data-bs-custom-class="custom-tooltip"
                               data-bs-title='Процент который будет получать менеджер
                               из прибыль с заявки с учетом налога (напрямую зависит от выполнения KPI) '></i>
                        </th>
                        <th>
                            Прибавка к з.п.
                            <i class="bi bi-question-circle" style="cursor: pointer" data-bs-toggle="tooltip" data-bs-placement="top"
                               data-bs-custom-class="custom-tooltip"
                               data-bs-title='Конечная сумма которая пойдет в зарплату'></i>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($listApplications as $application): ?>
                        <tr class="<?php if($application['strangeApplication']) echo 'strange-application' ?>">
                            <td>
                                <a href="/application?id=<?php echo $application['id']; ?>" target="_blank">
                                    <?php echo $application['application_number']; ?>
                                </a>
                            </td>
                            <td>
                                <?php echo $application['account_number_Client']; ?>
                            </td>
                            <td>
                                <?php echo $application['date']; ?>
                            </td>
                            <td>
                                <?php echo $application['application_closed_date']; ?>
                            </td>
                            <td>
                                <?php echo $application['application_walrus']; ?>
                                (<?php echo $application['marginality']; ?>%)
                            </td>
                            <td>
                                <?php echo $application['application_walrus_without_tax']; ?>
                            </td>
                            <td class="<?php if($salaryStatisticsArray['managerData']['id'] == 55) echo 'd-none'; ?>">
                                <?php 
                                    if($application['for_sales'] == 0)
                                        echo $application['percent_manager']; 
                                    else
                                        echo $application['percent_sales_client'];?>
                                    %
                            </td>
                            <td>
                                <?php echo $application['manager_share']; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <tr style="background-color: darkorange; font-weight: 600">
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td><?php echo $salaryStatisticsArray['sumApplicationsData']['application_walrus']; ?></td>
                    <td><?php echo $salaryStatisticsArray['sumApplicationsData']['application_walrus_without_tax']; ?></td>
                    <td class="<?php if($salaryStatisticsArray['managerData']['id'] == 55) echo 'd-none'; ?>"></td>
                    <td><?php echo $salaryStatisticsArray['sumApplicationsData']['manager_share']; ?></td>
                </tr>
                </tbody>
            </table>

            <?php if($salaryStatisticsArray['managerData']['id'] == 55): ?>
                <h3 class="text-center mt-5 mb-4">Статистика по заявкам новых клиентов</h3>
                <table class="table table-bordered 11">
                <thead>
                    <tr>
                        <th>
                            Номер заявки
                        </th>
                        <th>
                            Номер счета
                        </th>
                        <th>Дата заявки</th>
                        <th>
                            Клиент
                        </th>
                        <th>
                            Прибавка к з.п.
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($salaryStatisticsArray['applications_new_client_sales'] as $application): ?>
                        <tr class="">
                            <td>
                                <a href="/application?id=<?php echo $application['id']; ?>" target="_blank">
                                    <?php echo $application['application_number']; ?>
                                </a>
                            </td>
                            <td>
                                <?php echo $application['account_number_Client']; ?>
                            </td>
                            <td>
                                <?php echo date('d.m.Y',strtotime($application['date'])); ?>
                            </td>
                            <td>
                                <a href="/client?id=<?php echo $application['client']['id']; ?>" target="_blank">
                                    <?php echo $application['client']['name']; ?>
                                </a>
                            </td>
                            <td>
                                5 000 ₽
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <tr style="background-color: darkorange; font-weight: 600">
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>
                        <?php echo number_format(count($salaryStatisticsArray['applications_new_client_sales']) * 5000,0,'',' '); ?> ₽
                    </td>
                </tr>
                </tbody>
            </table>
            <?php endif; ?>
            <?php if(isset($salaryStatisticsArray['listApplicationsSubordinates'])): ?>
            <h3 class="text-center mt-5 mb-4">Статистика по закрытым заявкам подопечных (Для РОП)</h3>

            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>
                        Имя логиста
                    </th>
                    <th>
                        Номер заявки
                    </th>
                    <th>
                        Номер счета
                    </th>
                    <th>Дата заявки</th>
                    <th>
                        Дата закрытия заявки
                        <i class="bi bi-question-circle" style="cursor: pointer" data-bs-toggle="tooltip" data-bs-placement="top"
                           data-bs-custom-class="custom-tooltip"
                           data-bs-title='Дата когда заявка перешла в статус "Закрытые под расчет"'></i>
                    </th>
                    <th>
                        Прибыль с заявки
                        <i class="bi bi-question-circle" style="cursor: pointer" data-bs-toggle="tooltip" data-bs-placement="top"
                           data-bs-custom-class="custom-tooltip"
                           data-bs-title='Прибыль с заявки с учетом всех расходом'></i>
                    </th>
                    <th>
                        Прибыль с заявки <br> с вычетом налога
                        <i class="bi bi-question-circle" style="cursor: pointer" data-bs-toggle="tooltip" data-bs-placement="top"
                           data-bs-custom-class="custom-tooltip"
                           data-bs-title='Если заявка заключена на ООО то вычитаем 14%, если на ИП то 7%'></i>
                    </th>
                    <th >
                        Проценты по результатам KPI
                        <i class="bi bi-question-circle" style="cursor: pointer" data-bs-toggle="tooltip" data-bs-placement="top"
                           data-bs-custom-class="custom-tooltip"
                           data-bs-title='Процент который будет получать менеджер
                               из прибыль с заявки с учетом налога (напрямую зависит от выполнения KPI) '></i>
                    </th>
                    <th>
                        Прибавка к з.п.
                        <i class="bi bi-question-circle" style="cursor: pointer" data-bs-toggle="tooltip" data-bs-placement="top"
                           data-bs-custom-class="custom-tooltip"
                           data-bs-title='Конечная сумма которая пойдет в зарплату'></i>
                    </th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($listApplicationsSubordinates as $application): ?>
                    <tr>
                        <td>
                            <?php echo $application['fio_subordinates']; ?>
                        </td>
                        <td>
                            <a href="/application?id=<?php echo $application['id']; ?>" target="_blank">
                                <?php echo $application['application_number']; ?>
                            </a>
                        </td>
                        <td>
                            <?php echo $application['account_number_Client']; ?>
                        </td>
                        <td>
                            <?php echo $application['date']; ?>
                        </td>
                        <td>
                            <?php echo $application['application_closed_date']; ?>
                        </td>
                        <td>
                            <?php echo $application['application_walrus']; ?>
                            (<?php echo $application['marginality']; ?>%)
                        </td>
                        <td>
                            <?php echo $application['application_walrus_without_tax']; ?>
                        </td>
                        <td>
                            <?php echo $application['percent_manager']; ?>%
                        </td>
                        <td>
                            <?php echo $application['manager_share_rop']; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <tr style="background-color: darkorange; font-weight: 600">
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td><?php echo $salaryStatisticsArray['sumApplicationsSubordinates']['application_walrus']; ?></td>
                    <td><?php echo $salaryStatisticsArray['sumApplicationsSubordinates']['application_walrus_without_tax']; ?></td>
                    <td></td>
                    <td><?php echo $salaryStatisticsArray['sumApplicationsSubordinates']['manager_share_rop']; ?></td>
                </tr>
                </tbody>
            </table>
            <?php endif; ?>


            <h3 class="text-center mt-5 mb-4">
                Статистика по закрытым ПРР заявкам
            </h3>


            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>
                        Номер заявки
                    </th>
                    <th>
                        Номер счета
                    </th>
                    <th>Дата заявки</th>
                    <th>
                        Дата закрытия заявки
                        <i class="bi bi-question-circle" style="cursor: pointer" data-bs-toggle="tooltip" data-bs-placement="top"
                           data-bs-custom-class="custom-tooltip"
                           data-bs-title='Дата когда заявка перешла в статус "Закрытые под расчет"'></i>
                    </th>
                    <th>
                        Прибыль с заявки
                        <i class="bi bi-question-circle" style="cursor: pointer" data-bs-toggle="tooltip" data-bs-placement="top"
                           data-bs-custom-class="custom-tooltip"
                           data-bs-title='Прибыль с заявки с учетом всех расходом'></i>
                    </th>
                    <th>
                        Прибыль с заявки <br> с вычетом налога
                        <i class="bi bi-question-circle" style="cursor: pointer" data-bs-toggle="tooltip" data-bs-placement="top"
                           data-bs-custom-class="custom-tooltip"
                           data-bs-title='Если заявка заключена на Пегас то вычитаем 14%, если на ИП то 7%'></i>
                    </th>
                    <th>
                        Проценты по результатам KPI
                        <i class="bi bi-question-circle" style="cursor: pointer" data-bs-toggle="tooltip" data-bs-placement="top"
                           data-bs-custom-class="custom-tooltip"
                           data-bs-title='Процент который будет получать менеджер
                               из прибыль с заявки с учетом налога (напрямую зависит от выполнения KPI) '></i>
                    </th>
                    <th>
                        Прибавка к з.п.
                        <i class="bi bi-question-circle" style="cursor: pointer" data-bs-toggle="tooltip" data-bs-placement="top"
                           data-bs-custom-class="custom-tooltip"
                           data-bs-title='Конечная сумма которая пойдет в зарплату'></i>
                    </th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($listPrrApplications as $application): ?>
                    <tr class="<?php if($application['strangeApplication']) echo 'strange-application' ?>">
                        <td>
                            <a href="/prr/prr_application?id=<?php echo $application['id']; ?>" target="_blank">
                                <?php echo $application['application_number']; ?>
                            </a>
                        </td>
                        <td>
                            <?php echo $application['account_number_Client']; ?>
                        </td>
                        <td>
                            <?php echo $application['date']; ?>
                        </td>
                        <td>
                            <?php echo $application['application_closed_date']; ?>
                        </td>
                        <td>
                            <?php echo $application['application_walrus']; ?>
                            (<?php echo $application['marginality']; ?>%)
                        </td>
                        <td>
                            <?php echo $application['application_walrus_without_tax']; ?>
                        </td>
                        <td>
                            <?php echo $application['percent_manager']; ?>%
                        </td>
                        <td>
                            <?php echo $application['manager_share']; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <tr style="background-color: darkorange; font-weight: 600">
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td><?php echo $salaryStatisticsArray['sumPrrApplicationsData']['application_walrus']; ?></td>
                    <td><?php echo $salaryStatisticsArray['sumPrrApplicationsData']['application_walrus_without_tax']; ?></td>
                    <td></td>
                    <td><?php echo $salaryStatisticsArray['sumPrrApplicationsData']['manager_share']; ?></td>
                </tr>
                </tbody>
            </table>

            <h3 class="text-center mt-5 mb-4">Статистика по выплатам</h3>

            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>Тип выплат</th>
                    <th>Сумма</th>
                    <th>Дата</th>
                </tr>
                </thead>
                <tbody>
                    <?php foreach ($paymentsManagerListData as $paymentManager): ?>
                        <tr>
                            <td>
                                <?php
                                    switch ($paymentManager['type']):
                                        case 0:
                                            echo 'Аванс';
                                            break;
                                        case 1:
                                            echo "Офиц. 5 числа";
                                            break;
                                        case 2:
                                            echo 'Офиц. 20 числа';
                                             break;

                                    endswitch;
                                ?>
                            </td>
                            <td>
                                <?php echo $paymentManager['quantity']; ?>
                            </td>
                            <td>
                                <?php echo $paymentManager['date_create']; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>


            <h3 class="text-center mt-5 mb-4">Статистика по штрафам</h3>

            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>Описание</th>
                    <th>Сумма</th>
                    <th>Дата</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($fineManagerListData as $fineManager): ?>
                    <tr>
                        <td>
                            <?php echo $fineManager['description']; ?>
                        </td>
                        <td>
                            <?php echo $paymentManager['quantity']; ?>
                        </td>
                        <td>
                            <?php echo $paymentManager['date_create']; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>

                <?php if(empty($fineManagerListData)): ?>
                    <tr >
                        <td colspan="3">
                            <h5 class="text-center mb-0">В этом месяце ты молодец. У тебя нет штрафов</h5>
                        </td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>

            <?php if($plan['id'] < 3 AND $planExecution): ?>
                <?php $controller->view('Components/chartKPI'); ?>

            <?php endif; ?>

            <div class="my-5">
                <h3 class="text-center mb-4">Статистика по заявкам KPI</h3>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Номер заявки</th>
                            <th>Дата заявки</th>
                            <th>Прибыль с заявки</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($salaryStatisticsArray['applicationListKPI']['list'] as $application): ?>
                        <tr>
                            <td>
                                <a href="/application?id=<?php echo $application['id']; ?>" target="_blank">
                                    <?php echo $application['application_number']; ?>
                                </a>
                            </td>
                            <td>
                                <?php echo $application['date']; ?>
                            </td>
                            <td>
                                <?php echo $application['application_walrus']; ?>
                                (<?php echo $application['marginality']; ?>%)
                            </td>
                        </tr>
                    <?php endforeach; ?>
                        <tr style="background-color: darkorange; font-weight: 600">
                            <td></td>
                            <td></td>
                            <td><?php echo $salaryStatisticsArray['applicationListKPI']['sum']; ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </section>

</main>
<script>
    $('.js-open-journal').click(function (){

    })
</script>

<script>
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
</script>

</body>
