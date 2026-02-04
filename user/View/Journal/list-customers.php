<ul class="nav nav-pills justify-content-center w-100 mb-4">
    <?php foreach($customers as $item): ?>
    <li class="nav-item">
        <a class="nav-link js-tab-task js-tab-customer <?php if($item['id'] == 1) echo 'active'; ?>"
           aria-current="page" href="#" data-id-customer="<?php echo $item['id']; ?>">
            <?php echo $item['name']; ?>
        </a>
    </li>
    <?php endforeach; ?>
</ul>