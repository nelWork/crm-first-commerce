<?php
/** @var App\User\Contoller\Common\HomeController $controller */
/** @var String $titlePage */
/** @var array $applicationList */
/** @var array $userList */
/** @var array $carrierList */
/** @var array $clientList */
/** @var array $condition */
/** @var array $searchData */
/** @var int $countPage */
/** @var int $page */
/** @var string $link */
$controller->view('Components/head');

//dd($applicationList);

?>
<body>
<?php $controller->view('Components/header'); ?>
<main class="applications">
    <div class="sub-header" style="padding-bottom: 0;">
        <div class="wrapper">
            <?php $controller->view('Components/breadcrumbs'); ?>

            <h3 class="sub-header-title"><?php echo $titlePage; ?></h3>

        </div>
    </div>
    <div class="wrapper">

    </div>
</main>

<script>
    $('.js-chosen').chosen({
        width: '100%',
        no_results_text: 'Совпадений не найдено',
        placeholder_text_single: 'Выберите сотрудника'
    });
</script>

</body>

</html>