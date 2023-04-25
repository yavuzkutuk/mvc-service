<?php

namespace App\Controller;

use App\Model\ItemManager;
use App\Service\Slugify;
use App\Service\TTC;

class ItemController extends AbstractController
{
    /**
     * List items
     */
    public function index(): string
    {
        $itemManager = new ItemManager();
        $items = $itemManager->selectAll('title');

        return $this->twig->render('Item/index.html.twig', ['items' => $items]);
    }

    /**
     * Show informations for a specific item
     */
    public function show($title): string
    {
    
        $itemManager = new ItemManager();
        $item = $itemManager->selectOneByTitle($title);

        return $this->twig->render('Item/show.html.twig', ['item' => $item]);
    }

    /**
     * Edit a specific item
     */
    public function edit(int $id): ?string
    {
        $itemManager = new ItemManager();
        $item = $itemManager->selectOneById($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // clean $_POST data
            $item = array_map('trim', $_POST);
            $ttc = new TTC();

            $newvalue = $ttc->calcultva(10,2);
            
            $slugify = new Slugify();
            $item['title'] = $slugify->slug($item['title']);

            // if validation is ok, update and redirection
            $itemManager->update($item);

            header('Location: /items/show?id=' . $id);

            // we are redirecting so we don't want any content rendered
            return null;
        }

        return $this->twig->render('Item/edit.html.twig', [
            'item' => $item,
        ]);
    }

    /**
     * Add a new item
     */
    public function add(): ?string
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // clean $_POST data
            $item = array_map('trim', $_POST);

            $slugify = new Slugify();
            $item['title'] = $slugify->slug($item['title']);

            // TODO validations (length, format...)

            // if validation is ok, insert and redirection
            $itemManager = new ItemManager();
            $id = $itemManager->insert($item);

            header('Location:/items');
            return null;
        }

        return $this->twig->render('Item/add.html.twig');
    }

    /**
     * Delete a specific item
     */
    public function delete(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = trim($_POST['id']);
            $itemManager = new ItemManager();
            $itemManager->delete((int)$id);

            header('Location:/items');
        }
    }
}
