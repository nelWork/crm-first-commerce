<?php
/**
 * @var App\User\Contoller\Common\HomeController $controller
 * @var array $PRRApplication
 */

//dd($PRRApplication);

$controller->view('Components/head');
$controller->view('Components/header');
 $controller->view('PRR/subheader-edit');?>

    <main class="container applications-new__form">
        <div class="form__header">
            <div class="wrapper">
                <div class="form__header-menu">
                <span href="#" class="form__header-menu-item carrier-tab">
                    <span class="menu-item-click active">ПРР</span>
                </span>
                    <span href="#" class="form__header-menu-item client-tab">
                    <span class="menu-item-click">Клиент</span>
                </span>
                </div>
            </div>
        </div>
<!--    ПРР    -->
        <div class="form__body__prr">
            <input type="hidden" value="<?php echo $PRRApplication['id']?>" id="prr_application_id">
            <div class="application-main d-flex flex-column">
                <div class="title-base">
                    Основное
                </div>
                <div class="row type-applications">
                    <div class="col-3">
                        <div class="mb-3">
                            <label for="basic-url" class="form-label">Заказчик (наше юр лицо) <span class="text-danger">*</span></label>
                            <select name="customer_prr" id="customer_prr" class="form-select select-add-application select">
                                <option value="" disabled>Заказчик</option>
                                <?php foreach ($listCustomers as $customer){ ?>
                                    <option value="<?php echo $customer['id']; ?>"
                                        <?php if($customer["id"] == $PRRApplication['customer_id_prr']) echo 'selected';?>>
                                        <?php echo $customer['name']; ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                </div>
                <div class="row form-main__select-cont">
                    <div class="col-6">
                        <div class="mb-3">
                            <label for="basic-url" class="form-label">ПРР <span class="text-danger">*</span></label>
                            <div class="d-flex gap-5 carrier_input_wrap">
                                <select name="prr_id" id="prr_id_input" class="form-select select-add-application  select">
                                    <option value="0" disabled selected>ПРР</option>
                                    <?php foreach ($listPRRCompany as $PRRCompany){ ?>
                                        <option value="<?php echo $PRRCompany['id']; ?>"
                                            <?php if($PRRCompany["id"] == $PRRApplication['prr_id_prr']) echo 'selected';?>>
                                            <?php echo $PRRCompany['name']; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                                <script>
                                    var choicesPRR = new Choices('#prr_id_input', {allowHTML: true,});
                                </script>
                            </div>
                        </div>
                    </div>
                    <div class="col-2 d-flex align-items-end mb-3">
                        <button class="btn btn-add-light" data-bs-toggle="modal" data-bs-target="#modalPrrCompany" >
                            Добавить
                        </button>
                    </div>
                    <div class="col-2 d-flex align-items-end mb-3">
                        <button class="btn btn-add-light d-none" data-bs-toggle="modal" data-bs-target="#modalCarrierEdit" id="modal_prr_edit">
                            Редактировать
                        </button>
                    </div>
                </div>
                <div class="prr-company-info info">
                    <div class="inn mb-4">
                        <span class="gray">ИНН</span>
                        <span id="prr-company_info_inn" class="prr-company_info_value"></span>
                    </div>
                    <div class="legal_address mb-4">
                        <span class="gray">Юридический адрес</span>
                        <span id="prr-company_info_legal_address" class="prr-company_info_value"></span>
                    </div>
                    <div class="info mb-4">
                        <span class="gray">Телефон, код в АТИ, конт. лицо, почта</span>
                        <span id="prr-company_info_info" class="prr-company_info_value"></span>
                    </div>
                </div>
            </div>
            <div class="application-transportation mt-5 d-flex flex-column gap-5">
                <div class="title-base">
                    Место погрузки/разгрузки
                </div>

                <div class="btn-wrap">
                    <button class="btn btn-add-light" data-bs-toggle="modal" data-bs-target="#modalMarshrut"
                            onclick="$('#form-add-marshrut-side').val('prr');">Добавить</button>
                </div>
                <div class="marshrut-list" id="marshrut-list">

                </div>

                <div class="application-transportation-data row">
                    <div class="col-7">
                        <div class="d-flex flex-column gap-3">
                    <span>
                        Характер груза <span class="text-danger">*</span>
                    </span>
                            <div class="form-floating" style="height: 100%">
                                <textarea class="form-control" placeholder="Характер груза" id="nature_cargo_prr" style="height: 100px">
                                    <?php echo $PRRApplication['nature_cargo_prr']; ?>
                                </textarea>
                            </div>
                            <script>
                                let Editor_nature_cargo_prr;
                                ClassicEditor
                                    .create( document.querySelector( '#nature_cargo_prr' ),
                                        {
                                            toolbar: [  'undo', 'redo', 'Heading', 'Link', 'bold', 'italic', 'numberedList', 'bulletedList' ]
                                        })
                                    .then( editor => {
                                        Editor_nature_cargo_prr = editor;
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
                                    <input type="text" placeholder="Мест" class="form-control form-control-solid"
                                           name="place" id="place-input_prr" aria-describedby="basic-addon3 basic-addon4"
                                           value="<?php echo $PRRApplication['place_prr']; ?>">
                                </div>
                            </div>
                            <div class="">
                                <label for="basic-url" class="form-label">Вес <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text" placeholder="Вес" class="form-control form-control-solid weight-input"
                                           name="weight" id="weight-input_prr" aria-describedby="basic-addon3 basic-addon4"
                                           value="<?php echo $PRRApplication['weight_prr']; ?>">
                                </div>
                            </div>
                            <div class="">
                                <label for="basic-url" class="form-label">Кол-во грузчиков <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text" placeholder="Кол-во грузчиков" class="form-control form-control-solid"
                                           name="ref-mode" id="number_loaders-input_prr" aria-describedby="basic-addon3 basic-addon4"
                                           value="<?php echo $PRRApplication['number_loaders_prr']; ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="application-special-conditions mt-5 d-flex flex-column gap-5">
                <div class="title-base">
                    Особые условия
                </div>
                <div class="form-floating" style="height: 100%">

                    <textarea class="form-control" placeholder="Укажите Особые условия" id="special-conditions_prr"
                              style="height: 100%" name="special-conditions"><?php echo $PRRApplication['special_condition_prr']; ?></textarea>
                </div>
                <script>
                    let Editor_special_conditions_prr;
                    ClassicEditor
                        .create( document.querySelector( '#special-conditions_prr' ),
                            {
                                toolbar: [  'undo', 'redo', 'Heading', 'Link', 'bold', 'italic', 'numberedList', 'bulletedList' ]
                            })
                        .then( editor => {
                            Editor_special_conditions_prr = editor;
                            console.log( editor );
                        } )
                        .catch( error => {
                            console.error( error );
                        } );
                </script>
            </div>

            <div class="application-cost my-5 d-flex flex-column gap-5">
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
                                <textarea class="form-control" placeholder="" id="cost_textarea_prr" style="height: 100%">
                                    <?php echo $PRRApplication['terms_payment_prr']; ?></textarea>
                            </div>
                            <script>
                                let Editor_cost_textarea_prr;
                                ClassicEditor
                                    .create( document.querySelector( '#cost_textarea_prr' ),
                                        {
                                            toolbar: [  'undo', 'redo', 'Heading', 'Link', 'bold', 'italic', 'numberedList', 'bulletedList' ]
                                        })
                                    .then( editor => {
                                        Editor_cost_textarea_prr = editor;
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
                                <label for="basic-url" class="form-label">Стоимость, ₽ <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text" placeholder="Стоимость" class="form-control form-control-solid"
                                           name="cost" id="cost-input_prr" aria-describedby="basic-addon3 basic-addon4"
                                           value="<?php echo number_format($PRRApplication['cost_prr'],0,'',' '); ?>">
                                </div>
                            </div>
                            <div class="">
                                <label for="basic-url" class="form-label">Вид налогообложения <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <select name="type_taxation_id" id="taxation-type-input_prr" class="form-select select-type-taxation select">
                                        <option value="0" disabled selected>Вид налогообложения</option>
                                        <?php foreach ($listTypesTaxation as $typeTaxation){ ?>
                                            <option value="<?php echo $typeTaxation["name"]; ?>"
                                                <?php if($typeTaxation["name"] == $PRRApplication['taxation_type_prr']) echo 'selected'; ?>>
                                                <?php echo $typeTaxation['name']; ?>
                                            </option>
                                        <?php } ?>
                                    </select>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="application-additional-costs d-flex flex-column gap-4">
                <div class="title-base">
                    Доп затраты
                </div>
                <div>
                    <button class="btn btn-add-light"
                            data-bs-toggle="modal" data-bs-target="#modalExpenses" onclick="$('#expenses-type-for').val(0);">Доп затраты</button>
                </div>
                <div id="expenses-prr-block_prr" class="w-75 mb-4">
                    <?php $counterAdditionalExpenses = 1; foreach ($additionalExpensesList as $additionalExpense): ?>
                        <div class="expenses-item expenses-item_<?php echo $counterAdditionalExpenses; ?>>"
                             data-type-expenses="<?php echo $additionalExpense["type_expenses"];?>"
                             data-sum="<?php echo $additionalExpense["sum"];?>" data-id="<?php echo $additionalExpense['id']; ?>"
                             data-type-payment="<?php echo $additionalExpense["type_payment"];?>" data-comment="<?php echo $additionalExpense["comment"];?>">
                            <div class="row">
                                <div class="col">
                                    <label for="">Вид затрат</label>
                                    <span class="expense-type-expenses_<?php echo $counterAdditionalExpenses; ?>"><?php echo $additionalExpense["type_expenses"];?></span>
                                </div>
                                <div class="col">
                                    <label for="">Сумма</label>
                                    <span class="expense-sum_<?php echo $counterAdditionalExpenses; ?>"><?php echo $additionalExpense["sum"];?></span>
                                </div>
                                <div class="col">
                                    <label for="">Вид налогообложения</label>
                                    <span class="expense-type-payment_<?php echo $counterAdditionalExpenses; ?>"><?php echo $additionalExpense["type_payment"];?></span>
                                </div>
                                <div class="col">
                                    <label for="">Комментарий</label>
                                    <span class="expense-comment_<?php echo $counterAdditionalExpenses; ?>"><?php echo $additionalExpense["comment"];?></span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
<!-- Клиент -->
        <div class="form__body__client">
            <div class="application-main d-flex flex-column">
                <div class="title-base">
                    Основное
                </div>
                <div class="row type-applications">
                    <div class="col-3">
                        <div class="mb-3">
                            <label for="basic-url" class="form-label">Заказчик (наше юр лицо) <span class="text-danger">*</span></label>
                            <select name="customer_client" id="customer_client" class="form-select select select-add-application">
                                <option value="0" disabled >Заказчик</option>
                                <?php foreach ($listCustomers as $customer){ ?>
                                    <option value="<?php echo $customer['id']; ?>"
                                        <?php if($customer["id"] == $PRRApplication['customer_id_client']) echo 'selected';?>>
                                        <?php echo $customer['name']; ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="mb-3">
                            <label for="">Номер Заявки</label>
                            <select name="application_id" id="application_id" class="form-select select-add-application select ">
                                <option value="0">Не привязывать заявку</option>
                                <?php foreach ($listApplication as $application){ ?>
                                    <option value="<?php echo $application["id"]; ?>" <?php if($application['id'] == $PRRApplication['id_application']) echo 'selected'; ?>>
                                        № <?php echo $application['application_number']; ?>
                                    </option>
                                <?php } ?>
                            </select>
                            <script>
                                var applicationNumberChoices = new Choices('#application_id', {allowHTML: true,});
                            </script>
                        </div>
                    </div>
                </div>
                <div class="row form-main__select-cont">
                    <div class="col-3">
                        <div class="mb-3">
                            <label for="basic-url" class="form-label">Клиент <span class="text-danger">*</span></label>
                            <div class="d-flex gap-5 client_input_wrap">
                                <select name="client" id="client_id_input" class="form-select select-add-application select ">
                                    <option value="0" disabled selected>Клиент</option>
                                    <?php foreach ($listClients as $client){ ?>
                                        <option value="<?php echo $client["id"]; ?>"
                                            <?php if($client["id"] == $PRRApplication['client_id_client']) echo 'selected';?>>
                                            <?php echo $client['name'] .' ' .$client['inn']; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                                <script>
                                    var choicesClient = new Choices('#client_id_input', {allowHTML: true,});
                                </script>
                            </div>
                        </div>
                    </div>

                    <div class="col-2 d-flex align-items-end mb-3">
                        <?php if($isFullCRMAccess): ?>
                            <button class="btn btn-add-light" data-bs-toggle="modal" data-bs-target="#modalAddClient" >
                                Добавить
                            </button>
                        <?php endif; ?>
                    </div>

                    <div class="col-2 d-flex align-items-end d-none mb-3">
                        <button class="btn btn-add-light" data-bs-toggle="modal" data-bs-target="#modalClientEdit" id="clientEdit_btn">
                            Редактировать
                        </button>
                    </div>
                    <div class="col mb-3 col-application_number_client d-none">
                        <label for="" class="form-label">Номер заявки клиента</label>
                        <input type="text" class="form-control" name="application_number_client" id="application_number_client">
                    </div>

                </div>
                <div class="client-info info">
                    <div class="inn mb-4">
                        <span class="gray">ИНН</span>
                        <span id="client_info_inn" class="client_info_value"></span>
                    </div>
                    <div class="legal_address mb-4">
                        <span class="gray">Юридический адрес</span>
                        <span id="client_info_legal_address" class="client_info_value"></span>
                    </div>
                    <div class="info mb-4">
                        <span class="gray">Телефон, код в АТИ, конт. лицо, почта</span>
                        <span id="client_info_info" class="client_info_value"></span>
                    </div>
                </div>
            </div>
            <div class="application-transportation mt-5 d-flex flex-column gap-5">
                <div class="title-base">
                    Место погрузки/разгрузки
                </div>
                <div class="btn-wrap">
                    <button class="btn btn-add-light" data-bs-toggle="modal"
                            data-bs-target="#modalMarshrut"
                            onclick="$('#form-add-marshrut-side').val('client');">
                        Добавить
                    </button>
                </div>
                <div class="marshrut-list" id="marshrut-list">

                </div>
                <div class="application-transportation-data row">
                    <div class="col-7">
                        <div class="d-flex flex-column gap-3">
                    <span>
                        Характер груза <span class="text-danger">*</span>
                    </span>
                            <div class="form-floating" style="height: 100%">
                                <textarea class="form-control" placeholder="" id="nature_cargo_client" style="height: 100px">
                                    <?php echo $PRRApplication['nature_cargo_client']; ?></textarea>
                            </div>
                        </div>
                        <script>
                            let Editor_nature_cargo_client;
                            ClassicEditor
                                .create( document.querySelector( '#nature_cargo_client' ),
                                    {
                                        toolbar: [  'undo', 'redo', 'Heading', 'Link', 'bold', 'italic', 'numberedList', 'bulletedList' ]
                                    })
                                .then( editor => {
                                    Editor_nature_cargo_client = editor;
                                    console.log( editor );
                                } )
                                .catch( error => {
                                    console.error( error );
                                } );
                        </script>
                    </div>
                    <div class="col-5">
                        <div class="d-flex flex-column gap-4">
                            <div class="">
                                <label for="basic-url" class="form-label">Мест <span class="text-danger">*</span> </label>
                                <div class="input-group">
                                    <input type="text" placeholder="Мест" class="form-control form-control-solid"
                                           name="place" id="place-input_client" aria-describedby="basic-addon3 basic-addon4"
                                           value="<?php echo $PRRApplication['place_client']; ?>">
                                </div>
                            </div>
                            <div>
                                <label for="basic-url" class="form-label">Вес <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text" placeholder="Вес" class="form-control form-control-solid weight-input"
                                           name="weight" id="weight-input_client" aria-describedby="basic-addon3 basic-addon4"
                                           value="<?php echo $PRRApplication['weight_client']; ?>">
                                </div>
                            </div>
                            <div>
                                <label for="basic-url" class="form-label">Кол-во грузчиков <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text" placeholder="Кол-во грузчиков" class="form-control form-control-solid"
                                           name="ref-mode" id="number_loaders-input_client" aria-describedby="basic-addon3 basic-addon4"
                                           value="<?php echo $PRRApplication['number_loaders_client']; ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="application-special-conditions mt-5 d-flex flex-column gap-5">
                <div class="title-base">
                    Особые условия
                </div>
                <div class="form-floating" style="height: 100%">
                    <textarea class="form-control" placeholder="Особые условия" id="special-conditions_client"
                              style="height: 100%" name="special-conditions"><?php echo $PRRApplication['special_condition_client']; ?></textarea>
                </div>
                <script>
                    let Editor_special_conditions_client;
                    ClassicEditor
                        .create( document.querySelector( '#special-conditions_client' ),
                            {
                                toolbar: [  'undo', 'redo', 'Heading', 'Link', 'bold', 'italic', 'numberedList', 'bulletedList' ]
                            })
                        .then( editor => {
                            Editor_special_conditions_client = editor;
                            console.log( editor );
                        } )
                        .catch( error => {
                            console.error( error );
                        } );
                </script>
            </div>

            <div class="application-cost my-5 d-flex flex-column gap-5">
                <div class="title-base">
                    Стоимость
                </div>
                <div class="btn-wrap">
                    Условия оплаты
                </div>
                <div class="application-cost-data row">
                    <div class="col-7">
                        <div class="d-flex flex-column gap-3">
                            <div class="form-floating " style="height: 100%">
                                <textarea class="form-control" placeholder="" id="cost_textarea_client" style="height: 100%">
                                    <?php echo $PRRApplication['terms_payment_client']; ?>
                                </textarea>

                            </div>
                            <script>
                                let Editor_cost_textarea_client;
                                ClassicEditor
                                    .create( document.querySelector( '#cost_textarea_client' ),
                                        {
                                            toolbar: [  'undo', 'redo', 'Heading', 'Link', 'bold', 'italic', 'numberedList', 'bulletedList' ]
                                        })
                                    .then( editor => {
                                        Editor_cost_textarea_client = editor;
                                        console.log( editor );
                                    } )
                                    .catch( error => {
                                        console.error( error );
                                    } );
                            </script>
                        </div>
                    </div>
                    <div class="col-5">
                        <div class="d-flex flex-column gap-4 ">
                            <div class="">
                                <label for="basic-url" class="form-label">Стоимость, ₽ <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text" placeholder="Стоимость" class="form-control form-control-solid"
                                           name="cost" id="cost-input_client" aria-describedby="basic-addon3 basic-addon4"
                                           value="<?php echo number_format($PRRApplication['cost_client'],0,'',' '); ?>">
                                </div>
                            </div>
                            <div class="">
                                <label for="basic-url" class="form-label">Вид налогообложения <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <select name="type_taxation_id" id="taxation-type-input_client" class="form-select select select-type-taxation">
                                        <option value="0" disabled selected>Вид налогообложения</option>
                                        <?php foreach ($listTypesTaxation as $typeTaxation){ ?>
                                            <option value="<?php echo $typeTaxation["name"]; ?>"
                                                <?php if($typeTaxation["name"] == $PRRApplication['taxation_type_client']) echo 'selected'; ?> >
                                                <?php echo $typeTaxation['name']; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="" style="padding-left: 20px;">
            <button class="btn btn-add-light duplicate mb-5"  style="margin-left: auto">
                Дублировать
            </button>
            <div class="form__header-menu">
                <span href="#" class="form__header-menu-item carrier-tab">
                    <span class="menu-item-click active">ПРР</span>
                </span>
                <span href="#" class="form__header-menu-item client-tab">
                    <span class="menu-item-click">Клиент</span>
                </span>

                <button class="btn btn-add-light application_add" id="" style="margin-left: auto; margin-bottom: 7px;">
                    Сохранить
                </button>
            </div>
        </div>
    </main>
    <div class="modal modal-add-application fade" id="modalPrrCompany" tabindex="-1" aria-labelledby="modalPrrCompany" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title title-base" id="exampleModalLabel">Добавление ПРР компании</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                </div>
                <div class="modal-body">
                    <form action="application/add-prr-company" method="post" id="form-add-prr-company" class="form-add-prr-company">
                        <div class="row">
                            <div class="col">
                                <div class="col" id="prr-company_add_inputs">
                                    <div class="mb-4">
                                        <label for="" class="mb-1">Название <span class="text-danger">*</span></label>
                                        <input type="text" name="prr-company" placeholder="Название" class="form-control form-control-solid" required>
                                    </div>
                                    <div class="mb-4">
                                        <label for="" class="mb-1">ИНН <span class="text-danger">*</span></label>
                                        <input type="number" name="prr-company_inn" placeholder="ИНН" id="add-prr-company-inn" class="form-control form-control-solid" required>
                                        <div id="error-prr-company-inn-isset" class='d-none small mt-2'>Экспедитор с таким ИНН уже есть</div>
                                    </div>
                                    <div class="mb-4">
                                        <label for="" class="mb-1">Юридический адрес <span class="text-danger">*</span></label>
                                        <textarea name="prr-company_legal_address" placeholder="Адрес" class="form-control form-control-solid" required></textarea>
                                    </div>
                                    <div class="mb-4">
                                        <label for="" class="mb-1">Телефон, конт. лицо, почта <span class="text-danger">*</span></label>
                                        <input type="text" name="prr-company_info" id="prr-company_info-input" placeholder="8 ()--, Имя:"
                                               class="form-control form-control-solid prr-company_info-input" required>
                                    </div>
                                </div>
                                <div class="mb-4 d-flex justify-content-between">
                                    <div id="add_prr-company_info_input" class="mb-1" style="text-decoration: underline; cursor: pointer;"><span class="text-success">Добавить еще</span></div>
                                    <div id="delete_prr-company_info_input" class="mb-1" style="text-decoration: underline; cursor: pointer;"><span class="text-danger">Удалить</span></div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light btn-close-modal" data-bs-dismiss="modal">Отменить</button>
                    <button type="button" class="btn btn-add-light" id="btn-add-prr-company">Добавить</button>
                </div>
            </div>
        </div>
    </div>


    <div class="modal modal-add-application fade" id="modalExpenses" tabindex="-1" aria-labelledby="modalExpenses" aria-hidden="true">
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
                            <input type="text" name="type_expenses" id="input-type_expenses" placeholder="Вид затрат"
                                   class="form-control form-control-solid mt-2" required>
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

    <div class="modal modal-add-application fade " id="modalEditExpenses"
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

    <div class="modal modal-add-application fade " id="modalAddClient" tabindex="-1" aria-labelledby="modalAddClient" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="min-width: 700px">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title title-base" id="exampleModalLabel">Добавление клиента</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                </div>
                <div class="modal-body">
                    <form action="/application/ajax/add-client" method="post" id="form-add-client-from" class="form-add-client">
                        <div class="row">
                            <div class="col" id="client_add_inputs">
                                <h5 class="mb-3">
                                    Основная информация
                                </h5>
                                <div class="mb-4">
                                    <label for="" class="mb-1">По чьей заявке работаем?</label>
                                    <select name="application_format" id="" class="form-select">
                                        <option value="" disabled selected>Выберите вариант</option>
                                        <option value="0">По ООО</option>
                                        <option value="1">По заявке клиента</option>
                                    </select>
                                </div>
                                <div class="mb-4">
                                    <label for="" class="mb-1">Вид налогообложения <span class="text-danger">*</span></label>
                                    <select name="type_taxation_id" id="" class="form-select select-type-taxation">
                                        <option value="0" disabled selected>Вид налогообложения</option>
                                        <?php foreach ($listTypesTaxation as $typeTaxation){ ?>
                                            <option value="<?php echo $typeTaxation["name"]; ?>">
                                                <?php echo $typeTaxation['name']; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="mb-4">
                                    <label for="" class="mb-1">Название <span class="text-danger">*</span></label>
                                    <input type="text" name="client_name" placeholder="Название" class="form-control form-control-solid" required>
                                </div>
                                <div class="mb-4">
                                    <label for="" class="mb-1">ИНН <span class="text-danger">*</span></label>
                                    <input type="number" name="client_inn" placeholder="ИНН" class="form-control form-control-solid" required>
                                </div>
                                <div class="mb-4">
                                    <label for="" class="mb-1">Юридический адрес <span class="text-danger">*</span></label>
                                    <textarea name="client_legal_address" placeholder="Адрес" class="form-control form-control-solid" required></textarea>
                                </div>
                            </div>
                            <div class="col">
                                <h5 class="mb-3">
                                    Контактное лицо
                                </h5>
                                <div class="mb-4">
                                    <label for="" class="mb-1">ФИО <span class="text-danger">*</span></label>
                                    <input type="text" name="client_full_name" placeholder="ФИО" class="form-control form-control-solid" required>
                                </div>
                                <div class="mb-4">
                                    <label for="" class="mb-1">Должность <span class="text-danger">*</span></label>
                                    <input type="text" name="client_job_title" placeholder="Должность" class="form-control form-control-solid" required>
                                </div>
                                <div class="mb-4">
                                    <label for="" class="mb-1">Телефон, код в АТИ, конт. лицо, почта <span class="text-danger">*</span></label>
                                    <input type="text" name="client_phone" id="client-phone-input" placeholder="8 ()--" class="form-control form-control-solid client-phone-input" required value="8 ()--">
                                </div>
                                <div class="mb-4">
                                    <label for="" class="mb-1">Почта <span class="text-danger">*</span></label>
                                    <input type="email" name="client_email" id="client-email-input" placeholder="Почта" class="form-control form-control-solid client-phone-input" required>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light btn-close-modal" data-bs-dismiss="modal">Отменить</button>
                    <button type="button" class="btn btn-add-light" id="btn-add-client-from">Добавить</button>
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

    <div class="modal modal-add-application fade" id="modalMarshrut" tabindex="-1" aria-labelledby="modalMarshrut" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title title-base" id="exampleModalLabel">Адрес погрузки/разгрузки</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                </div>
                <div class="modal-body">
                    <form action="" id="form-add-marshrut">
                        <div class="w-25">
                            <input class="d-none" id="form-add-marshrut-side" value="prr">
                            <select name="direction" id="direction-input" class="form-select  mb-4 direction-input" required>
                                <option value="1">Погрузка</option>
                                <option value="0">Разгрузка</option>
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-6 mb-4" style="position: relative">
                                <label for="" class="label mb-2">Погрузка <span class="text-danger">*</span></label>
                                <input type="text" name="city" city-api="true" class="form-control form-control-solid"
                                       placeholder="Выберите город" required>
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
                    <h3 class="modal-title title-base" id="exampleModalLabel">Адрес погрузки/разгрузки</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                </div>
                <div class="modal-body">
                    <form action="" id="form-edit-marshrut">
                        <input type="number" class="d-none" id="marshrut_id_form-edit-modal">
                        <div class="w-25">
                            <input class="d-none" id="form-edit-marshrut-side" value="carrier">
                            <select name="direction" id="select-direction" class="form-select mb-4 direction-input" required>
                                <option value="1">Погрузка</option>
                                <option value="0">Разгрузка</option>
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-6 mb-4">
                                <label for="" class="label mb-2">Погрузка <span class="text-danger">*</span></label>
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


    <script>

        let marshrutIndex = 0;
        let fineIndex = 0;
        let expenseIndex = 0;
        let additionalProfitIndex = 0;

        let html_ = '';
        let direction_ = "";

        <?php foreach ($listPlacePRR as $placePrr){?>
        marshrutIndex++;
        <?php if($placePrr["direction"] === 1){?>
        direction_ = "Погрузка";
        <?php }
        else{?>
        direction_ = "Разгрузка";
        <?php }?>

        html_ = `<div class="marshrut-list__item row marshrut-list__item_${marshrutIndex}"
                            data-direction="<?php echo $placePrr["direction"];?>" data-city="<?php echo $placePrr["city"];?>"
                            data-address="<?php echo $placePrr["address"];?>" data-date="<?php echo $placePrr["date"];?>"
                            data-time="<?php echo $placePrr["time"];?>" data-contact="<?php echo $placePrr["contact"];?>"
                            data-phone="<?php echo $placePrr["phone"];?>" data-sort="<?php echo $placePrr["sort"];?>">
                    <div class="drag-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#B5B5C3"
                             class="bi bi-grip-vertical" viewBox="0 0 16 16">
                            <path d="M7 2a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zM7 5a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zM7 8a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm-3 3a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm-3 3a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"></path>
                        </svg>
                    </div>
                    <div class="col-2">
                        <label for="">${direction_}</label>
                        <span class="marshrut-address_${marshrutIndex}">
                            <?php echo $placePrr["city"];?>, <?php echo $placePrr["address"];?>
                        </span>
                    </div>
                    <div class="col-2">
                        <label for="">Дата</label>
                        <span class="marshrut-date_${marshrutIndex}">
                            <?php echo $placePrr["date"];?>
                        </span>
                    </div>
                    <div class="col">
                        <label for="">Время</label>
                        <span class="marshrut-time_${marshrutIndex}">
                            <?php echo $placePrr["time"];?>
                        </span>
                    </div>
                    <div class="col">
                        <label for="">Контактное лицо</label>
                        <span class="marshrut-contact-face_${marshrutIndex}">
                            <?php echo $placePrr["contact"];?>
                        </span>
                    </div>
                    <div class="col">
                        <label for="">Номер</label>
                        <span class="marshrut-number_${marshrutIndex}">
                            <?php echo $placePrr["phone"];?>
                        </span>
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

        <?php if($placePrr["type_for"] === 1){?>
        $('.form__body__client #marshrut-list').append(html_);
        <?php }else{?>
        $('.form__body__prr #marshrut-list').append(html_);
        <?php }?>

        <?php }?>

        let html__  = "";

        function collect_route(side){
            let fields = ['direction','city','address','date','time','contact','phone','sort'];

            if(side === 'client'){
                return collectData('.form__body__client .marshrut-list__item', fields);
            }
            return collectData('.form__body__prr .marshrut-list__item', fields);
        }

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



        function editRoute(id){
            $("#marshrut_id_form-edit-modal").val(id);
            let fields = ['direction','city','address','date','time','contact','phone','sort'];

            let editRouteData = collectData('.marshrut-list__item_'+id, fields)[0];

            $("#modalEditMarshrut #select-direction").val(editRouteData["direction"]);
            $("#modalEditMarshrut #input-city-modal-edit").val(editRouteData["city"]);
            $("#modalEditMarshrut #input-address-modal-edit").val(editRouteData["address"]);
            $("#modalEditMarshrut #input-date-modal-edit").val(editRouteData["date"]);
            $("#modalEditMarshrut #input-time-modal-edit").val(editRouteData["time"]);
            $("#modalEditMarshrut #input-contact-modal-edit").val(editRouteData["contact"]);
            $("#modalEditMarshrut #input-phone-modal-edit").val(editRouteData["phone"]);

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

            $('.marshrut-address_'+marshrutId).html(form_data["city"]+", "+form_data["address"]);
            $('.marshrut-date_'+marshrutId).html(form_data["date"]);
            $('.marshrut-time_'+marshrutId).html(form_data["time"]);
            $('.marshrut-contact-face_'+marshrutId).html(form_data["contact"]);
            $('.marshrut-number_'+marshrutId).html(form_data["phone"]);

            $("#modalEditMarshrut").modal("hide");
        });


        $(".direction-input").on('change', function(){
            if ($(this).val() === "1"){
                $('input[name="city"]').prev("label").html('Погрузка  <span class="text-danger">*</span>');
            }
            else{
                $('input[name="city"]').prev("label").html('Разгрузка <span class="text-danger">*</span>');
            }
        });

        $('#add-marshrut').click(function () {
            $('#btn-form-add-marshrut').trigger('click');
        });

        $('body').on('click','.js-delete-marshrut', function () {
            $('.marshrut-list__item').has(this).remove();
        });

        $('#form-add-marshrut').submit(function (e) {
            e.preventDefault();
            let form = $(this).serializeArray();

            console.log(form);
            let side = $('#form-add-marshrut-side').val();

            let direction = 'Разгрузка';

            if(form[0]["value"] == 1){
                direction = 'Погрузка';
            }
            // gggg

            marshrutIndex++;

            let html = `<div class="marshrut-list__item row marshrut-list__item_${marshrutIndex}"
                        data-direction="${form[0]["value"]}" data-city="${form[1]["value"]}"
                        data-address="${form[3]["value"]}" data-date="${form[5]["value"]}"
                        data-time="${form[6]["value"]}" data-contact="${form[2]["value"]}"
                        data-phone="${form[4]["value"]}" data-sort="">
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
                    <span class="marshrut-time_${marshrutIndex}">${form[6]['value']}</span>
                </div>
                <div class="col">
                    <label for="">Контактное лицо</label>
                    <span class="marshrut-contact-face_${marshrutIndex}">${form[2]['value']}</span>
                </div>
                <div class="col">
                    <label for="">Номер</label>
                    <span class="marshrut-number_${marshrutIndex}">${form[4]['value']}</span>
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


            $('.form__body__' + side +' #marshrut-list').append(html);


            $('#form-add-marshrut').trigger('reset');
            $('.direction-input').trigger('change');
        })
    </script>

    <script>
        $('.client-info').hide();
        $('#client_id_input').on('change', function (){
            $('#clientEdit_btn').show();
            let client_id = $('#client_id_input').val();

            ajaxGetClient(client_id);
        });

        $('#client_id_input').trigger('change');
        function ajaxGetClient(client_id){
            $.ajax({
                url: '/application/ajax/get-client',
                method: 'POST',
                data: {id: client_id},
                success: function (client_json){
                    let client_array = JSON.parse(client_json);
                    console.log(client_array);

                    $('.client-info').show();
                    $('.client-info #client_info_inn').html(client_array['inn']);
                    $('.client-info #client_info_legal_address').html(client_array['legal_address']);

                    $('.client-info #client_info_info').html(client_array['phone']);

                    // let infoArrayCarrier = carrier_array['info'].split("||");

                    let infoHtmlClient = `<select name="client_chosen_info" id="client_chosen_info" class="form-select select-add-application united-select select">
                    <option value="${client_array['phone']}">${client_array['phone']}</option>`;

                    client_array['phones'].forEach((item) => {
                        infoHtmlClient += `<option value="${item['phone']}">${item['phone']}</option>`
                    });

                    infoHtmlClient += `</select>`;

                    $('.client-info #client_info_info').html(infoHtmlClient);
                }
            });
        }
    </script>
    <script>
        $('.prr-company-info').hide();



        $('#prr_id_input').change(function () {
            ajaxGetPRRCompany($(this).val())
        })
        $('#prr_id_input').trigger('change');
        function ajaxGetPRRCompany(prr_company_id){
            $.ajax({
                url: '/prr/application/ajax/get-prr-company',
                method: 'POST',
                data: {id: prr_company_id},
                success: function (prr_json){
                    $('#modal_carrier_edit').removeClass('d-none');

                    let prr_company_array = JSON.parse(prr_json);

                    // $('#carrier_edit_id').val(prr_company_array['id']);
                    // $('#carrier_edit_name').val(prr_company_array['name']);
                    // $('#carrier_edit_inn').val(prr_company_array['inn']);
                    // $('#carrier_edit_legal_address').val(prr_company_array['legal_address']);
                    //
                    //
                    //
                    // let newInfoArray = prr_company_array['info'].split('||');


                    $('.prr-company-info').show();
                    $('.prr-company-info #prr-company_info_inn').html(prr_company_array['inn']);
                    $('.prr-company-info #prr-company_info_legal_address').html(prr_company_array['legal_address']);


                    let infoArrayPRRCompany = prr_company_array['contact'].split("||");

                    let infoHtmlPRRCompany = `<select name="prr-company_chosen_info" id="prr-company_chosen_info" class="form-select select-add-application united-select select">`;

                    infoArrayPRRCompany.forEach((item) => {
                        infoHtmlPRRCompany += `<option value="${item}">${item}</option>`
                    });

                    infoHtmlPRRCompany += `</select>`;

                    $('.prr-company-info #prr-company_info_info').html(infoHtmlPRRCompany);
                }
            });
        }
        $('#form-add-prr-company').submit(function (e) {
            e.preventDefault();

            let form = $(this).serializeArray();
            let form_data = {};

            form.forEach((form_element) => {
                form_data[form_element["name"]] = form_element["value"];
            });

            var prr_company_info_inputs = Array.from($(".prr-company_info-input"));
            var prr_company_info_inputs_values = [];

            prr_company_info_inputs.forEach((prr_company_info_input)=>{
                prr_company_info_inputs_values.push(prr_company_info_input.value);
            });

            $.ajax({
                url: '/prr/ajax/add-prr-company',
                method: 'POST',
                data: {form_data: form_data, info_inputs: prr_company_info_inputs_values},
                success: function (data_json){
                    let data = JSON.parse(data_json);

                    console.log(data);

                    if (data['status'] === "Success"){
                        $('#form-add-prr-company').trigger('reset');
                        $('#modalPrrCompany').modal("hide");
                        $(".prr_input_wrap").html(`<select name="prr_id" id="prr_id_input" class="form-select select-add-application  select">
                         <option value="0" disabled selected>ПРР</option>
                            <?php foreach ($listPRRCompany as $prrCopmany){ ?>
                                <option value="<?php echo $prrCopmany['id']; ?>"><?php echo $prrCopmany['name'] ; ?></option>
                                <?php } ?>
                         <option value="${data['data']['id']}">${data['data']['name']}</option>
                     </select>
                     <script>
                         <`+"/script>");

                        let prr_id = data['data']['id'];

                        ajaxGetPRRCompany(prr_id);

                        let sselect = document.getElementById('prr_id_input');
                        sselect.value = data['data']['id'];

                        choicesPRR = new Choices('#prr_id_input', {allowHTML: true,});
                    }
                    else if(data['status'] === "Error"){
                        if(data['errorText'])
                            alert(data['errorText']);

                        if(data['error']){
                            for(key in data['error']){
                                $('.form-control[name="prr-company_' + key + '"]').addClass('error-validate');
                                if(key == 'address')
                                    $('.form-control[name="prr-company_legal_' + key + '"]').addClass('error-validate');

                            }
                        }

                        if(!data['errorText'] && !data['error'])
                            alert("Не удалось добавить ПРР компанию");

                    }
                    else{
                        alert("Поля заполнены не правильно !");
                    }
                }
            });
        });

        $('#btn-add-prr-company').click(function () {
            $('#form-add-prr-company').trigger('submit');
        });
    </script>


    <script>
        $('.inputmask-phone').inputmask('8 (999) 999-99-99');
        function formatNumber(num) {
            return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1 ')
        }



        document.getElementById('cost-input_prr').oninput = function (){
            let value = $('#cost-input_prr').val();
            value = value.replaceAll(' ' ,'');
            console.log(value);

            $('#cost-input_prr').val(formatNumber(value));
        }

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
        document.getElementById('cost-input_client').oninput = function (){
            let value = $('#cost-input_client').val();
            value = value.replaceAll(' ' ,'');
            console.log(value);

            $('#cost-input_client').val(formatNumber(value));
        }
    </script>
    <script>
        function collectExpenses(){
            let fields = ['type-for','type-expenses','sum','type-payment','comment'];

            return collectData('#expenses-prr-block_prr .expenses-item', fields);
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
            customerIdPrr: {element: $('#customer_prr'), name: 'ПРР->Заказчик'},
            prrIdPrr: {element: $('.choices__inner').has('#prr_id_input'), name: 'ПРР->ПРР'},
            customerIdClient: {element: $('#customer_client'), name: 'Клиент->Заказчик'},
            applicationNumberClient: {element: $('#application_number_client'), name: 'Клиент->Номер заявки клиента'},
            clientIdClient: {element: $('.choices__inner').has('#client_id_input'), name: 'Клиент->Клиент'},
            natureCargoPrr: {element: $('.form-floating').has('#nature_cargo_prr'), name: 'ПРР->Характер груза'},
            natureCargoClient: {element: $('.form-floating').has('#nature_cargo_client'), name: 'Клиент->Характер груза'},
            placePrr: {element: $('#place-input_prr'), name: 'ПРР->Мест'},
            placeClient: {element: $('#place-input_client'), name: 'Клиент->Мест'},
            weightPrr: {element: $('#weight-input_prr'), name: 'ПРР->Вес'},
            weightClient: {element: $('#weight-input_client'), name: 'Клиент->Вес'},
            refModePrr: {element: $('#ref-mode-input_prr'), name: 'ПРР->Реф режим'},
            refModeClient: {element: $('#ref-mode-input_client'), name: 'Клиент->Реф режим'},
            cityPrr: {element: $('#city_prr'), name: 'ПРР->Город'},
            cityClient: {element: $('#city_client'), name: 'Клиент->Город'},
            addressPrr: {element: $('#address_prr'), name: 'ПРР->Город'},
            addressClient: {element: $('#address_client'), name: 'Клиент->Город'},
            datePrr: {element: $('#date_prr'), name: 'ПРР->Дата'},
            dateClient: {element: $('#date_client'), name: 'Клиент->Дата'},
            transportationCostPrr: {element: $('#cost-input_prr'), name: 'ПРР->Стоимость'},
            transportationCostClient: {element: $('#cost-input_client'), name: 'Клиент->Стоимость'},
            taxationTypePrr: {element: $('#taxation-type-input_prr'), name: 'Перевозчик->Вид налогообложения'},
            taxationTypeClient: {element: $('#taxation-type-input_client'), name: 'Клиент->Вид налогообложения'},
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
                'id': $('#prr_application_id').val(),
                // 'cityPrr': $('#city_prr').val(),
                'chosenContactClient': $('#client_chosen_info').val(),
                'chosenContactPrr': $('#prr-company_chosen_info').val(),
                // 'cityClient': $('#city_client').val(),
                // 'contactPrr': $('#contact_prr').val(),
                // 'contactClient': $('#contact_client').val(),
                // 'addressPrr': $('#address_prr').val(),
                // 'addressClient': $('#address_client').val(),
                // 'phonePrr': $('#phone_prr').val(),
                // 'phoneClient': $('#phone_client').val(),
                // 'datePrr': $('#date_prr').val(),
                // 'dateClient': $('#date_client').val(),
                // 'timePrr': $('#time_prr').val(),
                // 'timeClient': $('#time_client').val(),
                'placePrr': $('#place-input_prr').val(),
                'placeClient': $('#place-input_client').val(),
                'weightPrr': $('#weight-input_prr').val(),
                'weightClient': $('#weight-input_client').val(),
                'numberLoadersPrr': $('#number_loaders-input_prr').val(),
                'numberLoadersClient': $('#number_loaders-input_client').val(),
                'customerIdPrr': $('#customer_prr').val(),
                'customerIdClient': $('#customer_client').val(),
                'prrIdPrr': $('#prr_id_input').val(),
                'clientIdClient': $('#client_id_input').val(),
                'natureCargoPrr': Editor_nature_cargo_prr.getData(),
                'natureCargoClient': Editor_nature_cargo_client.getData(),
                'specialConditionPrr': Editor_special_conditions_prr.getData(),
                'specialConditionClient': Editor_special_conditions_client.getData(),
                'termsPaymentPrr': Editor_cost_textarea_prr.getData(),
                'termsPaymentClient': Editor_cost_textarea_client.getData(),
                'costPrr': $('#cost-input_prr').val().replaceAll(' ',''),
                'costClient': $('#cost-input_client').val().replaceAll(' ',''),
                'taxationTypePrr': $('#taxation-type-input_prr').val(),
                'taxationTypeClient': $('#taxation-type-input_client').val(),
                'additionalExpenses': JSON.stringify(collectExpenses()),
                'idApplication': $('#application_id').val(),
                'placeListClient': JSON.stringify(collect_route("client")),
                'placeListPrr': JSON.stringify(collect_route("prr"))
            };


            console.log(arrayData)

            $.ajax({
                url: '/prr/ajax/edit-application-prr',
                method: 'POST',
                data: arrayData,
                success: function (response){
                    console.log(response);
                    $this.attr('disabled', false);


                    let data = JSON.parse(response);

                    if (data['status'] === "Success"){
                        document.location.href = "/prr/prr_application?id=" + data['id'];
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


        $('body').on('click','.js-delete-expenses', function () {
            $('.expenses-item').has(this).remove();
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
        $('#add-expenses').click(function () {
            $('#form-expenses').trigger('submit');
        });
        let expenseId = <?php echo $counterAdditionalExpenses; ?>;
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

            console.log(html)

            if (data['type_for'] === '1'){
                $('#expenses-prr-block_client').append(html);
            }
            else if(data['type_for'] === '0'){
                $('#expenses-prr-block_prr').append(html);
            }
        })

        $('.duplicate').on('click', function() {

            let marshruts_prr = collect_route("prr");

            let html = ``;
            console.log(marshruts_prr);
            let direction;

            for(let i = 0; i < marshruts_prr.length; i++){
                direction = 'Разгрузка';
                let item = marshruts_prr[i];
                if(item['direction'] == 1){
                    direction = 'Погрузка';
                }

                let marshrutIndex = i;


                html += `<div class="marshrut-list__item row marshrut-list__item_${i}"
                        data-direction="${item['direction']}" data-city="${item['city']}"
                        data-address="${item['address']}" data-date="${item['date']}"
                        data-time="${item['time']}" data-contact="${item['contact']}"
                        data-phone="${item['phone']}" data-loading_method="${item['loading_method']}" data-sort="">
                <div class="drag-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#B5B5C3"
                         class="bi bi-grip-vertical" viewBox="0 0 16 16">
                        <path d="M7 2a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zM7 5a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zM7 8a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm-3 3a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm-3 3a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"></path>
                    </svg>
                </div>
                <div class="col-2">
                    <label for="">${direction}</label>
                    <span class="marshrut-address_${marshrutIndex}">${item['city']}, ${item['address']}</span>
                </div>
                <div class="col-2">
                    <label for="">Дата</label>
                    <span class="marshrut-date_${marshrutIndex}">${item['date']}</span>
                </div>
                <div class="col">
                    <label for="">Время</label>
                    <span class="marshrut-time_${marshrutIndex}">${item['time']}</span>
                </div>
                <div class="col">
                    <label for="">Контактное лицо</label>
                    <span class="marshrut-contact-face_${marshrutIndex}">${item['contact']}</span>
                </div>
                <div class="col">
                    <label for="">Номер</label>
                    <span class="marshrut-number_${marshrutIndex}">${item['phone']}</span>
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
            }

            $('.form__body__client #marshrut-list').html(html);


            var data = {
                'city_client': '#city_prr',
                'contact_client': '#contact_prr',
                'address_client': '#address_prr',
                'phone_client': '#phone_prr',
                'date_client': '#date_prr',
                'time_client': '#time_prr',
                'place-input_client': '#place-input_prr',
                'weight-input_client': '#weight-input_prr',
                'number_loaders-input_client': '#number_loaders-input_prr',
                'customer_client': '#customer_prr'
            };

            Editor_nature_cargo_client.setData(Editor_nature_cargo_prr.getData());

            for (var key in data) {
                $('#' + key).val($(data[key]).val());
            }
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
                    var cityApiSelect2 = $('.col-6').has(this).find('.custom-dropdown');
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
                                    console.log(itemSelect)
                                    console.log(jQuery(this)['0'].title)
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
        $('.form__body__client').hide();

        $('.client-tab').click(function (){
            $('.form__body__prr').hide();
            $('.form__body__client').show();

            $('.menu-item-click').removeClass('active');
            $(".client-tab .menu-item-click").addClass('active');
        });
        $('.carrier-tab').click(function (){
            $('.form__body__client').hide();
            $('.form__body__prr').show();

            $('.menu-item-click').removeClass('active');
            $(".carrier-tab .menu-item-click").addClass('active');
        });
    </script>
<?php $controller->view('Components/footer');?>