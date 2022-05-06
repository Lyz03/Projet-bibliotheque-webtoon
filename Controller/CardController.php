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
        $cardManager = new CardManager();

        self::render('list/seeAll', $data = [
            'cards' => $cardManager->getAllCards(),
            'page' => $cardManager->getCardNb() / Config::CARD_LIMIT,
        ]);
    }

    /**
     * Go to seeAll page following the page number
     * @param int $page
     */
    public function seeAll(int $page) {
        if ($page === 0 || $page === 1){
            self::default();
            exit();
        }

        $cardManager = new CardManager();

        self::render('list/seeAll', $data = [
            'cards' => $cardManager->getAllCards(($page - 1) * Config::CARD_LIMIT),
            'page' => $cardManager->getCardNb() / Config::CARD_LIMIT,
        ]);
    }

    /**
     * Go to search page
     */
    public function search() {
        if (!isset($_POST['submit']) || !isset($_POST['search']) || empty(trim($_POST['search']))) {
            exit();
        }

        self::render('list/seeAll', $data = [
            'cards' => (new CardManager())->getCardBySearch(filter_var($_POST['search'], FILTER_SANITIZE_STRING))
        ]);
    }

    /**
     * Get cards by type
     * @param int $type
     * @param int $page
     */
    public function kind(int $type, int $page) {
        $cardManager = new CardManager();

        if ($page === 0) {
            $page = 1;
        }

        self::render('list/seeAll', $data = [
            'cards' => $cardManager->getCardByType(Config::CARD_TYPE[$type], ($page - 1) * Config::CARD_LIMIT),
            'page' => $cardManager->getCardNbByType(Config::CARD_TYPE[$type]) / Config::CARD_LIMIT,
            'type' => $type,
        ]);
    }

    public function sortCards(string $sort, int $type = 0) {
        $cardManager = new CardManager();
        $ratingManager = new RatingManager();

        $cards = [];

        if ($type !== 0) {
            $cards = $cardManager->getCardByType($type);
            switch ($sort) {
                case 'popular':
                    $array = $cardManager->getPopularCards($ratingManager->getRatingForCards(false));
                    foreach ($array as $value) {
                        if (strpos($value->getType(), Config::CARD_TYPE[$type]) !== false) {
                            $cards[] = $value;
                        }
                    }
                    break;
                case 'recent':
                    $cards = $cardManager->getCardByType(Config::CARD_TYPE[$type]);
                    break;
                case 'old':
                    $cards = $cardManager->getCardByType(Config::CARD_TYPE[$type], 'ASC');
                    break;
            }
        } else {

            switch ($sort) {
                case 'popular':
                    $cards = $cardManager->getPopularCards($ratingManager->getRatingForCards(false));
                    break;
                case 'recent':
                    $cards = $cardManager->getAllCards();
                    break;
                case 'old':
                    $cards = $cardManager->getAllCards('ASC');
                    break;
            }
        }

        self::render('list/seeAll', $data = [
            'cards' => $cards
        ]);
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
            $newId = $cardManager->addCard($title, $script, $drawing, $dateStart, $dateEnd, htmlentities($synopsis), $type, $image);

            self::cardPage($newId);
            exit();
        }

        $cardManager->updateCard($id, $title, $script, $drawing, $dateStart, $dateEnd, htmlentities($synopsis), $type, $image);
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
                'comments' => $commentManager->getCommentByCardIdValidate($id, 1),
            ]);
            exit();
        }

        self::default();
    }

    /**
     * Show a user's list
     * @param int $name
     * @param int $id
     */
    public function cardList(int $name, int $id) {
        if (isset($_SESSION['user'])) {

            $listManager = new ListManager();
            $array = $listManager->getCardFromList(Config::DEFAULT_LIST[$name], $id);
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

    /**
     * Add a comment
     * @param int $id
     */
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
        $content = filter_var($content, FILTER_SANITIZE_STRING);

        if (strlen($content) < 5 || strlen($content) > 250) {
            $_SESSION['error'] = ['Votre commentaire doit faire entre 5 et 250 caractères'];
            self::cardPage($id);
            exit();
        }

        $commentManager = new CommentManager();
        $commentManager->addComment(htmlentities($content), $_SESSION['user']->getId(), $id);

        $_SESSION['error'] = ['Votre commentaire est en attente de validation par un administrateur'];
        $_SESSION['color'] = Config::SUCCESS;
        self::cardPage($id);
        exit();
    }

    /**
     * Delete comment
     * @param int $id
     * @param int $card
     */
    public function deleteComment(int $id, int $card) {
        if (!isset($_SESSION['user'])) {
            self::default();
            exit();
        }

        $commentManager = new CommentManager();
        $commentManager->deleteComment($id);

        self::cardPage($card);
        exit();
    }
}

