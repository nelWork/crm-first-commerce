<?php
/** @var App\User\Contoller\Common\HomeController $controller */
/** @var String $titlePage */
$controller->view('Components/head');

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
                                        <option value="2025-09-20 - 2025-10-31"
                                            <?php if($period == '2025-09-20 - 2025-10-31') echo 'selected';  ?>
                                        >
                                            20.09.2025 - 31.10.2025
                                        </option>
                                        <option value="2025-11-01 - 2025-11-30"
                                            <?php if($period == '2025-11-01 - 2025-11-30') echo 'selected';  ?>
                                        >
                                            01.11.2025 - 30.11.2025
                                        </option>
                                        <option value="2026-01-01 - 2026-01-31"
                                            <?php if($period == '2026-01-01 - 2026-01-31') echo 'selected';  ?>
                                        >
                                            01.01.2026 - 31.01.2026
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
                            <th>Оклад</th>
                            <th>Привлечение клиентом / бонусы</th>
                            <th>Экспедирование</th>
                            <th>ПРР</th>
                            <th>Зарплата</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php  foreach  ($declaration['managerList'] as $manager): if(!isset($manager['fix_salary'])) continue; ?>
                        <tr style="background-color: whitesmoke">
                            <td>
                                <a href="/analytics/salary/statistics?id=<?php echo $manager['hrefSalary']; ?>" target="_blank">
                                    <b><?php echo $manager['surname'] .' ' .$manager['name'] .' ' .$manager['lastname']; ?></b>
                                </a>
                            </td>
                            <td>
                                <?php echo $manager['fix_salary']; ?>
                            </td>
                            <td>
                                <?php echo $manager['additional_salary']; ?>
                            </td>
                            <td>
                                <?php echo $manager['percentApplications']; ?>
                            </td>
                            <td>
                                <?php echo $manager['percentPrrApplications']; ?>
                            </td>
                            <td>
                                <?php echo $manager['salary']; ?>
                            </td>
                        </tr>

                        <?php
                        if(!empty($manager['subordinates'])):
                        foreach ($manager['subordinates'] as $subordinates): if(!isset($subordinates['fix_salary'])) continue;
                        ?>
                        <tr>
                            <td>
                                <a href="/analytics/salary/statistics?id=<?php echo $subordinates['hrefSalary']; ?>" target="_blank">
                                <?php echo $subordinates['surname'] .' ' .$subordinates['name'] .' ' .$subordinates['lastname']; ?>
                                </a>
                            </td>
                            <td>
                                <?php echo $subordinates['fix_salary']; ?>
                            </td>
                            <td>
                                <?php echo $subordinates['additional_salary']; ?>
                            </td>
                            <td>
                                <?php echo $subordinates['percentApplications']; ?>
                            </td>
                            <td>
                                <?php echo $subordinates['percentPrrApplications']; ?>
                            </td>
                            <td>
                                <?php echo $subordinates['salary']; ?>
                            </td>
                        </tr>


                        <?php endforeach; ?>
                        <!-- <tr style="border-bottom: 1px solid black;background-color: white;font-weight: 600">
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td><?php echo $manager['sumSalary']; ?></td>
                        </tr> -->
                        <?php    endif; ?>

                        <?php endforeach; ?>
                        <tr style="background-color: darkorange; ">
                            <td></td>
                            <td><?php echo $declaration['sumFixSalary']; ?></td>
                            <td></td>
                            <td><?php echo $declaration['sumPercentApplications']; ?></td>
                            <td><?php echo $declaration['sumPercentPrrApplications']; ?></td>
                            <td><?php echo $declaration['sumSalary']; ?></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </section>
        </main>


        <script>
            $("#select-period").change(function () {
                let period = $(this).val();

                document.location.href = '/analytics/declaration?period=' + period;
            });
        </script>

    </body>
