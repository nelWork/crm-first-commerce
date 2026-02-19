<?php
/** @var App\User\Contoller\Common\JournalController $controller */
/** @var String $titlePage */
/** @var String $link */
/** @var array $listApplication */
/** @var array $condition */
/** @var array $listClients */
/** @var array $listManager */
/** @var array $listCarriers */
/** @var bool $fullCRMAccess */

/** @var array $listHistoryPaymentCarrier */
/** @var array $listHistoryPaymentClient */

/** @var float $sumClient */
/** @var float $sumCarrier */


//dd($listHistoryPaymentClient[0]);
//dd($listApplication[0]);
// dd($uniqueData);


$uniqueClientNumber = $controller->pluckUniqueSorted($listHistoryPaymentClient,'application_data.application_number');
$uniqueClientAccountNumber = $controller->pluckUniqueSorted($listHistoryPaymentClient,'application_data.account_number_Client');
$uniqueClientQuantity = $controller->pluckUniqueSorted($listHistoryPaymentClient,'quantity');

$uniqueCarrierNumber = $controller->pluckUniqueSorted($listHistoryPaymentCarrier,'application_data.application_number');
$uniqueCarrierQuantity = $controller->pluckUniqueSorted($listHistoryPaymentCarrier,'quantity');

$uniqueClientName = $controller->pluckUniqueSorted($listHistoryPaymentClient,'application_data.client');
$uniqueCarrierName = $controller->pluckUniqueSorted($listHistoryPaymentCarrier,'application_data.carrier');
// dd($listHistoryPaymentCarrier[0]);

//dd($uniqueCarrierName);

//dd($uniqueClientAccountNumber);
$controller->view('Components/head');


// dd($listHistoryPaymentCarrier);
?>

<body>
<?php $controller->view('Components/header'); ?>

<style>
    .nav-pills .nav-link{
        color: #0d6efd;
    }

</style>

<main class="journal container-fluid p-4">

    <h1 class="text-center mb-5"><?php echo $titlePage; ?>
            <a class="text-primary h3" href="/journal/parse-txt">
                / Журнал выписка
            </a>
            
    </h1>

    <ul class="nav nav-pills mb-5 justify-content-center w-100" id="tabMenu" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="debit-tab" data-bs-toggle="tab" data-bs-target="#debit" type="button" role="tab" aria-controls="debit" aria-selected="true">Дебет</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="credit-tab" data-bs-toggle="tab" data-bs-target="#credit" type="button" role="tab" aria-controls="credit" aria-selected="false">Кредит</button>
        </li>
    </ul>

    <form method="GET" class="mb-4 text-left">
        <label for="" class="mb-2" style="font-weight: bold">Выберите дату:</label>
            <!-- Поле выбора даты -->
            <input
                    type="text"
                    name="period"
                    id="date-picker"
                    value="<?php echo $period; ?>"
                    class="form-control"
                    placeholder="Выберите период">
    </form>

    <script type="module">
        let button = {
            content: 'Применить',
            className: 'custom-apply-button',
            onClick: (dp) => {
                $('#date-picker').trigger('change');
            }
        };

        new AirDatepicker('#date-picker', {
            range: true,
            multipleDatesSeparator: ' - ',
            buttons: ['clear', button],
        });
    </script>

    <style>
        .js-head-filter{
            color: #0d6efd;
            cursor: pointer;
            font-size: 1.5rem;
        }
        .js-head-filter.active{
            color: #055305;
        }
        .menu-filter{
            padding: 10px;
            position: absolute;
            display: none;
            z-index: 100;
            background-color: white;
            margin-left: -6px;
            margin-top: 6px;
            max-height: 300px;
            /*min-height: 300px;*/
            overflow: auto;
            min-width: 300px;
        }
        .container-checkbox{
            width: 100%;
            max-height: 200px;
            overflow: auto;
        }
        .menu-filter.active {
            display: block;
        }
        .js-tr-application.active{
            background-color: yellow;
        }
        thead th {
            position: sticky;
            top: 0;
            background-color: white !important;
            z-index: 10;
        }
        .table-container {
            width: 100%;
            max-width: 100%;
            max-height: 65vh;
            min-height: 65vh;
            overflow: auto;
        }
    </style>

    <!-- Вкладки с таблицами -->
    <div class="tab-content" id="tabContent">
        <!-- Таблица Дебит -->
        <div class="tab-pane fade show active" id="debit" role="tabpanel" aria-labelledby="debit-tab">
            <div class="table-container">
                <table class="table table-bordered">
                <thead>
                <tr>
                    <th>
                        Номер заявки
                        <span class="js-head-filter">
                            <i class="bi bi-funnel-fill"></i>
                        </span>
                        <div class="menu-filter">
                            <input type="text" class="form-control form-control-sm js-search-in-filter mb-4" placeholder="Поиск по номеру">
                            <div class="container-checkbox">
                            <?php foreach ($uniqueClientNumber as $item): ?>
                                <div class="form-check">
                                    <input class="form-check-input js-filter-checkbox" type="checkbox" data-type="0"
                                           data-name-col="number"
                                           value="<?php echo $item; ?>"
                                           id="checkbox-number-<?php echo $item; ?>">
                                    <label class="form-check-label"
                                           for="checkbox-number-<?php echo $item; ?>">
                                        <?php echo $item; ?>
                                    </label>
                                </div>

                            <?php endforeach; ?>
                            </div>
                            <button class="btn btn-outline-danger btn-sm w-100 mt-2 js-reset-filter">Сбросить фильтр</button>
                        </div>
                    </th>
                    <th>
                        Юр. лицо
                        <span class="js-head-filter">
                            <i class="bi bi-funnel-fill"></i>
                        </span>
                        <div class="menu-filter">
                                <div class="form-check">
                                    <input class="form-check-input js-filter-checkbox" type="checkbox" data-type="0"
                                           data-name-col="customer"
                                           value="1"
                                           id="checkbox-customer-1">
                                    <label class="form-check-label"
                                           for="checkbox-customer-1">
                                        ООО Логистика
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input js-filter-checkbox" type="checkbox" data-type="0"
                                           data-name-col="customer"
                                           value="2"
                                           id="checkbox-customer-1">
                                    <label class="form-check-label"
                                           for="checkbox-customer-1">
                                        ИП Иванов Иван Иванович
                                    </label>
                                </div>
                            
                            </div>
                        </div>
                    </th>
                    <th>
                        Клиент
                        <span class="js-head-filter">
                            <i class="bi bi-funnel-fill"></i>
                        </span>
                        <div class="menu-filter">
                            <?php $cnt = 0; foreach($uniqueClientName as $name): ?>
                            <div class="form-check">
                                <input class="form-check-input js-filter-checkbox" type="checkbox" data-type="0"
                                    data-name-col="client"
                                    value="<?php echo $name; ?>"
                                    id="checkbox-client-<?php echo $cnt; ?>">
                                <label class="form-check-label" for="checkbox-client-<?php echo $cnt; ?>">
                                    <?php echo $name; ?>
                                </label>
                            </div>
                            <?php $cnt++; endforeach; ?>
                        </div>
                    </th>
                    <th>
                        Номер счета
                        <span class="js-head-filter">
                            <i class="bi bi-funnel-fill"></i>
                        </span>
                        <div class="menu-filter">
                            <input type="text" class="form-control form-control-sm js-search-in-filter mb-4" placeholder="Поиск по номеру счета">
                            <div class="container-checkbox">
                            <?php foreach ($uniqueClientAccountNumber as $item): ?>
                                <div class="form-check">
                                    <input class="form-check-input js-filter-checkbox" type="checkbox" data-type="0"
                                           data-name-col="account-number"
                                           value="<?php echo $item; ?>"
                                           id="checkbox-account-number-<?php echo $item; ?>">
                                    <label class="form-check-label"
                                           for="checkbox-account-number-<?php echo $item; ?>">
                                        <?php echo $item; ?>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                            </div>
                            <button class="btn btn-outline-danger btn-sm w-100 mt-2 js-reset-filter">Сбросить фильтр</button>
                        </div>
                    </th>
                    <th>Дата оплаты</th>
                    <th>
                        Сумма оплаты
                        <span class="js-head-filter">
                            <i class="bi bi-funnel-fill"></i>
                        </span>
                        <div class="menu-filter">
                            <input type="text" class="form-control form-control-sm js-search-in-filter mb-4" placeholder="Поиск по сумме">
                            <div class="container-checkbox">
                            <?php foreach ($uniqueClientQuantity as $item): ?>
                                <div class="form-check">
                                    <input class="form-check-input js-filter-checkbox" type="checkbox" data-type="0"
                                           data-name-col="quantity"
                                           value="<?php echo $item; ?>"
                                           id="checkbox-quantity-<?php echo $item; ?>">
                                    <label class="form-check-label"
                                           for="checkbox-quantity-<?php echo $item; ?>">
                                        <?php echo $item; ?>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                            </div>
                            <button class="btn btn-outline-danger btn-sm w-100 mt-2 js-reset-filter">Сбросить фильтр</button>
                        </div>

                    </th>
                    <th>
                        Вид налогообложения
                    </th>
                </tr>
                </thead>
                <tbody>
                <tr style="background-color: orange;font-weight: bold;">
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td id="debit-sum"><?php echo number_format($sumClient,2,',',' '); ?></td>
                    <td></td>

                </tr>
                <?php foreach ($listHistoryPaymentClient as $item): ?>
                    <tr class="js-tr-application" data-number="<?php echo $item['application_data']['application_number']; ?>"
                        data-customer="<?php echo $item['application_data']['customer_id_Client']; ?>"
                        data-account-number="<?php echo $item['application_data']['account_number_Client']; ?>"
                        data-quantity="<?php echo $item['quantity']; ?>"
                        data-client="<?php echo $item['application_data']['client']; ?>"
                        >
                        <td><a target="_blank" href="/journal-list?app-id=<?php echo $item['id_application']; ?>">
                                <?php echo $item['application_data']['application_number']; ?>
                            </a></td>
                        <td><?php echo $item['application_data']['customer_Client']; ?></td>
                        <td><?php echo $item['application_data']['client']; ?></td>
                        <td><?php echo $item['application_data']['account_number_Client']; ?></td>
                        <td><?php echo date('d.m.Y', strtotime($item['date'])); ?></td>
                        <td><?php echo number_format($item['quantity'], 2, ',', ' '); ?></td>
                        <td><?php echo $item['application_data']['taxation_type_Client']; ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            </div>
        </div>

        <!-- Таблица Кредит -->
        <div class="tab-pane fade" id="credit" role="tabpanel" aria-labelledby="credit-tab">
            <div class="table-container">
                <table class="table table-bordered">
                <thead>
                <tr>
                    <th>
                        Номер заявки
                        <span class="js-head-filter">
                            <i class="bi bi-funnel-fill"></i>
                        </span>
                        <div class="menu-filter">
                            <input type="text" class="form-control form-control-sm js-search-in-filter mb-4" placeholder="Поиск по номеру">
                            <div class="container-checkbox">
                                <?php foreach ($uniqueCarrierNumber as $item): ?>
                                    <div class="form-check">
                                        <input class="form-check-input js-filter-checkbox" type="checkbox" data-type="0"
                                               data-name-col="number-carrier"
                                               value="<?php echo $item; ?>"
                                               id="checkbox-number-carrier-<?php echo $item; ?>">
                                        <label class="form-check-label"
                                               for="checkbox-number-carrier-<?php echo $item; ?>">
                                            <?php echo $item; ?>
                                        </label>
                                    </div>

                                <?php endforeach; ?>
                            </div>
                            <button class="btn btn-outline-danger btn-sm w-100 mt-2 js-reset-filter">Сбросить фильтр</button>
                        </div>
                    </th>
                    <th>
                        Юр. лицо
                        <span class="js-head-filter">
                            <i class="bi bi-funnel-fill"></i>
                        </span>
                        <div class="menu-filter">
                            <div class="form-check">
                                <input class="form-check-input js-filter-checkbox" type="checkbox" data-type="0"
                                       data-name-col="customer-carrier"
                                       value="1"
                                       id="checkbox-customer-carrier-1">
                                <label class="form-check-label"
                                       for="checkbox-customer-carrier-1">
                                    ООО Логистика
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input js-filter-checkbox" type="checkbox" data-type="0"
                                       data-name-col="customer-carrier"
                                       value="4"
                                       id="checkbox-customer-carrier-4">
                                <label class="form-check-label"
                                       for="checkbox-customer-carrier-4">
                                    ИП Иванов Иван Иванович
                                </label>
                            </div>
                            
                        </div>
                    </th>
                    <th>
                        Перевозчик
                        <span class="js-head-filter">
                            <i class="bi bi-funnel-fill"></i>
                        </span>
                        <div class="menu-filter">
                            <?php $cnt = 0; foreach($uniqueCarrierName as $name): ?>
                            <div class="form-check">
                                <input class="form-check-input js-filter-checkbox" type="checkbox" data-type="0"
                                    data-name-col="carrier"
                                    value="<?php echo $name; ?>"
                                    id="checkbox-carrier-<?php echo $cnt; ?>">
                                <label class="form-check-label" for="checkbox-carrier-<?php echo $cnt; ?>">
                                    <?php echo $name; ?>
                                </label>
                            </div>
                            <?php $cnt++; endforeach; ?>
                        </div>
                    </th>
                    <th>Дата оплаты</th>
                    <th>
                        Сумма оплаты
                        <span class="js-head-filter">
                            <i class="bi bi-funnel-fill"></i>
                        </span>
                        <div class="menu-filter">
                            <input type="text" class="form-control form-control-sm js-search-in-filter mb-4" placeholder="Поиск по сумме">
                            <div class="container-checkbox">
                                <?php foreach ($uniqueCarrierQuantity as $item): ?>
                                    <div class="form-check">
                                        <input class="form-check-input js-filter-checkbox" type="checkbox" data-type="0"
                                               data-name-col="quantity-carrier"
                                               value="<?php echo $item; ?>"
                                               id="checkbox-quantity-carrier-<?php echo $item; ?>">
                                        <label class="form-check-label"
                                               for="checkbox-quantity-carrier-<?php echo $item; ?>">
                                            <?php echo $item; ?>
                                        </label>
                                    </div>

                                <?php endforeach; ?>
                            </div>
                            <button class="btn btn-outline-danger btn-sm w-100 mt-2 js-reset-filter">Сбросить фильтр</button>
                        </div>
                    </th>
                    <th>
                        Вид налогообложения
                    </th>
                </tr>
                </thead>
                <tbody>
                <tr style="background-color: orange;font-weight: bold;">
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td id="credit-sum"><?php echo number_format($sumCarrier,2,',',' '); ?></td>
                    <td></td>
                </tr>
                <?php foreach ($listHistoryPaymentCarrier as $item): ?>
                    <tr class="js-tr-application" data-number-carrier="<?php echo $item['application_data']['application_number']; ?>"
                        data-customer-carrier="<?php echo $item['application_data']['customer_id_Carrier']; ?>"
                        data-quantity-carrier="<?php echo $item['quantity']; ?>"
                        data-carrier="<?php echo $item['application_data']['carrier']; ?>"
                    >
                        <td><a target="_blank" href="/journal-list?app-id=<?php echo $item['id_application']; ?>"><?php echo $item['application_data']['application_number']; ?></a></td>
                        <td><?php echo $item['application_data']['customer_Carrier']; ?></td>
                        <td><?php echo $item['application_data']['carrier']; ?></td>
                        <td><?php echo date('d.m.Y', strtotime($item['date'])); ?></td>
                        <td><?php echo number_format($item['quantity'], 2, ',', ' '); ?></td>
                        <td><?php echo $item['application_data']['taxation_type_Carrier']; ?></td>

                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            </div>
        </div>
    </div>
</main>
<script>

    // Закрытие фильтра при клике вне его
    $(document).on('click', function (e) {
        // Если клик вне .menu-filter и вне .js-head-filter
        if (!$(e.target).closest('.menu-filter, .js-head-filter').length) {
            $('.menu-filter').removeClass('active');
            $('.js-head-filter').removeClass('active');
        }
    });

    function countSum() {
        let sumDebit = 0;
        $('#debit').find('.js-tr-application:visible').each(function () {
            sumDebit += parseInt($(this).data('quantity'));
        })

        const formatted = new Intl.NumberFormat('ru-RU', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }).format(sumDebit);
        $('#debit-sum').text(formatted);
        console.log(sumDebit)

        let sumCredit = 0;
        $('#credit').find('.js-tr-application:visible').each(function () {
            sumCredit += parseInt($(this).data('quantity-carrier'));
        })

        const formattedCredit = new Intl.NumberFormat('ru-RU', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }).format(sumCredit);
        $('#credit-sum').text(formattedCredit);

    }
    $('.js-tr-application').click(function () {
        $(this).toggleClass('active');
    })
    $('.js-reset-filter').click(function () {
        $(this).closest('.menu-filter')
            .find('.js-filter-checkbox')
            .prop('checked', false);

        filter();
    });


    $('.js-search-in-filter').on('input', function () {
        $('.menu-filter').has(this).find('.form-check').hide();
        let search = $(this).val();
        $('.menu-filter').has(this).find('.form-check:contains(' + search.toUpperCase() + ')').show();

    });

    function filter(){
        let arrayNameCol = [
            'number','customer','account-number','quantity','customer-carrier',
            'number-carrier','quantity-carrier','client', 'carrier'
        ];

        let array = [];

        $.each(arrayNameCol,function (index,item) {
            let value = [];
            let typeFilter = '';
            $('.js-filter-checkbox[data-name-col="' + item +'"]:checked').each(function () {
                value.push($(this).val());
                typeFilter = $(this).data('type-filter')
            });
            if(value.length > 0)
                array.push({
                    nameCol: item,
                    typeFilter: typeFilter,
                    value: value
                });
        });

        let arrayFilter = [];

        $.each(array, function (index, item) {
            let filter = '';
            $.each(item['value'], function (indexVal, itemVal) {
                filter += `[data-${item['nameCol']}="${itemVal}"],`;
            });
            filter = filter.slice(0, -1);
            arrayFilter.push(filter);
        });

        console.log(arrayFilter);

        let normal = $(`.js-tr-application`);
            $('.js-tr-application').hide();

        console.log(arrayFilter);
        $.each(arrayFilter, function (index, item) {
            normal = normal.filter(item);
        });

        // normal.find('.section-application').removeClass('d-none');
        normal.show().find('.section-application').removeClass('d-none');

        countSum()
    }
    $('.js-head-filter').click(function (e) {
        // Закрыть все, кроме текущего
        $('.menu-filter').not($(this).siblings('.menu-filter')).removeClass('active');
        $('.js-head-filter').not(this).removeClass('active');

        $(this).toggleClass('active');
        $(this).siblings('.menu-filter').toggleClass('active');

        e.stopPropagation(); // Предотвращаем всплытие клика, чтобы document не закрыл только что открытое меню
    });


    $('.js-filter-checkbox').change(function () {
        filter();
    })

    $('#debit-tab,#credit-tab').click(function () {
        $('.menu-filter')
            .find('.js-filter-checkbox')
            .prop('checked', false);

        filter();
    });

</script>
    <script>
        $('#date-picker').change(function () {
            let period = $(this).val();

            document.location.href = '/journal/control-payment?period=' + period;
        })
    </script>

</body>
