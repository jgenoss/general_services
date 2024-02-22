<?php

namespace App\Controllers;

class SessionController
{
    private static $instance; // Unique instance of the controller

    private function __construct()
    {
        session_start();
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function getActiveSessions()
    {
        // Get information about active sessions
        $activeSessions = [];
        foreach ($_SESSION['data'] as $name => $value) {
            $session = [
                'name' => $name,
                'id' => $value
            ];
            $activeSessions[] = $session;
        }

        return $activeSessions;
    }

    public function startSession($sessionData)
    {
        // Save the user ID in the session variable 'data' or other variables you need
        $_SESSION['data'] = $sessionData;
    }

    public function endSession()
    {
        // Remove all session variables and destroy the session
        unset($_SESSION['data']);
        session_unset();
        session_destroy();
    }

    public function checkSession()
    {
        // Check if 'data' is present in the session to determine if the user is logged in
        return isset($_SESSION['data']);
    }

    public function getSessionData()
    {
        return $_SESSION['data'];
    }

    // Other methods related to sessions, if needed
}
