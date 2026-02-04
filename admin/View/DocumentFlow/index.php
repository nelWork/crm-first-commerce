<?php
/** @var App\User\Contoller\Common\HomeController $controller */
/** @var String $titlePage */
/** @var String $countElement */
/** @var array $documentFlow */

?>

<?php $controller->view('Components/start'); ?>
<div class="p-4">
    <div class="d-flex align-items-center">
        <h4 class="me-2 mb-5"><?php echo $titlePage; ?></h4>
    </div>

    <form action="" method="POST" class="w-25">
        <div class="mb-4">
            <label for="" class="mb-2">Номер заявки</label>
            <input type="number" name="application-num" class="form-control"
                   value="<?php echo $documentFlow['application_num'];?>" min="0" step="1">
        </div>
        <div class="mb-4">
            <label for="" class="mb-2">Номер доверенности на водителя</label>
            <input type="number" class="form-control" name="attorney-driver-num"
                   value="<?php echo $documentFlow['attorney_driver_num'];?>" min="0" step="1">
        </div>
        <div class="mb-4">
            <label for="" class="mb-2">Номер экспедиторской расписки</label>
            <input type="number" class="form-control" name="forwarding-receipt-num"
                   value="<?php echo $documentFlow['forwarding_receipt_num'];?>" min="0" step="1">
        </div>

        <button class="btn btn-success">Сохранить</button>
    </form>

</div>

<script>

</script>

<?php $controller->view('Components/end'); ?>
