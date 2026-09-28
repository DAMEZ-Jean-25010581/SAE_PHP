<?php
namespace SAE_PHP\controllers;
use Includes\Database\DatabaseConnection, Blog\Models\Post\PostRepository;
class Homepage
{
    public function execute(): void
    {
        $postRepository = new PostRepository(DatabaseConnection::getInstance());
        $posts = $postRepository->getPosts();
        (new \SAE_PHP\views\PlanSite())->show();
    }
}