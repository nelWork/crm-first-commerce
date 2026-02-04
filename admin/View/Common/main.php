<?php
/** @var App\User\Contoller\Common\HomeController $controller */
/** @var String $titlePage */
$controller->view('Components/head');
?>
<body style="min-height: 100vh">


<main class="container-fluid p-0" style="height: 100vh ;max-height: 100vh">
    <?php $controller->view('Components/header'); ?>
    <div class="row">
        <div class="col">
            <?php $controller->view('Components/sidebar'); ?>
        </div>
    </div>
</main>


</body>

</html>