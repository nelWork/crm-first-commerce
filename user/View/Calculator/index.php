<?php
/** @var App\User\Contoller\Common\HomeController $controller */
/** @var String $titlePage */
/** @var array $report */
$controller->view('Components/head');
?>
<body>
<style>
    input.form-control,select.form-select{
        font-size: 16px;
        font-weight: 600;
    }
    .danger-walrus .form-control,.danger-walrus label{
        color: red;
        border-color: red;
    }
</style>

<?php $controller->view('Components/header'); ?>

<main class="analytics">
    <div class="sub-header" style="padding-bottom: 0;">
        <div class="wrapper">
            <?php $controller->view('Components/breadcrumbs'); ?>

            <h3 class="sub-header-title"><?php echo $titlePage; ?></h3>

            <div class="row" style="margin-top: 20px;">

            </div>
        </div>
    </div>


    <div class="wrapper">
        <?php if($plan AND $planExecution): ?>
            <?php $controller->view('Components/chartKPI'); ?>
        <?php endif; ?>
    </div>

    <section class="analytics-applications__list mb-5">
        <div class="wrapper p-4 pt-1 mb-5">

            <h3 class="text-center mb-4">Калькулятор</h3>
            <div class="row">
                <div class="col" style="border-right: 1px solid black">
                    <h3 class="text-center">ООО</h3>
                    <div class="mb-3">
                        <label class="mb-1" for="" style="font-weight: 600;font-size: 16px">Поступление C НДС</label>
                        <div class="d-flex">
                            <div class="col-10">
                                <input type="text" min="0" step="0.01" class="form-control" id="input-income-vat">
                            </div>
                            <div class="col">
                                <select name="" id="select-income-vat" class="form-select">
                                    <option value="20">20%</option>
                                    <option value="0">0%</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="mb-1" for="" style="font-weight: 600;font-size: 16px">Расход С НДС</label>
                        <div class="d-flex">
                            <div class="col-10">
                                <input type="text" min="0" step="0.01" class="form-control" id="input-consumption-vat">
                            </div>
                            <div class="col">
                                <select name="" id="select-consumption-vat" class="form-select">
                                    <option value="20">20%</option>
                                    <option value="7">7%</option>
                                    <option value="5">5%</option>
                                    <option value="0">0%</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="mb-1" for="" style="font-weight: 600;font-size: 16px">Расход Б/НДС</label>
                        <input type="text" min="0" step="0.01" class="form-control" id="input-consumption-not-vat">
                    </div>
                    <div class="mb-4">
                        <label class="mb-1"  for="" style="font-weight: 600;font-size: 16px">Расход НАЛ</label>
                        <input type="text" min="0" step="0.01" class="form-control" id="input-consumption-cash">
                    </div>

                    <div class="mb-4">
                        <label class="mb-1" for="" style="font-weight: 600;font-size: 16px">Маржа с заявки</label>
                        <div class="d-flex">
                            <div class="col-10">
                                <input type="text" min="0" step="0.01" class="form-control" id="input-walrus" disabled>
                            </div>
                            <div class="col">
                                <input type="text" class="form-control" id="percent-walrus">
                            </div>
                        </div>
                    </div>

                    <div class="mb-1">
                        <label class="mb-1" for="" style="font-weight: 600;font-size: 16px">ЗП<span class="text-danger">*</span> (при 25 %)</label>
                        <input type="text" min="0" step="0.01" class="form-control" id="input-salary-25" disabled>
                    </div>
                    <div class="mb-1">
                        <label class="mb-1" for="" style="font-weight: 600;font-size: 16px">ЗП<span class="text-danger">*</span> (при 20 %)</label>
                        <input type="text" min="0" step="0.01" class="form-control" id="input-salary-20" disabled>
                    </div>
                    <div class="mb-1">
                        <label class="mb-1" for="" style="font-weight: 600;font-size: 16px">ЗП<span class="text-danger">*</span> (при 15 %)</label>
                        <input type="text" min="0" step="0.01" class="form-control" id="input-salary-15" disabled>
                    </div>
                </div>
                <div class="col">
                    <h3 class="text-center">ИП</h3>
                    <div class="mb-3">
                        <label class="mb-1" for="" style="font-weight: 600;font-size: 16px">Поступление Б/НДС</label>
                        <input type="text" min="0" step="0.01" class="form-control" id="input-income-not-vat-2">
                    </div>
                    <div class="mb-3" style="visibility:hidden;">
                        <label class="mb-1"  for="" style="font-weight: 600;font-size: 16px">Расход С НДС</label>
                        <input type="text" min="0" step="0.01" class="form-control" id="input-consumption-vat-2" value="0">
                    </div>
                    <div class="mb-3">
                        <label class="mb-1"  for="" style="font-weight: 600;font-size: 16px">Расход Б/НДС</label>
                        <input type="text" min="0" step="0.01" class="form-control" id="input-consumption-not-vat-2">
                    </div>
                    <div class="mb-4">
                        <label class="mb-1"  for="" style="font-weight: 600;font-size: 16px">Расход НАЛ</label>
                        <input type="text" min="0" step="0.01" class="form-control" id="input-consumption-cash-2">
                    </div>

                    <div class="mb-4">
                        <label class="mb-1"  for="" style="font-weight: 600;font-size: 16px">Маржа с заявки</label>
                        <div class="d-flex">
                            <div class="col-10">
                                <input type="text" min="0" step="0.01" class="form-control" id="input-walrus-2" disabled>
                            </div>
                            <div class="col">
                                <input type="text" class="form-control" id="percent-walrus-2" disabled>
                            </div>
                        </div>
                    </div>
                    <div class="mb-1">
                        <label class="mb-1"  for="" style="font-weight: 600;font-size: 16px">ЗП<span class="text-danger">*</span> (при 25 %)</label>
                        <input type="text" min="0" step="0.01" class="form-control" id="input-salary-25-2" disabled>
                    </div>
                    <div class="mb-1">
                        <label class="mb-1" for="" style="font-weight: 600;font-size: 16px">ЗП<span class="text-danger">*</span> (при 20 %)</label>
                        <input type="text" min="0" step="0.01" class="form-control" id="input-salary-20-2" disabled>
                    </div>
                    <div class="mb-1">
                        <label class="mb-1" for="" style="font-weight: 600;font-size: 16px">ЗП<span class="text-danger">*</span> (при 15 %)</label>
                        <input type="text" min="0" step="0.01" class="form-control" id="input-salary-15-2" disabled>
                    </div>
                </div>
            </div>
        </div>
    </section>

</main>
<script>
    $('#input-income-vat,#input-consumption-vat,#input-consumption-not-vat,#input-consumption-cash,#select-consumption-vat,#select-income-vat').change(function (){
        calculateWalrus();
    });

    $('#input-income-not-vat-2,#input-consumption-vat-2,#input-consumption-not-vat-2,#input-consumption-cash-2').change(function (){
        calculateWalrus2();
    });
    

    $('#percent-walrus').change(function(){
        let incomeVat = $('#input-income-vat').val().replaceAll(' ' ,'');

        let consumptionVat = $('#input-consumption-vat').val().replaceAll(' ' ,'');

        let percentWalrus = parseFloat($('#percent-walrus').val());
        
        let consumptionNotVat = $('#input-consumption-not-vat').val().replaceAll(' ' ,'');
        let consumptionCash = $('#input-consumption-cash').val().replaceAll(' ' ,'');

        if(!consumptionVat)
            consumptionVat = 0;
        if(!consumptionNotVat)
            consumptionNotVat = 0;
        if(!consumptionCash)
            consumptionCash = 0;


        let percentConsumptionVat = $('#select-consumption-vat').val() / 100 + 1;
        let percentIncomeVat = $('#select-income-vat').val() / 100 + 1;

        let walrus = ((incomeVat / percentIncomeVat) - (consumptionVat / percentConsumptionVat) - consumptionNotVat - (consumptionCash / 0.75)) * 0.95;

        // $('#percent-walrus').val(new Intl.NumberFormat("ru").format(walrus / incomeVat * 100 ).replace(',','.') + '%');

        // consumptionVat = ((incomeVat / percentIncomeVat)  - incomeVat / percentWalrus / 0.95 - consumptionNotVat - (consumptionCash / 0.75)) * percentConsumptionVat;

        // 18000 = ((180000 / 1.2) - (x / 1.2) - 500 - 0) * 0.95;

        // 150000 - (x / 1.2) - 500 = 18947

        // 130553 = x / 1.2

        // x = 156663

        if(incomeVat > 0){
            consumptionVat = ((incomeVat / percentIncomeVat)  - incomeVat / percentWalrus / 0.95 - consumptionNotVat - (consumptionCash / 0.75)) * percentConsumptionVat;
            $('#input-consumption-vat').val(new Intl.NumberFormat("ru").format(consumptionVat)).trigger('change');
        }
    });
    
    function calculateWalrus() {
        let incomeVat = $('#input-income-vat').val().replaceAll(' ' ,'');
        let consumptionVat = $('#input-consumption-vat').val().replaceAll(' ' ,'');
        let consumptionNotVat = $('#input-consumption-not-vat').val().replaceAll(' ' ,'');
        let consumptionCash = $('#input-consumption-cash').val().replaceAll(' ' ,'');

        if(!consumptionVat)
            consumptionVat = 0;
        if(!consumptionNotVat)
            consumptionNotVat = 0;
        if(!consumptionCash)
            consumptionCash = 0;


        console.log(incomeVat,consumptionVat,consumptionNotVat,consumptionCash)

        let percentConsumptionVat = $('#select-consumption-vat').val() / 100 + 1;
        let percentIncomeVat = $('#select-income-vat').val() / 100 + 1;

        let walrus = ((incomeVat / percentIncomeVat) - (consumptionVat / percentConsumptionVat) - consumptionNotVat - (consumptionCash / 0.75)) * 0.95;

        let salary = walrus * 0.86 * 0.25;
        let salary20 = walrus * 0.86 * 0.2;
        let salary15 = walrus * 0.86 * 0.15;


        $('.mb-4').has('#input-walrus').removeClass('danger-walrus');
        $('.mb-4').has('#input-walrus').find('label').text('Маржа с заявки');


        console.log("Процент маржи " +(walrus / incomeVat * 100 ));


        if(incomeVat > 60000){
            if(walrus > incomeVat / 10){

            }
            else{
                $('.mb-4').has('#input-walrus').addClass('danger-walrus');
                $('.mb-4').has('#input-walrus').find('label').text('Маржинальность ниже минимального значения!');
            }
        }
        else{
            if(walrus > 6000){

            }
            else{
                $('.mb-4').has('#input-walrus').addClass('danger-walrus');
                $('.mb-4').has('#input-walrus').find('label').text('Маржинальность ниже минимального значения!');
            }
        }

        $('#percent-walrus').val(new Intl.NumberFormat("ru").format(walrus / incomeVat * 100 ).replace(',','.') + '%');


        console.log(walrus,salary)
        walrus = new Intl.NumberFormat("ru").format(walrus).replace(',','.');

        salary = new Intl.NumberFormat("ru").format(salary).replace(',','.');
        salary20 = new Intl.NumberFormat("ru").format(salary20).replace(',','.');
        salary15 = new Intl.NumberFormat("ru").format(salary15).replace(',','.');

        $('#input-walrus').val(walrus)
        $('#input-salary-25').val(salary)
        $('#input-salary-20').val(salary20)
        $('#input-salary-15').val(salary15)

    }

    function calculateWalrus2() {
        let incomeVat = $('#input-income-not-vat-2').val().replaceAll(' ' ,'');
        let consumptionVat = $('#input-consumption-vat-2').val().replaceAll(' ' ,'');
        let consumptionNotVat = $('#input-consumption-not-vat-2').val().replaceAll(' ' ,'');
        let consumptionCash = $('#input-consumption-cash-2').val().replaceAll(' ' ,'');


        let walrus = (incomeVat - consumptionNotVat) * 0.92 -  consumptionCash / 0.9;
        let salary = walrus * 0.93 * 0.25;
        let salary20 = walrus * 0.93 * 0.2;
        let salary15 = walrus * 0.93 * 0.15;


        $('#percent-walrus-2').val(new Intl.NumberFormat("ru").format(walrus / incomeVat * 100 ).replace(',','.') + '%');


        walrus = new Intl.NumberFormat("ru").format(walrus).replace(',','.');

        salary = new Intl.NumberFormat("ru").format(salary).replace(',','.');
        salary20 = new Intl.NumberFormat("ru").format(salary20).replace(',','.');
        salary15 = new Intl.NumberFormat("ru").format(salary15).replace(',','.');


        $('#input-walrus-2').val(walrus)
        $('#input-salary-25-2').val(salary)
        $('#input-salary-20-2').val(salary20)
        $('#input-salary-15-2').val(salary15)
    }
    function formatNumber(num) {
        return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1 ')
    }

    $('.form-control').bind('input', function () {
        let value = $(this).val();
        value = value.replaceAll(' ' ,'');
        $(this).val(formatNumber(value));
    });
</script>

</body>
