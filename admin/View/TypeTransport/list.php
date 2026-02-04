<?php
/** @var App\User\Contoller\Common\HomeController $controller */
/** @var String $titlePage */
/** @var String $countElement */
/** @var array $listCarBrands */
?>

<?php $controller->view('Components/start'); ?>
<div class="p-4">
    <div class="d-flex align-items-center">
        <h4 class="me-2 mb-0">Тип транспорта</h4>
        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addCarBrandModal">Добавить запись</button>
    </div>
    <p>Всего: <span id="count"></span></p>
    <button class="btn btn-outline-danger btn-sm mb-4" id="delete" data-name="type-transport">Удалить выбранные</button>

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
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="addCarBrandModalLabel">Добавление типа транспорта</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
            </div>
            <div class="modal-body">
                <form action="" method="post" id="form-add" data-name="type-transport">
                    <label for="" class="mb-2">Название</label>
                    <input type="text" class="form-control" name="name" required>
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
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="editModalLabel">Редактирование типа транспорта</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
            </div>
            <div class="modal-body">
                <form action="" method="post" id="form-edit-modal" data-name="type-transport">
                    <input type="hidden" name="id" id="idEdit">
                    <label for="" class="mb-2">Название</label>
                    <input type="text" class="form-control" name="name" id="nameEdit" required>
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
    updateTable('type-transport');
</script>

<?php $controller->view('Components/end'); ?>
