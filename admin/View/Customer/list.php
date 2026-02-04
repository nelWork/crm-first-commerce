<?php
/** @var App\User\Contoller\Common\HomeController $controller */
/** @var String $titlePage */
/** @var String $countElement */
/** @var array $listCarBrands */
?>

<?php $controller->view('Components/start'); ?>
<div class="p-4">
    <div class="d-flex align-items-center">
        <h4 class="me-2 mb-0"><?php echo $titlePage; ?></h4>
        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addCarBrandModal">Добавить запись</button>
    </div>
    <p>Всего: <span id="count"></span></p>
    <button class="btn btn-outline-danger d-none btn-sm mb-4" id="delete" data-name="customer">Удалить выбранные</button>

    <table class="table table-hover table-bordered">
        <thead>
        <th style="width: 10px;">
            <input type="checkbox" class="form-check-input" id="check-all-checkbox">
        </th>
        <th>Заголовок</th>
        <th style="width: 200px;"></th>
        </thead>
        <tbody id="tbody">

        </tbody>
    </table>
</div>

<div class="modal fade" id="addCarBrandModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addCarBrandModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 60%">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="addCarBrandModalLabel">Добавление юр. лица</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
            </div>
            <div class="modal-body">
                <form action="" method="post" id="form-add" data-name="customer">
                    <div class="row">
                        <div class="col">
                            <div class="mb-4">
                                <label for="" class="mb-2">Название <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="name" required>
                            </div>
                            <div class="mb-4">
                                <label for="" class="mb-2">ИНН <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="inn" required>
                            </div>
                            <div class="mb-4">
                                <label for="" class="mb-2">Почтовый адрес <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="mailing_address" required>
                            </div>

                        </div>
                        <div class="col">
                            <div class="mb-4">
                                <label for="" class="mb-2">Контактное лицо <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="contact_face" required>
                            </div>
                            <div class="mb-4">
                                <label for="" class="mb-2">Инициалы <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="initials" required>
                            </div>
                            <div class="mb-4">
                                <label for="" class="mb-2">Телефон, факс <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="phone" required>
                            </div>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="" class="mb-2">Юридический адрес <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="legal_address" required> </textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer d-flex justify-content-between">
                <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Отменить</button>
                <button type="button" class="btn btn-outline-success btn-sm" id="btn-submit-add-form">Добавить</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
     aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 60%">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="editModalLabel">Редактирование юр. лицо</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
            </div>
            <div class="modal-body">
                <form action="" method="post" id="form-edit-modal" data-name="customer">
                    <input type="hidden" name="id" id="idEdit">
                    <div class="row">
                        <div class="col">
                            <div class="mb-4">
                                <label for="" class="mb-2">Название <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="name" id="nameEdit" required>
                            </div>
                            <div class="mb-4">
                                <label for="" class="mb-2">ИНН <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="inn" id="innEdit" required>
                            </div>
                            <div class="mb-4">
                                <label for="" class="mb-2">Почтовый адрес <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="mailing_address" id="mailingAddressEdit" required>
                            </div>

                        </div>
                        <div class="col">
                            <div class="mb-4">
                                <label for="" class="mb-2">Контактное лицо <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="contact_face" id="contactFaceEdit" required>
                            </div>
                            <div class="mb-4">
                                <label for="" class="mb-2">Инициалы <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="initials" id="initialsEdit" required>
                            </div>
                            <div class="mb-4">
                                <label for="" class="mb-2">Телефон, факс <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="phone" id="phoneEdit" required>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="" class="mb-2">Юридический адрес <span class="text-danger">*</span></label>
                        <textarea type="text" class="form-control" name="legal_address" id="legalAddressEdit" required></textarea>
                    </div>
                    <div class="row" >
                        <div class="col ">
                            <label for="" class="mb-2">Печать</label>
                            <div class="mb-2">
                                <img src="" alt="" id="img-seal-customer-edit" width="100" height="100">
                            </div>
                            <button class="btn btn-outline-primary btn-sm">Изменить</button>
                        </div>
                        <div class="col">
                            <label for="" class="mb-2">Подпись</label>
                            <div class="mb-2">
                                <img src="" alt="" id="img-signature-customer-edit" width="100" height="100">
                            </div>
                            <button class="btn btn-outline-primary btn-sm">Изменить</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer d-flex justify-content-between">
                <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Отменить</button>
                <button type="button" class="btn btn-outline-success btn-sm" id="btn-submit-edit-form">Сохранить</button>
            </div>
        </div>
    </div>
</div>

<script>
    updateTable('customer');
</script>

<?php $controller->view('Components/end'); ?>
