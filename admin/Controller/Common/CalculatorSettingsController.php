<?php

namespace App\Admin\Controller\Common;

use App\Admin\Controller\Controller;

class CalculatorSettingsController extends Controller
{
    public function calculatorSettings()
    {
        $this->extract([
            'controller' => $this,
            'titlePage' => 'Настройка калькулятора',
            'itemActiveMenu' => 2,
            'calculatorCoefficients' => $this->database->first('company_calculator_settings'),
        ]);

        $this->view('Calculator/settings');
    }

    public function editCalculatorSettings()
    {

        $this->database->update('company_calculator_settings',
            [
                'coefficient_income_vat' => $this->request->input('coefficient-input-income-vat'),
                'coefficient_consumption_vat' => $this->request->input('coefficient-input-consumption-vat'),
                'coefficient_consumption_not_vat' => $this->request->input('coefficient-input-consumption-not-vat'),
                'coefficient_consumption_cash' => $this->request->input('coefficient-input-consumption-cash'),
                'coefficient_salary' => $this->request->input('coefficient-input-salary'),
            ],
            ['id' => 1]
        );

        $this->redirect->to('/admin/calculator/settings');
    }
}