<?php

/** @var App\User\Contoller\Common\HomeController $controller */
/** @var String $titlePage */
/** @var array $applicationList */
/** @var array $userList */
/** @var array $carrierList */
/** @var array $clientList */
/** @var array $condition */
/** @var array $searchData */
/** @var int $countPage */
/** @var int $page */
/** @var string $link */
/** @var bool $isFullCRMAccess */

$controller->view('Components/head');
//dd($applicationList);
?>
<body>
<?php $controller->view('Components/header'); ?>

<style>
    .js-progress-bar{
        width: 100%;
        background-color: whitesmoke;
        border-radius: 6px;
        height: 20px;
        display: flex;
        position: relative;
    }
    .line{
        height: 20px;
        border-bottom-left-radius: 6px;
        border-top-left-radius: 6px;
        text-align: center;
        color: white;
        font-weight: 600;
    }
    .line-15-percent{
        background-color: #0a53be;
    }
    .line-20-percent{
        background-color: darkorange;
    }
    .line-25-percent{
        background-color: #0f5132;
    }
    .legends{
        display: flex;
        width: 100%;
        position: absolute;
        top: 30px;
    }
    .plan-percent-15{
        width: 48%;
    }
    .plan-percent-20{
        width: 10%;
    }
    .tags{
        position: absolute;
        width: 100%;
        display: flex;
        height: 20px;
        top: -3px;
    }
    .tag-20-percent:before{
        content: '|';
        font-weight: 600;
        font-size: 18px;
        width: 2px;
        height: 20px;
        color: white;
        position: absolute;
        right: 50%;
    }
    .tag-25-percent:before{
        content: '|';
        font-weight: 600;
        font-size: 18px;
        width: 2px;
        height: 20px;
        color: white;
        position: absolute;
        right: 40%;
    }
    .tag-20-percent.plan-2:before{
        right: 60%;
    }
    .tag-25-percent.plan-2:before{
        right: 40%;
    }
    .plan-percent-15.plan-2{
        width: 38%;
    }
    .plan-percent-20.plan-2{
        width: 20%;
    }
</style>

<?php


    $onePercent = $plan['quantity_min_25'] / 60 ;

?>
<main class="applications">
    <div class="sub-header" style="padding-bottom: 40px;">
        <div class="wrapper">
            <?php $controller->view('Components/breadcrumbs'); ?>

            <h3 class="sub-header-title "><?php echo $titlePage; ?></h3>

        </div>
    </div>

    <div class="wrapper ">
        <div class="post-list p-5">
            <div class="js-progress-bar <?php if($plan['id'] > 2) echo  'd-none'; ?>">
                <div class="line line-<?php echo $planExecution['percent']; ?>-percent"
                     data-percent-line="<?php echo $planExecution['quantity'] / $onePercent;  ?>"
                     data-percent-app="<?php echo $planExecution['percent']; ?>"
                     data-sum="<?php echo number_format($planExecution['quantity'], '2', ',' , ' '); ?>₽"></div>
                <div class="legends">
                    <div class="plan-2 plan-percent-15">0</div>
                    <div class="plan-2 plan-percent-20"><?php echo number_format($plan['quantity_min_20'], '0', ',' , ' '); ?></div>
                    <div class="plan-2 plan-percent-25"><?php echo number_format($plan['quantity_min_25'], '0', ',' , ' '); ?></div>
                </div>
                <div class="tags">
                    <div class="plan-2 tag-20-percent"></div>
                    <div class="plan-2 tag-25-percent"></div>
                </div>
            </div>
        </div>
    </div>

</main>


<script>
    $(document).ready(function () {
        $.each($('.line'), function (index,item){
            let percentLine = $(this).data('percent-line');
            $(this).css({'width': percentLine + '%'});
            let percentApp = $(this).data('percent-app');
            let sum = $(this).data('sum')
            $(this).text(sum + ' (' + percentApp + '%)')
        })
    })
</script>

</body>

</html>