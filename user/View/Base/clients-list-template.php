<section class="base-clients__list <?php if(empty($listClients)) echo 'd-none'; ?>">
        <div class="wrapper" style="">
            <div class="post-list__header d-flex">
                <div class="post-list__header-item client">НАЗВАНИЕ ОРГАНИЗАЦИИ</div>
                <div class="post-list__header-item client">ИНН</div>
                <div class="post-list__header-item client">ФИО</div>
                <div class="post-list__header-item client">ТЕЛЕФОН</div>
                <div class="post-list__header-item client">ДОЛЖНОСТЬ</div>
                <div class="post-list__header-item client">ЛОГИСТ</div>
                <div class="post-list__header-item client">ФОРМА ОПЛАТЫ / ОТСРОЧКА</div>
<!--                <div class="post-list__header-item client"></div>-->
<!--                <div class="post-list__header-item"></div>-->
            </div>

            <?php foreach($listClients as $client) { ?>
                <div class="post-list__items">
                    <a href="/client?id=<?php echo $client['id']?>" class="post-list__item item d-flex">
                        <div class="w-100 d-flex " style="align-items: center">
                            <div class="item__title"><?php echo $client['name']?></div>
                            <div class="item__inn"><?php echo $client['inn']?></div>
                            <div class="item__fio">
                                <?php echo $client['full_name']?>
                            </div>
                            <div class="item__tel">
                                <?php echo $client['phone']?>
                            </div>
                            <div class="item__job">
                                <?php echo $client['job_title']?>
                            </div>
                            <div class="item__gruz">
                                <?php foreach ($client['managersAccess'] as $manager): ?>
                                    <div class="manager_gray_wrap"><?php echo $manager['surname'] .' ' .$manager['name']; ?></div>
                                <?php endforeach; ?>
                            </div>
                            <div class="item__summ">
                                <?php echo $client['format_work']; ?>
                                <div style="text-decoration: underline"><?php echo $client['payment_deferment']; ?></div>
                            </div>

                        </div>

                    </a>
                </div>
            <?php }?>

        </div>
    </section>

<script>
    function countClient(){
        $('#span-quantity-client').text(<?php echo count($listClients); ?>);
    }
    countClient();
</script>