<?php
/** @var App\User\Contoller\Common\HomeController $controller */
/** @var String $titlePage */
/** @var double $countPage */
/** @var int $page */
/** @var String $link */
/** @var array $listClients */
/** @var array $searchData */

$controller->view('Components/head');
//dd($listClients);
?>

<body>
<?php $controller->view('Components/header'); ?>
<style>
    .client-comment-container{
        padding: 0.5rem;
        background-color: darkorange;
    }
</style>
<main class="base">
    <div class="sub-header" style="padding-bottom: 0;">
        <div class="wrapper">
            <?php $controller->view('Components/breadcrumbs'); ?>

            <h3 class="sub-header-title"><?php echo $titlePage; ?></h3>

            <div class="row" style="margin-top: 40px;">
                <div class="col-6">
                    <ul class="nav nav-underline nav-subheader">
                        <li class="nav-item">
                            <a href="#" class="nav-link ">База клиентов</a>
                        </li>
                        <li class="nav-item">
                            <a href="/base/carriers" class="nav-link">База перевозчиков</a>
                        </li>
                        <li class="nav-item d-none">
                            <a href="/base/loaders" class="nav-link">База грузчиков</a>
                        </li>
                        <li class="nav-item">
                            <a href="/base/call-bases" class="nav-link active">База обзвонов</a>
                        </li>
                    </ul>


                </div>
                <div class="col">
                    <div class="d-flex justify-content-end">
                        <?php if($controller->auth->user()->fullCRM()): ?>
                        <!-- <button class="btn btn-success me-3" id="download-excel">
                            Скачать в EXCEL
                        </button> -->
                        <?php endif; ?>
                        <div class="dropdown ">
                            <button class="btn btn-theme-white dropdown-toggle dropdown-toggle-without-arrow" type="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-sort-alpha-down" style="font-size: 16px"></i> По умолчанию
                            </button>
                            <ul class="dropdown-menu dropdown-menu-theme">
                                <li><a href="/base/clients" class="dropdown-item">По умолчанию</a></li>
                                <li><a href="?order=DESC" class="dropdown-item">Сначала новые</a></li>
                                <li><a href="?order=ASC" class="dropdown-item">Сначала старые</a></li>
                            </ul>
                        </div>
                        <button class="btn btn-theme-white mx-3" type="button" data-bs-toggle="modal" data-bs-target="#filterModal">
                            <i class="bi bi-funnel-fill" ></i> Фильтры
                        </button>
                        <?php if($isFullCRMAccess OR $controller->auth->user()->id() == 55): ?>
                            <button class="btn btn-theme-success" type="button" data-bs-toggle="modal" data-bs-target="#modalAddClient">
                                <i class="bi bi-plus" style="font-size: 16px"></i> Добавить
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <section class="base-clients__list">
        <div class="wrapper bg-whitesmoke" style="">
            <!-- <div class="search search-page-block">
                <form action="" class="d-flex">
                    <select name="search-select" id="" class="form-select">
                        <option value="all">Все</option>
                        <option value="name" <?php if($searchData['search-select'] == 'name') echo 'name'; ?>>Название</option>
                        <option value="inn" <?php if($searchData['search-select'] == 'inn') echo 'inn'; ?>>ИНН</option>
                        <option value="phone" <?php if($searchData['search-select'] == 'phone') echo 'selected'; ?>>Телефон</option>
                        <option value="email" <?php if($searchData['search-select'] == 'email') echo 'selected'; ?>>Email</option>
                    </select>
                    <input type="text" class="form-control" name="search" placeholder="Поиск..." value="<?php echo $searchData['search']; ?>">
                    <a class="btn btn-danger btn-cancel" href="/base/clients">Сбросить</a>
                </form>
            </div> -->
        </div>
        <div class="wrapper" style="">
            <div class="post-list__header d-flex">
                <div class="post-list__header-item client">НАЗВАНИЕ ОРГАНИЗАЦИИ</div>
                <div class="post-list__header-item client">ИНН</div>
                <div class="post-list__header-item client">ФИО</div>
                <div class="post-list__header-item client">ТЕЛЕФОН</div>
                <div class="post-list__header-item client">ДОЛЖНОСТЬ</div>
                <!-- <div class="post-list__header-item client">ЛОГИСТ</div> -->
                <div class="post-list__header-item client text-center">Последний комментарий</div>
<!--                <div class="post-list__header-item client"></div>-->
<!--                <div class="post-list__header-item"></div>-->
            </div>

            <?php foreach($listClients as $client) { ?>
                <div class="post-list__items">
                    <a href="/client?id=<?php echo $client['id']?>" class="post-list__item item d-flex">
                        <div class="w-100 d-flex " style="align-items: center">
                            <div class="item__title"><?php echo $client['name']?></div>
                            <div class="item__inn"><?php echo $client['inn']?></div>
                            <div class="item__fio">
                                <?php echo $client['full_name']?>
                            </div>
                            <div class="item__tel">
                                <?php echo $client['phone']?>
                            </div>
                            <div class="item__job">
                                <?php echo $client['job_title']?>
                            </div>
                            <!-- <div class="item__gruz">
                                <?php foreach ($client['managersAccess'] as $manager): ?>
                                    <div class="manager_gray_wrap"><?php echo $manager['surname'] .' ' .$manager['name']; ?></div>
                                <?php endforeach; ?>
                            </div> -->
                            <div class="item__summ text-center">
                                <div class="client-comment-container">
                                    <?php echo $client['last_comment']; ?>
                                </div>
                            </div>

                        </div>

                    </a>
                </div>
            <?php } ?>

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
                            <option value="С НДС 20%">С НДС</option>
                            <option value="Б/НДС">Б/НДС</option>
                            <option value="С НДС 0%">С НДС 0%</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="" class="label-theme">Форма оплаты:</label>
                        <select name="format-work" id="" class="form-select" >
                            <option value="0" disabled selected>Формат работы</option>
                            <option value="Сканы счет/акт"
                                <?php if($condition['format_work'] == 'Сканы счет/акт') echo 'selected'; ?> >
                                    Сканы счет/акт
                            </option>
                            <option value="Счет/акт + фото ТСД"
                                <?php if($condition['format_work'] == 'Счет/акт + фото ТСД') echo 'selected'; ?>
                            >Счет/акт + фото ТСД</option>
                            <option value="Счет/акт + сканы ТСД"
                                <?php if($condition['format_work'] == 'Счет/акт + сканы ТСД') echo 'selected'; ?>
                            >Счет/акт + сканы ТСД</option>
                            <option value="ЭДО"
                                <?php if($condition['format_work'] == 'ЭДО') echo 'selected'; ?>
                            >ЭДО</option>
                            <option value="Оригиналы"
                                <?php if($condition['format_work'] == 'Оригиналы') echo 'selected'; ?>
                            >Оригиналы</option>
                            <option value="Квиток"
                                <?php if($condition['format_work'] == 'Квиток') echo 'selected'; ?>
                            >Квиток</option>
                        </select>
                    </div>

                    <select name="search-select" id="" class="d-none">
                        <option value="all">Все</option>
                        <option value="name" <?php if($searchData['search-select'] == 'name') echo 'name'; ?>>Название</option>
                        <option value="inn" <?php if($searchData['search-select'] == 'inn') echo 'inn'; ?>>ИНН</option>
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


<div class="modal fade" id="addClientModal" tabindex="-1" aria-labelledby="addClientModalLabel" aria-hidden="true">
    <div class="modal-dialog" >
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="addClientModalLabel">Добавление клиента в базу обзвонов</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <div class="d-flex w-100 justify-content-between">
                    <button type="button" class="btn btn-theme btn-light" data-bs-dismiss="modal">Сбросить</button>
                    <button type="button" class="btn btn-theme btn-theme-success">Добавить</button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal modal-add-application fade" id="modalAddClient" tabindex="-1" aria-labelledby="modalAddClient" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="min-width: 70%">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title title-base" id="exampleModalLabel">Добавление клиента в базу обзвона</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
            </div>
            <div class="modal-body">
                <form action="/application/ajax/add-client-base" method="post" id="form-add-client-from" class="form-add-client">
                    <div class="row">
                        <div class="col" id="client_add_inputs">
                            <h5 class="mb-3">
                                Основная информация
                            </h5>
                            <div class="mb-4 d-none">
                                <label for="" class="mb-1">По чьей заявке работаем?</label>
                                <select name="application_format" id="" class="form-select">
                                    <option value="" disabled >Выберите вариант</option>
                                    <option value="0" selected>По ООО</option>
                                    <option value="1">По заявке клиента</option>
                                </select>
                            </div>
                            <div class="mb-4">
                                <label for="" class="mb-1">Название <span class="text-danger">*</span></label>
                                <input type="text" name="client_name" placeholder="Название" class="form-control form-control-solid" required>
                            </div>
                            <div class="mb-4">
                                <label for="" class="mb-1">ИНН <span class="text-danger">*</span></label>
                                <input type="number" name="client_inn" placeholder="ИНН" class="form-control form-control-solid" required>
                            </div>
                            <div class="mb-4">
                                <label for="" class="mb-1">Юридический адрес <span class="text-danger">*</span></label>
                                <textarea name="client_legal_address" placeholder="Адрес" class="form-control form-control-solid" required></textarea>
                            </div>
                            <div class="mb-4">
                                <label for="" class="mb-1">Вид налогообложения <span class="text-danger">*</span></label>
                                <select name="type_taxation_id" id="" class="form-select select-type-taxation">
                                    <option value="0" disabled selected>Вид налогообложения</option>
                                    <option value="С НДС 20%">С НДС 20%</option>
                                    <option value="С НДС 0%">С НДС 0%</option>
                                    <option value="Б/НДС">Б/НДС</option>
                                </select>
                            </div>
                            <div class="mb-4">
                                <label for="" class="mb-1">Формат работы <span class="text-danger">*</span></label>
                                <select name="format_work" id="" class="form-select select-type-taxation">
                                    <option value="Неизвестно" selected>Неизвестно</option>
                                    <option value="Сканы счет/акт">Сканы счет/акт</option>
                                    <option value="Счет/акт + фото ТСД">Счет/акт + фото ТСД</option>
                                    <option value="Счет/акт + сканы ТСД">Счет/акт + сканы ТСД</option>
                                    <option value="ЭДО">ЭДО</option>
                                    <option value="Оригиналы">Оригиналы</option>
                                    <option value="Квиток">Квиток</option>
                                </select>
                            </div>
                           
                        </div>
                        <div class="col">
                            <h5 class="mb-3">
                                Контактное лицо
                            </h5>
                            <div class="mb-4">
                                <label for="" class="mb-1">ФИО <span class="text-danger">*</span></label>
                                <input type="text" name="client_full_name" placeholder="ФИО" class="form-control form-control-solid" required>
                            </div>
                            <div class="mb-4">
                                <label for="" class="mb-1">Должность</label>
                                <input type="text" name="client_job_title" placeholder="Должность" class="form-control form-control-solid">
                            </div>
                            <div class="mb-4">
                                <label for="" class="mb-1">Телефон <span class="text-danger">*</span></label>
                                <input type="text" name="client_phone" id="client-phone-input" placeholder="8 ()--" class="form-control form-control-solid client-phone-input" required value="8 ()--">
                            </div>
                            <div class="mb-4">
                                <label for="" class="mb-1">Почта <span class="text-danger">*</span></label>
                                <input type="email" name="client_email" id="client-ati-input" placeholder="Почта" class="form-control form-control-solid client-phone-input" required>
                            </div>
                            <div class="mb-4">
                                <label for="" class="mb-1">Код в АТИ</label>
                                <input type="text" name="client_ati" id="client-ati-input" placeholder="Код АТИ" class="form-control form-control-solid client-phone-input" required>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light btn-close-modal" data-bs-dismiss="modal">Отменить</button>
                <button type="button" class="btn btn-add-light" id="btn-add-client-from">Добавить</button>
            </div>
        </div>
    </div>
</div>

<script>
    const url = "https://suggestions.dadata.ru/suggestions/api/4_1/rs/findById/party";
    const token = "9eaeccf0343b3d70297e7dd63e367b76572bde03";

    document.querySelector('[name="client_inn"]').addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();

            let inn = this.value.trim();
            if (!inn) return;

            let options = {
                method: "POST",
                mode: "cors",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "Authorization": "Token " + token
                },
                body: JSON.stringify({ query: inn })
            };

            fetch(url, options)
                .then(response => response.json())
                .then(result => {
                    if (result.suggestions && result.suggestions.length > 0) {
                        let data = result.suggestions[0].data;

                        // Название организации
                        document.querySelector('[name="client_name"]').value = data.name.full_with_opf || "";

                        // Юридический адрес
                        document.querySelector('[name="client_legal_address"]').value = data.address.value || "";
                    } else {
                        alert("Организация с таким ИНН не найдена");
                    }
                })
                .catch(error => console.log("Ошибка:", error));
        }
    });
</script>
<script>
    $("#client-phone-input").inputmask("8 (999) 999-99-99");
</script>
<script>
    $('#download-excel').click(function (){
        $.ajax({
            method: 'POST',
            url: '/clients/ajax/excel-list-clients',
            success: function (response){
                let data = JSON.parse(response);

                if(data['status'])
                    download_file('Список клиентов.xlsx', data['link_file']);

            }
        })
    });

    $('#btn-target-form-filter-client').click(function () {
        $('#form-filter-client').trigger('submit');
    });

    $('#btn-add-client-from').click(function () {
        $('#form-add-client-from').trigger('submit');
    });

    $('#form-add-client-from').submit(function (e) {
        e.preventDefault();

        let form = $(this).serializeArray();

        $.ajax({
            url: '/base/ajax/add-client-base-calls',
            method: 'POST',
            data: form,
            success: function (response){
                console.log(response);
                let data = JSON.parse(response);
                if (data['status'] === "Success"){
                    document.location.reload();
                }
                else if(data['status'] === 'Error'){
                    if(! data['errorText'])
                        alert("Не удалось добавить клиента");
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
