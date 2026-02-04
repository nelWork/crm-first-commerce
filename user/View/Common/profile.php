<?php

/**
 * @var App\User\Contoller\Common\HomeController $controller
 * @var App\Model\User\User $user
 */
$controller->view('Components/head');
?>


<body>
<?php $controller->view('Components/header'); ?>
<?php $controller->view('Components/subheader'); ?>

<div class="wrapper profile-wrapper">
    <div class="row m-0">
        <div class="col-7">
            <div class="card-white-block">
                <h4 class="mb-3">Данные</h4>
                <div class="profile-change-avatar-block">

                    <img src="<?php echo $user['img_avatar']; ?>" alt="Аватарка" id="avatar" width="100" height="100" class="mb-2">
                    <input type="file" name="file" id="input-avatar" accept=".png,.jpg" style="display: none">
                    <button class="btn btn-primary" id="change-avatar">Изменить</button>
                </div>
                <form action="" method="post">
                    <div class="row mb-2">
                        <div class="col-6 mb-4">
                            <label for="" class="mb-2">Имя <span class="text-danger fw-bold">*</span></label>
                            <input type="text" class="form-control form-control-solid" name="name" value="<?php echo $user['name']; ?>">
                        </div>
                        <div class="col-6 mb-4">
                            <label for="" class="mb-2">Фамилия <span class="text-danger fw-bold">*</span></label>
                            <input type="text" class="form-control form-control-solid" name="surname" value="<?php echo $user['surname']; ?>">
                        </div>
                        <div class="col-6 mb-4">
                            <label for="" class="mb-2">Отчество <span class="text-danger fw-bold">*</span></label>
                            <input type="text" class="form-control form-control-solid" name="lastname" value="<?php echo $user['lastname']; ?>">
                        </div>
                        <div class="col-6 mb-4">
                            <label for="" class="mb-2">Email <span class="text-danger fw-bold">*</span></label>
                            <input type="email" class="form-control form-control-solid" name="email" value="<?php echo $user['email']; ?>">
                        </div>
                        <div class="col-6 mb-4">
                            <label for="" class="mb-2">Телефон <span class="text-danger fw-bold">*</span></label>
                            <input type="text" class="form-control form-control-solid" name="phone" value="<?php echo $user['phone']; ?>">
                        </div>
                    </div>
                    <div class="w-100 d-flex justify-content-between">
                        <button class="btn btn-add-light">Сохранить</button>
                        <a href="/logout" class="btn btn-logout">Выйти</a>
                    </div>
                </form>
            </div>
        </div>
        <div class="col">
            <div class="card-white-block">
                <h4 class="mb-3">Пароль</h4>
                <form action="/update-password" method="post" >

                    <div class="mb-2">
                        <label for="">Старый пароль</label>
                        <input type="password" name="reset-password" class="form-control form-control-solid">
                    </div>
                    <div class="mb-2">
                        <label for="">Новый пароль</label>
                        <input type="password" name="reset-password" class="form-control form-control-solid">
                    </div>
                    <div class="mb-3">
                        <label for="">Повторите новый пароль</label>
                        <input type="password" name="reset-password" class="form-control form-control-solid">
                    </div>
                    <button class="btn btn-primary">Изменить</button>
            </form>
            </div>
        </div>
    </div>

    <script>
        $('#change-avatar').click(function () {
            $('#input-avatar').trigger('click');
        });

        $('#input-avatar').change(function () {
            console.log($('#input-avatar')[0].files[0])
            var formData = new FormData();
            formData.append('file', $("#input-avatar")[0].files[0]);
            formData.append('id', $('#idEdit').val());

            $.ajax({
                type: 'POST',
                url: '/profile/ajax/upload-avatar',
                contentType: false,
                processData: false,
                data: formData,
                success: function(response){
                    console.log(response);
                    let data = JSON.parse(response);
                    $('#avatar').attr('src', data['avatar']);
                    $('#input-avatar').val('');
                }
            });
        });
    </script>

</div>
</body>