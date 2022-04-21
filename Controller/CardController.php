<?php

namespace App\Controller;

use App\Config;
use App\Manager\CardManager;

class CardController extends AbstractController
{
    public function default()
    {
        self::render('list/seeAll');
    }

    /**
     * link to update-card
     */
    public function updatePage() {
        if (isset($_SESSION['user'])) {

            if ($_SESSION['user']->getRole() === 'admin') {
                self::render('card/update-card');
                exit();
            }

            self::render('user/account', $data = ['user' => $_SESSION['user']]);
        }
        self::render('home');
    }

    /**
     * Sanitize POST content to add a new card
     */
    public function updateCard() {

        if (!isset($_SESSION['user'])) {
            self::default();
            exit();
        }

        if ($_SESSION['user']->getRole() !== 'admin') {
            self::default();
            exit();
        }

        if (!isset($_POST['submit'])) {
            self::default();
            exit();
        }

        if (!isset($_POST['title']) || !isset($_POST['script']) || !isset($_POST['drawing']) || !isset($_POST['dateStart'])
            || !isset($_POST['dateEnd']) || !isset($_POST['synopsis']) || !isset($_POST['type']) || !isset($_POST['type2'])
            || !isset($_POST['type3']) || !isset($_FILES['image'])) {
            self::default();
            exit();
        }

        $types = [$_POST['type'], $_POST['type2'], $_POST['type3']];
        $validType = 0;

        // check if the type exist
        foreach ($types as $value) {
            if (in_array($value, Config::CARD_TYPE) || $value === 'none') {
                $validType += 1;
            }
        }

        if ($validType !== 3) {
            self::default();
            exit();
        }

        // check size
        $title = filter_var($_POST['title'], FILTER_SANITIZE_STRING);
        $script = filter_var($_POST['script'], FILTER_SANITIZE_STRING);
        $drawing = filter_var($_POST['drawing'], FILTER_SANITIZE_STRING);
        $dateStart = filter_var($_POST['dateStart'], FILTER_SANITIZE_NUMBER_INT);
        $dateEnd = filter_var($_POST['dateEnd'], FILTER_SANITIZE_NUMBER_INT);
        $synopsis = filter_var($_POST['synopsis'], FILTER_SANITIZE_STRING);

        $error = [];

        // Image
        if($_FILES['image']['error'] === 0) {
            if((int)$_FILES['image']['size'] <= (2 * 1024 * 1024)) {
                $tmp_name = $_FILES['image']['tmp_name'];
                $extension = pathinfo($_FILES['image']['name'])['extension'];
                $name = self::randomChars();
                move_uploaded_file($tmp_name, 'assets/images/' . $name . '.' . $extension);

            } else{
                $error[] = "L'image sélectionnée est trop grande";
            }

        } else {
            $_SESSION['error'] = ['Une erreur est survenue, veillez à remplir tous les champs'];
            self::updatePage();
            exit();
        }

        // Title
         if (strlen($title) < 1 || strlen($title) > 90) {
             $error[] = "Le titre doit faire entre 1 et 90 caractères";
         }

         // Script
        if (strlen($script) < 1 || strlen($script) > 60) {
            $error[] = "Le champ scénariste doit faire entre 1 et 60 caractères";
        }

        // Drawing
        if (strlen($drawing) < 1 || strlen($drawing) > 60) {
            $error[] = "Le champ dessinateur doit faire entre 1 et 60 caractères";
        }

        // Date start
        if ($dateStart < 1900 || $dateStart > 2800) {
            $error[] = "L'année de début de publication doit être compris entre 1900 et 2800";
        }

        // Date end
        if ($dateEnd < 1900 || $dateEnd > 2800) {
            $error[] = "L'année de fin de publication doit être compris entre 1900 et 2800";
        }

        // Synopsis
        if ($synopsis < 10 || $synopsis > 600) {
            $error[] = "Le synopsis doit faire entre 10 et 600 caractères";
        }

         if (count($error) > 0) {
             $_SESSION['error'] = $error;
             self::updatePage();
             exit();
         }

         $cardManager = new CardManager();

    }
}

