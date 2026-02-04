
<div class="flex-shrink-0 p-3 admin-sidebar">
    <ul class="list-unstyled ps-0">
        <li class="admin-sidebar__item">
            <a href="#" class="link <?php if($itemActiveMenu == 1) echo 'active'; ?>"><i class="fa-regular fa-hard-drive"></i> Хранилище</a>
        </li>
        <li class="admin-sidebar__item">
            <a href="#" class="link <?php if($itemActiveMenu == 2) echo 'active'; ?>"><i class="fa-solid fa-sack-dollar"></i> Финансы</a>
        </li>
        <li class="admin-sidebar__item">
            <a href="/admin/customer/list" class="link <?php if($itemActiveMenu == 3) echo 'active'; ?>">Юридические лица</a>
        </li>
        <li class="admin-sidebar__item">
            <a href="/admin/document-flow" class="link <?php if($itemActiveMenu == 4) echo 'active'; ?>"><i class="fa-regular fa-file-lines"></i> Документооборот</a>
        </li>
        <li class="admin-sidebar__item">
            <a class="collapsed link" href="#" data-bs-toggle="collapse" data-bs-target="#transport" aria-expanded="false">
                <i class="fa-solid fa-truck"></i> Транспорт <span></span>
            </a>
            <div class="collapse <?php if($itemActiveMenu >= 5 AND $itemActiveMenu <= 7) echo 'show'; ?>" id="transport">
                <ul class="nav-collapse">
                    <li><a href="/admin/car-brands/list" class="link <?php if($itemActiveMenu == 5) echo 'active'; ?>">Марки</a></li>
                    <li><a href="/admin/type-transport/list" class="link <?php if($itemActiveMenu == 6) echo 'active'; ?>">Тип транспорта</a></li>
                    <li><a href="/admin/type-carcase/list" class="link <?php if($itemActiveMenu == 7) echo 'active'; ?>">Тип кузова</a></li>
                </ul>
            </div>
        </li>
        <li class="admin-sidebar__item">
            <a class="collapsed link" href="#" data-bs-toggle="collapse" data-bs-target="#ur-block" aria-expanded="false">
                <i class="fa-solid fa-scale-balanced"></i> Юридический блок
            </a>
            <div class="collapse <?php if($itemActiveMenu >= 8 AND $itemActiveMenu <= 10) echo 'show'; ?>" id="ur-block">
                <ul class="nav-collapse">
                    <li><a href="/admin/condition/list" class="link <?php if($itemActiveMenu == 8) echo 'active'; ?>">Обязательные условия</a></li>
                    <li><a href="/admin/terms-of-payment/list" class="link <?php if($itemActiveMenu == 9) echo 'active'; ?>">Условия оплаты</a></li>
                    <li><a href="/admin/addition/list" class="link <?php if($itemActiveMenu == 10) echo 'active'; ?>">Приложения</a></li>
                </ul>
            </div>
        </li>
        <li class="admin-sidebar__item">
            <a href="/admin/users/list" class="link <?php if($itemActiveMenu == 11) echo 'active'; ?>"><i class="fa-solid fa-users"></i> Пользователи</a>
        </li>
<!--        <li class="border-top my-3"></li>-->
<!--        <li class="mb-1">-->
<!--            <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed" data-bs-toggle="collapse" data-bs-target="#account-collapse" aria-expanded="false">-->
<!--                Account-->
<!--            </button>-->
<!--            <div class="collapse" id="account-collapse">-->
<!--                <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">-->
<!--                    <li><a href="#" class="link-dark d-inline-flex text-decoration-none rounded">New...</a></li>-->
<!--                    <li><a href="#" class="link-dark d-inline-flex text-decoration-none rounded">Profile</a></li>-->
<!--                    <li><a href="#" class="link-dark d-inline-flex text-decoration-none rounded">Settings</a></li>-->
<!--                    <li><a href="#" class="link-dark d-inline-flex text-decoration-none rounded">Sign out</a></li>-->
<!--                </ul>-->
<!--            </div>-->
<!--        </li>-->
    </ul>
</div>