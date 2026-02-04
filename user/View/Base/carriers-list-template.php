<section class="base-clients__list carrier <?php if(empty($listCarriers)) echo 'd-none'; ?>">
        <div class="wrapper">

            <div class="post-list__header d-flex">
                <div class="post-list__header-item">НАЗВАНИЕ ОРГАНИЗАЦИИ</div>
                <div class="post-list__header-item">ИНН</div>
                <div class="post-list__header-item">КОНТАКТЫ</div>
            </div>

            <?php foreach($listCarriers as $carrier) { ?>
                <div class="post-list__items">
                    <a href="/carrier?id=<?php echo $carrier['id']?>" class="post-list__item item d-flex">
                        <div class="w-100 d-flex">
                            <div class="item__title"><?php echo $carrier['name']?></div>
                            <div class="item__inn"><?php echo $carrier['inn']?></div>
                            <div class="item__fio">
                                <?php $info = explode('||', $carrier['info']);?>
                                <?php foreach ($info as $item): ?>
                                    <div class=""><?php echo $item; ?></div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </a>
                </div>
            <?php }?>

        </div>
    </section>
<script>
    function countCarrier(){
        $('#span-quantity-carrier').text(<?php echo count($listCarriers); ?>);
    }
    countCarrier();
</script>