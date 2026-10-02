<?php

namespace utils;

class SessionHelpers
{
    public static function start(): void
    {
        if (session_status() == PHP_SESSION_NONE){
            session_start();
        }
    }

    public static function isLogin(): bool
    {
        if (isset($_SESSION['user'])) {
            return true;
        }
        return false;
    }

    public static function login(array $user): void
    {
        if (isset($user['user_id'])) {
            $_SESSION['user'] = [
                'id' => $user['user_id'],
                'username' => $user['user_name']
            ];
        }
    }

    public static function logout(): void
    {
        if (isset($_SESSION['user'])) {
            unset($_SESSION['user']);
        }
    }
}