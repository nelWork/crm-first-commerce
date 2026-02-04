<?php
/** @var App\User\Contoller\Common\HomeController $controller */
/** @var String $titlePage */
/** @var array $listCarrier */


//dd($listApplication);

// todo доделать реестр, выгрузку в EXCEL
$controller->view('Components/head');
?>

<body>
<?php $controller->view('Components/header'); ?>

<div class="container container-spravka-page pt-4" >
    <style>
        .container-spravka-page{
            min-height: 70vh;
        }
        .modal {
            position: fixed;
            top: 0;
            z-index: 100;
            left: 0;
            transform: unset;
            background: #00000038;
            border-radius: 10px;
            padding: 0;
            overflow: scroll !important;
        }
        .modal-backdrop.show{
            display: none !important;
        }
        .year-picker{
            font-size: 1.5rem;
        }
        .modal-dialog{
            max-width: 40%;
        }
        .btn-create,.btn-choose{
            border: 1px solid;
            margin-right: 1rem;
            padding: 0.5rem 1rem;
        }
        /*.js-tr-requisites{
            border: 1px solid;
        }*/
        .js-tr-requisites{
            cursor: pointer;
        }
        .js-tr-requisites.active td{
            background-color: yellow;
        }
    </style>
    <h1 class="text-center mb-5">Реестр</h1>

    <form action="" id="spravka-form" class="mb-5">
        <label for="" class="mb-2">Выберите перевозчика</label>

        <select name="carrier" id="carrier-select" class="form-select mb-4 js-chosen" data-placeholder="Выберите перевозчика">
            <!-- <option value="">Выберите перевозчика</option> -->
            <option value=""></option>

            <?php foreach ($listCarrier as $carrier): ?>
                <option value="<?php echo $carrier['id']; ?>"><?php echo $carrier['name']; ?></option>
            <?php endforeach; ?>
        </select>
        <!-- <label for="" class="mt-4 mb-2">Реквизиты</label>
        <input type="text" class="form-control mb-4" id="requisites-input" name="requisites"> -->

        <!-- <button class="btn btn-success" id="btn-save-requisites">Сохранить</button> -->

    </form>


    <div class="bank-details-container d-none">
        <div class="mb-4">
            <h4>Контрагент: <span id="span-counterparty"></span></h4>
            <h4>ИНН/КПП: <span id="span-INN"></span></h4>
        </div>

        <h3 class="mb-3">Банковские реквизиты</h3>

        <div class="btn-container mb-4">
            <button class="btn-create" id="btn-create" data-bs-toggle="modal" data-bs-target="#modalSettingTable">Добавить</button>
            <button class="btn-choose" id="btn-choose-main" disabled>Использовать как основной ✓</button>
        </div>
        <table class="table table-striped table-bordered" data-num-active-tr="-1">
            <thead>
            <tr>
                <th style="text-align: center;">✓</th>
                <th>Наименование банка</th>
                <th>БИК</th>
                <th>Корр. счет</th>
                <th>Номер счета</th>
            </tr>
            </thead>
            <tbody id="tbody-requisites">
            </tbody>
        </table>
    </div>

    <div id="loader" class="d-none">
        <div class="d-flex align-items-center">
            <div class="spinner-border" aria-hidden="true" style="width: 3rem; height: 3rem;"></div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalSettingTable" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalSettingTableLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modalSettingTable">Добавить банковские реквизиты</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" id="add-bank-details">
                    <input type="hidden" id="input-id-carrier" name="id">
                    <div class="mb-3">
                        <label for="" class="mb-2">Наименование банка</label>
                        <input type="text" class="form-control" name="bank_name">
                    </div>
                    <div class="mb-3">
                        <label for="" class="mb-2">БИК</label>
                        <input type="text" class="form-control" name="bik">
                    </div>
                    <div class="mb-3">
                        <label for="" class="mb-2">Корр. счет</label>
                        <input type="text" class="form-control" name="сorrespondent_account">
                    </div>
                    <div class="mb-3">
                        <label for="" class="mb-2">Номер счета</label>
                        <input type="text" class="form-control" name="account_number">
                    </div>
                    <button class="btn btn-success w-100" id="btn-add-bank-details">Добавить</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {

        $('#add-bank-details').submit(function(e){
            e.preventDefault();
            $('#loader').removeClass('d-none');
            $('.bank-details-container').addClass('d-none');
            let data = $(this).serializeArray();
            $('#btn-add-bank-details').attr('disabled',true)

            console.log(data)
            $.ajax({
                url: '/registry/ajax/add-carrier-detail',
                method: 'POST',
                data: data,
                success: function(response){

                    $('#loader').addClass('d-none');
                    $('.bank-details-container').removeClass('d-none');
                    console.log(response)
                    $('#btn-add-bank-details').attr('disabled',false);
                    $('.btn-close').trigger('click');

                    $('#carrier-select').trigger('change');
                }
            })

        });
        $('#carrier-select').change(function(){
            let idCarrier = $(this).val();
            $('.bank-details-container').addClass('d-none');
            $('#loader').removeClass('d-none');
            $('#requisites-input').attr('disabled',true);
            $('#btn-save-requisites').attr('disabled',true);
            $.ajax({
                url: '/registry/ajax/get-carrier-detail',
                method: 'POST',
                data: {id: idCarrier},
                success: function(response){
                    console.log(response)
                    $('#requisites-input').attr('disabled',false);
                    $('#btn-save-requisites').attr('disabled',false);
                    $('.bank-details-container').removeClass('d-none')
                    // $('#requisites-input').val(response);
                    let data = JSON.parse(response);
                    console.log(data)
                    $('#span-counterparty').text(data.title);
                    $('#span-INN').text(data.inn);
                    $('#input-id-carrier').val(idCarrier);
                    let tbodyHTML = ``;
                    $('#loader').addClass('d-none');
                    if(data.requisites == null){

                    }
                    else{
                        for(let i = 0; i < data.requisites.length; i++){
                            let active = ``;

                            if(data.requisites[i]['is_main']){
                                active = '✓';
                                tbodyHTML =    `<tr class="js-tr-requisites" data-num-tr="${data.requisites[i]['id']}">
                                                    <td style="font-weight: bold;text-align:center">${active}</td>
                                                    <td>${data.requisites[i]['bank_name']}</td>
                                                    <td>${data.requisites[i]['bik']}</td>
                                                    <td>${data.requisites[i]['corr_account']}</td>
                                                    <td>${data.requisites[i]['account_number']}</td>
                                                </tr>` + tbodyHTML;

                            }
                            else{
                                tbodyHTML +=    `<tr class="js-tr-requisites" data-num-tr="${data.requisites[i]['id']}">
                                                    <td style="font-weight: bold;text-align:center">${active}</td>
                                                    <td>${data.requisites[i]['bank_name']}</td>
                                                    <td>${data.requisites[i]['bik']}</td>
                                                    <td>${data.requisites[i]['corr_account']}</td>
                                                    <td>${data.requisites[i]['account_number']}</td>
                                                </tr>`;
                            }

                        }
                    }

                    $('#tbody-requisites').html(tbodyHTML);

                }
            })
        });

        $('body').on('click','.js-tr-requisites',function(){
            $('.js-tr-requisites').removeClass('active');
            $('#btn-choose-main').attr('disabled',false);

            let numActive = $(this).data('num-tr');

            $('.table').data('num-active-tr',numActive);
            $(this).addClass('active');
            console.log('test')
        });


        $('#btn-choose-main').click(function(){
            let numActive = $('.table').data('num-active-tr');
            $('.bank-details-container').addClass('d-none');
            $('#loader').removeClass('d-none');
            console.log(numActive)
            if(numActive >= 0){
                let id = $('#carrier-select').val();
                let data = {id: id, num: numActive};

                $.ajax({
                    url: '/registry/ajax/change-main-detail',
                    method: 'POST',
                    data: data,
                    success: function(response){
                        $('#carrier-select').trigger('change');
                        console.log(response);
                    }
                })
            }
        });

    });

</script>

</body>
</html>

