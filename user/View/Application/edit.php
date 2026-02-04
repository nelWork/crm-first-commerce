<?php
/**
 * @var App\User\Contoller\Common\HomeController $controller
 */

//dd($application);
$controller->view('Components/head');
$controller->view('Components/header');
$controller->view('Application/subheader-edit');

?>

    <main class="container applications-new__form">
        <div class="form__header">
            <div class="wrapper">
                <div class="form__header-menu">
                <span href="#" class="form__header-menu-item carrier-tab">
                    <span class="menu-item-click active">Перевозчик</span>
                </span>
                    <span href="#" class="form__header-menu-item client-tab">
                    <span class="menu-item-click">Клиент</span>
                </span>
                    <!--<button class="btn btn-add-light" id="duplicate" style="margin-left: auto">-->
                    <!--    Дублировать-->
                    <!--</button>-->
                    <!--<select name="" id="style-select" class="form-select select-add-application" style="width: 150px">-->
                    <!--    <option value=1 selected>Класика</option>-->
                    <!--    <option value=0>Свободный</option>-->
                    <!--</select>-->
                    <!--<button class="btn btn-add-light application_add" id="application_add" style="margin-left: auto">-->
                    <!--    Сохранить-->
                    <!--</button>-->
                </div>
            </div>
        </div>
        <div class="form__body__carrier">
            <div class="application-main d-flex flex-column">
                <div class="title-base">
                    Основное
                </div>
                <div class="row type-applications">
                    <div class="col-3">
                        <div class="mb-3">
                            <label for="basic-url" class="form-label">Заказчик (наше юр лицо) <span class="text-danger">*</span></label>
                            <!--                <div class="input-group">-->
                            <!--                    <input type="text" placeholder="Заказчик"-->
                            <!--                           class="form-control" name="customer-input"-->
                            <!--                           id="customer-input" aria-describedby="basic-addon3 basic-addon4">-->
                            <!--                </div>-->
                            <select name="customer_carrier" id="customer_carrier" class="form-select select-add-application select">
                                <option value="0" disabled selected>Заказчик</option>
                                <?php foreach ($listCustomers as $customer){ ?>
                                    <option value="<?php echo $customer['id']; ?>"><?php echo $customer['name']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-7">
                        <div class="mb-3">
                            <label for="basic-url" class="form-label">Заголовок заявки</label>
                            <div class="input-group">
                                <input type="text" placeholder="Заголовок" class="form-control form-control-solid" name="main-slogan" id="main-slogan-input_carrier" aria-describedby="basic-addon3 basic-addon4" value="<?php echo $application["application_title_Carrier"]; ?>">
                            </div>
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="mb-3">
                            <label for="basic-url" class="form-label">Дата заявки*</label>
                            <div class="input-group">
                                <input disabled type="date" class="form-control" name="date" id="date-input_carrier" aria-describedby="basic-addon3 basic-addon4" value="<?php echo $controller->format_date($application["date"], "Y-m-d");?>">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row form-main__select-cont">
                    <div class="col-3">
                        <div class="mb-3">
                            <label for="basic-url" class="form-label">Перевозчик <span class="text-danger">*</span></label>
                            <div class="d-flex gap-5 carrier_input_wrap">
                                <!--                     <div class="input-group ">-->
                                <!--                         <input type="text" placeholder="Перевозчик" class="form-control" name="customer-input" id="customer-input" aria-describedby="basic-addon3 basic-addon4">-->
                                <!--                     </div>-->
                                <select name="carrier_id" id="carrier_id_input" class="form-select select-add-application  select">
                                    <option value="0" disabled selected>Перевозчик</option>
                                    <?php foreach ($listCarriers as $carrier){ ?>
                                        <option value="<?php echo $carrier['id']; ?>"><?php echo $carrier['name'] .' ' .$carrier['inn']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-2 d-flex align-items-end mb-3">
                        <button class="btn btn-add-light" data-bs-toggle="modal" data-bs-target="#modalCarrier" >
                            Добавить
                        </button>
                    </div>
                    <div class="col-2 d-flex align-items-end mb-3">
                        <button class="btn btn-add-light d-none" data-bs-toggle="modal" data-bs-target="#modalCarrierEdit" id="modal_carrier_edit">
                            Редактировать
                        </button>
                    </div>
                </div>
                <div class="carrier-info info mt-3">
                    <div class="inn mb-4">
                        <span class="gray">ИНН</span>
                        <span id="carrier_info_inn" class="carrier_info_value"></span>
                    </div>
                    <div class="legal_address mb-4">
                        <span class="gray">Юридический адрес</span>
                        <span id="carrier_info_legal_address" class="carrier_info_value"></span>
                    </div>
                    <div class="info mb-4">
                        <span class="gray">Телефон, код в АТИ, конт. лицо, почта</span>
                        <span id="carrier_info_info" class="carrier_info_value"></span>
                    </div>
                </div>
            </div>
            <div class="application-transportation mt-5 d-flex flex-column gap-5">
                <div class="title-base">
                    Транспортировка
                </div>
                <div class="btn-wrap">
                    <button class="btn btn-add-light" data-bs-toggle="modal" data-bs-target="#modalMarshrut" onclick="$('#form-add-marshrut-side').val('carrier');">Добавить маршрут</button>
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
                                <textarea class="form-control" placeholder="Характер груза" id="nature_cargo_carrier" style="height: 100px"><?php echo $application["nature_cargo_Carrier"];?></textarea>
                            </div>
                            <script>
                                let Editor_nature_cargo_carrier;
                                ClassicEditor
                                    .create( document.querySelector( '#nature_cargo_carrier' ),
                                        {
                                            toolbar: [  'undo', 'redo', 'Heading', 'Link', 'bold', 'italic', 'numberedList', 'bulletedList' ]
                                        })
                                    .then( editor => {
                                        Editor_nature_cargo_carrier = editor;
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
                                    <input type="text" placeholder="Мест" class="form-control form-control-solid" name="place" id="place-input_carrier" aria-describedby="basic-addon3 basic-addon4"
                                           value = "<?php echo $application["place_Carrier"];?>" >
                                </div>
                            </div>
                            <div class="">
                                <label for="basic-url" class="form-label">Вес <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text" placeholder="Вес" class="form-control form-control-solid weight-input" name="weight" id="weight-input_carrier" aria-describedby="basic-addon3 basic-addon4"
                                           value = "<?php echo $application["weight_Carrier"];?>" >
                                </div>
                            </div>
                            <div class="">
                                <label for="basic-url" class="form-label">Реф режим <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text" placeholder="Реф режим" class="form-control form-control-solid" name="ref-mode" id="ref-mode-input_carrier" aria-describedby="basic-addon3 basic-addon4"
                                           value = "<?php echo $application["ref_mode_Carrier"];?>" >
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
                <div class="application-driver-data row justify-content-end">
                    <div class="col-6">
                        <div class="driver_carrier_wrap">
                            <label for="basic-url" class="form-label">ФИО <span class="text-danger">*</span></label>
                            <!--<div class="input-group">
                                <input type="text" placeholder="Фио водителя" class="form-control form-control-solid" name="fio" id="fio-input" aria-describedby="basic-addon3 basic-addon4">
                            </div>-->
                            <select name="driver_carrier" id="driver_carrier" class="form-select select  select-add-application">
                                <option value="0" disabled selected>Водитель</option>
                                <?php foreach ($listDrivers as $driver){ ?>
                                    <option value="<?php echo $driver['id']; ?>"><?php echo $driver['name']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-4 d-flex flex-column justify-content-end">
                        <div class="btn-wrap">
                            <button class="btn btn-add-light add-driver-btn-modal" data-bs-toggle="modal" data-bs-target="#modalDriver">
                                Добавить
                            </button>
                            <button class="btn btn-add-light edit-driver-btn-modal" data-bs-toggle="modal" data-bs-target="#modalEditDriver">
                                Редактировать
                            </button>
                        </div>
                    </div>
                    <div class="col d-flex align-items-end gap-2 justify-content-start">
                        <div class="item-doc-fotmat">
                            <div class="form-check form-check-custom form-check-solid form-check-sm align-items-start">
                                <input <?if($application["show_driver_number"] === 1) echo "checked='checked'";?> class="form-check-input seal" data-name="seal" id="check-show-driver-number" type="checkbox" value="">
                                <label for="check-dogovor-perevozhik" style="font-size: 14px;">Показать номер</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="application-driver-data application-driver-data-transport row">
                    <div class="col-6">
                        <div class="driver_carrier_wrap">
                            <label for="basic-url" class="form-label">Сохраненный транспорт</label>

                            <select id="driver_carrier_transport" class="form-select">

                            </select>
                        </div>
                    </div>
                </div>
                <div class="driver-info info">
                    <div class="inn mb-4">
                        <span class="gray">Водитель</span>
                        <span id="driver_info_name_carrier" class="driver_info_value"></span>
                    </div>
                    <div class="legal_address mb-4">
                        <span class="gray">Водительское удостоверение</span>
                        <span id="driver_info_licence_carrier" class="driver_info_value"></span>
                    </div>
                    <div class="info mb-4">
                        <span class="gray">Паспортные данные</span>
                        <span id="driver_info_passport_carrier" class="driver_info_value"></span>
                    </div>
                    <div class="info mb-4">
                        <span class="gray">Телефон</span>
                        <span id="driver_info_phone_carrier" class="driver_info_value"></span>
                    </div>
                </div>
            </div>

            <div class="application-transport mt-5 d-flex flex-column gap-5">
                <div class="title-base">
                    Транспорт
                </div>
                <div class="application-transport-data row">
                    <div class="col-3">
                        <div class="">
                            <label for="basic-url" class="form-label">Марка <span class="text-danger">*</span></label>
                            <div class="input-group input-group-without-choices__inner">
                                <input type="text" placeholder="Марка" class="form-control form-control-solid united-input js-trigger-input-choices"
                                       name="mark" id="mark-input_carrier" aria-describedby="basic-addon3 basic-addon4"
                                       value = "<?php echo $application["car_brand_id_Carrier"];?>" >
                                <select name="" id="select-car-brands" class="form-select united-select">
                                    <option value="0" selected disabled>По умолчанию</option>
                                    <?php foreach ($listCarBrands as $carBrand){ ?>
                                        <option value="<?php echo $carBrand['name']; ?>"><?php echo $carBrand['name']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-3">
                        <div class="">
                            <label for="basic-url" class="form-label">Гос номер <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="text" placeholder="Гос номер" class="form-control form-control-solid" name="gos-number" id="gos-number-input_carrier" aria-describedby="basic-addon3 basic-addon4"
                                       value="<?php echo $application["government_number_Carrier"];?>" >
                            </div>
                        </div>
                    </div>

                    <div class="col-3">
                        <div class="">
                            <label for="basic-url" class="form-label">П/П</label>
                            <div class="input-group">
                                <input type="text" placeholder="П/П" class="form-control form-control-solid" name="pp" id="pp_carrier" aria-describedby="basic-addon3 basic-addon4"
                                       value="<?php echo $application["semitrailer_Carrier"];?>" >
                            </div>
                        </div>
                    </div>

                    <div class="col-3">
                        <div class="">
                            <label for="basic-url" class="form-label">Тип транспорта <span class="text-danger">*</span></label>
                            <div class="input-group input-group-without-choices__inner">
                                <input type="text" placeholder="Тип транспорта" class="form-control form-control-solid united-input js-trigger-input-choices"
                                       name="transport-type" id="transport-type-input_carrier" aria-describedby="basic-addon3 basic-addon4"
                                       value="<?php echo $application["type_transport_id_Carrier"];?>" >
                                <select name="type_transport_carrier" id="select-type-transport" class="form-select united-select">
                                    <option value="0" selected disabled>По умолчанию</option>
                                    <?php foreach ($listTypeTransport as $typeTransport){ ?>
                                        <option value="<?php echo $typeTransport['name']; ?>"><?php echo $typeTransport['name']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="application-transport-data row">
                    <div class="col-3">
                        <div class="">
                            <label for="basic-url" class="form-label">Тип кузова</label>
                            <div class="input-group input-group-without-choices__inner">
                                <input type="text" placeholder="Тип кузова" class="form-control form-control-solid united-input js-trigger-input-choices"
                                       name="mark" id="carcase-type-input_carrier" aria-describedby="basic-addon3 basic-addon4"
                                       value="<?php echo $application["type_carcase_id_Carrier"];?>">
                                <select name="" id="select-carcase-type" class="form-select united-select">
                                    <option value="0" selected disabled>По умолчанию</option>
                                    <?php foreach ($listCarcaseTypes as $carcaseType){ ?>
                                        <option value="<?php echo $carcaseType['name']; ?>"><?php echo $carcaseType['name']; ?></option>
                                    <?php }?>
                                </select>
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
                    <textarea class="form-control" placeholder="Leave a comment here" id="special-conditions_carrier" style="height: 100%" name="special-conditions"><?php echo $application["special_conditions_Carrier"];?></textarea>
                </div>
                <script>
                    let Editor_special_conditions_carrier;
                    ClassicEditor
                        .create( document.querySelector( '#special-conditions_carrier' ),
                            {
                                toolbar: [  'undo', 'redo', 'Heading', 'Link', 'bold', 'italic', 'numberedList', 'bulletedList' ]
                            })
                        .then( editor => {
                            Editor_special_conditions_carrier = editor;
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
                                <textarea class="form-control" placeholder="" id="cost_textarea_carrier" style="height: 100%"><?php echo $application["terms_payment_Carrier"];?></textarea>
                                <!--                        <div class="dropdown">-->
                                <!---->
                                <!--                            <button class="btn btn-add-light" type="button" data-bs-toggle="dropdown" aria-expanded="false">+</button>-->
                                <!--                            <div class="dropdown-menu p-4 text-body-secondary">-->
                                <div class="btn-plus-condition">
                                    <span class="js-trigger-select-choices">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                          <path opacity="0.3" d="M17 2H7C4.23858 2 2 4.23858 2 7V17C2 19.7614 4.23858 22 7 22H17C19.7614 22 22 19.7614 22 17V7C22 4.23858 19.7614 2 17 2Z" fill="black"></path>
                                          <path d="M10.8892 6.8033V16.8033C10.8892 17.3556 11.3369 17.8033 11.8892 17.8033C12.4414 17.8033 12.8892 17.3556 12.8892 16.8033V6.8033C12.8892 6.25101 12.4414 5.8033 11.8892 5.8033C11.3369 5.8033 10.8892 6.25101 10.8892 6.8033Z" fill="black"></path>
                                          <path d="M17.0104 10.9247H7.01038C6.45809 10.9247 6.01038 11.3724 6.01038 11.9247C6.01038 12.477 6.45809 12.9247 7.01038 12.9247H17.0104C17.5627 12.9247 18.0104 12.477 18.0104 11.9247C18.0104 11.3724 17.5627 10.9247 17.0104 10.9247Z" fill="black"></path>
                                        </svg>
                                    </span>
                                    <div class="terms-payment_carrier-wrap">
                                        <select name="terms-payment_carrier" id="select-terms-payment_carrier" class="form-select select-chosen united-select" data-placeholder="">
                                            <?php foreach ($listTermsPayment as $term){ ?>
                                                <option value="<?php echo $term['id'] ?>"><?php echo $term['name'] ;?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <!--                            </div>-->
                                <!--                        </div>-->
                            </div>
                            <script>
                                let Editor_cost_textarea_carrier;
                                ClassicEditor
                                    .create( document.querySelector( '#cost_textarea_carrier' ),
                                        {
                                            toolbar: [  'undo', 'redo', 'Heading', 'Link', 'bold', 'italic', 'numberedList', 'bulletedList' ]
                                        })
                                    .then( editor => {
                                        Editor_cost_textarea_carrier = editor;
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
                                    <input type="text" placeholder="Стоимость перевозки" class="form-control form-control-solid" name="cost" id="cost-input_carrier" aria-describedby="basic-addon3 basic-addon4"
                                           value="<?php echo number_format($application["transportation_cost_Carrier"] , 0, '.',' ');?>">
                                </div>
                            </div>
                            <div class="">
                                <label for="basic-url" class="form-label">Вид налогообложения <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <!--                            <input type="text" placeholder="Вид налогообложения" class="form-control form-control-solid" name="taxation-type" id="taxation-type-input_carrier" aria-describedby="basic-addon3 basic-addon4">-->

                                    <select name="type_taxation_id" id="taxation-type-input_carrier" class="form-select select-type-taxation select">
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

            <div class="application-special-conditions mt-5 d-flex flex-column gap-5">
                <div class="title-base">
                    Обязательные условия
                </div>
                <div class="form-floating" style="height: 350px">
                    <textarea class="form-control" placeholder="Leave a comment here" id="prerequisites" style="height: 100%" name="prerequisites"><?php echo $application["prerequisites_Carrier"]; ?></textarea>
                    <div class="btn-plus-condition">
                        <span class="js-trigger-select-choices">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path opacity="0.3" d="M17 2H7C4.23858 2 2 4.23858 2 7V17C2 19.7614 4.23858 22 7 22H17C19.7614 22 22 19.7614 22 17V7C22 4.23858 19.7614 2 17 2Z" fill="black"></path>
                                <path d="M10.8892 6.8033V16.8033C10.8892 17.3556 11.3369 17.8033 11.8892 17.8033C12.4414 17.8033 12.8892 17.3556 12.8892 16.8033V6.8033C12.8892 6.25101 12.4414 5.8033 11.8892 5.8033C11.3369 5.8033 10.8892 6.25101 10.8892 6.8033Z" fill="black"></path>
                                <path d="M17.0104 10.9247H7.01038C6.45809 10.9247 6.01038 11.3724 6.01038 11.9247C6.01038 12.477 6.45809 12.9247 7.01038 12.9247H17.0104C17.5627 12.9247 18.0104 12.477 18.0104 11.9247C18.0104 11.3724 17.5627 10.9247 17.0104 10.9247Z" fill="black"></path>
                            </svg>
                        </span>
                        <select name="conditions_carrier" id="select-conditions" class="form-select select-chosen united-select">
                            <option></option>
                            <?php foreach ($listConditions as $condition){ ?>
                                <option value="<?php echo $condition['id'] ?>"><?php echo $condition['name'] ;?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <script>
                    let Editor_prerequisites;
                    ClassicEditor
                        .create( document.querySelector( '#prerequisites' ),
                            {
                                toolbar: [  'undo', 'redo', 'Heading', 'Link', 'bold', 'italic', 'numberedList', 'bulletedList' ]
                            })
                        .then( editor => {
                            Editor_prerequisites = editor;
                        } )
                        .catch( error => {
                            console.error( error );
                        } );
                </script>
            </div>

            <div class="application-addition  d-flex flex-column gap-5" style="margin-top: 7rem;">
                <div class="title-base">
                    Приложение
                </div>
                <div class="form-floating">
<!--                <textarea class="form-control mb-3" placeholder="Leave a comment here" id="addition" disabled style="height: 100%" name="addition">-->
<!--                    --><?php //echo $application["addition"];?>
<!--                </textarea>-->
<!--                    <script>-->
<!--                        let Editor_addition;-->
<!--                        ClassicEditor-->
<!--                            .create( document.querySelector( '#addition' ),-->
<!--                                {-->
<!--                                    toolbar: [  'undo', 'redo', 'Heading', 'Link', 'bold', 'italic', 'numberedList', 'bulletedList' ]-->
<!--                                })-->
<!--                            .then( editor => {-->
<!--                                editor.enableReadOnlyMode( 'my-feature-id' );-->
<!--                                Editor_addition = editor;-->
<!--                                console.log( editor );-->
<!--                            } )-->
<!--                            .catch( error => {-->
<!--                                console.error( error );-->
<!--                            } );-->
<!--                        // Editor_addition.isReadOnly = true;-->
<!--                        // Editor_addition.setReadOnly(true);-->
<!--                    </script>-->
                    <select name="conditions_carrier" id="select-addition" class="form-select select-chosen select mt-3">
                        <?php foreach ($listAdditions as $addition){ ?>
                            <option value="<?php echo $addition['id']; ?>" <?php if($addition['id'] == $application["addition"]) echo 'selected'; ?>><?php echo $addition['name'] ;?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>

<!--            <div class="application-сarrier-fines  d-flex flex-column gap-5">-->
<!--                <div class="title-base">-->
<!--                    Штрафы от перевозчика-->
<!--                </div>-->
<!--                <div>-->
<!--                    <button class="btn btn-add-light"-->
<!--                            data-bs-toggle="modal" data-bs-target="#modalFinesFrom">Добавить штраф</button>-->
<!--                </div>-->
<!--                <div id="fines-from-block" class="w-75">-->
<!---->
<!--                </div>-->
<!--            </div>-->
<!---->
<!--            <div class="application-сarrier-fines mt-5 d-flex flex-column gap-5">-->
<!--                <div class="title-base">-->
<!--                    Штрафы перевозчику-->
<!--                </div>-->
<!--                <div>-->
<!--                    <button class="btn btn-add-light"-->
<!--                            data-bs-toggle="modal" data-bs-target="#modalFinesTo">Добавить штраф</button>-->
<!--                </div>-->
<!--                <div id="fines-to-block" class="w-75">-->
<!--                </div>-->
<!--            </div>-->

            <div class="application-additional-costs mt-5 d-flex flex-column gap-5">
                <div class="title-base">
                    Доп затраты
                </div>
                <div>
                    <button class="btn btn-add-light"
                            data-bs-toggle="modal" data-bs-target="#modalExpenses" onclick="$('#expenses-type-for').val(0);">Доп затраты</button>
                </div>
                <div id="expenses-carrier-block_carrier" class="w-75">
                </div>
            </div>
        </div>

        <div class="form__body__client">
            <div class="application-main d-flex flex-column">
                <div class="title-base">
                    Основное
                </div>
                <div class="row type-applications">
                    <div class="col-3">
                        <div class="mb-3">
                            <label for="basic-url" class="form-label">Заказчик (наше юр лицо) <span class="text-danger">*</span></label>
                            <!--                <div class="input-group">-->
                            <!--                    <input type="text" placeholder="Заказчик"-->
                            <!--                           class="form-control" name="customer-input"-->
                            <!--                           id="customer-input" aria-describedby="basic-addon3 basic-addon4">-->
                            <!--                </div>-->
                            <select name="customer_client" id="customer_client" class="form-select select select-add-application">
                                <option value="0" disabled selected>Заказчик</option>
                                <?php foreach ($listCustomers as $customer){ ?>
                                    <option value="<?php echo $customer['id']; ?>"><?php echo $customer['name']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-7">
                        <div class="mb-3">
                            <label for="basic-url" class="form-label">Заголовок заявки</label>
                            <div class="input-group">
                                <input type="text" placeholder="Заголовок" class="form-control form-control-solid" name="main-slogan" id="main-slogan-input_client" aria-describedby="basic-addon3 basic-addon4"
                                       value="<?php echo $application["application_title_Client"];?>">
                            </div>
                        </div>
                    </div>
                    <div class="col-2">
                        
                    </div>
                </div>
                <div class="row form-main__select-cont">
                    <div class="col-3">
                        <div class="mb-3">
                            <label for="basic-url" class="form-label">Клиент <span class="text-danger">*</span></label>
                            <div class="d-flex gap-5 client_input_wrap">
                                <!--                     <div class="input-group ">-->
                                <!--                         <input type="text" placeholder="Перевозчик" class="form-control" name="customer-input" id="customer-input" aria-describedby="basic-addon3 basic-addon4">-->
                                <!--                     </div>-->
                                <select name="client" id="client_id_input" class="form-select select-add-application select ">
                                    <option value="0" disabled selected>Клиент</option>
                                    <?php foreach ($listClients as $client){ ?>
                                        <option value="<?php echo $client["id"]; ?>"><?php echo $client['name'] .' '  .$client['inn']; ?></option>
                                    <?php } ?>
                                </select>
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

                    <div class="col d-flex align-items-end mb-3 justify-content-start d-none">
                        <div class="item-doc-fotmat">
                            <div class="form-check form-check-custom form-check-solid form-check-sm align-items-start">
                                <input <?if($application["hide_title"] === 1) echo "checked='checked'";?>
                                        class="form-check-input seal" id="check-hide-title" type="checkbox">
                                <label for="check-hide-title" style="font-size: 14px;">Скрыть подзаголовок</label>
                            </div>
                        </div>
                    </div>

                    <div class="col mb-3 col-application_number_client d-none">
                        <label for="" class="form-label">Номер заявки клиента</label>
                        <input type="text" class="form-control" name="application_number_client"
                               value="<?php echo $application['application_number_Client']; ?>"
                               id="application_number_client">
                    </div>

                </div>
                <div class="client-info info mt-3">
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
                    Транспортировка
                </div>
                <div class="btn-wrap">
                    <button class="btn btn-add-light" data-bs-toggle="modal" data-bs-target="#modalMarshrut" onclick="$('#form-add-marshrut-side').val('client');">Добавить маршрут</button>
                </div>
                <div class="marshrut-list" id="marshrut-list">

                </div>
                <!--            <button class="btn btn-primary" id="collect-marshrut">Собрать</button>-->

                <div class="application-transportation-data row">
                    <div class="col-7">
                        <div class="d-flex flex-column gap-3">
                    <span>
                        Характер груза <span class="text-danger">*</span>
                    </span>
                            <div class="form-floating" style="height: 100%">
                                <textarea class="form-control" placeholder="Leave a comment here" id="nature_cargo_client" style="height: 100px"><?php echo $application["nature_cargo_Client"];?></textarea>
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
                                    <input type="text" placeholder="Мест" class="form-control form-control-solid" name="place" id="place-input_client" aria-describedby="basic-addon3 basic-addon4"
                                           value="<?php echo $application["place_Client"];?>">
                                </div>
                            </div>
                            <div class="">
                                <label for="basic-url" class="form-label">Вес <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text" placeholder="Вес" class="form-control form-control-solid weight-input" name="weight" id="weight-input_client" aria-describedby="basic-addon3 basic-addon4"
                                           value="<?php echo $application["weight_Client"];?>">
                                </div>
                            </div>
                            <div class="">
                                <label for="basic-url" class="form-label">Реф режим <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text" placeholder="Реф режим" class="form-control form-control-solid" name="ref-mode" id="ref-mode-input_client" aria-describedby="basic-addon3 basic-addon4"
                                           value="<?php echo $application["ref_mode_Client"];?>">
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
                <div class="application-driver-data row justify-content-end">
                    <div class="col-6">
                        <div class="driver_client_wrap">
                            <label for="basic-url" class="form-label">ФИО <span class="text-danger">*</span></label>
                            <!--<div class="input-group">
                                <input type="text" placeholder="Фио водителя" class="form-control form-control-solid" name="fio" id="fio-input" aria-describedby="basic-addon3 basic-addon4">
                            </div>-->
                            <select name="driver_client" id="driver_client" class="form-select united-select select  select-add-application">
                                <option value="0" disabled selected>Водитель</option>
                                <?php foreach ($listDrivers as $driver){ ?>
                                    <option value="<?php echo $driver['id']; ?>"><?php echo $driver['name']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-3 d-flex flex-column justify-content-end">
                        <div class="btn-wrap">
                            <button class="btn btn-add-light add-driver-btn-modal" data-bs-toggle="modal" data-bs-target="#modalDriver">
                                Добавить
                            </button>
                        </div>
                    </div>
                    <div class="col d-flex align-items-end gap-2 justify-content-center">
                        <!--                    <input type="checkbox"  id="btncheck1" autocomplete="off">-->
                        <!--                    <label class="" for="btncheck1">Показать номер</label>-->
                    </div>
                </div>
                <div class="driver-info info">
                    <div class="inn mb-4">
                        <span class="gray">Водитель</span>
                        <span id="driver_info_name_client" class="driver_info_value"></span>
                    </div>
                    <div class="legal_address mb-4">
                        <span class="gray">Водительское удостоверение</span>
                        <span id="driver_info_licence_client" class="driver_info_value"></span>
                    </div>
                    <div class="info mb-4">
                        <span class="gray">Паспортные данные</span>
                        <span id="driver_info_passport_client" class="driver_info_value"></span>
                    </div>
                    <div class="info mb-4">
                        <span class="gray">Телефон</span>
                        <span id="driver_info_phone_client" class="driver_info_value"></span>
                    </div>
                </div>
            </div>

            <div class="application-transport mt-5 d-flex flex-column gap-5">
                <div class="title-base">
                    Транспорт
                </div>
                <div class="application-transport-data row">
                    <div class="col-3">
                        <div class="">
                            <label for="basic-url" class="form-label">Марка <span class="text-danger">*</span></label>
                            <div class="input-group input-group-without-choices__inner">
                                <input type="text" placeholder="Марка" class="form-control form-control-solid united-input js-trigger-input-choices"
                                       name="mark" id="mark-input_client" aria-describedby="basic-addon3 basic-addon4"
                                       value="<?php echo $application["car_brand_id_Client"];?>">
                                <select name="" id="select-car-brands" class="form-select united-select">
                                    <option value="0" selected disabled>По умолчанию</option>
                                    <?php foreach ($listCarBrands as $carBrand){ ?>
                                        <option value="<?php echo $carBrand['name']; ?>"><?php echo $carBrand['name']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-3">
                        <div class="">
                            <label for="basic-url" class="form-label">Гос номер <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="text" placeholder="Гос номер" class="form-control form-control-solid" name="gos-number" id="gos-number-input_client" aria-describedby="basic-addon3 basic-addon4"
                                       value = "<?php echo $application["government_number_Client"];?>">
                            </div>
                        </div>
                    </div>

                    <div class="col-3">
                        <div class="">
                            <label for="basic-url" class="form-label">П/П</label>
                            <div class="input-group">
                                <input type="text" placeholder="П/П" class="form-control form-control-solid" name="pp" id="pp_client" aria-describedby="basic-addon3 basic-addon4"
                                       value="<?php echo $application["semitrailer_Client"];?>">
                            </div>
                        </div>
                    </div>

                    <div class="col-3">
                        <div class="">
                            <label for="basic-url" class="form-label">Тип транспорта <span class="text-danger">*</span></label>
                            <div class="input-group input-group-without-choices__inner">
                                <input type="text" placeholder="Тип транспорта" class="form-control form-control-solid united-input js-trigger-input-choices"
                                       name="transport-type" id="transport-type-input_client" aria-describedby="basic-addon3 basic-addon4"
                                       value="<?php echo $application["type_transport_id_Client"];?>">
                                <select name="" id="select-type-transport" class="form-select united-select">
                                    <option value="0" selected disabled>По умолчанию</option>
                                    <?php foreach ($listTypeTransport as $typeTransport){ ?>
                                        <option value="<?php echo $typeTransport['name']; ?>"><?php echo $typeTransport['name']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="application-transport-data row">
                    <div class="col-3">
                        <div class="">
                            <label for="basic-url" class="form-label">Тип кузова</label>
                            <div class="input-group input-group-without-choices__inner">
                                <input type="text" placeholder="Тип кузова" class="form-control form-control-solid united-input js-trigger-input-choices"
                                       name="mark" id="carcase-type-input_client" aria-describedby="basic-addon3 basic-addon4"
                                       value="<?php echo $application["type_carcase_id_Client"];?>">
                                <select name="" id="select-carcase-type" class="form-select united-select">
                                    <option value="0" selected disabled>По умолчанию</option>
                                    <?php foreach ($listCarcaseTypes as $carcaseType){ ?>
                                        <option value="<?php echo $carcaseType['name']; ?>"><?php echo $carcaseType['name']; ?></option>
                                    <?php }?>
                                </select>
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
                    <textarea class="form-control" placeholder="Leave a comment here" id="special-conditions_client" style="height: 100%" name="special-conditions"><?php echo $application["special_conditions_Client"];?></textarea>
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
                                <textarea class="form-control" placeholder="Leave a comment here" id="cost_textarea_client" style="height: 100%"><?php echo $application["terms_payment_Client"];?></textarea>
                                <!--                        <div class="dropdown">-->
                                <!---->
                                <!--                            <button class="btn btn-add-light" type="button" data-bs-toggle="dropdown" aria-expanded="false">+</button>-->
                                <!--                            <div class="dropdown-menu p-4 text-body-secondary">-->
                                <div class="btn-plus-condition">
                                    <span class="js-trigger-select-choices">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                          <path opacity="0.3" d="M17 2H7C4.23858 2 2 4.23858 2 7V17C2 19.7614 4.23858 22 7 22H17C19.7614 22 22 19.7614 22 17V7C22 4.23858 19.7614 2 17 2Z" fill="black"></path>
                                          <path d="M10.8892 6.8033V16.8033C10.8892 17.3556 11.3369 17.8033 11.8892 17.8033C12.4414 17.8033 12.8892 17.3556 12.8892 16.8033V6.8033C12.8892 6.25101 12.4414 5.8033 11.8892 5.8033C11.3369 5.8033 10.8892 6.25101 10.8892 6.8033Z" fill="black"></path>
                                          <path d="M17.0104 10.9247H7.01038C6.45809 10.9247 6.01038 11.3724 6.01038 11.9247C6.01038 12.477 6.45809 12.9247 7.01038 12.9247H17.0104C17.5627 12.9247 18.0104 12.477 18.0104 11.9247C18.0104 11.3724 17.5627 10.9247 17.0104 10.9247Z" fill="black"></path>
                                        </svg>
                                    </span>
                                    <div class="terms-payment_client-wrap">
                                        <select name="" id="select-terms-payment_client" class="form-select select-chosen united-select" data-placeholder="">
                                            <?php foreach ($listTermsPayment as $term){ ?>
                                                <option value="<?php echo $term['id']; ?>"><?php echo $term['name'] ;?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <!--                            </div>-->
                                <!--                        </div>-->
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
                        <div class="d-flex flex-column gap-4">
                            <div class="">
                                <label for="basic-url" class="form-label">Стоимость перевозки, ₽ <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text" placeholder="Стоимость перевозки" class="form-control form-control-solid" name="cost" id="cost-input_client" aria-describedby="basic-addon3 basic-addon4"
                                           value="<?php echo number_format($application["transportation_cost_Client"], 0, '.',' ');?>">
                                </div>
                            </div>
                            <div class="">
                                <label for="basic-url" class="form-label">Стоимость груза</label>
                                <div class="input-group">
                                    <input type="text" placeholder="Стоимость груза" class="form-control form-control-solid" name="cost" id="cost-cargo_client" aria-describedby="basic-addon3 basic-addon4"
                                           value="<?php if($application['cost_cargo_Client'] > 0) echo number_format($application["cost_cargo_Client"] , 0, '.',' ');?>">
                                </div>
                            </div>
                            <div class="">
                                <label for="basic-url" class="form-label">Вид налогообложения <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <!--                                <input type="text" placeholder="Вид налогообложения" class="form-control form-control-solid" name="taxation-type" id="taxation-type-input_client" aria-describedby="basic-addon3 basic-addon4">-->

                                    <select name="type_taxation_id" id="taxation-type-input_client" class="form-select select select-type-taxation">
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

            <div class="mt-5"></div>
            <!-- new 20-09-24 -->
            <div class="application-additional-costs mt-5 d-flex flex-column gap-4 d-none">
                <div class="title-base">
                    Доп прибыль
                </div>
                <div>
                    <button class="btn btn-add-light"
                            data-bs-toggle="modal" data-bs-target="#modalAdditionalProfit">Доп прибыль</button>
                </div>
                <div id="additional_profit-block_client" class="w-75 mb-4">
                </div>
            </div>
        </div>

        <div class="" style="padding-left: 20px;">
            <button class="btn btn-add-light duplicate mb-5"  style="margin-left: auto">
                Дублировать
            </button>
            <div class="form__header-menu">
                <span href="#" class="form__header-menu-item carrier-tab">
                    <span class="menu-item-click active">Перевозчик</span>
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

    <!--new 20-09-2024 -->
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
                            <label for="" class="mb-2">Вид доп прибыли <span class="text-danger">*</span></label>
                            <input type="text" name="type" id="input-type_additional_profit" placeholder="Вид доп прибыли"
                                   class="form-control form-control-solid mt-2" required>
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
                            <input class="d-none" id="form-add-marshrut-side" value="carrier">
                            <select name="direction" id="direction-input" class="form-select  mb-4 direction-input" required>
                                <option value="0">Куда</option>
                                <option value="1">Откуда</option>
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-6 mb-4">
                                <label for="" class="label mb-2">Куда <span class="text-danger">*</span></label>
                                <input type="text" name="city" city-api="true" class="form-control form-control-solid" placeholder="Выберите город" required>
                                <div class="custom-dropdown">

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
                                    <input type="text" name="time" class="form-control form-control-solid transportation-time-input" id="transportation-time-input" placeholder="Время">
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
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отменить</button>
                    <button type="button" class="btn btn-primary" id="add-marshrut">Добавить</button>
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
                            <input class="d-none" id="form-edit-marshrut-side" value="carrier">
                            <select name="direction" id="select-direction" class="form-select mb-4 direction-input" required>
                                <option value="0">Куда</option>
                                <option value="1">Откуда</option>
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-6 mb-4">
                                <label for="" class="label mb-2">Куда <span class="text-danger">*</span></label>
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
                                <label for="" class="mb-2">Время</label>
                                <input type="text" name="time" id="input-time-modal-edit" class="form-control form-control-solid" placeholder="Время">
                            </div>
                        </div>
                        <input type="submit" style="display: none" id="btn-form-add-marshrut">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отменить</button>
                    <button type="button" class="btn btn-primary" id="edit-marshrut">Сохранить</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal modal-add-application fade" id="modalFinesFrom"
         tabindex="-1" aria-labelledby="modalFinesFrom" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title title-base" id="exampleModalLabel">Добавление штрафа от перевозчика</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                </div>
                <div class="modal-body">
                    <form action="" id="form-add-fines-from" class="form-add-fines">
                        <input type="hidden" name="type-for" value="0" required>
                        <div class="row">
                            <div class="col">
                                <div class="mb-4">
                                    <label for="" class="mb-1">Сумма, ₽ <span class="text-danger">*</span></label>
                                    <input type="text" name="sum" placeholder="Сумма, ₽" class="form-control form-control-solid" required>
                                </div>
                                <div class="mb-2">
                                    <label for="" class="mb-1">Дата оплаты <span class="text-danger">*</span></label>
                                    <input type="datetime-local" name="date" placeholder="Дата оплаты" class="form-control form-control-solid" required>
                                </div>
                            </div>
                            <div class="col">
                                <div class="mb-2">
                                    <label for="" class="mb-1">Описание <span class="text-danger">*</span></label>
                                    <textarea name="description" id="" placeholder="Описание"
                                              class="form-control form-control-solid" required></textarea>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отменить</button>
                    <button type="button" class="btn btn-primary" id="btn-add-fines-from">Добавить</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal modal-add-application fade" id="modalFinesTo"
         tabindex="-1" aria-labelledby="modalFinesTo" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title title-base" id="exampleModalLabel">Добавление штрафа перевозчику</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                </div>
                <div class="modal-body">
                    <form action="" id="form-add-fines-to" class="form-add-fines">
                        <input type="hidden" name="type-for" value="1" required>
                        <div class="row">
                            <div class="col">
                                <div class="mb-4">
                                    <label for="" class="mb-1">Сумма, ₽ <span class="text-danger">*</span></label>
                                    <input type="text" name="sum" placeholder="Сумма, ₽" class="form-control form-control-solid" required>
                                </div>
                                <div class="mb-2">
                                    <label for="" class="mb-1">Дата оплаты <span class="text-danger">*</span></label>
                                    <input type="datetime-local" name="date" placeholder="Дата оплаты" class="form-control form-control-solid" required>
                                </div>
                            </div>
                            <div class="col">
                                <div class="mb-2">
                                    <label for="" class="mb-1">Описание <span class="text-danger">*</span></label>
                                    <textarea name="description" id="" placeholder="Описание"
                                              class="form-control form-control-solid" required></textarea>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отменить</button>
                    <button type="button" class="btn btn-primary" id="btn-add-fines-to">Добавить</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal modal-add-application fade" id="modalEditFine"
         tabindex="-1" aria-labelledby="modalEditFine" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title title-base" id="exampleModalLabel">Добавление штрафа перевозчику</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                </div>
                <div class="modal-body">
                    <form action="" id="form-edit-fines-to" class="form-edit-fines">
                        <input type="hidden" name="" id="fine-edit-id">
                        <div class="row">
                            <div class="col">
                                <div class="mb-4">
                                    <label for="" class="mb-1">Сумма, ₽ <span class="text-danger">*</span></label>
                                    <input id="input-edit-fine-sum" type="text" name="sum" placeholder="Сумма, ₽" class="form-control form-control-solid" required>
                                </div>
                                <div class="mb-2">
                                    <label for="" class="mb-1">Дата оплаты <span class="text-danger">*</span></label>
                                    <input id="input-edit-fine-date" type="datetime-local" name="date" placeholder="Дата оплаты" class="form-control form-control-solid" required>
                                </div>
                            </div>
                            <div class="col">
                                <div class="mb-2">
                                    <label for="" class="mb-1">Описание <span class="text-danger">*</span></label>
                                    <textarea name="description" id="input-edit-fine-description" placeholder="Описание"
                                              class="form-control form-control-solid" required></textarea>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отменить</button>
                    <button type="button" class="btn btn-primary" id="btn-edit-fines">Сохранить</button>
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
                            <label for="" class="mb-1">Вид затрат <span class="text-danger">*</span></label>
                            <select name="" id="select-type_expenses" class="form-select">
                                <option value="Грузчики">Грузчики</option>
                                <!--                                <option value="Страховка">Страховка</option>-->
<!--                                <option value="Доп. точка ">Доп. точка </option>-->
<!--                                <option value="Простои">Простои</option>-->
                                <option value="Перегруз">Перегруз</option>
                                <option value="Вычет">Вычет</option>
                                <option value="">Свободное поле</option>
                            </select>
                            <input type="text" name="type_expenses" id="input-type_expenses" placeholder="Вид затрат"
                                   class="form-control form-control-solid mt-2 d-none" value="Грузчики" required>
                        </div>
                        <div class="mb-2">
                            <label for="" class="mb-1">Сумма <span class="text-danger">*</span></label>
                            <input type="text" name="sum" placeholder="Сумма" class="form-control form-control-solid" id="input-sum_expenses" required>
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
                            <input type="text" name="comment" placeholder="Комментарий" class="form-control form-control-solid" required>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отменить</button>
                    <button type="button" class="btn btn-primary" id="add-expenses">Добавить</button>
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
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отменить</button>
                    <button type="button" class="btn btn-primary" id="edit-expenses">Сохранить</button>
                </div>
            </div>
        </div>
    </div>

<!--new 20-09-2024 -->
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
                            <label for="" class="mb-2">Вид доп прибыли <span class="text-danger">*</span></label>
                            <input type="text" name="type" id="input-type_additional_profit" placeholder="Вид доп прибыли"
                                   class="form-control form-control-solid mt-2" required>
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

    <div class="modal modal-add-application fade" id="modalCarrier" tabindex="-1" aria-labelledby="modalCarrier" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title title-base" id="exampleModalLabel">Добавление перевозчика</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                </div>
                <div class="modal-body">
                    <form action="application/add-carrier" method="post" id="form-add-carrier-from" class="form-add-carrier">
                        <div class="row">
                            <div class="col">
                                <div class="col" id="carrier_add_inputs">
                                    <div class="mb-4">
                                        <label for="" class="mb-1">Название <span class="text-danger">*</span></label>
                                        <input type="text" name="carrier_name" placeholder="Название"  class="form-control form-control-solid" required>

                                    </div>
                                    <div class="mb-4">
                                        <label for="" class="mb-1">ИНН <span class="text-danger">*</span></label>
                                        <input type="number" name="carrier_inn" placeholder="ИНН" class="form-control form-control-solid" id="add-carrier-inn" required>
                                        <div id="error-carrier-inn-isset" class='d-none small mt-2'>Перевозчик с таким ИНН уже есть</div>
                                    </div>
                                    <div class="mb-4">
                                        <label for="" class="mb-1">Юридический адрес <span class="text-danger">*</span></label>
                                        <textarea name="carrier_legal_address" placeholder="Адрес" class="form-control form-control-solid" required></textarea>
                                    </div>
                                    <div class="mb-4">
                                        <label for="" class="mb-1">Телефон, код в АТИ, конт. лицо, почта <span class="text-danger">*</span></label>
                                        <input type="text" name="carrier_info" id="carrier_info-input" placeholder="8 ()--, код в АТИ, Имя:" class="form-control form-control-solid carrier_info-input" required value="8 ()--, код в АТИ, Имя: ">
                                    </div>
                                </div>
                                <div class="mb-4 d-flex justify-content-between">
                                    <div id="add_carrier_info_input" class="mb-1" style="text-decoration: underline; cursor: pointer;"><span class="text-success">Добавить еще</span></div>
                                    <div id="delete_carrier_info_input" class="mb-1" style="text-decoration: underline; cursor: pointer;"><span class="text-danger">Удалить</span></div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отменить</button>
                    <button type="button" class="btn btn-primary" id="btn-add-carrier-from">Добавить</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal modal-add-application fade" id="modalCarrierEdit" tabindex="-1" aria-labelledby="modalCarrierEdit" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title title-base" id="exampleModalLabel">Редактирование перевозчика</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                </div>
                <div class="modal-body">
                    <form action="application/edit-carrier" method="post" id="form-edit-carrier-from-edit" class="form-edit-carrier">
                        <div class="row">
                            <div class="col">
                                <div class="col" id="carrier_edit_add_inputs">
                                    <div class="mb-4">
                                        <input class="d-none" id="carrier_edit_id" value="0" name="carrier_edit_id">
                                        <label for="" class="mb-1">Название <span class="text-danger">*</span></label>
                                        <input id="carrier_edit_name" type="text" name="carrier_name" placeholder="Название" class="form-control form-control-solid" required>
                                    </div>
                                    <div class="mb-4">
                                        <label for="" class="mb-1">ИНН <span class="text-danger">*</span></label>
                                        <input id="carrier_edit_inn" type="number" name="carrier_inn" placeholder="ИНН" class="form-control form-control-solid" required>
                                    </div>
                                    <div class="mb-4">
                                        <label for="" class="mb-1">Юридический адрес <span class="text-danger">*</span></label>
                                        <textarea id="carrier_edit_legal_address" name="carrier_legal_address" placeholder="Адрес" class="form-control form-control-solid" required></textarea>
                                    </div>
                                </div>
                                <div class="mb-4 d-flex justify-content-between">
                                    <div id="add_carrier_edit_info_input" class="mb-1" style="text-decoration: underline; cursor: pointer;"><span class="text-success">Добавить еще</span></div>
                                    <div id="delete_carrier_edit_info_input" class="mb-1" style="text-decoration: underline; cursor: pointer;"><span class="text-danger">Удалить</span></div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отменить</button>
                    <button type="button" class="btn btn-primary" id="btn-add-carrier-from-edit">Сохранить</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal modal-add-application fade" id="modalClientEdit" tabindex="-1" aria-labelledby="modalClientEdit" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title title-base" id="exampleModalLabel">Редактирование перевозчика</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                </div>
                <div class="modal-body">
                    <form action="application/edit-client" method="post" id="form-edit-client-from-edit" class="form-edit-client">
                        <div class="row">
                            <div class="col">
                                <div class="col" id="client_edit_add_inputs">
                                    <div class="mb-4">
                                        <input class="d-none" id="client_edit_id" value="0" name="client_edit_id">
                                        <label for="" class="mb-1">Название <span class="text-danger">*</span></label>
                                        <input id="client_edit_name" type="text" name="client_name" placeholder="Название" class="form-control form-control-solid" required>
                                    </div>
                                    <div class="mb-4">
                                        <label for="" class="mb-1">ИНН <span class="text-danger">*</span></label>
                                        <input id="client_edit_inn" type="number" name="client_inn" placeholder="ИНН" class="form-control form-control-solid" required>
                                    </div>
                                    <div class="mb-4">
                                        <label for="" class="mb-1">Юридический адрес <span class="text-danger">*</span></label>
                                        <textarea id="client_edit_legal_address" name="client_legal_address" placeholder="Адрес" class="form-control form-control-solid" required></textarea>
                                    </div>
                                    <div class="mb-4">
                                        <label for="" class="mb-1">Телефон <span class="text-danger">*</span></label>
                                        <input id="client_edit_phone" type="text" name="client_edit_phone" placeholder="Телефон" class="form-control form-control-solid" required>
                                    </div>
                                </div>
                                <!--                            <div class="mb-4 d-flex justify-content-between">-->
                                <!--                                <div id="add_client_edit_info_input" class="mb-1" style="text-decoration: underline; cursor: pointer;"><span class="text-success">Добавить еще</span></div>-->
                                <!--                                <div id="delete_client_edit_info_input" class="mb-1" style="text-decoration: underline; cursor: pointer;"><span class="text-danger">Удалить</span></div>-->
                                <!--                            </div>-->
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отменить</button>
                    <button type="button" class="btn btn-primary" id="btn-add-client-from-edit">Сохранить</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal modal-add-application fade" id="modalAddClient" tabindex="-1" aria-labelledby="modalAddClient" aria-hidden="true">
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
                                            <option value="<?php echo $typeTaxation["name"]; ?>"><?php echo $typeTaxation['name']; ?></option>
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
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отменить</button>
                    <button type="button" class="btn btn-primary" id="btn-add-client-from">Добавить</button>
                </div>
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
                        <div class="row">
                            <div class="col" id="driver_add_inputs">
                                <div class="mb-4">
                                    <label for="" class="mb-1">ФИО <span class="text-danger">*</span></label>
                                    <input type="text" name="driver_full_name" placeholder="ФИО" class="form-control form-control-solid" required>
                                </div>
                                <div class="mb-4">
                                    <label for="" class="mb-1">Водительское удостоверение <span class="text-danger">*</span></label>
                                    <input type="text" name="driver_license" id="driver_license-input" placeholder="Водительское удостоверение" class="form-control form-control-solid" required>
                                </div>
                                <div class="mb-4">
                                    <label for="" class="mb-1">Номер телефона <span class="text-danger">*</span></label>
                                    <input type="text" name="driver_phone" id="driver_phone_input" placeholder="8 ()--" class="form-control form-control-solid driver-phone-input" required value="8 ()--">
                                </div>
                                <h5 class="mb-2">Паспортные данные</h5>

                                <div class="mb-4">
                                    <label for="" class="mb-1">Серия/Номер <span class="text-danger">*</span></label>
                                    <input type="text" name="driver_passport_serial_number" id="driver_passport_serial_number_input" placeholder="Серия/Номер" class="form-control form-control-solid" required>
                                </div>
                                <div class="mb-4">
                                    <label for="" class="mb-1">Кем выдан <span class="text-danger">*</span></label>
                                    <input type="text" name="driver_issued_by" placeholder="Кем выдан" class="form-control form-control-solid" style="text-transform: uppercase;" required>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div class="mb-4">
                                            <label for="" class="mb-1">Дата выдачи <span class="text-danger">*</span></label>
                                            <input autocomplete="off" type="text" name="driver_issued_date" id="driver_issued_date_input" placeholder="Дата выдачи" class="form-control form-control-solid" required>
<!--                                            <script type="module">-->
<!--                                                new AirDatepicker('#driver_issued_date_input');-->
<!--                                            </script>-->
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="mb-4">
                                            <label for="" class="mb-1">Код подразделения <span class="text-danger">*</span></label>
                                            <input type="text" name="driver_department_code" id="driver_department_code_input" placeholder="Код подразделения" class="form-control form-control-solid" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отменить</button>
                    <button type="button" class="btn btn-primary" id="btn-add-driver-from">Добавить</button>
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
                                    <input type="text" name="driver_full_name" id="driver_full_name_input-edit" placeholder="ФИО" class="form-control form-control-solid" required>
                                </div>
                                <div class="mb-4">
                                    <label for="" class="mb-1">Водительское удостоверение <span class="text-danger">*</span></label>
                                    <input type="text" name="driver_license" id="driver_license_input-edit" placeholder="Водительское удостоверение" class="form-control form-control-solid" required>
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

    <button type="button" class="btn btn-primary d-none" id="liveToastBtn">Показать пример</button>

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

    <script>
        $('#add-carrier-inn').bind('input', function () {
            let inn = $(this).val();
            let $this = $(this);
            $.ajax({
                method: 'POST',
                url: '/application/ajax/check-isset-carrier-inn',
                data: {inn: inn},
                success: function (response) {
                    console.log(response);

                    response = JSON.parse(response);

                    if(response['isset']){
                        $this.addClass('error-validate');
                        $('#error-carrier-inn-isset').removeClass('d-none');
                    }
                    else{
                        $this.removeClass('error-validate');
                        $('#error-carrier-inn-isset').addClass('d-none');

                    }

                }
            })

        })
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
                    console.log(itemSelect)
                    jQuery(cityApiSelect2Input).on('input', function () {

                        var url = "https://suggestions.dadata.ru/suggestions/api/4_1/rs/suggest/address";
                        var token = "5bc73fed829d8a90e4fb93de8387182eb829acc3";
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
    // new 20-09-24 start
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
    // new 20-09-24 end

        $('.form__body__carrier .edit-driver-btn-modal').click(function () {
            $("#edit-driver-side").val("carrier");
        });
        $('.form__body__client .edit-driver-btn-modal').click(function () {
            $("#edit-driver-side").val("client");
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
                        if($("#edit-driver-side").val() === "client"){
                            $(".driver_client_wrap").html(`<label for="basic-url" class="form-label">ФИО <span class="text-danger">*</span></label>
                              <select name="driver_client" id="driver_client" class="form-select united-select select  select-add-application">

                                    <?php foreach ($listDrivers as $driver){ ?>
                                        <option value="<?php echo $driver['id']; ?>"><?php echo $driver['name']; ?></option>
                                    <?php } ?>
                                 <option value="${data['data']['id']}">${data['data']['name']} selected</option>
                             </select>`);



                            ajaxGetDriver(driver_id, false);

                            document.getElementById('driver_client').value = data['data']['id'];

                            choicesDriverClient = new Choices('#driver_client', {allowHTML: true,});
                        }
                        else{
                            $(".driver_carrier_wrap").html(`<label for="basic-url" class="form-label">ФИО <span class="text-danger">*</span></label>
                            <select name="driver_carrier" id="driver_carrier" class="form-select united-select select  select-add-application">
                                <option value="0" disabled >Водитель</option>
                                <?php foreach ($listDrivers as $driver){ ?>
                                    <option value="<?php echo $driver['id']; ?>"><?php echo $driver['name']; ?></option>
                                <?php } ?>
                                 <option value="${data['data']['id']}" selected>${data['data']['name']}</option>
                             </select>`);

                            ajaxGetDriver(driver_id, true);

                            console.log(data['data']['id']);

                            document.getElementById('driver_carrier').value = data['data']['id'];
                            choicesDriverCarrier = new Choices('#driver_carrier', {allowHTML: true,});

                            // добавление водителя если вдруг что-то ломается первым делом отключить этот блок - старт
                            $(".driver_client_wrap").html(`<label for="basic-url" class="form-label">ФИО <span class="text-danger">*</span></label>
                              <select name="driver_client" id="driver_client" class="form-select united-select select  select-add-application">
                                    <option value="0" disabled selected>Водитель</option>
                                    <?php foreach ($listDrivers as $driver){ ?>
                                        <option value="<?php echo $driver['id']; ?>"><?php echo $driver['name']; ?></option>
                                    <?php } ?>
                                 <option value="${data['data']['id']}">${data['data']['name']}</option>
                             </select>`);

                            driveHTML = `<label for="basic-url" class="form-label">ФИО <span class="text-danger">*</span></label>
                              <select name="driver_client" id="driver_client" class="form-select united-select select  select-add-application">
                                    <option value="0" disabled selected>Водитель</option>
                                    <?php foreach ($listDrivers as $driver){ ?>
                                        <option value="<?php echo $driver['id']; ?>"><?php echo $driver['name']; ?></option>
                                    <?php } ?>
                                 <option value="${data['data']['id']}">${data['data']['name']}</option>
                             </select>`;

                            document.getElementById('driver_client').value = data['data']['id'];

                            choicesDriverClient = new Choices('#driver_client', {allowHTML: true,});
                            // добавление водителя если вдруг что-то ломается первым делом отключить этот блок - конец
                        }
                        setDriversSelectsOptions();
                    }
                    else if(data['status'] === "Error"){
                        alert("Не удалось отредактировать данные водителя");
                    }
                    else{
                        alert("Не правильно заполнены поля");
                    }
                }
            });
        })
        $('.application-driver-data-transport').hide();
        $('#driver_carrier_transport').change(function (){
            let dataTransport = $(this).val();

            if(dataTransport == 0){
                dataTransport = ['','','','',''];
            }
            else{
                dataTransport = dataTransport.split(' ; ');
            }

            $('#mark-input_carrier').val(dataTransport[0]);
            $('#gos-number-input_carrier').val(dataTransport[1]);
            $('#pp_carrier').val(dataTransport[2]);
            $('#transport-type-input_carrier').val(dataTransport[3]);
            $('#carcase-type-input_carrier').val(dataTransport[4]);
            console.log(dataTransport);
        })
        var driveHTML = `<label for="basic-url" class="form-label">ФИО <span class="text-danger">*</span></label>
              <select name="driver_client" id="driver_client" class="form-select united-select select  select-add-application">
                    <option value="0" disabled selected>Водитель</option>
                    <?php foreach ($listDrivers as $driver){ ?>
                        <option value="<?php echo $driver['id']; ?>"><?php echo $driver['name']; ?></option>
                    <?php } ?>
             </select>`;
        function formatNumber(num) {
            return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1 ')
        }



        document.getElementById('cost-input_carrier').oninput = function (){
            let value = $('#cost-input_carrier').val();
            value = value.replaceAll(' ' ,'');
            console.log(value);

            $('#cost-input_carrier').val(formatNumber(value));
        }

        document.getElementById('cost-input_client').oninput = function (){
            let value = $('#cost-input_client').val();
            value = value.replaceAll(' ' ,'');
            console.log(value);

            $('#cost-input_client').val(formatNumber(value));
        }
        document.getElementById('cost-cargo_client').oninput = function (){
            let value = $('#cost-cargo_client').val();
            value = value.replaceAll(' ' ,'');
            console.log(value);

            $('#cost-cargo_client').val(formatNumber(value));
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


        $('body').on('click','.error-validate' ,function () {
            $(this).removeClass('error-validate');
        })
        const toastTrigger = document.getElementById('liveToastBtn')
        const toastLiveExample = document.getElementById('liveToast')

        if (toastTrigger) {
            const toastBootstrap = bootstrap.Toast.getOrCreateInstance(toastLiveExample)
            toastTrigger.addEventListener('click', () => {
                toastBootstrap.show()
            })
        }

        let validateFields = {
            customerIdCarrier: {element: $('#customer_carrier'), name: 'Перевозчик->Заказчик'},
            carrierIdCarrier: {element: $('.choices__inner').has('#carrier_id_input'), name: 'Перевозчик->Перевозчик'},
            customerIdClient: {element: $('#customer_client'), name: 'Клиент->Заказчик'},
            applicationNumberClient: {element: $('#application_number_client'), name: 'Клиент->Номер заявки клиента'},
            clientIdClient: {element: $('.choices__inner').has('#client_id_input'), name: 'Клиент->Клиент'},
            driverIdClient: {element: $('.choices__inner').has('#driver_client'), name: 'Клиент->Водитель'},
            driverIdCarrier: {element: $('.choices__inner').has('#driver_carrier'), name: 'Перевозчик->Водитель'},
            natureCargoCarrier: {element: $('.form-floating').has('#nature_cargo_carrier'), name: 'Перевозчик->Характер груза'},
            natureCargoClient: {element: $('.form-floating').has('#nature_cargo_client'), name: 'Клиент->Характер груза'},
            carBrandIdCarrier: {element: $('#mark-input_carrier'), name: 'Перевозчик->Марка'},
            carBrandIdClient: {element: $('#mark-input_client'), name: 'Клиент->Марка'},
            governmentNumberCarrier: {element: $('#gos-number-input_carrier'), name: 'Перевозчик->Гос номер'},
            governmentNumberClient: {element: $('#gos-number-input_client'), name: 'Клиент->Гос номер'},
            typeTransportIdCarrier: {element: $('#transport-type-input_carrier'), name: 'Перевозчик->Тип транспорта'},
            typeTransportIdClient: {element: $('#transport-type-input_client'), name: 'Клиент->Тип транспорта'},
            placeCarrier: {element: $('#place-input_carrier'), name: 'Перевозчик->Мест'},
            placeClient: {element: $('#place-input_client'), name: 'Клиент->Мест'},
            weightCarrier: {element: $('#weight-input_carrier'), name: 'Перевозчик->Вес'},
            weightClient: {element: $('#weight-input_client'), name: 'Клиент->Вес'},
            refModeCarrier: {element: $('#ref-mode-input_carrier'), name: 'Перевозчик->Реф режим'},
            refModeClient: {element: $('#ref-mode-input_client'), name: 'Клиент->Реф режим'},
            // transportationCarrier: true,
            // transportationClient: true,
            // termsPaymentCarrier: true,
            // termsPaymentClient: true,
            transportationCostCarrier: {element: $('#cost-input_carrier'), name: 'Перевозчик->Стоимость перевозки'},
            transportationCostClient: {element: $('#cost-input_client'), name: 'Клиент->Стоимость перевозки'},
            taxationTypeCarrier: {element: $('#taxation-type-input_carrier'), name: 'Перевозчик->Вид налогообложения'},
            taxationTypeClient: {element: $('#taxation-type-input_client'), name: 'Клиент->Вид налогообложения'},
            // prerequisitesCarrier: true,

        };

        function removeValidateError(){
            for(key in validateFields)
                validateFields[key]['element'].removeClass('error-validate')
        }
//
//
//
//

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

        $('#taxation-type-input_client').change(function () {
            let type = $(this).val();
            let typeFor = 1;

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


                    $('.terms-payment_client-wrap').html(
                        `<select name="terms-payment_client" id="select-terms-payment_client"
                                class="form-select select-chosen united-select" data-placeholder="">${htmlOption}</select>`
                    );
                    new Choices('#select-terms-payment_client', {allowHTML: true,});
                }
            })
        })

        $('.js-trigger-select-choices').click(function () {
            $('.btn-plus-condition').has(this).find('.choices').trigger('click');
        })
        $('.js-trigger-input-choices').dblclick(function (){
            $('.input-group').has(this).find('.choices').trigger('click');
        })
        $('#driver_issued_date_input').inputmask('99.99.9999');


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

        function ajaxGetCarrier(carrier_id){
            $.ajax({
                url: '/application/ajax/get-carrier',
                method: 'POST',
                data: {id: carrier_id},
                success: function (carrier_json){
                    $('#modal_carrier_edit').removeClass('d-none');

                    let carrier_array = JSON.parse(carrier_json);

                    $('#carrier_edit_id').val(carrier_array['id']);
                    $('#carrier_edit_name').val(carrier_array['name']);
                    $('#carrier_edit_inn').val(carrier_array['inn']);
                    $('#carrier_edit_legal_address').val(carrier_array['legal_address']);



                    let newInfoArray = carrier_array['info'].split('||');

                    $('.carrier-info-input-wrap').remove();
                    for (let i = 0; i < newInfoArray.length; i++) {
                        $('#modalCarrierEdit #carrier_edit_add_inputs').append(`
                                        <div class="mb-4 carrier-info-input-wrap">
                                            <label for="" class="mb-1">Телефон, код в АТИ, конт. лицо, почта <span class="text-danger">*</span></label>
                                            <input id="carrier_edit_info" type="text" name="carrier_info" id="carrier_info-input" placeholder="8 ()--, код в АТИ, Имя:" class="form-control form-control-solid carrier_info-input" required value="`+newInfoArray[i]+`">
                                        </div>
                                    `);

                    }
                    setFieldsView();

                    $('.carrier-info').show();
                    $('.carrier-info #carrier_info_inn').html(carrier_array['inn']);
                    $('.carrier-info #carrier_info_legal_address').html(carrier_array['legal_address']);


                    let infoArrayCarrier = carrier_array['info'].split("||");

                    let infoHtmlCarrier = `<select name="carrier_chosen_info" id="carrier_chosen_info" class="form-select select-add-application united-select select">`;

                    infoArrayCarrier.forEach((item) => {
                        infoHtmlCarrier += `<option value="${item}">${item}</option>`
                    });

                    infoHtmlCarrier += `</select>`;

                    $('.carrier-info #carrier_info_info').html(infoHtmlCarrier);
                }
            });
        }

        function ajaxGetClient(client_id){
            $.ajax({
                url: '/application/ajax/get-client',
                method: 'POST',
                data: {id: client_id},
                success: function (client_json){
                    $('#modal_client_edit').removeClass('d-none');

                    let client_array = JSON.parse(client_json);
                    console.log(client_array);
                    $('#client_edit_id').val(client_array['id']);
                    $('#client_edit_name').val(client_array['name']);
                    $('#client_edit_inn').val(client_array['inn']);
                    $('#client_edit_legal_address').val(client_array['legal_address']);
                    $('#client_edit_phone').val(client_array['phone']);

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

                    if(client_array['application_format']){
                        $('.col-application_number_client').removeClass('d-none');
                        $('input[name="application_number_client"').attr('required');
                    }
                }
            });
        }
        $('.edit-driver-btn-modal').hide();
        function ajaxGetDriver(driver_id, isForCarrier){
            $.ajax({
                url: '/application/ajax/get-driver',
                method: 'POST',
                data: {id: driver_id},
                success: function (driver_json){
                    $('.driver-info').show();

                    let driver_array = JSON.parse(driver_json);


                    console.log(driver_array);
                    console.log(driver_array['listCar']);



                    if(isForCarrier){

                        $('.edit-driver-btn-modal').show();
                        console.log(driver_array);
                        $('#driver_id_input-edit').val(driver_array['id']);
                        $('#driver_full_name_input-edit').val(driver_array['name']);
                        $('#driver_license_input-edit').val(driver_array['driver_license']);
                        $('#driver_phone_input-edit').val(driver_array['phone']);
                        $('#driver_passport_serial_number_input-edit').val(driver_array['passport_serial_number']);
                        $('#driver_issued_by_input-edit').val(driver_array['issued_by']);
                        $('#driver_issued_date_input-edit').val(driver_array['issued_date']);
                        $('#driver_department_code_input-edit').val(driver_array['department_code']);

                        $('.form__body__carrier .driver-info').show();
                        $('#driver_info_name_carrier').html(driver_array['name']);
                        $('#driver_info_licence_carrier').html(driver_array['driver_license']);
                        $('#driver_info_phone_carrier').html(driver_array['phone']);

                        $('#driver_info_passport_carrier').html(driver_array['passport_serial_number']);

                        if(driver_array['listCar'].length > 0){

                            let htmlOptionDriverCar = `<option value="0">По умолчанию</option>`;
                            let listCar = driver_array['listCar'];

                            for(let i = 0; i < listCar.length; i++){
                                let temp = listCar[i];
                                htmlOptionDriverCar +=
                                    `<option value="${temp['car_brand']} ; ${temp['government_number']} ; ${temp['semitrailer']} ; ${temp['type_transport']} ; ${temp['type_carcase']}">
                                        ${temp['car_brand']} ${temp['government_number']} ${temp['semitrailer']}
                                        ${temp['type_transport']} ${temp['type_carcase']}
                                    </option>`;
                            }

                            $('#driver_carrier_transport').html(htmlOptionDriverCar);

                            $('.application-driver-data-transport').show();
                        }
                        else{
                            $('.application-driver-data-transport').hide();
                        }
                    }
                    else{
                        $('.form__body__client .driver-info').show();
                        $('#driver_info_name_client').html(driver_array['name']);
                        $('#driver_info_licence_client').html(driver_array['driver_license']);

                        $('#driver_info_passport_client').html(driver_array['passport_serial_number']);
                        $('#driver_info_phone_client').html(driver_array['phone']);
                    }
                }
            });
        }

        $('#carrier_id_input').on('change', function (){
            let carrier_id = $('#carrier_id_input').val();

            ajaxGetCarrier(carrier_id);
        });

        $('#client_id_input').on('change', function (){
            // $('#clientEdit_btn').show();
            let client_id = $('#client_id_input').val();

            ajaxGetClient(client_id);
        });

        document.getElementById("customer_carrier").value = <?php echo $application["customer_id_Carrier"]; ?>;
        document.getElementById("carrier_id_input").value = <?php echo $application["carrier_id_Carrier"]; ?>;
        ajaxGetCarrier(<?php echo $application["carrier_id_Carrier"]; ?>);

        document.getElementById("customer_client").value = <?php echo $application["customer_id_Client"]; ?>;

        document.getElementById("client_id_input").value = <?php echo $application["client_id_Client"]; ?>;
        ajaxGetClient(<?php echo $application["client_id_Client"]; ?>);

        document.getElementById("driver_carrier").value = <?php echo $application["driver_id_Carrier"]; ?>;
        ajaxGetDriver(<?php echo $application["driver_id_Carrier"]; ?>, true);
        let choicesDriverCarrier = new Choices('#driver_carrier', {allowHTML: true,});

        document.getElementById("driver_client").value = <?php echo $application["driver_id_Client"]; ?>;
        ajaxGetDriver(<?php echo $application["driver_id_Client"]; ?>, false);
        let choicesDriverClient = new Choices('#driver_client', {allowHTML: true,});

        document.getElementById("style-select").value = <?php echo $application["is_classic"]; ?>;

        //document.getElementById("select-addition").value = <?php //echo $application["driver_id_Carrier"]; ?>//;

        // $("#select-addition").on('change', function(){
        //     let addition_id = $(this).val();
        //     $.ajax({
        //         url: "/application/ajax/get-addition",
        //         method: "POST",
        //         data: {id: addition_id},
        //         success: function(response){
        //             console.log(response);
        //             $("#addition").html(response);
        //             Editor_addition.setData(response);
        //         }
        //     });
        // });

        let taxationTypeCarrier = 0;
        switch("<?php echo $application["taxation_type_Carrier"]; ?>") {
            case 'С НДС':
                taxationTypeCarrier = 1;
                break;

            case 'Б/НДС':
                taxationTypeCarrier = 2;
                break;

            case 'НАЛ':
                taxationTypeCarrier = 3;
                break;

            case 'НДС 0%':
                taxationTypeCarrier = 4;
                break;

        }
        document.getElementById("taxation-type-input_carrier").selectedIndex = taxationTypeCarrier;
        $('#taxation-type-input_carrier').trigger('change');
        let taxationTypeClient = 0;
        console.log(`<?php echo $application["taxation_type_Client"]; ?>`)
        switch("<?php echo $application["taxation_type_Client"]; ?>") {
            case 'С НДС':
                taxationTypeClient = 1;
                break;

            case 'Б/НДС':
                taxationTypeClient = 2;
                break;

            case 'НАЛ':
                taxationTypeClient = 3;
                break;

            case 'НДС 0%':
                taxationTypeClient = 4;
                break;
        }

        document.getElementById("taxation-type-input_client").selectedIndex = taxationTypeClient;
        console.log(document.getElementById("taxation-type-input_client").selectedIndex)
        $('#taxation-type-input_client').trigger('change');


        var choicesCarrier = new Choices('#carrier_id_input', {allowHTML: true,});
        var choicesClient = new Choices('#client_id_input', {allowHTML: true,});

        let marshrutIndex = 0;
        let fineIndex = 0;
        let expenseIndex = 0;
        let additionalProfitIndex = 0;

        let html_ = '';
        let direction_ = "";
        let method_ = "";

        <?php foreach ($listRoutes as $route){?>
        marshrutIndex++;
        <?php if($route["direction"] === 1){?>
        direction_ = "Откуда";
        method_ = "загрузки"
        <?php }
        else{?>
        direction_ = "Куда";
        method_ = "разгрузки";
        <?php }?>

        html_ = `<div class="marshrut-list__item row marshrut-list__item_${marshrutIndex}"
                            data-direction="<?php echo $route["direction"];?>" data-city="<?php echo $route["city"];?>"
                            data-address="<?php echo $route["address"];?>" data-date="<?php echo $route["date"];?>"
                            data-time="<?php echo $route["time"];?>" data-contact="<?php echo $route["contact"];?>"
                            data-phone="<?php echo $route["phone"];?>" data-loading_method="<?php echo $route["loading_method"];?>" data-sort="<?php echo $route["sort"];?>">
                    <div class="drag-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#B5B5C3"
                             class="bi bi-grip-vertical" viewBox="0 0 16 16">
                            <path d="M7 2a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zM7 5a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zM7 8a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm-3 3a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm-3 3a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"></path>
                        </svg>
                    </div>
                    <div class="col-2">
                        <label for="">${direction_}</label>
                        <span class="marshrut-address_${marshrutIndex}"><?php echo $route["city"];?>, <?php echo $route["address"];?></span>
                    </div>
                    <div class="col-2">
                        <label for="">Дата</label>
                        <span class="marshrut-date_${marshrutIndex}"><?php echo $route["date"];?></span>
                    </div>
                    <div class="col">
                        <label for="">Время</label>
                        <span class="marshrut-time_${marshrutIndex}"><?php echo $route["time"];?></span>
                    </div>
                    <div class="col">
                        <label for="">Контактное лицо</label>
                        <span class="marshrut-contact-face_${marshrutIndex}"><?php echo $route["contact"];?></span>
                    </div>
                    <div class="col">
                        <label for="">Номер</label>
                        <span class="marshrut-number_${marshrutIndex}"><?php echo $route["phone"];?></span>
                    </div>
                    <div class="col">
                        <label for="">Способ ${method_}</label>
                        <span class="marshrut-method_${marshrutIndex}"><?php echo $route["loading_method"];?></span>
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

        <?php if($route["type_for"] === 1){?>
        $('.form__body__client #marshrut-list').append(html_);
        <?php }else{?>
        $('.form__body__carrier #marshrut-list').append(html_);
        <?php }?>

        <?php }?>

        let html__  = "";

        <?php foreach ($listFines as $fine){?>
        fineIndex++;

        html__ = `<div class="fines-item fines-item_${fineIndex}" data-type-for="<?php echo $fine["type_for"];?>" data-sum="<?php echo $fine["sum"];?>"
                        data-description="<?php echo $fine["description"];?>" data-datetime="<?php echo $fine["datetime"];?>">
                <div class="row">
                    <div class="col">
                        <label for="">Сумма, ₽</label>
                        <span class="fine-sum_${fineIndex}"><?php echo $fine["sum"];?></span>
                    </div>
                    <div class="col">
                        <label for="">Описание</label>
                        <span class="fine-description_${fineIndex}"><?php echo $fine["description"];?></span>
                    </div>
                    <div class="col">
                        <label for="">Дата оплаты</label>
                        <span class="fine-date_${fineIndex}"><?php echo $fine["datetime"];?></span>
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
                                <li><div class="dropdown-item js-delete-fines">Удалить</div></li>
                                <li><div class="dropdown-item js-edit-fines" onclick="editFine(${fineIndex});">Редактировать</div></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>`;

        <?php if($fine["type_for"] === 1){?>
        $('#fines-to-block').append(html__);
        <?php }else{?>
        $('#fines-from-block').append(html__);
        <?php }?>

        <?php }?>

        let html___  = "";

        <?php foreach ($additionalExpenses as $additionalExpense){?>
        expenseIndex++;

        html___ = `<div class="expenses-item expenses-item_${expenseIndex}" data-type-for="<?php echo $additionalExpense["type_for"];?>"
         data-type-expenses="<?php echo $additionalExpense["type_expenses"];?>"
         data-sum="<?php echo $additionalExpense["sum"];?>" data-id="<?php echo $additionalExpense['id']; ?>"
         data-type-payment="<?php echo $additionalExpense["type_payment"];?>" data-comment="<?php echo $additionalExpense["comment"];?>">
                <div class="row">
                    <div class="col">
                        <label for="">Вид затрат</label>
                        <span class="expense-type-expenses_${expenseIndex}"><?php echo $additionalExpense["type_expenses"];?></span>
                    </div>
                    <div class="col">
                        <label for="">Сумма</label>
                        <span class="expense-sum_${expenseIndex}"><?php echo $additionalExpense["sum"];?></span>
                    </div>
                    <div class="col">
                        <label for="">Вид налогообложения</label>
                        <span class="expense-type-payment_${expenseIndex}"><?php echo $additionalExpense["type_payment"];?></span>
                    </div>
                    <div class="col">
                        <label for="">Комментарий</label>
                        <span class="expense-comment_${expenseIndex}"><?php echo $additionalExpense["comment"];?></span>
                    </div>
                    <div class="col">
                    <?php if($controller->auth->user()->fullCRM()): ?>
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
                                <li><div class="dropdown-item js-edit-expenses" onclick="editExpense(${expenseIndex});">Редактировать</div></li>
                             </ul>
                        </div>
                    <?php endif; ?>
                    </div>
                </div>
            </div>`;

        <?php if($additionalExpense["type_for"] === 1){?>
        $('#expenses-carrier-block_client').append(html___);
        <?php }else{?>
        $('#expenses-carrier-block_carrier').append(html___);
        <?php }?>

        <?php }?>


        let html____ = "";
        <?php foreach ($additionalProfit as $additional_profit){?>
        additionalProfitIndex++;

        html____ = `<div class="additional_profit-item additional_profit-item_${additionalProfitIndex}" data-type="<?php echo $additional_profit["type"];?>"
                        data-sum="<?php echo $additional_profit["sum"];?>" data-type-payment="<?php echo $additional_profit["type_payment"];?>"
                        data-comment="<?php echo $additional_profit["comment"];?>">
                <div class="row">
                    <div class="col">
                        <label for="">Вид доп прибыли</label>
                        <span class="additional_profit-type_${additionalProfitIndex}"><?php echo $additional_profit["type"];?></span>
                    </div>
                    <div class="col">
                        <label for="">Сумма</label>
                        <span class="additional_profit-sum_${additionalProfitIndex}"><?php echo $additional_profit["sum"];?></span>
                    </div>
                    <div class="col">
                        <label for="">Вид налогообложения</label>
                        <span class="additional_profit-type-payment_${additionalProfitIndex}"><?php echo $additional_profit["type_payment"];?></span>
                    </div>
                    <div class="col">
                        <label for="">Комментарий</label>
                        <span class="additional_profit-comment_${additionalProfitIndex}"><?php echo $additional_profit["comment"];?></span>
                    </div>
                </div>
            </div>`;
        $('#additional_profit-block_client').append(html____);
        <?php }?>

        function setFieldsView(){
            if ($("#style-select").val() === "0"){
                setFieldsFree();
                console.log("free");
            }
            else{
                setFieldsClassic();
                console.log("classic");
            }
        }

        function setFieldsClassic(){
            $('#gos-number-input_carrier').inputmask('A 999 AA / 999{0,1}');
            $('#pp_carrier').inputmask('AA 9999 / 999{0,1}');

            $('#gos-number-input_client').inputmask('A 999 AA / 999{0,1}');
            $('#pp_client').inputmask('AA 9999 / 999{0,1}');

            $("#client-phone-input").inputmask("8 (999) 999-99-99");

            $("#carrier_info-input").inputmask("8 (999) 999-99-99, код в АТИ *{0,50}");
            $("input[name='carrier_info']").inputmask({
                mask: "8 (999) 999-99-99, код в АТИ 9{3,10}, Имя: *{0,50}",
                definitions: {
                    '*': {
                        validator: "[\\s\\S]",
                        cardinality: 0,
                    }
                }
            });

            $("#client_edit_phone").inputmask('8 (999) 999-99-99');

            $("#driver_phone_input").inputmask("8 (999) 999-99-99");

            $("#driver_license-input").inputmask("99 99 999999");

            $("#driver_passport_serial_number_input").inputmask("99 99 999999");

            $("#driver_department_code_input").inputmask("999-999");

            $("#transportation-time-input").val("");
            $("#transportation-time-input").inputmask("99:99 - 99:99");

            $('.inputmask-phone').inputmask('8 (999) 999-99-99');
        }
        function setFieldsFree(){
            let buffer = '';

            buffer = $('#gos-number-input_carrier').val();
            $('#gos-number-input_carrier').inputmask("remove");
            $('#gos-number-input_carrier').val(buffer);

            buffer = $('#pp_carrier').val();
            $('#pp_carrier').inputmask("remove");
            $('#pp_carrier').val(buffer);

            buffer = $('#gos-number-input_client').val();
            $('#gos-number-input_client').inputmask("remove");
            $('#gos-number-input_client').val(buffer);

            buffer = $('#pp_client').val();
            $('#pp_client').inputmask("remove");
            $('#pp_client').val(buffer);

            buffer = $('#client-phone-input').val();
            $("#client-phone-input").inputmask("remove");
            $('#client-phone-input').val(buffer);

            buffer = $('#carrier_info-input').val();
            $("#carrier_info-input").inputmask("remove");
            $('#carrier_info-input').val(buffer);

            buffer = $("input[name='carrier_info']").val();
            $("input[name='carrier_info']").inputmask("remove");
            $("input[name='carrier_info']").val(buffer);


            buffer = $('#client_edit_phone').val();
            $("#client_edit_phone").inputmask("remove");
            $('#client_edit_phone').val(buffer);

            buffer = $('#driver_phone_input').val();
            $("#driver_phone_input").inputmask("remove");
            $('#driver_phone_input').val(buffer);

            buffer = $('#driver_license-input').val();
            $("#driver_license-input").inputmask("remove");
            $('#driver_license-input').val(buffer);

            buffer = $('#driver_passport_serial_number_input').val();
            $("#driver_passport_serial_number_input").inputmask("remove");
            $('#driver_passport_serial_number_input').val(buffer);

            buffer = $('#driver_department_code_input').val();
            $("#driver_department_code_input").inputmask("remove");
            $('#driver_department_code_input').val(buffer);

            buffer = $('#transportation-time-input').val();
            $("#transportation-time-input").inputmask("remove");
            $('#transportation-time-input').val(buffer);

            $('.inputmask-phone').inputmask('remove');
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

        $('#style-select').on('change', function(){
            setFieldsView();
        });

        $('.duplicate').on('click', function(){
            $('#customer_client').val($('#customer_carrier').val());

            let marshruts_carrier = collect_route("carrier");

            let html = ``;
            console.log(marshruts_carrier);
            let direction;
            let method;

            for(let i = 0; i < marshruts_carrier.length; i++){
                direction = 'Куда';
                method = 'разгрузки';
                let item = marshruts_carrier[i];
                if(item['direction'] == 1){
                    direction = 'Откуда';
                    method = "погрузки";
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
                <div class="col">
                    <label for="">Способ ${method}</label>
                    <span class="marshrut-method_${marshrutIndex}">${item['loading_method']}</span>
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

            // $('#nature_cargo_client').val($('#nature_cargo_carrier').val());
            Editor_nature_cargo_client.setData(Editor_nature_cargo_carrier.getData());

            $('#place-input_client').val($('#place-input_carrier').val());
            $('#weight-input_client').val($('#weight-input_carrier').val());
            $('#ref-mode-input_client').val($('#ref-mode-input_carrier').val());
            $(".driver_client_wrap").html(driveHTML);

            ajaxGetDriver($('#driver_carrier').val(), false);

            document.getElementById('driver_client').value = $('#driver_carrier').val();

            choicesDriverClient = new Choices('#driver_client', {allowHTML: true,});
            // ajaxGetDriver($('#driver_carrier').val(), false);
            // $('.form__body__client .driver-info').html($('.form__body__carrier .driver-info').html());

            $('#mark-input_client').val($('#mark-input_carrier').val());
            $('#gos-number-input_client').val( $('#gos-number-input_carrier').val() );
            $('#pp_client').val( $('#pp_carrier').val() );
            $('#transport-type-input_client').val( $('#transport-type-input_carrier').val() );
            $('#carcase-type-input_client').val( $('#carcase-type-input_carrier').val() );
        });


        $('body').on('change','#select-terms-payment_carrier' , function (){
            getTermsPaymentDescription($(this).val(), "carrier");
            console.log($(this).val());
        });

        $('body').on('change','#select-terms-payment_client', function (){
            getTermsPaymentDescription($(this).val(), "client");
        })

        function getTermsPaymentDescription(id, side){
            $.ajax({
                url: "/application/ajax/getTermsPaymentDescription",
                method: "POST",
                data:{
                    id: id
                },
                success: function (response){
                    console.log(response);
                    if(side === "client"){
                        Editor_cost_textarea_client.setData(response);
                    }
                    else{
                        Editor_cost_textarea_carrier.setData(response);
                    }
                }
            });
        }

        $( ".united-select" ).each(function(element) {
            $(this).addClass('gg');
            new Choices(this, {
                allowHTML: true
            });
        });

        $('.united-select').on('change', function (){
            $(this).parents('.input-group').children('.united-input').val($(this).val());
        });

        $('.driver-info').hide();
        <?php if($canChangeApplication): ?>
        $('.application_add').click(function (){
            let customer_carrier = $('#customer_carrier').val();
            let main_slogan_carrier = $('#main-slogan-input_carrier').val();
            // let date_input_carrier = $('#date-input_carrier').val();
            let carrier_id_input = $('#carrier_id_input').val();

            let customer_client = $('#customer_client').val();
            let main_slogan_client = $('#main-slogan-input_client').val();
            let proxy_input = $('#proxy-input').val();
            let client_id_input = $('#client_id_input').val();

            let nature_cargo_carrier = Editor_nature_cargo_carrier.getData();
            let nature_cargo_client = Editor_nature_cargo_client.getData();
            let place_carrier = $('#place-input_carrier').val();
            let place_client = $('#place-input_client').val();
            let weight_carrier = $('#weight-input_carrier').val();
            let weight_client = $('#weight-input_client').val();
            let ref_mode_carrier = $('#ref-mode-input_carrier').val();
            let ref_mode_client = $('#ref-mode-input_client').val();


            let driver_client = $('#driver_client').val();
            let driver_carrier = $('#driver_carrier').val();

            let mark_client = $('#mark-input_client').val();
            let mark_carrier = $('#mark-input_carrier').val();

            let gos_number_carrier = $('#gos-number-input_carrier').val();
            let gos_number_client = $('#gos-number-input_client').val();
            let pp_carrier = $('#pp_carrier').val();
            let pp_client = $('#pp_client').val();

            let special_conditions_client = Editor_special_conditions_client.getData();
            let special_conditions_carrier = Editor_special_conditions_carrier.getData();

            let transport_type_carrier = $('#transport-type-input_carrier').val();
            let transport_type_client = $('#transport-type-input_client').val();

            let cost_textarea_carrier = Editor_cost_textarea_carrier.getData();
            let cost_textarea_client = Editor_cost_textarea_client.getData();

            let cost_carrier = $('#cost-input_carrier').val();
            cost_carrier = cost_carrier.replaceAll(' ','');
            let cost_client = $('#cost-input_client').val();
            cost_client = cost_client.replaceAll(' ','');

            let cost_cargo_client = $('#cost-cargo_client').val();
            cost_cargo_client = cost_cargo_client.replaceAll(' ','');

            let taxation_type_carrier = $('#taxation-type-input_carrier').val();
            let taxation_type_client = $('#taxation-type-input_client').val();

            let prerequisites = Editor_prerequisites.getData();

            let collect_marshrut_client = collect_route("client");
            let collect_marshrut_carrier = collect_route("carrier");
            console.log(JSON.stringify(collect_marshrut_carrier), collect_marshrut_carrier);
            let fines = collectFines();

            let expenses_client = collectExpenses('client');
            let expenses_carrier = collectExpenses('carrier');

            let additional_profit = collectAdditionalProfit();

            let carcase_type_carrier = $('#carcase-type-input_carrier').val();
            let carcase_type_client = $('#carcase-type-input_client').val();

            // let additionId = $('#addition').html();

            let style_select = $('#style-select').val();

            let check_show_driver_number = 1;

            if  (!$("#check-show-driver-number").prop('checked')){
                check_show_driver_number = 0;
            }

            let check_hide_title = 1;

            if  (!$("#check-hide-title").prop('checked')){

                check_hide_title = 0;
            }

            let carrier_chosen_info = $("#carrier_chosen_info").val();
            let client_chosen_info = $("#client_chosen_info").val();

            let additionId = $('#select-addition').val();
            let application_number_client = $('#application_number_client').val();

            var application_data = {
                "applicationId" : <?php echo $application["id"];?>,
                'customerIdCarrier' : customer_carrier,
                'applicationTitleCarrier': main_slogan_carrier,
                // 'dateCarrier': date_input_carrier,
                'carrierIdCarrier': carrier_id_input,
                'applicationNumberClient': application_number_client,

                'customerIdClient': customer_client,
                'applicationTitleClient': main_slogan_client,
                'proxyClient': proxy_input,
                'clientIdClient': client_id_input,

                'driverIdClient': driver_client,
                'driverIdCarrier': driver_carrier,

                'carBrandIdClient': mark_client,
                'carBrandIdCarrier': mark_carrier,
                'governmentNumberCarrier': gos_number_carrier,
                'governmentNumberClient': gos_number_client,
                'semitrailerCarrier': pp_carrier,
                'semitrailerClient': pp_client,
                'typeTransportIdCarrier': transport_type_carrier,
                'typeTransportIdClient': transport_type_client,

                'natureCargoCarrier': nature_cargo_carrier,
                'natureCargoClient': nature_cargo_client,
                'placeCarrier' : place_carrier,
                'placeClient' : place_client,
                'weightCarrier' : weight_carrier,
                'weightClient' : weight_client,
                'refModeCarrier' : ref_mode_carrier,
                'refModeClient' : ref_mode_client,

                'specialConditionsCarrier': special_conditions_carrier,
                'specialConditionsClient': special_conditions_client,

                'transportationCarrier': JSON.stringify(collect_marshrut_carrier),
                'transportationClient': JSON.stringify(collect_marshrut_client),
                'termsPaymentCarrier': cost_textarea_carrier,
                'termsPaymentClient': cost_textarea_client,

                'transportationCostCarrier': cost_carrier,
                'transportationCostClient': cost_client,
                'show_driver_number' : check_show_driver_number,

                'costCargoClient': cost_cargo_client,
                'hideTitle': check_hide_title,
                'taxationTypeCarrier': taxation_type_carrier,
                'taxationTypeClient': taxation_type_client,

                'prerequisitesCarrier': prerequisites,

                'fines': JSON.stringify(fines),
                'isClassic' : style_select,
                'expensesCarrier': JSON.stringify(expenses_carrier),
                'expensesClient': JSON.stringify(expenses_client),
                'additionalProfit': JSON.stringify(additional_profit),

                'typeCarcaseIdCarrier': carcase_type_carrier,
                'typeCarcaseIdClient': carcase_type_client,

                'additionId' : additionId,
                'carrier_chosen_info' : carrier_chosen_info,

                'client_chosen_info' : client_chosen_info
            };

            console.log(application_data);
            $.ajax({
                url: '/application/ajax/edit-application',
                method: 'POST',
                data: application_data,
                success: function (response){
                    console.log(response);

                    let data = JSON.parse(response);

                    if (data['status'] === "Success"){
                        document.location.href = "/application?id=" + data['id'];
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
                    else{
                        alert("Не правильно заполнены поля");
                    }
                }
            });
        });
        <?php else: ?>
        $('.application_add').click(function (){
            alert('Данную заявку нельзя редактировать');
        })
        <?php endif; ?>
        $('#driver_carrier').on('change', function (){
            let driver_id_carrier = $(this).val();

            ajaxGetDriver(driver_id_carrier, true);
            console.log('test');
        });

        $('#driver_client').on('change', function (){
            $('.driver-info').show();
            let driver_id_client = $(this).val();

            ajaxGetDriver(driver_id_client, false);
            // $.ajax({
            //     url: '/application/ajax/get-driver',
            //     method: 'POST',
            //     data: {id: driver_id_client},
            //     success: function (driver_json){
            //         // $('#modal_client_edit').removeClass('d-none');
            //
            //         let driver_array = JSON.parse(driver_json);
            //
            //         $('#client_edit_id').val(driver_array['id']);
            //         $('#client_edit_name').val(driver_array['name']);
            //         $('#client_edit_inn').val(driver_array['inn']);
            //         $('#client_edit_legal_address').val(driver_array['legal_address']);
            //         $('#client_edit_phone').val(driver_array['phone']);
            //
            //         $('.form__body__client .driver-info').show();
            //         $('#driver_info_name_client').html(driver_array['name']);
            //         $('#driver_info_licence_client').html(driver_array['driver_license']);
            //
            //         $('#driver_info_passport_client').html(driver_array['passport_serial_number']);
            //     }
            // });
        });



        $('#clientEdit_btn').hide();

        $('#form-edit-carrier-from-edit').submit(function (e) {
            e.preventDefault();

            let form = $(this).serializeArray();
            let form_data = {};

            form.forEach((form_element) => {
                form_data[form_element["name"]] = form_element["value"];
            });

            var carrier_info_inputs = Array.from($("#form-edit-carrier-from-edit .carrier_info-input"));
            var carrier_info_inputs_values = [];

            carrier_info_inputs.forEach((carrier_info_input)=>{
                carrier_info_inputs_values.push(carrier_info_input.value);
            });

            $.ajax({
                url: '/application/ajax/edit-carrier',
                method: 'POST',
                data: {form_data: form_data, info_inputs: carrier_info_inputs_values},
                success: function (response){
                    console.log(response);
                    if (response !== "Error"){
                        // document.location.reload();
                        let carrier_array = JSON.parse(response);

                        $('#carrier_edit_id').val(carrier_array['id']);
                        $('#carrier_edit_name').val(carrier_array['name']);
                        $('#carrier_edit_inn').val(carrier_array['inn']);
                        $('#carrier_edit_legal_address').val(carrier_array['legal_address']);

                        let newInfoArray = carrier_array['info'].split('||');

                        $('.carrier-info-input-wrap').remove();
                        for (let i = 0; i < newInfoArray.length; i++) {
                            $('#modalCarrierEdit #carrier_edit_add_inputs').append(`
                        <div class="mb-4 carrier-info-input-wrap">
                            <label for="" class="mb-1">Телефон, код в АТИ, конт. лицо, почта <span class="text-danger">*</span></label>
                            <input id="carrier_edit_info" type="text" name="carrier_info" id="carrier_info-input" placeholder="8 ()--, код в АТИ, Имя:" class="form-control form-control-solid carrier_info-input" required value="`+newInfoArray[i]+`">
                        </div>
                    `);
                        }

                        setFieldsView();

                        $('.carrier-info').show();
                        $('.carrier-info #carrier_info_inn').html(carrier_array['inn']);
                        $('.carrier-info #carrier_info_legal_address').html(carrier_array['legal_address']);

                        let newInfoStr = carrier_array['info'].replace('||', '<br>');

                        $('.carrier-info #carrier_info_info').html(newInfoStr);
                        response = JSON.parse(response);
                        ajaxGetCarrier(response['id']);
                        $('#modalCarrierEdit').modal('hide');
                    }
                }
            });
        });

        $('#btn-add-carrier-from-edit').click(function () {
            $('#form-edit-carrier-from-edit').trigger('submit');
        });

        $('.client-info').hide();


        $('#form-edit-client-from-edit').submit(function (e) {
            e.preventDefault();

            let form = $(this).serializeArray();
            console.log(form);
            $.ajax({
                url: '/application/ajax/edit-client',
                method: 'POST',
                data: form,
                success: function (response){
                    console.log(response);
                    if (response !== "Error"){
                        let client_array = JSON.parse(response);
                        console.log(client_array);
                        $('#client_edit_id').val(client_array['id']);
                        $('#client_edit_name').val(client_array['name']);
                        $('#client_edit_inn').val(client_array['inn']);
                        $('#client_edit_legal_address').val(client_array['legal_address']);
                        $('#client_edit_phone').val(client_array['phone']);

                        $('.client-info').show();
                        $('.client-info #client_info_inn').html(client_array['inn']);
                        $('.client-info #client_info_legal_address').html(client_array['legal_address']);

                        $('.client-info #client_info_info').html(client_array['phone']);

                        $('#modalClientEdit').modal('hide');
                    }
                }
            });
        });

        $('#btn-add-client-from-edit').click(function () {
            $('#form-edit-client-from-edit').trigger('submit');
        });

        $('.carrier-info').hide();

        $("#delete_carrier_edit_info_input").click(function (){
            if ($("#carrier_edit_add_inputs").children().length > 4){
                $(".carrier-info-input-wrap:last-child").remove();
            }
        });

        $("#add_carrier_edit_info_input").click(function (){
            $("#carrier_edit_add_inputs").append(`
                        <div class="mb-4 carrier-info-input-wrap">
                            <label for="" class="mb-1">Телефон, код в АТИ, конт. лицо, почта <span class="text-danger">*</span></label>
                            <input id="carrier_edit_info" type="text" name="carrier_info" placeholder="8 ()--, код в АТИ, Имя:" class="form-control form-control-solid carrier_info-input" required value="">
                        </div>
                    `);
            setFieldsView();
        });

        $('.form__body__carrier .add-driver-btn-modal').click(function () {
            $("#add-driver-side").val("carrier");
        });
        $('.form__body__client .add-driver-btn-modal').click(function () {
            $("#add-driver-side").val("client");
        });

        $('#form-add-driver-from').submit(function (e) {
            e.preventDefault();

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
                        if($("#add-driver-side").val() === "client"){
                            $(".driver_client_wrap").html(`<label for="basic-url" class="form-label">ФИО <span class="text-danger">*</span></label>
                              <select name="driver_client" id="driver_client" class="form-select united-select select  select-add-application">
                                    <option value="0" disabled selected>Водитель</option>
                                    <?php foreach ($listDrivers as $driver){ ?>
                                        <option value="<?php echo $driver['id']; ?>"><?php echo $driver['name']; ?></option>
                                    <?php } ?>
                                 <option value="${data['data']['id']}">${data['data']['name']}</option>
                             </select>`);

                            ajaxGetDriver(driver_id, false);
                            console.log(data['data']['id']);

                            document.getElementById('driver_client').value = data['data']['id'];

                            choicesDriverClient = new Choices('#driver_client', {allowHTML: true,});
                        }
                        else{
                            $(".driver_carrier_wrap").html(`<label for="basic-url" class="form-label">ФИО <span class="text-danger">*</span></label>
                            <select name="driver_carrier" id="driver_carrier" class="form-select united-select select  select-add-application">
                                <option value="0" disabled selected>Водитель</option>
                                <?php foreach ($listDrivers as $driver){ ?>
                                    <option value="<?php echo $driver['id']; ?>"><?php echo $driver['name']; ?></option>
                                <?php } ?>
                                 <option value="${data['data']['id']}">${data['data']['name']}</option>
                             </select>`);

                            ajaxGetDriver(driver_id, true);

                            console.log(data['data']['id']);
                            document.getElementById('driver_carrier').value = data['data']['id'];

                            choicesDriverCarrier = new Choices('#driver_carrier', {allowHTML: true,});

                            // добавление водителя если вдруг что-то ломается первым делом отключить этот блок - старт
                            $(".driver_client_wrap").html(`<label for="basic-url" class="form-label">ФИО <span class="text-danger">*</span></label>
                              <select name="driver_client" id="driver_client" class="form-select united-select select  select-add-application">
                                    <option value="0" disabled selected>Водитель</option>
                                    <?php foreach ($listDrivers as $driver){ ?>
                                        <option value="<?php echo $driver['id']; ?>"><?php echo $driver['name']; ?></option>
                                    <?php } ?>
                                 <option value="${data['data']['id']}">${data['data']['name']}</option>
                             </select>`);

                            driveHTML = `<label for="basic-url" class="form-label">ФИО <span class="text-danger">*</span></label>
                              <select name="driver_client" id="driver_client" class="form-select united-select select  select-add-application">
                                    <option value="0" disabled selected>Водитель</option>
                                    <?php foreach ($listDrivers as $driver){ ?>
                                        <option value="<?php echo $driver['id']; ?>"><?php echo $driver['name']; ?></option>
                                    <?php } ?>
                                 <option value="${data['data']['id']}">${data['data']['name']}</option>
                             </select>`;

                            document.getElementById('driver_client').value = data['data']['id'];

                            choicesDriverClient = new Choices('#driver_client', {allowHTML: true,});
                        }
                    }
                    else if(data['status'] === "Error"){
                        alert("Не удалось добавить водителя");
                    }
                    else{
                        alert("Не правильно заполнены поля");
                    }
                }
            });
        });

        $('#btn-add-driver-from').click(function () {
            $('#form-add-driver-from').trigger('submit');
        });

        $('#form-add-client-from').submit(function (e) {
            e.preventDefault();

            let form = $(this).serializeArray();

            $.ajax({
                url: '/application/ajax/add-client',
                method: 'POST',
                data: form,
                success: function (response){
                    console.log(response);

                    let data = JSON.parse(response);
                    console.log(data);
                    if (data['status'] === "Success"){
                        $('#form-add-client-from').trigger('reset');
                        $('#modalAddClient').modal("hide");
                        $(".client_input_wrap").html(`<select name="client" id="client_id_input" class="form-select select-add-application select ">
                                <option value="0" disabled selected>Клиент</option>
                                <?php foreach ($listClients as $client){ ?>
                                    <option value="<?php echo $client["id"]; ?>">
                                    <?php echo $client['name'] .' '  .$client['inn']; ?>
                                    </option>
                                <?php } ?>
                         <option value="${data['data']['id']}">${data['data']['name']}</option>
                     </select>`);

                        let client_id = data['data']['id'];

                        ajaxGetClient(client_id);

                        let sselect_client = document.getElementById('client_id_input');
                        sselect_client.value = data['data']['id'];

                        choicesClient = new Choices('#client_id_input', {allowHTML: true,});
                    }
                    else if(data['status'] === "Error"){
                        if(data['errorText'])
                            alert(data['errorText']);

                        if(data['error']){
                            for(key in data['error']){
                                if(key != 'application_format' && key != 'type_taxation_id'){
                                    $('.form-control[name="' + key + '"]').addClass('error-validate');
                                }
                                else{
                                    $('.form-select[name="' + key + '"]').addClass('error-validate');
                                }
                            }
                        }

                        if(!data['error'] && !data['errorText']){
                            alert('Ошибка при попытке добавить!');
                        }

                    }
                    else{
                        alert("Поля заполнены не правильно !");
                    }
                }
            });
        });

        $('#btn-add-client-from').click(function () {
            $('#form-add-client-from').trigger('submit');
        });

        $('.form__body__client').hide();

        $('.client-tab').click(function (){
            $('.form__body__carrier').hide();
            $('.form__body__client').show();

            $('.menu-item-click').removeClass('active');
            $(".client-tab .menu-item-click").addClass('active');
        });
        $('.carrier-tab').click(function (){
            $('.form__body__client').hide();
            $('.form__body__carrier').show();

            $('.menu-item-click').removeClass('active');
            $(".carrier-tab .menu-item-click").addClass('active');
        });

        setFieldsView();
        let carrier_info_input_html = `<div class="mb-4 carrier_info-input-wrap">
        <label for="" class="mb-1">Телефон, код в АТИ, конт. лицо, почта <span class="text-danger">*</span></label>
        <input type="text" name="carrier_info" id="carrier_info-input" placeholder="8 ()--, код в АТИ, Имя:" class="form-control form-control-solid carrier_info-input" required value="8 ()--, код в АТИ, Имя: ">
    </div>`;

        $("#add_carrier_info_input").click(function (){
            $("#carrier_add_inputs").append(carrier_info_input_html);
            // setTimeout(setFieldsView(), 2000);
            setFieldsView();
        });

        $("#delete_carrier_info_input").click(function (){
            $(".carrier_info-input-wrap:last-child").remove();
        });

        $('.form-add-carrier').submit(function (e) {
            e.preventDefault();

            let form = $(this).serializeArray();
            let form_data = {};

            form.forEach((form_element) => {
                form_data[form_element["name"]] = form_element["value"];
            });

            var carrier_info_inputs = Array.from($(".carrier_info-input"));
            var carrier_info_inputs_values = [];

            carrier_info_inputs.forEach((carrier_info_input)=>{
                carrier_info_inputs_values.push(carrier_info_input.value);
            });

            $.ajax({
                url: '/application/ajax/add-carrier',
                method: 'POST',
                data: {form_data: form_data, info_inputs: carrier_info_inputs_values},
                success: function (data_json){
                    let data = JSON.parse(data_json);

                    console.log(data);

                    if (data['status'] === "Success"){
                        $('.form-add-carrier').trigger('reset');
                        $('#modalCarrier').modal("hide");
                        $(".carrier_input_wrap").html(`<select name="carrier_id" id="carrier_id_input" class="form-select select-add-application  select">
                         <option value="0" disabled selected>Перевозчик</option>
                            <?php foreach ($listCarriers as $carrier){ ?>
                                <option value="<?php echo $carrier['id']; ?>"><?php echo $carrier['name'] .' ' .$carrier['inn']; ?></option>
                                <?php } ?>
                         <option value="${data['data']['id']}">${data['data']['name']}</option>
                     </select>
                     <script>
                         <`+"/script>");

                        let carrier_id = data['data']['id'];

                        ajaxGetCarrier(carrier_id);

                        let sselect = document.getElementById('carrier_id_input');
                        sselect.value = data['data']['id'];

                        choicesCarrier = new Choices('#carrier_id_input', {allowHTML: true,});
                    }
                    else if(data['status'] === "Error"){
                        if(! data['errorText'])
                            alert("Не удалось добавить перевозчика");
                        else
                            alert(data['errorText']);
                    }
                    else{
                        alert("Поля заполнены не правильно !");
                    }
                    // if (response !== "Error"){
                    //     document.location.reload();
                    // }
                }
            });
        });

        $('#btn-add-carrier-from').click(function () {
            $('#form-add-carrier-from.form-add-carrier').trigger('submit');
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

        // $('.form__body__client #collect-marshrut').click(function () {
        //     let fields = ['direction','city','address','date','time','contact','phone','loading','sort'];
        //
        //     collect_marshrut_client = collectData('.form__body__client .marshrut-list__item', fields);
        // });
        //
        // let collect_marshrut_carrier = [];
        // $('.form__body__carrier #collect-marshrut').click(function () {
        //     let fields = ['direction','city','address','date','time','contact','phone','loading','sort'];
        //
        //     collect_marshrut_carrier = collectData('.form__body__carrier .marshrut-list__item', fields);
        // });
        function collectAdditionalProfit(){
            let fields = ['type','sum','type-payment','comment'];

            return collectData('#additional_profit-block_client .additional_profit-item', fields);
        }
        function collect_route(side){
            let fields = ['direction','city','address','date','time','contact','phone','loading_method','sort'];

            if(side === 'client'){
                return collectData('.form__body__client .marshrut-list__item', fields);
            }
            return collectData('.form__body__carrier .marshrut-list__item', fields);
        }

        function collectFines(){
            let fields = ['type-for','sum','description','datetime'];

            return collectData('.fines-item', fields);
        }
        function collectExpenses(side){
            let fields = ['type-for','type-expenses','sum','type-payment','comment','id'];

            if(side === 'client'){
                return collectData('#expenses-carrier-block_client .expenses-item', fields);
            }
            return collectData('#expenses-carrier-block_carrier .expenses-item', fields);
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

        $('body').on('click','.js-delete-fines', function () {
            $('.fines-item').has(this).remove();
        });

        $('#btn-add-fines-to').click(function () {
            $('#form-add-fines-to.form-add-fines').trigger('submit');
        });
        $('#btn-add-fines-from').click(function () {
            $('#form-add-fines-from.form-add-fines').trigger('submit');
        });


        $('.form-add-fines').submit(function (e) {
            e.preventDefault();

            let form = $(this).serializeArray();

            let data = {};

            fineIndex++;

            for(let i = 0; i < form.length; i++){
                data[form[i]['name']] = form[i]['value'];
            }

            let html = `<div class="fines-item fines-item_${fineIndex}" data-type-for="${data['type-for']}" data-sum="${data['sum']}"
                        data-description="${data['description']}" data-datetime="${data['date']}">
                <div class="row">
                    <div class="col">
                        <label for="">Сумма, ₽</label>
                        <span class="fine-sum_${fineIndex}">${data['sum']}</span>
                    </div>
                    <div class="col">
                        <label for="">Описание</label>
                        <span class="fine-description_${fineIndex}">${data['description']}</span>
                    </div>
                    <div class="col">
                        <label for="">Дата оплаты</label>
                        <span class="fine-date_${fineIndex}">${data['date']}</span>
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
                                <li><div class="dropdown-item js-delete-fines">Удалить</div></li>
                                <li><div class="dropdown-item js-edit-fines" onclick="editFine(${fineIndex});">Редактировать</div></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>`;

            if(data['type-for'] == 1)
                $('#fines-to-block').append(html);
            else
                $('#fines-from-block').append(html);

            $('.form-add-fines').trigger('reset');
        });

        // new 20-09-24 start
        $('#add-additional_profit').click(function () {
            $('#form-additional_profit').trigger('submit');
        });


        let additionalProfitId = 0;

        $('#form-additional_profit').submit(function (e) {
            e.preventDefault();
            additionalProfitId = $('.additional_profit-item').length;
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
                        <label for="">Вид доп прибыли</label>
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

            $('#additional_profit-block_client').append(html);
        })
        // new 20-09-24 end
        $('#add-expenses').click(function () {
            $('#form-expenses').trigger('submit');
        });

        let expenseId = 0;

        $('#form-expenses').submit(function (e) {
            e.preventDefault();
            expenseId = $('.expenses-item').length;
            expenseId++;

            let form = $(this).serializeArray();

            let data = {};

            console.log(form)
            for(let i = 0; i < form.length; i++){
                data[form[i]['name']] = form[i]['value'];
            }

            console.log(data);

            let html = `<div class="expenses-item expenses-item_${expenseId}" data-type-for="${data['type_for']}" data-type-expenses="${data['type_expenses']}"
                        data-sum="${data['sum']}" data-type-payment="${data['type_payment']}" data-id="0" data-comment="${data['comment']}">
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

            if (data['type_for'] === '1'){
                $('#expenses-carrier-block_client').append(html);
            }
            else if(data['type_for'] === '0'){
                $('#expenses-carrier-block_carrier').append(html);
            }
        })
        // $('.select-chosen').chosen();
        $('#select-car-brands').change(function () {
            let mark = $(this).val();

            if(mark != 'default')
                $('#mark-input').val(mark)
        });

        $('#select-type-transport').change(function () {
            let type = $(this).val();

            if(type != 'default')
                $('#transport-type-input').val(type);
        });

        $('.form__body__carrier #select-terms-payment').change(function (){
            $.ajax({
                url: '/application/ajax/get-terms-payment',
                method: 'POST',
                data: {id: $(this).val()},
                success: function (response){
                    console.log('test');
                    $('#cost_textarea_carrier').val(response);
                }
            })
        });

        $('.form__body__client #select-terms-payment').change(function (){
            $.ajax({
                url: '/application/ajax/get-terms-payment',
                method: 'POST',
                data: {id: $(this).val()},
                success: function (response){
                    console.log('test2');

                    $('#cost_textarea_client').val(response);
                }
            })
        });

        $('#select-conditions').change(function (){
            $.ajax({
                url: '/application/ajax/get-conditions',
                method: 'POST',
                data: {id: $(this).val()},
                success: function (response){
                    $('#prerequisites').val(response);
                    Editor_prerequisites.setData(response);
                    console.log('test2');
                }
            })
        });

        $('#add-marshrut').click(function () {
            $('#btn-form-add-marshrut').trigger('click');
        });

        $('.marshrut-list').sortable();


        $('#form-add-marshrut').submit(function (e) {
            e.preventDefault();
            let form = $(this).serializeArray();

            console.log(form);
            let side = $('#form-add-marshrut-side').val();

            let direction = 'Куда';
            let method = 'разгрузки';

            if(form[0]["value"] == 1){
                direction = 'Откуда';
                method = "погрузки";
            }
            // gggg

            marshrutIndex++;

            form[3]["value"] = form[3]["value"].replace(/'/g, "&apos;").replace(/"/g, "&quot;");
            // form[3]["value"] = form[3]["value"].replaceAll("'", "\'");

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

            if (side == 'carrier'){
                $('.form__body__carrier #marshrut-list').append(html);
            }
            else if (side == 'client'){
                $('.form__body__client #marshrut-list').append(html);
            }

            $('#form-add-marshrut').trigger('reset');
        })

        function editFine(id){
            $("#fine-edit-id").val(id);

            let fields = ['type-for','sum','description','datetime'];

            let editFineData = collectData('.fines-item_'+id, fields)[0];

            $("#modalEditFine #input-edit-fine-sum").val(editFineData["sum"]);
            $("#modalEditFine #input-edit-fine-date").val(editFineData["datetime"]);
            $("#modalEditFine #input-edit-fine-description").val(editFineData["description"]);

            $("#modalEditFine").modal("show");
        }
        $("#btn-edit-fines").click(function (){
            $(".form-edit-fines").trigger("submit");
        });
        $(".form-edit-fines").submit(function (e) {
            e.preventDefault();
            let form = $(this).serializeArray();
            let form_data = {};

            form.forEach((form_element) => {
                form_data[form_element["name"]] = form_element["value"];
            });

            let fineId = $("#fine-edit-id").val();

            $(".fines-item_"+fineId).data('sum',form_data["sum"]);
            $(".fines-item_"+fineId).data('description',form_data["description"]);
            $(".fines-item_"+fineId).data('datetime',form_data["date"]);

            $(".fine-sum_"+fineId).html(form_data["sum"]);
            $(".fine-description_"+fineId).html(form_data["description"]);
            $(".fine-date_"+fineId).html(form_data["date"]);

            // let fields = ['type-for','sum','description','datetime'];

            // let editFineData = collectData('.fines-item_'+fineId, fields)[0];
            // console.log(form_data);
            // console.log(editFineData);
            $("#modalEditFine").modal("hide");
        });

        function editRoute(id){
            $("#marshrut_id_form-edit-modal").val(id);
            let fields = ['direction','city','address','date','time','contact','phone','loading_method','sort'];

            let editRouteData = collectData('.marshrut-list__item_'+id, fields)[0];

            console.log(editRouteData);

            $("#modalEditMarshrut #select-direction").val(editRouteData["direction"]);
            $("#modalEditMarshrut .direction-input").trigger('change');
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

            // form[3]["value"] = form[3]["value"].replace(/'/g, "&apos;").replace(/"/g, "&quot;");

            $(".marshrut-list__item_"+marshrutId).data('direction',form_data["direction"]);
            $(".marshrut-list__item_"+marshrutId).data('city',form_data["city"]);
            $(".marshrut-list__item_"+marshrutId).data('address',form_data["address"].replace(/'/g, "&apos;").replace(/"/g, "&quot;"));
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

        function editAdditionalProfit(id){
            $("#edit-additional_profit-id").val(id);

            let fields = ['type','sum','type-payment','comment'];

            let editAdditionalProfitData = collectData('.additional_profit-item_'+id, fields)[0];

            console.log(1);

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


        setFieldsView();
    </script>
<?php $controller->view('Components/footer');?>