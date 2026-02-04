<?php
/**
 * @var App\User\Contoller\Common\ApplicationController $controller
 */
/** @var App\User\Model\Application\ApplicationPage $application */
/** @var array $comments */
$TSApplicationData = $TSApplication->get();
$controller->view('Components/head');
//dd($application);

?>


<div class="w-100 comment__add-input d-flex flex-column mb-5" id="comment__add-input" data-id-app="<?php echo $TSApplicationData['id']; ?>">
    <div class="d-flex w-100 mb-4">
        <img src="<?php echo $controller->auth->user()->avatar(); ?>" alt="" class="avatar">
        <textarea class="w-100 add-comment-input" placeholder="Оставить комментарий" required></textarea>
    </div>
    <div class="comment__buttons">
        <button class="btn btn-success add-comment-button">Добавить</button>
        <button class="btn btn-light close-input-comment">Отмена</button>
    </div>
</div>

<div class="w-100 comment__add-input edit d-none flex-column mb-5"  id="comment__edit-input" data-id-app="<?php echo $TSApplicationData['id']; ?>" data-id-comment="0">
    <div class="d-flex w-100 mb-4">
        <img src="<?php echo $controller->auth->user()->avatar(); ?>" alt="" class="avatar">
        <textarea class="w-100 add-comment-input edit"
                  id="edit-comment-input" placeholder="Оставить комментарий" required></textarea>
    </div>
    <div class="comment__buttons">
        <button class="btn btn-success edit-comment-button">Изменить</button>
        <button class="btn btn-light close-input-comment">Отмена</button>
    </div>
</div>

<div class="comments-list">

</div>
