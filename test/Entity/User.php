<?php

require __DIR__ . "/../../Vendor/autoload.php";
require __DIR__ . "/../../Entity/User.php";

use \App\Entity\User;

use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    private User $user;

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct();
        $this->user = new \App\Entity\User();
    }

    /**
     * Test User setUsername
     */
    public function testSetUsername(): void {
        $result = $this->user->setUsername('test');

        $this->assertEquals($this->user, $result);
    }

    /**
     * Test User getUsername
     */
    public function testGetUsername(): void {
        $this->user->setUsername('test');

        $this->assertEquals('test',  $this->user->getUsername());
    }
}