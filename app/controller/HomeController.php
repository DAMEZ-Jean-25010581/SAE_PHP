<?php

namespace SAE_PHP\controllers;

use Auth\Model\User\UserRepository;
use Includes\Database\DatabaseConnection;
use Utils\Pagination;
use Utils\SessionHelpers;
use Utils\Template;

/**
 * Home page: landing page with the paginated players ranking.
 */
class HomeController
{
    /** Number of players displayed per page in the ranking. */
    private const PLAYERS_PER_PAGE = 5;

    public function execute(): void
    {
        $userRepository = new UserRepository(DatabaseConnection::getInstance());

        $pagination = new Pagination(
            $userRepository->countAll(),
            self::PLAYERS_PER_PAGE,
            $_GET['page'] ?? 1
        );

        $players = $userRepository->findRankingPage($pagination->getPerPage(), $pagination->getOffset());

        Template::render('homepage', [
            'title'      => 'CyberLab - Accueil',
            'isLoggedIn' => SessionHelpers::isLogin(),
            'players'    => $players,
            'pagination' => $pagination,
        ]);
    }
}
