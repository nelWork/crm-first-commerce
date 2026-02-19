<?php
/** @var App\User\Contoller\Common\HomeController $controller */
/** @var String $titlePage */
/** @var String $link */
/** @var array $listApplication */
/** @var array $condition */
/** @var array $listClients */
/** @var array $listManager */
/** @var array $listCarriers */
/** @var bool $fullCRMAccess */

//dd($listApplication);
//dd($uniqueData);
// dd($listPRRApplication);

$controller->view('Components/head');
?>

<body>
<?php $controller->view('Components/header'); ?>
<style>
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
        overflow: scroll;
    }
    .table{
        width: 170%;
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
    .table-col-9,.table-col-16{
        max-width: 200px;
    }
    .table-col-6-1,.table-col-1,.table-col-3{
        max-width: 120px;
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
    <h1 class="text-center mb-5"><?php echo $titlePage; ?><br>
        <a class="btn btn-warning" href="/journal-list?no-profit=1">
            Журнал без прибыли
        </a>
    </h1>

    <?php //$controller->view('Journal/list-customers'); ?>
    <?php //$controller->view('Journal/list-section'); ?>

    
    <ul class="nav nav-pills justify-content-center w-100 mt-4 mb-5 d-none">
        <li class="nav-item">
            <a class="nav-link js-tab-task js-tab-type-app active"
               aria-current="page" href="#" data-type-app="1">
                Экспедирование
            </a>
        </li>
        <li class="nav-item ">
            <a class="nav-link js-tab-task js-tab-type-app"
               href="#" data-type-app="2">
                ПРР
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link js-tab-task js-tab-type-app"
               href="#" data-type-app="3">
                ТС
            </a>
        </li>
    </ul>


    <form action="">
        <input type="hidden" name="type" value="">
        <div class="fillter-date-container mb-4">
            <input type="date" name="date-start" id="fillter-date-start" value="<?php echo $condition['dateField']['start']; ?>">
            <label for="" class="label-fillter-date">—</label>
            <input type="date" name="date-end" id="fillter-date-end" value="<?php echo $condition['dateField']['end']; ?>">
            <button class="btn-fillter-date" type="button" data-bs-toggle="modal" data-bs-target="#modalFillterDate">...</button>
            <button class="btn-fillter-all" id="btn-fillter-all"  type="button">Фильтры</button>
            <button class="btn-setting-table" data-bs-toggle="modal" data-bs-target="#modalSettingTable" type="button">Настройки таблицы</button>
            <button class="btn-fillter-submit" id="btn-fillter-submit">Применить</button>
            <a href="/journal" class="btn-fillter-submit">Сбросить фильтры</a>
            <button class="btn-fillter-submit" id="journal-excel" type="button">Выгрузить в EXCEL</button>

            <button class="btn-fillter-submit" id="create-register" type="button">Сформировать реестр</button>
        </div>

        <div class="fillter-all-container d-none" data-status="0">
            <label for="" >Выберите логиста</label>

            <select name="logist[]" id="userTo" class="form-select mb-2 js-chosen" data-placeholder="Выберите логиста" multiple>
                <?php foreach ($listManager as $manager): ?>
                    <option value="<?php echo $manager['id']; ?>"
                        <?php foreach ($condition['id_user'] as $selectedId):
                            if($selectedId == $manager['id'])
                                echo 'selected';
                        endforeach; ?>
                    >
                        <?php echo $manager['surname'] .' ' .$manager['name'] . ' ' .$manager['lastname']; ?>
                    </option>
                <?php endforeach; ?>
            </select>


            <label for="" class="mt-3">Выберите клиента</label>
            <select name="client[]" id="selectClients" class="form-select mb-2 js-chosen" data-placeholder="Выберите клиента" multiple>
                <?php foreach ($listClients as $client): ?>
                    <option value="<?php echo $client['id']; ?>"
                        <?php foreach ($condition['client_id_Client'] as $selectedId):
                            if($selectedId == $client['id'])
                                echo 'selected';
                        endforeach; ?>
                    >
                        <?php echo str_replace(['"',"'", '«','»'],'',$client['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="" class="mt-3">Выберите перевозчика</label>
            <select name="carrier[]" id="selectCarriers" class="form-select mb-2 js-chosen" data-placeholder="Выберите перевозчика" multiple>
                <?php foreach ($listCarriers as $carrier): ?>
                    <option value="<?php echo $carrier['id']; ?>"
                        <?php foreach ($condition['carrier_id_Carrier'] as $selectedId):
                            if($selectedId == $carrier['id'])
                                echo 'selected';
                        endforeach; ?>>
                        <?php echo str_replace(['"',"'", '«','»'],'',$carrier['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>


    </form>
    <div class="mb-4">
        <div class="d-flex w-100">
            <div class="">
                <select name="" id="select-search" class="form-select">
                    <option value="0">Все</option>
                    <option value="1">Номер заявки, перевозчик</option>
                    <option value="2">Номер заявки, клиент</option>
                    <option value="3">Номер счета, клиент</option>
                </select>
            </div>
            <div class="w-100 mx-2">
                <input type="text" class="form-control" id="search-journal" placeholder="Введите данные для поиска...">
            </div>
            <div class="">
                <button class="btn btn-danger" id="reset-search">Сбросить</button>
            </div>
        </div>

    </div>

    <div class="table-container">
        <table class="table display table-striped table-bordered" id="table-1">
        <thead >
            <tr>
                <th class="table-col-1">
                    <div class="header-table-filter">
                        <div class="filter-head">
                            Логист <i class="bi bi-caret-down-fill"></i>
                        </div>
                        <div class="filter-body">
                            <div class="search-in-filter-container mb-2">
                                <input type="text" class="form-control form-control-sm js-search-in-filter"
                                       placeholder="Введите имя чтобы найти логиста">
                            </div>
                            <?php foreach ($listManager as $manager): ?>
                                <div class="form-check">
                                    <input class="form-check-input js-filter-header-table" data-name-col="id-user"
                                           data-type-filter="id" type="checkbox" name="filter-header-table-1"
                                           value="<?php echo $manager['id']; ?>" id="flexCheckDefault<?php echo $manager['id']; ?>">
                                    <label class="form-check-label" for="flexCheckDefault<?php echo $manager['id']; ?>">
                                        <?php echo mb_strtoupper($manager['surname'] .' ' .$manager['name'] .' ' .$manager['lastname']); ?>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                </th>
                <th class="table-col-2">
                    <div class="header-table-filter">
                        <div class="filter-head">
                            № заявки, <br> перевозчик <i class="bi bi-caret-down-fill"></i>
                        </div>
                        <div class="filter-body">
                            <div class="search-in-filter-container mb-2">
                                <input type="text" class="form-control form-control-sm js-search-in-filter">
                            </div>
                            <?php foreach ($uniqueData['application_number'] as $appNumber): ?>
                                <div class="form-check">
                                    <input class="form-check-input js-filter-header-table" data-name-col="application-number"
                                           data-type-filter="id" type="checkbox" name="filter-header-table-2"
                                           value="<?php echo $appNumber;?>" id="flexCheckDefault-app-number-<?php echo $appNumber;?>">
                                    <label class="form-check-label" for="flexCheckDefault-app-number-<?php echo $appNumber;?>">
                                        <?php echo $appNumber;?>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </th>
                <th class="table-col-3">№ заявки, клиент</th>
                <th class="table-col-condition " style="text-decoration: underline">
                    Невыполненные условия
                    <i class="bi bi-question-circle" data-bs-toggle="tooltip" data-bs-placement="top"
                       data-bs-custom-class="custom-tooltip"
                       data-bs-title='Условия которые осталось выполнить, чтобы заявка перешла в следующий статус'></i>
                </th>
                <th class="table-col-4">
                    <div class="header-table-filter">
                        <div class="filter-head">
                            Дата заявки <i class="bi bi-caret-down-fill"></i>
                        </div>
                        <div class="filter-body">
                            <?php foreach ($uniqueData['date'] as $date): ?>
                                <div class="form-check">
                                    <input class="form-check-input js-filter-header-table" data-name-col="app-date"
                                           data-type-filter="id" type="checkbox" name="filter-header-table-2"
                                           value="<?php echo $date;?>" id="flexCheckDefault-date-<?php echo $date;?>">
                                    <label class="form-check-label" for="flexCheckDefault-date-<?php echo $date;?>">
                                        <?php echo $date;?>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                </th>
                <th class="table-col-5">Дата погрузки</th>
                <th class="table-col-6">
                    <div class="header-table-filter">
                        <div class="filter-head">
                            Дата разгрузки <i class="bi bi-caret-down-fill"></i>
                        </div>
                        <div class="filter-body">
                            <?php foreach ($uniqueData['date_unloading_str'] as $date): ?>
                                <div class="form-check">
                                    <input class="form-check-input js-filter-header-table" data-name-col="date-unloading"
                                           data-type-filter="id" type="checkbox" name="filter-header-table-2"
                                           value="<?php echo $date;?>" id="flexCheckDefault-date-actual-unloading-<?php echo $date;?>">
                                    <label class="form-check-label" for="flexCheckDefault-date-actual-unloading-<?php echo $date;?>">
                                        <?php echo $date;?>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </th>
                <th class="table-col-6-1">
                    <div class="header-table-filter">
                        <div class="filter-head">
                            Актуальная дата разгрузки <i class="bi bi-caret-down-fill"></i>
                        </div>
                        <div class="filter-body">
                            <?php foreach ($uniqueData['application_date_actual_unloading'] as $date): ?>
                                <div class="form-check">
                                    <input class="form-check-input js-filter-header-table" data-name-col="date-actual-unloading"
                                           data-type-filter="id" type="checkbox" name="filter-header-table-2"
                                           value="<?php echo $date;?>" id="flexCheckDefault-date-actual-unloading-<?php echo $date;?>">
                                    <label class="form-check-label" for="flexCheckDefault-date-actual-unloading-<?php echo $date;?>">
                                        <?php echo $date;?>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                </th>
                <th class="table-col-7">ТТН</th>
                <th class="table-col-8">ТТН отправлено</th>
                <th class="table-col-9">
                    <div class="header-table-filter">
                        <div class="filter-head">
                            Название клиента <i class="bi bi-caret-down-fill"></i>
                        </div>
                        <div class="filter-body">

                            <div class="search-in-filter-container mb-2">
                                <input type="text" class="form-control form-control-sm js-search-in-filter" placeholder="Введите название чтобы найти клиента">
                            </div>

                            <?php foreach ($uniqueData['client'] as $arrayClient): ?>
                                <div class="form-check">
                                    <input class="form-check-input js-filter-header-table" data-name-col="client-id"
                                           data-type-filter="id" type="checkbox" name="filter-header-table-3"
                                           value="<?php echo $arrayClient['id'];?>" id="flexCheckDefault-client-id-<?php echo $arrayClient['id'];?>">
                                    <label class="form-check-label" for="flexCheckDefault-client-id-<?php echo $arrayClient['id'];?>">
                                        <?php echo mb_strtoupper(str_replace(['"',"'","«","»"],'',$arrayClient['name']));?>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </th>
                <th class="table-col-10">
                    <div class="dropdown ">
                        <div class="dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Номер счета <br> и дата
                        </div>
                        <div class="dropdown-menu ">
                            <div class="ps-2">
                            <div class="form-check">
                                <input class="form-check-input js-filter-header-table"  data-name-col="app-isset-account-number-client"
                                       type="checkbox" checked
                                       name="filter-account-number-client" value="2" id="flexCheckIndeterminate-1">
                                <label class="form-check-label" for="flexCheckIndeterminate-1">
                                    Все
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input js-filter-header-table" data-name-col="app-isset-account-number-client"
                                       type="checkbox" name="filter-account-number-client"
                                       value="0" id="flexCheckIndeterminate-2">
                                <label class="form-check-label" for="flexCheckIndeterminate-2">
                                    Пустые
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input js-filter-header-table" data-name-col="app-isset-account-number-client"
                                       type="checkbox" name="filter-account-number-client" value="1" id="flexCheckIndeterminate-3">
                                <label class="form-check-label" for="flexCheckIndeterminate-3">
                                    Со счетом
                                </label>
                            </div>
                            </div>
                        </div>
                    </div>
                </th>
                <th class="table-col-11">Номер УПД и дата</th>
                <th class="table-col-12">
                    <div class="header-table-filter">
                        <div class="filter-head">
                            Общая сумма <i class="bi bi-caret-down-fill"></i>
                        </div>
                        <div class="filter-body" style="top: 80px">
                            <?php foreach ($uniqueData['cost_client'] as $cost): ?>
                                <div class="form-check">
                                    <input class="form-check-input js-filter-header-table" data-name-col="transportation-cost-client"
                                           data-type-filter="id" type="checkbox" name="filter-header-table-2"
                                           value="<?php echo $cost;?>" id="flexCheckDefault-cost-client-<?php echo $cost;?>">
                                    <label class="form-check-label" for="flexCheckDefault-cost-client-<?php echo $cost;?>">
                                        <?php echo number_format($cost,0,'.',' '); ?> ₽
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="filter-body filter-body-flex">
                            <div class="form-check">
                                <input class="form-check-input js-filter-header-table" data-name-col="taxation-client"
                                       data-type-filter="id" type="checkbox" name="filter-header-table-2"
                                       value="С НДС" id="flexCheckDefault-taxation-client-1">
                                <label class="form-check-label" for="flexCheckDefault-taxation-client-1">
                                    С НДС
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input js-filter-header-table" data-name-col="taxation-client"
                                       data-type-filter="id" type="checkbox" name="filter-header-table-2"
                                       value="Б/НДС" id="flexCheckDefault-taxation-client-2">
                                <label class="form-check-label" for="flexCheckDefault-taxation-client-2">
                                    Б/НДС
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input js-filter-header-table" data-name-col="taxation-client"
                                       data-type-filter="id" type="checkbox" name="filter-header-table-2"
                                       value="НАЛ" id="flexCheckDefault-taxation-client-3">
                                <label class="form-check-label" for="flexCheckDefault-taxation-client-3">
                                    НАЛ
                                </label>
                            </div>
                        </div>
                    </div>

                </th>
                <th class="table-col-13">Сумма без НДС</th>
                <th class="table-col-14">НДС</th>
                <th class="table-col-15">
                    <div class="header-table-filter">
                        <div class="filter-head">
                            Факт. сумма оплаты <i class="bi bi-caret-down-fill"></i>
                        </div>
                        <div class="filter-body">
                            <div class="form-check" style="display: block!important;">
                                <input class="form-check-input js-filter-header-table"  data-name-col="actual-payment-client"
                                       data-type-filter="id" type="checkbox" name="filter-header-table-2"
                                       value="*" id="flexCheckDefault-actual-payment-client">
                                <label class="form-check-label" for="flexCheckDefault-actual-payment-client">
                                    Все
                                </label>
                            </div>
                            <?php foreach ($uniqueData['actual_payment_client'] as $cost): ?>
                                <div class="form-check">
                                    <input class="form-check-input js-filter-header-table" data-name-col="actual-payment-client"
                                           data-type-filter="id" type="checkbox" name="filter-header-table-2"
                                           value="<?php echo $cost;?>" id="flexCheckDefault-actual-payment-client-<?php echo $cost;?>">
                                    <label class="form-check-label" for="flexCheckDefault-actual-payment-client-<?php echo $cost;?>">
                                        <?php echo number_format($cost,0,'.',' '); ?> ₽
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </th>
                <th class="table-col-15-1">
                    <div class="header-table-filter">
                        <div class="filter-head">
                            Доп. прибыль <i class="bi bi-caret-down-fill"></i>
                        </div>
                    </div>
                </th>
                <th class="table-col-16">
                    <div class="header-table-filter">
                        <div class="filter-head">
                            Название перевозчика <i class="bi bi-caret-down-fill"></i>
                        </div>
                        <div class="filter-body">
                            <div class="search-in-filter-container mb-2">
                                <input type="text" class="form-control form-control-sm js-search-in-filter" placeholder="Введите название чтобы найти перевозчика">
                            </div>
                            <?php foreach ($uniqueData['carrier'] as $arrayCarrier): ?>
                                <div class="form-check">
                                    <input class="form-check-input js-filter-header-table" data-name-col="carrier-id"
                                           data-type-filter="id" type="checkbox" name="filter-header-table-3"
                                           value="<?php echo $arrayCarrier['id'];?>" id="flexCheckDefault-carrier-id-<?php echo $arrayCarrier['id'];?>">
                                    <label class="form-check-label" for="flexCheckDefault-carrier-id-<?php echo $arrayCarrier['id'];?>">
                                        <?php echo $arrayCarrier['name'];?>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </th>
                <th class="table-col-19">
                    <div class="header-table-filter">
                        <div class="filter-head">
                            Общая сумма <i class="bi bi-caret-down-fill"></i>
                        </div>
                        <div class="filter-body" style="top: 80px">
                            <?php foreach ($uniqueData['cost_carrier'] as $cost): ?>
                                <div class="form-check">
                                    <input class="form-check-input js-filter-header-table" data-name-col="transportation-cost-carrier"
                                           data-type-filter="id" type="checkbox" name="filter-header-table-2"
                                           value="<?php echo $cost;?>" id="flexCheckDefault-cost-carrier-<?php echo $cost;?>">
                                    <label class="form-check-label" for="flexCheckDefault-cost-carrier-<?php echo $cost;?>">
                                        <?php echo number_format($cost,0,'.',' '); ?> ₽
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="filter-body filter-body-flex">
                            <div class="form-check">
                                <input class="form-check-input js-filter-header-table" data-name-col="taxation-carrier"
                                       data-type-filter="id" type="checkbox" name="filter-header-table-2"
                                       value="С НДС" id="flexCheckDefault-taxation-carrier-1">
                                <label class="form-check-label" for="flexCheckDefault-taxation-carrier-1">
                                    С НДС
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input js-filter-header-table" data-name-col="taxation-carrier"
                                       data-type-filter="id" type="checkbox" name="filter-header-table-2"
                                       value="Б/НДС" id="flexCheckDefault-taxation-carrier-2">
                                <label class="form-check-label" for="flexCheckDefault-taxation-carrier-2">
                                    Б/НДС
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input js-filter-header-table" data-name-col="taxation-carrier"
                                       data-type-filter="id" type="checkbox" name="filter-header-table-2"
                                       value="НАЛ" id="flexCheckDefault-taxation-carrier-3">
                                <label class="form-check-label" for="flexCheckDefault-taxation-carrier-3">
                                    НАЛ
                                </label>
                            </div>
                        </div>
                    </div>
                </th>
                <th class="table-col-20">Сумма без НДС</th>
                <th class="table-col-21">НДС</th>
                <th class="table-col-22">
                    <div class="header-table-filter">
                        <div class="filter-head">
                            Факт. сумма оплаты <i class="bi bi-caret-down-fill"></i>
                        </div>
                        <div class="filter-body">
                            <div class="form-check">
                                <input class="form-check-input js-filter-header-table" data-name-col="actual-payment-carrier"
                                       data-type-filter="id" type="checkbox" name="filter-header-table-2"
                                       value="*" id="flexCheckDefault-actual-payment-carrier">
                                <label class="form-check-label" for="flexCheckDefault-actual-payment-carrier">
                                    Все
                                </label>
                            </div>

                            <?php foreach ($uniqueData['actual_payment_carrier'] as $cost): ?>
                                <div class="form-check">
                                    <input class="form-check-input js-filter-header-table" data-name-col="actual-payment-carrier"
                                           data-type-filter="id" type="checkbox" name="filter-header-table-2"
                                           value="<?php echo $cost;?>" id="flexCheckDefault-actual-payment-carrier-<?php echo $cost;?>">
                                    <label class="form-check-label" for="flexCheckDefault-actual-payment-carrier-<?php echo $cost;?>">
                                        <?php echo number_format($cost,0,'.',' '); ?> ₽
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </th>
                <th class="table-col-23">
                    <div class="header-table-filter">
                        <div class="filter-head">
                            Доп. расходы <i class="bi bi-caret-down-fill"></i>
                        </div>
                        <div class="filter-body">
                            <div class="form-check">
                                <input class="form-check-input js-filter-header-table" data-name-col="additional-expenses-gruz"
                                           data-type-filter="id" type="checkbox" name="filter-header-table-2"
                                           value="1" id="flexCheckDefault-additional-expenses-gruz">
                                <label class="form-check-label" for="flexCheckDefault-additional-expenses-gruz">
                                    Грузчики
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input js-filter-header-table" data-name-col="additional-expenses-deduction"
                                       data-type-filter="id" type="checkbox" name="filter-header-table-2"
                                       value="1" id="flexCheckDefault-additional-expenses-deduction">
                                <label class="form-check-label" for="flexCheckDefault-additional-expenses-deduction">
                                    Вычет
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input js-filter-header-table" data-name-col="additional-expenses-point"
                                       data-type-filter="id" type="checkbox" name="filter-header-table-2"
                                       value="1" id="flexCheckDefault-additional-expenses-point">
                                <label class="form-check-label" for="flexCheckDefault-additional-expenses-point">
                                    Доп. точка
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input js-filter-header-table" data-name-col="additional-expenses-downtime"
                                       data-type-filter="id" type="checkbox" name="filter-header-table-2"
                                       value="1" id="flexCheckDefault-additional-expenses-downtime">
                                <label class="form-check-label" for="flexCheckDefault-additional-expenses-downtime">
                                    Простои
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input js-filter-header-table" data-name-col="additional-expenses-overload"
                                       data-type-filter="id" type="checkbox" name="filter-header-table-2"
                                       value="1" id="flexCheckDefault-additional-expenses-overload">
                                <label class="form-check-label" for="flexCheckDefault-additional-expenses-overload">
                                    Перегруз
                                </label>
                            </div>
                        </div>
                    </div>
                </th>

                <th class="table-col-24">
                    <div class="header-table-filter">
                        <div class="filter-head">
                            Доход <i class="bi bi-caret-down-fill"></i>
                        </div>
                        <div class="filter-body">
                            <?php foreach ($uniqueData['application_walrus'] as $cost): ?>
                                <div class="form-check">
                                    <input class="form-check-input js-filter-header-table" data-name-col="application-walrus"
                                           data-type-filter="id" type="checkbox" name="filter-header-table-2"
                                           value="<?php echo $cost;?>" id="flexCheckDefault-application-walrus-<?php echo $cost;?>">
                                    <label class="form-check-label" for="flexCheckDefault-application-walrus-<?php echo $cost;?>">
                                        <?php echo number_format($cost,0,'.',' '); ?> ₽
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </th>
                <th class="table-col-25">Маржа з.п.</th>
                <th class="table-col-26">Чистая прибыль</th>
                <th class="table-col-27">
                    <div class="header-table-filter">
                        <div class="filter-head">
                            Маржинальность <i class="bi bi-caret-down-fill"></i>
                        </div>
                        <div class="filter-body">
                            <?php foreach ($uniqueData['marginality'] as $cost): ?>
                                <div class="form-check">
                                    <input class="form-check-input js-filter-header-table" data-name-col="marginality"
                                           data-type-filter="id" type="checkbox" name="filter-header-table-2"
                                           value="<?php echo $cost;?>" id="flexCheckDefault-marginality-<?php echo $cost;?>">
                                    <label class="form-check-label" for="flexCheckDefault-marginality-<?php echo $cost;?>">
                                        <?php echo $cost; ?>%
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </th>
            </tr>
        </thead>
        <tbody style="position: relative; padding-top: 40px;">
        <div class="loader-page d-none">
            <div class="d-flex w-100 h-100 justify-content-center align-items-center">
                <div class="spinner-border text-light" role="status">
                    <span class="visually-hidden">Загрузка...</span>
                </div>
            </div>
        </div>
        <tr id="tr-sum-1" style="background-color: darkorange">
            <td></td>
            <td></td>
            <td></td>
            <td class="table-col-condition"></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td class="table-col-12">1</td>
            <td class="table-col-13">2</td>
            <td class="table-col-14">3</td>
            <td class="table-col-15">4</td>
            <td class="table-col-15-1"></td>
            <td></td>
            <td class="table-col-19">1</td>
            <td class="table-col-20">2</td>
            <td class="table-col-21">3</td>
            <td class="table-col-22">4</td>
            <td class=""></td>
            <td class="table-col-24">5</td>
            <td class="table-col-25">6</td>
            <td class="table-col-26">7</td>
            <td class="table-col-27"></td>
        </tr>
        <?php foreach ($listApplication as $application): ?>
            <?php if(isset($application['unfulfilledConditions'])){
                    foreach ($application['unfulfilledConditions'] as $key => $condition){
                        switch ($condition){
                            case 'Комментарий о полученных документах':
                                $application['unfulfilledConditions'][$key] = 'Комментарий о полученных документах (оригиналы) для нас';
                                break;
                            case 'Комментарий о полученных документах для клиента':
                                $application['unfulfilledConditions'][$key] = 'Комментарий о полученных документах (оригиналы) для клиента';
                                break;
                        }
                    }

            } ?>
            <?php
            $carrierPaymentEvent = false;
            $clientPaymentEvent = false;
            foreach ($application['events_application'] as $event) {
                if($event['event'] == 'client_payment_status')
                    $clientPaymentEvent = ['id' => $event['id'] ,'status' => $event['status']];
                if($event['event'] == 'carrier_payment_status')
                    $carrierPaymentEvent = ['id' => $event['id'] ,'status' => $event['status']];
            }

            $unloadingStr = '';

            foreach ($application['transportation_list'] as $transportation){
                if(!$transportation['direction'])
                    $unloadingStr .= $transportation['date'] . ', ';
            }

            $unloadingStr = trim($unloadingStr, ', ');

            ?>
            <tr class="js-tr-application tr-application"
                data-application-walrus="<?php echo $application['application_walrus']; ?>"
                data-additional-expenses-gruz="<?php foreach ($application['additional_expenses'] as $expense)
                    if($expense['type_expenses'] == 'Грузчики'){ echo 1; break; }?>"
                data-additional-expenses-deduction="<?php foreach ($application['additional_expenses'] as $expense)
                    if($expense['type_expenses'] == 'Вычет'){ echo 1; break; }?>"
                data-additional-expenses-point="<?php foreach ($application['additional_expenses'] as $expense)
                    if($expense['type_expenses'] == 'Доп. точка'){ echo 1; break; }?>"
                data-additional-expenses-downtime="<?php foreach ($application['additional_expenses'] as $expense)
                    if($expense['type_expenses'] == 'Простои'){ echo 1; break; }?>"
                data-additional-expenses-overload="<?php foreach ($application['additional_expenses'] as $expense)
                    if($expense['type_expenses'] == 'Перегруз'){ echo 1; break; }?>"
                data-date-unloading="<?php echo $unloadingStr; ?>"
                data-actual-payment-client="<?php echo $application['actual_payment_Client']; ?>"
                data-actual-payment-carrier="<?php echo $application['actual_payment_Carrier']; ?>"
                data-transportation-cost-client="<?php echo $application['transportation_cost_Client']; ?>"
                data-transportation-cost-carrier="<?php echo $application['transportation_cost_Carrier']; ?>"
                data-id-user="<?php echo $application['id_user']; ?>"
                data-application-number="<?php echo $application['application_number']; ?>"
                data-date-actual-unloading="<?php if($application['application_date_actual_unloading']) echo date('d.m.Y', strtotime($application['application_date_actual_unloading'])); else echo 'не указана';?>"
                data-client-id="<?php echo $application['client_id_Client'];  ?>"
                data-carrier-id="<?php echo $application['carrier_id_Carrier'];  ?>"
                data-app-date="<?php echo date('d.m.Y', strtotime($application['date'])); ?>"
                data-app-section-journal="<?php echo $application['application_section_journal']; ?>"
                data-app-status-journal="<?php echo $application['application_status_journal']; ?>"
                data-app-id-customer="<?php echo $application['id_customer']; ?>"
                data-marginality="<?php echo $application['marginality']; ?>"
                data-active="0" data-id-application="<?php echo $application['id']; ?>"
                data-app-isset-account-number-client="<?php if($application['account_number_Client'] == '') echo 0; else echo 1; ?>"
                data-id="<?php echo $application['id']; ?>"
                data-taxation-client="<?php echo $application['taxation_type_Client']; ?>"
                data-taxation-carrier="<?php echo $application['taxation_type_Carrier']; ?>"
            >
                <td class="table-col-1">
                    <!-- Логист -->
                    <?php echo $application['manager']; ?>

                </td>
                <td class="table-col-2 table-col-application-number-carrier">
                    <!-- Номер заявки, перевозчик -->
                    <a href="/application?id=<?php echo $application['id']; ?>" target="_blank" style="color: black;text-decoration: unset;">
                        <?php if($application['application_number'] < 500)
                            echo $application['application_number'].'-Т';
                        else echo $application['application_number'];?>
                    </a>
                </td>
                <td class="table-col-3 table-col-application-number-client">
                    <!-- Номер заявки, клиента -->
                    <?php
                        if($application['application_number_Client']) echo $application['application_number_Client'];
                        else echo '<div class="text-center">—</div>';
                    ?>

                </td>
                <td class="table-col-condition">
                    <!-- Невыполненные условия -->
                    <?php if(isset($application['unfulfilledConditions'])): ?>
                        <?php foreach ($application['unfulfilledConditions'] as $unfulfilledCondition): ?>
                            <div>- <?php echo $unfulfilledCondition; ?></div>
                        <?php endforeach; ?>
                    <?php endif; ?>

                </td>
                <td class="table-col-4">
                    <!-- Дата заявки -->
                    <?php echo date('d.m.Y', strtotime($application['date'])); ?>
                    <span style="font-size: 12px; color: #0d6efd" class="section-application d-none">
                        <?php
                        switch ($application['application_section_journal']):
                            case 1:
                                echo '(Актуальные)';
                                break;
                            case 2:
                                echo '(Завершенные)';
                                break;
                            case 3:
                                echo '(Закрытые под расчет)';
                                break;
                            case 4:
                                echo '(Срывы)';
                                break;
                            case 5:
                                echo '(Отмененные)';
                                break;
                        endswitch;
                        ?>
                    </span>
                    <div style="font-size: 12px; color: #0d6efd" class="section-application d-none">
                        <?php echo $customers[$application['id_customer'] - 1]['name']; ?>
                    </div>
                </td>
                <td class="table-col-5">
                    <!-- Дата погрузки -->
                    <?php foreach ($application['transportation_list'] as $item): if($item['direction']): ?>
                        <div><?php echo $item['date']; ?></div>
                    <?php endif; endforeach; ?>
                </td>
                <td class="table-col-6">
                    <!-- Дата разгрузки -->
                    <?php foreach ($application['transportation_list'] as $item): if(!$item['direction']): ?>
                        <div><?php echo $item['date']; ?></div>
                    <?php endif; endforeach; ?>
                </td>
                <td class="table-col-6-1">
                    <!-- Актуальная дата разгрузки -->
                    <?php if($application['application_date_actual_unloading'])
                        echo date('d.m.Y', strtotime($application['application_date_actual_unloading'])); ?>
                </td>
                <td class="table-col-7">
                    <!-- ТТН -->
                </td>
                <td class="table-col-8">
                    <!-- ТТН отправлено -->
                    <div class="text-center" style="font-weight: 600">
                        <?php if($application['ttn_sent']) echo 'ОТПРАВЛЕНО'; else echo 'НЕ ОТПРАВЛЕНО'; ?>
                    </div>
                </td>
                <td class="table-col-9 col-client
                    <?php if($clientPaymentEvent) echo 'event-payment-' .$clientPaymentEvent['status']; ?> "
                    <?php if($clientPaymentEvent) echo 'data-id-event="'.$clientPaymentEvent['id'] .'"'; ?>
                    data-type="client">
                    <!-- Название клиента -->
                    <?php echo $application['client_data']['name']; ?>
                    <div><span class="inn text-secondary"><?php echo $application['client_data']['inn']; ?></span></div>
                    <?php if ($application['client_data']['format_work']): ?>
                        <div class="">(<?php echo $application['client_data']['format_work']; ?>)</div>
                    <?php endif; ?>
                </td>
                <td class="table-col-10 col-client table-col-account-number-client
                    <?php if($clientPaymentEvent) echo 'event-payment-' .$clientPaymentEvent['status']; ?> "
                    <?php if($clientPaymentEvent) echo 'data-id-event="'.$clientPaymentEvent['id'] .'"'; ?>
                    data-type="client">
                    <!-- Номер счета и дата  -->
                    <textarea id="account-number-client-<?php echo $application['id']; ?>" data-name-span="span-account-number-client"
                              data-id-application="<?php echo $application['id'] ?>" data-name-info="account_number_Client"
                              class="d-none form-control textarea-change-application-info"><?php echo $application['account_number_Client']; ?></textarea>
                    <span class="span-info" id="span-account-number-client-<?php echo $application['id']; ?>">
                        <?php echo $application['account_number_Client']; ?>
                    </span>

                    <div class="">
                        (<?php
                                if($application['account_status_Client']) echo 'Отправлен ' . date('d.m.Y', strtotime($application['date_invoice_Client']));
                                else echo 'Не отправлен';
                            ?>)
                    </div>

                </td>
                <td class="table-col-11 col-client
                    <?php if($clientPaymentEvent) echo 'event-payment-' .$clientPaymentEvent['status']; ?> "
                    <?php if($clientPaymentEvent) echo 'data-id-event="'.$clientPaymentEvent['id'] .'"'; ?>
                    data-type="client">
                    <!-- Номер УПД и дата -->
                    <textarea id="upd-number-client-<?php echo $application['id']; ?>" data-name-span="span-upd-number-client"
                              data-id-application="<?php echo $application['id'] ?>" data-name-info="upd_number_Client"
                              class="d-none form-control textarea-change-application-info"><?php echo $application['upd_number_Client']; ?></textarea>
                    <span class="span-info" id="span-upd-number-client-<?php echo $application['id']; ?>">
                        <?php echo $application['upd_number_Client']; ?>
                    </span>
                </td>
                <td class="table-col-12 col-client
                    <?php if($clientPaymentEvent) echo 'event-payment-' .$clientPaymentEvent['status']; ?> "
                    <?php if($clientPaymentEvent) echo 'data-id-event="'.$clientPaymentEvent['id'] .'"'; ?>
                    data-type="client" data-cost="<?php echo $application['transportation_cost_Client']; ?>">
                    <!-- Общая сумма -->
                    <div><?php echo number_format($application['transportation_cost_Client'],0,'.',' '); ?> ₽</div>
                    <span class="text-secondary"><?php echo $application['taxation_type_Client']; ?></span>
                </td>
                <td class="table-col-13 col-client
                    <?php if($clientPaymentEvent) echo 'event-payment-' .$clientPaymentEvent['status']; ?> "
                    <?php if($clientPaymentEvent) echo 'data-id-event="'.$clientPaymentEvent['id'] .'"'; ?>
                    data-type="client" data-cost="<?php if($application['taxation_type_Client'] == 'С НДС') echo $application['transportation_cost_Client'] / 1.2; else echo $application['transportation_cost_Client']; ?>">
                    <!-- Сумма без НДС -->
                    <?php
                    if($application['taxation_type_Client'] == 'С НДС')
                        echo number_format($application['transportation_cost_Client'] / 1.2,0,'.',' ');
                    else
                        echo number_format($application['transportation_cost_Client'],0,'.',' ');
                    ?> ₽
                </td>
                <td class="table-col-14 col-client
                    <?php if($clientPaymentEvent) echo 'event-payment-' .$clientPaymentEvent['status']; ?> "
                    <?php if($clientPaymentEvent) echo 'data-id-event="'.$clientPaymentEvent['id'] .'"'; ?>
                    data-type="client" data-cost="<?php if($application['taxation_type_Client'] == 'С НДС') echo $application['transportation_cost_Client'] / 6; else echo 0; ?>">
                    <!-- НДС -->
                    <?php
                    if($application['taxation_type_Client'] == 'С НДС')
                        echo number_format($application['transportation_cost_Client'] / 6,0,'.',' ');
                    else
                        echo number_format(0,0,'.',' ');
                    ?> ₽
                </td>
                <td class="table-col-15 col-client
                    <?php if($clientPaymentEvent) echo 'event-payment-' .$clientPaymentEvent['status']; ?> "
                    <?php if($clientPaymentEvent) echo 'data-id-event="'.$clientPaymentEvent['id'] .'"'; ?>
                    data-type="client" data-cost="<?php echo $application['actual_payment_Client']; ?>">
                    <!-- Фактическая сумма оплаты -->
                    <input type="number" min="0" id="actual-payment-client-<?php echo $application['id']; ?>" data-name-span="span-actual-payment-client"
                              data-id-application="<?php echo $application['id'] ?>" data-name-info="actual_payment_Client"
                              class="d-none form-control textarea-change-application-info">

                    <div data-bs-toggle="collapse" class="span-actual-payment-client"
                          id="span-actual-payment-client-<?php echo $application['id']; ?>" href="#collapseHistoryPayment<?php echo $application['id']; ?>"
                         role="button" aria-expanded="false" aria-controls="collapseHistoryPayment">
                        <?php echo number_format(
                            $application['actual_payment_Client'],
                            0,
                            ',',
                            ' '
                        ); ?> ₽
                        <div class="">
                            <?php if($application['full_payment_date_Client']): ?>
                                (<span class="span-date-payment"><?php echo date('d.m.Y', strtotime($application['full_payment_date_Client'])); ?></span>
                                <?php if($accessChangePayment): ?><i class="bi bi-pencil-square js-change-payment-date"></i> <?php endif; ?>)
                                <?php if($accessChangePayment): ?><input type="date" class="form-control input-change-date d-none" data-side="Client" data-id-app="<?php echo $application['id']; ?>"
                                       value="<?php echo date('d.m.Y', strtotime($application['full_payment_date_Client'])); ?>">
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                        <?php if(count($application['history_payment_Client'])): ?>
                            <i class="bi bi-caret-down-fill text-dark"></i>
                        <?php endif; ?>
                    </div>
                    <div class="collapse" id="collapseHistoryPayment<?php echo $application['id']; ?>">
                        <?php foreach ($application['history_payment_Client'] as $history): ?>
                            <div class="expenses small">

                                <?php echo number_format($history['quantity'],0, ',',' ') ; ?> ₽
                                 (
                                    <span class="span-date-payment-history">
                                        <?php echo date('d.m.Y', strtotime($history['date'])); ?>
                                    </span>
                                    <i class="bi bi-pencil-square js-change-payment-date-history"></i>)
                                <input type="date" class="form-control input-change-date-history d-none" data-id-payment="<?php echo $history['id']; ?>"
                                       value="<?php echo date('d.m.Y', strtotime($history['date'])); ?>">
                            </div>
                        <?php endforeach; ?>
                    </div>

                </td>
                <td class="table-col-15-1">
                    <div class="text-success" data-bs-toggle="collapse" href="#collapseProfit<?php echo $application['id']; ?>" role="button" aria-expanded="false" aria-controls="collapseProfit">
                        <?php echo number_format(
                            $application['additional_profit_sum_Client'] + $application['fines_sum'],
                            0,
                            ',',
                            ' '
                        ); ?> ₽
                        <?php if(count($application['additional_profit'])): ?>
                            <i class="bi bi-caret-down-fill text-dark"></i>
                        <?php endif; ?>
                    </div>
                    <div class="collapse" id="collapseProfit<?php echo $application['id']; ?>">
                        <?php foreach ($application['additional_profit'] as $profit): ?>
                            <div class="profit small">
                                <?php echo $profit['type'] ." (" .$profit['type_payment'] .' - ' .number_format($profit['sum'],0, ',',' ') ."₽)"; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </td>
                <td class="table-col-16 col-carrier <?php if($carrierPaymentEvent) echo 'event-payment-' .$carrierPaymentEvent['status']; ?> "
                    <?php if($carrierPaymentEvent) echo 'data-id-event="'.$carrierPaymentEvent['id'] .'"'; ?>
                    data-type="carrier">
                    <!-- Название перевозчика -->
                    <?php echo $application['carrier_data']['name']; ?>
                    <div><span class="inn text-secondary"><?php echo $application['carrier_data']['inn']; ?></span></div>
                </td>
                <td class="table-col-19 col-carrier <?php if($carrierPaymentEvent) echo 'event-payment-' .$carrierPaymentEvent['status']; ?> "
                    <?php if($carrierPaymentEvent) echo 'data-id-event="'.$carrierPaymentEvent['id'] .'"'; ?>
                    data-type="carrier" data-cost="<?php echo $application['transportation_cost_Carrier']; ?>">
                    <!-- Общая сумма  -->
                    <div><?php echo number_format($application['transportation_cost_Carrier'],0,'.',' '); ?> ₽</div>
                    <span class="text-secondary"><?php echo $application['taxation_type_Carrier']; ?></span>
                </td>
                <td class="table-col-20 col-carrier <?php if($carrierPaymentEvent) echo 'event-payment-' .$carrierPaymentEvent['status']; ?> "
                    <?php if($carrierPaymentEvent) echo 'data-id-event="'.$carrierPaymentEvent['id'] .'"'; ?>
                    data-type="carrier" data-cost="<?php if($application['taxation_type_Carrier'] == 'С НДС') echo $application['transportation_cost_Carrier'] / 1.2; else echo $application['transportation_cost_Carrier']; ?>">
                    <!-- Сумма без НДС -->
                    <?php
                    if($application['taxation_type_Carrier'] == 'С НДС')
                        echo number_format($application['transportation_cost_Carrier'] / 1.2,0,'.',' ');
                    else
                        echo number_format($application['transportation_cost_Carrier'],0,'.',' ');
                    ?> ₽
                </td>
                <td class="table-col-21 col-carrier <?php if($carrierPaymentEvent) echo 'event-payment-' .$carrierPaymentEvent['status']; ?> "
                    <?php if($carrierPaymentEvent) echo 'data-id-event="'.$carrierPaymentEvent['id'] .'"'; ?>
                    data-type="carrier" data-cost="<?php if($application['taxation_type_Carrier'] == 'С НДС') echo $application['transportation_cost_Carrier'] / 6; else echo 0; ?>">
                    <!-- НДС -->
                    <?php
                    if($application['taxation_type_Carrier'] == 'С НДС')
                        echo number_format($application['transportation_cost_Carrier'] / 6,0,'.',' ');
                    else
                        echo number_format(0,0,'.',' ');
                    ?> ₽
                </td>
                <td class="table-col-22 col-carrier <?php if($carrierPaymentEvent) echo 'event-payment-' .$carrierPaymentEvent['status']; ?> "
                    <?php if($carrierPaymentEvent) echo 'data-id-event="'.$carrierPaymentEvent['id'] .'"'; ?>
                    data-type="carrier" data-cost="<?php echo $application['actual_payment_Carrier']; ?>">
                    <!-- Фактическая сумма оплаты -->
                    <input id="actual-payment-carrier-<?php echo $application['id']; ?>" data-name-span="span-actual-payment-carrier"
                              data-id-application="<?php echo $application['id'] ?>" data-name-info="actual_payment_Carrier"
                              class="d-none form-control textarea-change-application-info">
                    <div  data-bs-toggle="collapse" class="span-actual-payment-carrier" id="span-actual-payment-carrier-<?php echo $application['id']; ?>"
                          href="#collapseHistoryPaymentCarrier-<?php echo $application['id']; ?>"
                          role="button" aria-expanded="false" aria-controls="collapseHistoryPaymentCarrier">
                        <?php echo number_format(
                            $application['actual_payment_Carrier'],
                            0,
                            ',',
                            ' '
                        ); ?> ₽
                        <div class="">
                            <?php if($application['full_payment_date_Carrier']): ?>
                                (<span class="span-date-payment">
                                    <?php echo date('d.m.Y', strtotime($application['full_payment_date_Carrier'])); ?>
                                </span>
                                <?php if($accessChangePayment): ?><i class="bi bi-pencil-square js-change-payment-date"></i> <?php endif; ?>)
                                <?php if($accessChangePayment): ?>
                                    <input type="date" class="form-control input-change-date d-none"
                                           data-side="Carrier" data-id-app="<?php echo $application['id']; ?>"
                                        value="<?php echo date('d.m.Y', strtotime($application['full_payment_date_Carrier'])); ?>">
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                        <?php if(count($application['history_payment_Carrier'])): ?>
                            <i class="bi bi-caret-down-fill text-dark"></i>
                        <?php endif; ?>
                    </div>
                    <div class="collapse" id="collapseHistoryPaymentCarrier-<?php echo $application['id']; ?>">
                        <?php foreach ($application['history_payment_Carrier'] as $history): ?>
                            <div class="expenses small">

                                <?php echo number_format($history['quantity'],0, ',',' ') ; ?> ₽
                                (
                                <span class="span-date-payment-history">
                                    <?php echo date('d.m.Y', strtotime($history['date'])); ?>
                                </span>
                                <i class="bi bi-pencil-square js-change-payment-date-history"></i>)
                                <input type="date" class="form-control input-change-date-history d-none" data-id-payment="<?php echo $history['id']; ?>"
                                       value="<?php echo date('d.m.Y', strtotime($history['date'])); ?>">
                            </div>
                        <?php endforeach; ?>
                    </div>
                </td>
                <td class="table-col-23" data-cost="<?php echo $application['application_walrus']; ?>">
                    <!-- Доп. расходы -->
                    <div class="text-danger" data-bs-toggle="collapse" href="#collapseExpenses<?php echo $application['id']; ?>" role="button" aria-expanded="false" aria-controls="collapseExpenses">
                        - <?php echo number_format(
                            $application['additional_expenses_sum_Carrier'],
                            0,
                            ',',
                            ' '
                        ); ?> ₽
                        <?php if(count($application['additional_expenses'])): ?>
                            <i class="bi bi-caret-down-fill text-dark"></i>
                        <?php endif; ?>
                    </div>
                    <div class="collapse" id="collapseExpenses<?php echo $application['id']; ?>">
                        <?php foreach ($application['additional_expenses'] as $expenses): ?>
                            <div class="expenses small">
                                <?php
                                if(is_float($expenses['sum'])):
                                    echo $expenses['type_expenses'] ."  <br> (" .$expenses['type_payment']
                                        .' - '   .number_format($expenses['sum'],0, ',',' ') ."₽)";
                                else:
                                    echo $expenses['type_expenses'] ." <br> (" .$expenses['type_payment']
                                        .' - '    .$expenses['sum'] ."₽)";
                                endif;
                                ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </td>
                <td class="table-col-24" data-cost="<?php echo $application['application_walrus']; ?>">
                    <!-- Доход -->
                    <div> <?php echo number_format(
                            $application['application_walrus'],
                            0,
                            ',',
                            ' '
                        ); ?> ₽</div>
                </td>

                <td class="table-col-25" data-cost="<?php echo $application['manager_share']; ?>">
                    <!-- Маржа з.п. -->
                    <?php if($application['manager_share'] > 0): ?>
                        <span class="text-success">+ <?php echo number_format(
                                $application['manager_share'],
                                0,
                                ',',
                                ' '
                            ); ?> ₽</span>
                    <?php else: ?>
                        <span class="text-danger"><?php echo number_format(
                                $application['manager_share'],
                                0,
                                ',',
                                ' '
                            ); ?> ₽</span>
                    <?php endif; ?>
                </td>
                <td class="table-col-26" data-cost="<?php echo $application['application_net_profit']; ?>">
                    <!-- Чистая прибыль -->
                    <div> <?php echo number_format(
                            $application['application_net_profit'],
                            0,
                            ',',
                            ' '
                        ); ?> ₽</div>
                </td>
                <td class="table-col-27" data-cost="<?php echo $application['marginality']; ?>">
                    <!-- Маржинальность-->
                    <div> <?php echo $application['marginality']; ?>%</div>
                </td>

            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
        <?php $controller->view('Journal/index-prr-table'); ?>
        <?php $controller->view('Journal/index-ts-table'); ?>
    </div>
    <div class="modal fade" id="modalFillterDate" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
         aria-labelledby="modalFillterDateLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalFillterDate">Выбор периода</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="1c-datepicker-container">

                        <div class="year-picker bg-warning p-4 text-center mb-4" id="year-picker" data-year="2024">
                            <button id="prev-year"><</button>
                            <label for="" id="label-year" class="mx-4">2024</label>
                            <button id="next-year">></button>
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <div class="row m-0">
                                    <div class="col-4 months-picker" data-type="month" data-month="01">январь</div>
                                    <div class="col-4 months-picker" data-type="month" data-month="02">февраль</div>
                                    <div class="col-4 months-picker" data-type="month" data-month="03">март</div>
                                    <div class="col-4 months-picker" data-type="month" data-month="04">апрель</div>
                                    <div class="col-4 months-picker" data-type="month" data-month="05">май</div>
                                    <div class="col-4 months-picker" data-type="month" data-month="06">июнь</div>
                                    <div class="col-4 months-picker" data-type="month" data-month="07">июль</div>
                                    <div class="col-4 months-picker" data-type="month" data-month="08">август</div>
                                    <div class="col-4 months-picker" data-type="month" data-month="09">сентябрь</div>
                                    <div class="col-4 months-picker" data-type="month" data-month="10">октябрь</div>
                                    <div class="col-4 months-picker" data-type="month" data-month="11">ноябрь</div>
                                    <div class="col-4 months-picker" data-type="month" data-month="12">декабрь</div>
                                </div>
                            </div>
                            <div class="col-3" style="border-right: 2px solid green; border-left: 2px solid green;">
                                <div class="months-picker" data-type="quarter" data-quarter="1">1 квартал</div>
                                <div class="months-picker" data-type="quarter" data-quarter="2">2 квартал</div>
                                <div class="months-picker" data-type="quarter" data-quarter="3">3 квартал</div>
                                <div class="months-picker" data-type="quarter" data-quarter="4">4 квартал</div>
                            </div>
                            <div class="col-3">
                                <div class="months-picker" data-type="day">день...</div>
                                <div class="months-picker" data-type="half-year">полугодие</div>
                                <div class="months-picker" data-type="9-months">9 месяцев</div>
                                <div class="months-picker" data-type="year">год</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalSettingTable" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
         aria-labelledby="modalSettingTableLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalSettingTable">Настройка таблицы</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-setting-container" data-status="0">
                        <div class="row mb-4">
                            <div class="col">
                                <div class="form-check">
                                    <input class="form-check-input js-change-table" type="checkbox" name="table-checkbox[]" value="1" id="table-checkbox-1" checked>
                                    <label class="form-check-label" for="table-checkbox-1">
                                        Логист
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input js-change-table" type="checkbox" name="table-checkbox[]" value="2" id="table-checkbox-2" checked>
                                    <label class="form-check-label" for="table-checkbox-2">
                                        № заявки, перевозчик
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input js-change-table" type="checkbox" name="table-checkbox[]" value="3" id="table-checkbox-3" checked>
                                    <label class="form-check-label" for="table-checkbox-3">
                                        № заявки, клиент
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input js-change-table" type="checkbox" name="table-checkbox[]" value="4" id="table-checkbox-4" checked>
                                    <label class="form-check-label" for="table-checkbox-4">
                                        Дата заявки
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input js-change-table" type="checkbox" name="table-checkbox[]" value="5" id="table-checkbox-5" checked>
                                    <label class="form-check-label" for="table-checkbox-5">
                                        Дата погрузки
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input js-change-table" type="checkbox" name="table-checkbox[]" value="6" id="table-checkbox-6" checked>
                                    <label class="form-check-label" for="table-checkbox-6">
                                        Дата разгрузки
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input js-change-table" type="checkbox" name="table-checkbox[]" value="7" id="table-checkbox-7" checked>
                                    <label class="form-check-label" for="table-checkbox-7">
                                        ТТН
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input js-change-table" type="checkbox" name="table-checkbox[]" value="8" id="table-checkbox-8" checked>
                                    <label class="form-check-label" for="table-checkbox-8">
                                        ТТН отправлено
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input js-change-table" type="checkbox" name="table-checkbox[]" value="9" id="table-checkbox-9" checked>
                                    <label class="form-check-label" for="table-checkbox-9">
                                        Название клиента
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input js-change-table" type="checkbox" name="table-checkbox[]" value="10" id="table-checkbox-10" checked>
                                    <label class="form-check-label" for="table-checkbox-10">
                                        Номер счета и дата
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input js-change-table" type="checkbox" name="table-checkbox[]" value="11" id="table-checkbox-11" checked>
                                    <label class="form-check-label" for="table-checkbox-11">
                                        Номер УПД и дата
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input js-change-table" type="checkbox" name="table-checkbox[]" value="12" id="table-checkbox-12" checked>
                                    <label class="form-check-label" for="table-checkbox-12">
                                        Общая сумма
                                    </label>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-check">
                                    <input class="form-check-input js-change-table" type="checkbox" name="table-checkbox[]" value="13" id="table-checkbox-13" checked>
                                    <label class="form-check-label" for="table-checkbox-13">
                                        Сумма без НДС
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input js-change-table" type="checkbox" name="table-checkbox[]" value="14" id="table-checkbox-14" checked>
                                    <label class="form-check-label" for="table-checkbox-14">
                                        НДС
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input js-change-table" type="checkbox" name="table-checkbox[]" value="15" id="table-checkbox-15" checked>
                                    <label class="form-check-label" for="table-checkbox-15">
                                        Фактическая сумма оплаты
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input js-change-table" type="checkbox" name="table-checkbox[]" value="16" id="table-checkbox-16" checked>
                                    <label class="form-check-label" for="table-checkbox-16">
                                        Название перевозчика
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input js-change-table" type="checkbox" name="table-checkbox[]" value="17" id="table-checkbox-17" checked>
                                    <label class="form-check-label" for="table-checkbox-17">
                                        Номер счета и дата
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input js-change-table" type="checkbox" name="table-checkbox[]" value="18" id="table-checkbox-18" checked>
                                    <label class="form-check-label" for="table-checkbox-18">
                                        Номер УПД и дата
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input js-change-table" type="checkbox" name="table-checkbox[]" value="19" id="table-checkbox-19" checked>
                                    <label class="form-check-label" for="table-checkbox-19">
                                        Общая сумма
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input js-change-table" type="checkbox" name="table-checkbox[]" value="20" id="table-checkbox-20" checked>
                                    <label class="form-check-label" for="table-checkbox-20">
                                        Сумма без НДС
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input js-change-table" type="checkbox" name="table-checkbox[]" value="21" id="table-checkbox-21" checked>
                                    <label class="form-check-label" for="table-checkbox-21">
                                        НДС
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input js-change-table" type="checkbox" name="table-checkbox[]" value="22" id="table-checkbox-22" checked>
                                    <label class="form-check-label" for="table-checkbox-22">
                                        Доход
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input js-change-table" type="checkbox" name="table-checkbox[]" value="23" id="table-checkbox-23" checked>
                                    <label class="form-check-label" for="table-checkbox-23">
                                        Маржа з.п.
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input js-change-table" type="checkbox" name="table-checkbox[]" value="24" id="table-checkbox-24" checked>
                                    <label class="form-check-label" for="table-checkbox-24">
                                        Статус по перевозчику
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="btn-table-setting-container">
                            <button class="btn btn-warning" id="reset-all-setting-table" type="button">Сбросить все</button>
                            <button class="btn btn-primary" id="check-all-setting-table" type="button">Выбрать все</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</main>



<ul id="custom-menu-tr-application" data-application_id = "0" class="custom-menu" style="display:none;">
    <?php if($accessChangePayment): ?>
    <li class="js-change-payment-client dropright-menu-container">
        Оплата клиентом <i class="bi bi-caret-right-fill"></i>
        <div class="dropright-menu">
            <div class="item inactive">Ожидается счет</div>
            <div class="item inactive">Запрошен</div>
            <div class="item inactive">Сформирован</div>
            <div class="item inactive">Отправлено клиенту</div>
            <div class="item js-paid-cancel text-danger" data-name="client">Отменить оплату</div>
            <div class="item js-paid-full " data-name="client">Оплачено полностью</div>
            <div class="item single js-change-application-info" data-name="actual-payment-client">Оплачено частично</div>
        </div>
    </li>
    <?php endif; ?>
    <li class="js-change-application-info single" data-name="account-number-client">Ввести номер счета клиента</li>
    <li class="js-change-application-info single" data-name="upd-number-client">Ввести номер УПД клиента</li>
    <?php if($accessChangePayment): ?>
    <li class="js-change-payment-carrier dropright-menu-container">
        Оплата <span id="menu-text-carrier">перевозчику</span> <i class="bi bi-caret-right-fill"></i>
        <div class="dropright-menu">
            <div class="item inactive">Ожидается счет</div>
            <div class="item inactive">Запрошен</div>
            <div class="item inactive">Сформирован</div>
            <div class="item inactive">Отправлено клиенту</div>
            <div class="item js-paid-cancel text-danger" data-name="carrier">Отменить оплату</div>
            <div class="item js-paid-full" data-name="carrier">Оплачено полностью</div>
            <div class="item single js-change-application-info" data-name="actual-payment-carrier">Оплачено частично</div>
        </div>
    </li>
    <?php endif; ?>

    <li class="js-change-additional-expenses single" data-name="additional-expenses" data-application-id="">ОПЛАТА ДОП. ЗАТРАТ</li>
<!--    <li class="js-change-application-info single" data-name="upd-number-carrier">Ввести номер УПД перевозчика</li>-->
</ul>



<!-- Модальное окно -->
<div class="modal fade" id="additionalExpensesModal" tabindex="-1"
     aria-labelledby="additionalExpensesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="additionalExpensesModalLabel">Доп затраты заявки</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
            </div>
            <div class="modal-body">
                <table class="table w-100" style="display: table!important;">
                    <thead>
                        <tr>
                            <td>Название</td>
                            <td>Сумма</td>
                            <td>Оплачено</td>
                        </tr>
                    </thead>
                    <tbody id="pay_additional_expenses_modal_tbody">

                    </tbody>
                </table>
                
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        <?php if($controller->session->get('journalCustomer')): ?>
            $('.js-tab-task[data-id-customer="<?php echo $controller->session->get('journalCustomer') ?>"]').trigger('click');
        <?php endif; ?>

        <?php if($controller->session->get('journalSection')): ?>
            $('.js-tab-task[data-section="<?php echo $controller->session->get('journalSection') ?>"]').trigger('click');
        <?php endif; ?>

        <?php if($controller->session->get('journalTypeApp')): ?>
            $('.js-tab-task[data-type-app="<?php echo $controller->session->get('journalTypeApp') ?>"]').trigger('click');
        <?php endif; ?>
    })
</script>
<script>
    function additionalExpensesPaid( id, typeApp = '' ){
        $.ajax({
            method: 'POST',
            url: '/journal/additional-expense-change-paid',
            data: {id: id, status: 'pay', typeApp: typeApp},
            success: function (response) {
                console.log(response);
                let data = JSON.parse(response);

                if ( data['status'] ){
                    $('button[data-id='+id+']').parent().html(
                        `<button type="button" data-id="`+id+`" class="btn btn-danger"
                            data-type-app="${typeApp}" data-id="${id}" onclick="additionalExpensesCancelPaid(${id},'${typeApp}');">
                            Отменить</button>`
                    );
                }
            }
        })
    }
    function additionalExpensesCancelPaid( id, typeApp = '' ){
        $.ajax({
            method: 'POST',
            url: '/journal/additional-expense-change-paid',
            data: {id: id, status: 'cancel', typeApp: typeApp},
            success: function (response) {
                console.log(response);
                let data = JSON.parse(response);

                if ( data['status'] ){
                    $('button[data-id='+id+']').parent().html(
                        `<button type="button" data-id="`+id+`" data-type-app="${typeApp}"
                            class="btn btn-success" onclick="additionalExpensesPaid(${id},'${typeApp}');">Оплатить</button>`
                    );
                }
            }
        })
    }

    $('.js-change-additional-expenses').click(function () {
        let activeApplication = $('.js-tr-application.active');

        let idActiveApplication = activeApplication.data('id');

        let typeApp = activeApplication.data('type-app');


        if(!typeApp)
            typeApp = '';

        console.log({id: idActiveApplication, typeApp: typeApp})

        $.ajax({
            method: 'POST',
            url: '/journal/get-list-application-additional-expenses',
            data: {id: idActiveApplication, typeApp: typeApp},
            success: function (response) {
                let data = JSON.parse(response);
                console.log('GET-LIST_APP-ADD-EXPENSES')
                console.log(data);

                let newHtml = '';
                data.forEach((item) => {
                    if (item['type_expenses'] === 'Страховка' || item['type_expenses'] === 'Вычет'){
                        return;
                    }

                    let btnHtml = '';


                    if( item['is_paid'] > 0 ){
                        btnHtml = `<button type="button" class="btn btn-danger" data-type-app="${typeApp}" data-id="${item['id']}"
                                        onclick="additionalExpensesCancelPaid(${item['id']}, '${typeApp}');">
                                        Отменить
                                    </button>`;
                    }
                    else{
                        btnHtml = `<button type="button" class="btn btn-success" data-id="${item['id']}" data-type-app="${typeApp}" onclick="additionalExpensesPaid(${item['id']}, '${typeApp}');">Оплатить</button>`;
                    }

                    newHtml += `
                        <tr>
                            <td>${item['type_expenses']}</td>
                            <td class="td-el-between">
                                <span>${item['type_payment']}</span>

                                <span>${item['sum']} ₽</span>
                            </td>
                            <td>${btnHtml}</td>
                        </tr>`;
                });


                if (newHtml.length > 0){
                    $('#pay_additional_expenses_modal_tbody').html(newHtml);
                }
                else{
                    $('#pay_additional_expenses_modal_tbody').html(`<div class="about-empty-text">Доп. затрат нет.</div>`);
                }
                $('#additionalExpensesModal').modal('show');
            }
        });
    });
</script>
<script>
    $(document).ready(function (){
        console.log($('.js-tr-application').length);


    })
    // ajaxLoadApplicationIndex(2);
    // ajaxLoadApplicationIndex(3);
    // ajaxLoadApplicationIndex(6);
    // ajaxLoadApplicationIndex(4);
    // ajaxLoadApplicationIndex(5);


    function ajaxLoadApplicationIndex(applicationSectionJournal = 0, idCustomer = 0){
        $.ajax({
            method: 'POST',
            url: '/journal/ajax/load-application?date-start=2024-12-28&date-end=2025-01-27&application_section_journal=' + applicationSectionJournal,
            data: {
                // 'date-start': '2024-12-28',
                // 'date-end': '2025-01-28',
                // 'application_section_journal': applicationSectionJournal
            },
            success: function (response) {
                // console.log(response);
                $('tbody').append(response);
            }
        })
    }
</script>

<script>
    $('body').on('click','.js-change-payment-date',function () {
        $('td').has(this).find('.input-change-date').removeClass('d-none');
    });
    $('body').on('click','.js-change-payment-date-history',function () {
        $('.expenses').has(this).find('.input-change-date-history').removeClass('d-none');
    });

    $('body').on('change','.input-change-date-history', function () {
        let $this = $(this);
        let id = $this.data('id-payment');
        let value = $this.val();

        let type = $this.data('type');

        $.ajax({
            method: 'POST',
            url: '/journal/ajax/change-payment-date-history',
            data: {id:id, value: value, type: type},
            success: function (response) {
                console.log(response)
                response = JSON.parse(response);

                if(response['status']){
                    $('.expenses').has($this).find('.span-date-payment-history').text(response['date']);
                    $this.addClass('d-none');
                }
                else{
                    alert('Ошибка')
                }
            }
        })
    });

    $('body').on('change','.input-change-date',function () {
        let $this = $(this);
        let id = $this.data('id-app');
        let side = $this.data('side');
        let value = $this.val();
        let type = $this.data('type');

        $.ajax({
            method: 'POST',
            url: '/journal/ajax/change-payment-date',
            data: {id:id, side: side, value: value, type: type},
            success: function (response) {
                console.log(response)
                response = JSON.parse(response);

                if(response['status']){
                    $('td').has($this).find('.span-date-payment').text(response['date']);
                    $this.addClass('d-none');
                }
                else{
                    alert('Ошибка')
                }
            }
        });
        console.log({id:id,side: side, value: value})
    })
</script>
<script>
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
</script>
<script>
    $('.filter-head').click(function (){
        $('.filter-body').removeClass('active');
        $('.header-table-filter').has(this).find('.filter-body').toggleClass('active');
    });

    $('.js-filter-header-table').change(function () {
        if ($(this).val() === '*') {
                $('.filter-body').has(this).find('.js-filter-header-table').not(this).prop('checked', $(this).is(':checked'));

        }
        filterHeaderTable();
    });
    $('.js-search-in-filter').on('input', function () {
        $('.filter-body').has(this).find('.form-check').hide();
        let search = $(this).val();
        $('.filter-body').has(this).find('.form-check:contains(' + search.toUpperCase() + ')').show();

    });
    // todo закрытые фильтра столбцов
    $(document).on('click', function(event) {
        if ($(event.target).closest('.filter-body.active').length || $(event.target).is('.filter-head')){ console.log($(event.target)); return}; // Ignore clicks on the element itself
        console.log($(event.target));
        $('.filter-body').removeClass('active');
    });

    function updateFilterVisibility(normal) {
        // Для каждого фильтра
        $('.js-filter-header-table').each(function() {
            let filterName = $(this).data('name-col');
            let filterValue = $(this).val();

            // Проверяем, есть ли хотя бы одна строка с таким значением, и если нет, скрываем чекбокс фильтра
            let hasActiveValues = false;

            // Проходим по строкам таблицы и проверяем, есть ли строки с таким значением
            normal.each(function() {
                if ($(this).data(filterName) == filterValue && $(this).data('active') !== "0" || filterValue === '*') {
                    hasActiveValues = true;
                }
            });

            if (hasActiveValues) {
                $(this).closest('.form-check').show(); // Показываем фильтр
            } else {
                $(this).closest('.form-check').hide(); // Скрываем фильтр
            }
        });
    }
    // updateFilterVisibility();

    function filterHeaderTable(changeSection = 0){

        let arrayNameCol = [
            'application-number','client-id',
            'carrier-id','app-date',
            'date-actual-unloading','id-user',
            'transportation-cost-client', 'transportation-cost-carrier','taxation-client','taxation-carrier',
            'actual-payment-client', 'actual-payment-carrier', 'app-isset-account-number-client',
            'application-walrus','marginality', 'additional-expenses-gruz','date-unloading',
            'additional-expenses-deduction', 'additional-expenses-point', 'additional-expenses-downtime',
            'additional-expenses-overload'
        ];

        let array = [];

        $.each(arrayNameCol,function (index,item) {
            let value = [];
            let typeFilter = '';
            $('.js-filter-header-table[data-name-col="' + item +'"]:checked').each(function () {
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


        $('.js-tr-application').hide();


        let idCustomer = $('.js-tab-customer.active').data('id-customer');
        let section = $('.js-tab-section.active').data('section');

        let normal = $(`.js-tr-application[data-app-id-customer="${idCustomer}"][data-app-section-journal="${section}"]`);

        // let normal = $(`.js-tr-application`);
        console.log(arrayFilter);
        $.each(arrayFilter, function (index, item) {
            normal = normal.filter(item);
        });

        // normal.find('.section-application').removeClass('d-none');
        normal.show().find('.section-application').removeClass('d-none');

        countAllSum();
        if(changeSection)
            updateFilterVisibility(normal);
    }

    $('#reset-search').click(function () {
        $('#search-journal').val('');
        $('#select-search').val('0');
        $('#search-journal').trigger('input');
    });
    $('#search-journal').on('change', function (){

        let search = $(this).val();
        let selectSearch = $('#select-search').val();
        let find = 'application_number';

        $('.tr-application').hide();
        $('.section-application').addClass('d-none');
        $('.js-tab-task').removeClass('active');

        if(search == ''){
            $('.js-tab-customer[data-id-customer="1"]').addClass('active');
            $('.js-tab-section[data-section="1"]').addClass('active');
            filterTable(1,1);
        }
        else {
            let lengthSearch = 0;
            switch (selectSearch){
                case '0':
                    $('.tr-application:contains(' + search + ')').show();
                    $('.tr-application:contains(' + search + ')').find('.section-application').removeClass('d-none');
                    lengthSearch = $('.tr-application:contains(' + search + ')').find('.section-application').length;
                    break;

                case '1':
                    $('.tr-application .table-col-application-number-carrier:contains(' + search + ')').show().find('.section-application').removeClass('d-none');
                    lengthSearch = $('.tr-application .table-col-application-number-carrier:contains(' + search + ')').find('.section-application').length;
                    break;

                case '2':
                    $('.tr-application .table-col-application-number-client:contains(' + search + ')').show().find('.section-application').removeClass('d-none');
                    lengthSearch = $('.tr-application .table-col-application-number-client:contains(' + search + ')').find('.section-application').length;
                    find = 'application_number_client';
                    break;
                case '3':
                    $('.tr-application .table-col-account-number-client:contains(' + search + ')').show();
                    $('.tr-application .table-col-account-number-client:contains(' + search + ')').find('.section-application').removeClass('d-none');
                    lengthSearch = $('.tr-application .table-col-account-number-client:contains(' + search + ')').find('.section-application').length;
                    find = 'account_number_Client';
                    break;
            }


            if(! lengthSearch){
                $.ajax({
                    method: 'POST',
                    url: '/journal/ajax/search-application-number',
                    data: {search: search,field: find},
                    success: function (response){
                        console.log(response);

                        let datas = JSON.parse(response);

                        if(datas['status']){
                            datas = datas['applications'];
                            console.log(datas)
                            $.each(datas, function (index,item){
                                console.log(item)
                                 let data = item;

                                let tdPaymentCarrier = `
                                    <input id="actual-payment-carrier-${data['id']}" data-name-span="span-actual-payment-carrier"
                                    data-id-application="${data['id']}" data-name-info="actual_payment_Carrier"
                                    class="d-none form-control textarea-change-application-info">
                                    <div  data-bs-toggle="collapse" class="span-actual-payment-carrier" id="span-actual-payment-carrier-${data['id']}"
                                          href="#collapseHistoryPaymentCarrier-${data['id']}"
                                          role="button" aria-expanded="false" aria-controls="collapseHistoryPaymentCarrier">
                                        ${data['actual_payment_Carrier']} ₽
                                        <div class="">
                                                (<span class="span-date-payment">
                                                    ${data['full_payment_date_Carrier']}
                                                </span>
                                                <i class="bi bi-pencil-square js-change-payment-date"></i>)
                                                    <input type="date" class="form-control input-change-date d-none"
                                                           data-side="Carrier" data-id-app="${data['id']}"
                                                        value="${data['full_payment_date_Carrier']}">
                                        </div>
                                            <i class="bi bi-caret-down-fill text-dark"></i>
                                    </div>
                                    <div class="collapse" id="collapseHistoryPaymentCarrier-${data['id']}">`;

                                    $.each(data['history_payment_Carrier'], function (indexPayment, itemPayment) {
                                        tdPaymentCarrier += `
                                            <div class="expenses small">

                                                ${itemPayment['quantity']} ₽
                                                (
                                                <span class="span-date-payment-history">
                                                    ${itemPayment['date']}
                                                </span>
                                                <i class="bi bi-pencil-square js-change-payment-date-history"></i>)
                                                <input type="date" class="form-control input-change-date-history d-none" data-id-payment="${itemPayment['id']}"
                                                       value="${itemPayment['date']}">
                                            </div>
                                        `;
                                    });

                                    tdPaymentCarrier += `</div>`;

                                let tdPaymentClient = `
                                   <input type="number" min="0" id="actual-payment-client-${data['id']}" data-name-span="span-actual-payment-client"
                                              data-id-application="${data['id']}" data-name-info="actual_payment_Client"
                                              class="d-none form-control textarea-change-application-info">

                                    <div data-bs-toggle="collapse" class="span-actual-payment-client"
                                          id="span-actual-payment-client-${data['id']}" href="#collapseHistoryPayment${data['id']}"
                                         role="button" aria-expanded="false" aria-controls="collapseHistoryPayment">
                                        ${data['actual_payment_Client']} ₽
                                        <div class="">
                                                (<span class="span-date-payment">
                                                    ${data['full_payment_date_Client']}
                                                </span>
                                                <i class="bi bi-pencil-square js-change-payment-date"></i>)
                                                <input type="date" class="form-control input-change-date d-none" data-side="Client" data-id-app="${data['id']}"
                                                       value="${data['full_payment_date_Client']}">
                                        </div>
                                            <i class="bi bi-caret-down-fill text-dark"></i>
                                    </div>
                                    <div class="collapse" id="collapseHistoryPayment${data['id']}">`;

                                $.each(data['history_payment_Client'], function (indexPayment, itemPayment) {
                                    tdPaymentClient += `
                                        <div class="expenses small">

                                            ${itemPayment['quantity']} ₽
                                             (
                                                <span class="span-date-payment-history">
                                                    ${itemPayment['date']}
                                                </span>
                                                <i class="bi bi-pencil-square js-change-payment-date-history"></i>)
                                            <input type="date" class="form-control input-change-date-history d-none" data-id-payment="${itemPayment['id']}"
                                                   value="${itemPayment['date']}">
                                        </div>
                                        `;
                                });

                                tdPaymentClient += `</div>`;

                            let htmlNewApplication = ` <tr class="js-tr-application tr-application"
                                data-app-date="${data['date']}"
                                data-app-section-journal="${data['application_section_journal']}"
                                data-app-status-journal="${data['application_status_journal']}"
                                data-app-id-customer="${data['id_customer']}"
                                data-active="0" data-id-application="${data['id']}"
                                data-app-isset-account-number-client=""
                            >
                                <td class="table-col-1">
                                    <!-- Логист -->
                                    ${data['manager']}
                                </td>
                                <td class="table-col-2">
                                    <!-- Номер заявки, перевозчик -->
                                    <a href="/application?id=${data['id']}" target="_blank" style="color: black;text-decoration: unset;">
                                        ${data['application_number']}
                                    </a>
                                </td>
                                <td class="table-col-3">
                                    <!-- Номер заявки, клиента -->
                                    ${data['application_number_Client']}

                                </td>
                                <td class="table-col-4">
                                    <!-- Дата заявки -->
                                    ${data['date']}
                                    <span style="font-size: 12px; color: #0d6efd" class="section-application">
                                        ${data['application_section_journal_name']}
                                    </span>
                                    <div style="font-size: 12px; color: #0d6efd" class="section-application">
                                        ${data['id_customer_name']}
                                    </div>
                                </td>
                                <td class="table-col-5">
                                    <!-- Дата погрузки -->
                                    ${data['date-loading']}
                                </td>
                                <td class="table-col-6">
                                    <!-- Дата разгрузки -->
                                    ${data['date-unloading']}
                                </td>
                                <td class="table-col-6-1">
                                    <!-- Актуальная дата разгрузки -->
                                    ${data['application_date_actual_unloading']}
                                </td>
                                <td class="table-col-7">
                                    <!-- ТТН -->
                                </td>
                                <td class="table-col-8">
                                    <!-- ТТН отправлено -->
                                </td>
                                <td class="table-col-9 col-client ${data['event_class_Client']} "
                                    <?php if($clientPaymentEvent) echo 'data-id-event="'.$clientPaymentEvent['id'] .'"'; ?>
                                    data-type="client">
                                    <!-- Название клиента -->
                                    ${data['client_data']['name']}
                                    <div><span class="inn text-secondary">${data['client_data']['inn']}</span></div>
                                    <div class="">(${data['client_data']['format_work']})</div>
                                </td>
                                <td class="table-col-10 col-client ${data['event_class_Client']} "
                                    <?php if($clientPaymentEvent) echo 'data-id-event="'.$clientPaymentEvent['id'] .'"'; ?>
                                    data-type="client">
                                    <!-- Номер счета и дата  -->
                                    <textarea id="account-number-client-${data['id']}" data-name-span="span-account-number-client"
                                              data-id-application="${data['id']}" data-name-info="account_number_Client"
                                              class="d-none form-control textarea-change-application-info">${data['account_number_Client']}</textarea>
                                    <span class="span-info" id="span-account-number-client-${data['id']}">
                                        ${data['account_number_Client']}
                                    </span>
                                </td>
                                <td class="table-col-11 col-client  ${data['event_class_Client']}" data-type="client">
                                    <!-- Номер УПД и дата -->
                                    <textarea id="upd-number-client-${data['id']}" data-name-span="span-upd-number-client"
                                              data-id-application="${data['id']}" data-name-info="upd_number_Client"
                                              class="d-none form-control textarea-change-application-info">${data['upd_number_Client']}</textarea>
                                    <span class="span-info" id="span-upd-number-client-${data['id']}">
                                        ${data['upd_number_Client']}
                                    </span>
                                </td>
                                <td class="table-col-12 col-client ${data['event_class_Client']} "
                                    <?php if($clientPaymentEvent) echo 'data-id-event="'.$clientPaymentEvent['id'] .'"'; ?>
                                    data-type="client" data-cost="${data['transportation_cost_Client_normal']}">
                                    <!-- Общая сумма -->
                                    <div>${data['transportation_cost_Client']} ₽</div>
                                    <span class="text-secondary">${data['taxation_type_Client']}</span>
                                </td>
                                <td class="table-col-13 col-client ${data['event_class_Client']} "
                                    <?php if($clientPaymentEvent) echo 'data-id-event="'.$clientPaymentEvent['id'] .'"'; ?>
                                    data-type="client" data-cost="${data['transportation_cost_Client_without_VAT']}">
                                    <!-- Сумма без НДС -->
                                    ${data['transportation_cost_Client_without_VAT']} ₽
                                </td>
                                <td class="table-col-14 col-client ${data['event_class_Client']} "
                                    <?php if($clientPaymentEvent) echo 'data-id-event="'.$clientPaymentEvent['id'] .'"'; ?>
                                    data-type="client" data-cost="${data['transportation_VAT_Client']}">
                                    <!-- НДС -->
                                    ${data['transportation_VAT_Client']} ₽
                                </td>
                                <td class="table-col-15 col-client ${data['event_class_Client']} "

                                    data-type="client" data-cost="${data['actual_payment_Client_normal']}">
                                    <!-- Фактическая сумма оплаты -->
                                    ${tdPaymentClient}
                                </td>
                                <td class="table-col-15-1 col-client">
                                    ${data['additional_profit_sum_Client']} ₽
                                </td>
                                <td class="table-col-16 col-carrier ${data['event_class_Carrier']} "
                                    <?php if($carrierPaymentEvent) echo 'data-id-event="'.$carrierPaymentEvent['id'] .'"'; ?>
                                    data-type="carrier">
                                    <!-- Название перевозчика -->
                                    ${data['carrier_data']['name']}
                                    <div><span class="inn text-secondary">${data['carrier_data']['inn']}</span></div>
                                </td>
                                <td class="table-col-19 col-carrier ${data['event_class_Carrier']} "
                                    <?php if($carrierPaymentEvent) echo 'data-id-event="'.$carrierPaymentEvent['id'] .'"'; ?>
                                    data-type="carrier" data-cost="${data['transportation_cost_Carrier_normal']}">
                                    <!-- Общая сумма  -->
                                    <div>${data['transportation_cost_Carrier']} ₽</div>
                                    <span class="text-secondary">${data['taxation_type_Carrier']}</span>
                                </td>
                                <td class="table-col-20 col-carrier ${data['event_class_Carrier']} "
                                    <?php if($carrierPaymentEvent) echo 'data-id-event="'.$carrierPaymentEvent['id'] .'"'; ?>
                                    data-type="carrier" data-cost="${data['transportation_cost_Carrier_without_VAT']}">
                                    <!-- Сумма без НДС -->
                                    ${data['transportation_cost_Carrier_without_VAT']} ₽
                                </td>
                                <td class="table-col-21 col-carrier ${data['event_class_Carrier']} "
                                    <?php if($carrierPaymentEvent) echo 'data-id-event="'.$carrierPaymentEvent['id'] .'"'; ?>
                                    data-type="carrier" data-cost="${data['transportation_VAT_Carrier']}">
                                    <!-- НДС -->
                                    ${data['transportation_VAT_Carrier']} ₽
                                </td>
                                <td class="table-col-22 col-carrier ${data['event_class_Carrier']} "
                                    data-type="carrier" data-cost="${data['actual_payment_Carrier_normal']}">
                                    <!-- Фактическая сумма оплаты -->
                                    ${tdPaymentCarrier}
                                </td>
                                <td class="table-col-23">
                                    ${data['additional_expenses_sum_Carrier']} ₽
                                </td>
                                <td class="table-col-24" data-cost="${data['application_walrus_normal']}">
                                    <!-- Доход -->
                                    <div>${data['application_walrus']}  ₽</div>
                                </td>
                                <td class="table-col-25" data-cost="${data['application_net_profit_normal']}">
                                    <!-- Чистая прибыль -->
                                    <div>${data['application_net_profit']}  ₽</div>
                                </td>
                                <td class="table-col-26" data-cost="${data['manager_share_normal']}">
                                    <!-- Маржа з.п. -->
                                   <span>${data['manager_share']} ₽</span>

                                </td>
                            </tr>`
                            $('tbody').append(htmlNewApplication);
                            })
                            countAllSum();
                        }
                        else{

                        }
                    }
                })
            }

        }
        countAllSum();
        console.log(search);

    });
    $('.js-tab-type-app').click(function () {

        $('.js-tab-type-app').removeClass('active');
        $(this).addClass('active');

        let typeApp = $(this).data('type-app');

        $.ajax({
            method: 'POST',
            url: '/journal/ajax/set-session-filter',
            data: {type: 'type-app', 'type-app': typeApp}
        });

        if(typeApp === 1){
            $('#menu-text-carrier').text('перевозчику');
        }

        if(typeApp === 2){
            $('#menu-text-carrier').text('ПРР компании');
        }

        $('table').addClass('d-none');

        console.log(typeApp)

        $('#table-' + typeApp).removeClass('d-none');
        countAllSum();

    })
    $('.js-tab-customer').click(function (){

        let idCustomer = $(this).data('id-customer');

        $.ajax({
            method: 'POST',
            url: '/journal/ajax/set-session-filter',
            data: {type: 'customer', idCustomer: idCustomer},
            success: function (response) {
                console.log(response)
            }
        });

        $('.js-tab-customer').removeClass('active');
        $(this).addClass('active');
        filterTable();
    });

    $('.js-tab-section').click(function (){
        let section = $(this).data('section');

        $.ajax({
            method: 'POST',
            url: '/journal/ajax/set-session-filter',
            data: {type: 'section', section: section}
        });

        $('.js-tab-section').removeClass('active');
        $(this).addClass('active');
        filterTable();

    });

    const formatter = new Intl.NumberFormat('ru-RU', {
        style: 'decimal', // Можно использовать 'currency' для валюты

    });
    function countAllSum(){
        let arrayNumCol = {12:0, 13:0, 14:0, 15:0, 19:0, 20:0, 21:0, 22:0, 23:0, 24:0, 25:0, 26:0};

        let typeApp = $('.js-tab-type-app.active').data('type-app');

        $(".tr-application:visible").each(function () {
            var $this = $(this);
            $.each(arrayNumCol, function (indexNumCol,itemNumCol) {
                var number = parseInt($this.find('.table-col-' + indexNumCol).data('cost'));

                arrayNumCol[indexNumCol] += number;
            })

        });

        $.each(arrayNumCol, function (indexNumCol,itemNumCol) {
            $('#tr-sum-' + typeApp).find('.table-col-' + indexNumCol).text(formatter.format(itemNumCol) + ' ₽');
        })
        console.log(arrayNumCol)
    }


    function filterTable(numCustomer = 0, numSection = 0, accountNumber = null){
        let idCustomer = $('.js-tab-customer.active').data('id-customer');
        if(numCustomer > 0)
            idCustomer = numCustomer;
        let section = $('.js-tab-section.active').data('section');
        if(numSection > 0)
            section = numSection;

        if(section === 2 || section === 3){
            $('.table-col-condition').removeClass('d-none');
        }
        else{
            $('.table-col-condition').addClass('d-none');
        }

        let condition = {'section-journal': section, 'id-customer': idCustomer}
        if(accountNumber != null) {
            condition = {
                'section-journal': section,
                'id-customer': idCustomer,
                'isset-account-number-client': accountNumber
            }
        }
        else{
            $('#flexCheckIndeterminate-1').trigger('click')
        }

        console.log(condition)
        let search = $('#search-journal').val();
        $('.tr-application').hide();
        let filterText = '';
        $.each(condition, function(index, item){
            console.log(index, item)
            if(item != undefined)
                filterText += `[data-app-${index}="${item}"]`;
        })
        if(search != '') {
            $('.tr-application:contains(' + search + ')').show();
            console.log('.tr-application:contains(' + search + ')' + filterText);
            countAllSum();
            return false;
        }
        else
            $('.tr-application' + filterText).show();
        console.log(filterText);

        $('.js-filter-header-table').prop('checked', false);



        filterHeaderTable(1);

    }
    filterTable(1,1);
    $('.js-filter-account-number-client').change(function () {
        let value = $(this).val();

        switch (value){
            case '0':
                filterTable();
                console.log(value)
                break;

            case '1':
                filterTable(0,0,0);
                break;

            case '2':
                filterTable(0,0,1);
                break;

        }

    });
</script>
<script>
    $('body').on('click','.inn', function (event){
        event.stopPropagation();
        var text = $(this).text();
        let $this = $(this);
        navigator.clipboard.writeText(text).then(function() {
            $('td').has($this).append(`<div class="copy-success">Скопировано</div>`);

            setTimeout(function (){
                $('.copy-success').remove();
            }, 500)

            console.log('Text copied to clipboard');
        }, function(err) {
            console.error('Error copying text to clipboard', err);
        });
    });

    $('.js-paid-cancel').click(function () {
        let activeApplication = $('.js-tr-application.active');
        let arrayIdActiveApplication = [];
        activeApplication.each(function (index) {
            arrayIdActiveApplication.push($(this).data('id-application'));
        })
        let name = $(this).data('name');

        let applicationType = 'default';

        if (activeApplication.hasClass('tr-prr-application')){
            applicationType = 'prr';
        }

        if (activeApplication.hasClass('tr-ts-application')){
            applicationType = 'ts';
        }

        $.ajax({
            method: 'POST',
            url:'/journal/ajax/change-payment-status-cancel',
            data:{id:arrayIdActiveApplication,name: name, applicationType: applicationType},
            success: function (response){
                console.log(response)

                response = JSON.parse(response);

                if(response['result']) {
                    activeApplication.find('.' + name + '-actual-payment').text(0);
                    activeApplication.find('.col-' + name).removeClass('event-payment-0');
                    activeApplication.find('.col-' + name).removeClass('event-payment-1');
                    $("#custom-menu-tr-application").hide();
                    $('.js-tr-application.active').removeClass('active');
                    activeApplication.find('.span-actual-payment-' + name).text(0);
                    console.log('#' + name + '-actual-payment');
                }
            }
        })
    })
    $('.js-paid-full').click(function () {
        let activeApplication = $('.js-tr-application.active');

        console.log(activeApplication);
        let arrayIdActiveApplication = [];
        activeApplication.each(function (index) {
            arrayIdActiveApplication.push($(this).data('id-application'));
        })
        console.log(arrayIdActiveApplication);
        let name = $(this).data('name');

        let applicationType = 'default';

        if (activeApplication.hasClass('tr-prr-application')){
            applicationType = 'prr';
        }

        if (activeApplication.hasClass('tr-ts-application')){
            applicationType = 'ts';
        }

        console.log({id:arrayIdActiveApplication,name: name, applicationType: applicationType});

        $.ajax({
            method: 'POST',
            url:'/journal/ajax/change-payment-status-full',
            data:{id:arrayIdActiveApplication,name: name, applicationType: applicationType},
            success: function (response){
                console.log(response)

                response = JSON.parse(response);

                if(response['result']) {
                    activeApplication.find('.' + name + '-actual-payment').text(response['cost']);
                    activeApplication.find('.span-actual-payment-' + name).text(response['cost']);
                    activeApplication.find('.col-' + name).addClass('event-payment-0');
                    $("#custom-menu-tr-application").hide();
                    $('.js-tr-application.active').removeClass('active');
                    console.log('#' + name + '-actual-payment');
                }
            }
        })

    })

    $('body').click(function(){
        $('.textarea-change-application-info').addClass('d-none');
        $('.span-info').removeClass('d-none')
    });

    $('body').on('keypress','textarea.textarea-change-application-info', function(event) {
        if (event.which === 13) { // Enter key
            $(this).trigger('change');
            $('.js-tr-application').removeClass('active');
        }
    });
    $('body').on('change','.textarea-change-application-info',function () {
        let id = $(this).data('id-application');
        let side = $(this).data('side');
        let nameInfo = $(this).data('name-info');
        let info = $(this).val();

        let typeApp = $(this).data('type');

        if(info === '')
            return false;

        let nameSpan = $(this).data('name-span');

        let $this = $(this);
        $(this).val('');

        $.ajax({
            method:'POST',
            url: '/journal/ajax/change-application-info',
            data: {id: id, side: side, nameInfo: nameInfo, info: info,typeApp: typeApp},
            success: function (response) {
                console.log(response)

                response = JSON.parse(response);

                if(response['status']){
                    $this.addClass('d-none');
                    $this.parent().find('#' + nameSpan + '-' + id).text(info);
                    $this.parent().find('#' + nameSpan + '-' + id).removeClass('d-none');

                    if(nameSpan === 'span-account-number-client'){
                        $('#span-upd-number-client-'+id).text(info)
                    }

                    if(response['event']){
                        if(nameInfo === 'actual_payment_Client'){
                            $('tr').has($this).find('.col-client').addClass('event-payment-0');
                        }
                        if(nameInfo === 'actual_payment_Carrier'){
                            $('tr').has($this).find('.col-carrier').addClass('event-payment-0');
                        }
                    }

                    if(response['cost']){
                        $this.parent().find('#' + nameSpan + '-' + id).text(formatter.format(response['cost']) + ' ₽');
                    }
                }
            }
        })

    })

    $('.js-change-application-info').click(function () {
        let id = $('.js-tr-application.active').data('id-application');
        let name = $(this).data('name');

        $('.js-tr-application.active ' +'#span-' + name +'-' + id).addClass('d-none');

        $('.js-tr-application.active ' +'#' + name + '-' + id).removeClass('d-none');
        $('.js-tr-application.active ' +'#' + name + '-' + id).focus();

        $("#custom-menu-tr-application").hide();
    });
    $('body').on('change','.textarea-change-application-info-acc-number',function () {
        let id = $(this).data('id-application');
        let typeApp = $(this).data('type');
        let value = $(this).val();

        let $this = $(this);

        $.ajax({
            method:'POST',
            url: '/journal/ajax/change-application-account-number',
            data: {id: id, typeApp: typeApp, value: value},
            success: function (response) {
                console.log(response)
                let data = JSON.parse(response);

                if(data['status']){
                    $this.addClass('d-none');
                    $this.parent().find('.span-info').text(data['number']);
                    $this.parent().find('.span-info').removeClass('d-none');
                }
            }
        })

    })

    $('.js-tab-customer').click(function (){
        $('.js-tab-customer').removeClass('active');
        $(this).addClass('active');
        // ajaxLoadApplications();
    });

    $('.js-tab-section').click(function (){
        $('.js-tab-section').removeClass('active');
        $(this).addClass('active');
        // ajaxLoadApplications();
    });
</script>

<script>
    $(document).ready(function() {
        $('body').on("contextmenu", '.js-tr-application', function(event) {
            $('.js-tr-application').removeClass('active');
            $(this).addClass('active');
            event.preventDefault();

            let applicationId = $(this).data('id');

            $("#custom-menu-tr-application").css({
                top: event.pageY + "px",
                left: event.pageX + "px"
            }).show();

            let activeApplication = $('.js-tr-application.active');

            if(activeApplication.length > 1){
                $('.single').addClass('d-none');
            }
            else{
                $('.single').removeClass('d-none');
            }

        });

        $(document).bind("click", function(event) {
            if ($(event.target).parents("#custom-menu-tr-application").length === 0) {
                $("#custom-menu-tr-application").hide();
            }
        });
    });

    $('body').on('click', '.js-change-payment-carrier' ,function () {
        let activeApplication = $('.js-tr-application.active');
        let arrayIdActiveApplication = [];
        activeApplication.each(function (index) {
            arrayIdActiveApplication.push($(this).data('id-application'));
        })

        $.ajax({
            method: 'POST',
            url: '/journal/ajax/change-payment-status-carrier',
            data: {id_applications: arrayIdActiveApplication},
            success: function (response) {
                console.log(response);
                response = JSON.parse(response);

                if(response['result']) {
                    $("#custom-menu-tr-application").hide();
                    $('.js-tr-application.active').removeClass('active');
                }
            }
        })

    })
</script>
<script>
    $(document).on('click', function(event) {
        if ($(event.target).is('.js-tr-application') || $(event.target).is('#custom-menu-tr-application')) return; // Ignore clicks on the element itself
        $('.js-tr-application').removeClass('active');
    });

    $('#journal-excel').click(function () {
        let $this = $(this);
        $this.attr('disabled', true);
        let section = $('.js-tab-section.active').data('section');
        let customer = $('.js-tab-customer.active').data('id-customer');
        let accountFilterNumberClient = $('.js-filter-account-number-client:checked').val();

        let linkGet = '?<?php echo $linkForExcelTable; ?>';

        $.ajax({
            url: '/journal/ajax/excel' + linkGet,
            method: 'POST',
            data: {section: section, customer: customer,accountFilterNumberClient: accountFilterNumberClient},
            success: function (response){
                $this.attr('disabled', false);
                console.log(response);
                let data = JSON.parse(response);

                download_file('journal.xlsx', data['link_file']);
            }
        })
    })

    // var table = new DataTable('#table', {
    //     language: {
    //         url: '//cdn.datatables.net/plug-ins/2.0.3/i18n/ru.json',
    //     },
    //     pageLength: 100,
    //     scrollX: true,
    // });


    $('#custom-menu-tr-application').click(function(event){
        event.stopPropagation();
    });
    $('body').on('click', '.js-tr-application', function(event){
        event.stopPropagation();
        $("#custom-menu-tr-application").hide();
        $('.js-tr-application').removeClass('active');
        $(this).toggleClass('active')

    });

    $('#create-register').click(function(){
        let arrayApplicationRegister = [];
        $('.js-tr-application.active').each(function(index){
            arrayApplicationRegister.push($(this).data('id-application'));
        })

        console.log(arrayApplicationRegister);

        $.ajax({
            url: '/journal/ajax/register',
            method: 'POST',
            data: {applications:arrayApplicationRegister},
            success: function(response){
                // download_file('Реестр.xlsx','../../wp-content/themes/pegas/ajax/journal/register.xlsx');
                console.log(response)
            }
        })
    })

    $('#testTableChange').click(function(){
        $('.table-col-1').hide();
    });

    $('.js-change-table').change(function(){
        let num = $(this).val();

        table.columns(num-1).visible($(this).is(':checked'));
        table.columns.adjust().draw(false);
    });


    $('#fillter-date-start').change(function(){
        let minDate = $(this).val();

        $('#fillter-date-end').attr('min',minDate);
    });

    $('#fillter-date-end').change(function(){
        let maxDate = $(this).val();

        $('#fillter-date-start').attr('max',maxDate);
    });
    $('.fillter-date').hide();

    $('#fillter-date-select').change(function(){
        let select = $(this).val();
        $('.fillter-date').hide();

        if(select > 1 && select < 5){
            $('#fillter-date-' + select).show();
        }
        console.log(select);
    });

    $('#btn-fillter-date').click(function(){
        $('.1c-datepicker-container').removeClass('d-none');
    });

    $('.months-picker').click(function(){
        let type = $(this).data('type');

        let dateStart = '';
        let dateEnd = '';

        let valueYear = $('#year-picker').data('year');


        switch(type){
            case 'month':
                let valueMonth = $(this).data(type);
                let daysInCurrentMonth=new Date(valueYear, valueMonth, 0).getDate();

                dateStart = valueYear + '-' + valueMonth + '-01';
                dateEnd = valueYear + '-' + valueMonth + '-' + daysInCurrentMonth;

                break;
            case 'quarter':
                let valueQuarter = $(this).data(type);

                switch(valueQuarter){
                    case 1:
                        dateStart = valueYear + '-01-01';
                        dateEnd = valueYear + '-03-31';
                        break;
                    case 2:
                        dateStart = valueYear + '-04-01';
                        dateEnd = valueYear + '-06-30';
                        break;
                    case 3:
                        dateStart = valueYear + '-07-01';
                        dateEnd = valueYear + '-09-30';
                        break;
                    case 4:
                        dateStart = valueYear + '-10-01';
                        dateEnd = valueYear + '-12-31';
                        break;
                }



                break;
            case 'day':

                break;
            case 'half-year':
                dateStart = valueYear + '-01-01';
                dateEnd = valueYear + '-06-30';

                break;
            case '9-months':
                dateStart = valueYear + '-01-01';
                dateEnd = valueYear + '-09-30';

                break;
            case 'year':
                dateStart = valueYear + '-01-01';
                dateEnd = valueYear + '-12-31';

                break;

        }

        $('#fillter-date-start').val(dateStart);
        $('#fillter-date-end').val(dateEnd);

        $('.months-picker').removeClass('active');
        $(this).addClass('active');

        // $('.1c-datepicker-container').addClass('d-none');

        console.log(dateStart + " - " + dateEnd);
    });

    $('#prev-year').click(function(){
        let yearNew = $('#year-picker').data('year') - 1;

        $('#year-picker').data('year', yearNew);
        $('#label-year').text(yearNew);

    });

    $('#next-year').click(function(){
        let yearNew = $('#year-picker').data('year') + 1;

        $('#year-picker').data('year', yearNew);
        $('#label-year').text(yearNew);

    });

    $('#btn-fillter-all').click(function(){

        if($('.fillter-all-container').data('status') == 0){

            $('.fillter-all-container').data('status', 1);
            $('.fillter-all-container').removeClass('d-none');


        }
        else{

            $('.fillter-all-container').data('status', 0);
            $('.fillter-all-container').addClass('d-none');
        }

    });

    $('#btn-setting-table').click(function(){
        if($('.table-setting-container').data('status') == 0){
            $('.table-setting-container').removeClass('d-none');
            $('.table-setting-container').data('status', 1);

            $('.fillter-all-container').data('status', 0);
            $('.fillter-all-container').addClass('d-none');
        }
        else{
            $('.table-setting-container').addClass('d-none');
            $('.table-setting-container').data('status', 0);

            $('.fillter-all-container').addClass('d-none');
        }

    });

    $('#reset-all-setting-table').click(function(){
        $('.js-change-table').each(function(index){
            table.columns(index).visible(false);
            table.columns.adjust().draw(false);
        })
        $('.js-change-table').removeAttr('checked');
    });

    

</script>
</body>
