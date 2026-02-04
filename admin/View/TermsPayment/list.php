<?php
/** @var App\User\Contoller\Common\HomeController $controller */
/** @var String $titlePage */
/** @var String $countElement */
/** @var array $listCarBrands */

?>

<?php $controller->view('Components/start'); ?>
<div class="p-4">
    <div class="d-flex align-items-center">
        <h4 class="me-2 mb-0">Условия оплаты</h4>
        <a class="btn btn-sm btn-outline-primary" href="/admin/terms-of-payment/add" target="_blank">Добавить запись</a>
    </div>
    <p>Всего: <span id="count"></span></p>
    <button class="btn btn-outline-danger btn-sm mb-4" id="delete" data-name="terms-of-payment">Удалить выбранные</button>

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


<script>
    updateTable('terms-of-payment', true);
</script>

<script src="https://cdn.ckeditor.com/ckeditor5/37.1.0/super-build/ckeditor.js"></script>

<?php $controller->view('Components/end'); ?>
