<?php

namespace Utils;

class SessionHelpers
{
    public static function start(): void
    {
        // vérifie si aucune session demarrée 
        if (session_status() == PHP_SESSION_NONE){
            session_start();
        }
    }

    public static function isLogin(): bool
    {
        //regarde si le 'user' est défini dans la session
        if (isset($_SESSION['user'])) {
            return true;
        }
        return false;
    }

    public static function login(array $user): void
    {
        //vérifie si l'utilisateur est défini dans la session
        if (isset($user['user_id'])) { // le stocke si c'est le cas
            $_SESSION['user'] = [
                'id' => $user['user_id'],
                'username' => $user['user_name']
            ];
        }
    }

    public static function logout(): void
    {

        if (isset($_SESSION['user'])) { //supprime l'utilisateur de la session si il existe
            unset($_SESSION['user']);
        }
    }
}