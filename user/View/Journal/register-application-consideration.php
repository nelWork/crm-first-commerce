<?php
/** @var App\User\Contoller\Common\HomeController $controller */
/** @var String $titlePage */
/** @var String $link */
/** @var array $listApplication */
/** @var bool $fullCRMAccess */

//dd($listApplication);

$controller->view('Components/head');
?>

<body>
<?php $controller->view('Components/header'); ?>
<style>
    #table-1,#table-2,#table-3{
        font-size: 0.95rem;
    }
    @media (max-width:1440px) {
        #table-1,#table-2,#table-3{
            font-size: 0.9rem;
        }
        .dt-scroll-head{
            font-size: 0.9rem;
        }
    }
    .months-picker{
        text-align: center;
        padding: 1rem;
        cursor: pointer;
        border: 1px solid white;
        margin-bottom: 0.5rem;
    }
    .months-picker:hover,
    .months-picker.active{
        border: 1px solid orange;
    }
    .fillter-date-container{
        width: 100%;
        background: orange;
        padding: 6px 12px;
    }
    .label-fillter-date{
        margin: 0 1rem;
    }
    .btn-fillter-date,
    .btn-fillter-all,
    .btn-setting-table,
    .btn-fillter-submit{
        margin-left: 1rem;
        border: 1px solid grey;
        background-color: white;
        padding: 0 0.5rem;
        margin-right: 1rem;
        text-decoration: none;
        color: black;
    }
    .table-setting-container .form-check{
        margin-bottom: 0.25rem;
    }
    .table-setting-container .form-check-label{
        color: black;
        font-size: 16px;
    }
    .table-setting-container .form-check-input{
        width: 1.125em;
        height: 1.125em;
        margin-top: 0.325em;
    }
    .table-setting-container .form-check:not(.form-switch) .form-check-input[type=checkbox]{
        background-size: 100%;
    }
    .modal {
        position: fixed;
        top: 0;
        z-index: 100;
        left: 0;
        transform: unset;
        background: #00000038;
        border-radius: 10px;
        padding: 0;
        overflow: scroll !important;
    }
    .modal-backdrop.show{
        display: none !important;
    }
    .year-picker{
        font-size: 1.5rem;
    }
    .modal-dialog{
        max-width: 40%;
    }
    .js-tr-application{
        cursor: pointer;
    }
    .js-tr-application.active td{
        background-color: rgba(255, 255, 0, 0.5) !important;;
    }
    .js-tab-task{
        color: #0a58ca
    }
    .custom-menu{
        position: absolute;
        z-index: 10000000;
        background-color: white;
        box-shadow: 5px 10px 10px rgba(0, 0, 0, 0.29);
        list-style: none;
        border-radius: 4px;
        padding: 4px;
        width: 200px;
    }
    .custom-menu li{
        padding: 8px;
        cursor: pointer;
        font-weight: 500;
    }
    .custom-menu li:hover{
        background-color: whitesmoke;
    }
    .dropright-menu-container{
        position: relative;
    }
    .dropright-menu{
        box-shadow: 5px 10px 10px rgba(0, 0, 0, 0.29);
        padding: 4px;
        position: absolute;
        right: -160px;
        top: -5px;
        display: none;
        background-color: white;
    }
    .dropright-menu .item{
        padding: 8px;
    }
    .dropright-menu .item:hover{
        background-color: #e3e3e3;
    }
    .dropright-menu .item.inactive{
        background-color: whitesmoke;
        cursor: default;
        display: none;
    }
    .dropright-menu-container:hover .dropright-menu,
    .dropright-menu:hover{
        display: block;
    }
    .loader-page{
        position: absolute;

        width: calc(100% - 30px);
        height: 70%;
        background-color: rgba(10, 10, 10, 0.64);
        z-index: 1111111;
    }
    .loader-page .spinner-border{
        width: 10rem;
        height: 10rem;
    }
    td{
        position: relative;
    }
    .inn{
        cursor: copy;
    }
    .inn:hover{
        color: #0A58CAFF !important;
    }
    .copy-success{
        position: absolute;
        background-color: #373737;
        font-size: 12px;
        color: white;
        padding: 4px;
    }

    .event-payment-0{
        animation: event 6s ease 0s infinite;
        cursor: pointer;
    }
    .event-payment-1{
        background-color: rgba(45, 226, 118, 0.52) !important;
    }

    @keyframes event {
        0%,
        50%,
        100% {
            background-color: rgba(45, 226, 118, 0.7);
        }

        25%,
        75% {
            background-color: rgba(146, 246, 185, 0.45);
        }
    }
    .table-container{
        width: 100%;
        max-width: 100%;
        max-height: 70vh;
        min-height: 70vh;
    }
    .table{
        width: 100%;
    }
    .filter-header-table{
        cursor: pointer;
        text-align: center;
    }
    .header-table-filter{
        cursor: pointer;
        position: relative;
    }
    .filter-body{
        padding: 10px;
        position: absolute;
        display: none;
        z-index: 100;
        background-color: white;
        margin-left: -6px;
        margin-top: 6px;
        max-height: 300px;
        overflow: auto;
        min-width: 400px;
    }
    .filter-body.active{
        display: block;
    }
    .filter-body-flex.active{
        display: flex;
        justify-content: space-between;
        background: whitesmoke;
    }
    thead th {
        position: sticky; /* Делаем шапку таблицы "липкой" */
        top: 0; /* Привязываем к верхней части контейнера */
        background-color: white !important; /* Цвет фона для шапки */
        z-index: 10; /* Обеспечиваем, чтобы шапка была над остальными ячейками */

    }

    .js-tab-type-app,.js-tab-type-app:hover,.js-tab-type-app:active{
        color: #3f0ecc
    }
    .js-tab-type-app.active{
        background-color: #3f0ecc!important;
    }
    .td-el-between{
        padding-right: 40px !important;
    }
    .td-el-between > span:nth-child(2){
        display: inline-block;
        margin-right: auto;
        margin-left: 30px;
    }
    .td-el-between > span:nth-child(1){
        display: inline-block;
        width: 44px;
    }
    .about-empty-text{
        display: flex;
        justify-content: center;
        margin-top: 30px;
        position: relative;
        left: 61%;
    }
</style>
<main class="journal container-fluid p-4">
    <h1 class="text-center mb-5"><?php echo $titlePage; ?></h1>

    <div class="table-container">
        <table class="table table-striped table-bordered mt-4" id="table-1">
            <thead>
            <tr>
                <th class="table-col-1">Логист</th>
                <th class="table-col-2">№ заявки / Направление</th>
                <th class="table-col-3">Дата заявки</th>
                <th class="table-col-4">Дата погрузки / <div>Дата разгрузки </div></th>
                <th class="table-col-5">Клиент</th>
                <th class="table-col-6">Стоимость перевозки<br>(Клиент)</th>
                <th class="table-col-8">Перевозчик / контакты</th>
                <th class="table-col-9">Стоимость перевозки<br>(Перевозчик)</th>
                <th class="table-col-11"></th>
            </tr>
            </thead>
            <tbody id="tbody-application">
            <?php foreach ($listApplication as $application):  ?>

                <tr class=" tr-application"
                    data-type-app="1"
                    data-app-date="<?php echo date('Y-m-d', strtotime($application['date'])); ?>"
                    data-app-section-journal="<?php echo $application['application_section_journal']; ?>"
                    data-app-status-journal="<?php echo $application['application_status_journal']; ?>"
                    data-app-id-customer="<?php echo $application['id_customer']; ?>"
                    data-app-logist-id="<?php echo $application['id_user']; ?>"
                    data-app-month="<?php echo date('m', strtotime($application['date'])); ?>"
                >

                    <td class="table-col table-col-1">
                        <?php echo $application['manager']; ?>
                    </td>

                    <td class="table-col table-col-2">
                        <!-- № заявки / Направление -->
                        <a href="/application?id=<?php echo $application['id']; ?>" target="_blank" style="color: black;text-decoration: unset;">
                            <?php if($application['application_number'] < 500)
                                echo $application['application_number'].'-Т';
                            else echo $application['application_number'];?>
                        </a>
                        <div class="">
                            <?php $textTransportation = '';
                            foreach ($application['transportation_list'] as $transportation) {
                                $city = explode(',',$transportation['city']);
                                $textTransportation .= $city[count($city) - 1].' - ';
                            }
                            $textTransportation = trim($textTransportation, ' - ');
                            echo $textTransportation;
                            ?>
                        </div>
                    </td>

                    <td class="table-col table-col-3">
                        <!-- Дата заявки -->
                        <?php echo date('d.m.Y', strtotime($application['date'])); ?>
                        <span style="font-size: 12px; color: #0d6efd" class="section-application d-none">
                            <?php echo '(На проверке)'; ?>
                        </span>
                    </td>

                    <td class="table-col table-col-4">
                        <!-- Дата погрузки / Дата разгрузки -->
                        <?php foreach ($application['transportation_list'] as $item): ?>
                            <div><?php echo $item['date']; ?></div>
                        <?php endforeach; ?>
                    </td>

                    <td class="table-col table-col-5">
                        <!-- Название клиента -->
                        <?php echo $application['client_data']['name']; ?>
                    </td>

                    <td class="table-col table-col-6">
                        <!-- Общая сумма -->
                        <div><?php echo number_format(
                                $application['transportation_cost_Client'],
                                0,
                                ',',
                                ' '
                            ); ?> ₽</div>
                        <span class="text-secondary"><?php echo $application['taxation_type_Client']; ?></span>
                    </td>

                    <td class="table-col table-col-8">
                        <!-- Название перевозчика / контактная информация -->
                        <div><?php echo $application['carrier_data']['name']; ?></div>
                        <div>
                        <span>
                            Вод. <?php echo $application['driver_info']; ?>
                        </span>
                        </div>
                    </td>

                    <td class="table-col table-col-9">
                        <!-- Общая сумма  -->
                        <div>
                            <?php echo number_format(
                                $application['transportation_cost_Carrier'],
                                0,
                                ',',
                                ' '
                            ); ?> ₽</div>
                        <span class="text-secondary"><?php echo $application['taxation_type_Carrier']; ?></span>
                    </td>

                    <td class="table-col table-col-11">
                        <!-- Решение  -->
                        <div>
                            <button class="btn btn-primary js-consideration" id="" data-id="<?php echo $application['id']; ?>">Подтвердить</button>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</main>

<script>

    $('.js-consideration').click(function () {

        let id = $(this).data('id');
        console.log (id);

        $.ajax({
            method: 'POST',
            url: '/register-application-consideration/accepted',
            data: {id: id},
            success: function (response) {
                console.log(response);
                let data = JSON.parse(response);
                location.reload();
            }
        })
    });


</script>

</body>
