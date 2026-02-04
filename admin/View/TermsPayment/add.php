<?php
/** @var App\User\Contoller\Common\HomeController $controller */
/** @var String $titlePage */
/** @var String $countElement */
/** @var array $listCarBrands */

?>

<?php $controller->view('Components/start'); ?>
<div class="p-4">
    <h3 class="mb-4"><?php echo $titlePage;?></h3>

    <form action="" method="post">
        <div class="mb-4">
            <label for="" class="mb-2">Название</label>
            <input type="text" class="form-control" name="name" required>
        </div>
        <div class="mb-4">
            <label for="" class="mb-2">Описание</label>
            <textarea name="description" id="description" cols="30" rows="10"></textarea>
        </div>
        <div class="mb-4">
            <label for="" class="mb-2">Перевозчик/Клиент</label>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="type-for" checked id="flexRadioDefault1" value="0" required>
                <label class="form-check-label" for="flexRadioDefault1">
                    Перевозчик
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="type-for" id="flexRadioDefault2" value="1">
                <label class="form-check-label" for="flexRadioDefault2">
                    Клиент
                </label>
            </div>
        </div>
        <div class="mb-4">
            <label for="" class="mb-2">Тип налогообложения</label>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="type-taxation" checked id="flexRadioDefault11" value="НДС" required>
                <label class="form-check-label" for="flexRadioDefault11">
                    НДС
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="type-taxation" id="flexRadioDefault12" value="Б/НДС">
                <label class="form-check-label" for="flexRadioDefault12">
                    Б/НДС
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="type-taxation" id="flexRadioDefault13" value="НАЛ">
                <label class="form-check-label" for="flexRadioDefault13">
                    НАЛ
                </label>
            </div>
        </div>
        <button class="btn btn-outline-success">Добавить</button>
    </form>
</div>



<script src="https://cdn.ckeditor.com/ckeditor5/37.1.0/super-build/ckeditor.js"></script>
<script>
    var editor= CKEDITOR.ClassicEditor.create(document.getElementById("description"), {
        toolbar: {
            items: [

                'heading', '|',
                'bold', 'italic', 'strikethrough', 'underline',  'removeFormat', '|',
                'bulletedList', 'numberedList', 'todoList', '|',
                'outdent', 'indent', '|',
                'undo', 'redo',
                '-',
                'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor', 'highlight', '|',
                'alignment', '|',
                'link',  'blockQuote', 'insertTable', 'codeBlock', 'htmlEmbed', '|',
            ],
            shouldNotGroupWhenFull: true
        },
        list: {
            properties: {
                styles: true,
                startIndex: true,
                reversed: true
            }
        },
        heading: {
            options: [
                { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
                { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
                { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' },
                { model: 'heading4', view: 'h4', title: 'Heading 4', class: 'ck-heading_heading4' },
                { model: 'heading5', view: 'h5', title: 'Heading 5', class: 'ck-heading_heading5' },
                { model: 'heading6', view: 'h6', title: 'Heading 6', class: 'ck-heading_heading6' }
            ]
        },
        placeholder: '',
        fontFamily: {
            options: [
                'default',
                'Arial, Helvetica, sans-serif',
                'Courier New, Courier, monospace',
                'Georgia, serif',
                'Lucida Sans Unicode, Lucida Grande, sans-serif',
                'Tahoma, Geneva, sans-serif',
                'Times New Roman, Times, serif',
                'Trebuchet MS, Helvetica, sans-serif',
                'Verdana, Geneva, sans-serif'
            ],
            supportAllValues: true
        },
        fontSize: {
            options: [ 10, 12, 14, 'default', 18, 20, 22 ],
            supportAllValues: true
        },
        htmlSupport: {
            allow: [
                {
                    name: /.*/,
                    attributes: true,
                    classes: true,
                    styles: true
                }
            ]
        },
        htmlEmbed: {
            showPreviews: true
        },
        link: {
            decorators: {
                addTargetToExternalLinks: true,
                defaultProtocol: 'https://',
                toggleDownloadable: {
                    mode: 'manual',
                    label: 'Downloadable',
                    attributes: {
                        download: 'file'
                    }
                }
            }
        },
        mention: {
            feeds: [
                {
                    marker: '@',
                    feed: [
                        '@apple', '@bears', '@brownie', '@cake', '@cake', '@candy', '@canes', '@chocolate', '@cookie', '@cotton', '@cream',
                        '@cupcake', '@danish', '@donut', '@dragée', '@fruitcake', '@gingerbread', '@gummi', '@ice', '@jelly-o',
                        '@liquorice', '@macaroon', '@marzipan', '@oat', '@pie', '@plum', '@pudding', '@sesame', '@snaps', '@soufflé',
                        '@sugar', '@sweet', '@topping', '@wafer'
                    ],
                    minimumCharacters: 1
                }
            ]
        },
        removePlugins: [
            'CKBox',
            'CKFinder',
            'EasyImage',
            'RealTimeCollaborativeComments',
            'RealTimeCollaborativeTrackChanges',
            'RealTimeCollaborativeRevisionHistory',
            'PresenceList',
            'Comments',
            'TrackChanges',
            'TrackChangesData',
            'RevisionHistory',
            'Pagination',
            'WProofreader',
            'MathType',
            'SlashCommand',
            'Template',
            'DocumentOutline',
            'FormatPainter',
            'TableOfContents'
        ]
    });
</script>
<?php $controller->view('Components/end'); ?>
