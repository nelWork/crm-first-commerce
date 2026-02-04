<?php
/** @var App\User\Contoller\Common\HomeController $controller */
/** @var String $titlePage */
/** @var double $countPage */
/** @var int $page */
/** @var String $link */
/** @var array $listCarriers */
/** @var array $searchData */
$controller->view('Components/head');

?>

<body>
<?php $controller->view('Components/header'); ?>

<main class="base">
    <div class="sub-header" style="padding-bottom: 0;">
        <div class="wrapper">
            <?php $controller->view('Components/breadcrumbs'); ?>

            <h3 class="sub-header-title"><?php echo $titlePage; ?></h3>

            <div class="row" style="margin-top: 40px;">
                <div class="col-8">
                    <ul class="nav nav-underline nav-subheader">
                        <li class="nav-item">
                            <a href="/base/clients" class="nav-link">База клиентов</a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">База перевозчиков</a>
                        </li>
                        <li class="nav-item">
                            <a href="/base/loaders" class="nav-link active">База грузчиков</a>
                        </li>
                        <li class="nav-item">
                            <a href="/base/call-bases" class="nav-link">База обзвонов</a>
                        </li>
                    </ul>

                </div>
                <div class="col">
                    <div class="d-flex justify-content-end">
                        <div class="dropdown ">
                            <button class="btn btn-theme-white dropdown-toggle dropdown-toggle-without-arrow" type="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-sort-alpha-down" style="font-size: 16px"></i> По умолчанию
                            </button>
                            <ul class="dropdown-menu dropdown-menu-theme">
                                <li><a href="/base/carriers" class="dropdown-item">По умолчанию</a></li>
                                <li><a href="?order=DESC" class="dropdown-item">Сначала новые</a></li>
                                <li><a href="?order=ASC" class="dropdown-item">Сначала старые</a></li>
                            </ul>
                        </div>
                        <button class="btn btn-theme-white mx-3" type="button" data-bs-toggle="modal" data-bs-target="#filterModal">
                            <i class="bi bi-funnel-fill" ></i> Фильтры
                        </button>
                        <button class="btn btn-theme-success" type="button" data-bs-toggle="modal" data-bs-target="#modalCarrier">
                            <i class="bi bi-plus" style="font-size: 16px"></i> Добавить
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <section class="base-clients__list carrier">
        <div class="wrapper bg-whitesmoke">
            <div class="search search-page-block">
                <form action="" class="d-flex">
                    <select name="search-select" id="" class="form-select">
                        <option value="all">Все</option>
                        <option value="name" <?php if($searchData['search-select'] == 'name') echo 'name'; ?>>Название</option>
                        <option value="inn" <?php if($searchData['search-select'] == 'inn') echo 'inn'; ?>>ИНН</option>
                        <option value="phone" <?php if($searchData['search-select'] == 'phone') echo 'selected'; ?>>Телефон</option>
                        <option value="email" <?php if($searchData['search-select'] == 'email') echo 'selected'; ?>>Email</option>

                    </select>

                    <input type="text" class="form-control" name="search" placeholder="Поиск..." value="<?php echo $searchData['search']; ?>">

                    <a class="btn btn-danger btn-cancel" href="/base/carriers">Сбросить</a>
                </form>
            </div>
        </div>

        <div class="wrapper">

            <div class="post-list__header d-flex">
                <div class="post-list__header-item">НАЗВАНИЕ ОРГАНИЗАЦИИ</div>
                <div class="post-list__header-item">ИНН</div>
                <div class="post-list__header-item">КОНТАКТЫ</div>
            </div>

            <?php foreach($listCarriers as $carrier) { ?>
                <div class="post-list__items">
                    <a href="/carrier?id=<?php echo $carrier['id']?>" class="post-list__item item d-flex">
                        <div class="w-100 d-flex">
                            <div class="item__title"><?php echo $carrier['name']?></div>
                            <div class="item__inn"><?php echo $carrier['inn']?></div>
                            <div class="item__fio">
                                <?php $info = explode('||', $carrier['info']);?>
                                <?php foreach ($info as $item): ?>
                                    <div class=""><?php echo $item; ?></div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </a>
                </div>
            <?php }?>


            <?php $controller->view('Components/pagination'); ?>

        </div>
    </section>

</main>
<div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="margin-right: 50px">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="filterModalLabel">Параметры фильтрации</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
            </div>
            <div class="modal-body">
                <form action="" style="overflow: auto; height: 75vh;max-height: 75vh" id="form-filter-client">
                    <div class="mb-4">
                        <label for="" class="label-theme">Выберите период</label>
                        <input type="text" class="form-control form-control-theme form-control-solid">
                    </div>

                    <div class="mb-4">
                        <label for="" class="label-theme">Вид налогообложения клиента:</label>
                        <select name="taxation" id="" class="form-select" >
                            <option value="null">По умолчанию</option>
                            <option value="С НДС">С НДС</option>
                            <option value="Б/НДС">Б/НДС</option>
                            <option value="НАЛ">НАЛ</option>
                        </select>
                    </div>

                    <select name="search-select" id="" class="d-none">
                        <option value="all">Все</option>
                        <option value="name" <?php if($searchData['search-select'] == 'name') echo 'name'; ?>>Название</option>
                        <option value="inn" <?php if($searchData['search-select'] == 'inn') echo 'inn'; ?>>ИНН</option>
                        <option value="ati" <?php if($searchData['search-select'] == 'ati') echo 'inn'; ?>>Код в АТИ</option>
                        <option value="phone" <?php if($searchData['search-select'] == 'phone') echo 'selected'; ?>>Телефон</option>
                        <option value="email" <?php if($searchData['search-select'] == 'email') echo 'selected'; ?>>Email</option>
                    </select>
                    <input type="hidden" class="form-control" name="search" placeholder="Поиск..." value="<?php echo $searchData['search']; ?>">
                </form>
            </div>
            <div class="modal-footer">
                <div class="d-flex w-100 justify-content-between">
                    <button type="button" class="btn btn-theme btn-success" id="btn-target-form-filter-client">Применить</button>
                    <a type="button" class="btn btn-theme btn-light" href="/base/clients">Сбросить</a>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal modal-add-application fade" id="modalCarrier" tabindex="-1" aria-labelledby="modalCarrier" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title title-base" id="exampleModalLabel">Добавление перевозчика</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
            </div>
            <div class="modal-body">
                <form action="application/add-carrier" method="post" id="form-add-carrier-from" class="form-add-carrier">
                    <div class="row">
                        <div class="col">
                            <div class="col" id="carrier_add_inputs">
                                <div class="mb-4">
                                    <label for="" class="mb-1">Название <span class="text-danger">*</span></label>
                                    <input type="text" name="carrier_name" placeholder="Название" class="form-control form-control-solid" required>
                                </div>
                                <div class="mb-4">
                                    <label for="" class="mb-1">ИНН <span class="text-danger">*</span></label>
                                    <input type="number" name="carrier_inn" placeholder="ИНН" class="form-control form-control-solid" required>
                                </div>
                                <div class="mb-4">
                                    <label for="" class="mb-1">Юридический адрес <span class="text-danger">*</span></label>
                                    <textarea name="carrier_legal_address" placeholder="Адрес" class="form-control form-control-solid" required></textarea>
                                </div>
                                <div class="mb-4">
                                    <label for="" class="mb-1">Телефон, код в АТИ, конт. лицо, почта <span class="text-danger">*</span></label>
                                    <input type="text" name="carrier_info" id="carrier_info-input" placeholder="8 ()--, код в АТИ" class="form-control form-control-solid carrier_info-input" required value="8 ()--, код в АТИ ">
                                </div>
                            </div>
                            <div class="mb-4 d-flex justify-content-between">
                                <div id="add_carrier_info_input" class="mb-1" style="text-decoration: underline; cursor: pointer;"><span class="text-success">Добавить еще</span></div>
                                <div id="delete_carrier_info_input" class="mb-1" style="text-decoration: underline; cursor: pointer;"><span class="text-danger">Удалить</span></div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отменить</button>
                <button type="button" class="btn btn-primary" id="btn-add-carrier-from">Добавить</button>
            </div>
        </div>
    </div>
</div>

<script>

    $('#btn-target-form-filter-client').click(function () {
        $('#form-filter-client').trigger('submit');
    });

    $("#carrier_info-input").inputmask("8 (999)-99-99, код в АТИ *{0,50}");
    $('#btn-add-carrier-from').click(function () {
        $('#form-add-carrier-from.form-add-carrier').trigger('submit');
    });
    let carrier_info_input_html = `<div class="mb-4 carrier_info-input-wrap">
        <label for="" class="mb-1">Телефон, код в АТИ, конт. лицо, почта <span class="text-danger">*</span></label>
        <input type="text" name="carrier_info" id="carrier_info-input" placeholder="8 ()--, код в АТИ" class="form-control form-control-solid carrier_info-input" required value="8 ()--, код в АТИ ">
    </div>`;
    $("#add_carrier_info_input").click(function (){
        $("#carrier_add_inputs").append(carrier_info_input_html);
        $(".carrier_info-input").inputmask("8 (999)-99-99, код в АТИ *{0,50}");
    });
    $("#delete_carrier_info_input").click(function (){
        $(".carrier_info-input-wrap:last-child").remove();
    });

    $('#form-add-carrier-from').submit(function (e) {
        e.preventDefault();

        let form = $(this).serializeArray();
        let form_data = {};

        form.forEach((form_element) => {
            form_data[form_element["name"]] = form_element["value"];
        });

        var carrier_info_inputs = Array.from($(".carrier_info-input"));
        var carrier_info_inputs_values = [];

        carrier_info_inputs.forEach((carrier_info_input)=>{
            carrier_info_inputs_values.push(carrier_info_input.value);
        });

        $.ajax({
            url: '/application/ajax/add-carrier',
            method: 'POST',
            data: {form_data: form_data, info_inputs: carrier_info_inputs_values},
            success: function (data_json){
                let data = JSON.parse(data_json);

                console.log(data);

                if(data['status'] === 'Success'){
                    document.location.reload();
                }
                else if(data['status'] === "Error"){
                    if(! data['errorText'])
                        alert("Не удалось добавить перевозчика");
                    else
                        alert(data['errorText']);
                }
                else{
                    alert("Поля заполнены не правильно !");
                }
            }
        });
    });
</script>
</body>
