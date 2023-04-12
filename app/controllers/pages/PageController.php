<?php

namespace App\controllers\pages;

use App\models\pages\PageModel;

class PageController
{
    public function index(): void
    {
        $pageModel = new PageModel();
        $pages = $pageModel->getAllPages();
        include 'app/views/pages/index.php';
    }

    public function create(): void
    {
        include 'app/views/pages/create.php';
    }

    public function store()
    {
        if(isset($_POST['title']) && isset($_POST['slug'])) {
            $title = trim($_POST['title']);
            $slug = trim($_POST['slug']);

            if (empty($title) && empty($slug)) {
                echo 'Title and slug are required';
                return;
            }

            $pageModel = new PageModel();
            $pageModel->createPage($title, $slug);
        }
        header("Location: index.php?page=pages");
    }

    public function edit($id): void
    {
        $pageModel = new PageModel();
        $page = $pageModel->getPageById($id);

        if(!$page) {
            echo 'PageModel not find!';
            return;
        }
        include 'app/views/pages/edit.php';
    }

    public function update(): void
    {
        if(isset($_POST['id']) && isset($_POST['title']) && isset($_POST['slug'])) {
            $id = trim($_POST['id']);
            $title = trim($_POST['title']);
            $slug = trim($_POST['slug']);

            if (empty($title) && empty($slug)) {
                echo 'Title and slug are required';
                return;
            }

            $pageModel = new PageModel();
            $pageModel->updatePage($id, $title, $slug);
        }
        header("Location: index.php?page=pages");
    }

    public function delete(): void
    {
        $pageModel = new PageModel();
        $pageModel->deletePage($_GET['id']);

        header("Location: index.php?page=pages");
    }
}