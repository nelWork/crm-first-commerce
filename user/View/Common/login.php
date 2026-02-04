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

    <main class="form-signin w-100 m-auto">
        <form action="/login/user" method="post">
            <h3 class="mb-3 text-center">CRM</h3>
            <?php
            if($session->has('error')){
                echo "<div class='alert alert-danger mb-3'>" .$session->getFlash('error') ."</div>";
            }
            ?>
            <?php
                if($session->has('login-success')){
                    echo "<div class='alert alert-success mb-3'>" .$session->getFlash('login-success') ."</div>";
                }
            ?>
            <div class="form-floating mb-3">
                <input type="text" id="floatingLogin" name="login" class="form-control" placeholder="Имя пользователя"
                       value="<?php if($session->has('login')) echo $session->getFlash('login'); ?>"
                       required>
                <label for="floatingLogin">Имя пользователя</label>
            </div>
            <div class="form-floating mb-3">
                <input type="password" id="floatingPassword" class="form-control" placeholder="Пароль" name="password" required>
                <label for="floatingPassword">Пароль</label>
            </div>
<!--            <div class="form-check text-start mt-3">-->
<!--                <input class="form-check-input" type="checkbox" value="remember-me" id="flexCheckDefault">-->
<!--                <label class="form-check-label" for="flexCheckDefault">-->
<!--                    Запомнить меня-->
<!--                </label>-->
<!--            </div>-->
            <div class="mb-3">
                <a href="/reset-password">Забыли пароль?</a>
            </div>
            <button class="btn btn-primary w-100">Войти</button>
        </form>
    </main>
</body>
</html>

