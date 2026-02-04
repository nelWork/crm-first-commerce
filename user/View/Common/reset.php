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
    <form action="/reset/password" method="post">
        <h3 class="mb-4 text-center">PEGAS.CRM</h3>
        <?php
        if($session->has('error-reset-repeat-password')){
            echo "<div class='alert alert-danger mb-3'>" .$session->getFlash('error-reset-repeat-password') ."</div>";
        }
        ?>
        <div class="form-floating mb-3">
            <input type="password" id="floatingPassword" name="password" class="form-control"
                   placeholder="Введите новый пароль"
                   required>
            <label for="floatingPassword">Введите новый пароль</label>
        </div>

        <div class="form-floating mb-3">
            <input type="password" id="floatingPasswordRepeat" name="password-repeat" class="form-control"
                   placeholder="Повторите новый пароль"
                   required>
            <label for="floatingPasswordRepeat">Повторите новый пароль</label>
        </div>
        <button class="btn btn-primary w-100">Сохранить пароль</button>
    </form>
</main>
</body>
</html>

