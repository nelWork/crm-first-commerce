<?php
/** @var App\User\Contoller\Common\HomeController $controller */
/** @var String $titlePage */
$controller->view('Components/head');
?>

<body>
<?php $controller->view('Components/header'); ?>

<main class="analytics">
    <div class="sub-header" style="padding-bottom: 0;">
        <div class="wrapper">
            <?php $controller->view('Components/breadcrumbs'); ?>

            <h3 class="sub-header-title"><?php echo $titlePage; ?></h3>

            <div class="row" style="margin-top: 40px;">
                <div class="col-8">
                    <ul class="nav nav-underline nav-subheader">
                        <?php if(in_array($controller->auth->user()->id(),[1,20,25,22,18,5])): ?>
                            <li class="nav-item">
                                <a href="/analytics" class="nav-link active">Статистика</a>
                            </li>
                        <?php endif; ?>
                        <?php if(in_array($controller->auth->user()->id(),[1,20,25,22,18,5])): ?>
                            <li class="nav-item">
                                <a href="/analytics/declaration" class="nav-link ">Ведомость</a>
                            </li>
                        <?php endif; ?>
                        <li class="nav-item">
                            <a href="/analytics/salary" class="nav-link">Зарплата</a>
                        </li>
                        <?php if($controller->auth->user()->fullCRM()): ?>
                            <li class="nav-item">
                                <a href="/analytics/managers" class="nav-link">Менеджеры</a>
                            </li>
                        <?php endif; ?>
                        <li class="nav-item">
                            <a href="/analytics/managers" class="nav-link">Менеджеры</a>
                        </li>
<!--                        <li class="nav-item">-->
<!--                            <a href="/analytics/applications" class="nav-link">Заявки</a>-->
<!--                        </li>-->
                    </ul>
                </div>
                <div class="col">
                    <div class="d-flex justify-content-end">

                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="analytics-applications__list mb-5">
        <div class="wrapper p-2 mb-5">

        </div>
    </section>
</main>


</body>
