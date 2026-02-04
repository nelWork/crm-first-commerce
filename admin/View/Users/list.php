<?php
/** @var App\User\Contoller\Common\HomeController $controller */
/** @var String $titlePage */
/** @var String $countElement */
/** @var array $listRoles */
?>

<?php $controller->view('Components/start'); ?>
<div class="p-4">
    <div class="d-flex align-items-center">
        <h4 class="me-2 mb-0">Пользователи</h4>
        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addCarBrandModal">Добавить пользователя</button>
    </div>
    <p>Всего: <span id="count"></span></p>
    <button class="btn btn-outline-danger btn-sm mb-4 d-none" id="delete" data-name="users">Удалить выбранные</button>

    <table class="table table-hover table-bordered">
        <thead>
        <th>Имя пользователя</th>
        <th>Имя</th>
        <th>Email</th>
        <th>Роль</th>
        <th style="width: 200px;"></th>
        </thead>
        <tbody id="tbody">

        </tbody>
    </table>
</div>

<div class="modal fade" id="addCarBrandModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 50%">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Добавление пользователя</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
            </div>
            <div class="modal-body">
                <form action="" method="post" id="form-add-user" data-name="users">
                    <div class="row">
                        <div class="col">
                            <div class="mb-4">
                                <label for="" class="mb-2">Логин <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="login" required>
                            </div>
                            <div class="mb-4">
                                <label for="" class="mb-2">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" name="email" required>
                            </div>
                            <div class="mb-4">
                                <label for="" class="mb-2">Имя <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="name" required>
                            </div>
                            <div class="mb-4">
                                <label for="" class="mb-2">Фамилия <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="surname" required>
                            </div>
                        </div>
                        <div class="col">
                            <div class="mb-4">
                                <label for="" class="mb-2">Отчество</label>
                                <input type="text" class="form-control" name="lastname">
                            </div>
                            <div class="mb-4">
                                <label for="" class="mb-2">Телефон <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="phone" required>
                            </div>
                            <div class="mb-4">
                                <label for="" class="mb-2">Зарплата</label>
                                <input type="text" class="form-control" name="salary">
                            </div>
                            <div class="mb-4">
                                <label for="" class="mb-2">Процент</label>
                                <input type="text" class="form-control" name="procent">
                            </div>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="" class="mb-2">Роль</label>
                        <select name="role" id="" class="form-select">
                            <?php foreach ($listRoles as $role): ?>
                                <option value="<?php echo $role['id']; ?>"><?php echo $role['name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="" class="mb-2">Пароль <span class="text-danger">*</span></label>
                        <button class="btn btn-outline-primary btn-sm" id="btn-create-password" type="button">Создать</button>
                        <input type="text" class="form-control" name="password" id="user-password" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer d-flex justify-content-between">
                <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Отменить</button>
                <button type="button" class="btn btn-outline-success btn-sm" id="btn-submit-add-form-user">Добавить</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
     aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 50%">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="editModalLabel">Редактирование пользователя</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
            </div>
            <div class="modal-body">
                <form action="" method="post" id="form-edit-modal" data-name="users">
                    <input type="hidden" name="id" id="idEdit">
                    <div class="row">
                        <div class="col">
                            <div class="mb-4">
                                <label for="" class="mb-2">Логин <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="login" id="loginEdit" required>
                            </div>
                            <div class="mb-4">
                                <label for="" class="mb-2">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" name="email" id="emailEdit" required>
                            </div>
                            <div class="mb-4">
                                <label for="" class="mb-2">Имя <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="name" id="nameEdit" required>
                            </div>
                            <div class="mb-4">
                                <label for="" class="mb-2">Фамилия <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="surname" id="surnameEdit" required>
                            </div>
                        </div>
                        <div class="col">
                            <div class="mb-4">
                                <label for="" class="mb-2">Отчество</label>
                                <input type="text" class="form-control" name="lastname" id="lastnameEdit">
                            </div>
                            <div class="mb-4">
                                <label for="" class="mb-2">Телефон <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="phone" id="phoneEdit" required>
                            </div>
                            <div class="mb-4">
                                <label for="" class="mb-2">Зарплата</label>
                                <input type="text" class="form-control" id="salaryEdit" name="salary">
                            </div>
                            <div class="mb-4">
                                <label for="" class="mb-2">Процент</label>
                                <input type="text" class="form-control" id="procentEdit" name="procent">
                            </div>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="" class="mb-2">Роль</label>
                        <select name="role" class="form-select" id="roleEdit">
                            <?php foreach ($listRoles as $role): ?>
                                <option value="<?php echo $role['id']; ?>"><?php echo $role['name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="" class="mb-2">Подчиненные</label>
                        <select name="subordinates[]" class="form-select js-chosen" id="subordinatesEdit" multiple>
                            <option value="">Нет подчиненных</option>
                            <?php foreach ($usersList as $user): ?>
                                <option value="<?php echo $user['id']; ?>"><?php echo $user['surname'] .' ' . $user['name'] .' (' . $user['login'] . ')'; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="" class="mb-2">Аватарка</label>
                        <div class="">
                            <img src="" alt="Аватарка" id="avatar" width="60" height="60" class="mb-2">
<!--                            <form action="" id="form-img" enctype="multipart/form-data" class="d-none">-->
                                <input type="file" name="file" id="input-avatar" accept=".png,.jpg" style="display: none">
<!--                            </form>-->
                            <button class="btn btn-outline-primary btn-sm" id="change-avatar">Изменить</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer d-flex justify-content-between">
                <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Отменить</button>
                <button type="button" class="btn btn-outline-success btn-sm" id="btn-submit-edit-form">Сохранить</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="editPasswordModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
     aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 50%">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="editModalLabel">Сбросить пароль</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
            </div>
            <div class="modal-body">
                <form action="" method="post" id="form-edit-password-modal" data-name="users">
                    <input type="hidden" name="id" id="idEditPassword">
                    <div class="mb-4">
                        Сбросить пароль у пользователя: <span id="name-user-edit-password" style="font-weight: 500"></span>
                    </div>
                    <div class="mb-4">
                        <label for="" class="mb-2">Пароль <span class="text-danger">*</span></label>
                        <input type="text" class="form-control mb-2" name="password" id="user-password-edit" required>
                        <button class="btn btn-outline-primary btn-sm" id="btn-create-password-edit" type="button">Создать</button>
                    </div>
                </form>
            </div>
            <div class="modal-footer d-flex justify-content-between">
                <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Отменить</button>
                <button type="button" class="btn btn-outline-success btn-sm" id="btn-submit-edit-password-form">Сохранить</button>
            </div>
        </div>
    </div>
</div>


<script>
    $('.js-chosen').chosen({
        width: '100%',
        no_results_text: 'Совпадений не найдено',
        placeholder_text_single: 'Выберите сотрудника'
    });
</script>

<script>

    $('#btn-submit-edit-password-form').click(function () {
        $('#form-edit-password-modal').trigger('submit');
    });

    $('#form-edit-password-modal').submit(function (e) {
        e.preventDefault();

        let data = $(this).serializeArray();
        
        $.ajax({
            url: '/admin/ajax/users/reset-password',
            method: 'POST',
            data: data,
            success: function (response) {
                console.log(response)

                let data = JSON.parse(response);

                if(data['result']) {
                    $('.btn-outline-secondary').trigger('click');
                    $('#form-edit-password-modal').trigger('reset');
                }
                else {
                    alert('Ошибка!');
                }
            }
        })

    });
    $('body').on('click' ,'.js-edit-password',function () {
        let nameUser = $(this).data('name-user');
        let idUser = $(this).data('id');
        console.log(nameUser);
        $('#name-user-edit-password').html(nameUser);
        $('#idEditPassword').val(idUser);
    });

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
            url: '/admin/users/ajax/upload-avatar',
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

    updateTable('users');
    $('#btn-create-password').click(function () {
        $('#user-password').val(generatePassword(15));
    });

    $('#btn-create-password-edit').click(function () {
        $('#user-password-edit').val(generatePassword(15));
    });

    $('#btn-submit-add-form-user').click(function () {
        $('#form-add-user').trigger('submit');
    });

    $('#form-add-user').submit(function (e) {
        e.preventDefault();

        let data = $(this).serializeArray();

        console.log(data)

        $.ajax({
            url: '/admin/users/add',
            method: 'POST',
            data: data,
            success: function (response) {
                if(response){
                    updateTable('users');
                }
                else{

                }
            }
        })

    });
</script>

<?php $controller->view('Components/end'); ?>
