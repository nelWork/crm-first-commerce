<?php

/**
 * @var App\User\Contoller\Common\HomeController $controller
 * @var App\Model\User\User $user
 */
$controller->view('Components/head');
?>


<?php $controller->view('Components/header'); ?>
<body>
<form action="" method="post">
    <div>
        <label for="">Логин</label>
        <input type="text" name="login" value="<?php echo $user['login']; ?>">
    </div>
    <div>
        <label for="">Email</label>
        <input type="email" name="email" value="<?php echo $user['email']; ?>">
    </div>
    <div>
        <label for="">Имя</label>
        <input type="text" name="name" value="<?php echo $user['name']; ?>">
    </div>
    <div>
        <label for="">Фамилия</label>
        <input type="text" name="surname" value="<?php echo $user['surname']; ?>">
    </div>
    <div>
        <label for="">Отчество</label>
        <input type="text" name="lastname" value="<?php echo $user['lastname']; ?>">
    </div>
    <div>
        <label for="">Телефон</label>
        <input type="text" name="phone" value="<?php echo $user['phone']; ?>">
    </div>
    <div>
        <label for="">Зарплата</label>
        <input type="text" name="salary" value="<?php echo $user['salary']; ?>">
    </div>
    <div>
        <label for="">Процент к зарплате</label>
        <input type="text" name="procent" value="<?php echo $user['procent']; ?>">
    </div>

    <select name="role" id="">
        <option value="1" <?php if($user['role'] == 1) echo 'selected'; ?>>Менеджер</option>
        <option value="2" <?php if($user['role'] == 2) echo 'selected'; ?>>Бухгалтер</option>
        <option value="3" <?php if($user['role'] == 3) echo 'selected'; ?>>Администратор</option>
    </select>

    <input type="submit">

</form>

<form action="/update-password" method="post" >
    <div>
        <label for="">Сбросить пароль</label>
        <input type="text" name="reset-password">
    </div>
    <input type="submit">
</form>

<form action="/upload-avatar" method="post" enctype="multipart/form-data">
    <div>
        <label for="">Загрузите изображение</label>
        <input type="file" name="avatar" accept=".jpg,.png">

    </div>
    <input type="submit">
</form>

</body>