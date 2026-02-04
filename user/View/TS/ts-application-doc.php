<?php
/**
 * @var App\User\Contoller\Common\ApplicationController $controller
 * @var App\Model\TSApplication\TSApplication $TSApplication
 */
$documentsComment = $TSApplication->getDocumentsComment();
$TSApplicationData = $TSApplication->get();
$documents = $TSApplication->getFiles();
?>
    <div class="sub-title-doc" style="font-size: 1.5rem">
        Файлы
    </div>
    <div class="quest__table-header quest__table-header-doc d-flex">
        <span>№</span>
        <span>Тип документа</span>
        <span>Название</span>
        <span>Дата добавления</span>
    </div>
    <div class="quest__table-list-item list-item d-flex position-relative">
        <div class="list-item__number">1</div>
        <div class="list-item__type-document">
            Подписанная заявка от клиента (обязательное) (Логист)
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
            <form action="/ts/application/ajax/uploadFiles" method="post" id="form_file10" class="form-upload-file" enctype="multipart/form-data">
                <input type="file" class="d-none upload_file_input" id="input-file10" name="file-signed-application-client" multiple>
                <button type="button" class="btn doc-upload-btn">
                    <i class="bi bi-upload"></i>
                </button>
            </form>
        </div>
    </div>

    <div class="quest__table-list-item list-item d-flex position-relative">
        <div class="list-item__number">2.1</div>
        <div class="list-item__type-document">
            Фото ТТН/ТН/ЭР с выгрузки (обязательное) (Логист)
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
            <form action="/ts/application/ajax/uploadFiles" method="post" id="form_file2" class="form-upload-file" enctype="multipart/form-data">
                <input type="file" class="d-none upload_file_input" id="input-file2" name="file-ttn-carrier" multiple>
                <button type="button" class="btn doc-upload-btn">
                    <i class="bi bi-upload"></i>
                </button>
            </form>
        </div>
    </div>

    <div class="quest__table-list-item list-item d-flex position-relative">
        <div class="list-item__number">2.2</div>
        <div class="list-item__type-document">
            Сканы ТТН/ТН/ЭР (обязательное) (Бухгалтерия)
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
            <form action="/ts/application/ajax/uploadFiles" method="post" id="form_file18" class="form-upload-file" enctype="multipart/form-data">
                <input type="file" class="d-none upload_file_input" id="input-file18" name="file-ttn-carrier" multiple>
                <button type="button" class="btn doc-upload-btn">
                    <i class="bi bi-upload"></i>
                </button>
            </form>
        </div>
    </div>

    <div class="quest__table-list-item list-item d-flex">
        <div class="list-item__number">3</div>
        <div class="w-100">
            <label class="mb-2">Комментарий о полученных документах (оригиналы) для клиента (обязательное) (Бухгалтерия)</label>
            <input type="text" class="form-control application-comment-document"
                   data-id-application="<?php echo $TSApplicationData['id']; ?>" data-side="0"
                   data-name="Комментарий о полученных документах для клиента"
                   data-num-document="5-2"
                <?php foreach ($documentsComment as $comment){
                    if($comment['name'] === 'Комментарий о полученных документах для клиента') {
                        echo 'value="' . $comment['comment'] . '"';
                        break;
                    }
                } ?>
            >
        </div>
    </div>

    <div class="quest__table-list-item list-item d-flex position-relative">
        <div class="list-item__number">4</div>
        <div class="list-item__type-document">
            Счета для клиента (обязательное) (Бухгалтерия)
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
            <form action="/ts/application/ajax/uploadFiles" method="post" id="form_file13" class="form-upload-file" enctype="multipart/form-data">
                <input type="file" class="d-none upload_file_input" id="input-file13" name="file-invoices-client" multiple>
                <button type="button" class="btn doc-upload-btn">
                    <i class="bi bi-upload"></i>
                </button>
            </form>
        </div>
    </div>

    <div class="quest__table-list-item list-item d-flex position-relative">
    <div class="list-item__number">5</div>
    <div class="list-item__type-document">
        АКТ/УПД для клиента (обязательное) (Бухгалтерия)
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
        <form action="/ts/application/ajax/uploadFiles" method="post" id="form_file14" class="form-upload-file" enctype="multipart/form-data">
            <input type="file" class="d-none upload_file_input" id="input-file14" name="file-act-client" multiple>
            <button type="button" class="btn doc-upload-btn">
                <i class="bi bi-upload"></i>
            </button>
        </form>
    </div>
</div>

    <div class="quest__table-list-item list-item d-flex position-relative">
        <div class="list-item__number">6</div>
        <div class="list-item__type-document">
            Отметка об отправке оригиналов (счет, ТТН, АКТ/УПД) клиенту (обязательное) (Бухгалтерия)
        </div>
        <div class="list-item__document-rows list-item__document-rows1" style="visibility: hidden">
            <div class="list-item__document-row empty">
                <div class="list-item__file-name">

                </div>
                <div class="data-and-btn m-0">
                    <span class="file-data-none">Не загружено</span>
                </div>
            </div>
        </div>
        <div class="form-check form-check-custom form-check-solid form-check-sm align-items-start ml-auto" style="visibility: hidden">
            <form action="/ts/application/ajax/uploadFiles" method="post" id="form_file15" class="form-upload-file" enctype="multipart/form-data">
                <input type="file" class="d-none upload_file_input" id="input-file15" name="file-mark-client" multiple>
                <button type="button" class="btn doc-upload-btn">
                    <i class="bi bi-upload"></i>
                </button>
            </form>
        </div>
    </div>

    <div class="quest__table-list-item d-flex list-item">
        <div class="list-item__number">6.1</div>
        <div class="w-100">
            <label class="mb-2">Комментарий об отправленных документах (обязательное) (Бухгалтерия)</label>
            <input type="text" class="form-control application-comment-document"
                   data-id-application="<?php echo $TSApplicationData['id']; ?>" data-side="1"
                   data-name="Комментарий об отправленных документах"
                   data-num-document="6-1"
                <?php foreach ($documentsComment as $comment){
                    if($comment['name'] === 'Комментарий об отправленных документах') {
                        echo 'value="' . $comment['comment'] . '"';
                        break;
                    }
                } ?>
            >
        </div>
    </div>

    <div class="quest__table-list-item d-flex list-item">
        <div class="list-item__number">6.2</div>
        <div class="w-100">
            <label class="mb-2">Трек-номер отправки (обязательное) (Бухгалтерия)</label>
            <input type="text" class="form-control application-comment-document"
                   data-id-application="<?php echo $TSApplicationData['id']; ?>" data-side="1"
                   data-name="Трек-номер отправки"
                   data-num-document="6-2"
                <?php foreach ($documentsComment as $comment){
                    if($comment['name'] === 'Трек-номер отправки') {
                        echo 'value="' . $comment['comment'] . '"';
                        break;
                    }
                } ?>
            >
        </div>
    </div>

    <div class="quest__table-list-item list-item d-flex position-relative">
        <div class="list-item__number">6.3</div>
        <div class="list-item__type-document">
            Скриншот отправки (Бухгалтерия)
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
            <form action="/ts/application/ajax/uploadFiles" method="post" id="form_file19" class="form-upload-file" enctype="multipart/form-data">
                <input type="file" class="d-none upload_file_input" id="input-file19" name="file-payment-mark-client" multiple>
                <button type="button" class="btn doc-upload-btn">
                    <i class="bi bi-upload"></i>
                </button>
            </form>
        </div>
    </div>

<!--    <div class="quest__table-list-item list-item d-flex position-relative">-->
<!--        <div class="list-item__number">6.4</div>-->
<!--        <div class="list-item__type-document">-->
<!--            Отправка ТТН (Бухгалтерия)-->
<!--        </div>-->
<!--        <div class="list-item__document-rows list-item__document-rows1">-->
<!---->
<!--        </div>-->
<!--        <div class="form-check form-check-custom form-check-solid form-check-sm align-items-start ml-auto">-->
<!--            <input class="form-check-input" style="width: 2em;height: 2em;" type="checkbox" id="ttnSentCheckbox"-->
<!--                --><?php //if(!$controller->auth->user()->fullCRM()) echo 'disabled';  ?>
<!--                   data-id-app="--><?php //echo $TSApplicationData['id']; ?><!--"-->
<!--                --><?php //if($application->ttnSent) echo 'checked'; ?><!-- >-->
<!--        </div>-->
<!--    </div>-->

    <script>
        <?php if($controller->auth->user()->fullCRM()): ?>
        $('#ttnSentCheckbox').change(function (){
            if(confirm('Вы уверены что хотите изменить поле "Отправка ТНН" ?')){
                let id = $(this).data('id-app');
                let status = 0;
                if($(this).is(':checked'))
                    status = 1;
                console.log({idApp: id, type: 'sent', status: status})
                $.ajax({
                    method: 'POST',
                    url: '/application/ajax/change-ttn-status',
                    data: {idApp: id, type: 'sent', status: status}
                })
            }
        });
        <?php endif; ?>

        <?php
            foreach ($documents as $document) {?>
                $('#form_file<?php echo $document['document_id'];?>').parent().prev('.list-item__document-rows').children('.list-item__document-row.empty').remove();
                $('#form_file<?php echo $document['document_id'];?>').children('.doc-upload-btn').remove();
                $('#form_file<?php echo $document['document_id'];?>').parent().prev('.list-item__document-rows').append(`
                        <div class="list-item__document-row document-row_<?php echo $document['id'];?>">
                            <div class="list-item__file-name">
                                <a href="<?php echo $document['link'];?>"><?php echo $document['name'];?></a>
                            </div>
                            <div class="data-and-btn m-0 align-items-center d-flex gap-2">
                                <span class="data-date"><?php echo $document['date'];?></span>
                                <a href="<?php echo $document['link'];?>" download="<?php echo $document['name'];?>" class="list-item__download-file btn"><i class="bi bi-cloud-download"></i></a>
                                <button class="list-item__delete-file-btn btn" data-file-id = "<?php echo $document['id'];?>" onclick="deleteFile(<?php echo $document['id'];?>);">
                                    <i class="bi bi-file-earmark-x"></i>
                                </button>
                            </div>
                        </div>`);
            <?php }
        ?>
    </script>