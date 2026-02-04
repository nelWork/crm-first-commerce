<?php
/**
 * @var App\User\Contoller\Common\HomeController $controller
 */
/** @var App\User\Model\Application\ApplicationPage $application */
/** @var array $client */
/** @var array $listManager */

$controller->view('Components/head');

//dd($client);
?>

<body>
<?php $controller->view('Components/header'); ?>

<div class="card-application">
    <div class="card-wrapper wrapper">
        <div class="card__header row align-items-center">
            <div class="card__header-title col-10 d-flex align-items-center">
                <?php echo $client['name'];?>

            </div>
            <div class="row col-2 justify-content-end align-items-center">
                <div class="settings col-2 p-0 row justify-content-end">
                    <div class="dropdown">
                        <div class="dropdown-toggle dropdown-toggle-without-arrow" data-bs-toggle="dropdown" aria-expanded="false">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path opacity="0.3" d="M17 2H7C4.23858 2 2 4.23858 2 7V17C2 19.7614 4.23858 22 7 22H17C19.7614 22 22 19.7614 22 17V7C22 4.23858 19.7614 2 17 2Z" fill="#B5B5C3"></path>
                                <path d="M13 12C13 11.4477 12.5523 11 12 11C11.4477 11 11 11.4477 11 12C11 12.5523 11.4477 13 12 13C12.5523 13 13 12.5523 13 12Z" fill="#B5B5C3"></path>
                                <path d="M13 16C13 15.4477 12.5523 15 12 15C11.4477 15 11 15.4477 11 16C11 16.5523 11.4477 17 12 17C12.5523 17 13 16.5523 13 16Z" fill="#B5B5C3"></path>
                                <path d="M13 8C13 7.44772 12.5523 7 12 7C11.4477 7 11 7.44772 11 8C11 8.55228 11.4477 9 12 9C12.5523 9 13 8.55228 13 8Z" fill="#B5B5C3"></path>
                            </svg>
                        </div>
                        <?php if($controller->auth->user()->fullCRM() OR $controller->auth->user()->id() == 55): ?>
                        <ul class="dropdown-menu dropdown-menu-theme">
                            <li><div class="dropdown-item js-edit-fines" data-bs-toggle="modal" data-bs-target="#modalEditClient">Редактировать</div></li>
<!--                            <li><div class="dropdown-item js-delete-fines">Удалить</div></li>-->
                            <?php if($client['in_work'] == 1): ?>
                                <li><div class="dropdown-item" data-bs-toggle="modal" data-bs-target="#modalChangeManager">Передать</div></li>
                            <?php endif; ?>
                            <?php if($client['in_work'] == 0): ?>
                                <li><div class="dropdown-item" id="changeInWork" data-id-client="<?php echo $client['id']; ?>">Перенести в основные</div></li>
                            <?php endif; ?>
                        </ul>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        </div>

        <div class="card__content d-flex">
            <div class="card__content-main d-flex flex-column">
                <div class="card__content-main-harakter_gruza">
                    <textarea name="client_character" id="client_character" class="ajax-input"  rows="10" style="width: 100%"> <?php echo $clientInfo['client_character']?></textarea>
                </div>
                <script>
                    let Editor_client_character;
                    ClassicEditor
                        .create( document.querySelector( '#client_character' ),
                            {
                                toolbar: [  'undo', 'redo', 'Heading', 'Link', 'bold', 'italic', 'numberedList', 'bulletedList' ]
                            })
                        .then( editor => {
                            Editor_client_character = editor;
                            console.log( editor );
                        } )
                        .catch( error => {
                            console.error( error );
                        });
                </script>
                <div class="card__content-main__header  d-flex">

                    <div class="form__header-menu-list d-flex w-100">
                        <a class="form__header-menu-item">
                            <div class="menu-item-click active js-link-tab-change main-header-js-link-tab-change"
                                 data-container=".card__content-tab" data-tab-app="#tab-1" data-prefix-tab=".main-header">
                                Контактные лица
                            </div>
                        </a>
                        <?php if($client['in_work'] == 1): ?>
                        <a class="mr-6 form__header-menu-item d-none">
                            <div class="menu-item-click js-link-tab-change main-header-js-link-tab-change"
                                 data-container=".card__content-tab"  data-tab-app="#tab-1-1" data-prefix-tab=".main-header">
                                Чат
                            </div>
                        </a>

                        <a class="mr-6 form__header-menu-item ">
                            <div class="menu-item-click js-link-tab-change main-header-js-link-tab-change"
                                 data-container=".card__content-tab"  data-tab-app="#tab-2" data-prefix-tab=".main-header">
                                Документы
                            </div>
                        </a>

                        <a class="mr-6 form__header-menu-item  ">
                            <div class="menu-item-click js-link-tab-change main-header-js-link-tab-change"
                                 data-container=".card__content-tab" data-tab-app="#tab-3" data-prefix-tab=".main-header">
                                Бухгалтера
                            </div>
                        </a>
                        <?php endif; ?>

                        <a class="mr-6 form__header-menu-item ">
                            <div class="menu-item-click js-link-tab-change main-header-js-link-tab-change"
                                 data-container=".card__content-tab" data-tab-app="#tab-4" data-prefix-tab=".main-header">
                                Отдел продаж
                            </div>
                        </a>

                        <?php if($client['in_work'] == 1): ?>

                        <a class="mr-6 form__header-menu-item ">
                            <div class="menu-item-click js-link-tab-change main-header-js-link-tab-change"
                                 data-container=".card__content-tab" data-tab-app="#tab-5" data-prefix-tab=".main-header">
                                Юристы
                            </div>
                        </a>

                        <a class="mr-6 form__header-menu-item ">
                            <div class="menu-item-click js-link-tab-change main-header-js-link-tab-change"
                                 data-container=".card__content-tab" data-tab-app="#tab-6" data-prefix-tab=".main-header">
                                Заявки
                            </div>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="card__content-tab active" id="tab-1">
                    <button class="btn btn-add-light" data-bs-toggle="modal" data-bs-target="#modalAddClient">
                        + Добавить
                    </button>

                    <div class="quest__table">
                        <div class="quest__table-header d-flex">
                            <span>Имя</span>
                            <span>Должность</span>
                            <span>Номер телефона</span>
                            <span>Почта</span>
                            <span></span>
                        </div>
                        <div class="quest__table-list quest__table-contact-faces-list d-flex flex-column">
                            <?php foreach($listContactFaces as $contactFace){?>
                                <div class="quest__table-list quest__table-tasks contact_face_<?php echo $contactFace["id"];?>">
                                    <div class="quest__table-task">
                                        <div class="quest__table-task-name">
                                            <div class="name">
                                                <?php echo $contactFace['name']?>
                                            </div>
                                        </div>
                                        <div class="quest__table-task-deadline">
                                            <?php echo $contactFace['job']?>
                                        </div>
                                        <div class="quest__table-task-deadline">
                                            <?php echo $contactFace['phone']?>
                                        </div>
                                        <div class="quest__table-task-deadline" style="width: 325px;">
                                            <?php echo $contactFace['email']?>
                                        </div>
                                        <div class="quest__table-task-status-btns" style="width: auto;">
                                            <div class="settings col-2 p-0 row justify-content-end">
                                                <div class="dropdown">
                                                    <div class="dropdown-toggle dropdown-toggle-without-arrow" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path opacity="0.3" d="M17 2H7C4.23858 2 2 4.23858 2 7V17C2 19.7614 4.23858 22 7 22H17C19.7614 22 22 19.7614 22 17V7C22 4.23858 19.7614 2 17 2Z" fill="#B5B5C3"></path>
                                                            <path d="M13 12C13 11.4477 12.5523 11 12 11C11.4477 11 11 11.4477 11 12C11 12.5523 11.4477 13 12 13C12.5523 13 13 12.5523 13 12Z" fill="#B5B5C3"></path>
                                                            <path d="M13 16C13 15.4477 12.5523 15 12 15C11.4477 15 11 15.4477 11 16C11 16.5523 11.4477 17 12 17C12.5523 17 13 16.5523 13 16Z" fill="#B5B5C3"></path>
                                                            <path d="M13 8C13 7.44772 12.5523 7 12 7C11.4477 7 11 7.44772 11 8C11 8.55228 11.4477 9 12 9C12.5523 9 13 8.55228 13 8Z" fill="#B5B5C3"></path>
                                                        </svg>
                                                    </div>
                                                    <ul class="dropdown-menu dropdown-menu-theme">
                                                        <li><div class="dropdown-item js-edit-fines">Редактировать</div></li>
                                                        <li><div class="dropdown-item js-delete-fines" onclick="deleteContactFace(<?php echo $contactFace['id']?>);">Удалить</div></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            <?php }?>
                        </div>

                    </div>
                </div>
                <div class="card__content-tab" id="tab-1-1">
                    <div class="quest__table-list-item list-item d-flex position-relative">
                        <div class="list-item__number">1</div>
                        <div class="list-item__type-document">
                            Фото чата
                        </div>
                        <div class="list-item__document-rows list-item__document-rows1">
                            <div class="list-item__document-row empty">
                                <div class="list-item__file-name">

                                </div>
                                <div class="data-and-btn m-0">
                                    <span class="file-data-none">Не загружено</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-check form-check-custom form-check-solid form-check-sm align-items-start ml-auto">
                            <form action="/application/ajax/uploadFiles" method="post" id="form_file0" class="form-upload-file" enctype="multipart/form-data">
                                <input type="file" class="d-none upload_file_input" id="input-file0" name="file-signed-application-client" accept="image" multiple>
                                <button type="button" class="btn doc-upload-btn">
                                    <i class="bi bi-upload"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <?php
                            foreach ($documents as $document) :
                                if($document['document_id'] == 0): ?>
                                <div class="col">
                                    <img src="<?php echo $document['link']; ?>" alt="Фото" class="w-100">
                                </div>
                        <?php endif;
                            endforeach; ?>
                    </div>
                </div>
                <div class="card__content-tab" id="tab-2">
                    <?php $controller->view('Base/client-docs'); ?>
                </div>
                <div class="card__content-tab" id="tab-3">
                    <div class="d-flex flex-column">
                        <div class="d-flex flex-wrap  justify-content-between align-items-end">
                            <div class="mb-3" style="width: 30%">
                                <label for="basic-url" class="form-label" style="font-size: 15px;">Договор типовой</label>
                                <div class="input-group">
                                    <input value="<?php echo $clientInfo['accountant_standard_contract'];?>" type="text" placeholder="Договор типовой" class="form-control form-control-solid ajax-input" name="accountant_standard_contract" id="accountant_standard_contract" aria-describedby="basic-addon3 basic-addon4">
                                </div>
                            </div>
                            <div class="mb-3" style="width: 30%">
                                <label for="basic-url" class="form-label" style="font-size: 15px;">Оригиналы договора</label>
                                <div class="input-group">
                                    <input value="<?php echo $clientInfo['accountant_originals_contract'];?>" type="text" placeholder="Оригиналы договора" class="form-control form-control-solid ajax-input" name="accountant_originals_contract" id="accountant_originals_contract" aria-describedby="basic-addon3 basic-addon4">
                                </div>
                            </div>
                            <div class="mb-3" style="width: 30%">
                                <label for="basic-url" class="form-label" style="font-size: 15px;">Отправка закрывающих документов</label>
                                <div class="input-group">
                                    <input value="<?php echo $clientInfo['accountant_sending_closing_documents'];?>" type="text" placeholder="Отправка закрывающих документов" class="form-control form-control-solid ajax-input" name="accountant_sending_closing_documents" id="accountant_sending_closing_documents" aria-describedby="basic-addon3 basic-addon4">
                                </div>
                            </div>
                        </div>
                        <div class="d-flex flex-wrap mt-3" style="gap: 50px">
                            <div class="mb-3" style="width: 30%">
                                <label for="basic-url" class="form-label" style="font-size: 15px;"> Наличие ТТН/ТрН</label>
                                <div class="input-group">
                                    <input value="<?php echo $clientInfo['accountant_availability_ttn_trn'];?>" type="text" placeholder="Наличие ТТН/ТрН" class="form-control form-control-solid ajax-input" name="accountant_availability_ttn_trn" id="accountant_availability_ttn_trn" aria-describedby="basic-addon3 basic-addon4">
                                </div>
                            </div>
                            <div class="mb-3" style="width: 30%">
                                <label for="basic-url" class="form-label" style="font-size: 15px;">Отправка ТТН/ТрН/ЭР</label>
                                <div class="input-group">
                                    <input value="<?php echo $clientInfo['accountant_sending_ttn_trn_er'];?>" type="text" placeholder="Отправка ТТН/ТрН/ЭР" class="form-control form-control-solid ajax-input" name="accountant_sending_ttn_trn_er" id="accountant_sending_ttn_trn_er" aria-describedby="basic-addon3 basic-addon4">
                                </div>
                            </div>
                        </div>
                        <div class="d-flex mt-3">
                            <textarea name="accountant_text" id="accountant_text" style="width: 100%" rows="10" class="ajax-input w-100"><?php echo $clientInfo['accountant_text'];?></textarea>
                        </div>
                        <script>
                            let Editor_accountant_text;
                            ClassicEditor
                                .create( document.querySelector( '#accountant_text' ),
                                    {
                                        toolbar: [  'undo', 'redo', 'Heading', 'Link', 'bold', 'italic', 'numberedList', 'bulletedList' ]
                                    })
                                .then( editor => {
                                    Editor_accountant_text = editor;
                                    console.log( editor );
                                } )
                                .catch( error => {
                                    console.error( error );
                                } );
                        </script>
                        <style>
                            .ck-editor{
                                width: 100% !important;
                            }
                        </style>
                    </div>
                </div>
                <div class="card__content-tab" id="tab-4">

                    <div class="client-comments">
                        <?php if(in_array($controller->auth->user()->id(),[1,55])): ?>
                            <div class="w-100 comment__add-input d-flex flex-column mb-5" id="comment__add-input" data-id-client="<?php echo $client['id']; ?>">
                                <div class="d-flex w-100 mb-4">
                                    <img src="<?php echo $controller->auth->user()->avatar(); ?>" alt="" class="avatar">
                                    <textarea class="w-100 add-comment-input" placeholder="Оставить комментарий" required></textarea>
                                </div>
                                <div class="comment__buttons">
                                    <button class="btn btn-success add-comment-button">Добавить</button>
                                    <button class="btn btn-light close-input-comment">Отмена</button>
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="w-100 comment__add-input edit d-none flex-column mb-5"  id="comment__edit-input" data-id-client="<?php echo $client['id']; ?>" data-id-comment="0">
                            <div class="d-flex w-100 mb-4">
                                <img src="<?php echo $controller->auth->user()->avatar(); ?>" alt="" class="avatar">
                                <textarea class="w-100 add-comment-input edit"
                                        id="edit-comment-input" placeholder="Оставить комментарий" required></textarea>
                            </div>
                            <div class="comment__buttons">
                                <button class="btn btn-success edit-comment-button">Изменить</button>
                                <button class="btn btn-light close-input-comment">Отмена</button>
                            </div>
                        </div>

                        <div class="comments-list">

                        </div>
                    </div>

                    <div class="d-none mt-3">
                        <textarea name="quality_department_text" id="quality_department_text" style="width: 100%" rows="10" class="ajax-input"><?php echo $clientInfo['quality_department_text'];?></textarea>
                    </div>
                    <script>
                        let Editor_quality_department_text;
                        ClassicEditor
                            .create( document.querySelector( '#quality_department_text' ),
                                {
                                    toolbar: [  'undo', 'redo', 'Heading', 'Link', 'bold', 'italic', 'numberedList', 'bulletedList' ]
                                })
                            .then( editor => {
                                Editor_quality_department_text = editor;
                                console.log( editor );
                            } )
                            .catch( error => {
                                console.error( error );
                            } );
                    </script>
                </div>
                <div class="card__content-tab" id="tab-5">
                    <div class="d-flex mt-3">
                        <textarea name="lawyers_text" id="lawyers_text" style="width: 100%" rows="10" class="ajax-input"><?php echo $clientInfo['lawyers_text'];?></textarea>
                    </div>
                    <script>
                        let Editor_lawyers_text;
                        ClassicEditor
                            .create( document.querySelector( '#lawyers_text' ),
                                {
                                    toolbar: [  'undo', 'redo', 'Heading', 'Link', 'bold', 'italic', 'numberedList', 'bulletedList' ]
                                })
                            .then( editor => {
                                Editor_lawyers_text = editor;
                                console.log( editor );
                            } )
                            .catch( error => {
                                console.error( error );
                            } );
                    </script>
                </div>
                <div class="card__content-tab" id="tab-6">
                    <?php if($controller->auth->user()->fullCRM()): ?>
                    <a class="btn btn-success" href="/journal-list?client-id=<?php echo $client['id']; ?>" target="_blank">Открыть в журнале</a>
                    <?php endif; ?>
                    <div class="d-flex mt-3 flex-column">
                        <?php foreach ($listApplications as $application) {?>
                            <div class="application-item" style="font-size: 18px; font-width: 700;">
                                <a href="/application?id=<?php echo $application['application_id']?>">
                                    № <?php echo $application['application_number']." (".$application['application_date'].")"?>
                                </a>
                            </div>
                            <br>
                        <?php }?>
                    </div>
                </div>
            </div>
            <div class="card__content-side col-4">
                <div class="card__content-side-tab active" id="side-tab-1">
                    <div class="side-block-info">
                        <div class="mb-4">
                            <span class="side-tab-span">Вид налогообложения</span>
                            <span class="side-main-span"><?php echo $client['type_taxation_id'];?></span>
                        </div>
                        <div class="mb-4">
                            <span class="side-tab-span">Формат работы</span>
                            <span class="side-main-span"><?php echo $client['format_work'];?></span>
                        </div>
                        <div class="mb-4">
                            <span class="side-tab-span">ИНН</span>
                            <span class="side-main-span"><?php echo $client['inn'];?></span>
                        </div>
                        <div class="mb-4">
                            <span class="side-tab-span">Юридический адрес</span>
                            <span class="side-main-span"><?php echo $client['legal_address'];?></span>
                        </div>
                        <div class="mb-4">
                            <span class="side-tab-span">Менеджеры</span>
                            <?php foreach ($client['managersAccess'] as $manager): ?>
                                <span class="side-main-span manager_gray_wrap">
                                    <?php echo $manager['name'] .' ' .$manager['surname']; ?>
                                </span>
                            <?php endforeach; ?>
                        </div>
                        <div class="mb-4">
                            <span class="side-tab-span">Кол-во заявок</span>
                            <span class="side-main-span"><?php echo $client['quantity_applications'];?></span>
                        </div>
                        <div class="mb-4">
                            <span class="side-tab-span">Отсрочка платежа</span>
                            <span class="side-main-span"><?php echo $client['payment_deferment']; ?></span>
                        </div>
                    </div>
                </div>
                <div class="card__content-side-tab" id="side-tab-2">
                    <div class="side-block-info">
                        <div class="mb-4">
                            <span class="side-tab-span">Менеджер</span>
                            <div class="">
                                <img alt="аватар"
                                     src="https://secure.gravatar.com/avatar/ad516503a11cd5ca435acc9bb6523536?s=150&amp;d=mm&amp;r=gforcedefault=1"
                                     srcset="https://secure.gravatar.com/avatar/ad516503a11cd5ca435acc9bb6523536?s=150&amp;d=mm&amp;r=gforcedefault=1 2x"
                                     class="avatar avatar-28 photo my-class-1 super-gravarar" height="28" width="28" style="top:-5px;border-radius: 100%"
                                     title="Миша в горах" decoding="async">
                                <span class="side-main-span">Вадим Гринкевич</span>
                            </div>
                        </div>
                        <div class="mb-4">
                            <span class="side-tab-span">Перевозчик</span>
<!--                            <span class="side-main-span">--><?php //echo $application->carrier['name']; ?><!--</span>-->
                        </div>
                        <div class="mb-4">
                            <span class="side-tab-span">Маршрут</span>
                            <span class="side-main-span">Московская обл, г.о. Пушкинский, рп Софрино - Респ Башкортостан,
                                    р-н Туймазинский, г Туймазы</span>
                        </div>

                        <div class="mb-4">
                            <span class="side-tab-span">Вид налогообложения</span>
<!--                            <span class="side-main-span">--><?php //echo $application->carrier['type_taxation']; ?><!--</span>-->
                        </div>
                    </div>

                    <?php $controller->view('Application/application-carrier-dop-info'); ?>

                </div>
                <div class="card__content-side-tab" id="side-tab-3">
                    <div class="side-block-info">
                        <div class="mb-4">
                            <span class="side-tab-span">Менеджер</span>
                            <div class="">
                                <img alt="аватар"
                                     src="https://secure.gravatar.com/avatar/ad516503a11cd5ca435acc9bb6523536?s=150&amp;d=mm&amp;r=gforcedefault=1"
                                     srcset="https://secure.gravatar.com/avatar/ad516503a11cd5ca435acc9bb6523536?s=150&amp;d=mm&amp;r=gforcedefault=1 2x"
                                     class="avatar avatar-28 photo my-class-1 super-gravarar" height="28" width="28" style="top:-5px;border-radius: 100%"
                                     title="Миша в горах" decoding="async">
                                <span class="side-main-span">Вадим Гринкевич</span>
                            </div>
                        </div>
                        <div class="mb-4">
                            <span class="side-tab-span">Клиент</span>
<!--                            <span class="side-main-span">--><?php //echo $application->client['name']; ?><!--</span>-->
                        </div>
                        <div class="mb-4">
                            <span class="side-tab-span">Маршрут</span>
                            <span class="side-main-span">Московская обл, г.о. Пушкинский, рп Софрино - Респ Башкортостан,
                                    р-н Туймазинский, г Туймазы</span>
                        </div>

                        <div class="mb-4">
                            <span class="side-tab-span">Вид налогообложения</span>
<!--                            <span class="side-main-span">--><?php //echo $application->client['type_taxation']; ?><!--</span>-->
                        </div>
                    </div>
                    <?php $controller->view('Application/application-client-dop-info'); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal modal-add-application fade" id="modalComment" tabindex="-1" aria-labelledby="modalComment" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title title-base">Комментарий</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
            </div>
            <div class="modal-body">
                <div class="modal-comment-text" id="modal-comment-text">

                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal modal-add-application fade" id="modalEditClient" tabindex="-1" aria-labelledby="modalEditClient" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="min-width: 700px">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title title-base" id="exampleModalEditClient">Редактировать клиента</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
            </div>
            <div class="modal-body">
                <form action="/application/ajax/edit-client" method="post" id="form-edit-client-from" class="form-edit-client">
                    <input type="hidden" name="id" value="<?php echo $client['id'];?>">
                    <div class="row">
                        <div class="col" id="client_add_inputs">
                            <h5 class="mb-3">
                                Основная информация
                            </h5>
                            <div class="mb-4 d-none">
                                <label for="" class="mb-1">По чьей заявке работаем?</label>
                                <select name="application_format" id="" class="form-select">
                                    <option value="" disabled selected>Выберите вариант</option>
                                    <option value="0" <?php if($client['application_format'] == 0) echo 'selected'; ?>>По ООО</option>
                                    <option value="1" <?php if($client['application_format']) echo 'selected'; ?>>По заявке клиента</option>
                                </select>
                            </div>
                            <div class="mb-4">
                                <label for="" class="mb-1">Вид налогообложения <span class="text-danger">*</span></label>
                                <select name="type_taxation_id" id="" class="form-select select-type-taxation">
                                    <option value="0" disabled selected><?php echo $client['type_taxation_id'];?></option>
                                    <?php foreach ($listTypesTaxation as $typeTaxation){ ?>
                                        <option value="<?php echo $typeTaxation["name"]; ?>"><?php echo $typeTaxation['name']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="mb-4">
                                <label for="" class="mb-1">Формат работа <span class="text-danger">*</span></label>
                                <select name="format_work" id="" class="form-select select-type-taxation">
                                    <option value="0" disabled selected>Формат работы</option>
                                    <option value="Сканы счет/акт" <?php if($client['format_work'] == 'Сканы счет/акт') echo 'selected'; ?>>
                                        Сканы счет/акт
                                    </option>
                                    <option value="Счет/акт + фото ТСД" <?php if($client['format_work'] == 'Счет/акт + фото ТСД') echo 'selected'; ?>>
                                        Счет/акт + фото ТСД
                                    </option>
                                    <option value="Счет/акт + сканы ТСД" <?php if($client['format_work'] == 'Счет/акт + сканы ТСД') echo 'selected'; ?>>
                                        Счет/акт + сканы ТСД
                                    </option>

                                    <option value="ЭДО" <?php if($client['format_work'] == 'ЭДО') echo 'selected'; ?>>
                                        ЭДО
                                    </option>
                                    <option value="Оригиналы" <?php if($client['format_work'] == 'Оригиналы') echo 'selected'; ?>>
                                        Оригиналы
                                    </option>
                                    <option value="Квиток" <?php if($client['format_work'] == 'Квиток') echo 'selected'; ?>>
                                        Квиток
                                    </option>
                                </select>
                            </div>
                            <div class="mb-4">
                                <label for="" class="mb-1">Название <span class="text-danger">*</span></label>
                                <input type="text" name="name" placeholder="Название" class="form-control form-control-solid" value="<?php echo str_replace('"','', $client['name']);?>" required>
                            </div>
                            <div class="mb-4">
                                <label for="" class="mb-1">ИНН <span class="text-danger">*</span></label>
                                <input type="number" name="inn" placeholder="ИНН" class="form-control form-control-solid" value="<?php echo $client['inn'];?>" required>
                            </div>
                            <div class="mb-4">
                                <label for="" class="mb-1">Юридический адрес <span class="text-danger">*</span></label>
                                <textarea name="legal_address" placeholder="Адрес" class="form-control form-control-solid" required><?php echo $client['legal_address'];?></textarea>
                            </div>
                        </div>
                        <div class="col">
                            <h5 class="mb-3">
                                Контактное лицо
                            </h5>
                            <div class="mb-4">
                                <label for="" class="mb-1">ФИО <span class="text-danger">*</span></label>
                                <input type="text" name="full_name" placeholder="ФИО" class="form-control form-control-solid"
                                       value="<?php echo $client['full_name']; ?>" required>
                            </div>
                            <div class="mb-4">
                                <label for="" class="mb-1">Должность <span class="text-danger">*</span></label>
                                <input type="text" name="job_title" placeholder="Должность" value="<?php echo $client['job_title']; ?>"
                                       class="form-control form-control-solid" required>
                            </div>
                            <div class="mb-4">
                                <label for="" class="mb-1">Телефон, код в АТИ, конт. лицо, почта <span class="text-danger">*</span></label>
                                <input type="text" name="phone" id="client-phone-input" value="<?php echo $client['phone']; ?>"
                                       placeholder="8 ()--" class="form-control form-control-solid client-phone-input" required value="8 ()--">
                            </div>
                            <div class="mb-4">
                                <label for="" class="mb-1">Почта <span class="text-danger">*</span></label>
                                <input type="email" name="email" id="client-email-input" placeholder="Почта" value="<?php echo $client['email']; ?>"
                                       class="form-control form-control-solid client-phone-input" required>
                            </div>
                            <div class="mb-4">
                                <label for="" class="mb-1">Процент</label>
                                <select name="percent" id="" class="form-select">
                                    <option value="0" <?php if($client['percent'] == 0) echo 'selected'; ?>>0 %</option>
                                    <option value="20" <?php if($client['percent'] == 20) echo 'selected'; ?>>20 %</option>
                                    <option value="22" <?php if($client['percent'] == 22) echo 'selected'; ?>>22 %</option>
                                    <option value="25" <?php if($client['percent'] == 25) echo 'selected'; ?>>25 %</option>
                                </select>
                            </div>
                            <div class="mb-4">
                                <label for="" class="mb-1">Отсрочка платежа</label>
                                <input name="payment-deferment" id="" type="text" class="form-control" value="<?php echo $client['payment_deferment']; ?>">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отменить</button>
                <button type="button" class="btn btn-primary" id="btn-edit-client-from">Сохранить</button>
            </div>
        </div>
    </div>
</div>

<div class="modal modal-add-application fade" id="modalAddClient" tabindex="-1" aria-labelledby="modalAddClient" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="min-width: 700px">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title title-base" id="exampleModalLabel">Добавление контактного лица</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
            </div>
            <div class="modal-body">
                <form action="/application/ajax/add-contact-face" method="post" id="form-add-contact-face-from" class="form-add-client">
                    <div class="row">
                        <div class="col">
                            <h5 class="mb-3">
                                Контактное лицо
                            </h5>
                            <input class="d-none" name="client_id" value="<?php echo $client['id'];?>">
                            <div class="mb-4">
                                <label for="" class="mb-1">ФИО <span class="text-danger">*</span></label>
                                <input type="text" name="name" placeholder="ФИО" class="form-control form-control-solid" required>
                            </div>
                            <div class="mb-4">
                                <label for="" class="mb-1">Должность</label>
                                <input type="text" name="job" placeholder="Должность" class="form-control form-control-solid">
                            </div>
                            <div class="mb-4">
                                <label for="" class="mb-1">Телефон <span class="text-danger">*</span></label>
                                <input type="text" name="phone" id="client-phone-input" placeholder="8 ()--" class="form-control form-control-solid client-phone-input" required value="8 ()--">
                            </div>
                            <div class="mb-4">
                                <label for="" class="mb-1">Код АТИ</label>
                                <input type="text" name="ati" id="client-ati-input" placeholder="Код АТИ" class="form-control form-control-solid client-phone-input">
                            </div>
                            <div class="mb-4">
                                <label for="" class="mb-1">Почта <span class="text-danger">*</span></label>
                                <input type="email" name="email" id="client-email-input" placeholder="Почта" class="form-control form-control-solid client-phone-input" required>
                            </div>
                            

                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отменить</button>
                <button type="button" class="btn btn-primary" id="btn-add-contact-face-from">Добавить</button>
            </div>
        </div>
    </div>
</div>

<div class="modal modal-add-application fade" id="modalChangeManager" tabindex="-1" aria-labelledby="modalChangeManager" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title title-base">Выберите менеджера</h3>

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
            </div>
            <div class="modal-body">
                <div class="modal-comment-text" id="modal-comment-text">
                    <label for="select-change-manager" class="mb-2">Менеджеры</label>
                    <select multiple id="select-change-client-manager" class="form-select js-chosen"
                            data-placeholder="Выберите менеджера" data-id-client="<?php echo $client['id']; ?>">
                        <?php foreach ($listManager as $manager): ?>
                            <option value="<?php echo $manager->id(); ?>" <?php foreach ($client['managersAccess'] as $managerClient){
                                if($managerClient['id'] == $manager->id()){
                                    echo 'selected';
                                    break;
                                }
                            } ?>>
                                <?php echo $manager->FIO(); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                <button type="button" class="btn btn-primary" id="btn-change-client-manager">Сохранить</button>
            </div>
        </div>
    </div>
</div>
<script>
    $('#changeInWork').click(function(){
        let idClient = $(this).data('id-client');
        $.ajax({
            method: 'POST',
            url: '/base/client/ajax/change-in-work',
            data: {idClient: idClient},
            success: function(response){
                document.location.reload();
            }
        }) 
    });
    $(document).ready(function(){
        <?php if($controller->auth->user()->id() == 55): ?>
            $('.menu-item-click[data-tab-app="#tab-4"]').trigger('click');
            $('.card__content-main-harakter_gruza').addClass('d-none');
        <?php endif; ?>
    });
</script>
<script>
    $('.add-comment-input').click(function (){
        $('.comment__add-input').has(this).find('.comment__buttons').addClass('active')
    });
    $('body').on('click','.js-change-important', function () {
            let $this = $(this);

            let id = $this.data('id-comment');

            $.ajax({
                url:'/base/client/ajax/change-important-comment',
                method: 'POST',
                data: {id: id},
                success: function (response) {
                    let data = JSON.parse(response);

                    if(data['result']){
                        $('.comment-item').has($this).find('.icon-important-comment').toggleClass('d-none');
                    }
                }
        });

    });

    $('body').on('click', '.js-edit-comment', function () {

            let id = $(this).data('id-comment');

            let comment = $('.comment-item').has(this).find('.text-comment').text();

            $('#comment__edit-input').data('id-comment', id);

            $('#edit-comment-input').val(comment);

            $('#comment__edit-input').removeClass('d-none');
            $('#comment__add-input').addClass('d-none');
            $('#edit-comment-input').trigger('focus');

        });

    loadComment();
        function loadComment(){
            $.ajax({
                url: '/base/client/ajax/load-comment',
                method: 'POST',
                data: {id_client: <?php echo $client['id']; ?>},
                success: function (response) {
                    console.log(response)
                    let data = JSON.parse(response);
                    console.log(data);

                    let htmlDefaultComment = ``;
                    let htmlCarrierFilesComment = ``;
                    let htmlClientFilesComment = ``;

                    for(let i = 0; i < data.length; i++){
                        let importantHide = 'd-none';
                        if(data[i]['important'])
                            importantHide = '';
                        let btn = `<div class="btn-comment"><div class="dropdown">
                                    <div class="dropdown-toggle dropdown-toggle-without-arrow" data-bs-toggle="dropdown" aria-expanded="false">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path opacity="0.3" d="M17 2H7C4.23858 2 2 4.23858 2 7V17C2 19.7614 4.23858 22 7 22H17C19.7614 22 22 19.7614 22 17V7C22 4.23858 19.7614 2 17 2Z" fill="#B5B5C3"></path>
                                            <path d="M13 12C13 11.4477 12.5523 11 12 11C11.4477 11 11 11.4477 11 12C11 12.5523 11.4477 13 12 13C12.5523 13 13 12.5523 13 12Z" fill="#B5B5C3"></path>
                                            <path d="M13 16C13 15.4477 12.5523 15 12 15C11.4477 15 11 15.4477 11 16C11 16.5523 11.4477 17 12 17C12.5523 17 13 16.5523 13 16Z" fill="#B5B5C3"></path>
                                            <path d="M13 8C13 7.44772 12.5523 7 12 7C11.4477 7 11 7.44772 11 8C11 8.55228 11.4477 9 12 9C12.5523 9 13 8.55228 13 8Z" fill="#B5B5C3"></path>
                                        </svg>
                                    </div>
                                    <ul class="dropdown-menu dropdown-menu-theme">
                                        <li><div class="dropdown-item js-delete-comment" data-id-comment="${data[i]['id']}">Удалить</div></li>
                                        <li><div class="dropdown-item js-edit-comment" data-id-comment="${data[i]['id']}">Редактировать</div></li>
                                        <li><div class="dropdown-item js-change-important" data-id-comment="${data[i]['id']}">Сделать важным</div></li>
                                    </ul>
                                </div></div>`;

                        if(! data[i]['edit_access'])
                            btn = '';

                            htmlDefaultComment += `
                            <div class="w-100 d-flex comment-item align-items-start">
                                <div class="icon-important-comment ${importantHide}">
                                    <i class="bi bi-exclamation-circle text-danger"></i>
                                </div>
                                <img src="${data[i]['user_data']['avatar']}" alt="avatar" class="avatar">
                                <div class="mt-1">
                                    <div class="head-comment">
                                        ${data[i]['user_data']['name']}
                                        <span class="small">${data[i]['user_data']['role']}</span>
                                    </div>
                                    <span class="date-comment">
                                        ${data[i]['date_time']}
                                    </span>
                                    <p class="text-comment">${data[i]['comment']}</p>
                                </div>
                                ${btn}
                            </div>`;
                        
                    }
                    console.log(htmlDefaultComment)
                    $('.comments-list').html(htmlDefaultComment);
                }
            })
        }

        $('.close-input-comment').click(function () {
            let container = $('.comment__add-input').has(this);
            container.find('.add-comment-input').val('');
            container.find('.comment__buttons').removeClass('active');

        })

        $('.add-comment-button').click(function () {
            addComment($(this));
        });

        $('.add-comment-input').keydown(function (e) {
            if (e.key === 'Enter') {
                e.preventDefault(); // чтобы не срабатывал перенос строки
                let container = $(this).closest('.comment__add-input');
                addComment(container.find('.add-comment-button'));
            }
        });

    function addComment(button) {
        let container = $('.comment__add-input').has(button);

        let clientId = container.data('id-client');

        let comment = container.find('.add-comment-input').val();
        container.find('.add-comment-input').val('');

        $.ajax({
            url: '/base/client/ajax/add-comment',
            method: 'POST',
            data: {id_client: clientId, comment: comment},
            success: function (response) {
                let data = JSON.parse(response);
                console.log(data);
                loadComment();
            }
        });
    }


        $('.edit-comment-button').click(function () {
            let container = $('.comment__add-input').has(this);

            let clientId = container.data('id-client');
            let commentId = container.data('id-comment');

            let comment = container.find('.add-comment-input').val();
            container.find('.add-comment-input').val('');

            $.ajax({
                url: '/base/client/ajax/edit-comment',
                method: 'POST',
                data: {id_client: clientId, comment: comment, comment_id: commentId},
                success: function (response) {
                    let data = JSON.parse(response);
                    if (data['result']) {
                        $('#comment__edit-input').addClass('d-none');
                        $('#comment__add-input').removeClass('d-none');
                        loadComment();
                    }
                }
            });
        });

        // обработчик Enter
        $('.add-comment-input').keydown(function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                let container = $(this).closest('.comment__add-input');

                // если редактируем → жмём edit-comment-button
                if (container.find('.edit-comment-button').length) {
                    container.find('.edit-comment-button').trigger('click');
                } 
                // если добавляем → жмём add-comment-button
                else if (container.find('.add-comment-button').length) {
                    container.find('.add-comment-button').trigger('click');
                }
            }
        });

</script>
<script>
    $('.list-item__type-document').on('dragover', function (e) {
        e.preventDefault();
        e.stopPropagation();
        $(this).addClass('active');
    });

    $('.list-item__type-document').on('dragleave', function (e) {
        e.preventDefault();
        e.stopPropagation();
        $(this).removeClass('active');
    });

    $('.list-item__type-document').on('drop', function (e) {
        // e.preventDefault();
        // e.stopPropagation();
        let $this = $(this);
        if(e.originalEvent.dataTransfer && e.originalEvent.dataTransfer.files.length) {
            console.log(e);
            e.preventDefault();
            e.stopPropagation();
            $('.quest__table-list-item').has($this).find('.upload_file_input').prop('files', e.originalEvent.dataTransfer.files);
            $('.quest__table-list-item').has(this).find('.upload_file_input').trigger('change');
        }

        $(this).removeClass('active');
    });
    $('#btn-change-client-manager').click(function () {
        let val = $('#select-change-client-manager').val();

        let idClient = $('#select-change-client-manager').data('id-client');

        $.ajax({
            url: '/client/ajax/change-client-manager',
            method: 'POST',
            data: {id: idClient, val: val},
            success: function (response) {
                let data = JSON.parse(response)

                if(data['result'])
                    document.location.reload();
            }
        })
    })
    Editor_client_character.model.document.on( 'change', () => {
        let input = "client_character";
        let value = Editor_client_character.getData();
        $.ajax({
            url: "ajax/changeClientInfo",
            method: "POST",
            data: {input: input, value: value, client_id: <?php echo $client['id'];?>},
            success: function (response){
                console.log(response);
            }
        });
    } );

    Editor_accountant_text.model.document.on( 'change', () => {
        let input = "accountant_text";
        let value = Editor_accountant_text.getData();
        $.ajax({
            url: "ajax/changeClientInfo",
            method: "POST",
            data: {input: input, value: value, client_id: <?php echo $client['id'];?>},
            success: function (response){
                console.log(response);
            }
        });
    } );

    Editor_quality_department_text.model.document.on( 'change', () => {
        let input = "quality_department_text";
        let value = Editor_quality_department_text.getData();
        $.ajax({
            url: "ajax/changeClientInfo",
            method: "POST",
            data: {input: input, value: value, client_id: <?php echo $client['id'];?>},
            success: function (response){
                console.log(response);
            }
        });
    } );

    Editor_lawyers_text.model.document.on( 'change', () => {
        let input = "lawyers_text";
        let value = Editor_lawyers_text.getData();
        $.ajax({
            url: "ajax/changeClientInfo",
            method: "POST",
            data: {input: input, value: value, client_id: <?php echo $client['id'];?>},
            success: function (response){
                console.log(response);
            }
        });
    } );

    $('#client-phone-input').inputmask("8 (999) 999-99-99");
    $('.ajax-input').on('change', function (){
        let input = $(this).attr('name');
        let value = $(this).val();
        $.ajax({
            url: "ajax/changeClientInfo",
            method: "POST",
            data: {input: input, value: value, client_id: <?php echo $client['id'];?>},
            success: function (response){
                console.log(response);
            }
        });
    });

    function deleteContactFace(id){
        $.ajax({
            url: "/application/ajax/delete-contact-face",
            method: "POST",
            data: {id: id},
            success: function (response){
                if(response === "Success"){
                    $(".contact_face_"+id).remove();
                }
                else{
                    alert("Не удалось удалить!");
                }
            }
        });
    }
    $('#btn-edit-client-from').click(function () {
        $('#form-edit-client-from').trigger('submit');
    });
    $('#form-edit-client-from').submit(function (e) {
        e.preventDefault();

        let form = $(this).serializeArray();
        console.log(form);

        $.ajax({
            method: "POST",
            url: "/ajax/editClient",
            data: form,
            success: function (response){
                if (response === "Success"){
                    document.location.reload();
                }
            }
        });
    });


    $('#btn-add-contact-face-from').click(function () {
        $('#form-add-contact-face-from').trigger('submit');
    });
    $('#form-add-contact-face-from').submit(function (e) {
        e.preventDefault();

        let form = $(this).serializeArray();
        console.log(form);
        $.ajax({
            url: '/application/ajax/add-contact-face',
            method: 'POST',
            data: form,
            success: function (response){
                if (response !== "Error"){
                    let client_array = JSON.parse(response);
                    console.log(client_array);
                    $('#modalAddClient').modal("hide");

                    $(".quest__table").append(`<div class="quest__table-list quest__table-tasks contact_face_${client_array["id"]}">
                                    <div class="quest__table-task">
                                        <div class="quest__table-task-name">
                                            <div class="name">
                                                ${client_array["name"]}
                                            </div>
                                        </div>
                                        <div class="quest__table-task-deadline">
                                            ${client_array["job"]}
                                        </div>
                                        <div class="quest__table-task-deadline">
                                            ${client_array["phone"]}
                                        </div>
                                        <div class="quest__table-task-deadline" style="width: 325px;">${client_array["email"]}
                                        </div>
                                        <div class="quest__table-task-status-btns" style="width: auto;">
                                            <div class="settings col-2 p-0 row justify-content-end">
                                                <div class="dropdown">
                                                    <div class="dropdown-toggle dropdown-toggle-without-arrow" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path opacity="0.3" d="M17 2H7C4.23858 2 2 4.23858 2 7V17C2 19.7614 4.23858 22 7 22H17C19.7614 22 22 19.7614 22 17V7C22 4.23858 19.7614 2 17 2Z" fill="#B5B5C3"></path>
                                                            <path d="M13 12C13 11.4477 12.5523 11 12 11C11.4477 11 11 11.4477 11 12C11 12.5523 11.4477 13 12 13C12.5523 13 13 12.5523 13 12Z" fill="#B5B5C3"></path>
                                                            <path d="M13 16C13 15.4477 12.5523 15 12 15C11.4477 15 11 15.4477 11 16C11 16.5523 11.4477 17 12 17C12.5523 17 13 16.5523 13 16Z" fill="#B5B5C3"></path>
                                                            <path d="M13 8C13 7.44772 12.5523 7 12 7C11.4477 7 11 7.44772 11 8C11 8.55228 11.4477 9 12 9C12.5523 9 13 8.55228 13 8Z" fill="#B5B5C3"></path>
                                                        </svg>
                                                    </div>
                                                    <ul class="dropdown-menu dropdown-menu-theme">
                                                        <li><div class="dropdown-item js-edit-fines">Редактировать</div></li>
                                                        <li><div class="dropdown-item js-delete-fines" onclick="deleteContactFace(${client_array["id"]});">Удалить</div></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>`);
                    $('.client-info #client_info_inn').html(client_array['inn']);
                    $('.client-info #client_info_legal_address').html(client_array['legal_address']);

                    $('.client-info #client_info_info').html(client_array['phone']);

                    $('#modalClientEdit').modal('hide');
                }
            }
        });
    });




    $('.js-link-tab-change').click(function () {
        let tabId = $(this).data('tab-app');
        let container = $(this).data('container');
        let prefix = $(this).data('prefix-tab');
        $(prefix + '-js-link-tab-change').removeClass('active');
        $(this).addClass('active');
        $(container).removeClass('active');
        $(tabId).addClass('active');
    });
    function addTask(applicationId){
        $.ajax({
            url: "/application/ajax/addTask",
            method: "POST",
            data: {application_id: applicationId},
            success: function (response){
                console.log(response);
                if (response !== "Error"){
                    let data = JSON.parse(response);
                    console.log(data);

                    let deadline = ""
                    if (data['status'] === '0'){
                        console.log("status = 0");
                        deadline = "<span class='task-date_completion orange'> Не сделано </span>";
                    }

                    $('.quest__table').append(`
                        <div class="quest__table-list quest__table-tasks">
                                <div class="quest__table-task">
                                    <div class="quest__table-task-name">
                                        <div class="name">
                                            ${data['name']}
                                        </div>
                                        <div class="date">
                                            ${data['create_datetime']}
                                        </div>
                                    </div>
                                    <div class="quest__table-task-deadline">
                                        ${data['deadline']}
                                    </div>
                                    <div class="quest__table-task-date_completion">
                                        <span class='task-date_completion orange'> Не сделано </span>
                                    </div>
                                    <div class="quest__table-task-comment">
                                        <span onclick="openCommentModal(${data['id']});" data-bs-toggle="modal" data-bs-target="#modalComment">ПРОСЬБА ...</span>
                                    </div>
                                    <div class="quest__table-task-full-comment d-none task-comment_${data['id']}">
                                        ${data['comment']}
                                    </div>
                                    <div class="quest__table-task-executor d-flex">
                                        <div class="avatar">
                                            <img src="/assets/img/empty_avatar.png" alt="">
                                        </div>
                                        <div class="name">
                                            Тамара Б .
                                            <br>
                                            <span class="gray">Бухгалтер</span>
                                        </div>
                                    </div>
                                    <div class="quest__table-task-status">
                                        <select name="task_status" id="status" value="Сделать"
                                                class="select-status-light status-type blue select2-hidden-accessible"
                                                tabindex="-1" aria-hidden="true" data-select2-id="select2-data-status">
                                            <option value="В работе" selected="" data-select2-id="select2-data-69-ubkx">В работе</option>
                                            <option value="В пути">В пути</option>
                                            <option value="Выгрузился">Выгрузился</option>
                                            <option value="Завершена (оплата)">Завершена (оплата)</option>
                                            <option value="Завершена (док.)">Завершена (док.)</option>
                                            <option value="Завершена">Завершена</option>
                                        </select>
                                    </div>
                                    <div class="quest__table-task-status-btns">
                                        <div class="settings col-2 p-0 row justify-content-end">
                                            <div class="dropdown">
                                                <div class="dropdown-toggle dropdown-toggle-without-arrow" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path opacity="0.3" d="M17 2H7C4.23858 2 2 4.23858 2 7V17C2 19.7614 4.23858 22 7 22H17C19.7614 22 22 19.7614 22 17V7C22 4.23858 19.7614 2 17 2Z" fill="#B5B5C3"></path>
                                                        <path d="M13 12C13 11.4477 12.5523 11 12 11C11.4477 11 11 11.4477 11 12C11 12.5523 11.4477 13 12 13C12.5523 13 13 12.5523 13 12Z" fill="#B5B5C3"></path>
                                                        <path d="M13 16C13 15.4477 12.5523 15 12 15C11.4477 15 11 15.4477 11 16C11 16.5523 11.4477 17 12 17C12.5523 17 13 16.5523 13 16Z" fill="#B5B5C3"></path>
                                                        <path d="M13 8C13 7.44772 12.5523 7 12 7C11.4477 7 11 7.44772 11 8C11 8.55228 11.4477 9 12 9C12.5523 9 13 8.55228 13 8Z" fill="#B5B5C3"></path>
                                                    </svg>
                                                </div>
                                                <ul class="dropdown-menu dropdown-menu-theme">
                                                    <li><div class="dropdown-item js-edit-fines">Редактировать</div></li>
                                                    <li><div class="dropdown-item js-delete-fines">Удалить</div></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>`);

                }
            }
        });
    }

    function openCommentModal(id){
        $('#modal-comment-text').html($('.task-comment_'+id).html());
    }

    function deleteFile(id){
        $.ajax({
            url: "/application/ajax/deleteFile",
            method: "POST",
            data: {id: id},
            success: function (response){
                if (response === "Success"){
                    if ($('.document-row_'+id).parent('.list-item__document-rows').children().length < 2){
                        $('.document-row_'+id).parent('.list-item__document-rows').append(`<div class="list-item__document-row">
                            <div class="list-item__file-name">

                            </div>
                            <div class="data-and-btn m-0">
                                <span class="file-data-none">Не загружено</span>
                            </div>
                        </div>`);

                        $('.document-row_'+id).parent('.list-item__document-rows').parent('.quest__table-list-item').children('.form-check').children('form').append(`
                            <button type="button" class="btn doc-upload-btn">
                                <i class="bi bi-upload"></i>
                            </button>
                        `);

                        $('input[type=file]').val('');


                        setupTriggerInputFile();
                    }

                    $('.document-row_'+id).remove();
                }
                else{
                    alert("Ошибка! Файл не был удален!");
                }
            }
        });
    }

    function setupTriggerInputFile(){
        $('.doc-upload-btn').on('click', function (){
            $(this).prev('input').trigger('click');
        });

    }

    setupTriggerInputFile();

    for (let i = 0; i < 18; i++){
        $('#input-file'+i).on('change', function (){
            $('#form_file'+i).trigger('submit');
        });
    }

    $('.form-upload-file').submit(function (e) {
        e.preventDefault();

        let doc_id = $(this).attr('id').replace('form_file', '');

        let form = $(this).serializeArray();

        var formData = new FormData();

        let fileInput = $(this).children('input');
        for (let i = 0; i< fileInput[0].files.length; i++){
            formData.append('file_'+i, fileInput[0].files[i]);

        }

        formData.append('client_id', <?php echo $client['id'];?>);
        formData.append('doc_id', doc_id);

        let this_ = $(this);

        $.ajax({
            url: '/application/ajax/uploadFiles',
            method: 'POST',
            processData: false,
            contentType: false,
            data: formData,
            success: function (data_json){
                console.log(data_json)
                let data = JSON.parse(data_json);

                this_.parent().prev('.list-item__document-rows').children().remove('.list-item__document-row');
                this_.children().remove('.doc-upload-btn');

                for (let i = 0; i < data.length; i++){
                    this_.parent().prev('.list-item__document-rows').append(`
                         <div class="list-item__document-row document-row_${data[i]['id']}">
                             <div class="list-item__file-name">
                                 <a href="${data[i]['link']}">${data[i]['name']}</a>
                             </div>
                             <div class="data-and-btn m-0 align-items-center d-flex gap-2">
                                 <span class="data-date">${data[i]['date']}</span>
                                 <a href="${data[i]['link']}" download="${data[i]['name']}" class="list-item__download-file btn"><i class="bi bi-cloud-download"></i></a>
                                 <button class="list-item__delete-file-btn btn" data-file-id = "${data[i]['id']}" onclick="deleteFile(${data[i]['id']});">
                                     <i class="bi bi-file-earmark-x"></i>
                                 </button>
                             </div>
                         </div>`);
                 }
            }
        });
    });
</script>
</body>
</html>