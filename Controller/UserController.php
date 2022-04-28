<?php

namespace App\Controller;

use App\Config;
use App\Manager\CommentManager;
use App\Manager\ListManager;
use App\Manager\UserManager;

class UserController extends AbstractController
{

    public function default()
    {
        if (isset($_SESSION['user'])) {
            $listManager = new ListManager();

            $list = [];

            foreach (Config::DEFAULT_LIST as $value) {
                $list[$value] = $listManager->getTreeCardlist($value, $_SESSION['user']->getId());
            }

            self::render('user/account', $data = [
                'user' => $_SESSION['user'],
                'list' => $list,
            ]);
            exit();
        }

        self::render('connection-inscription/connection');
    }

    /**
     * Delete a User
     * @param int $id
     */
    public function deleteUser(int $id) {
        if (!isset($_SESSION['user'])) {
            self::default();
            exit();
        }

        $userManager = new UserManager();
        $var = $userManager->deleteUser($id);

        if ($var) {
            ConnectionController::logOut();
            exit();
        }

        self::default();
    }

    /***************
     *   Comments  *
     ***************/

    /**
     * go to the comment list page
     */
    public function commentList() {
        if (!isset($_SESSION['user'])) {
            self::default();
            exit();
        }

        if ($_SESSION['user']->getRole() !== 'admin') {
            self::default();
            exit();
        }

        $commentManager = new CommentManager();

        self::render('list/commentList', $data = [
            'comments' => $commentManager->getUnvalidatedComment(),
        ]);
    }

    /**
     * Validate a comment
     * @param int $id
     */
    public function validateComment(int $id) {
        if (!isset($_SESSION['user'])) {
            self::default();
            exit();
        }

        if ($_SESSION['user']->getRole() !== 'admin') {
            self::default();
            exit();
        }

        $commentManager = new CommentManager();
        $commentManager->validateComment($id);

        self::render('list/commentList', $data = [
            'comments' => $commentManager->getUnvalidatedComment(),
        ]);
    }

    /**
     * Delete comment
     * @param int $id
     */
    public function deleteComment(int $id) {
        if (!isset($_SESSION['user'])) {
            self::default();
            exit();
        }

        $commentManager = new CommentManager();
        $commentManager->deleteComment($id);

        self::render('list/commentList', $data = [
            'comments' => $commentManager->getUnvalidatedComment(),
        ]);
    }
}