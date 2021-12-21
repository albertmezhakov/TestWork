<?php
/*
 * Copyright (c) 2021.
 * TestWork
 * Author: BerhtAdal
 */

namespace app\models;


/*
 * Copyright (c) 2021.
 * TestWork
 * Author: BerhtAdal
 */

namespace app\models;

use yii\base\Model;

class ReminderForm extends Model
{
    public $content;
    public $date;


    public function rules()
    {
        return [
            ['date', 'required', 'message' => 'Заполните дату'],
            ['date', 'datetime', 'format' => 'php:d.m.Y H:i', 'message' => 'Неправильный формат даты'],
            ['content', 'required', 'message' => 'Заполните текст'],
            ['content', 'string', 'max' => 300, 'message' => 'Максимальная длинна текста 300 символов']

        ];
    }
}
