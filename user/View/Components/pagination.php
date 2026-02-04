<?php
/** @var int $countPage */
/** @var int $page */
/** @var int $elementsPage */
/** @var string $link */
$link = trim($link,'?');
$link = trim($link,'&');

//dd($link);
?>
<div class="d-flex justify-content-between p-4 " >
    <nav aria-label="...">
        <?php if($countPage > 1): ?>
            <ul class="pagination">
                <?php if($page != 1): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?php echo ($page - 1) .'&' . $link ; ?>">Предыдущая</a>
                </li>
                <?php endif; ?>
                <?php for($i = 1; $i <= $countPage; $i++): ?>
                    <?php if($i > 5) break; ?>
                    <li class="page-item">
                        <a class="page-link <?php if($i == $page) echo 'active'; ?>" href="?page=<?php echo $i .'&' .$link;?>">
                            <?php echo $i; ?>
                        </a>
                    </li>
                <?php endfor; ?>

                <?php if($countPage > 6): ?>
                    <?php if($page > 6): ?>
                        <li class="page-item">
                            <a class="page-link">...</a>
                        </li>
                    <?php endif; ?>
                    <?php if($page >= 6): ?>
                        <li class="page-item">
                            <a class="page-link active" href="?page=<?php echo $page .'&' .$link; ?>">
                                <?php echo $page; ?>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if($page < $countPage - 1): ?>
                        <li class="page-item">
                            <a class="page-link">...</a>
                        </li>
                    <?php endif; ?>

                    <?php if($page != $countPage): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?php echo $countPage .'&' .$link; ?>">
                            <?php echo $countPage; ?>
                        </a>
                    </li>
                    <?php endif; ?>
                <?php endif; ?>
                <?php if($page < $countPage): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?php echo ($page + 1) .'&' .$link; ?>">Следующая</a>
                    </li>
                <?php endif; ?>
            </ul>
        <?php endif; ?>
    </nav>
    <div class="count-element">
        Показывать по
        <select name="" id="js-select-count-page">
            <option value="25" <?php if($elementsPage == 25) echo 'selected'; ?>>25</option>
            <option value="50" <?php if($elementsPage == 50) echo 'selected'; ?>>50</option>
            <option value="75" <?php if($elementsPage == 75) echo 'selected'; ?>>75</option>
            <option value="100" <?php if($elementsPage == 100) echo 'selected'; ?>>100</option>
        </select>
    </div>
</div>

<script>
    $('#js-select-count-page').change(function () {
        let count = $(this).val();

        console.log(count);
        
        $.ajax({
            url:'/ajax/change-count-element-page',
            method:'POST',
            data: {count: count},
            success: function (response) {
                document.location.reload();
            }
        });
    });
</script>