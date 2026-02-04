<?php
/**
 * @var App\User\Contoller\Common\HomeController  $controller
 */

$controller->view('Components/header');

?>


<body>
    <h2>Добавление пользователя</h2>
    <form action="/add-user" method="post">
        <div>
            <label for="">Логин</label>
            <input type="text" name="login">
        </div>
        <div>
            <label for="">Email</label>
            <input type="email" name="email">
        </div>
        <div>
            <label for="">Роль</label>
            <select name="role" id="">
                <option value="1">Менеджер</option>
                <option value="2">Бухгалтер</option>
                <option value="3">Администратор</option>
            </select>
        </div>
        <div>
            <label for="">Пароль</label>
            <input type="password" name="password">
        </div>

        <input type="submit">
    </form>

</body>
