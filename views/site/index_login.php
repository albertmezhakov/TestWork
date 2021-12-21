<?php
/*
 * Copyright (c) 2021.
 * TestWork
 * Author: BerhtAdal
 */

/* @var $this yii\web\View
 * @var $model ReminderForm
 * @var $db Reminders
 * @var $pages Pagination
 * @var $username string
 */

use app\models\ReminderForm;
use app\models\Reminders;
use buibr\datepicker\DatePicker;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;
use yii\bootstrap4\LinkPager;
use yii\data\Pagination;

$this->title = 'Напоминания';

?>
<div class="site-index">

    <div class="jumbotron text-center bg-transparent">
        <h1 class="display-4">Вы, <?= $username; ?></h1>

        <p class="lead">Для добавления напоминания воспользуйтесь<br>формой ниже. А для удаления воспользуйтесь<br> красной кнопкой напротив напоминания или<br> командой бота /cancel ID.
    </div>

    <div class="body-content">
        <div class="row">
            <?php
            $form = ActiveForm::begin([
                'id' => 'Rem_add',
                'options' => ['class' => 'row justify-content-between w-100', 'template' => "{input}"]

            ]) ?>

            <div class="mb-3  w-50">
                <?= $form->field($model, 'content')->label(false)->input('content', ['placeholder' => 'Текст(300 символов)']) ?>
            </div>
            <div class="mb-3  w-25">
                <div class=" input-group nex-datepicker-container date">
                    <?= $form->field($model, 'date')->label(false)->widget(
                        DatePicker::className(), [
                        'addon' => false,
                        'placeholder' => 'Дата',
                        'clientOptions' => [
                            'format' => 'DD.MM.YYYY HH:mm',
                        ],
                    ]); ?>
                </div>
            </div>
            <div class="mb-3">
                <?= Html::submitButton('Добавить', ['class' => 'btn btn-primary']) ?>
            </div>
            <?php ActiveForm::end() ?>
        </div>
        <div class="row">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Text</th>
                        <th scope="col">Date</th>
                        <th scope="col">Delete</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($db as $item): ?>
                    <tr id="id_<?= $item->id; ?>">
                        <th scope="row"><?= $item->id; ?></th>
                        <td><?= mb_strimwidth($item->content, 0, 90, "..."); ?></td>
                        <td><?= date('d.m.y G:i', $item->unix_time); ?></td>
                        <td><button type="submit" class="btn btn-danger" onclick="del_rem(<?= $item->id; ?>)">&times;</button></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <?php echo LinkPager::widget([
                'pagination' => $pages,
            ]); ?>
        </div>
        <script type="text/javascript">
            function del_rem(id) {
                let xhr = new XMLHttpRequest();
                let params = 'id=' + encodeURIComponent(id);
                xhr.open("GET", '/api/delete?' + params, true);
                xhr.send();
                document.querySelector('#id_' + id).remove()
            }
        </script>
    </div>
</div>
