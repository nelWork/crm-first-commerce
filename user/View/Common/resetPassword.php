<?php
/**
 * @var App\User\Contoller\Common\HomeController $controller
 * @var App\Session\SessionInterface $session
 */

$controller->view('Components/head');


?>
<style>
    body,html{
        font-size: 16px!important;
    }
</style>
<body class="d-flex align-items-center py-4 bg-body-tertiary">
<?php
    if($session->getFlash('success-reset-password')){
        echo "<script>alert(`Проверьте вашу почту`)</script>";
    }
?>
<main class="form-signin w-100 m-auto">
    <form action="" method="post">
        <h3 class="mb-4 text-center">PEGAS.CRM</h3>
        <?php
        if($session->has('error-reset-password')){
            echo "<div class='alert alert-danger mb-3'>" .$session->getFlash('error-reset-password') ."</div>";
        }
        ?>
        <div class="form-floating mb-3">
            <input type="text" id="floatingLogin" name="email" class="form-control" placeholder="Введите ваш Email"
                   value="<?php if($session->has('reset-password-email')) echo $session->getFlash('reset-password-email'); ?>"
                   required>
            <label for="floatingLogin">Введите ваш Email</label>
        </div>
        <button class="btn btn-success w-100 mb-2">Сбросить пароль</button>
        <a href="/login" class="btn btn-primary w-100">Назад</a>
    </form>
</main>
</body>
</html>

