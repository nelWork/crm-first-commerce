<div class="sub-header sub-header-application">
    <div class="wrapper">
        <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="/">
                        <svg width="17" height="15" viewBox="0 0 17 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M8.4375 0.914062C8.68359 0.722656 9.03906 0.722656 9.28516 0.914062L16.5039 7.03906C16.7773 7.28516 16.8047 7.69531 16.5859 7.96875C16.3398 8.24219 15.9297 8.26953 15.6562 8.05078L15 7.47656V12.5625C15 13.793 14.0156 14.75 12.8125 14.75H4.9375C3.70703 14.75 2.75 13.793 2.75 12.5625V7.47656L2.06641 8.05078C1.79297 8.26953 1.38281 8.24219 1.13672 7.96875C0.917969 7.69531 0.945312 7.28516 1.21875 7.03906L8.4375 0.914062ZM4.0625 6.35547V12.5625C4.0625 13.0547 4.44531 13.4375 4.9375 13.4375H12.8125C13.2773 13.4375 13.6875 13.0547 13.6875 12.5625V6.35547L8.875 2.28125L4.0625 6.35547Z" fill="#7E8299"></path>
                        </svg>
                    </a>
                </li>
                <li class="breadcrumb-item" aria-current="page" style="margin-top: 2px;">
                    <a href="/prr/list">Заявки ПРР</a>
                </li>
                <li class="breadcrumb-item active" style="margin-top: 2px;" aria-current="page"><?php echo $titlePage;?></li>
            </ol>
        </nav>

        <div class="row align-items-end">
            <div class="col-4 p-0">
                <h3 class="sub-header-title"><?php echo $titlePage; ?></h3>
            </div>
            <div class="col">
                <div class="d-flex justify-content-end">
                    <button class="btn btn-success-theme duplicate me-4" id="duplicate">
                        Дублировать
                    </button>

                    <button class="btn btn-add-light application_add" id="application_add_up">
                        <span class="svg-icon svg-icon-3">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="5" fill="currentColor"></rect>
                                <rect x="10.8891" y="17.8033" width="12" height="2" rx="1" transform="rotate(-90 10.8891 17.8033)" fill="currentColor"></rect>
                                <rect x="6.01041" y="10.9247" width="12" height="2" rx="1" fill="currentColor"></rect>
                            </svg>
                        </span>
                        Сохранить
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>