<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Города внутри фигуры</title>
    <script src="https://api-maps.yandex.ru/2.1/?apikey=e584d941-c02f-4012-bf47-dad715847017&lang=ru_RU"></script>
    <style>
        #map { width: 100%; height: 500px; margin-bottom: 20px; }
        .columns { display: flex; gap: 20px; }
        .column { flex: 1; }
        h4 { margin-bottom: 5px; }
    </style>
</head>
<body>

<h3>Введите два города:</h3>
<input type="text" id="cityFrom" placeholder="Откуда (например, Екатеринбург)">
<input type="text" id="cityTo" placeholder="Куда (например, Москва)">
<button id="drawBtn">Построить фигуры</button>
<p id="distanceDisplay"></p>

<div id="map"></div>
<div class="columns">
    <div class="column">
        <h4>Города вокруг первой точки</h4>
        <ul id="citiesFrom"></ul>
    </div>
    <div class="column">
        <h4>Города вокруг второй точки</h4>
        <ul id="citiesTo"></ul>
    </div>
</div>

<script>
// Данные по городам
const cities = [
  { name: "Москва", region: "Москва", population: 12655050 },
  { name: "Санкт-Петербург", region: "Санкт-Петербург", population: 5384342 },
  { name: "Новосибирск", region: "Новосибирская область", population: 1620162 },
  { name: "Екатеринбург", region: "Свердловская область", population: 1495066 },
  { name: "Казань", region: "Татарстан", population: 1257341 },
  { name: "Нижний Новгород", region: "Нижегородская область", population: 1244251 },
  { name: "Челябинск", region: "Челябинская область", population: 1187960 },
  { name: "Самара", region: "Самарская область", population: 1144759 },
  { name: "Омск", region: "Омская область", population: 1139897 },
  { name: "Ростов-на-Дону", region: "Ростовская область", population: 1137704 },
  { name: "Уфа", region: "Башкортостан", population: 1125933 },
  { name: "Красноярск", region: "Красноярский край", population: 1092851 },
  { name: "Воронеж", region: "Воронежская область", population: 1050602 },
  { name: "Пермь", region: "Пермский край", population: 1049199 },
  { name: "Волгоград", region: "Волгоградская область", population: 1004763 },
  { name: "Краснодар", region: "Краснодарский край", population: 948827 },
  { name: "Саратов", region: "Саратовская область", population: 830155 },
  { name: "Тюмень", region: "Тюменская область", population: 816800 },
  { name: "Тольятти", region: "Самарская область", population: 693072 },
  { name: "Ижевск", region: "Удмуртия", population: 646468 },
  { name: "Барнаул", region: "Алтайский край", population: 631127 },
  { name: "Ульяновск", region: "Ульяновская область", population: 625462 },
  { name: "Иркутск", region: "Иркутская область", population: 617315 },
  { name: "Хабаровск", region: "Хабаровский край", population: 610305 },
  { name: "Махачкала", region: "Дагестан", population: 604266 },
  { name: "Ярославль", region: "Ярославская область", population: 601403 },
  { name: "Владивосток", region: "Приморский край", population: 600871 },
  { name: "Оренбург", region: "Оренбургская область", population: 572819 },
  { name: "Томск", region: "Томская область", population: 568508 },
  { name: "Кемерово", region: "Кемеровская область", population: 552546 },
  { name: "Новокузнецк", region: "Кемеровская область", population: 547885 },
  { name: "Рязань", region: "Рязанская область", population: 525062 },
  { name: "Астрахань", region: "Астраханская область", population: 520662 },
  { name: "Пенза", region: "Пензенская область", population: 519592 },
  { name: "Набережные Челны", region: "Татарстан", population: 513242 },
  { name: "Липецк", region: "Липецкая область", population: 508124 },
  { name: "Тула", region: "Тульская область", population: 501129 },
  { name: "Киров", region: "Кировская область", population: 473668 },
  { name: "Чебоксары", region: "Чувашия", population: 447929 },
  { name: "Улан-Удэ", region: "Бурятия", population: 431922 },
  { name: "Калининград", region: "Калининградская область", population: 431491 },
  { name: "Брянск", region: "Брянская область", population: 415640 },
  { name: "Курск", region: "Курская область", population: 414595 },
  { name: "Иваново", region: "Ивановская область", population: 409277 },
  { name: "Магнитогорск", region: "Челябинская область", population: 408401 },
  { name: "Тверь", region: "Тверская область", population: 403726 },
  { name: "Ставрополь", region: "Ставропольский край", population: 398266 },
  { name: "Севастополь(Оспаривается)", region: "Севастополь", population: 393304 },
  { name: "Нижний Тагил", region: "Свердловская область", population: 361883 },
  { name: "Белгород", region: "Белгородская область", population: 356426 },
  { name: "Архангельск", region: "Архангельская область", population: 348716 },
  { name: "Владимир", region: "Владимирская область", population: 348256 },
  { name: "Сочи", region: "Краснодарский край", population: 343285 },
  { name: "Курган", region: "Курганская область", population: 333640 },
  { name: "Симферополь(Оспаривается)", region: "Крым", population: 332317 },
  { name: "Смоленск", region: "Смоленская область", population: 326863 },
  { name: "Калуга", region: "Калужская область", population: 325185 },
  { name: "Чита", region: "Забайкальский край", population: 323964 },
  { name: "Саранск", region: "Мордовия", population: 318841 },
  { name: "Орёл", region: "Орловская область", population: 317854 },
  { name: "Волжский", region: "Волгоградская область", population: 314436 },
  { name: "Череповец", region: "Вологодская область", population: 312311 },
  { name: "Владикавказ", region: "Северная Осетия — Алания", population: 311635 },
  { name: "Мурманск", region: "Мурманская область", population: 307664 },
  { name: "Сургут", region: "Ханты-Мансийский АО — Югра", population: 306703 },
  { name: "Вологда", region: "Вологодская область", population: 301642 },
  { name: "Тамбов", region: "Тамбовская область", population: 280457 },
  { name: "Стерлитамак", region: "Башкортостан", population: 273432 },
  { name: "Грозный", region: "Чечня", population: 271596 },
  { name: "Якутск", region: "Якутия", population: 269486 },
  { name: "Кострома", region: "Костромская область", population: 268617 },
  { name: "Комсомольск-на-Амуре", region: "Хабаровский край", population: 263906 },
  { name: "Петрозаводск", region: "Карелия", population: 263540 },
  { name: "Таганрог", region: "Ростовская область", population: 257692 },
  { name: "Нижневартовск", region: "Ханты-Мансийский АО — Югра", population: 251860 },
  { name: "Йошкар-Ола", region: "Марий Эл", population: 248688 },
  { name: "Братск", region: "Иркутская область", population: 246348 },
  { name: "Новороссийск", region: "Краснодарский край", population: 241788 },
  { name: "Дзержинск", region: "Нижегородская область", population: 240762 },
  { name: "Шахты", region: "Ростовская область", population: 240152 },
  { name: "Нальчик", region: "Кабардино-Балкария", population: 240095 },
  { name: "Орск", region: "Оренбургская область", population: 238006 },
  { name: "Сыктывкар", region: "Коми", population: 235006 },
  { name: "Нижнекамск", region: "Татарстан", population: 234108 },
  { name: "Ангарск", region: "Иркутская область", population: 233765 },
  { name: "Королёв", region: "Московская область", population: 225858 },
  { name: "Старый Оскол", region: "Белгородская область", population: 221163 },
  { name: "Великий Новгород", region: "Новгородская область", population: 218724 },
  { name: "Балашиха", region: "Московская область", population: 215353 },
  { name: "Благовещенск", region: "Амурская область", population: 214397 },
  { name: "Прокопьевск", region: "Кемеровская область", population: 210150 },
  { name: "Химки", region: "Московская область", population: 207125 },
  { name: "Псков", region: "Псковская область", population: 203974 },
  { name: "Бийск", region: "Алтайский край", population: 203826 },
  { name: "Энгельс", region: "Саратовская область", population: 202838 },
  { name: "Рыбинск", region: "Ярославская область", population: 200771 },
  { name: "Балаково", region: "Саратовская область", population: 199576 },
  { name: "Северодвинск", region: "Архангельская область", population: 192265 },
  { name: "Армавир", region: "Краснодарский край", population: 188897 },
  { name: "Подольск", region: "Московская область", population: 187956 },
  { name: "Южно-Сахалинск", region: "Сахалинская область", population: 181727 },
  { name: "Петропавловск-Камчатский", region: "Камчатский край", population: 179526 },
  { name: "Сызрань", region: "Самарская область", population: 178773 },
  { name: "Норильск", region: "Красноярский край", population: 175301 },
  { name: "Златоуст", region: "Челябинская область", population: 174985 },
  { name: "Каменск-Уральский", region: "Свердловская область", population: 174710 },
  { name: "Мытищи", region: "Московская область", population: 173341 },
  { name: "Люберцы", region: "Московская область", population: 171978 },
  { name: "Волгодонск", region: "Ростовская область", population: 170621 },
  { name: "Новочеркасск", region: "Ростовская область", population: 169039 },
  { name: "Абакан", region: "Хакасия", population: 165183 },
  { name: "Находка", region: "Приморский край", population: 159695 },
  { name: "Уссурийск", region: "Приморский край", population: 157946 },
  { name: "Березники", region: "Пермский край", population: 156350 },
  { name: "Салават", region: "Башкортостан", population: 156085 },
  { name: "Электросталь", region: "Московская область", population: 155324 },
  { name: "Миасс", region: "Челябинская область", population: 151812 },
  { name: "Первоуральск", region: "Свердловская область", population: 149800 },
  { name: "Керчь(Оспаривается)", region: "Крым", population: 147033 },
  { name: "Рубцовск", region: "Алтайский край", population: 146386 },
  { name: "Альметьевск", region: "Татарстан", population: 146309 },
  { name: "Ковров", region: "Владимирская область", population: 145492 },
  { name: "Коломна", region: "Московская область", population: 144642 },
  { name: "Майкоп", region: "Адыгея", population: 144055 },
  { name: "Пятигорск", region: "Ставропольский край", population: 142397 },
  { name: "Одинцово", region: "Московская область", population: 139021 },
  { name: "Копейск", region: "Челябинская область", population: 137604 },
  { name: "Хасавюрт", region: "Дагестан", population: 133929 },
  { name: "Новомосковск", region: "Тульская область", population: 131227 },
  { name: "Кисловодск", region: "Ставропольский край", population: 128502 },
  { name: "Серпухов", region: "Московская область", population: 126496 },
  { name: "Новочебоксарск", region: "Чувашия", population: 124094 },
  { name: "Нефтеюганск", region: "Ханты-Мансийский АО — Югра", population: 123276 },
  { name: "Димитровград", region: "Ульяновская область", population: 122549 },
  { name: "Нефтекамск", region: "Башкортостан", population: 121757 },
  { name: "Черкесск", region: "Карачаево-Черкесия", population: 121439 },
  { name: "Орехово-Зуево", region: "Московская область", population: 120620 },
  { name: "Дербент", region: "Дагестан", population: 119961 },
  { name: "Камышин", region: "Волгоградская область", population: 119924 },
  { name: "Невинномысск", region: "Ставропольский край", population: 118351 },
  { name: "Красногорск", region: "Московская область", population: 116738 },
  { name: "Муром", region: "Владимирская область", population: 116078 },
  { name: "Батайск", region: "Ростовская область", population: 112400 },
  { name: "Новошахтинск", region: "Ростовская область", population: 111087 },
  { name: "Сергиев Посад", region: "Московская область", population: 110878 },
  { name: "Ноябрьск", region: "Ямало-Ненецкий АО", population: 110572 },
  { name: "Щёлково", region: "Московская область", population: 110380 },
  { name: "Кызыл", region: "Тыва", population: 109906 },
  { name: "Октябрьский", region: "Башкортостан", population: 109379 },
  { name: "Ачинск", region: "Красноярский край", population: 109156 },
  { name: "Северск", region: "Томская область", population: 108466 },
  { name: "Новокуйбышевск", region: "Самарская область", population: 108449 },
  { name: "Елец", region: "Липецкая область", population: 108404 },
  { name: "Арзамас", region: "Нижегородская область", population: 106367 },
  { name: "Евпатория(Оспаривается)", region: "Крым", population: 105719 },
  { name: "Обнинск", region: "Калужская область", population: 104798 },
  { name: "Новый Уренгой", region: "Ямало-Ненецкий АО", population: 104144 },
  { name: "Каспийск", region: "Дагестан", population: 103914 },
  { name: "Элиста", region: "Калмыкия", population: 103728 },
  { name: "Пушкино", region: "Московская область", population: 102840 },
  { name: "Жуковский", region: "Московская область", population: 102729 },
  { name: "Артём", region: "Приморский край", population: 102636 },
  { name: "Междуреченск", region: "Кемеровская область", population: 101995 },
  { name: "Ленинск-Кузнецкий", region: "Кемеровская область", population: 101666 },
  { name: "Сарапул", region: "Удмуртия", population: 101390 },
  { name: "Ессентуки", region: "Ставропольский край", population: 100969 },
  { name: "Воткинск", region: "Удмуртия", population: 100034 },
  { name: "Ногинск", region: "Московская область", population: 99762 },
  { name: "Тобольск", region: "Тюменская область", population: 99698 },
  { name: "Ухта", region: "Коми", population: 99642 },
  { name: "Серов", region: "Свердловская область", population: 99381 },
  { name: "Бердск", region: "Новосибирская область", population: 98809 },
  { name: "Великие Луки", region: "Псковская область", population: 98778 },
  { name: "Мичуринск", region: "Тамбовская область", population: 98758 },
  { name: "Киселёвск", region: "Кемеровская область", population: 98382 },
  { name: "Новотроицк", region: "Оренбургская область", population: 97914 },
  { name: "Зеленодольск", region: "Татарстан", population: 97651 },
  { name: "Соликамск", region: "Пермский край", population: 97239 },
  { name: "Раменское", region: "Московская область", population: 96355 },
  { name: "Домодедово", region: "Московская область", population: 96123 },
  { name: "Магадан", region: "Магаданская область", population: 95925 },
  { name: "Глазов", region: "Удмуртия", population: 95835 },
  { name: "Каменск-Шахтинский", region: "Ростовская область", population: 95306 },
  { name: "Железногорск", region: "Курская область", population: 95057 },
  { name: "Канск", region: "Красноярский край", population: 94230 },
  { name: "Назрань", region: "Ингушетия", population: 93357 },
  { name: "Гатчина", region: "Ленинградская область", population: 92566 },
  { name: "Саров", region: "Нижегородская область", population: 92073 },
  { name: "Новоуральск", region: "Свердловская область", population: 91813 },
  { name: "Воскресенск", region: "Московская область", population: 91301 },
  { name: "Долгопрудный", region: "Московская область", population: 90976 },
  { name: "Бугульма", region: "Татарстан", population: 89144 },
  { name: "Кузнецк", region: "Пензенская область", population: 88886 },
  { name: "Губкин", region: "Белгородская область", population: 88562 },
  { name: "Кинешма", region: "Ивановская область", population: 88113 },
  { name: "Ейск", region: "Краснодарский край", population: 87771 },
  { name: "Реутов", region: "Московская область", population: 87195 },
  { name: "Усть-Илимск", region: "Иркутская область", population: 86591 },
  { name: "Железногорск", region: "Красноярский край", population: 85559 },
  { name: "Усолье-Сибирское", region: "Иркутская область", population: 83364 },
  { name: "Чайковский", region: "Пермский край", population: 82933 },
  { name: "Азов", region: "Ростовская область", population: 82882 },
  { name: "Бузулук", region: "Оренбургская область", population: 82816 },
  { name: "Озёрск", region: "Челябинская область", population: 82268 },
  { name: "Балашов", region: "Саратовская область", population: 82222 },
  { name: "Юрга", region: "Кемеровская область", population: 81536 },
  { name: "Кирово-Чепецк", region: "Кировская область", population: 80920 },
  { name: "Кропоткин", region: "Краснодарский край", population: 80743 },
  { name: "Клин", region: "Московская область", population: 80584 },
  { name: "Выборг", region: "Ленинградская область", population: 80013 },
  { name: "Ханты-Мансийск", region: "Ханты-Мансийский АО — Югра", population: 79410 },
  { name: "Троицк", region: "Челябинская область", population: 78637 },
  { name: "Бор", region: "Нижегородская область", population: 78079 },
  { name: "Шадринск", region: "Курганская область", population: 77744 },
  { name: "Белово", region: "Кемеровская область", population: 76752 },
  { name: "Ялта(Оспаривается)", region: "Крым", population: 76746 },
  { name: "Минеральные Воды", region: "Ставропольский край", population: 76715 },
  { name: "Анжеро-Судженск", region: "Кемеровская область", population: 76669 },
  { name: "Биробиджан", region: "Еврейская АО", population: 75419 },
  { name: "Лобня", region: "Московская область", population: 74350 },
  { name: "Новоалтайск", region: "Алтайский край", population: 73134 },
  { name: "Чапаевск", region: "Самарская область", population: 72689 },
  { name: "Георгиевск", region: "Ставропольский край", population: 72126 },
  { name: "Черногорск", region: "Хакасия", population: 72117 },
  { name: "Минусинск", region: "Красноярский край", population: 71171 },
  { name: "Михайловск", region: "Ставропольский край", population: 71018 },
  { name: "Елабуга", region: "Татарстан", population: 70750 },
  { name: "Дубна", region: "Московская область", population: 70569 },
  { name: "Воркута", region: "Коми", population: 70551 },
  { name: "Егорьевск", region: "Московская область", population: 70133 },
  { name: "Асбест", region: "Свердловская область", population: 70067 },
  { name: "Ишим", region: "Тюменская область", population: 69567 },
  { name: "Феодосия(Оспаривается)", region: "Крым", population: 69038 },
  { name: "Белорецк", region: "Башкортостан", population: 68804 },
  { name: "Белогорск", region: "Амурская область", population: 68220 },
  { name: "Кунгур", region: "Пермский край", population: 67857 },
  { name: "Лысьва", region: "Пермский край", population: 67712 },
  { name: "Гуково", region: "Ростовская область", population: 67268 },
  { name: "Ступино", region: "Московская область", population: 66942 },
  { name: "Туймазы", region: "Башкортостан", population: 66849 },
  { name: "Кстово", region: "Нижегородская область", population: 66641 },
  { name: "Вольск", region: "Саратовская область", population: 66520 },
  { name: "Ишимбай", region: "Башкортостан", population: 66259 },
  { name: "Зеленогорск", region: "Красноярский край", population: 66018 },
  { name: "Сосновый Бор", region: "Ленинградская область", population: 65901 },
  { name: "Буйнакск", region: "Дагестан", population: 65735 },
  { name: "Борисоглебск", region: "Воронежская область", population: 65585 },
  { name: "Наро-Фоминск", region: "Московская область", population: 64640 },
  { name: "Будённовск", region: "Ставропольский край", population: 64628 },
  { name: "Донской", region: "Тульская область", population: 64561 },
  { name: "Сунжа", region: "Ингушетия", population: 64493 },
  { name: "Полевской", region: "Свердловская область", population: 64316 },
  { name: "Лениногорск", region: "Татарстан", population: 64145 },
  { name: "Павловский Посад", region: "Московская область", population: 63771 },
  { name: "Славянск-на-Кубани", region: "Краснодарский край", population: 63768 },
  { name: "Заречный", region: "Пензенская область", population: 63579 },
  { name: "Туапсе", region: "Краснодарский край", population: 63233 },
  { name: "Россошь", region: "Воронежская область", population: 62865 },
  { name: "Горно-Алтайск", region: "Алтай", population: 62861 },
  { name: "Кумертау", region: "Башкортостан", population: 62854 },
  { name: "Лабинск", region: "Краснодарский край", population: 62822 },
  { name: "Сибай", region: "Башкортостан", population: 62732 },
  { name: "Клинцы", region: "Брянская область", population: 62510 },
  { name: "Ржев", region: "Тверская область", population: 62026 },
  { name: "Тихорецк", region: "Краснодарский край", population: 61825 },
  { name: "Нерюнгри", region: "Якутия", population: 61746 },
  { name: "Алексин", region: "Тульская область", population: 61738 },
  { name: "Ревда", region: "Свердловская область", population: 61713 },
  { name: "Александров", region: "Владимирская область", population: 61544 },
  { name: "Дмитров", region: "Московская область", population: 61454 },
  { name: "Мелеуз", region: "Башкортостан", population: 61408 },
  { name: "Сальск", region: "Ростовская область", population: 61312 },
  { name: "Лесосибирск", region: "Красноярский край", population: 61146 },
  { name: "Гусь-Хрустальный", region: "Владимирская область", population: 60773 },
  { name: "Чистополь", region: "Татарстан", population: 60703 },
  { name: "Павлово", region: "Нижегородская область", population: 60699 },
  { name: "Чехов", region: "Московская область", population: 60677 },
  { name: "Котлас", region: "Архангельская область", population: 60562 },
  { name: "Белебей", region: "Башкортостан", population: 60183 },
  { name: "Искитим", region: "Новосибирская область", population: 60072 },
  { name: "Краснотурьинск", region: "Свердловская область", population: 59701 },
  { name: "Апатиты", region: "Мурманская область", population: 59690 },
  { name: "Всеволожск", region: "Ленинградская область", population: 59689 },
  { name: "Прохладный", region: "Кабардино-Балкария", population: 59595 },
  { name: "Михайловка", region: "Волгоградская область", population: 59153 },
  { name: "Анапа", region: "Краснодарский край", population: 58983 },
  { name: "Тихвин", region: "Ленинградская область", population: 58843 },
  { name: "Верхняя Пышма", region: "Свердловская область", population: 58707 },
  { name: "Ивантеевка", region: "Московская область", population: 58594 },
  { name: "Свободный", region: "Амурская область", population: 58594 },
  { name: "Шуя", region: "Ивановская область", population: 58528 },
  { name: "Когалым", region: "Ханты-Мансийский АО — Югра", population: 58192 },
  { name: "Щёкино", region: "Тульская область", population: 58154 },
  { name: "Жигулёвск", region: "Самарская область", population: 57565 },
  { name: "Крымск", region: "Краснодарский край", population: 57370 },
  { name: "Вязьма", region: "Смоленская область", population: 57103 },
  { name: "Видное", region: "Московская область", population: 56798 },
  { name: "Арсеньев", region: "Приморский край", population: 56742 },
  { name: "Избербаш", region: "Дагестан", population: 56301 },
  { name: "Выкса", region: "Нижегородская область", population: 56196 },
  { name: "Лиски", region: "Воронежская область", population: 55864 },
  { name: "Волжск", region: "Марий Эл", population: 55671 },
  { name: "Краснокаменск", region: "Забайкальский край", population: 55668 },
  { name: "Фрязино", region: "Московская область", population: 55449 },
  { name: "Узловая", region: "Тульская область", population: 55282 },
  { name: "Лыткарино", region: "Московская область", population: 55147 },
  { name: "Нягань", region: "Ханты-Мансийский АО — Югра", population: 54903 },
  { name: "Рославль", region: "Смоленская область", population: 54898 },
  { name: "Геленджик", region: "Краснодарский край", population: 54813 },
  { name: "Боровичи", region: "Новгородская область", population: 54731 },
  { name: "Тимашёвск", region: "Краснодарский край", population: 53921 },
  { name: "Белореченск", region: "Краснодарский край", population: 53891 },
  { name: "Солнечногорск", region: "Московская область", population: 52996 },
  { name: "Назарово", region: "Красноярский край", population: 52829 },
  { name: "Кириши", region: "Ленинградская область", population: 52826 },
  { name: "Черемхово", region: "Иркутская область", population: 52650 },
  { name: "Краснокамск", region: "Пермский край", population: 52632 },
  { name: "Лесной", region: "Свердловская область", population: 52464 },
  { name: "Вышний Волочёк", region: "Тверская область", population: 52326 },
  { name: "Бугуруслан", region: "Оренбургская область", population: 52249 },
  { name: "Берёзовский", region: "Свердловская область", population: 51583 },
  { name: "Балахна", region: "Нижегородская область", population: 51526 },
  { name: "Ливны", region: "Орловская область", population: 50430 },
  { name: "Донецк", region: "Ростовская область", population: 50085 },
  { name: "Североморск", region: "Мурманская область", population: 50076 },
  { name: "Саяногорск", region: "Хакасия", population: 49889 },
  { name: "Кимры", region: "Тверская область", population: 49623 },
  { name: "Мегион", region: "Ханты-Мансийский АО — Югра", population: 49471 },
  { name: "Кизляр", region: "Дагестан", population: 49169 },
  { name: "Урус-Мартан", region: "Чечня", population: 49071 },
  { name: "Снежинск", region: "Челябинская область", population: 48896 },
  { name: "Кингисепп", region: "Ленинградская область", population: 48667 },
  { name: "Курганинск", region: "Краснодарский край", population: 47974 },
  { name: "Шелехов", region: "Иркутская область", population: 47960 },
  { name: "Можга", region: "Удмуртия", population: 47959 },
  { name: "Сертолово", region: "Ленинградская область", population: 47854 },
  { name: "Ярцево", region: "Смоленская область", population: 47853 },
  { name: "Шали", region: "Чечня", population: 47715 },
  { name: "Отрадный", region: "Самарская область", population: 47709 },
  { name: "Торжок", region: "Тверская область", population: 47702 },
  { name: "Рузаевка", region: "Мордовия", population: 47529 },
  { name: "Волхов", region: "Ленинградская область", population: 47344 },
  { name: "Берёзовский", region: "Кемеровская область", population: 47292 },
  { name: "Куйбышев", region: "Новосибирская область", population: 47278 },
  { name: "Дзержинский", region: "Московская область", population: 47125 },
  { name: "Заринск", region: "Алтайский край", population: 47035 },
  { name: "Грязи", region: "Липецкая область", population: 46798 },
  { name: "Чусовой", region: "Пермский край", population: 46740 },
  { name: "Надым", region: "Ямало-Ненецкий АО", population: 46550 },
  { name: "Верхняя Салда", region: "Свердловская область", population: 46240 },
  { name: "Сафоново", region: "Смоленская область", population: 46116 },
  { name: "Осинники", region: "Кемеровская область", population: 45997 },
  { name: "Кольчугино", region: "Владимирская область", population: 45804 },
  { name: "Гудермес", region: "Чечня", population: 45643 },
  { name: "Канаш", region: "Чувашия", population: 45608 },
  { name: "Рассказово", region: "Тамбовская область", population: 45484 },
  { name: "Сатка", region: "Челябинская область", population: 45465 },
  { name: "Мончегорск", region: "Мурманская область", population: 45381 },
  { name: "Усть-Кут", region: "Иркутская область", population: 45061 },
  { name: "Тулун", region: "Иркутская область", population: 44603 },
  { name: "Шебекино", region: "Белгородская область", population: 44277 },
  { name: "Спасск-Дальний", region: "Приморский край", population: 44166 },
  { name: "Белая Калитва", region: "Ростовская область", population: 43688 },
  { name: "Печора", region: "Коми", population: 43458 },
  { name: "Чебаркуль", region: "Челябинская область", population: 43405 },
  { name: "Радужный", region: "Ханты-Мансийский АО — Югра", population: 43394 },
  { name: "Усть-Лабинск", region: "Краснодарский край", population: 43268 },
  { name: "Мценск", region: "Орловская область", population: 43216 },
  { name: "Мыски", region: "Кемеровская область", population: 43029 },
  { name: "Амурск", region: "Хабаровский край", population: 42977 },
  { name: "Курчатов", region: "Курская область", population: 42691 },
  { name: "Качканар", region: "Свердловская область", population: 42563 },
  { name: "Салехард", region: "Ямало-Ненецкий АО", population: 42494 },
  { name: "Ефремов", region: "Тульская область", population: 42350 },
  { name: "Стрежевой", region: "Томская область", population: 42216 },
  { name: "Аксай", region: "Ростовская область", population: 41984 },
  { name: "Переславль-Залесский", region: "Ярославская область", population: 41923 },
  { name: "Ахтубинск", region: "Астраханская область", population: 41898 },
  { name: "Кашира", region: "Московская область", population: 41880 },
  { name: "Заинск", region: "Татарстан", population: 41798 },
  { name: "Камень-на-Оби", region: "Алтайский край", population: 41787 },
  { name: "Советск", region: "Калининградская область", population: 41709 },
  { name: "Пугачёв", region: "Саратовская область", population: 41705 },
  { name: "Лангепас", region: "Ханты-Мансийский АО — Югра", population: 41675 },
  { name: "Бирск", region: "Башкортостан", population: 41637 },
  { name: "Урюпинск", region: "Волгоградская область", population: 41594 },
  { name: "Моршанск", region: "Тамбовская область", population: 41550 },
  { name: "Пыть-Ях", region: "Ханты-Мансийский АО — Югра", population: 41453 },
  { name: "Конаково", region: "Тверская область", population: 41303 },
  { name: "Ртищево", region: "Саратовская область", population: 41295 },
  { name: "Вязники", region: "Владимирская область", population: 41252 },
  { name: "Кореновск", region: "Краснодарский край", population: 41179 },
  { name: "Усинск", region: "Коми", population: 41100 },
  { name: "Тутаев", region: "Ярославская область", population: 41001 },
  { name: "Красный Сулин", region: "Ростовская область", population: 40866 },
  { name: "Саянск", region: "Иркутская область", population: 40786 },
  { name: "Новодвинск", region: "Архангельская область", population: 40612 },
  { name: "Новозыбков", region: "Брянская область", population: 40552 },
  { name: "Людиново", region: "Калужская область", population: 40550 },
  { name: "Изобильный", region: "Ставропольский край", population: 40546 },
  { name: "Мариинск", region: "Кемеровская область", population: 40522 },
  { name: "Черняховск", region: "Калининградская область", population: 40464 },
  { name: "Заволжье", region: "Нижегородская область", population: 40265 },
  { name: "Апшеронск", region: "Краснодарский край", population: 40229 },
  { name: "Красноуфимск", region: "Свердловская область", population: 39765 },
  { name: "Коряжма", region: "Архангельская область", population: 39629 },
  { name: "Каменка", region: "Пензенская область", population: 39579 },
  { name: "Елизово", region: "Камчатский край", population: 39548 },
  { name: "Фролово", region: "Волгоградская область", population: 39489 },
  { name: "Урай", region: "Ханты-Мансийский АО — Югра", population: 39435 },
  { name: "Большой Камень", region: "Приморский край", population: 39257 },
  { name: "Тосно", region: "Ленинградская область", population: 39127 },
  { name: "Алексеевка", region: "Белгородская область", population: 39026 },
  { name: "Коркино", region: "Челябинская область", population: 38950 },
  { name: "Кыштым", region: "Челябинская область", population: 38950 },
  { name: "Лянтор", region: "Ханты-Мансийский АО — Югра", population: 38922 },
  { name: "Моздок", region: "Северная Осетия — Алания", population: 38748 },
  { name: "Реж", region: "Свердловская область", population: 38709 },
  { name: "Партизанск", region: "Приморский край", population: 38648 },
  { name: "Джанкой(Оспаривается)", region: "Крым", population: 38622 },
  { name: "Шарыпово", region: "Красноярский край", population: 38570 },
  { name: "Светлоград", region: "Ставропольский край", population: 38520 },
  { name: "Сокол", region: "Вологодская область", population: 38454 },
  { name: "Ирбит", region: "Свердловская область", population: 38352 },
  { name: "Гай", region: "Оренбургская область", population: 38302 },
  { name: "Алатырь", region: "Чувашия", population: 38202 },
  { name: "Алапаевск", region: "Свердловская область", population: 38198 },
  { name: "Темрюк", region: "Краснодарский край", population: 38014 },
  { name: "Южноуральск", region: "Челябинская область", population: 37890 },
  { name: "Учалы", region: "Башкортостан", population: 37771 },
  { name: "Вичуга", region: "Ивановская область", population: 37609 },
  { name: "Дальнегорск", region: "Приморский край", population: 37503 },
  { name: "Протвино", region: "Московская область", population: 37308 },
  { name: "Мирный", region: "Якутия", population: 37179 },
  { name: "Нижнеудинск", region: "Иркутская область", population: 37056 },
  { name: "Лесозаводск", region: "Приморский край", population: 36975 },
  { name: "Баксан", region: "Кабардино-Балкария", population: 36857 },
  { name: "Беслан", region: "Северная Осетия — Алания", population: 36724 },
  { name: "Ялуторовск", region: "Тюменская область", population: 36494 },
  { name: "Миллерово", region: "Ростовская область", population: 36493 },
  { name: "Луга", region: "Ленинградская область", population: 36409 },
  { name: "Кизилюрт", region: "Дагестан", population: 36187 },
  { name: "Фурманов", region: "Ивановская область", population: 36149 },
  { name: "Краснознаменск", region: "Московская область", population: 36057 },
  { name: "Зеленокумск", region: "Ставропольский край", population: 35790 },
  { name: "Кулебаки", region: "Нижегородская область", population: 35762 },
  { name: "Добрянка", region: "Пермский край", population: 35720 },
  { name: "Кандалакша", region: "Мурманская область", population: 35659 },
  { name: "Тында", region: "Амурская область", population: 35574 },
  { name: "Тайшет", region: "Иркутская область", population: 35481 },
  { name: "Тавда", region: "Свердловская область", population: 35421 },
  { name: "Сердобск", region: "Пензенская область", population: 35393 },
  { name: "Валуйки", region: "Белгородская область", population: 35322 },
  { name: "Гулькевичи", region: "Краснодарский край", population: 35225 },
  { name: "Вятские Поляны", region: "Кировская область", population: 35159 },
  { name: "Истра", region: "Московская область", population: 35106 },
  { name: "Тейково", region: "Ивановская область", population: 34993 },
  { name: "Абинск", region: "Краснодарский край", population: 34926 },
  { name: "Азнакаево", region: "Татарстан", population: 34859 },
  { name: "Новокубанск", region: "Краснодарский край", population: 34847 },
  { name: "Сухой Лог", region: "Свердловская область", population: 34836 },
  { name: "Углич", region: "Ярославская область", population: 34505 },
  { name: "Кинель", region: "Самарская область", population: 34472 },
  { name: "Благовещенск", region: "Башкортостан", population: 34246 },
  { name: "Югорск", region: "Ханты-Мансийский АО — Югра", population: 34066 },
  { name: "Слободской", region: "Кировская область", population: 33983 },
  { name: "Острогожск", region: "Воронежская область", population: 33842 },
  { name: "Трёхгорный", region: "Челябинская область", population: 33678 },
  { name: "Сланцы", region: "Ленинградская область", population: 33587 },
  { name: "Корсаков", region: "Сахалинская область", population: 33526 },
  { name: "Касимов", region: "Рязанская область", population: 33494 },
  { name: "Шумерля", region: "Чувашия", population: 33412 },
  { name: "Муравленко", region: "Ямало-Ненецкий АО", population: 33401 },
  { name: "Чернушка", region: "Пермский край", population: 33275 },
  { name: "Сосновоборск", region: "Красноярский край", population: 33090 },
  { name: "Кушва", region: "Свердловская область", population: 33027 },
  { name: "Кондопога", region: "Карелия", population: 32978 },
  { name: "Артёмовский", region: "Свердловская область", population: 32878 },
  { name: "Шатура", region: "Московская область", population: 32836 },
  { name: "Благодарный", region: "Ставропольский край", population: 32736 },
  { name: "Балтийск", region: "Калининградская область", population: 32670 },
  { name: "Нововоронеж", region: "Воронежская область", population: 32635 },
  { name: "Нурлат", region: "Татарстан", population: 32600 },
  { name: "Зима", region: "Иркутская область", population: 32522 },
  { name: "Котельники", region: "Московская область", population: 32347 },
  { name: "Приморско-Ахтарск", region: "Краснодарский край", population: 32253 },
  { name: "Старая Русса", region: "Новгородская область", population: 32235 },
  { name: "Инта", region: "Коми", population: 32021 },
  { name: "Аша", region: "Челябинская область", population: 31916 },
  { name: "Богородицк", region: "Тульская область", population: 31897 },
  { name: "Киров", region: "Калужская область", population: 31888 },
  { name: "Котовск", region: "Тамбовская область", population: 31851 },
  { name: "Ростов", region: "Ярославская область", population: 31791 },
  { name: "Богданович", region: "Свердловская область", population: 31752 },
  { name: "Гагарин", region: "Смоленская область", population: 31721 },
  { name: "Нарткала", region: "Кабардино-Балкария", population: 31679 },
  { name: "Великий Устюг", region: "Вологодская область", population: 31664 },
  { name: "Маркс", region: "Саратовская область", population: 31535 },
  { name: "Можайск", region: "Московская область", population: 31388 },
  { name: "Борзя", region: "Забайкальский край", population: 31376 },
  { name: "Ликино-Дулёво", region: "Московская область", population: 31331 },
  { name: "Дюртюли", region: "Башкортостан", population: 31272 },
  { name: "Петровск", region: "Саратовская область", population: 31158 },
  { name: "Карабулак", region: "Ингушетия", population: 31081 },
  { name: "Малгобек", region: "Ингушетия", population: 31076 },
  { name: "Удомля", region: "Тверская область", population: 31048 },
  { name: "Холмск", region: "Сахалинская область", population: 30936 },
  { name: "Кудымкар", region: "Пермский край", population: 30711 },
  { name: "Городец", region: "Нижегородская область", population: 30699 },
  { name: "Дагестанские Огни", region: "Дагестан", population: 30671 },
  { name: "Усть-Джегута", region: "Карачаево-Черкесия", population: 30602 },
  { name: "Верхний Уфалей", region: "Челябинская область", population: 30504 },
  { name: "Малоярославец", region: "Калужская область", population: 30401 },
  { name: "Скопин", region: "Рязанская область", population: 30374 },
  { name: "Славгород", region: "Алтайский край", population: 30370 },
  { name: "Мирный", region: "Архангельская область", population: 30259 },
  { name: "Барабинск", region: "Новосибирская область", population: 30250 },
  { name: "Еманжелинск", region: "Челябинская область", population: 30218 },
  { name: "Сорочинск", region: "Оренбургская область", population: 30136 },
  { name: "Горячий Ключ", region: "Краснодарский край", population: 30093 },
  { name: "Киржач", region: "Владимирская область", population: 30044 },
  { name: "Луховицы", region: "Московская область", population: 29849 },
  { name: "Десногорск", region: "Смоленская область", population: 29677 },
  { name: "Сегежа", region: "Карелия", population: 29660 },
  { name: "Аргун", region: "Чечня", population: 29528 },
  { name: "Дятьково", region: "Брянская область", population: 29438 },
  { name: "Кохма", region: "Ивановская область", population: 29408 },
  { name: "Знаменск", region: "Астраханская область", population: 29357 },
  { name: "Дедовск", region: "Московская область", population: 29280 },
  { name: "Североуральск", region: "Свердловская область", population: 29279 },
  { name: "Карталы", region: "Челябинская область", population: 29136 },
  { name: "Карпинск", region: "Свердловская область", population: 29118 },
  { name: "Алушта(Оспаривается)", region: "Крым", population: 29078 },
  { name: "Карасук", region: "Новосибирская область", population: 28929 },
  { name: "Кировск", region: "Мурманская область", population: 28659 },
  { name: "Топки", region: "Кемеровская область", population: 28642 },
  { name: "Алейск", region: "Алтайский край", population: 28528 },
  { name: "Кимовск", region: "Тульская область", population: 28493 },
  { name: "Костомукша", region: "Карелия", population: 28433 },
  { name: "Дивногорск", region: "Красноярский край", population: 28271 },
  { name: "Гусев", region: "Калининградская область", population: 28260 },
  { name: "Похвистнево", region: "Самарская область", population: 28181 },
  { name: "Сасово", region: "Рязанская область", population: 28117 },
  { name: "Сосногорск", region: "Коми", population: 27809 },
  { name: "Советская Гавань", region: "Хабаровский край", population: 27712 },
  { name: "Нефтекумск", region: "Ставропольский край", population: 27700 },
  { name: "Морозовск", region: "Ростовская область", population: 27644 },
  { name: "Полысаево", region: "Кемеровская область", population: 27624 },
  { name: "Дальнереченск", region: "Приморский край", population: 27601 },
  { name: "Губаха", region: "Пермский край", population: 27544 },
  { name: "Бахчисарай(Оспаривается)", region: "Крым", population: 27448 },
  { name: "Медногорск", region: "Оренбургская область", population: 27253 },
  { name: "Октябрьск", region: "Самарская область", population: 27244 },
  { name: "Бутурлиновка", region: "Воронежская область", population: 27208 },
  { name: "Янаул", region: "Башкортостан", population: 26988 },
  { name: "Лабытнанги", region: "Ямало-Ненецкий АО", population: 26948 },
  { name: "Калач-на-Дону", region: "Волгоградская область", population: 26892 },
  { name: "Камышлов", region: "Свердловская область", population: 26875 },
  { name: "Зерноград", region: "Ростовская область", population: 26850 },
  { name: "Уварово", region: "Тамбовская область", population: 26829 },
  { name: "Заречный", region: "Свердловская область", population: 26803 },
  { name: "Новоалександровск", region: "Ставропольский край", population: 26759 },
  { name: "Майский", region: "Кабардино-Балкария", population: 26755 },
  { name: "Тара", region: "Омская область", population: 26664 },
  { name: "Новопавловск", region: "Ставропольский край", population: 26556 },
  { name: "Советский", region: "Ханты-Мансийский АО — Югра", population: 26434 },
  { name: "Балабаново", region: "Калужская область", population: 26337 },
  { name: "Родники", region: "Ивановская область", population: 26318 },
  { name: "Соль-Илецк", region: "Оренбургская область", population: 26308 },
  { name: "Красноармейск", region: "Московская область", population: 26294 },
  { name: "Красноперекопск(Оспаривается)", region: "Крым", population: 26268 },
  { name: "Унеча", region: "Брянская область", population: 26196 },
  { name: "Кувандык", region: "Оренбургская область", population: 26176 },
  { name: "Обь", region: "Новосибирская область", population: 26137 },
  { name: "Железногорск-Илимский", region: "Иркутская область", population: 26134 },
  { name: "Татарск", region: "Новосибирская область", population: 26114 },
  { name: "Ипатово", region: "Ставропольский край", population: 26055 },
  { name: "Семилуки", region: "Воронежская область", population: 26025 },
  { name: "Исилькуль", region: "Омская область", population: 25905 },
  { name: "Озёры", region: "Московская область", population: 25788 },
  { name: "Буй", region: "Костромская область", population: 25763 },
  { name: "Курчалой", region: "Чечня", population: 25672 },
  { name: "Заводоуковск", region: "Тюменская область", population: 25657 },
  { name: "Кировск", region: "Ленинградская область", population: 25633 },
  { name: "Аткарск", region: "Саратовская область", population: 25620 },
  { name: "Асино", region: "Томская область", population: 25614 },
  { name: "Киреевск", region: "Тульская область", population: 25585 },
  { name: "Богородск", region: "Нижегородская область", population: 25497 },
  { name: "Тайга", region: "Кемеровская область", population: 25330 },
  { name: "Невьянск", region: "Свердловская область", population: 25147 },
  { name: "Саки(Оспаривается)", region: "Крым", population: 25146 },
  { name: "Павловск", region: "Воронежская область", population: 25126 },
  { name: "Зея", region: "Амурская область", population: 25042 },
  { name: "Котельнич", region: "Кировская область", population: 24979 },
  { name: "Красноуральск", region: "Свердловская область", population: 24973 },
  { name: "Ленск", region: "Якутия", population: 24955 },
  { name: "Гурьевск", region: "Кемеровская область", population: 24816 },
  { name: "Зарайск", region: "Московская область", population: 24648 },
  { name: "Бежецк", region: "Тверская область", population: 24517 },
  { name: "Железноводск", region: "Ставропольский край", population: 24496 },
  { name: "Дубна", region: "Московская область", population: 24412 },
  { name: "Тихвин", region: "Ленинградская область", population: 24391 },
  { name: "Сергач", region: "Нижегородская область", population: 24362 },
  { name: "Солнечногорск", region: "Московская область", population: 24310 },
  { name: "Берёзовский", region: "Свердловская область", population: 24288 },
  { name: "Лесной", region: "Свердловская область", population: 24234 },
  { name: "Новая Ляля", region: "Свердловская область", population: 24192 },
  { name: "Боровичи", region: "Новгородская область", population: 24180 },
  { name: "Егорьевск", region: "Московская область", population: 24140 },
  { name: "Югорск", region: "Ханты-Мансийский АО — Югра", population: 24125 },
  { name: "Чехов", region: "Московская область", population: 24097 },
  { name: "Лобня", region: "Московская область", population: 24082 },
  { name: "Кушва", region: "Свердловская область", population: 24071 },
  { name: "Берёзовский", region: "Кемеровская область", population: 24058 },
  { name: "Луховицы", region: "Московская область", population: 24037 },
  { name: "Сосновоборск", region: "Красноярский край", population: 24022 },
  { name: "Морозовск", region: "Ростовская область", population: 24010 },
  { name: "Полевской", region: "Свердловская область", population: 23996 },
  { name: "Зеленогорск", region: "Красноярский край", population: 23987 },
  { name: "Новомосковск", region: "Тульская область", population: 23963 },
  { name: "Светлоград", region: "Ставропольский край", population: 23950 },
  { name: "Канаш", region: "Чувашия", population: 23944 },
  { name: "Сорочинск", region: "Оренбургская область", population: 23920 },
  { name: "Пушкин", region: "Московская область", population: 23897 },
  { name: "Южноуральск", region: "Челябинская область", population: 23885 },
  { name: "Керчь(Оспаривается)", region: "Крым", population: 23870 },
  { name: "Шахты", region: "Ростовская область", population: 23855 },
  { name: "Клинцы", region: "Брянская область", population: 23842 },
  { name: "Кропоткин", region: "Краснодарский край", population: 23820 },
  { name: "Краснокамск", region: "Пермский край", population: 23805 },
  { name: "Железногорск", region: "Красноярский край", population: 23790 },
  { name: "Котлас", region: "Архангельская область", population: 23776 },
  { name: "Алексин", region: "Тульская область", population: 23762 },
  { name: "Ипатово", region: "Ставропольский край", population: 23748 },
  { name: "Зерноград", region: "Ростовская область", population: 23736 },
  { name: "Кириши", region: "Ленинградская область", population: 23722 },
  { name: "Истра", region: "Московская область", population: 23710 },
  { name: "Краснокаменск", region: "Забайкальский край", population: 23698 },
  { name: "Дмитров", region: "Московская область", population: 23687 },
  { name: "Лесозаводск", region: "Приморский край", population: 23674 },
  { name: "Грязи", region: "Липецкая область", population: 23661 },
  { name: "Черняховск", region: "Калининградская область", population: 23650 },
  { name: "Губкин", region: "Белгородская область", population: 23638 },
  { name: "Нововоронеж", region: "Воронежская область", population: 23625 },
  { name: "Кимовск", region: "Тульская область", population: 23612 },
  { name: "Железногорск-Илимский", region: "Иркутская область", population: 23600 }
];

// --- Инициализация карты ---
ymaps.ready(init);
let map;

function init() {
    map = new ymaps.Map("map", {
        center: [55.7558, 37.6176],
        zoom: 5
    });

    document.getElementById('drawBtn').addEventListener('click', function() {
        const cityFrom = document.getElementById('cityFrom').value;
        const cityTo = document.getElementById('cityTo').value;

        if (!cityFrom || !cityTo) return alert("Введите оба города");

        Promise.all([
            ymaps.geocode(cityFrom, {kind: 'locality', results: 1}),
            ymaps.geocode(cityTo, {kind: 'locality', results: 1})
        ]).then(function(results){
            const geoFrom = results[0].geoObjects.get(0);
            const geoTo = results[1].geoObjects.get(0);

            if (!geoFrom || !geoTo) return alert("Город не найден");

            const coordsFrom = geoFrom.geometry.getCoordinates();
            const coordsTo = geoTo.geometry.getCoordinates();

            map.geoObjects.removeAll(); // очищаем карту
            document.getElementById('citiesFrom').innerHTML = "";
            document.getElementById('citiesTo').innerHTML = "";

            // метки городов
            map.geoObjects.add(new ymaps.Placemark(coordsFrom, { balloonContent: cityFrom }));
            map.geoObjects.add(new ymaps.Placemark(coordsTo, { balloonContent: cityTo }));

            // расстояние между городами (в метрах)
            const distanceMeters = ymaps.coordSystem.geo.getDistance(coordsFrom, coordsTo);
            const distanceKm = distanceMeters / 1000;
            document.getElementById('distanceDisplay').textContent =
                "Расстояние между городами: " + distanceKm.toFixed(2) + " км";

            // выбираем параметры фигур в зависимости от расстояния
            const params = chooseParamsByDistance(distanceKm);
            // создаём фигуры
            const figureFrom = buildCityFigure(coordsFrom, coordsTo, cityFrom,
                "rgba(0,150,255,0.3)", "rgba(0,150,255,0.15)", 'citiesFrom',
                params.triangleDistKm, params.circleRadiusKm, params.semicircle);
            const figureTo = buildCityFigure(coordsTo, coordsFrom, cityTo,
                "rgba(255,100,0,0.3)", "rgba(255,100,0,0.15)", 'citiesTo',
                params.triangleDistKm, params.circleRadiusKm, params.semicircle);

            map.geoObjects.add(figureFrom);
            map.geoObjects.add(figureTo);

            map.setBounds([coordsFrom, coordsTo], { checkZoomRange: true, zoomMargin: 50 });
        });
    });
}

// --- Выбор параметров по длине маршрута (в километрах) ---
// Интерпретация:
// >4000: треугольник до 1500км, круг до 500км
// 3000..4000: треугольник до 800км, круг до 300км
// 2000..2999: треугольник до 500км, полукруг до 200км
// 1000..1999: треугольник до 400км, круг до 150км
// 500..999: треугольник до 300км, круг до 100км
// <500: треугольник до 100км, круг до 50км
function chooseParamsByDistance(distKm) {
    if (distKm > 4000) {
        return { triangleDistKm: 1500, circleRadiusKm: 500, semicircle: false };
    } else if (distKm >= 3000) {
        return { triangleDistKm: 800, circleRadiusKm: 300, semicircle: false };
    } else if (distKm >= 2000) {
        return { triangleDistKm: 500, circleRadiusKm: 200, semicircle: true }; // полукруг
    } else if (distKm >= 1000) {
        return { triangleDistKm: 400, circleRadiusKm: 150, semicircle: false };
    } else if (distKm >= 500) {
        return { triangleDistKm: 300, circleRadiusKm: 100, semicircle: false };
    } else {
        return { triangleDistKm: 100, circleRadiusKm: 50, semicircle: false };
    }
}

// --- Вспомогательные функции геометрии ---
function offsetCoord(lat, lon, distanceMeters, angleDeg) {
    // возвращает [lat, lon]
    const R = 6371000;
    const angleRad = angleDeg * Math.PI / 180;
    const dLat = (distanceMeters * Math.cos(angleRad)) / R;
    const dLon = (distanceMeters * Math.sin(angleRad)) / (R * Math.cos(lat * Math.PI/180));
    return [lat + dLat * 180/Math.PI, lon + dLon * 180/Math.PI];
}

// азимут в градусах от from к to
function getAzimuth(from, to) {
    const lat1 = from[0] * Math.PI/180;
    const lon1 = from[1] * Math.PI/180;
    const lat2 = to[0] * Math.PI/180;
    const lon2 = to[1] * Math.PI/180;
    const dLon = lon2 - lon1;
    const y = Math.sin(dLon) * Math.cos(lat2);
    const x = Math.cos(lat1)*Math.sin(lat2) - Math.sin(lat1)*Math.cos(lat2)*Math.cos(dLon);
    const brng = Math.atan2(y, x) * 180/Math.PI;
    return (brng + 360) % 360;
}

// Проверка: находится ли точка внутри полигона
// polygonCoords: массив точек [ [lat,lon], [lat,lon], ... ]
function pointInPolygon(point, polygonCoords) {
    const x = point[1], y = point[0]; // lon = x, lat = y
    let inside = false;
    for (let i = 0, j = polygonCoords.length - 1; i < polygonCoords.length; j = i++) {
        const xi = polygonCoords[i][1], yi = polygonCoords[i][0];
        const xj = polygonCoords[j][1], yj = polygonCoords[j][0];

        const intersect = ((yi > y) !== (yj > y)) &&
            (x < (xj - xi) * (y - yi) / (yj - yi) + xi);
        if (intersect) inside = !inside;
    }
    return inside;
}

// --- Поиск и добавление городов (учитывает круг и треугольник) ---
// center: [lat,lon]
// circleRadiusKm: радиус круга в км
// triangleCoords: массив [center, leftPoint, rightPoint] (в формате [lat,lon])
// minPopulation: минимум населения для фильтра
// listElementId: id UL для вывода имен
function addCitiesFromArrayWithTriangle(center, circleRadiusKm, triangleCoords, minPopulation = 20000, listElementId = null) {
    const addedCities = new Set();
    const radiusMeters = circleRadiusKm * 1000;

    cities.forEach(city => {
        if (city.population < minPopulation) return;

        ymaps.geocode(city.name, { kind: 'locality', results: 1 }).then(res => {
            const geoObject = res.geoObjects.get(0);
            if (!geoObject) return;

            const coords = geoObject.geometry.getCoordinates();
            const key = city.name + '_' + coords[0].toFixed(5) + '_' + coords[1].toFixed(5);
            if (addedCities.has(key)) return;

            const distance = ymaps.coordSystem.geo.getDistance(center, coords); // в метрах
            const inCircle = distance <= radiusMeters;
            const inTriangle = pointInPolygon(coords, triangleCoords);

            if (inCircle || inTriangle) {
                addedCities.add(key);

                map.geoObjects.add(new ymaps.Placemark(coords, {
                    balloonContent: `${city.name} (${city.population.toLocaleString()})`
                }));

                if (listElementId) {
                    const ul = document.getElementById(listElementId);
                    if (ul) {
                        const li = document.createElement('li');
                        li.textContent = city.name + ' — ' + city.population.toLocaleString();
                        ul.appendChild(li);
                    }
                }
            }
        }).catch(err => {
            // игнорируем отдельные ошибки геокодирования
            console.warn('geocode error for', city.name, err);
        });
    });
}

// --- Построение фигуры (круг или полукруг) и треугольника ---
// center: [lat,lon]
// otherCity: [lat,lon] - для расчёта направления (азимута)
// triangleDistKm: длина треугольника в километрах (точки вершины на этом расстоянии)
// circleRadiusKm: радиус круга в километрах
// semicircle: boolean - рисовать полукруг вместо полного круга
function buildCityFigure(center, otherCity, cityName, fillColor, circleColor, listElementId, triangleDistKm, circleRadiusKm, semicircle=false) {
    const collection = new ymaps.GeoObjectCollection();

    // Круг или полукруг
    if (!semicircle) {
        const circle = new ymaps.Circle([center, circleRadiusKm * 1000], { balloonContent: cityName + ` (круг ${circleRadiusKm} км)` }, {
            fillColor: circleColor,
            strokeColor: "#000000",
            strokeWidth: 2,
            opacity: 0.4
        });
        collection.add(circle);
    } else {
        // Полукруг: строим полиго́н, аппроксимация дуги
        const azimuth = getAzimuth(otherCity, center); // направление на центр от другого города
        // Для полукруга центр ориентирован «в сторону» другого города: полукруг будет в полуокружности, обращенной к другому городу
        const startAngle = azimuth - 90;
        const endAngle = azimuth + 90;
        const points = [];
        const steps = 40;
        // добавляем крайние точки дуги
        for (let i = 0; i <= steps; i++) {
            const a = startAngle + (endAngle - startAngle) * (i / steps);
            const pt = offsetCoord(center[0], center[1], circleRadiusKm * 1000, a);
            points.push(pt);
        }
        // замыкаем полукруг через центр (делаем сектор)
        const polygonCoords = [ [center].concat([]) ]; // not used direct
        const polyPoints = [ center ].concat(points);
        const semi = new ymaps.Polygon([polyPoints], { balloonContent: cityName + ` (полукруг ${circleRadiusKm} км)` }, {
            fillColor: circleColor,
            strokeColor: "#000000",
            strokeWidth: 2
        });
        collection.add(semi);
    }

    // Треугольник / сектор: вершины на расстоянии triangleDistKm
    const triangleDistMeters = triangleDistKm * 1000;
    const az = getAzimuth(otherCity, center); // азимут от otherCity к center
    // угол сектора — оставим похожим на исходный: левый -45, правый +35 (можно изменить)
    const leftAngle = az - 45;
    const rightAngle = az + 35;
    const pointLeft = offsetCoord(center[0], center[1], triangleDistMeters, leftAngle);
    const pointRight = offsetCoord(center[0], center[1], triangleDistMeters, rightAngle);

    const triangleCoords = [center, pointLeft, pointRight];
    const triangle = new ymaps.Polygon([triangleCoords], { balloonContent: cityName + ` (треугольник до ${triangleDistKm} км)` }, {
        fillColor: fillColor,
        strokeColor: "#000000",
        strokeWidth: 2
    });
    collection.add(triangle);

    // Добавляем города внутри круга/полукруга и треугольника
    addCitiesFromArrayWithTriangle(center, circleRadiusKm, triangleCoords, 20000, listElementId);

    return collection;
}
</script>
</body>
</html>
