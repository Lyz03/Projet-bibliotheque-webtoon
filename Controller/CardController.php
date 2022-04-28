<?php

namespace App\Controller;

use App\Config;
use App\Manager\CardManager;
use App\Manager\CommentManager;
use App\Manager\ListManager;
use App\Manager\RatingManager;

class CardController extends AbstractController
{
    public function default()
    {
        $carManager = new CardManager();

        self::render('list/seeAll', $data = ['cards' => $carManager->getAllCards()]);
    }

    /**
     * link to update-card
     */
    public function updatePage(int $id) {
        if (isset($_SESSION['user'])) {

            if ($_SESSION['user']->getRole() === 'admin') {
                // if new card
                if ($id === 0) {
                    self::render('card/update-card');
                    exit();
                }

                // if update a card
                $cardManager = new CardManager();

                self::render('card/update-card', $data = ['card' => $cardManager->getCardById($id)]);
                exit();
            }

            self::render('user/account', $data = ['user' => $_SESSION['user']]);
        }
        self::render('home');
    }

    /**
     * Sanitize POST content to add or update a new card
     */
    public function updateCard(int $id) {

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
        $image = '';

        $error = [];

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
        if (strlen($dateEnd) === 0) {
            $dateEnd = 0;
        } else {
            if ($dateEnd < 1900 || $dateEnd > 2800) {
                $error[] = "L'année de fin de publication doit être compris entre 1900 et 2800";
            }
        }

        // Synopsis
        if (strlen($synopsis) < 10 || strlen($synopsis) > 600) {
            $error[] = "Le synopsis doit faire entre 10 et 600 caractères";
        }

         if (count($error) > 0) {
             $_SESSION['error'] = $error;
             self::updatePage($id);
             exit();
         }

        $cardManager = new CardManager();

        // Image
        if($_FILES['image']['error'] === 0) {
            if((int)$_FILES['image']['size'] <= (2 * 1024 * 1024)) {
                $tmp_name = $_FILES['image']['tmp_name'];
                $extension = pathinfo($_FILES['image']['name'])['extension'];
                $name = self::randomChars();
                move_uploaded_file($tmp_name, 'assets/images/' . $name . '.' . $extension);
                $image = $name . '.' . $extension;

                // delete the older one
                if ($id !== 0) {
                    unlink('assets/images/' . $cardManager->getCardById($id)->getImage());
                }

            } else{
                $_SESSION['error'] = ["L'image sélectionnée est trop grande"];
                self::updatePage($id);
                exit();
            }

        } else {
            // if already has an image
            if ($id !== 0) {
                $image = $cardManager->getCardById($id)->getImage();
            } else {
                $_SESSION['error'] = ['Une erreur est survenue, veillez à remplir tous les champs'];
                self::updatePage($id);
                exit();
            }
        }

        $type = [];
        foreach ($types as $value) {
            if ($value !== 'none') {
                $type[] = $value;
            }
        }

        $type = array_unique($type);
        $type = implode(',', $type);

        if ($id === 0) {
            $newId = $cardManager->addCard($title, $script, $drawing, $dateStart, $dateEnd, $synopsis, $type, $image);

            self::cardPage($newId);
            exit();
        }

        $cardManager->updateCard($id, $title, $script, $drawing, $dateStart, $dateEnd, $synopsis, $type, $image);
        self::cardPage($id);
        exit();
    }

    /**
     * Delete a card
     * @param int $id
     */
    public function deleteCard(int $id) {
        if (!isset($_SESSION['user'])) {
            self::default();
            exit();
        }

        $cardManager = new CardManager();

        $image = $cardManager->getCardById($id)->getImage();
        $var = $cardManager->deleteCard($id);

        if ($var) {
            unlink('assets/images/' . $image);
        }

        self::cardPage($id);
    }

    /**
     * Link to card (full page)
     * @param int $id
     */
    public function cardPage(int $id) {

        $ratingManager = new RatingManager();
        $listManager = new ListManager();

        $userRating = null;
        $userList = null;

        if (isset($_SESSION['user'])) {
            $userRating = $ratingManager->getRatingByUserCard($id, $_SESSION['user']->getId());
            $userList = $listManager->getListByUserCard($id, $_SESSION['user']->getId());
        }

        $cardManager = new CardManager();
        $card = $cardManager->getCardById($id);

        $commentManager = new CommentManager();

        if ($card !== null) {
            self::render('card/card', $data = [
                'card' => $card,
                'rating' => $ratingManager->getRatingByCardId($id),
                'userRating' => $userRating,
                'userList' => $userList,
                'comments' => $commentManager->getCommentByCardId($id),
            ]);
            exit();
        }

        self::default();
    }

    public function cardList(int $name) {
        if (isset($_SESSION['user'])) {

            $listManager = new ListManager();
            $array = $listManager->getCardFromList(Config::DEFAULT_LIST[$name], $_SESSION['user']->getId());
            $cards = [];

            foreach ($array as $value) {
                $cards[] = $value->getCard();
            }

            self::render('list/cardList', $data = [
                'cards' => $cards,
                'name' => Config::DEFAULT_LIST[$name],
            ]);
            exit();
        }

        self::default();
    }

    /****************
     *    Rating    *
     ****************/

    /**
     * Add a new rating
     * @param int $id
     * @param int $mark
     */
    public function addReview(int $id, int $mark) {
        if (!isset($_SESSION['user'])) {
            self::default();
            exit();
        }

        $ratingManager = new RatingManager();
        $ratingManager->addRating($mark, $_SESSION['user']->getId(), $id);
        self::cardPage($id);
    }

    /**
     * Update a rating
     * @param int $id
     * @param int $mark
     */
    public function updateReview(int $id, int $mark) {
        if (!isset($_SESSION['user'])) {
            self::default();
            exit();
        }

        $ratingManager = new RatingManager();
        $ratingManager->updateRating($mark, $_SESSION['user']->getId(), $id);
        self::cardPage($id);
    }

    /**
     * Delete a rating
     * @param int $id
     */
    public function deleteReview(int $id) {
        if (!isset($_SESSION['user'])) {
            self::default();
            exit();
        }

        $ratingManager = new RatingManager();
        $ratingManager->deleteRating($_SESSION['user']->getId(), $id);
        self::cardPage($id);
    }

    /***************
     *     List    *
     ***************/

    /**
     * Add a new card in a list
     * @param int $id
     * @param int $list
     */
    public function addList(int $id, int $list) {
        if (!isset($_SESSION['user'])) {
            self::default();
            exit();
        }

        $listManager = new ListManager();
        $listManager->addList(Config::DEFAULT_LIST[$list],1, $_SESSION['user']->getId(), $id);
        self::cardPage($id);
    }

    /**
     * Delete a rating
     * @param int $id
     * @param int $list
     */
    public function removeList(int $id, int $list) {
        if (!isset($_SESSION['user'])) {
            self::default();
            exit();
        }

        $listManager = new ListManager();
        $listManager->removeList($_SESSION['user']->getId(), $id, Config::DEFAULT_LIST[$list]);
        self::cardPage($id);
    }

    /***************
     *   Comments  *
     ***************/

    public function addComment(int $id) {
        if (!isset($_SESSION['user'])) {
            self::default();
            exit();
        }

        if (!isset($_POST['content']) || !isset($_POST['submit'])) {
            self::default();
            exit();
        }

        $content = $_POST['content'];

        if (strlen($content) < 5 || strlen($content) > 250) {
            $_SESSION['error'] = ['Votre commentaire doit faire entre 5 et 250 caractères'];
            self::cardPage($id);
            exit();
        }

        $commentManager = new CommentManager();
        $commentManager->addComment(htmlentities($content), $_SESSION['user']->getId(), $id);

        self::cardPage($id);
        exit();
    }
}

