<?php

namespace App\Controllers;

use App\Controllers\Storage\MagazineStorage;
use Exception;

class MagazineController extends AbstractController
{
    const FILE_PATH = '../magazines.json';
    const IMAGES_PATH = '../images/';
    const MAX_SIZE_FILES = 2 * 1024 * 1024;
    public $magazinesList = [];

    public function __construct()
    {
        parent::__construct();
        $this->read();
    }

    /**
     * post /magazine/add
     * body:
     * {
     * "magazine": {
     * "name": "value"
     * "descr": "value"
     * "image": "value"
     * "authors": "value"
     * "date": "value"
     * }
     * }
     */
    public function add()
    {
        if (isset($_FILES['image']) && $_FILES['image']['size'] > 0) {

            $this->addFiles();

            $magazine = new MagazineStorage($_POST['name'], $_FILES['image'], $_POST['authors'], $_POST['date'], $_POST['descr'] ?? null);
            move_uploaded_file($_FILES['image']['tmp_name'], self::IMAGES_PATH . $_FILES['image']['name']);

            if (file_exists(self::FILE_PATH)) {
                $this->magazinesList = json_decode(file_get_contents(self::FILE_PATH));
            }
            $this->magazinesList[] = $magazine;
            $this->store();
        }

        return $this->response->json([['response' => 'OK',
            'request' => $magazine,]]);
    }


    /**
     * post /magazine/update/{id}
     * body:
     * {
     * "magazine": {
     * "name": "value"
     * "descr": "value"
     * "image": "value"
     * "authors": "value"
     * "date": "value"
     * }
     * }
     */
    public function update($magazineId)
    {
        foreach ($this->magazinesList as $key => $magazine) {
            if ($magazine->id === intval($magazineId)) {
                $this->magazinesList[$key]->name = $_POST['name'] ?? $this->magazinesList[$key]->name;
                $this->magazinesList[$key]->descr = $_POST['descr'] ?? $this->magazinesList[$key]->descr;

                if (isset($_FILES) && !empty($_FILES)) {
                    $this->addFiles();
                    $this->magazinesList[$key]->image = $_FILES['image'] ?? $this->magazinesList[$key]->image;
                    move_uploaded_file($_FILES['image']['tmp_name'], self::IMAGES_PATH . $_FILES['image']['name']);
                } else {
                    $this->magazinesList[$key]->image = $_POST['image'] ?? $this->magazinesList[$key]->image;
                }
                $this->magazinesList[$key]->authors = $_POST['authors'] ?? $this->magazinesList[$key]->authors;
                $this->magazinesList[$key]->date = $_POST['date'] ?? $this->magazinesList[$key]->date;
                $this->store();

                return $this->response->json([
                    [
                        'response' => 'OK',
                        'request' => $magazine,
                    ]
                ]);
            }
        }
    }

    /**
     * post /magazine/delete/{id}
     * body:
     * {
     * }
     */
    public function delete($magazineId)
    {
        foreach ($this->magazinesList as $key => $magazine) {
            if ($magazine->id === intval($magazineId)) {
                unset($this->magazinesList[$key]);
                $this->store();

                return $this->response->json([
                    [
                        'response' => 'OK',
                        'request' => $magazineId . ' ' . 'deleted',
                    ]
                ]);
            }
        }
    }

    /**
     * get /magazine/list?page={}&perPage={}
     * body:
     * {
     * }
     */
    public function list()
    {
        if (isset($_GET['page']) || isset($_GET['perPage'])) {
            $limit = $_GET['perPage'] ?? null;
            $page = !isset($_GET['page']) ? 1 : $_GET['page'];
            $offset = ($page - 1) * $limit;
            $listPage = array_splice($this->magazinesList, $offset, $limit);

            return $this->response->json([
                [
                    'response' => 'OK',
                    'request' => $listPage
                ]
            ]);
        } else {
            return $this->response->json([
                [
                    'response' => 'OK',
                    'request' => $this->magazinesList
                ]
            ]);
        }
    }

    public function store()
    {
        file_put_contents(self::FILE_PATH, json_encode($this->magazinesList));
    }

    public function read()
    {
        if (file_exists(self::FILE_PATH)) {
            $this->magazinesList = json_decode(file_get_contents(self::FILE_PATH));
        }
    }

    public function addFiles()
    {
        if (!($_FILES['image']['type'] === 'image/png' || $_FILES['image']['type'] === 'image/jpg')) {
            throw new Exception('Недопустимый формат файла');
        }

        if ($_FILES['image']['size'] > self::MAX_SIZE_FILES) {
            throw new Exception('Размер файла не должен превышать 2 МБ');
        }
    }
}