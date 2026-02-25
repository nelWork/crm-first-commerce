<?php
/** @var App\User\Contoller\Common\HomeController $controller */
/** @var String $titlePage */
/** @var array $calculatorCoefficients */
//dd($calculatorCoefficients);
?>

<?php $controller->view('Components/start'); ?>
<div class="p-4">
    <div class="d-flex align-items-center">
        <h4 class="me-2 mb-5"><?php echo $titlePage; ?></h4>
    </div>

    <form action="" method="POST" class="w-25">
        <div class="mb-4">
            <label for="" class="mb-2">Поступление с НДС</label>
            <div class="d-flex">
                <div class="col">
                    <input type="number" id="input-income-vat" class="form-control" value="" min="0" step="1" required>
                </div> -
                <div class="col">
                    <input type="number" id="company-input-income-vat" class="form-control" value="" min="0" step="1" required>
                </div>
                <input type="text" id="coefficient-input-income-vat" name="coefficient-input-income-vat" class="d-none" value="0">
            </div>
        </div>
        <div class="mb-4">
            <label for="" class="mb-2">Расход с НДС</label>
            <div class="d-flex">
                <div class="col">
                    <input type="number" id="input-consumption-vat" class="form-control" value="" min="0" step="1" required>
                </div> -
                <div class="col">
                    <input type="number" id="company-input-consumption-vat" class="form-control" value="" min="0" step="1" required>
                </div>
                <input type="text" id="coefficient-input-consumption-vat" name="coefficient-input-consumption-vat" class="d-none" value="0">
            </div>
        </div>
        <div class="mb-4">
            <label for="" class="mb-2">Расход без НДС</label>
            <div class="d-flex">
                <div class="col">
                    <input type="number" id="input-consumption-not-vat" class="form-control" value="" min="0" step="1" required>
                </div> -
                <div class="col">
                    <input type="number" id="company-input-consumption-not-vat" class="form-control" value="" min="0" step="1" required>
                </div>
                <input type="text" id="coefficient-input-consumption-not-vat" name="coefficient-input-consumption-not-vat" class="d-none" value="0">
            </div>
        </div>
        <div class="mb-4">
            <label for="" class="mb-2">Расход нал</label>
            <div class="d-flex">
                <div class="col">
                    <input type="number" id="input-consumption-cash" class="form-control" value="" min="0" step="1" required>
                </div> -
                <div class="col">
                    <input type="number" id="company-input-consumption-cash" class="form-control" value="" min="0" step="1" required>
                </div>
                <input type="text" id="coefficient-input-consumption-cash" name="coefficient-input-consumption-cash" class="d-none" value="0">
            </div>
        </div>
        <div class="mb-4">
            <label for="" class="mb-2">ЗП</label>
            <div class="d-flex">
                <div class="col">
                    <input type="number" id="input-salary" class="form-control" value="" min="0" step="1" required>
                </div> -
                <div class="col">
                    <input type="number" id="company-input-salary" class="form-control" value="" min="0" step="1" required>
                </div>
                <input type="text" id="coefficient-input-salary" name="coefficient-input-salary" class="d-none" value="0">
            </div>
        </div>

        <button class="btn btn-success">Сохранить</button>
    </form>

</div>

<script>
    $('#input-income-vat, #company-input-income-vat').change(function (){
        let incomeVat = $('#input-income-vat').val();
        let companyIncomeVat = $('#company-input-income-vat').val();
        console.log(incomeVat, companyIncomeVat);

        let coefficientIncomeVat = companyIncomeVat / incomeVat;
        console.log(coefficientIncomeVat);

        $('#coefficient-input-income-vat').val(coefficientIncomeVat);
        console.log($('#coefficient-input-income-vat').val());
    });

    $('#input-consumption-vat, #company-input-consumption-vat').change(function (){
        let incomeConsumptionVat = $('#input-consumption-vat').val();
        let companyIncomeConsumptionVat = $('#company-input-consumption-vat').val();
        console.log(incomeConsumptionVat, companyIncomeConsumptionVat);

        let coefficientIncomeConsumptionVat = companyIncomeConsumptionVat / incomeConsumptionVat;
        console.log(coefficientIncomeConsumptionVat);

        $('#coefficient-input-consumption-vat').val(coefficientIncomeConsumptionVat);
        console.log($('#coefficient-input-consumption-vat').val());
    });

    $('#input-consumption-not-vat, #company-input-consumption-not-vat').change(function (){
        let incomeConsumptionNotVat = $('#input-consumption-not-vat').val();
        let companyIncomeConsumptionNotVat = $('#company-input-consumption-not-vat').val();
        console.log(incomeConsumptionNotVat, companyIncomeConsumptionNotVat);

        let coefficientIncomeConsumptionNotVat = companyIncomeConsumptionNotVat / incomeConsumptionNotVat;
        console.log(coefficientIncomeConsumptionNotVat);

        $('#coefficient-input-consumption-not-vat').val(coefficientIncomeConsumptionNotVat);
        console.log($('#coefficient-input-consumption-not-vat').val());
    });

    $('#input-consumption-cash, #company-input-consumption-cash').change(function (){
        let incomeConsumptionCash = $('#input-consumption-cash').val();
        let companyIncomeConsumptionCash = $('#company-input-consumption-cash').val();
        console.log(incomeConsumptionCash, companyIncomeConsumptionCash);

        let coefficientIncomeConsumptionCash = companyIncomeConsumptionCash / incomeConsumptionCash;
        console.log(coefficientIncomeConsumptionCash);

        $('#coefficient-input-consumption-cash').val(coefficientIncomeConsumptionCash);
        console.log($('#coefficient-input-consumption-cash').val());
    });

    $('#input-salary, #company-input-salary').change(function (){
        let incomeSalary = $('#input-salary').val();
        let companyIncomeSalary = $('#company-input-salary').val();
        console.log(incomeSalary, companyIncomeSalary);

        let coefficientIncomeSalary = companyIncomeSalary / incomeSalary;
        console.log(coefficientIncomeSalary);

        $('#coefficient-input-salary').val(coefficientIncomeSalary);
        console.log($('#coefficient-input-salary').val());
    });
</script>

<?php $controller->view('Components/end'); ?>
