<?php

use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    protected $user;
    public function setUp(): void
    {
        $this->user = new \App\Models\User();
    }
    public function testThatWeCanGetTheFirstName()
    {

        $this->user->setFirstName('Billy');
        $this->assertEquals($this->user->getFirstName(), 'Billy');
    }

    public function testThatWeCanGetTheLastName()
    {

        $this->user->setLastName('Garrett');
        $this->assertEquals($this->user->getLastName(), 'Garrett');
    }

    public function testFullNameIsReturned()
    {

        $this->user->setFirstName('Billy');
        $this->user->setLastName('Garrett');

        $this->assertEquals($this->user->getFullName(), 'Billy Garrett');
    }

    public function testFirstAndLastNameAreTrimmed()
    {

        $this->user->setFirstName('Billy ');
        $this->user->setLastName('   Garrett');
        $this->assertEquals($this->user->getFirstName(), 'Billy');
        $this->assertEquals($this->user->getLastName(), 'Garrett');
    }

    public function testEmailAddressCanBeSet()
    {
        $email = 'billy@code.com';

        $this->user->setEmail('billy@code.com');

        $this->assertEquals($this->user->getEmail(), 'billy@code.com');
    }

    public function testEmailVariablesContainCorrectValues()
    {

        $this->user->setFirstName('Billy ');
        $this->user->setLastName('   Garrett');
        $this->user->setEmail('billy@code.com');

        $emailVariables = $this->user->getEmailVariables();

        $this->assertArrayHasKey('full_name', $emailVariables);
        $this->assertArrayHasKey('email', $emailVariables);

        $this->assertEquals($emailVariables['full_name'], 'Billy Garrett');
        $this->assertEquals($emailVariables['email'], 'billy@code.com');
    }
}
