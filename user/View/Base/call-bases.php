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
<!--<script src="/assets/plugins/webix/webix.js"></script>-->

<!---->
<!--<link rel="stylesheet" href="/assets/plugins/webix/webix.css">-->


<style>
    .webix_ssheet {
        display: flex;
        flex-direction: column;
    }
    .webix_scrollview {
        order: 2;
    }
    .webix_ssheet_toolbar {
        order: 3;
    }
    .webix_layout_line {
        order: 4;
    }
    .webix_ssheet_bottom_toolbar {
        order: 1;
    }
    .webix_ssheet_bottom_toolbar .webix_list_item {
        width: auto !important;
    }
    .webix_text_highlight {
        display: none;
    }
    .webix_el_texthighlight.webix_control .webix_el_box input {
        color: black;
    }
    .webix_message_area{
        /*display: none;*/
    }
</style>

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
                            <a href="/base/carriers" class="nav-link">База перевозчиков</a>
                        </li>
                        <li class="nav-item">
                            <a href="/base/loaders" class="nav-link">База грузчиков</a>
                        </li>
                        <li class="nav-item">
                            <a href="/base/call-bases" class="nav-link active">База обзвонов</a>
                        </li>
                    </ul>

                </div>
                <div class="col">
                    <div class="d-flex justify-content-end">
                        <?php
                            if ($userRole === 6 || $userRole === 7) {?>
                                <select name="carrier_id" id="user_id_select" class="form-select select-add-application united-select select">
                                    <option value="0" disabled selected>Сменить пользователя</option>
                                    <?php foreach ($usersList as $user){ ?>
                                        <option value="<?php echo $user['id']; ?>">
                                            <?php echo $user['name']; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                                <script>
                                    $("#user_id_select").on('change', function(){
                                        document.location.href = "?userId="+$(this).val();
                                    });
                                    $( ".united-select" ).each(function(element) {
                                        $(this).addClass('gg');
                                        new Choices(this, {
                                            allowHTML: true
                                        });
                                    });
                                </script>
                            <?php }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <section class="base-clients__list carrier">
        <div id="box" style="height: 900px;">

        </div>
        <script>
            let userId = "<?php echo $userId; ?>";

            <?php if ($controller->request->input("userId")){?>
                userId = <?php echo $controller->request->input("userId")?>;
            <?php }?>
           webix.ready(function(){
                // объект конструктора
                webix.ui({
                    container: "box",
                    id: "ssheet",
                    view:"spreadsheet",
                    toolbar: "full",
                    scroll:"xy",
                    width: "100%",
                    url:function(params){
                        var data = webix.ajax().sync().get("<?php echo $controller->configGet("app.url");?>assets/user-base-obzvon/"+userId+"/info.json?<?= mt_rand(1, 1000) ?>").responseText;

                       this.parse(data);
                    },
                    on: {
                        onChange: function (mode, name, oldName) {
                            console.log("change");
                            var data = {
                                value: JSON.stringify($$("ssheet").serialize({sheets: true})),
                            }


                            jQuery.post('<?php echo $controller->configGet("app.url");?>assets/user-base-obzvon/'+userId+'/data.php', data, function (res) {

                            })
                        },
                        onSheetAdd: function (name) {
                            console.log("SheetAdd");
                            var styleProps = $$("ssheet").addStyle({
                                "background":"#FFFF00",
                                "color": "#000",
                                "text-align": "center",
                                "wrap":"wrap",
                            });
                            $$("ssheet").setCellValue(1, 1, "Город", name);
                            $$("ssheet").setStyle(1, 1, styleProps);

                            $$("ssheet").setCellValue(1, 2, "Сайт", name);
                            $$("ssheet").setColumnWidth(2, 198);
                            $$("ssheet").setStyle(1, 2, styleProps);

                            $$("ssheet").setCellValue(1, 3, "Название фирмы, ИНН", name);
                            $$("ssheet").setColumnWidth(3, 198);
                            $$("ssheet").setStyle(1, 3, styleProps);

                            $$("ssheet").setCellValue(1, 4, "Характер груза", name);
                            $$("ssheet").setColumnWidth(4, 200);
                            $$("ssheet").setStyle(1, 4, styleProps);

                            $$("ssheet").setCellValue(1, 5, "Номер телефона, E-mail Контактное лицо", name);
                            $$("ssheet").setColumnWidth(5, 208);
                            $$("ssheet").setStyle(1, 5, styleProps);

                            $$("ssheet").setCellValue(1, 6, "Результат переговоров", name);
                            $$("ssheet").setStyle(1, 6, styleProps);
                            $$("ssheet").setColumnWidth(6, 557);

                            $$("ssheet").setCellValue(1, 7, "Дата следущего звонка", name);
                            $$("ssheet").setStyle(1, 7, styleProps);
                            $$("ssheet").setColumnWidth(7, 148);

                            $$("ssheet").setFormat(2,7,"dd.mm.yyyy");
                            $$("ssheet").freezeRows(1);
                            $$("ssheet").setRowHeight(1, 57);
                            $$("ssheet").refresh();

                        },
                        onFileUpload: function (response) {
                            console.log("file");
                            var data = response.data; // Получение данных из импортированного файла
                            // Сохранение данных в вашем коде
                            var saveData = {
                                value: $$("ssheet").serialize({sheets: true}),
                                importedData: data
                            };
                            console.log(saveData)

                            jQuery.post('<?php echo $controller->configGet("app.url");?>assets/user-base-obzvon/'+userId+'/data.php', saveData, function (res) {

                                // Обработка успешного сохранения данных после импорта
                            });
                        },
                    }
                });
            });
        </script>
    </section>

</main>



</body>
