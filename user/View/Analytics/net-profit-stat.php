<?php

/** @var App\User\Contoller\Common\HomeController $controller */
/** @var String $titlePage */
$controller->view('Components/head');
// dd($statistics['managerList']);

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
                            <form action="/journal-list" method="post">
                                <textarea name="app-id" style="display:none"><?php echo $netProfitStat['textId']; ?></textarea>
                                <input type="hidden" name="type" value="report">
                                <input type="hidden" name="name" value="ДДC за <?php echo $date; ?>">
                                <button type="submit" class="btn btn-success mx-4">
                                    Открыть в журнале
                                </button>
                            </form>
                            <input type="text" name="period" id="date-picker" class="form-control w-50" value="<?php echo $date; ?>">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <section class="analytics-applications__list mb-5">
            <div class="wrapper p-2 mb-5" style="min-height: 400px">
                <h2 class="text-center my-4">
                    Данные за <?php echo $date; ?>
                </h2>

                <div class="row m-0" style="font-size: 1.125rem">
                    <div class="col">
                        <label class="mb-2 fw-bold">Фактическая оплата от клиентов</label>
                        <input
                            type="text"
                            class="form-control"
                            id="actual"
                            value="<?php echo number_format($netProfitStat['sumActualPayment'], 0, '', ' '); ?>"
                            style="font-weight: bold; font-size: 1.125rem"
                            disabled>
                    </div>
                    <div class="col">
                        
                    <label class="mb-2 fw-bold">Чистая прибыль (без учета ЗП)</label>
                        <input
                            type="text"
                            class="form-control mb-4"
                            id="profit"
                            value="<?php echo number_format($netProfitStat['sumNetProfit'], 0, '', ' '); ?>"
                            style="font-weight: bold; font-size: 1.125rem"
                            disabled>

                            <label class="mb-2 fw-bold">Зарплатный фонд</label>
                        <input
                            type="text"
                            class="form-control"
                            id="salary"
                            value="<?php echo number_format($netProfitStat['sumFixSalary'], 0, '', ' '); ?>"
                            style="font-weight: bold; font-size: 1.125rem"
                            disabled>
                    </div>
                    
                    <div class="col">
                        <label class="mb-2 fw-bold">Чистая прибыль</label>
                        <input
                            type="text"
                            class="form-control"
                            id="result"
                            value="<?php echo number_format($netProfitStat['sumResult'], 0, '', ' '); ?>"
                            style="font-weight: bold; font-size: 1.125rem"
                            disabled>
                    </div>
                    <div class="col">
                        <label class="mb-2 fw-bold">Затраты</label>
                        <input
                            type="text"
                            class="form-control"
                            id="expenses"
                            value="0"
                            style="font-weight: bold; font-size: 1.125rem">
                    </div>
                </div>

                <h4 class="text-center mt-5">
                    Итоговая чистая прибыль:
                    <span id="result">
                        <b><?php echo number_format($netProfitStat['sumResult'], 0, '', ' '); ?></b>
                    </span> ₽
                </h4>
            </div>
        </section>

        <script>
            $(document).ready(function() {

                function formatNumber(num) {
                    num = num.toString().replace(/\s+/g, '');
                    if (num === '' || isNaN(num)) return '0';
                    return num.replace(/\B(?=(\d{3})+(?!\d))/g, ' ');
                }

                function parseNumber(num) {
                    num = num.toString().replace(/\s+/g, '');
                    return parseFloat(num) || 0;
                }

                function updateResult() {
                    const profit = parseNumber($('#result').val());
                    const expenses = parseNumber($('#expenses').val());
                    const result = profit - expenses;
                    $('#result').html('<b>' + formatNumber(result) + '</b>');
                }

                const $expenses = $('#expenses');

                $expenses.on('focus', function() {
                    // если значение ровно "0", очищаем поле для удобства ввода
                    if ($(this).val().trim() === '0') {
                        $(this).val('');
                    }
                });

                $expenses.on('blur', function() {
                    // если пользователь ничего не ввёл — возвращаем 0
                    if ($(this).val().trim() === '') {
                        $(this).val('0');
                        updateResult();
                    }
                });

                $expenses.on('input', function() {
                    let value = $(this).val().replace(/\s+/g, '');
                    if (value === '') value = '0';
                    // форматируем только если не 0
                    if (value !== '0') {
                        $(this).val(formatNumber(value));
                    } else {
                        $(this).val('0');
                    }
                    updateResult();
                });

                // первичный пересчет при загрузке
                updateResult();

                new AirDatepicker('#date-picker', {
                    range: true,
                    multipleDatesSeparator: ' - ',
                    buttons: ['clear'],
                    onSelect({ formattedDate }) {
                        if (formattedDate && formattedDate.length === 2) {
                            const period = formattedDate.join(' - ');
                            window.location.href = '/analytics/net-profit-stat?date=' + encodeURIComponent(period);
                        }
                    }
                });

            });
        </script>



</body>