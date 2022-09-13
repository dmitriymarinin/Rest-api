<?php

namespace App\Controllers\Storage;

class MagazineStorage
{
    public $id;
    public $name;
    public $descr;
    public $image;
    public $authors;
    public $date;

    //Название. (Обязательное поле)
    // Короткое описание. (Необязательное поле)
    // Картинка. (jpg или png, не больше 2 Мб, должна сохраняться в отдельную папку и иметь уникальное имя файла)
    // Авторы (Обязательное поле, может быть несколько авторов у одного журнала, должна быть возможность выбирать из списка авторов, который создается отдельно).
    // Дата выпуска журнала.
    public function __construct($name, $image, $authors, $date, $descr = null)
    {
        $this->id = rand(0, 100);
        $this->name = $name;
        $this->image = $image;
        $this->authors = $authors;
        $this->date = $date;
        $this->descr = $descr;
    }
}