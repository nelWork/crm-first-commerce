<?php

$columnsConfig = [
    [
        'class' => 'table-col-1',
        'header' => 'Логист',
        'filter' => true,
        'search_placeholder' => 'Введите имя чтобы найти логиста',
        'dataNameCol' => 'id-user',
        'dataTypeFilter' => 'id',
        'filterData' => $listManager,
        'filterValueField' => 'id',
        'filterLabelCallback' => fn($manager) => mb_strtoupper($manager['surname'].' '.$manager['name'].' '.$manager['lastname']),
    ],
    [
        'class' => 'table-col-2',
        'header' => '№ заявки, перевозчик',
        'filter' => true,
        'search_placeholder' => null,
        'dataNameCol' => 'application-number',
        'dataTypeFilter' => 'id',
        'filterData' => $uniqueData['application_number'],
        'filterValueField' => null,
        'filterLabelCallback' => fn($value) => $value,
    ],
    [
        'class' => 'table-col-3',
        'header' => '№ заявки, клиент',
        'filter' => false,
    ],
    [
        'class' => 'table-col-condition',
        'header' => 'Невыполненные условия <i class="bi bi-question-circle" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Условия которые осталось выполнить, чтобы заявка перешла в следующий статус"></i>',
        'filter' => false,
    ],
    [
        'class' => 'table-col-4',
        'header' => 'Дата заявки',
        'filter' => true,
        'filterData' => $uniqueData['date'],
        'dataNameCol' => 'app-date',
        'dataTypeFilter' => 'id',
        'filterValueField' => null,
        'filterLabelCallback' => fn($date) => $date,
    ],
    [
        'class' => 'table-col-5',
        'header' => 'Дата погрузки',
        'filter' => false,
    ],
    [
        'class' => 'table-col-6',
        'header' => 'Дата разгрузки',
        'filter' => true,
        'filterData' => $uniqueData['date_unloading_str'],
        'dataNameCol' => 'date-unloading',
        'dataTypeFilter' => 'id',
        'filterValueField' => null,
        'filterLabelCallback' => fn($date) => $date,
    ],
    [
        'class' => 'table-col-6-1',
        'header' => 'Актуальная дата разгрузки',
        'filter' => true,
        'filterData' => $uniqueData['application_date_actual_unloading'],
        'dataNameCol' => 'date-actual-unloading',
        'dataTypeFilter' => 'id',
        'filterValueField' => null,
        'filterLabelCallback' => fn($date) => $date,
    ],
    [
        'class' => 'table-col-7',
        'header' => 'ТТН',
        'filter' => false,
    ],
    [
        'class' => 'table-col-8',
        'header' => 'ТТН отправлено',
        'filter' => false,
    ],
    [
        'class' => 'table-col-9',
        'header' => 'Название клиента',
        'filter' => true,
        'search_placeholder' => 'Введите название чтобы найти клиента',
        'filterData' => $uniqueData['client'],
        'dataNameCol' => 'client-id',
        'dataTypeFilter' => 'id',
        'filterValueField' => 'id',
        'filterLabelCallback' => fn($client) => mb_strtoupper(str_replace(['"',"'","«","»"],'',$client['name'])),
    ],
    [
        'class' => 'table-col-10',
        'header' => 'Номер счета и дата',
        'filter' => true,
        'filterDataCustom' => [
            ['value' => 2, 'label' => 'Все'],
            ['value' => 0, 'label' => 'Пустые'],
            ['value' => 1, 'label' => 'Со счетом']
        ],
        'dataNameCol' => 'app-isset-account-number-client',
        'dataTypeFilter' => 'id',
    ],
    [
        'class' => 'table-col-11',
        'header' => 'Номер УПД и дата',
        'filter' => false,
    ],
    [
        'class' => 'table-col-12',
        'header' => 'Общая сумма (клиент)',
        'filter' => true,
        'filterData' => $uniqueData['cost_client'],
        'dataNameCol' => 'transportation-cost-client',
        'dataTypeFilter' => 'id',
        'filterValueField' => null,
        'filterLabelCallback' => fn($cost) => number_format($cost,0,'.',' ').' ₽',
    ],
    [
        'class' => 'table-col-13',
        'header' => 'Сумма без НДС',
        'filter' => false,
    ],
    [
        'class' => 'table-col-14',
        'header' => 'НДС',
        'filter' => false,
    ],
    [
        'class' => 'table-col-15',
        'header' => 'Факт. сумма оплаты (клиент)',
        'filter' => true,
        'filterData' => $uniqueData['actual_payment_client'],
        'dataNameCol' => 'actual-payment-client',
        'dataTypeFilter' => 'id',
        'filterValueField' => null,
        'filterLabelCallback' => fn($cost) => number_format($cost,0,'.',' ').' ₽',
        'withAllOption' => true
    ],
    [
        'class' => 'table-col-15-1',
        'header' => 'Доп. прибыль',
        'filter' => false,
    ],
    [
        'class' => 'table-col-16',
        'header' => 'Название перевозчика',
        'filter' => true,
        'search_placeholder' => 'Введите название чтобы найти перевозчика',
        'filterData' => $uniqueData['carrier'],
        'dataNameCol' => 'carrier-id',
        'dataTypeFilter' => 'id',
        'filterValueField' => 'id',
        'filterLabelCallback' => fn($item) => $item['name'],
    ],
    [
        'class' => 'table-col-19',
        'header' => 'Общая сумма',
        'filter' => false,
    ],
    [
        'class' => 'table-col-20',
        'header' => 'Сумма без НДС',
        'filter' => false,
    ],
    [
        'class' => 'table-col-21',
        'header' => 'НДС',
        'filter' => false,
    ],
    [
        'class' => 'table-col-22',
        'header' => 'Факт. сумма оплаты',
        'filter' => false,
    ],
    [
        'class' => 'table-col-23',
        'header' => 'Доп. расходы',
        'filter' => false,
    ],
    [
        'class' => 'table-col-24',
        'header' => 'Доход',
        'filter' => false,
    ],
    [
        'class' => 'table-col-25',
        'header' => 'Маржа з.п.',
        'filter' => false,
    ],
    [
        'class' => 'table-col-26',
        'header' => 'Чистая прибыль',
        'filter' => false,
    ],
    [
        'class' => 'table-col-27',
        'header' => 'Маржинальность',
        'filter' => false,
    ],
];

$tableColumns = [
    [
        'class' => 'table-col-1',
        'header' => 'Логист',
        'render' => fn($app) => htmlspecialchars($app['manager']),
        'dataAttributes' => [],
    ],
    [
        'class' => 'table-col-2 table-col-application-number-carrier',
        'header' => 'Номер заявки перевозчика',
        'render' => function($app) {
            $appNumber = ($app['application_number'] < 500) ? "{$app['application_number']}-Т" : $app['application_number'];
            return sprintf(
                '<a href="/application?id=%d" target="_blank" style="color: black;text-decoration: unset;">%s</a>',
                $app['id'],
                htmlspecialchars($appNumber)
            );
        },
        'dataAttributes' => ['application-number' => 'application_number'],
    ],
    [
        'class' => 'table-col-3 table-col-application-number-client',
        'header' => 'Номер заявки клиента',
        'render' => fn($app) => $app['application_number_Client'] ?: '<div class="text-center">—</div>',
        'dataAttributes' => [],
    ],
    [
        'class' => 'table-col-condition',
        'header' => 'Невыполненные условия',
        'render' => function($app) {
            if(empty($app['unfulfilledConditions'])) return '';
            return implode('', array_map(fn($condition)=>"<div>- ".htmlspecialchars($condition)."</div>", $app['unfulfilledConditions']));
        },
        'dataAttributes' => [],
    ],
    [
        'class' => 'table-col-4',
        'header' => 'Дата заявки',
        'render' => function($app){
            $date = date('d.m.Y', strtotime($app['date']));
            return $date;
        },
        'dataAttributes' => ['app-date' => 'date'],
    ],
    [
        'class' => 'table-col-5',
        'header' => 'Дата погрузки',
        'render' => function ($app) {
            $dates = array_filter($app['transportation_list'], fn($item) => $item['direction']);
            return implode('', array_map(fn($item) => '<div>' . htmlspecialchars($item['date']) . '</div>', $dates));
        },
        'dataAttributes' => [],
    ],
    [
        'class' => 'table-col-6',
        'header' => 'Дата разгрузки',
        'render' => function ($app) {
            $dates = array_filter($app['transportation_list'], fn($item) => !$item['direction']);
            return implode('', array_map(fn($item) => '<div>' . htmlspecialchars($item['date']) . '</div>', $dates));
        },
        'dataAttributes' => [],
    ],
    [
        'class' => 'table-col-6-1',
        'header' => 'Актуальная дата разгрузки',
        'render' => fn($app) => $app['application_date_actual_unloading'] ? date('d.m.Y', strtotime($app['application_date_actual_unloading'])) : '',
        'dataAttributes' => [],
    ],
    [
        'class' => 'table-col-7',
        'header' => 'ТТН ',
        'render' => fn($app) => '',
        'dataAttributes' => [],
    ],
    [
        'class' => 'table-col-8',
        'header' => 'ТТН отправлено',
        'render' => fn($app) => '<div class="text-center" style="font-weight:600">' . ($app['ttn_sent'] ? 'ОТПРАВЛЕНО' : 'НЕ ОТПРАВЛЕНО') . '</div>',
        'dataAttributes' => [],
    ],
    [
        'class' => 'table-col-9 col-client',
        'header' => 'Название клиента',
        'render' => function ($app) {
            $inn = htmlspecialchars($app['client_data']['inn']);
            $name = htmlspecialchars($app['client_data']['name']);
            $formatWork = $app['client_data']['format_work'] ? "<div>(" . htmlspecialchars($app['client_data']['format_work']) . ")</div>" : "";
            return "{$name}<div><span class=\"inn text-secondary\">{$inn}</span></div>{$formatWork}";
        },
        'dataAttributes' => [],
    ],
    [
        'class' => 'table-col-10 col-client',
        'header' => 'Номер счета и дата клиента',
        'render' => fn($app) => htmlspecialchars($app['account_number_Client']) . "<div>("
            . ($app['account_status_Client'] ? 'Отправлен ' . date('d.m.Y', strtotime($app['date_invoice_Client'])) : 'Не отправлен') . ")</div>",
        'dataAttributes' => [],
    ],
    [
        'class' => 'table-col-11 col-client',
        'header' => 'Номер УПД и дата клиента',
        'render' => fn($app) => htmlspecialchars($app['account_number_Client']) . "<div>("
            . ($app['account_status_Client'] ? 'Отправлен ' . date('d.m.Y', strtotime($app['date_invoice_Client'])) : 'Не отправлен') . ")</div>",
        'dataAttributes' => [],
    ],
    [
        'class' => 'table-col-12 col-client',
        'header' => 'Общая сумма Клиент',
        'render' => function ($app) {
            return sprintf(
                "<div>%s ₽</div><span class='text-secondary'>%s</span>",
                number_format($app['transportation_cost_Client'], 0, '.', ' '),
                htmlspecialchars($app['taxation_type_Client'])
            );
        },
        'dataAttributes' => [],
    ],
    [
        'class' => 'table-col-13 col-client',
        'header' => 'Сумма без НДС Клиент',
        'render' => function ($app) {
            return sprintf(
                "<div>%s ₽</div><span class='text-secondary'>%s</span>",
                number_format($app['transportation_cost_Client'], 0, '.', ' '),
                htmlspecialchars($app['taxation_type_Client'])
            );
        },
        'dataAttributes' => [],
    ],
    [
        'class' => 'table-col-14 col-client',
        'header' => 'НДС Клиент',
        'render' => function ($app) {
            return sprintf(
                "<div>%s ₽</div><span class='text-secondary'>%s</span>",
                number_format($app['transportation_cost_Client'], 0, '.', ' '),
                htmlspecialchars($app['taxation_type_Client'])
            );
        },
        'dataAttributes' => [],
    ],
    [
        'class' => 'table-col-15 col-client',
        'header' => 'Фактическая сумма оплаты клиента',
        'render' => function ($app) {
            return number_format($app['actual_payment_Client'], 0, ',', ' ') . ' ₽';
        },
        'dataAttributes' => [],
    ],
    [
        'class' => 'table-col-15-1',
        'header' => 'Доп. прибыль',
        'render' => fn($app) => sprintf('<div class="text-success"> %s ₽</div>', number_format($app['additional_profit_sum_Client'], 0, ',', ' ')),
        'dataAttributes' => [],
    ],
    [
        'class' => 'table-col-16 col-carrier',
        'header' => 'Название перевозчика',
        'render' => function ($app) {
            return htmlspecialchars($app['carrier_data']['name']) . "<div><span class='inn text-secondary'>" . htmlspecialchars($app['carrier_data']['inn']) . "</span></div>";
        },
        'dataAttributes' => [],
    ],
    [
        'class' => 'table-col-19 col-carrier',
        'header' => 'Общая сумма Перевозчик',
        'render' => fn($app) => sprintf("<div>%s ₽</div><span class='text-secondary'>%s</span>", number_format($app['transportation_cost_Carrier'], 0, '.', ' '), $app['taxation_type_Carrier']),
        'dataAttributes' => [],
    ],
    [
        'class' => 'table-col-22 col-carrier',
        'header' => 'Сумма без НДС Перевозчик',
        'render' => fn($app) => number_format($app['transportation_cost_Carrier'], 0, ',', ' ') . ' ₽',
        'dataAttributes' => [],
    ],
    [
        'class' => 'table-col-22 col-carrier',
        'header' => 'НДС Перевозчик',
        'render' => fn($app) => number_format($app['transportation_cost_Carrier'], 0, ',', ' ') . ' ₽',
        'dataAttributes' => [],
    ],
    [
        'class' => 'table-col-22 col-carrier',
        'header' => 'Фактическая сумма оплаты Перевозчик',
        'render' => fn($app) => number_format($app['actual_payment_Carrier'], 0, ',', ' ') . ' ₽',
        'dataAttributes' => [],
    ],
    [
        'class' => 'table-col-23',
        'header' => 'Доп. расходы',
        'render' => fn($app) => sprintf('<div class="text-danger">- %s ₽</div>', number_format($app['additional_expenses_sum_Carrier'], 0, ',', ' ')),
        'dataAttributes' => [],
    ],
    [
        'class' => 'table-col-24',
        'header' => 'Доход',
        'render' => fn($app) => number_format($app['application_walrus'], 0, ',', ' ') . ' ₽',
        'dataAttributes' => [],
    ],
    [
        'class' => 'table-col-25',
        'header' => 'Маржа зарплаты',
        'render' => function ($app) {
            $color = $app['manager_share'] > 0 ? 'text-success' : 'text-danger';
            $sign = $app['manager_share'] > 0 ? '+' : '';
            return "<span class=\"{$color}\">{$sign} " . number_format($app['manager_share'], 0, ',', ' ') . " ₽</span>";
        },
        'dataAttributes' => [],
    ],
    [
        'class' => 'table-col-26',
        'header' => 'Чистая прибыль',
        'render' => fn($app) => number_format($app['application_net_profit'],0,',', ' '),
        'dataAttributes' => [],
    ],
    [
        'class' => 'table-col-27',
        'header' => 'Маржинальность',
        'render' => fn($app) => "{$app['marginality']}%",
        'dataAttributes' => [],
    ]
];

function renderTableRow(array $application, array $columnsConfig): string {
    $html = '<tr';
    foreach($columnsConfig as $column) {
        foreach($column['dataAttributes'] as $attrHtml => $attrSource) {
            $value = htmlspecialchars($application[$attrSource] ?? '', ENT_QUOTES);
            $html .= " data-{$attrHtml}=\"{$value}\"";
        }
    }
    $html .= '>';

    foreach ($columnsConfig as $column) {
        $html .= '<td class="'.htmlspecialchars($column['class'], ENT_QUOTES).'">';
        $html .= $column['render']($application);
        $html .= '</td>';
    }

    $html .= '</tr>';

    return $html;
}
//dd($tableColumns);

?>


<table class="table display table-striped table-bordered">
    <thead>
    <tr>
        <?php foreach ($columnsConfig as $column): ?>
            <th class="<?= htmlspecialchars($column['class']) ?>">
                <?php if ($column['filter'] ?? false): ?>
                    <div class="header-table-filter">
                        <div class="filter-head">
                            <?= htmlspecialchars($column['header']) ?>
                            <i class="bi bi-caret-down-fill"></i>
                        </div>
                        <div class="filter-body">
                            <?php if (!empty($column['search_placeholder'])): ?>
                                <div class="search-in-filter-container mb-2">
                                    <input type="text" class="form-control form-control-sm js-search-in-filter"
                                           placeholder="<?= htmlspecialchars($column['search_placeholder']) ?>">
                                </div>
                            <?php endif; ?>
                            <?php foreach ($column['filterData'] as $filterItem): ?>
                                <?php
                                $value = $filterItem[$column['filterValueField']] ?? $filterItem;
                                $label = htmlspecialchars(($column['filterLabelCallback'])($filterItem));
                                $inputId = "filter-" . $column['dataNameCol'] . "-" . htmlspecialchars($value);
                                ?>
                                <div class="form-check">
                                    <input class="form-check-input js-filter-header-table"
                                           data-name-col="<?= htmlspecialchars($column['dataNameCol']) ?>"
                                           data-type-filter="<?= htmlspecialchars($column['dataTypeFilter']) ?>"
                                           type="checkbox"
                                           value="<?= htmlspecialchars($value) ?>"
                                           id="<?= $inputId ?>">
                                    <label class="form-check-label" for="<?= $inputId ?>">
                                        <?= $label ?>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php else: ?>
                    <?= htmlspecialchars($column['header']) ?>
                <?php endif; ?>
            </th>
        <?php endforeach; ?>
    </tr>
    </thead>
    <tbody>
        <?php foreach ($listApplication as $application):
            echo renderTableRow($application, $tableColumns);
        endforeach; ?>
    </tbody>
</table>
