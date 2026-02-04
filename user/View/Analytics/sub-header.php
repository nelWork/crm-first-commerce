<ul class="nav nav-underline nav-subheader">
    <li class="nav-item">
        <a href="/analytics" class="nav-link <?= $controller->isActive('/analytics', true) ?>">
            Статистика
        </a>
    </li>

    <li class="nav-item d-none">
        <a href="/analytics/report" class="nav-link <?= $controller->isActive('/analytics/report', true) ?>">
            P&L
        </a>
    </li>

    <li class="nav-item">
        <a href="/analytics/debtor-creditor" class="nav-link <?= $controller->isActive('/analytics/debtor-creditor', true) ?>">
            Дебит, кредит
        </a>
    </li>

    <li class="nav-item">
        <a href="/analytics/declaration" class="nav-link <?= $controller->isActive('/analytics/declaration', true) ?>">
            Ведомость
        </a>
    </li>

    <li class="nav-item d-none">
        <a href="/analytics/net-profit-stat" class="nav-link <?= $controller->isActive('/analytics/net-profit-stat', true) ?>">
            ДДС
        </a>
    </li>

    <li class="nav-item d-none">
        <a href="/analytics/salary" class="nav-link <?= $controller->isActive('/analytics/salary', true) ?>">
            Зарплата
        </a>
    </li>

    <li class="nav-item">
        <a href="/analytics/managers" class="nav-link <?= $controller->isActive('/analytics/managers', true) ?>">
            Менеджеры
        </a>
    </li>

    <li class="nav-item d-none">
        <a href="/analytics/carrier-stat" class="nav-link <?= $controller->isActive('/analytics/carrier-stat', true) ?>">
            Статистика по перевозчикам
        </a>
    </li>
</ul>
