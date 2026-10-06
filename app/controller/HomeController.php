<?php

namespace SAE_PHP\controllers;

use Auth\Model\User\UserRepository;
use Includes\Database\DatabaseConnection;
use Utils\Pagination;
use Utils\SessionHelpers;
use Utils\Template;

/**
 * Page d'accueil avec le classement paginé des joueurs.
 */
class HomeController
{
    /** Nombre de joueurs affichés par page du classement. */
    private const PLAYERS_PER_PAGE = 5;

    /** Récupère la page du classement demandée et affiche l'accueil. */
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
