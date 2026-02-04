<?php
/**
 * @var App\User\Contoller\Common\HomeController $controller
 */
/** @var App\User\Model\Application\ApplicationPage $application */
/** @var array $carrier */
/** @var array $listManager */

$controller->view('Components/head');

//dd($listContactFaces);
?>

<body>
<?php $controller->view('Components/header'); ?>

<div class="card-application">
    <div class="card-wrapper wrapper">
        <div class="card__header row align-items-center">
            <div class="card__header-title col-10 d-flex align-items-center">
                <?php echo $carrier['name'];?>

            </div>
            <div class="row col-2 justify-content-end align-items-center">
                <div class="settings col-2 p-0 row justify-content-end">
                    <div class="dropdown">
                        <div class="dropdown-toggle dropdown-toggle-without-arrow" data-bs-toggle="dropdown" aria-expanded="false">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path opacity="0.3" d="M17 2H7C4.23858 2 2 4.23858 2 7V17C2 19.7614 4.23858 22 7 22H17C19.7614 22 22 19.7614 22 17V7C22 4.23858 19.7614 2 17 2Z" fill="#B5B5C3"></path>
                                <path d="M13 12C13 11.4477 12.5523 11 12 11C11.4477 11 11 11.4477 11 12C11 12.5523 11.4477 13 12 13C12.5523 13 13 12.5523 13 12Z" fill="#B5B5C3"></path>
                                <path d="M13 16C13 15.4477 12.5523 15 12 15C11.4477 15 11 15.4477 11 16C11 16.5523 11.4477 17 12 17C12.5523 17 13 16.5523 13 16Z" fill="#B5B5C3"></path>
                                <path d="M13 8C13 7.44772 12.5523 7 12 7C11.4477 7 11 7.44772 11 8C11 8.55228 11.4477 9 12 9C12.5523 9 13 8.55228 13 8Z" fill="#B5B5C3"></path>
                            </svg>
                        </div>
                        <ul class="dropdown-menu dropdown-menu-theme">
                            <li><div class="dropdown-item js-edit-fines" data-bs-toggle="modal" data-bs-target="#modalCarrierEdit">Редактировать</div></li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>

        <div class="card__content d-flex">
            <div class="card__content-main d-flex flex-column">

                <div class="card__content-main__header  d-flex">

                    <div class="form__header-menu-list d-flex w-100">
<!--                        <a class="form__header-menu-item">-->
<!--                            <div class="menu-item-click active js-link-tab-change main-header-js-link-tab-change"-->
<!--                                 data-container=".card__content-tab" data-tab-app="#tab-1" data-prefix-tab=".main-header">-->
<!--                                Контактные лица-->
<!--                            </div>-->
<!--                        </a>-->

<!--                        <a class="mr-6 form__header-menu-item ">-->
<!--                            <div class="menu-item-click js-link-tab-change main-header-js-link-tab-change"-->
<!--                                 data-container=".card__content-tab"  data-tab-app="#tab-2" data-prefix-tab=".main-header">-->
<!--                                Документы-->
<!--                            </div>-->
<!--                        </a>-->

<!--                        <a class="mr-6 form__header-menu-item  ">-->
<!--                            <div class="menu-item-click js-link-tab-change main-header-js-link-tab-change"-->
<!--                                 data-container=".card__content-tab" data-tab-app="#tab-3" data-prefix-tab=".main-header">-->
<!--                                Бухгалтера-->
<!--                            </div>-->
<!--                        </a>-->
<!---->
<!--                        <a class="mr-6 form__header-menu-item ">-->
<!--                            <div class="menu-item-click js-link-tab-change main-header-js-link-tab-change"-->
<!--                                 data-container=".card__content-tab" data-tab-app="#tab-4" data-prefix-tab=".main-header">-->
<!--                                Отдела качества-->
<!--                            </div>-->
<!--                        </a>-->
<!---->
<!--                        <a class="mr-6 form__header-menu-item ">-->
<!--                            <div class="menu-item-click js-link-tab-change main-header-js-link-tab-change"-->
<!--                                 data-container=".card__content-tab" data-tab-app="#tab-5" data-prefix-tab=".main-header">-->
<!--                                Юристы-->
<!--                            </div>-->
<!--                        </a>-->

                        <a class="mr-6 form__header-menu-item ">
                            <div class="menu-item-click active js-link-tab-change main-header-js-link-tab-change"
                                 data-container=".card__content-tab" data-tab-app="#tab-6" data-prefix-tab=".main-header">
                                Заявки
                            </div>
                        </a>
                    </div>
                </div>
                <div class="card__content-tab" id="tab-1">

                </div>
                <div class="card__content-tab" id="tab-2">

                </div>
                <div class="card__content-tab" id="tab-3">

                </div>
                <div class="card__content-tab" id="tab-4">

                </div>
                <div class="card__content-tab" id="tab-5">

                </div>
                <div class="card__content-tab active" id="tab-6">

                    <?php if($controller->auth->user()->fullCRM()): ?>
                        <a class="btn btn-success" href="/journal-list?carrier-id=<?php echo $carrier['id']; ?>" target="_blank">Открыть в журнале</a>
                    <?php endif; ?>

                    <div class="d-flex mt-3 flex-column">
                        <?php foreach ($listApplications as $application) {?>
                            <div class="application-item" style="font-size: 18px; font-width: 700;">
                                <a href="/application?id=<?php echo $application['id']?>">
                                    <?php echo $application['application_number']." (". date('d.m.Y', strtotime($application['date'])).")"?>
                                </a>
                            </div>
                            <br>
                        <?php }?>
                    </div>
                </div>
            </div>
            <div class="card__content-side col-4">
                <div class="card__content-side-tab active" id="side-tab-1">
                    <div class="side-block-info">
<!--                        <div class="mb-4">-->
<!--                            <span class="side-tab-span">Вид налогообложения</span>-->
<!--                        </div>-->
                        <div class="mb-4">
                            <span class="side-tab-span">ИНН</span>
                            <span class="side-main-span"><?php echo $carrier['inn'];?></span>
                        </div>
                        <div class="mb-4">
                            <span class="side-tab-span">Юридический адрес</span>
                            <span class="side-main-span"><?php echo $carrier['legal_address'];?></span>
                        </div>
                        <div class="mb-4">
                            <span class="side-tab-span">Контактные данные</span>
                            <?php foreach ($listContactFaces as $contact): ?>
                                <div><span class="side-main-span"><?php echo $contact ;?></span></div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

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
                                    <input class="d-none" id="carrier_edit_id" value="<?php echo $carrier['id']; ?>" name="carrier_edit_id">
                                    <label for="" class="mb-1">Название <span class="text-danger">*</span></label>
                                    <input type="text" value="<?php echo $carrier['name']; ?>" name="carrier_name" placeholder="Название" class="form-control form-control-solid" required>
                                </div>
                                <div class="mb-4">
                                    <label for="" class="mb-1">ИНН <span class="text-danger">*</span></label>
                                    <input type="number" value="<?php echo $carrier['inn']; ?>" name="carrier_inn" placeholder="ИНН" class="form-control form-control-solid" required>
                                </div>
                                <div class="mb-4">
                                    <label for="" class="mb-1">Юридический адрес <span class="text-danger">*</span></label>
                                    <textarea name="carrier_legal_address" placeholder="Адрес" class="form-control form-control-solid" required><?php echo $carrier['legal_address']; ?></textarea>
                                </div>
                                <?php foreach ($listContactFaces as $contact):  ?>
                                    <div class="mb-4 carrier-info-input-wrap">
                                        <label for="" class="mb-1">Телефон, код в АТИ, конт. лицо, почта <span class="text-danger">*</span></label>
                                        <input type="text" value="<?php echo $contact; ?>" name="carrier_info" placeholder="8 ()--, код в АТИ"
                                               class="form-control form-control-solid carrier_info-input"  required>
                                    </div>
                                <?php endforeach; ?>
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

<script>
    $(".carrier_info-input").inputmask("8 (999)999-99-99, код в АТИ *{0,50}");

    $("#add_carrier_edit_info_input").click(function (){
        $("#carrier_edit_add_inputs").append(`
            <div class="mb-4 carrier-info-input-wrap">
                <label for="" class="mb-1">Телефон, код в АТИ, конт. лицо, почта <span class="text-danger">*</span></label>
                <input type="text" name="carrier_info" placeholder="8 ()--, код в АТИ" class="form-control form-control-solid carrier_info-input" required value="">
             </div>`);
        $(".carrier_info-input").inputmask("8 (999)999-99-99, код в АТИ *{0,50}");
    });

    $('.js-link-tab-change').click(function () {
        let tabId = $(this).data('tab-app');
        let container = $(this).data('container');
        let prefix = $(this).data('prefix-tab');
        $(prefix + '-js-link-tab-change').removeClass('active');
        $(this).addClass('active');
        $(container).removeClass('active');
        $(tabId).addClass('active');
    });
    $('#btn-add-carrier-from-edit').click(function () {
        $('#form-edit-carrier-from-edit').trigger('submit');
    })
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

        console.log({form_data: form_data, info_inputs: carrier_info_inputs_values})

        $.ajax({
            url: '/application/ajax/edit-carrier',
            method: 'POST',
            data: {form_data: form_data, info_inputs: carrier_info_inputs_values},
            success: function (response){
                console.log(response);
                // if (response !== "Error"){
                //     document.location.reload();
                //
                // }

            }
        });
    });
</script>
</body>
</html>