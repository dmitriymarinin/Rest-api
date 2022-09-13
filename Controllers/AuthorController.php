<?php

namespace App\Controllers;

use App\Controllers\Storage\AuthorStorage;

class AuthorController extends AbstractController
{
    const FILE_PATH_AUTHOR = '../authors.json';
    public $authorsList = [];

    public function __construct()
    {
        parent::__construct();
        $this->read();
    }

    /**
     * @throws \Exception
     */
    public function addAuthor()
    {
        if (!isset($_POST['surname'])) {
            throw new \Exception('Введите Фамилию автора');
        }

        if (!isset($_POST['name'])) {
            throw new \Exception('Введите Имя автора');
        }

        $author = new AuthorStorage($_POST['surname'], $_POST['name'], $_POST['patronymic'] ?? null);
        $this->authorsList[] = $author;
        $this->store();

        return $this->response->json([['response' => 'OK',
            'request' => $author,]]);
    }

    public function update($authorId)
    {
        foreach ($this->authorsList as $key => $author) {
            if ($author->id === intval($authorId)) {
                $this->authorsList[$key]->surname = $_POST['surname'] ?? $this->authorsList[$key]->surname;
                $this->authorsList[$key]->name = $_POST['name'] ?? $this->authorsList[$key]->name;
                $this->authorsList[$key]->patronymic = $_POST['patronymic'] ?? $this->authorsList[$key]->patronymic;
                $this->store();

                return $this->response->json([
                    [
                        'response' => 'OK',
                        'request' => $author,
                    ]
                ]);
            }
        }
    }

    public function delete($authorId)
    {
        foreach ($this->authorsList as $key => $author) {
            if ($author->id === intval($authorId)) {
                unset($this->authorsList[$key]);
                $this->store();

                return $this->response->json([
                    [
                        'response' => 'OK',
                        'request' => $authorId . ' ' . 'deleted',
                    ]
                ]);
            }
        }
    }

    public function list()
    {
        if (isset($_GET['page']) || isset($_GET['perPage'])) {
            $limit = $_GET['perPage'] ?? null;
            $page = !isset($_GET['page']) ? 1 : $_GET['page'];
            $offset = ($page - 1) * $limit;
            $listPage = array_splice($this->authorsList, $offset, $limit);

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
                    'request' => $this->authorsList
                ]
            ]);
        }
    }

    public function store()
    {
        file_put_contents(self::FILE_PATH_AUTHOR, json_encode($this->authorsList));
    }

    public function read()
    {
        if (file_exists(self::FILE_PATH_AUTHOR)) {
            $this->authorsList = json_decode(file_get_contents(self::FILE_PATH_AUTHOR));
        }
    }
}