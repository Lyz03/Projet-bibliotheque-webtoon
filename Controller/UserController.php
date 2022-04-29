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
     * Go to a user's profile
     * @param int $id
     */
    public function userProfile(int $id) {
        $userManager = new UserManager();
        $listManager = new ListManager();

        $list = [];

        foreach (Config::DEFAULT_LIST as $value) {
            $list[$value] = $listManager->getTreeCardlist($value, $id);
        }

        self::render('user/account', $data = [
            'user' => $userManager->getUserById($id),
            'list' => $list,
        ]);
    }

    /**
     * Go to user list
     */
    public function userList() {
        if (!isset($_SESSION['user'])) {
            self::default();
            exit();
        }

        if ($_SESSION['user']->getRole() !== 'admin') {
            self::default();
            exit();
        }

        $userManager = new UserManager();

        self::render('list/userList', $data = ['users' => $userManager->getAllUser()]);
    }

    /**
     * Update a user's role
     * @param int $id
     * @param string $role
     */
    public function changeRole(int $id, string $role) {
        if (!isset($_SESSION['user'])) {
            self::default();
            exit();
        }

        if ($_SESSION['user']->getRole() !== 'admin') {
            self::default();
            exit();
        }

        if (!in_array($role, Config::ROLE)) {
            self::default();
            exit();
        }

        $userManager = new UserManager();
        $userManager->updateRole($id, $role);

        self::userList();
    }

    public function changeAvatar(int $avatar) {
        if (!isset($_SESSION['user'])) {
            self::default();
            exit();
        }

        if (!key_exists($avatar, Config::AVATAR)) {
            self::default();
            exit();
        }

        $userManager = new UserManager();
        $userManager->updateAvatar($_SESSION['user']->getId(), Config::AVATAR[$avatar]);

        $_SESSION['user']->setAvatar(Config::AVATAR[$avatar]);
        self::default();
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

        if ($_SESSION['user']->getRole() !== 'admin' && $_SESSION['user']->getId() !== $id) {
            self::default();
            exit();
        }

        $userManager = new UserManager();
        $var = $userManager->deleteUser($id);

        if ($var && $_SESSION['user']->getId() === $id) {
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