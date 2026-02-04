<?php
/** @var App\User\Contoller\Common\HomeController $controller */
/** @var String $titlePage */
$controller->view('Components/head');
?>
    <body style="min-height: 100vh">


    <main class="container-fluid p-0" style="height: 100vh ;max-height: 100vh">
        <?php $controller->view('Components/header'); ?>
        <div class="row m-0" style="height: calc(100% - 30px);">
            <div class="col-250 p-0" style="height: 100%;">
                <?php $controller->view('Components/sidebar'); ?>
            </div>
            <div class="col" style="height: 100%;max-height: 100%; overflow: auto">

