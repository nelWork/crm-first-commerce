<?php
/**
 * @var App\User\Contoller\Common\HomeController $controller
 */
$controller->view('Components/head');
$controller->view('Components/header');
$controller->view('TS/subheader-add-edit');
//dd($tsTransportList);
?>

    <main class="container applications-new__form">
        <div class="form__body__forwarder">
            <div class="application-main d-flex flex-column">
                <div class="title-base">
                    Основное
                </div>
                <div class="row type-applications">
                    <div class="col-6">
                        <div class="mb-3">
                            <label for="basic-url" class="form-label">Номер входящей заявки</label>
                            <div class="input-group">
                                <input type="text" placeholder="Номер входящей заявки" class="form-control form-control-solid"
                                       name="application_number" id="application_number" aria-describedby="basic-addon3 basic-addon4">
                            </div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="mb-3">
                            <label for="basic-url" class="form-label">Дата заявки </label>
                            <div class="input-group">
                                <input type="text" placeholder="Дата заявки" class="form-control form-control-solid"
                                       name="date" id="date" autocomplete="false" aria-describedby="basic-addon3 basic-addon4">
                            </div>
                            <script type="module">
                                new AirDatepicker('#date',{
                                    multipleDatesSeparator: ' - ',
                                    buttons: ['clear']
                                });
                            </script>
                        </div>
                    </div>
                </div>
                <div class="row type-applications d-none">
                    <div class="col-3">
                        <div class="mb-3">
                            <label for="basic-url" class="form-label">Заказчик (наше юр. лицо) <span class="text-danger">*</span></label>

                            <select name="id_customer" id="id_customer" class="form-select select-add-application select">
                                <option value="" disabled selected>Заказчик</option>
                                <?php foreach ($listCustomers as $customer){ ?>
                                    <option value="<?php echo $customer['id']; ?>" <?php if($customer['id'] == 1) echo 'selected';?>><?php echo $customer['name']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row form-main__select-cont">
                    <div class="col-6">
                        <div class="mb-3">
                            <label for="basic-url" class="form-label">Экспедитор <span class="text-danger">*</span></label>
                            <div class="d-flex gap-5 forwarder_input_wrap">
                                <select name="id_forwarder" id="id_forwarder" class="form-select select-add-application  select">
                                    <option value="0" disabled selected>Экспедитор</option>
                                    <?php foreach ($listForwarders as $forwarder){ ?>
                                        <option value="<?php echo $forwarder['id']; ?>"><?php echo $forwarder['name'] .' ' .$forwarder['inn']; ?></option>
                                    <?php } ?>
                                </select>
                                <script>
                                    var choicesForwarder = new Choices('#id_forwarder', {allowHTML: true,});
                                </script>
                            </div>
                        </div>
                    </div>
                    <div class="col-2 d-flex align-items-end mb-3">
                        <button class="btn btn-add-light" data-bs-toggle="modal" data-bs-target="#modalForwarder" >
                            Добавить
                        </button>
                    </div>
                    <div class="col-2 d-flex align-items-end mb-3">
                        <button class="btn btn-add-light d-none" data-bs-toggle="modal"
                                data-bs-target="#modalForwarderEdit" id="modal_forwarder_edit">
                            Редактировать
                        </button>
                    </div>
                </div>
                <div class="forwarder-info info">
                    <div class="inn mb-4">
                        <span class="gray">ИНН</span>
                        <span id="forwarder_info_inn" class="forwarder_info_value"></span>
                    </div>
                    <div class="legal_address mb-4">
                        <span class="gray">Юридический адрес</span>
                        <span id="forwarder_info_legal_address" class="forwarder_info_value"></span>
                    </div>
                    <div class="info mb-4">
                        <span class="gray">Телефон, код в АТИ, конт. лицо, почта</span>
                        <span id="forwarder_info_info" class="forwarder_info_value"></span>
                    </div>
                </div>
            </div>

            <div class="application-transportation mt-5 d-flex flex-column gap-5">
                <div class="title-base">
                    Транспортировка
                </div>
                <div class="btn-wrap">
                    <button class="btn btn-add-light" data-bs-toggle="modal" data-bs-target="#modalMarshrut" onclick="$('#form-add-marshrut-side').val('forwarder');">Добавить маршрут</button>
                </div>
                <div class="marshrut-list" id="marshrut-list">

                </div>
                <!--        <button class="btn btn-primary" id="collect-marshrut">Собрать</button>-->

                <div class="application-transportation-data row">
                    <div class="col-7">
                        <div class="d-flex flex-column gap-3">
                    <span>
                        Характер груза <span class="text-danger">*</span>
                    </span>
                            <div class="form-floating" style="height: 100%">
                                <textarea class="form-control" placeholder="Характер груза" id="nature_cargo" style="height: 100px"></textarea>
                            </div>
                            <script>
                                let Editor_nature_cargo;
                                ClassicEditor
                                    .create( document.querySelector( '#nature_cargo' ),
                                        {
                                            toolbar: [  'undo', 'redo', 'Heading', 'Link', 'bold', 'italic', 'numberedList', 'bulletedList' ]
                                        })
                                    .then( editor => {
                                        Editor_nature_cargo = editor;
                                        console.log( editor );
                                    } )
                                    .catch( error => {
                                        console.error( error );
                                    } );
                            </script>
                        </div>
                    </div>
                    <div class="col-5">
                        <div class="d-flex flex-column gap-4">
                            <div class="">
                                <label for="basic-url" class="form-label">Мест <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text" placeholder="Мест" class="form-control form-control-solid" name="place" id="place" aria-describedby="basic-addon3 basic-addon4">
                                </div>
                            </div>
                            <div class="">
                                <label for="basic-url" class="form-label">Вес <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text" placeholder="Вес" class="form-control form-control-solid weight-input" name="weight" id="weight" aria-describedby="basic-addon3 basic-addon4">
                                </div>
                            </div>
                            <div class="">
                                <label for="basic-url" class="form-label">Реф режим <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text" placeholder="Реф режим" class="form-control form-control-solid" name="ref_mode-mode" id="ref_mode" aria-describedby="basic-addon3 basic-addon4">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="application-driver mt-5 d-flex flex-column">
                <div class="title-base">
                    Водитель
                </div>
                <div class="application-driver-data row">
                    <div class="col-6">
                        <div class="driver_forwarder_wrap">
                            <label for="basic-url" class="form-label">ФИО <span class="text-danger">*</span></label>

                            <select name="id_driver" id="id_driver" class="form-select united-select select  select-add-application mb-0">
                                <option value="0" disabled selected>Водитель</option>
                                <?php foreach ($listDrivers as $driver){ ?>
                                    <option value="<?php echo $driver['id']; ?>"><?php echo $driver['name']; ?></option>
                                <?php } ?>
                            </select>
                            <script>
                                let choicesDriverForwarder = new Choices('#id_driver', {allowHTML: true,});
                            </script>
                        </div>
                    </div>
                    <div class="col-4 d-flex flex-column justify-content-end">
                        <div class="btn-wrap" style="margin-bottom: 24px;">
                            <button class="btn btn-add-light add-driver-btn-modal" data-bs-toggle="modal" data-bs-target="#modalDriver">
                                Добавить
                            </button>
                            <button class="btn btn-add-light edit-driver-btn-modal" data-bs-toggle="modal" data-bs-target="#modalEditDriver">
                                Редактировать
                            </button>
                        </div>
                    </div>
                </div>

                <div class="driver-info info">
                    <div class="inn mb-4">
                        <span class="gray">Водитель</span>
                        <span id="driver_info_name_forwarder" class="driver_info_value"></span>
                    </div>
                    <div class="legal_address mb-4">
                        <span class="gray">Водительское удостоверение</span>
                        <span id="driver_info_licence_forwarder" class="driver_info_value"></span>
                    </div>
                    <div class="info mb-4">
                        <span class="gray">Паспортные данные</span>
                        <span id="driver_info_passport_forwarder" class="driver_info_value"></span>
                    </div>
                    <div class="info">
                        <span class="gray">Телефон</span>
                        <span id="driver_info_phone_forwarder" class="driver_info_value"></span>
                    </div>

                </div>
            </div>

            <div class="application-transport mt-5 d-flex flex-column gap-5">
                <div class="title-base">
                    Транспорт
                </div>
                <div class="row form-main__select-cont">
                    <div class="col-6">
                        <div class="mb-3">
                            <label for="basic-url" class="form-label">ТС <span class="text-danger"></span></label>
                            <div class="d-flex gap-5 forwarder_transport_list_input_wrap">
                                <select name="id_ts_transport" id=id_ts_transport class="form-select select-ts-transport select">
                                    <option value="0" disabled selected>ТС</option>
                                    <?php foreach ($tsTransportList as $transport){ ?>
                                        <option value="<?php echo $transport['id']; ?>">
                                            <?php echo $transport['car_brand'] .' (' .$transport['government_number']
                                                .') П/П(' .$transport['pp'] .') ' .$transport['type_transport'] .' ' .$transport['type_carcase']; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-2 d-flex align-items-end mb-3">
                        <button class="btn btn-add-light" data-bs-toggle="modal" data-bs-target="#modalTransport" >
                            Добавить
                        </button>
                    </div>
<!--                    <div class="col-2 d-flex align-items-end mb-3">-->
<!--                        <button class="btn btn-add-light d-none" data-bs-toggle="modal"-->
<!--                                data-bs-target="#modalForwarderEdit" id="modal_forwarder_edit">-->
<!--                            Редактировать-->
<!--                        </button>-->
<!--                    </div>-->
                </div>
                <div class="application-transport-data row">
                    <div class="col-3">
                        <div class="">
                            <label for="basic-url" class="form-label">Марка <span class="text-danger">*</span></label>
                            <div class="input-group input-group-without-choices__inner">
                                <input type="text" placeholder="Марка" class="form-control form-control-solid united-input js-trigger-input-choices"
                                       name="car_brand" id="car_brand" aria-describedby="basic-addon3 basic-addon4">
                            </div>
                        </div>
                    </div>

                    <div class="col-3">
                        <div class="">
                            <label for="basic-url" class="form-label">Гос номер <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="text" placeholder="Гос номер" class="form-control form-control-solid"
                                       name="government_number" id="government_number" aria-describedby="basic-addon3 basic-addon4">
                            </div>
                        </div>
                    </div>

                    <div class="col-3">
                        <div class="">
                            <label for="basic-url" class="form-label">П/П</label>
                            <div class="input-group">
                                <input type="text" placeholder="П/П" class="form-control form-control-solid"
                                       name="semitrailer" id="semitrailer" aria-describedby="basic-addon3 basic-addon4">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="application-transport-data row">
                    <div class="col-3">
                        <div class="">
                            <label for="basic-url" class="form-label">Тип транспорта <span class="text-danger">*</span></label>
                            <div class="input-group input-group-without-choices__inner">
                                <input type="text" placeholder="Тип транспорта" class="form-control form-control-solid united-input js-trigger-input-choices"
                                       name="type_transport" id="type_transport" aria-describedby="basic-addon3 basic-addon4">
                            </div>
                        </div>
                    </div>

                    <div class="col-3">
                        <div class="">
                            <label for="basic-url" class="form-label">Тип кузова</label>
                            <div class="input-group input-group-without-choices__inner">
                                <input type="text" placeholder="Тип кузова" class="form-control form-control-solid united-input js-trigger-input-choices"
                                       name="type_carcase" id="type_carcase" aria-describedby="basic-addon3 basic-addon4">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="application-special-conditions mt-5 d-flex flex-column gap-5 d-none">
                <div class="title-base">
                    Особые условия
                </div>
                <div class="form-floating" style="height: 100%">

                    <textarea class="form-control" placeholder="Особые условия" id="special_conditions" style="height: 100%"
                              name="special_conditions"></textarea>
                </div>
                <script>
                    let Editor_special_conditions;
                    ClassicEditor
                        .create( document.querySelector( '#special_conditions' ),
                            {
                                toolbar: [  'undo', 'redo', 'Heading', 'Link', 'bold', 'italic', 'numberedList', 'bulletedList' ]
                            })
                        .then( editor => {
                            Editor_special_conditions = editor;
                            console.log( editor );
                        } )
                        .catch( error => {
                            console.error( error );
                        } );
                </script>
            </div>

            <div class="application-cost mt-5 d-flex flex-column gap-5">
                <div class="title-base">
                    Стоимость
                </div>
                <div class="btn-wrap">
                    Условия оплаты
                </div>
                <div class="application-cost-data row">
                    <div class="col-7">
                        <div class="d-flex flex-column gap-3">
                            <div class="form-floating" style="height: 100%">
                                <textarea class="form-control" placeholder="" id="terms_payment" style="height: 100%"></textarea>
                                <div class="btn-plus-condition d-none">
                                    <span class="js-trigger-select-choices">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                          <path opacity="0.3" d="M17 2H7C4.23858 2 2 4.23858 2 7V17C2 19.7614 4.23858 22 7 22H17C19.7614 22 22 19.7614 22 17V7C22 4.23858 19.7614 2 17 2Z" fill="black"></path>
                                          <path d="M10.8892 6.8033V16.8033C10.8892 17.3556 11.3369 17.8033 11.8892 17.8033C12.4414 17.8033 12.8892 17.3556 12.8892 16.8033V6.8033C12.8892 6.25101 12.4414 5.8033 11.8892 5.8033C11.3369 5.8033 10.8892 6.25101 10.8892 6.8033Z" fill="black"></path>
                                          <path d="M17.0104 10.9247H7.01038C6.45809 10.9247 6.01038 11.3724 6.01038 11.9247C6.01038 12.477 6.45809 12.9247 7.01038 12.9247H17.0104C17.5627 12.9247 18.0104 12.477 18.0104 11.9247C18.0104 11.3724 17.5627 10.9247 17.0104 10.9247Z" fill="black"></path>
                                        </svg>
                                    </span>
                                    <!--                        <div class="dropdown">-->
                                    <!---->
                                    <!--                            <button class="btn btn-add-light" type="button" data-bs-toggle="dropdown" aria-expanded="false">+</button>-->
                                    <!--                            <div class="dropdown-menu p-4 text-body-secondary">-->
                                    <div class="terms-payment_forwarder-wrap">
                                        <select name="select-terms-payment" id="select-terms-payment" class="form-select select-chosen united-select" data-placeholder="">
                                            <?php foreach ($listTermsPayment as $term){ ?>
                                                <option value="<?php echo $term['id'] ?>">
                                                    <?php echo $term['name'] ;?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <!--                            </div>-->
                                    <!--                        </div>-->
                                </div>
                            </div>
                            <script>
                                let Editor_terms_payment;
                                ClassicEditor
                                    .create( document.querySelector( '#terms_payment' ),
                                        {
                                            toolbar: [  'undo', 'redo', 'Heading', 'Link', 'bold', 'italic', 'numberedList', 'bulletedList' ]
                                        })
                                    .then( editor => {
                                        Editor_terms_payment = editor;
                                        console.log( editor );
                                    } )
                                    .catch( error => {
                                        console.error( error );
                                    } );
                            </script>
                        </div>
                    </div>
                    <div class="col-5">
                        <div class="d-flex flex-column gap-4">
                            <div class="">
                                <label for="basic-url" class="form-label">Стоимость перевозки, ₽ <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text" placeholder="Стоимость перевозки" class="form-control form-control-solid"
                                           name="transportation_cost" id="transportation_cost" aria-describedby="basic-addon3 basic-addon4">
                                </div>
                            </div>
<!--                            <div class='d-none'>-->
<!--                                <label for="basic-url" class="form-label">Стоимость груза, ₽</label>-->
<!--                                <div class="input-group">-->
<!--                                    <input type="text" placeholder="Стоимость груза" class="form-control form-control-solid"-->
<!--                                           name="cost_cargo" id="cost_cargo" aria-describedby="basic-addon3 basic-addon4">-->
<!--                                </div>-->
<!--                            </div>-->
                            <div class="">
                                <label for="basic-url" class="form-label">Вид налогообложения <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <!--                            <input type="text" placeholder="Вид налогообложения" class="form-control form-control-solid" name="taxation-type" id="taxation-type-input_carrier" aria-describedby="basic-addon3 basic-addon4">-->

                                    <select name="taxation_type" id="taxation_type" class="form-select select-type-taxation select">
                                        <option value="0" disabled selected>Вид налогообложения</option>
                                        <?php foreach ($listTypesTaxation as $typeTaxation){ ?>
                                            <option value="<?php echo $typeTaxation["name"]; ?>"><?php echo $typeTaxation['name']; ?></option>
                                        <?php } ?>
                                    </select>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="application-additional-costs mt-5 d-flex flex-column gap-5">
                <div class="title-base">
                    Доп затраты
                </div>
                <div>
                    <button class="btn btn-add-light"
                            data-bs-toggle="modal" data-bs-target="#modalExpenses" onclick="$('#expenses-type-for').val(0);">Доп затраты</button>
                </div>
                <div id="expenses-forwarder-block_forwarder" class="w-75 mb-4">
                    <div class="expenses-item expenses-item_1" data-type-for="0" data-type-expenses="Страховка" data-sum="500" data-type-payment="Б/НДС" data-comment="">
                        <div class="row">
                            <div class="col">
                                <label for="">Вид затрат</label>
                                <span class="expense-type-expenses_1">Страховка</span>
                            </div>
                            <div class="col">
                                <label for="">Сумма</label>
                                <span class="expense-sum_1">500</span>
                            </div>
                            <div class="col">
                                <label for="">Вид налогообложения</label>
                                <span class="expense-type-payment_1">Б/НДС</span>
                            </div>
                            <div class="col">
                                <label for="">Комментарий</label>
                                <span class="expense-comment_1"></span>
                            </div>
                            <div class="col">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="application-additional-costs mt-5 d-flex flex-column gap-5">
                <div class="title-base">
                    Доп прибыль
                </div>
                <div>
                    <button class="btn btn-add-light"
                            data-bs-toggle="modal" data-bs-target="#modalAdditionalProfit">Доп прибыль</button>
                </div>
                <div id="additional_profit" class="w-75 mb-4">
                </div>
            </div>
        </div>
        <div class="" style="padding-left: 20px;">
            <div class="form__header-menu">

                <button class="btn btn-add-light application_add" id="" style="margin-left: auto; margin-bottom: 7px;">
                    Добавить
                </button>
            </div>
        </div>
    </main>


    <div class="modal modal-add-application fade" id="modalMarshrut" tabindex="-1" aria-labelledby="modalMarshrut" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title title-base" id="exampleModalLabel">Маршрут</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                </div>
                <div class="modal-body">
                    <form action="" id="form-add-marshrut">
                        <div class="w-25">
                            <input class="d-none" id="form-add-marshrut-side" value="forwarder">
                            <select name="direction" id="direction-input" class="form-select  mb-4 direction-input" required>
                                <option value="1">Откуда</option>
                                <option value="0">Куда</option>
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-6 mb-4" style="position: relative">
                                <label for="" class="label mb-2">Откуда <span class="text-danger">*</span></label>
                                <input type="text" name="city" city-api="true" class="form-control form-control-solid" placeholder="Выберите город" required>
                                <div id="container-city-api" class="custom-dropdown">

                                </div>
                            </div>
                            <div class="col-6 mb-4">
                                <label for="" class="mb-2">Контактное лицо</label>
                                <input type="text" name="contact" class="form-control form-control-solid" placeholder="Контактное лицо">
                            </div>
                            <div class="col-6 mb-4">
                                <label for="" class="mb-2">Адрес <span class="text-danger">*</span></label>
                                <input type="text" name="address" class="form-control form-control-solid" placeholder="Адрес" required>
                            </div>
                            <div class="col-6 mb-4">
                                <label for="" class="mb-2">Номер телефона</label>
                                <input type="text" name="phone" class="form-control form-control-solid inputmask-phone" placeholder="Номер телефона" >
                            </div>
                            <div class="col-6 mb-4">
                                <label for="" class="mb-2">Дата <span class="text-danger">*</span></label>
                                <input autocomplete="off" type="text" name="date" class="form-control form-control-solid" id="modal_input_phone_number_1" placeholder="Дата" required>
                                <script type="module">
                                    new AirDatepicker('#modal_input_phone_number_1',{
                                        range: true,
                                        multipleDatesSeparator: ' - ',
                                        buttons: ['clear']
                                    });
                                </script>
                            </div>
                            <div class="col-6 mb-4">
                                <label for="" class="mb-2">Способ погрузки <span class="text-danger">*</span></label>
                                <input type="text" name="loading_method" class="form-control form-control-solid" placeholder="Способ погрузки" required>
                            </div>
                            <div class="col-6 mb-4">
                                <label for="" class="mb-2">Время</label>
                                <div class="d-flex">
                                    <input type="text" name="time" class="form-control form-control-solid transportation-time-input"
                                           id="transportation-time-input" placeholder="Время">
                                    <select class="transportation-time-select form-select">
                                        <option value = 0 >По умолчанию</option>
                                        <option value = 1 >Время согласовать с менеджером</option>
                                        <option value = 2 >Круглосуточно</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <input type="submit" style="display: none" id="btn-form-add-marshrut">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light btn-close-modal" data-bs-dismiss="modal">Отменить</button>
                    <button type="button" class="btn btn-add-light" id="add-marshrut">Добавить</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal modal-add-application fade" id="modalEditMarshrut" tabindex="-1" aria-labelledby="modalEditMarshrut" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title title-base" id="exampleModalLabel">Маршрут</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                </div>
                <div class="modal-body">
                    <form action="" id="form-edit-marshrut">
                        <input type="number" class="d-none" id="marshrut_id_form-edit-modal">
                        <div class="w-25">
                            <input class="d-none" id="form-edit-marshrut-side" value="forwarder">
                            <select name="direction" id="select-direction" class="form-select mb-4 direction-input" required>
                                <option value="1">Откуда</option>
                                <option value="0">Куда</option>
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-6 mb-4">
                                <label for="" class="label mb-2">Откуда <span class="text-danger">*</span></label>
                                <input type="text" name="city" city-api="true" id="input-city-modal-edit" class="form-control form-control-solid" placeholder="Выберите город" required>
                                <div class="custom-dropdown">

                                </div>
                            </div>
                            <div class="col-6 mb-4">
                                <label for="" class="mb-2">Контактное лицо</label>
                                <input type="text" name="contact" id="input-contact-modal-edit" class="form-control form-control-solid" placeholder="Контактное лицо">
                            </div>
                            <div class="col-6 mb-4">
                                <label for="" class="mb-2">Адрес <span class="text-danger">*</span></label>
                                <input type="text" name="address" id="input-address-modal-edit" class="form-control form-control-solid" placeholder="Адрес" required>
                            </div>
                            <div class="col-6 mb-4">
                                <label for="" class="mb-2">Номер телефона</label>
                                <input type="text" name="phone" id="input-phone-modal-edit" class="form-control form-control-solid inputmask-phone" placeholder="Номер телефона" >
                            </div>
                            <div class="col-6 mb-4">
                                <label for="" class="mb-2">Дата <span class="text-danger">*</span></label>
                                <input autocomplete="off" type="text" name="date" id="input-date-modal-edit" class="form-control form-control-solid" placeholder="Дата" required>
                                <script type="module">
                                    new AirDatepicker('#input-date-modal-edit',{
                                        range: true,
                                        multipleDatesSeparator: ' - ',
                                        buttons: ['clear']
                                    });
                                </script>
                            </div>
                            <div class="col-6 mb-4">
                                <label for="" class="mb-2">Способ выгрузки <span class="text-danger">*</span></label>
                                <input type="text" name="loading_method" id="input-loading_method-modal-edit" class="form-control form-control-solid" placeholder="Способ выгрузки" required>
                            </div>
                            <div class="col-6 mb-4">
                                <label for="" class="mb-2">Время <span class="text-danger">*</span></label>
                                <input type="text" name="time" id="input-time-modal-edit" class="form-control form-control-solid" placeholder="Время" required>
                            </div>
                        </div>
                        <input type="submit" style="display: none" id="btn-form-add-marshrut">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light btn-close-modal" data-bs-dismiss="modal">Отменить</button>
                    <button type="button" class="btn btn-add-light" id="edit-marshrut">Сохранить</button>
                </div>
            </div>
        </div>
    </div>


    <div class="modal modal-add-application fade" id="modalExpenses"
         tabindex="-1" aria-labelledby="modalExpenses" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title title-base" id="exampleModalLabel">Доп затраты</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                </div>
                <div class="modal-body">
                    <form action="" id="form-expenses">
                        <input type="hidden" name="type_for" value="0" id="expenses-type-for" required>
                        <div class="mb-2">
                            <label for="" class="mb-2">Вид затрат <span class="text-danger">*</span></label>
                            <select name="" id="select-type_expenses" class="form-select">
                                <option value="Грузчики">Грузчики</option>
                                <!--                                <option value="Страховка">Страховка</option>-->
                                <option value="Доп. точка ">Доп. точка </option>
                                <option value="Простои">Простои</option>
                                <option value="Перегруз">Перегруз</option>
                                <option value="Вычет">Вычет</option>
                                <option value="">Свободное поле</option>
                            </select>
                            <input type="text" name="type_expenses" id="input-type_expenses" placeholder="Вид затрат"
                                   class="form-control form-control-solid mt-2 d-none" value="Грузчики" required>
                        </div>
                        <div class="mb-2">
                            <label for="" class="mb-2">Сумма <span class="text-danger">*</span></label>
                            <input type="text" name="sum" placeholder="Сумма" id="input-sum_expenses" class="form-control form-control-solid" required>
                        </div>
                        <div class="mb-2">
                            <label for="" class="mb-2">Вид налогообложения <span class="text-danger">*</span></label>
                            <select name="type_payment" id="select-type_payment_expense" class="form-select">
                                <option value="С НДС">С НДС</option>
                                <option value="Б/НДС">Б/НДС</option>
                                <option value="НАЛ">НАЛ</option>
                            </select>
                            <!--                            <input type="text" name="type_payment" placeholder="Вид оплаты" class="form-control form-control-solid" required>-->
                        </div>
                        <div class="mb-2">
                            <label for="" class="mb-2">Комментарий <span class="text-danger">*</span></label>
                            <input type="text" name="comment" placeholder="Комментарий" class="form-control form-control-solid" required>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light btn-close-modal" data-bs-dismiss="modal">Отменить</button>
                    <button type="button" class="btn btn-add-light" id="add-expenses">Добавить</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal modal-add-application fade" id="modalAdditionalProfit"
         tabindex="-1" aria-labelledby="modalAdditionalProfit" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title title-base" id="exampleModalLabel">Доп прибыль</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                </div>
                <div class="modal-body">
                    <form action="" id="form-additional_profit">
                        <div class="mb-2">
                            <label for="select-type_additional_profit" class="mb-2">Вид доп прибыли <span class="text-danger">*</span></label>
                            <select name="" id="select-type_additional_profit" class="form-select mb-1">
                                <option value="Грузчики">Грузчики</option>
                                <option value="Доп. точка">Доп. точка</option>
                                <option value="Простои">Простои</option>
                                <option value="Перегруз">Перегруз</option>
                                <option value="">Свободное поле</option>
                            </select>
                            <input type="text" name="type" id="input-type_additional_profit" placeholder="Вид доп прибыли"
                                   class="form-control d-none form-control-solid mt-2" value="Грузчики" required>
                        </div>
                        <div class="mb-2">
                            <label for="" class="mb-2">Сумма <span class="text-danger">*</span></label>
                            <input type="text" name="sum" placeholder="Сумма" id="input-sum_additional_profit" class="form-control form-control-solid" required>
                        </div>
                        <div class="mb-2">
                            <label for="" class="mb-2">Вид налогообложения <span class="text-danger">*</span></label>
                            <select name="type_payment" id="select-type_payment_additional_profit" class="form-select">
                                <option value="С НДС">С НДС</option>
                                <option value="Б/НДС">Б/НДС</option>
                                <option value="НАЛ">НАЛ</option>
                            </select>
                            <!--                            <input type="text" name="type_payment" placeholder="Вид оплаты" class="form-control form-control-solid" required>-->
                        </div>
                        <div class="mb-2">
                            <label for="" class="mb-2">Комментарий <span class="text-danger">*</span></label>
                            <input type="text" name="comment" placeholder="Комментарий"
                                   class="form-control form-control-solid" required>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light btn-close-modal" data-bs-dismiss="modal">Отменить</button>
                    <button type="button" class="btn btn-add-light" id="add-additional_profit">Добавить</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal modal-add-application fade" id="modalEditExpenses"
         tabindex="-1" aria-labelledby="modalEditExpenses" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title title-base" id="exampleModalLabel">Доп затраты</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                </div>
                <div class="modal-body">
                    <form action="" id="edit-expenses-form">
                        <input type="hidden" id="edit-expense-id">
                        <div class="mb-2">
                            <label for="" class="mb-1">Вид затрат <span class="text-danger">*</span></label>
                            <input type="text" name="type_expenses" id="edit-expense-type_expenses" placeholder="Вид затрат" class="form-control form-control-solid" required>
                        </div>
                        <div class="mb-2">
                            <label for="" class="mb-1">Сумма <span class="text-danger">*</span></label>
                            <input type="text" name="sum" id="edit-expense-sum" placeholder="Сумма" class="form-control form-control-solid" required>
                        </div>
                        <div class="mb-2">
                            <label for="" class="mb-1">Вид оплаты <span class="text-danger">*</span></label>
                            <select name="type_payment" id="edit-expense-type_payment" class="form-select form-select-solid">
                                <option value="С НДС">С НДС</option>
                                <option value="Б/НДС">Б/НДС</option>
                                <option value="НАЛ">НАЛ</option>
                            </select>
                            <!--                            <input type="text" name="type_payment" id="edit-expense-type_payment" placeholder="Вид оплаты" class="form-control form-control-solid" required>-->
                        </div>
                        <div class="mb-2">
                            <label for="" class="mb-1">Комментарий <span class="text-danger">*</span></label>
                            <input type="text" name="comment" id="edit-expense-comment" placeholder="Комментарий" class="form-control form-control-solid" required>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light btn-close-modal" data-bs-dismiss="modal">Отменить</button>
                    <button type="button" class="btn btn-add-light" id="edit-expenses">Сохранить</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal modal-add-application fade" id="modalEditAdditionalProfit"
         tabindex="-1" aria-labelledby="modalEditAdditionalProfit" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title title-base" id="exampleModalLabel">Доп прибыль</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                </div>
                <div class="modal-body">
                    <form action="" id="edit-additional_profit-form">
                        <input type="hidden" id="edit-additional_profit-id">
                        <div class="mb-2">
                            <label for="" class="mb-1">Вид затрат <span class="text-danger">*</span></label>
                            <input type="text" name="type" id="edit-additional_profit-type"
                                   placeholder="Вид затрат" class="form-control form-control-solid" required>
                        </div>
                        <div class="mb-2">
                            <label for="" class="mb-1">Сумма <span class="text-danger">*</span></label>
                            <input type="text" name="sum" id="edit-additional_profit-sum" placeholder="Сумма"
                                   class="form-control form-control-solid" required>
                        </div>
                        <div class="mb-2">
                            <label for="" class="mb-1">Вид оплаты <span class="text-danger">*</span></label>
                            <select name="type_payment" id="edit-additional_profit-type_payment" class="form-select form-select-solid">
                                <option value="С НДС">С НДС</option>
                                <option value="Б/НДС">Б/НДС</option>
                                <option value="НАЛ">НАЛ</option>
                            </select>
                            <!--                            <input type="text" name="type_payment" id="edit-additional_profit-type_payment"-->
                            <!--                                   placeholder="Вид оплаты" class="form-control form-control-solid" required>-->
                        </div>
                        <div class="mb-2">
                            <label for="" class="mb-1">Комментарий <span class="text-danger">*</span></label>
                            <input type="text" name="comment" id="edit-additional_profit-comment"
                                   placeholder="Комментарий" class="form-control form-control-solid" required>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light btn-close-modal" data-bs-dismiss="modal">Отменить</button>
                    <button type="button" class="btn btn-add-light" id="edit-additional_profit">Сохранить</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal modal-add-application fade" id="modalTransport" tabindex="-1" aria-labelledby="modalTransport" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="min-width: 60%">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title title-base" id="exampleModalLabel">Добавление ТС</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                </div>
                <div class="modal-body">
                    <form action="ts/add-transport" method="post" id="form-add-transport" class="form-add-transport">
                        <div class="application-transport-data row mb-4">
                            <div class="col-4">
                                <div class="">
                                    <label for="basic-url" class="form-label">Марка <span class="text-danger">*</span></label>
                                    <div class="input-group input-group-without-choices__inner">
                                        <input type="text" placeholder="Марка" class="form-control form-control-solid united-input js-trigger-input-choices"
                                               name="car_brand" aria-describedby="basic-addon3 basic-addon4">
                                    </div>
                                </div>
                            </div>

                            <div class="col-4">
                                <div class="">
                                    <label for="basic-url" class="form-label">Гос номер <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" placeholder="Гос номер" class="form-control form-control-solid"
                                               name="government_number"  aria-describedby="basic-addon3 basic-addon4">
                                    </div>
                                </div>
                            </div>

                            <div class="col-4">
                                <div class="">
                                    <label for="basic-url" class="form-label">П/П</label>
                                    <div class="input-group">
                                        <input type="text" placeholder="П/П" class="form-control form-control-solid"
                                               name="pp" aria-describedby="basic-addon3 basic-addon4">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="application-transport-data row">
                            <div class="col-4">
                                <div class="">
                                    <label for="basic-url" class="form-label">Тип транспорта <span class="text-danger">*</span></label>
                                    <div class="input-group input-group-without-choices__inner">
                                        <input type="text" placeholder="Тип транспорта" class="form-control form-control-solid united-input js-trigger-input-choices"
                                               name="type_transport"  aria-describedby="basic-addon3 basic-addon4">
                                    </div>
                                </div>
                            </div>

                            <div class="col-4">
                                <div class="">
                                    <label for="basic-url" class="form-label">Тип кузова</label>
                                    <div class="input-group input-group-without-choices__inner">
                                        <input type="text" placeholder="Тип кузова" class="form-control form-control-solid united-input js-trigger-input-choices"
                                               name="type_carcase" aria-describedby="basic-addon3 basic-addon4">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light btn-close-modal" data-bs-dismiss="modal">Отменить</button>
                    <button type="button" class="btn btn-add-light" id="btn-add-transport">Добавить</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal modal-add-application fade" id="modalForwarder" tabindex="-1" aria-labelledby="modalForwarder" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title title-base" id="exampleModalLabel">Добавление экспедитора</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                </div>
                <div class="modal-body">
                    <form action="application/add-forwarder" method="post" id="form-add-forwarder" class="form-add-forwarder">
                        <div class="row">
                            <div class="col">
                                <div class="col" id="forwarder_add_inputs">
                                    <div class="mb-4">
                                        <label for="" class="mb-1">Название <span class="text-danger">*</span></label>
                                        <input type="text" name="forwarder_name" placeholder="Название" class="form-control form-control-solid" required>
                                    </div>
                                    <div class="mb-4">
                                        <label for="" class="mb-1">ИНН <span class="text-danger">*</span></label>
                                        <input type="number" name="forwarder_inn" placeholder="ИНН" id="add-forwarder-inn" class="form-control form-control-solid" required>
                                        <div id="error-forwarder-inn-isset" class='d-none small mt-2'>Экспедитор с таким ИНН уже есть</div>
                                    </div>
                                    <div class="mb-4">
                                        <label for="" class="mb-1">Юридический адрес <span class="text-danger">*</span></label>
                                        <textarea name="forwarder_legal_address" placeholder="Адрес" class="form-control form-control-solid" required></textarea>
                                    </div>
                                    <div class="mb-4">
                                        <label for="" class="mb-1">Телефон, код в АТИ, конт. лицо, почта <span class="text-danger">*</span></label>
                                        <input type="text" name="forwarder_info" id="forwarder_info-input"  required
                                               placeholder="8 ()--, код в АТИ, Имя:" class="form-control form-control-solid forwarder_info-input" >
                                    </div>
                                </div>
                                <div class="mb-4 d-flex justify-content-between d-none">
                                    <div id="add_forwarder_info_input" class="mb-1" style="text-decoration: underline; cursor: pointer;"><span class="text-success">Добавить еще</span></div>
                                    <div id="delete_forwarder_info_input" class="mb-1" style="text-decoration: underline; cursor: pointer;"><span class="text-danger">Удалить</span></div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <script>
                    $("input[name='forwarder_info']").inputmask({
                        mask: "8 (999) 999-99-99, код в АТИ 9{3,10}, Имя: *{0,50}",
                        definitions: {
                            '*': {
                                validator: "[\\s\\S]",
                                cardinality: 0,
                            }
                        }
                    });
                </script>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light btn-close-modal" data-bs-dismiss="modal">Отменить</button>
                    <button type="button" class="btn btn-add-light" id="btn-add-forwarder">Добавить</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal modal-add-application fade" id="modalForwarderEdit" tabindex="-1" aria-labelledby="modalForwarderEdit" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title title-base" id="exampleModalLabel">Редактирование экспедитора</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                </div>
                <div class="modal-body">
                    <form action="application/edit-forwarder" method="post" id="form-edit-forwarder-from-edit" class="form-edit-forwarder">
                        <div class="row">
                            <div class="col">
                                <div class="col" id="forwarder_edit_add_inputs">
                                    <div class="mb-4">
                                        <input class="d-none" id="forwarder_edit_id" value="0" name="forwarder_edit_id">
                                        <label for="" class="mb-1">Название <span class="text-danger">*</span></label>
                                        <input id="forwarder_edit_name" type="text" name="forwarder_name" placeholder="Название" class="form-control form-control-solid" required>
                                    </div>
                                    <div class="mb-4">
                                        <label for="" class="mb-1">ИНН <span class="text-danger">*</span></label>
                                        <input id="forwarder_edit_inn" type="number" name="forwarder_inn" placeholder="ИНН" class="form-control form-control-solid" required>
                                    </div>
                                    <div class="mb-4">
                                        <label for="" class="mb-1">Юридический адрес <span class="text-danger">*</span></label>
                                        <textarea id="forwarder_edit_legal_address" name="forwarder_legal_address" placeholder="Адрес" class="form-control form-control-solid" required></textarea>
                                    </div>
                                </div>
                                <div class="mb-4 d-flex justify-content-between d-none">
                                    <div id="add_forwarder_edit_info_input" class="mb-1" style="text-decoration: underline; cursor: pointer;"><span class="text-success">Добавить еще</span></div>
                                    <div id="delete_forwarder_edit_info_input" class="mb-1" style="text-decoration: underline; cursor: pointer;"><span class="text-danger">Удалить</span></div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light btn-close-modal" data-bs-dismiss="modal">Отменить</button>
                    <button type="button" class="btn btn-add-light" id="btn-add-forwarder-from-edit">Сохранить</button>
                </div>
            </div>
        </div>
    </div>

    <button type="button" class="btn btn-primary d-none" id="liveToastBtn">Показать</button>

    <div class="toast-container  position-fixed bottom-0 end-0 p-3">
        <div id="liveToast" class="toast border-0 text-bg-danger" role="alert" aria-live="assertive"
             aria-atomic="true" data-bs-autohide="false">
            <div class="toast-header text-bg-danger">
                <strong class="me-auto" style="font-size: 16px">Ошибка</strong>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Закрыть"></button>
            </div>
            <div class="toast-body" id="error-toast-container" style="font-size: 16px;">

            </div>
        </div>
    </div>
    <div class="modal modal-add-application fade" id="modalDriver" tabindex="-1" aria-labelledby="modalDriver" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="min-width: 700px">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title title-base" id="exampleModalLabel">Добавление водителя</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                </div>
                <div class="modal-body">
                    <form action="/application/ajax/add-driver" method="post" id="form-add-driver-from" class="form-add-driver">
                        <input type="hidden" id="add-driver-side">
                        <div class="row">
                            <div class="col" id="driver_add_inputs">
                                <div class="mb-4">
                                    <label for="" class="mb-1">ФИО <span class="text-danger">*</span></label>
                                    <input type="text" name="driver_full_name" placeholder="ФИО" class="form-control form-control-solid" required>
                                </div>
                                <div class="mb-4">
                                    <label for="" class="mb-1">Водительское удостоверение <span class="text-danger">*</span></label>
                                    <input type="text" name="driver_license" id="driver_license-input" placeholder="Водительское удостоверение"
                                           class="form-control form-control-solid" required>
                                </div>
                                <div class="mb-4">
                                    <label for="" class="mb-1">Номер телефона <span class="text-danger">*</span></label>
                                    <input type="text" name="driver_phone" id="driver_phone_input" placeholder="8 ()--"
                                           class="form-control form-control-solid driver-phone-input" required value="8 ()--">
                                </div>
                                <h5 class="mb-2">Паспортные данные</h5>

                                <div class="mb-4">
                                    <label for="" class="mb-1">Серия/Номер <span class="text-danger">*</span></label>
                                    <input type="text" name="driver_passport_serial_number" id="driver_passport_serial_number_input"
                                           placeholder="Серия/Номер" class="form-control form-control-solid" required>
                                </div>
                                <div class="mb-4">
                                    <label for="" class="mb-1">Кем выдан <span class="text-danger">*</span></label>
                                    <input type="text" name="driver_issued_by" placeholder="Кем выдан"
                                           class="form-control form-control-solid" style="text-transform: uppercase;" required>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div class="mb-4">
                                            <label for="" class="mb-1">Дата выдачи <span class="text-danger">*</span></label>
                                            <input autocomplete="off" type="text" name="driver_issued_date"
                                                   id="driver_issued_date_input" placeholder="Дата выдачи"
                                                   class="form-control form-control-solid" required>
                                            <!--                                            <script type="module">-->
                                            <!--                                                new AirDatepicker('#driver_issued_date_input');-->
                                            <!--                                            </script>-->
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="mb-4">
                                            <label for="" class="mb-1">Код подразделения <span class="text-danger">*</span></label>
                                            <input type="text" name="driver_department_code" id="driver_department_code_input"
                                                   placeholder="Код подразделения" class="form-control form-control-solid" required>
                                        </div>

                                        <input type="hidden" value="1" name="is-our-driver">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light btn-close-modal" data-bs-dismiss="modal">Отменить</button>
                    <button type="button" class="btn btn-add-light" id="btn-add-driver-from">Добавить</button>
                </div>
            </div>
        </div>
    </div>


    <div class="modal modal-add-application fade" id="modalEditDriver" tabindex="-1" aria-labelledby="modalEditDriver" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="min-width: 700px">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title title-base" id="exampleModalLabel">Редактирование водителя</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                </div>
                <div class="modal-body">
                    <form action="/application/ajax/edit-driver" method="post" id="form-edit-driver-from" class="form-edit-driver">
                        <input type="hidden" id="driver_id_input-edit" name="driver_id">
                        <div class="row">
                            <div class="col" id="driver_add_inputs">
                                <div class="mb-4">
                                    <label for="" class="mb-1">ФИО <span class="text-danger">*</span></label>
                                    <input type="text" name="driver_full_name" id="driver_full_name_input-edit"
                                           placeholder="ФИО" class="form-control form-control-solid" required>
                                </div>

                                <div class="mb-4">
                                    <label for="" class="mb-1">Водительское удостоверение <span class="text-danger">*</span></label>
                                    <input type="text" name="driver_license" id="driver_license_input-edit"
                                           placeholder="Водительское удостоверение" class="form-control form-control-solid" required>
                                </div>

                                <div class="mb-4">
                                    <label for="" class="mb-1">Номер телефона <span class="text-danger">*</span></label>
                                    <input type="text" name="driver_phone" id="driver_phone_input-edit" placeholder="8 ()--" class="form-control form-control-solid driver-phone-input" required value="8 ()--">
                                </div>
                                <h5 class="mb-2">Паспортные данные</h5>

                                <div class="mb-4">
                                    <label for="" class="mb-1">Серия/Номер <span class="text-danger">*</span></label>
                                    <input type="text" name="driver_passport_serial_number" id="driver_passport_serial_number_input-edit" placeholder="Серия/Номер" class="form-control form-control-solid" required>
                                </div>
                                <div class="mb-4">
                                    <label for="" class="mb-1">Кем выдан <span class="text-danger">*</span></label>
                                    <input type="text" name="driver_issued_by" id="driver_issued_by_input-edit" placeholder="Кем выдан" class="form-control form-control-solid" style="text-transform: uppercase;" required>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div class="mb-4">
                                            <label for="" class="mb-1">Дата выдачи <span class="text-danger">*</span></label>
                                            <input autocomplete="off" type="text" name="driver_issued_date" id="driver_issued_date_input-edit" placeholder="Дата выдачи" class="form-control form-control-solid" required>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="mb-4">
                                            <label for="" class="mb-1">Код подразделения <span class="text-danger">*</span></label>
                                            <input type="text" name="driver_department_code" id="driver_department_code_input-edit" placeholder="Код подразделения" class="form-control form-control-solid" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light btn-close-modal" data-bs-dismiss="modal">Отменить</button>
                    <button type="button" class="btn btn-add-light" id="btn-edit-driver-from">Редактировать</button>
                </div>
            </div>
        </div>
    </div>
<!-- Настройка масок -->
    <script>

        $('input[name="driver_license"],input[name="driver_passport_serial_number"]').inputmask({
            mask: "99 99 999999"
        });
        $('input[name="driver_phone"]').inputmask({
            mask: "8 (999) 999-99-99"
        });
        $('input[name="driver_issued_date"]').inputmask({
            mask: "99.99.9999"
        });
        $('input[name="driver_department_code"]').inputmask({
            mask: "999-999"
        });
        $('input[name="government_number"]').inputmask({
            mask: "A 999 AA / 999{0,1}"
        });
        $('input[name="pp"],input[name="semitrailer"]').inputmask({
            mask: "AA 9999 / 999{0,1}"
        })
    </script>

<!--  Редактирование Экспедитора  -->
    <script>
        $("#btn-add-forwarder-from-edit").click(function () {
            $('#form-edit-forwarder-from-edit').trigger('submit');
        });
        $('#form-edit-forwarder-from-edit').submit(function (event){
            event.preventDefault();

            let form = $(this).serializeArray();
            let form_data = {};

            form.forEach((form_element) => {
                form_data[form_element["name"]] = form_element["value"];
            });

            var forwarder_info_inputs = Array.from($(".forwarder_info-input"));
            var forwarder_info_inputs_values = [];

            forwarder_info_inputs.forEach((forwarder_info_input)=>{
                forwarder_info_inputs_values.push(forwarder_info_input.value);
            });

            $.ajax({
                url: '/ts/ajax/edit-forwarder',
                method: 'POST',
                data: {form_data: form_data, info_inputs: forwarder_info_inputs_values},
                success: function (data_json){
                    let data = JSON.parse(data_json);

                    console.log(data);

                    if (data['status'] === "Success"){
                        $('#modalForwarderEdit').modal("hide");
                        $(".forwarder_input_wrap").html(`<select name="id_forwarder" id="id_forwarder" class="form-select select-add-application  select">
                         <option value="0" disabled selected>Экспедитор</option>
                            <?php foreach ($listForwarders as $forwarder){ ?>
                                <option value="<?php echo $forwarder['id']; ?>"><?php echo $forwarder['name'] ; ?></option>
                                <?php } ?>
                         <option value="${data['data']['id']}">${data['data']['name']}</option>
                     </select>
                     <script>
                         <`+"/script>");

                        let id_forwarder = data['data']['id'];

                        ajaxGetForwarder(id_forwarder);

                        let sselect = document.getElementById('id_forwarder');
                        sselect.value = data['data']['id'];

                        choicesForwarder = new Choices('#id_forwarder', {allowHTML: true,});
                    }
                    else if(data['status'] === "Error"){
                        if(data['errorText'])
                            alert(data['errorText']);

                        if(data['error']){
                            for(key in data['error']){
                                $('.form-control[name="forwarder_' + key + '"]').addClass('error-validate');
                                if(key == 'address')
                                    $('.form-control[name="id_forwarder_legal_' + key + '"]').addClass('error-validate');

                            }
                        }

                        if(!data['errorText'] && !data['error'])
                            alert("Не удалось добавить Экспедитора");

                    }
                    else{
                        alert("Поля заполнены не правильно !");
                    }
                }
            });

        })
    </script>


    <script>
<?php //todo доделать чтобы после выбора вида налогообложения выбирались только нужные ?>
        $('#taxation-type-input_carrier').change(function () {
            let type = $(this).val();
            let typeFor = 0;

            $.ajax({
                url:'/application/ajax/get-terms-payment-list',
                method:'POST',
                data:{type: type, typeFor: typeFor},
                success:function (response) {
                    let data = JSON.parse(response);

                    let htmlOption = ``;

                    for(let i = 0; i < data.length; i++){
                        htmlOption += `<option value="${data[i]['id']}">${data[i]['name']}</option>`;
                    }


                    $('.terms-payment_carrier-wrap').html(
                        `<select name="terms-payment_carrier" id="select-terms-payment_carrier"
                                class="form-select select-chosen united-select" data-placeholder="">${htmlOption}</select>`
                    );
                    new Choices('#select-terms-payment_carrier', {allowHTML: true,});
                }
            })
        })

        $('body').on('change','#select-terms-payment' , function (){
            getTermsPaymentDescription($(this).val());
        });


        function getTermsPaymentDescription(id){
            $.ajax({
                url: "/application/ajax/getTermsPaymentDescription",
                method: "POST",
                data:{
                    id: id
                },
                success: function (response){
                    console.log(response);
                        Editor_terms_payment.setData(response);

                }
            });
        }


        $('.edit-driver-btn-modal').hide();

        $('#id_driver').change(function () {
           ajaxGetDriver($(this).val());
        });

        $('#btn-add-driver-from').click(function () {
            $('#form-add-driver-from').trigger('submit');
        });

        $('#form-add-driver-from').submit(function (e) {
            e.preventDefault();
            $(this).find('input').removeClass('error-validate');
            let form = $(this).serializeArray();

            console.log(form);

            $.ajax({
                url: '/application/ajax/add-driver',
                method: 'POST',
                data: form,
                success: function (response){

                    let data = JSON.parse(response);
                    console.log(data);
                    if (data['status'] === "Success"){
                        $('#form-add-driver-from').trigger('reset');
                        $('#modalDriver').modal("hide");

                        let driver_id = data['data']['id'];
                        $(".driver_forwarder_wrap").html(`<label for="basic-url" class="form-label">ФИО <span class="text-danger">*</span></label>
                             <select name="id_driver" id="id_driver" class="form-select united-select select  select-add-application">
                                   <option value="0" disabled selected>Водитель</option>
                                   <?php foreach ($listDrivers as $driver){ ?>
                                       <option value="<?php echo $driver['id']; ?>"><?php echo $driver['name']; ?></option>
                                   <?php } ?>
                                <option value="${data['data']['id']}">${data['data']['name']}</option>
                            </select>`);

                        ajaxGetDriver(driver_id, false);

                        document.getElementById('id_driver').value = data['data']['id'];

                        choicesDriverClient = new Choices('#id_driver', {allowHTML: true,});

                    }
                    else if(data['status'] === "Error"){
                        if(data['error']){
                            let errors = data['error'];
                            for(key in errors){
                                $('input[name="' + key + '"]').addClass('error-validate');
                            }
                        }
                        else
                            alert("Не удалось добавить водителя");
                    }
                    else{
                        alert("Не правильно заполнены поля");
                    }
                }
            });
        });

        $('#btn-edit-driver-from').click(function () {
            $('#form-edit-driver-from').trigger('submit');
        });

        $('#form-edit-driver-from').submit(function (e){
            e.preventDefault();

            let form = $(this).serializeArray();

            $.ajax({
                url: '/application/ajax/edit-driver',
                method: 'POST',
                data: form,
                success: function (response){
                    let data = JSON.parse(response);
                    if (data['status'] === "Success"){
                        $('#modalEditDriver').modal("hide");

                        let driver_id = data['data']['id'];
                        $(".driver_forwarder_wrap").html(`<label for="basic-url" class="form-label">ФИО <span class="text-danger">*</span></label>
                                     <select name="id_driver" id="id_driver" class="form-select united-select select  select-add-application">
                                           <option value="0" disabled selected>Водитель</option>
                                           <?php foreach ($listDrivers as $driver){ ?>
                                               <option value="<?php echo $driver['id']; ?>"><?php echo $driver['name']; ?></option>
                                           <?php } ?>
                                        <option value="${data['data']['id']}">${data['data']['name']}</option>
                                    </select>`);

                        ajaxGetDriver(driver_id);

                        document.getElementById('id_driver').value = data['data']['id'];

                        choicesDriverClient = new Choices('#id_driver', {allowHTML: true,});

                        // setDriversSelectsOptions();
                    }
                    else if(data['status'] === "Error"){
                        if(data['error']){
                            let errors = data['error'];
                            for(key in errors){
                                $('input[name="' + key + '"]').addClass('error-validate');
                            }
                        }
                        else
                            alert("Не удалось отредактировать данные водителя");
                    }
                    else{
                        alert("Не правильно заполнены поля");
                    }
                }
            });
        })



        $('.forwarder-info').hide();

        $('#id_forwarder').change(function () {
            ajaxGetForwarder($(this).val())
        })
        function ajaxGetForwarder(forwarder_id){
            $.ajax({
                url: '/ts/application/ajax/get-forwarder',
                method: 'POST',
                data: {id: forwarder_id},
                success: function (forwarder_json){
                    $('#modal_forwarder_edit').removeClass('d-none');

                    let forwarder_array = JSON.parse(forwarder_json);

                    $('#forwarder_edit_id').val(forwarder_array['id']);
                    $('#forwarder_edit_name').val(forwarder_array['name']);
                    $('#forwarder_edit_inn').val(forwarder_array['inn']);
                    $('#forwarder_edit_legal_address').val(forwarder_array['legal_address']);
                    //
                    //
                    //
                    let newInfoArray = forwarder_array['contact'].split('||');

                    $('.forwarder-info-input-wrap').remove();
                    for (let i = 0; i < newInfoArray.length; i++) {
                        $('#modalForwarderEdit #forwarder_edit_add_inputs').append(`
                                        <div class="mb-4 forwarder-info-input-wrap">
                                            <label for="" class="mb-1">Телефон, код в АТИ, конт. лицо, почта <span class="text-danger">*</span></label>
                                            <input id="forwarder_edit_info" type="text" name="forwarder_info" id="forwarder_info-input"
                                    placeholder="8 ()--, код в АТИ, Имя:" class="form-control form-control-solid forwarder_info-input"
                                            required value="`+newInfoArray[i]+`">
                                        </div>
                                    `);

                    }

                    $('.forwarder-info').show();
                    $('.forwarder-info #forwarder_info_inn').html(forwarder_array['inn']);
                    $('.forwarder-info #forwarder_info_legal_address').html(forwarder_array['legal_address']);


                    let infoArrayForwarder = forwarder_array['contact'].split("||");

                    let infoHtmlForwarder = `<select name="forwarder_chosen_info" id="forwarder_chosen_info" class="form-select select-add-application united-select select">`;

                    infoArrayForwarder.forEach((item) => {
                        infoHtmlForwarder += `<option value="${item}">${item}</option>`
                    });

                    infoHtmlForwarder += `</select>`;

                    $('.forwarder-info #forwarder_info_info').html(infoHtmlForwarder);

                    // setFieldsView();
                }
            });
        }

        $('#form-add-forwarder').submit(function (e) {
            e.preventDefault();

            let form = $(this).serializeArray();
            let form_data = {};

            form.forEach((form_element) => {
                form_data[form_element["name"]] = form_element["value"];
            });

            var forwarder_info_inputs = Array.from($(".forwarder_info-input"));
            var forwarder_info_inputs_values = [];

            forwarder_info_inputs.forEach((forwarder_info_input)=>{
                forwarder_info_inputs_values.push(forwarder_info_input.value);
            });

            $.ajax({
                url: '/ts/ajax/add-forwarder',
                method: 'POST',
                data: {form_data: form_data, info_inputs: forwarder_info_inputs_values},
                success: function (data_json){
                    let data = JSON.parse(data_json);

                    console.log(data);

                    if (data['status'] === "Success"){
                        $('#form-add-forwarder').trigger('reset');
                        $('#modalForwarder').modal("hide");
                        $(".forwarder_input_wrap").html(`<select name="id_forwarder" id="id_forwarder" class="form-select select-add-application  select">
                         <option value="0" disabled selected>Экспедитор</option>
                            <?php foreach ($listForwarders as $forwarder){ ?>
                                <option value="<?php echo $forwarder['id']; ?>"><?php echo $forwarder['name'] ; ?></option>
                                <?php } ?>
                         <option value="${data['data']['id']}">${data['data']['name']}</option>
                     </select>
                     <script>
                         <`+"/script>");

                        let id_forwarder = data['data']['id'];

                        ajaxGetForwarder(id_forwarder);

                        let sselect = document.getElementById('id_forwarder');
                        sselect.value = data['data']['id'];

                        choicesForwarder = new Choices('#id_forwarder', {allowHTML: true,});
                    }
                    else if(data['status'] === "Error"){
                        if(data['errorText'])
                            alert(data['errorText']);

                        if(data['error']){
                            for(key in data['error']){
                                $('.form-control[name="forwarder_' + key + '"]').addClass('error-validate');
                                if(key == 'address')
                                    $('.form-control[name="id_forwarder_legal_' + key + '"]').addClass('error-validate');

                            }
                        }

                        if(!data['errorText'] && !data['error'])
                            alert("Не удалось добавить Экспедитора");

                    }
                    else{
                        alert("Поля заполнены не правильно !");
                    }
                }
            });
        });

        $('#btn-add-forwarder').click(function () {
            $('#form-add-forwarder').trigger('submit');
        });

        $('#btn-add-transport').click(function () {
            $('#form-add-transport').trigger('submit');
        });

        $('#form-add-transport').submit(function (e) {
            e.preventDefault();

            let data = $(this).serializeArray();

            $.ajax({
                method: 'POST',
                url: '/ts/ajax/add-transport',
                data: data,
                success: function (response) {
                    response = JSON.parse(response);
                    if(response['status']){
                        data = response['data'];
                        let html = `<option value="${response['status']}">
                                ${data['car_brand']} (${data["government_number"]})
                                П/П (${data['pp']}) ${data['type_transport']} ${data['type_carcase']}
                            </option>`;
                        $('#id_ts_transport').append(html);
                        $('#modalTransport').modal("hide");
                    }
                    else{
                        alert('Не удалось добавить Транспорт!')
                    }

                }
            })

        });
    </script>
    <!--    MAP-->

    <script>


        $('body').on('click', '.modal', function () {
            $('.custom-dropdown').removeClass('active');

        })
        $('body').on('click','.custom-dropdown-item', function () {
            $('.modal').has(this).find('input[name="city"]').val($(this).text());
            $('.custom-dropdown').has($(this)).removeClass('active');
        });
        $('body').on('input','input[name="city"]', function (e) {
            e.stopPropagation();
            $('.modal').has(this).find('.custom-dropdown').addClass('active');
        });
        $('input[name="city"]').click(function (e) {
            e.stopPropagation();
            $('.modal').has(this).find('.custom-dropdown').addClass('active');
        })
        var cityApi = function () {
            jQuery(document).ready(function () {
                var cityApiSelect = jQuery('[city-api="true"]');

                jQuery(cityApiSelect).each(function () {
                    var itemSelect = jQuery(this);
                    var cityApiSelect2 = $('.modal').has(this).find('.custom-dropdown');
                    var cityApiSelect2Input = jQuery(this).parent().find('input');
                    console.log(itemSelect,cityApiSelect2Input)
                    jQuery(cityApiSelect2Input).on('input', function () {

                        var url = "https://suggestions.dadata.ru/suggestions/api/4_1/rs/suggest/address";
                        var token = "10b05f00b18767997950b4f20c962a0265ca0489";
                        var query = jQuery(cityApiSelect2Input).val();

                        var options = {
                            method: "POST",
                            mode: "cors",
                            headers: {
                                "Content-Type": "application/json",
                                "Accept": "application/json",
                                "Authorization": "Token " + token
                            },
                            body: JSON.stringify({
                                query: query,
                                division: 'municipal',

                            })
                        }

                        fetch(url, options)
                            .then(response => response.text())
                            .then(function (result) {

                                var arrResult = JSON.parse(result).suggestions;
                                var arrFormat = ['По умолчанию'];
                                for (var i = 0; i < arrResult.length; i++) {
                                    var item = arrResult[i];


                                    arrFormat.push(item.value);

                                    jQuery(itemSelect).html(``)
                                    let html = ``;

                                    jQuery(arrFormat).each(function () {
                                        jQuery(itemSelect).append(`<option value="${String(this)}">${String(this)}</option>`)
                                        html += `<div class="custom-dropdown-item">${String(this)}</div>`;
                                    })
                                    cityApiSelect2.html(html);

                                    if(! cityApiSelect2.hasClass('active'))
                                        cityApiSelect2.addClass('active')

                                    // jQuery(itemSelect).each(function () {
                                    //     jQuery(this).select2({
                                    //         width: 'resolve',
                                    //     });
                                    //     jQuery(this).select2().data('select2').$dropdown.addClass('dropdown-tooltip');
                                    // })
                                    // jQuery(itemSelect).select2('open')
                                }

                                console.log(arrFormat);
                            })
                            .catch(error => console.log("error", error));


                    })
                    jQuery(cityApiSelect2Input).on('paste', function () {

                        var url = "https://suggestions.dadata.ru/suggestions/api/4_1/rs/suggest/address";
                        var token = "003fb687d427e3ebd885fcf697c9f72e3f7ddd43";
                        var query = jQuery(cityApiSelect2Input).val();

                        var options = {
                            method: "POST",
                            mode: "cors",
                            headers: {
                                "Content-Type": "application/json",
                                "Accept": "application/json",
                                "Authorization": "Token " + token
                            },
                            body: JSON.stringify({
                                query: query,
                                division: 'municipal',

                            })
                        }

                        fetch(url, options)
                            .then(response => response.text())
                            .then(function (result) {
                                var arrResult = JSON.parse(result).suggestions;
                                var arrFormat = ['По умолчанию'];
                                for (var i = 0; i < arrResult.length; i++) {
                                    var item = arrResult[i];


                                    arrFormat.push(item.value);

                                    jQuery(itemSelect).html(``)

                                    jQuery(arrFormat).each(function () {
                                        jQuery(itemSelect).append(`<option value="${String(this)}">${String(this)}</option>`)
                                    })


                                    jQuery(itemSelect).each(function () {
                                        jQuery(this).select2({
                                            width: 'resolve',
                                        });
                                        jQuery(this).select2().data('select2').$dropdown.addClass('dropdown-tooltip');
                                    })
                                    jQuery(itemSelect).select2('open')
                                }


                            })
                            .catch(error => console.log("error", error));


                    })


                    var sense = 'getCities';
                    var need_all = 0;
                    var country_id = 1;
                    jQuery.ajax({
                        url: "https://api.vk.com/method/database." + sense,
                        crossDomain: true,
                        dataType: 'jsonp',
                        type: 'GET',
                        data: {
                            access_token: '054c0b69054c0b69054c0b6962065fa1fb0054c054c0b69610cdd3e651aaf3a612a5fab', // Здесь access_token вашего приложения(!)
                            need_all: need_all,
                            country_id,
                            v: 5.131
                        },
                        success: function (data) {

                            jQuery(data.response.items).each(function () {

                                jQuery(itemSelect).append(`<option>${jQuery(this)['0'].title}</option>`)

                            })
                        }
                    });


                    jQuery(cityApiSelect2).click(function () {

                        var sense = 'getCities';

                        var need_all = 0;
                        var country_id = 1;


                        jQuery.ajax({
                            url: "https://api.vk.com/method/database." + sense,
                            crossDomain: true,
                            dataType: 'jsonp',
                            type: 'GET',
                            data: {
                                access_token: '054c0b69054c0b69054c0b6962065fa1fb0054c054c0b69610cdd3e651aaf3a612a5fab', // Здесь access_token вашего приложения(!)
                                need_all: need_all,
                                country_id,
                                v: 5.131
                            },
                            success: function (data) {

                                jQuery(data.response.items).each(function () {

                                    jQuery(itemSelect).append(`<option>${jQuery(this)['0'].title}</option>`)

                                })
                            }
                        });


                    })
                })


            })
        }
        cityApi();
    </script>
    <script>

        $('#id_ts_transport').change(function (){
            let idTransport = $(this).val();
            
            $.ajax({
                method: 'POST',
                url: '/ts/ajax/get-data-ts-transport',
                data: {idTransport: idTransport},
                success: function (response) {
                    let data = JSON.parse(response);

                    $('#car_brand').val(data['car_brand']);
                    $('#government_number').val(data['government_number']);
                    $('#semitrailer').val(data['pp']);
                    $('#type_transport').val(data['type_transport']);
                    $('#type_carcase').val(data['type_carcase']);
                    console.log(data)
                }
            })
        });

        $('.js-trigger-select-choices').click(function () {
            $('.btn-plus-condition').has(this).find('.choices').trigger('click');
        })
    </script>
    <script>
        $( ".united-select" ).each(function(element) {
            $(this).addClass('gg');
            new Choices(this, {
                allowHTML: true
            });
        });
        function formatNumber(num) {
            return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1 ')
        }
        $(".weight-input").on('change', function(){
            if ($("#style-select").val() === "0"){
                return;
            }
            let additional = '';
            if ($(this).val().includes("кг")){
                additional = "кг";
            }
            else{
                additional = "тонн";
            }

            let newValue = '';
            for(let i = 0; i < $(this).val().length; i++){
                if ($.isNumeric($(this).val()[i]) || $(this).val()[i] == ',' || $(this).val()[i] == '.'){
                    newValue += $(this).val()[i];
                }
            }
            $(this).val(newValue + " " + additional);
        });
        document.getElementById('transportation_cost').oninput = function (){
            let value = $('#transportation_cost').val();
            value = value.replaceAll(' ' ,'');
            console.log(value);

            $('#transportation_cost').val(formatNumber(value));
        }
        // document.getElementById('cost_cargo').oninput = function (){
        //     let value = $('#cost_cargo').val();
        //     value = value.replaceAll(' ' ,'');
        //     console.log(value);
        //
        //     $('#cost_cargo').val(formatNumber(value));
        // }

        document.getElementById('input-sum_expenses').oninput = function (){
            let value = $('#input-sum_expenses').val();
            value = value.replaceAll(' ' ,'');
            console.log(value);

            $('#input-sum_expenses').val(formatNumber(value));
        }

        document.getElementById('edit-expense-type_expenses').oninput = function (){
            let value = $('#edit-expense-type_expenses').val();
            value = value.replaceAll(' ' ,'');
            console.log(value);

            $('#edit-expense-type_expenses').val(formatNumber(value));
        }

        document.getElementById('input-sum_additional_profit').oninput = function (){
            let value = $('#input-sum_additional_profit').val();
            value = value.replaceAll(' ' ,'');
            console.log(value);

            $('#input-sum_additional_profit').val(formatNumber(value));
        }
        document.getElementById('edit-additional_profit-sum').oninput = function (){
            let value = $('#edit-additional_profit-sum').val();
            value = value.replaceAll(' ' ,'');
            console.log(value);

            $('#edit-additional_profit-sum').val(formatNumber(value));
        }



        $('.form__body__forwarder .driver-info').hide();

        function ajaxGetDriver(driver_id){
            $.ajax({
                url: '/application/ajax/get-driver',
                method: 'POST',
                data: {id: driver_id},
                success: function (driver_json){
                    $('.driver-info').show();

                    let driver_array = JSON.parse(driver_json);
                    console.log(driver_array);
                    // console.log(driver_array['listCar']);

                    $('.edit-driver-btn-modal').show();

                    $('#driver_id_input-edit').val(driver_array['id']);
                    $('#driver_full_name_input-edit').val(driver_array['name']);
                    $('#driver_license_input-edit').val(driver_array['driver_license']);
                    $('#driver_phone_input-edit').val(driver_array['phone']);
                    $('#driver_passport_serial_number_input-edit').val(driver_array['passport_serial_number']);
                    $('#driver_issued_by_input-edit').val(driver_array['issued_by']);
                    $('#driver_issued_date_input-edit').val(driver_array['issued_date']);
                    $('#driver_department_code_input-edit').val(driver_array['department_code']);

                    $('.form__body__forwarder .driver-info').show();
                    $('#driver_info_name_forwarder').html(driver_array['name']);
                    $('#driver_info_licence_forwarder').html(driver_array['driver_license']);
                    $('#driver_info_phone_forwarder').html(driver_array['phone']);

                    $('#driver_info_passport_forwarder').html(driver_array['passport_serial_number']);
                }
            });
        }


    </script>
    <script>


        function collectData(nameCollectItem, fieldsData = []){
            let data = [];

            $(nameCollectItem).each(function (index, element) {
                let elementData = {};
                for(let i = 0 ; i < fieldsData.length; i++){
                    elementData[fieldsData[i]] = $(element).data(fieldsData[i]);

                    if(fieldsData[i] == 'sort')
                        elementData[fieldsData[i]] = index;
                }
                data.push(elementData);
            });

            return data;
        }

        function collect_route(side){
            let fields = ['direction','city','address','date','time','contact','phone','loading_method','sort'];

            return collectData('.form__body__forwarder .marshrut-list__item', fields);
        }

        function collectExpenses(side){
            let fields = ['type-for','type-expenses','sum','type-payment','comment'];

            return collectData('#expenses-forwarder-block_forwarder .expenses-item', fields);
        }

        function collectAdditionalProfit(){
            let fields = ['type','sum','type-payment','comment'];

            return collectData('#additional_profit .additional_profit-item', fields);
        }

        $('body').on('click','.js-delete-marshrut', function () {
            $('.marshrut-list__item').has(this).remove();
        });

        $('body').on('click','.js-delete-expenses', function () {
            $('.expenses-item').has(this).remove();
        });

        $('body').on('click','.js-delete-additional_profit', function () {
            $('.additional_profit-item').has(this).remove();
        });


        $('#add-expenses').click(function () {
            $('#form-expenses').trigger('submit');
        });

        let expenseId = 1;

        $('#form-expenses').submit(function (e) {
            e.preventDefault();

            expenseId++;

            let form = $(this).serializeArray();

            let data = {};

            console.log(form)
            for(let i = 0; i < form.length; i++){
                data[form[i]['name']] = form[i]['value'];
            }

            console.log(data);

            let html = `<div class="expenses-item expenses-item_${expenseId}" data-type-for="${data['type_for']}" data-type-expenses="${data['type_expenses']}"
                        data-sum="${data['sum']}" data-type-payment="${data['type_payment']}" data-comment="${data['comment']}">
                <div class="row">
                    <div class="col">
                        <label for="">Вид затрат</label>
                        <span class="expense-type-expenses_${expenseId}">${data['type_expenses']}</span>
                    </div>
                    <div class="col">
                        <label for="">Сумма</label>
                        <span class="expense-sum_${expenseId}">${data['sum']}</span>
                    </div>
                    <div class="col">
                        <label for="">Вид налогообложения</label>
                        <span class="expense-type-payment_${expenseId}">${data['type_payment']}</span>
                    </div>
                    <div class="col">
                        <label for="">Комментарий</label>
                        <span class="expense-comment_${expenseId}">${data['comment']}</span>
                    </div>
                    <div class="col">
                        <div class="dropdown">
                            <div class="dropdown-toggle dropdown-toggle-without-arrow" data-bs-toggle="dropdown" aria-expanded="false">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path opacity="0.3" d="M17 2H7C4.23858 2 2 4.23858 2 7V17C2 19.7614 4.23858 22 7 22H17C19.7614 22 22 19.7614 22 17V7C22 4.23858 19.7614 2 17 2Z" fill="#B5B5C3"></path>
                                    <path d="M13 12C13 11.4477 12.5523 11 12 11C11.4477 11 11 11.4477 11 12C11 12.5523 11.4477 13 12 13C12.5523 13 13 12.5523 13 12Z" fill="#B5B5C3"></path>
                                    <path d="M13 16C13 15.4477 12.5523 15 12 15C11.4477 15 11 15.4477 11 16C11 16.5523 11.4477 17 12 17C12.5523 17 13 16.5523 13 16Z" fill="#B5B5C3"></path>
                                    <path d="M13 8C13 7.44772 12.5523 7 12 7C11.4477 7 11 7.44772 11 8C11 8.55228 11.4477 9 12 9C12.5523 9 13 8.55228 13 8Z" fill="#B5B5C3"></path>
                                </svg>
                            </div>
                            <ul class="dropdown-menu">
                                <li><div class="dropdown-item js-delete-expenses" >Удалить</div></li>
                                <li><div class="dropdown-item js-edit-expenses" onclick="editExpense(${expenseId});">Редактировать</div></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>`;

            $('#expenses-forwarder-block_forwarder').append(html);
        })

        $("#edit-expenses").click(function (){
            $("#edit-expenses-form").trigger("submit");
        });
        $("#edit-expenses-form").submit(function (e) {
            e.preventDefault();
            let form = $(this).serializeArray();
            let form_data = {};

            form.forEach((form_element) => {
                form_data[form_element["name"]] = form_element["value"];
            });

            let expenseId = $("#edit-expense-id").val();

            $(".expenses-item_"+expenseId).data('type-expenses',form_data["type_expenses"]);
            $(".expenses-item_"+expenseId).data('sum',form_data["sum"]);
            $(".expenses-item_"+expenseId).data('type-payment',form_data["type_payment"]);
            $(".expenses-item_"+expenseId).data('comment',form_data["comment"]);

            $('.expense-type-expenses_'+expenseId).html(form_data["type_expenses"]);
            $('.expense-sum_'+expenseId).html(form_data["sum"]);
            $('.expense-type-payment_'+expenseId).html(form_data["type_payment"]);
            $('.expense-comment_'+expenseId).html(form_data["comment"]);

            let fields = ['type-expenses','sum','type-payment','comment'];

            let editExpenseData = collectData('.expenses-item_'+expenseId, fields)[0];

            console.log(form_data);
            console.log(editExpenseData);

            $("#modalEditExpenses").modal("hide");
        });

        function editExpense(id){
            $("#edit-expense-id").val(id);

            let fields = ['type-expenses','sum','type-payment','comment'];

            let editExpenseData = collectData('.expenses-item_'+id, fields)[0];

            console.log(editExpenseData);

            $("#modalEditExpenses #edit-expense-type_expenses").val(editExpenseData["type-expenses"]);
            $("#modalEditExpenses #edit-expense-sum").val(editExpenseData["sum"]);
            $("#modalEditExpenses #edit-expense-type_payment").val(editExpenseData["type-payment"]);
            $("#modalEditExpenses #edit-expense-comment").val(editExpenseData["comment"]);

            $("#modalEditExpenses").modal("show");
        }

        $('#select-type_expenses').change(function () {
            let valueSelect = $(this).val();
            $('#input-type_expenses').addClass('d-none');

            $('#input-type_expenses').val(valueSelect);

            switch (valueSelect){
                case 'Страховка':
                    $('#input-sum_expenses').val('500');
                    break;
                case '':
                    $('#input-type_expenses').removeClass('d-none');
                    break;
            }
        })

        $('#add-additional_profit').click(function () {
            $('#form-additional_profit').trigger('submit');
        });

        let additionalProfitId = 1;

        $('#form-additional_profit').submit(function (e) {
            e.preventDefault();

            additionalProfitId++;

            let form = $(this).serializeArray();

            let data = {};

            console.log(form)
            for(let i = 0; i < form.length; i++){
                data[form[i]['name']] = form[i]['value'];
            }

            console.log(data);

            let html = `<div class="additional_profit-item additional_profit-item_${additionalProfitId}" data-type-for="${data['type_for']}"
                data-type="${data['type']}"
                        data-sum="${data['sum']}" data-type-payment="${data['type_payment']}" data-comment="${data['comment']}">
                <div class="row">
                    <div class="col">
                        <label for="">Вид затрат</label>
                        <span class="additional_profit-type_${additionalProfitId}">${data['type']}</span>
                    </div>
                    <div class="col">
                        <label for="">Сумма</label>
                        <span class="additional_profit-sum_${additionalProfitId}">${data['sum']}</span>
                    </div>
                    <div class="col">
                        <label for="">Вид налогообложения</label>
                        <span class="additional_profit-type-payment_${additionalProfitId}">${data['type_payment']}</span>
                    </div>
                    <div class="col">
                        <label for="">Комментарий</label>
                        <span class="additional_profit-comment_${additionalProfitId}">${data['comment']}</span>
                    </div>
                    <div class="col">
                        <div class="dropdown">
                            <div class="dropdown-toggle dropdown-toggle-without-arrow" data-bs-toggle="dropdown" aria-expanded="false">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path opacity="0.3" d="M17 2H7C4.23858 2 2 4.23858 2 7V17C2 19.7614 4.23858 22 7 22H17C19.7614 22 22 19.7614 22 17V7C22 4.23858 19.7614 2 17 2Z" fill="#B5B5C3"></path>
                                    <path d="M13 12C13 11.4477 12.5523 11 12 11C11.4477 11 11 11.4477 11 12C11 12.5523 11.4477 13 12 13C12.5523 13 13 12.5523 13 12Z" fill="#B5B5C3"></path>
                                    <path d="M13 16C13 15.4477 12.5523 15 12 15C11.4477 15 11 15.4477 11 16C11 16.5523 11.4477 17 12 17C12.5523 17 13 16.5523 13 16Z" fill="#B5B5C3"></path>
                                    <path d="M13 8C13 7.44772 12.5523 7 12 7C11.4477 7 11 7.44772 11 8C11 8.55228 11.4477 9 12 9C12.5523 9 13 8.55228 13 8Z" fill="#B5B5C3"></path>
                                </svg>
                            </div>
                            <ul class="dropdown-menu">
                                <li><div class="dropdown-item js-delete-additional_profit" >Удалить</div></li>
                                <li><div class="dropdown-item js-edit-additional_profit" onclick="editAdditionalProfit(${additionalProfitId});">Редактировать</div></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>`;

            $('#additional_profit').append(html);
        })

        function editAdditionalProfit(id){
            $("#edit-additional_profit-id").val(id);

            let fields = ['type','sum','type-payment','comment'];

            let editAdditionalProfitData = collectData('.additional_profit-item_'+id, fields)[0];

            console.log(editAdditionalProfitData);

            $("#modalEditAdditionalProfit #edit-additional_profit-type").val(editAdditionalProfitData["type"]);
            $("#modalEditAdditionalProfit #edit-additional_profit-sum").val(editAdditionalProfitData["sum"]);
            $("#modalEditAdditionalProfit #edit-additional_profit-type_payment").val(editAdditionalProfitData["type-payment"]);
            $("#modalEditAdditionalProfit #edit-additional_profit-comment").val(editAdditionalProfitData["comment"]);

            $("#modalEditAdditionalProfit").modal("show");
        }

        $('#edit-additional_profit').click(function () {
            $("#edit-additional_profit-form").trigger("submit");
        });

        $("#edit-additional_profit-form").submit(function (e) {
            e.preventDefault();
            let form = $(this).serializeArray();
            let form_data = {};

            form.forEach((form_element) => {
                form_data[form_element["name"]] = form_element["value"];
            });

            console.log({'text': form_data})

            let additionalProfitId = $("#edit-additional_profit-id").val();

            console.log(additionalProfitId)

            $(".additional_profit-item_"+additionalProfitId).data('type',form_data["type"]);
            $(".additional_profit-item_"+additionalProfitId).data('sum',form_data["sum"]);
            $(".additional_profit-item_"+additionalProfitId).data('type-payment',form_data["type_payment"]);
            $(".additional_profit-item_"+additionalProfitId).data('comment',form_data["comment"]);

            $('.additional_profit-type_'+additionalProfitId).html(form_data["type"]);
            $('.additional_profit-sum_'+additionalProfitId).html(form_data["sum"]);
            $('.additional_profit-type-payment_'+additionalProfitId).html(form_data["type_payment"]);
            $('.additional_profit-comment_'+additionalProfitId).html(form_data["comment"]);

            let fields = ['type','sum','type-payment','comment'];

            let editAdditionalProfitData = collectData('.additional_profit-item_'+additionalProfitId, fields)[0];

            console.log(form_data);
            console.log(editAdditionalProfitData);

            $("#modalEditAdditionalProfit").modal("hide");
        });

        $('#select-type_additional_profit').change(function () {
            let valueSelect = $(this).val();
            $('#input-type_additional_profit').addClass('d-none');

            $('#input-type_additional_profit').val(valueSelect);

            switch (valueSelect){
                case '':
                    $('#input-type_additional_profit').removeClass('d-none');
                    break;
            }
        })



        $('#add-marshrut').click(function () {
            $('#btn-form-add-marshrut').trigger('click');
        });

        $('.marshrut-list').sortable();

        let marshrutIndex = 0;

        $('#form-add-marshrut').submit(function (e) {
            e.preventDefault();
            let form = $(this).serializeArray();

            console.log(form);
            let side = $('#form-add-marshrut-side').val();

            let direction = 'Куда';
            let method = 'выгрузки';

            if(form[0]["value"] == 1){
                direction = 'Откуда';
                method = "погрузки";
            }

            marshrutIndex++;

            let html = `<div class="marshrut-list__item row marshrut-list__item_${marshrutIndex}"
                        data-direction="${form[0]["value"]}" data-city="${form[1]["value"]}"
                        data-address="${form[3]["value"]}" data-date="${form[5]["value"]}"
                        data-time="${form[7]["value"]}" data-contact="${form[2]["value"]}"
                        data-phone="${form[4]["value"]}" data-loading_method="${form[6]["value"]}" data-sort="">
                <div class="drag-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#B5B5C3"
                         class="bi bi-grip-vertical" viewBox="0 0 16 16">
                        <path d="M7 2a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zM7 5a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zM7 8a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm-3 3a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm-3 3a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"></path>
                    </svg>
                </div>
                <div class="col-2">
                    <label for="">${direction}</label>
                    <span class="marshrut-address_${marshrutIndex}">${form[1]['value']}, ${form[3]['value']}</span>
                </div>
                <div class="col-2">
                    <label for="">Дата</label>
                    <span class="marshrut-date_${marshrutIndex}">${form[5]['value']}</span>
                </div>
                <div class="col">
                    <label for="">Время</label>
                    <span class="marshrut-time_${marshrutIndex}">${form[7]['value']}</span>
                </div>
                <div class="col">
                    <label for="">Контактное лицо</label>
                    <span class="marshrut-contact-face_${marshrutIndex}">${form[2]['value']}</span>
                </div>
                <div class="col">
                    <label for="">Номер</label>
                    <span class="marshrut-number_${marshrutIndex}">${form[4]['value']}</span>
                </div>
                <div class="col">
                    <label for="">Способ ${method}</label>
                    <span class="marshrut-method_${marshrutIndex}">${form[6]['value']}</span>
                </div>
                <div class="setting">
                    <div class="dropdown">
                        <div class="dropdown-toggle dropdown-toggle-without-arrow" data-bs-toggle="dropdown" aria-expanded="false">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path opacity="0.3" d="M17 2H7C4.23858 2 2 4.23858 2 7V17C2 19.7614 4.23858 22 7 22H17C19.7614 22 22 19.7614 22 17V7C22 4.23858 19.7614 2 17 2Z" fill="#B5B5C3"></path>
                                <path d="M13 12C13 11.4477 12.5523 11 12 11C11.4477 11 11 11.4477 11 12C11 12.5523 11.4477 13 12 13C12.5523 13 13 12.5523 13 12Z" fill="#B5B5C3"></path>
                                <path d="M13 16C13 15.4477 12.5523 15 12 15C11.4477 15 11 15.4477 11 16C11 16.5523 11.4477 17 12 17C12.5523 17 13 16.5523 13 16Z" fill="#B5B5C3"></path>
                                <path d="M13 8C13 7.44772 12.5523 7 12 7C11.4477 7 11 7.44772 11 8C11 8.55228 11.4477 9 12 9C12.5523 9 13 8.55228 13 8Z" fill="#B5B5C3"></path>
                            </svg>
                        </div>
                        <ul class="dropdown-menu">
                            <li><div class="dropdown-item js-delete-marshrut">Удалить</div></li>
                            <li><div class="dropdown-item" onclick="editRoute(${marshrutIndex});">Редактировать</div></li>
                            <li><div class="dropdown-item">Копировать</div></li>

                        </ul>
                    </div>
                </div>
            </div>`;

            $('#marshrut-list').append(html);


            $('#form-add-marshrut').trigger('reset');
            $('.direction-input').trigger('change');
        })

        function editRoute(id){
            $("#marshrut_id_form-edit-modal").val(id);
            let fields = ['direction','city','address','date','time','contact','phone','loading_method','sort'];

            let editRouteData = collectData('.marshrut-list__item_'+id, fields)[0];

            $("#modalEditMarshrut #select-direction").val(editRouteData["direction"]);

            $("#modalEditMarshrut #select-direction").trigger('change');

            $("#modalEditMarshrut #input-city-modal-edit").val(editRouteData["city"]);
            $("#modalEditMarshrut #input-address-modal-edit").val(editRouteData["address"]);
            $("#modalEditMarshrut #input-date-modal-edit").val(editRouteData["date"]);
            $("#modalEditMarshrut #input-time-modal-edit").val(editRouteData["time"]);
            $("#modalEditMarshrut #input-contact-modal-edit").val(editRouteData["contact"]);
            $("#modalEditMarshrut #input-phone-modal-edit").val(editRouteData["phone"]);
            $("#modalEditMarshrut #input-loading_method-modal-edit").val(editRouteData["loading_method"]);

            $("#modalEditMarshrut").modal("show");
        }
        $("#edit-marshrut").click(function (){
            $("#form-edit-marshrut").trigger("submit");
        });
        $("#form-edit-marshrut").submit(function (e) {
            e.preventDefault();
            let form = $(this).serializeArray();
            let form_data = {};

            form.forEach((form_element) => {
                form_data[form_element["name"]] = form_element["value"];
            });

            let marshrutId = $("#marshrut_id_form-edit-modal").val();

            $(".marshrut-list__item_"+marshrutId).data('direction',form_data["direction"]);
            $(".marshrut-list__item_"+marshrutId).data('city',form_data["city"]);
            $(".marshrut-list__item_"+marshrutId).data('address',form_data["address"]);
            $(".marshrut-list__item_"+marshrutId).data('date',form_data["date"]);
            $(".marshrut-list__item_"+marshrutId).data('time',form_data["time"]);
            $(".marshrut-list__item_"+marshrutId).data('contact',form_data["contact"]);
            $(".marshrut-list__item_"+marshrutId).data('phone',form_data["phone"]);
            $(".marshrut-list__item_"+marshrutId).data('loading_method',form_data["loading_method"]);

            $('.marshrut-address_'+marshrutId).html(form_data["city"]+", "+form_data["address"]);
            $('.marshrut-date_'+marshrutId).html(form_data["date"]);
            $('.marshrut-time_'+marshrutId).html(form_data["time"]);
            $('.marshrut-contact-face_'+marshrutId).html(form_data["contact"]);
            $('.marshrut-number_'+marshrutId).html(form_data["phone"]);
            $('.marshrut-method_'+marshrutId).html(form_data["loading_method"]);

            $("#modalEditMarshrut").modal("hide");
        });

        $(".direction-input").on('change', function(){
            if ($(this).val() === "1"){
                $('input[name="loading_method"]').prev("label").html('Способ погрузки <span class="text-danger">*</span>');
                $('input[name="loading_method"]').attr("placeholder", "Способ погрузки");
                $('input[name="city"]').prev("label").html('Откуда  <span class="text-danger">*</span>');
            }
            else{
                $('input[name="loading_method"]').prev("label").html('Способ выгрузки <span class="text-danger">*</span>');
                $('input[name="loading_method"]').attr("placeholder", "Способ выгрузки");
                $('input[name="city"]').prev("label").html('Куда <span class="text-danger">*</span>');
            }
        });

        $(".transportation-time-select").on('change', function(){
            switch($(this).val()) {
                case '0':
                    $("#transportation-time-input").val("");
                    $("#transportation-time-input").inputmask("99:99 - 99:99");
                    break;

                case '1':
                    $("#transportation-time-input").inputmask("remove");
                    $("#transportation-time-input").val("Время согласовать с менеджером");
                    break;

                case '2':
                    $("#transportation-time-input").inputmask("remove");
                    $("#transportation-time-input").val("Круглосуточно");
                    break;
            }
        });

        $(".transportation-time-input").on('input', function(){
            if ($("#style-select").val() === "0"){
                return;
            }

            let splitValue = $(this).val().split(" - ");
            let newValue = '';
            splitValue.forEach((item) => {
                let itemValues = item.replace(/_/g,"").split(":");
                if (itemValues[0] > 23){
                    itemValues[0] = 23;
                }
                if (itemValues[1] > 59){
                    itemValues[1] = 59;
                }
                newValue += itemValues[0] + itemValues[1];
            });
            $(this).val(newValue);
        });

        if ($("#style-select").val() === "0"){
            $("#transportation-time-input").inputmask("remove");
        }
        else{
            $("#transportation-time-input").inputmask("99:99 - 99:99");
        }




        const toastTrigger = document.getElementById('liveToastBtn')
        const toastLiveExample = document.getElementById('liveToast')

        if (toastTrigger) {
            const toastBootstrap = bootstrap.Toast.getOrCreateInstance(toastLiveExample)
            toastTrigger.addEventListener('click', () => {
                toastBootstrap.show()
            })
        }

        let validateFields = {
            // date: {element: $('#date'), name: 'Дата заявки'},
            applicationNumberForwarder: {element: $('#application_number'), name: 'Номер входящей заявки'},
            natureCargo: {element: $('.form-floating').has('#nature_cargo'), name: 'Характер груза'},
            idCustomer: {element: $('#id_customer'), name: 'Заказчик'},
            idForwarder: {element: $('#id_forwarder'), name: 'Экспедитор'},
            place: {element: $('#place'), name: 'Мест'},
            weight: {element: $('#weight'), name: 'Вес'},
            refMode: {element: $('#ref_mode'), name: 'Реф режим'},
            specialConditions: {element: $('#special_conditions'), name: 'Особые условия'},
            termsPayment: {element: $('#terms_payment'), name: 'Условия оплаты'},
            transportationCost: {element: $('#transportation_cost'), name: 'Стоимость перевозки'},
            taxationType: {element: $('#taxation_type'), name: 'Вид налогообложения'},
            // costCargo: {element: $('#cost_cargo'), name: 'Стоимость груза'},
            idDriver: {element: $('#id_driver'), name: 'Водитель'},
            carBrand: {element: $('#car_brand'), name: 'Марка '},
            governmentNumber: {element: $('#government_number'), name: 'Гос номер'},
            semitrailer: {element: $('#semitrailer'), name: 'П/П'},
            typeTransport: {element: $('#type_transport'), name: 'Тип транспорта'},
            typeCarcase: {element: $('#type_carcase'), name: 'Тип кузова'},


            // prrIdPrr: {element: $('.choices__inner').has('#prr_id_input'), name: 'ПРР->ПРР'},

        };

        console.log(validateFields);

        function removeValidateError(){
            for(key in validateFields)
                validateFields[key]['element'].removeClass('error-validate')
        }

        $('.application_add').on('click', function() {

            let $this = $(this);

            $this.attr('disabled', true);
            removeValidateError();

            let arrayData = {
                'date': $('#date').val(),
                'applicationNumberForwarder': $('#application_number').val(),
                'natureCargo': Editor_nature_cargo.getData(),
                'idCustomer': $('#id_customer').val(),
                'idForwarder': $('#id_forwarder').val(),
                'place': $('#place').val(),
                'weight': $('#weight').val(),
                'refMode': $('#ref_mode').val(),
                'specialConditions': Editor_special_conditions.getData(),
                'termsPayment': Editor_terms_payment.getData(),
                'transportationCost': $('#transportation_cost').val().replaceAll(' ', ''),
                'taxationType': $('#taxation_type').val(),
                // 'costCargo': $('#cost_cargo').val().replaceAll(' ', ''),
                'idDriver': $('#id_driver').val(),
                'carBrand': $('#car_brand').val(),
                'governmentNumber': $('#government_number').val(),
                'semitrailer': $('#semitrailer').val(),
                'typeTransport': $('#type_transport').val(),
                'typeCarcase': $('#type_carcase').val(),
                'transportationList': JSON.stringify(collect_route()),
                'additionalExpenses': JSON.stringify(collectExpenses()),
                'additionalProfit': JSON.stringify(collectAdditionalProfit()),
            };


            console.log(arrayData)

            $.ajax({
                url: '/ts/ajax/add-application-ts',
                method: 'POST',
                data: arrayData,
                success: function (response){
                    console.log(response);
                    $this.attr('disabled', false);


                    let data = JSON.parse(response);

                    if (data['status'] === "Success"){
                        document.location.href = "/ts/application?id=" + data['id'];
                    }
                    else if(data['status'] === "Error"){
                        alert("Не удалось добавить заявку");
                    }
                    else if(data['status'] === 'Validate Error'){
                        console.log(data);
                        console.log(data['error']);

                        let htmlError = ``;

                        for (key in data['error']){
                            if (validateFields[key] !== undefined) {
                                validateFields[key]['element'].addClass('error-validate');
                                htmlError += `<div>${validateFields[key]['name']}</div>`
                            }
                        }

                        $('#error-toast-container').html(htmlError);

                        $('#liveToastBtn').trigger('click');
                    }
                }
            });

        })
    </script>

<?php $controller->view('Components/footer');?>