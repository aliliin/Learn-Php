<?php

use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{

    public function testThatWeCanGetTheFirstName()
    {
        $user = new \App\Models\User();
        $user->setFirstName('Billy');
        $this->assertEquals($user->getFirstName(), 'Billy');
    }

    public function testThatWeCanGetTheLastName()
    {
        $user = new \App\Models\User();
        $user->setLastName('Garrett');
        $this->assertEquals($user->getLastName(), 'Garrett');
    }

    public function testFullNameIsReturned()
    {
        $user = new \App\Models\User();
        $user->setFirstName('Billy');
        $user->setLastName('Garrett');

        $this->assertEquals($user->getFullName(), 'Billy Garrett');
    }

    public function testFirstAndLastNameAreTrimmed()
    {
        $user = new \App\Models\User();
        $user->setFirstName('Billy ');
        $user->setLastName('   Garrett');
        $this->assertEquals($user->getFirstName(), 'Billy');
        $this->assertEquals($user->getLastName(), 'Garrett');
    }

    public function testEmailAddressCanBeSet()
    {
        $email = 'billy@code.com';
        $user = new \App\Models\User();
        $user->setEmail('billy@code.com');

        $this->assertEquals($user->getEmail(), 'billy@code.com');
    }

    public function testEmailVariablesContainCorrectValues()
    {
        $user = new \App\Models\User();
        $user->setFirstName('Billy ');
        $user->setLastName('   Garrett');
        $user->setEmail('billy@code.com');

        $emailVariables = $user->getEmailVariables();

        $this->assertArrayHasKey('full_name', $emailVariables);
        $this->assertArrayHasKey('email', $emailVariables);

        $this->assertEquals($emailVariables['full_name'], 'Billy Garrett');
        $this->assertEquals($emailVariables['email'], 'billy@code.com');
    }
}
