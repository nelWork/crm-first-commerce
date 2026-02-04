<?php

/** @var App\User\Contoller\Common\HomeController $controller */
/** @var String $titlePage */
$controller->view('Components/head');
// dd($statistics['managerList']);

?>

<body>
    <?php $controller->view('Components/header'); ?>

    <main class="analytics">
        <div class="sub-header" style="padding-bottom: 0;">
            <div class="wrapper">
                <?php $controller->view('Components/breadcrumbs'); ?>

                <h3 class="sub-header-title"><?php echo $titlePage; ?></h3>

                <div class="row" style="margin-top: 40px;">
                    <div class="col-8">
                        <?php $controller->view('Analytics/sub-header'); ?>
                    </div>
                    <div class="col">
                        <div class="d-flex justify-content-end">
                            <!-- <form action="/journal-list" method="post">
                                <textarea name="app-id" style="display:none"><?php echo $netProfitStat['textId']; ?></textarea>
                                <input type="hidden" name="type" value="report">
                                <input type="hidden" name="name" value="ДДC за <?php echo $date; ?>">
                                <button type="submit" class="btn btn-success mx-4">
                                    Открыть в журнале
                                </button>
                            </form> -->
                            <button id="save-excel" class="btn btn-success me-4">Скачать в EXCEL</button>
                            <input type="text" name="period" id="date-picker" class="form-control w-50" value="<?php echo $date; ?>">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <section class="analytics-applications__list mb-5">
            <div class="wrapper p-2 mb-5" style="min-height: 400px">
                <h2 class="text-center my-4">
                    Статистика по перевозчикам за <?php echo $date; ?>
                </h2>

                
            </div>
        </section>

        <script>
            $('#save-excel').click(function() {
                $.ajax({
                    url: '/analytics/ajax/get-excel-carrier-stat',
                    type: 'POST',
                    data: {date: '<?php echo $date; ?>'},
                    success: function(response) {
                        console.log(response);
                        // let data = JSON.parse(response);

                        // console.log(data);
                        download_file(
                            'Статистика по перезвозчикам <?php echo $date; ?>.xlsx',
                            '/doc/journal.xlsx'
                        );


                    }
                });
            });
            new AirDatepicker('#date-picker', {
                    range: true,
                    multipleDatesSeparator: ' - ',
                    buttons: ['clear'],
                    onSelect({ formattedDate }) {
                        if (formattedDate && formattedDate.length === 2) {
                            const period = formattedDate.join(' - ');
                            window.location.href = '/analytics/carrier-stat?date=' + encodeURIComponent(period);
                        }
                    }
                });
        </script>



</body>