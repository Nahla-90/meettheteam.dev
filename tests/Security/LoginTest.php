<?php

namespace App\Tests\Security;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LoginTest extends WebTestCase
{
    /**
     * Created By Nahla Sameh
     * this function used to test success login
     * @param $client
     * @return mixed
     */
    public function testSuccessLogin()
    {
        $client = static::createClient();

        /* Send Request for login page */
        $crawler = $client->request('GET', '/');

        /* Fill Login Form with registered email and Submit*/
        $form = $crawler->selectButton('login')->form();
        $form['email_login[email]'] = 'nahla_sameh@ymail.com';
        $crawler = $client->submit($form);

        /* Validate Login Form Response*/
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertStringContainsString(
            'Please Check your email for otp',
            $client->getResponse()->getContent()
        );


        /* Fill Login Form with valid otp and Submit*/
        $form = $crawler->selectButton('submit')->form();
        $form['otp_login[otp]'] = '638rz';
        $client->submit($form);

        /* Validate OTP Form Response*/
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $this->assertStringContainsString(
            'Redirecting to /colleague/',
            $client->getResponse()->getContent()
        );

    }

    /**
     * Created By Nahla Sameh
     * Test login form with invalid email format
     */
    public function testInvalidEmailFormat()
    {
        /* Init Client */
        $client = static::createClient();

        /* Send Get Request to login */
        $crawler = $client->request('GET', '/');

        /* Fill Login Form with invalid email*/
        $form = $crawler->selectButton('login')->form();
        $form['email_login[email]'] = 'invalidEmail';
        $client->submit($form);

        /* Validate response*/
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertStringContainsString(
            'Your entered invalid email',
            $client->getResponse()->getContent()
        );
    }

    /**
     * Created By Nahla Sameh
     * Test login form with unregistered email
     */
    public function testUnregisteredEmail()
    {
        /* Get Client*/
        $client = static::createClient();

        /* Send Get Request to login */
        $crawler = $client->request('GET', '/');

        /* Fill login form with unregistered email*/
        $form = $crawler->selectButton('login')->form();
        $form['email_login[email]'] = 'test@test.com';
        $client->submit($form);

        /* Validate response*/
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertStringContainsString(
            'Your email not registered',
            $client->getResponse()->getContent()
        );
    }

    /**
     * Creadted By Nahla Sameh
     * test login process when enter wrong otp
     */
    public function testWrongOtp()
    {
        /* Create Client Object*/
        $client = static::createClient();

        /* Send Get Request to login page*/
        $crawler = $client->request('GET', '/');

        /* Fill The login form and submit*/
        $form = $crawler->selectButton('login')->form();
        $form['email_login[email]'] = 'nahla_sameh@ymail.com';
        $crawler = $client->submit($form);

        /* Get The Otp Form and fill it*/
        $form = $crawler->selectButton('submit')->form();
        $form['otp_login[otp]'] = '1000';
        $client->submit($form);

        /* Validate Response*/
        $this->assertStringContainsString(
            'Your entered wrong otp',
            $client->getResponse()->getContent()
        );
    }
}
