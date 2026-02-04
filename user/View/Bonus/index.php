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

            <div class="row mb-4" style="margin-top: 40px;">
                <div class="col">

                </div>
                <div class="col-3">
                    <div class="d-flex justify-content-end">
                        <input type="text" class="form-control mb-4" id="date-picker" placeholder="Выберите период"
                        value="<?php echo $period; ?>">
                        <script type="module">
                            let button = {
                                content: 'Применить',
                                className: 'custom-button-classname',
                                onClick: (dp) => {
                                    $('#date-picker').trigger('change')
                                }
                            }
                            new AirDatepicker('#date-picker',{
                                range: true,
                                multipleDatesSeparator: ' - ',
                                buttons: ['clear',button]
                            });
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="analytics-applications__list mb-5">
        <div class="wrapper p-2 mb-5">
                <h3 class="text-center my-4">
                    Вычеты за <?php echo $period; ?>
                </h3>
            <table class="table table-bordered">
                <thead>
                    <th>Название клиента</th>
                    <th>Логист</th>
                    <th>Кол-во заявок</th>
                    <th>Общая сумма</th>
                </thead>
                <tbody id="clients-table"></tbody>
            </table>

            <script>
                const data = <?php echo json_encode($resultList); ?>;

                console.log(data)

                let tableBody = $("#clients-table");

                data.forEach((client, index) => {
                    let clientName = client.client_data.name;
                    let applicationCount = client.application_count;
                    let totalSum = client.total_deduction_sum;
                    let users = client.users;

                    let logistNames = Object.values(users)
                        .map(user => `<a href="#" class="logist" data-index="${index}" data-logist="${user.name}">${user.name}</a>`)
                        .join(", ");

                    tableBody.append(`
            <tr>
                <td>${clientName}</td>
                <td>${logistNames}</td>
                <td>${applicationCount}</td>
                <td>${totalSum}</td>
            </tr>
        `);
                });

                $(".logist").click(function (e) {
                    e.preventDefault();
                    let index = $(this).data("index");
                    let logistName = $(this).data("logist");
                    let clientData = data[index];

                    let tableId = `applications-${index}-${logistName.replace(/\s+/g, "-")}`;

                    // Если таблицы еще нет, создаем её
                    if ($(`#${tableId}`).length === 0) {
                        let applicationsBody = `
            <div class="table-container" id="table-container-${index}-${logistName.replace(/\s+/g, "-")}">
                <div class="text-end mb-2">
                    <button class="btn btn-secondary copy-table-button" data-table-id="${tableId}">Копировать таблицу</button>
                </div>
                <table class="table table-sm table-striped mt-2" id="${tableId}">
                    <thead>
                        <th>Логист</th>
                        <th>Номер заявки</th>
                        <th>Маршрут</th>
                        <th>Дата заявки</th>
                        <th>Дата оплаты</th>
                        <th>Сумма вычета</th>
                    </thead>
                    <tbody>`;

                        Object.values(clientData.users).forEach(user => {
                            if (user.name === logistName) {
                                user.applications.forEach(app => {
                                    applicationsBody += `
                        <tr>
                            <td>${logistName}</td>
                            <td>${app.application_number}</td>
                            <td>${app.routes}</td>
                            <td>${app.date}</td>
                            <td>${app.full_payment_date_Client}</td>
                            <td>${app.deduction_sum}</td>
                        </tr>`;
                                });
                            }
                        });

                        applicationsBody += `</tbody></table></div>`;

                        $(this).closest("tr").after(`<tr><td colspan="4">${applicationsBody}</td></tr>`);

                        // Добавляем событие для копирования содержимого таблицы
                        $(".copy-table-button").off("click").on("click", function () {
                            const tableId = $(this).data("table-id");
                            const table = document.getElementById(tableId);

                            // Создаем текстовое содержимое ТОЛЬКО из строк <tbody>
                            let textToCopy = "";
                            const rows = Array.from(table.querySelectorAll("tbody tr")); // Выбираем только тело таблицы (без заголовков)
                            console.log(rows);
                            let isHead = true;
                            rows.forEach(row => {
                                if(isHead) {
                                    isHead = false;
                                    return;
                                }
                                const cells = Array.from(row.cells).map((cell, index) => {
                                    // Настройка имени столбца вручную
                                    switch (index) {
                                        case 0:
                                            return `Логист - ${cell.innerText.trim()}`;
                                        case 1:
                                            return `Номер заявки - ${cell.innerText.trim()}`;
                                        case 2:
                                            return `Маршрут - ${cell.innerText.trim()}`;
                                        case 3:
                                            return `Дата заявки - ${cell.innerText.trim()}`;
                                        case 4:
                                            return `Дата оплаты - ${cell.innerText.trim()}`;
                                        case 5:
                                            return `Сумма - ${cell.innerText.trim()}`;
                                        default:
                                            return "";
                                    }
                                });

                                // Объединение данных строки и добавление отступа
                                textToCopy += cells.join("\n") + "\n\n";
                            });

                            textToCopy.replace(`Логист - Логист\n
                                Номер заявки - Номер заявки\n
                                Маршрут - Маршрут\n
                                Дата заявки - Дата заявки\n
                                Дата оплаты - Дата оплаты\n
                                Сумма - Сумма вычета\n`,'');

                            // Убедимся, что текст содержит только строки из <tbody>
                            console.log("Copied text: ", textToCopy);

                            // Копируем текст в буфер обмена
                            let tempElement = document.createElement("textarea");
                            document.body.appendChild(tempElement);
                            tempElement.value = textToCopy.trim(); // Убираем лишние пробелы и переносы в конце
                            tempElement.select();
                            document.execCommand("copy");

                            document.body.removeChild(tempElement);
                            alert("Содержимое таблицы скопировано.");
                        });
                    } else {
                        // Если таблица уже есть, скрываем/открываем её
                        $(`#table-container-${index}-${logistName.replace(/\s+/g, "-")}`).toggle();
                    }
                });

            </script>
        </div>
    </section>
</main>


<script>
    $("#date-picker").change(function () {
        let period = $(this).val();

        document.location.href = '/bonus?period=' + period;
    });
</script>

</body>
