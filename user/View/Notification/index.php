<?php
/**
 * @var App\User\Contoller\Common\HomeController $controller
 */

$controller->view('Components/head');

//dd($notificationsList);
?>

<body>
<?php $controller->view('Components/header'); ?>

<div class="card-application">
    <div class="card-wrapper wrapper">
        <div class="list-notifications p-4">
            <?php foreach ($notificationsList as $notification): ?>
                <div class="item">
                    <div class="header-notification">
                        <?php echo $notification['name']; ?>
                        Заявка № <?php echo $notification['application_number'];  ?>
                        <small><?php echo date('d.m.Y', strtotime($notification['date'])); ?></small></div>
                    <div class="text-notification"><?php echo $notification['text']; ?></div>
                    <button class="btn btn-primary js-set-complete-status-notification" data-id-notification="<?php echo $notification['id']; ?>">Выполнено</button>
                    <div class="from-notification">От <?php echo $notification['id_from_user']; ?></div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

</body>
</html>