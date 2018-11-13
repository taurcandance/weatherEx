<?php

namespace SessionState;

{

    class SessionState
    {
        public $cookieLifetime;
        public $sessionLifetime;
        public $sessionGcDivisor;
        public $sessionSavePath;

        function __construct()
        {
            $this->cookieLifetime   = 1440;
            $this->sessionLifetime  = 1440;
            $this->sessionSavePath  = $_SERVER['DOCUMENT_ROOT'].'/sessions/';
            $this->sessionGcDivisor = 100;
        }

        public function save(int $cookieLifetime = null, int $sessionLifetime = null, int $sessionGcDivisor = null, $sessionSavePath = null)
        {
            if ( ! is_null($sessionSavePath)) {
                if (file_exists($sessionSavePath) == false) {
                    mkdir($sessionSavePath, 0777);
                    ini_set('session.save_path', $sessionSavePath);
                }
            }
            else{
                if (file_exists($this->sessionSavePath) == false) {
                    mkdir($this->sessionSavePath, 0777);
                }
                ini_set('session.save_path', $this->sessionSavePath);
            }

            if ( ! is_null($cookieLifetime) && $cookieLifetime >= 1440 && $cookieLifetime <= 2678400) {
                ini_set('session.cookie_lifetime', $cookieLifetime);
            } else {
                ini_set('session.cookie_lifetime', $this->cookieLifetime);
            }

            if ( ! is_null($sessionLifetime) && $sessionLifetime >= 1440 && $sessionLifetime <= 2678400) {
                ini_set('session.gc_maxlifetime', $sessionLifetime);
            } else {
                ini_set('session.gc_maxlifetime', $this->sessionLifetime);
            }

            if ( ! is_null($sessionGcDivisor) && $sessionGcDivisor >= 1 && $sessionGcDivisor <= 1000000) {
                ini_set('session.gc_divisor', $sessionGcDivisor);
            } else {
                ini_set('session.gc_divisor', $this->sessionGcDivisor);
            }

            session_start();

            if ($_GET['city'] && ! is_null($_GET['city'])) {
                $_SESSION['last_city'] = $_GET['city'];
            }

            session_write_close();
        }

        public function getLastCity()
        {
            session_start();
            if ($_SESSION['last_city']) {
                return $_SESSION['last_city'];
            } else {
                return false;
            }
        }
    }
}