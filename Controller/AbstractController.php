<?php

namespace App\Controller;

use Exception;

abstract class AbstractController
{

    abstract public function default();

    public function render(string $directoryFile, array $data = null) {
        ob_start();
        require __DIR__ . "/../view/" . $directoryFile . ".php";
        $page = ob_get_clean();
        require __DIR__ . "/../view/base.php";
    }

    /**
     * return random characters
     * @param int $len
     * @return string
     */
    public function randomChars(int $len = 15): string{
        try {
            $bytes = random_bytes($len);
        } catch (Exception $e) {
            $bytes = openssl_random_pseudo_bytes($len);
        }
        return bin2hex($bytes);
    }
}