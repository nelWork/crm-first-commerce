<?php
/**
 * @var App\User\Contoller\Common\HomeController  $titlePage
 */

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/assets/plugins/bootstrap5/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/plugins/bootstrap-icon/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/assets/plugins/jquery-ui/jquery-ui.min.css">
    <link rel="stylesheet" href="/assets/plugins/js-choosen/chosen.min.css">
    <link rel="stylesheet" href="/assets/plugins/air-datepicker/datapicker_libary.css">
    <link rel="stylesheet" href="/assets/plugins/js-choices/choices.min.css">
    <link rel="stylesheet" href="/assets/plugins/dataTable/datatables.min.css">
    <link rel="stylesheet" href="/assets/plugins/webix/codebase/webix.css">
    <link rel="stylesheet" type="text/css" href="/assets/plugins/webix/codebase/spreadsheet.min.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/style.css?110325">


    <script src="/assets/plugins/bootstrap5/dist/js/bootstrap.bundle.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <?php
    if ($_SERVER['REQUEST_URI'] !== '/application/add' AND $_SERVER['REQUEST_URI'] !== '/bonus') {?>
                    <script src="/assets/plugins/dataTable/datatables.min.js"></script>
    <?php }
    ?>
    <script src="/assets/plugins/jquery-ui/jquery-ui.min.js"></script>
    <script src="/assets/plugins/js-choosen/chosen.jquery.min.js"></script>
    <script src="/assets/plugins/air-datepicker/datapicker.js"></script>
    <script src="/assets/plugins/js-choices/choices.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/3.3.4/jquery.inputmask.bundle.min.js"></script>

    <script src="/assets/js/ckeditor.js"></script>
    <script src="/assets/js/functions.js"></script>
    <script src="/assets/plugins/webix/codebase/webix.js" type="text/javascript"></script>
    <script src="/assets/plugins/webix/codebase/spreadsheet.js" type="text/javascript"></script>


    <title><?php echo $titlePage; ?></title>
</head>











