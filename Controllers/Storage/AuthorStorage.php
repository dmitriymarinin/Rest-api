<?php

namespace App\Controllers\Storage;

class AuthorStorage
{
    public $id;
    public $surname;
    public $name;
    public $patronymic;

    // Фамилия (Обязательное поле, не короче 3 символов)
    // Имя (Обязательное, не пустое)
    // Отчество (Необязательное)

    /**
     * @throws \Exception
     */
    public function __construct($surname, $name, $patronymic = '')
    {
        $this->id = rand(0, 100);
        $this->setSurname($surname);
        $this->setName($name);
        $this->patronymic = $patronymic;
    }

    protected function setSurname($surname)
    {
        if (mb_strlen($surname) > 3) {
            $this->surname = $surname;
        } else {
            throw new \Exception('Фамилия должна быть больше 3 символов');
        }
    }

    protected function setName($name)
    {
        if (!empty($name)) {
            $this->name = $name;
        } else {
            throw new \Exception('Имя не должно быть пустым');
        }
    }
}