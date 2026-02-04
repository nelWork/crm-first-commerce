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
                                    <select name="" id="select-period" class="form-select" style="font-size: 16px;font-weight: bold">
                                        <option value="" disabled selected>Выберите период</option>
                                        <option value="2025-01-20 - 2025-02-19"
                                            <?php if($period == '2025-01-20 - 2025-02-19') echo 'selected';  ?>
                                        >
                                            20.01.2025 - 19.02.2025
                                        </option>
                                        <option value="2025-02-20 - 2025-03-19"
                                            <?php if($period == '2025-02-20 - 2025-03-19') echo 'selected';  ?>
                                        >
                                            20.02.2025 - 19.03.2025
                                        </option>
                                        <option value="2025-03-20 - 2025-04-19"
                                            <?php if($period == '2025-03-20 - 2025-04-19') echo 'selected';  ?>
                                        >
                                            20.03.2025 - 19.04.2025
                                        </option>
                                        <option value="2025-04-20 - 2025-05-19"
                                            <?php if($period == '2025-04-20 - 2025-05-19') echo 'selected';  ?>
                                        >
                                            20.04.2025 - 19.05.2025
                                        </option>
                                         <option value="2025-05-20 - 2025-06-19"
                                            <?php if($period == '2025-05-20 - 2025-06-19') echo 'selected';  ?>
                                        >
                                            20.05.2025 - 19.06.2025
                                        </option>
                                        <option value="2025-06-20 - 2025-07-19"
                                            <?php if($period == '2025-06-20 - 2025-07-19') echo 'selected';  ?>
                                        >
                                            20.06.2025 - 19.07.2025
                                        </option>
                                        <option value="2025-07-20 - 2025-08-19"
                                            <?php if($period == '2025-07-20 - 2025-08-19') echo 'selected';  ?>
                                        >
                                            20.07.2025 - 19.08.2025
                                        </option>
                                        <option value="2025-08-20 - 2025-09-19"
                                            <?php if($period == '2025-08-20 - 2025-09-19') echo 'selected';  ?>
                                        >
                                            20.08.2025 - 19.09.2025
                                        </option>
                                    </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <section class="analytics-applications__list mb-5">
                <div class="wrapper p-2 mb-5">
                    <?php if($period != ''): $period = explode(' - ', $period) ; ?>
                        <h3 class="text-center my-4">
                            Данные за
                            <?php echo date('d.m.Y', strtotime($period[0])) .' - ' . date('d.m.Y',strtotime($period[1]));  ?>
                        </h3>
                    <?php endif; ?>
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>Имя логиста</th>
                            <th>Дата регистрации</th>
                            <th>Тип плана</th>
                            <th>План</th>
                            <th>Факт выполнения</th>
                            <th>Процент</th>
                            <th>Маржинальность</th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($statistics['managerList'] as $manager): if($manager['active'] == 0) continue; ?>
                                <tr style="background-color: whitesmoke">
                                    <td>
                                        <b><?php echo $manager['surname'] .' ' .$manager['name'] .' ' .$manager['lastname']; ?></b>
                                    </td>
                                    <td>
                                        <?php echo date('d.m.Y', strtotime($manager['registr_date'])); ?>
                                    </td>
                                    <td>
                                        <?php switch ($manager['plan']['id_plan']){
                                            case 1:
                                                echo "Основной (более 6 месяцев)";
                                                break;
                                            case 2:
                                                echo "По истечению 3 месяца (более 3 месяцев)";
                                                break;
                                            case 3:
                                                echo "3-ий месяц";
                                                break;
                                            case 4:
                                                echo "2 месяца (до 2 месяцев)";
                                                break;
                                        }?>
                                    </td>
                                    <td>
                                        <?php
                                            switch ($manager['plan']['percent']){
                                                case 15:
                                                    echo number_format(
                                                            $statistics['plans'][$manager['plan']['id_plan'] - 1]['quantity_min_20'],
                                                            2,'.', ' ');
                                                    break;
                                                case 20:
                                                case 25:
                                                    echo number_format(
                                                            $statistics['plans'][$manager['plan']['id_plan'] - 1]['quantity_min_25'],
                                                            2,'.', ' ');

                                                    break;
                                            }
                                        ?>
                                    </td>
                                    <td>
                                        <?php echo number_format($manager['plan']['quantity'], 2, '.', ' '); ?>

                                    </td>
                                    <td>
                                        <?php echo $manager['plan']['percent'] / 100; ?>

                                    </td>
                                    <td>
                                        <?php echo number_format($manager['marginality'],2,'.', ' '); ?>
                                    </td>
                                </tr>

                                <?php
                                    if(!empty($manager['subordinates'])):
                                        foreach ($manager['subordinates'] as $subordinates):
                                ?>
                                            <tr>
                                                <td>
                                                    <?php echo $subordinates['surname'] .' ' .$subordinates['name'] .' ' .$subordinates['lastname']; ?>
                                                </td>
                                                <td>
                                                    <?php echo date('d.m.Y', strtotime($subordinates['registr_date'])); ?>
                                                </td>
                                                <td>
                                                    <?php switch ($subordinates['plan']['id_plan']){
                                                        case 1:
                                                            echo "Основной (более 6 месяцев)";
                                                            break;
                                                        case 2:
                                                            echo "По истечению 3 месяца (более 3 месяцев)";
                                                            break;
                                                        case 3:
                                                            echo "3-ий месяц";
                                                            break;
                                                        case 4:
                                                            echo "2 месяца (до 2 месяцев)";
                                                            break;
                                                        default:
                                                            echo '';
                                                            break;
                                                    }?>
                                                </td>
                                                <td>
                                                    <?php
                                                        switch ($subordinates['plan']['percent']){
                                                            case 15:
                                                                echo number_format(
                                                                    $statistics['plans'][$subordinates['plan']['id_plan'] - 1]['quantity_min_20'],
                                                                2,'.', ' ');
                                                                break;
                                                            case 20:
                                                            case 25:
                                                                echo number_format(
                                                                    $statistics['plans'][$subordinates['plan']['id_plan'] - 1]['quantity_min_25'],
                                                                2,'.', ' ');
                                                                break;

                                                        }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php echo number_format($subordinates['plan']['quantity'], 2, '.', ' '); ?>

                                                </td>
                                                <td>
                                                    <?php echo $subordinates['plan']['percent'] / 100; ?>

                                                </td>
                                                <td>
                                                    <?php echo number_format($subordinates['marginality'],2,'.', ' '); ?>
                                                </td>
                                            </tr>



                                <?php endforeach; ?>
                                        <tr style="border-bottom: 1px solid black;background-color: white;font-weight: 600">
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td><?php echo $manager['sumPlanROP']; ?></td>
                                            <td><?php echo $manager['sumQuantityROP']; ?></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                <?php    endif; ?>

                            <?php endforeach; ?>
                        <tr style="background-color: darkorange;">
                            <td></td>
                            <td></td>
                            <td></td>
                            <td style="font-weight: 600"><?php echo $statistics['sumPlan'] ?></td>
                            <td style="font-weight: 600"><?php echo $statistics['sumQuantity'] ?></td>
                            <td></td>
                            <td></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </section>
        </main>


        <script>
            $("#select-period").change(function () {
                let period = $(this).val();

                document.location.href = '/analytics?period=' + period;
            });
        </script>

    </body>
