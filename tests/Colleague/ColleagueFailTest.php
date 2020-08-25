<?php

namespace App\Tests\Colleague;

use App\Repository\ColleagueRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ColleagueFailTest extends WebTestCase
{
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
    }

    /**
     * Created By Nahla Sameh
     * this function used to login before every authorized request
     * @param $client
     * @return mixed
     */
    public function login()
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

        /* Retuen logged client to continue working on it*/
        return $client;
    }


    /**
     * Created By Nahla Sameh
     * Test Fail functionality for adding new colleague
     */
    public function testNew()
    {
        /* Authorize client*/
        $client = $this->login();

        /* Get New Colleague Form */
        $crawler = $client->request('GET', '/colleague/new');

        /* Validate New Colleague Form */
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        /* Fill New Colleague Form with invalid data*/
        $form = $crawler->selectButton('Save')->form();
        $form['colleague[Name]'] = '';
        $form['colleague[imageFile][file]'] = '';
        $form['colleague[role]'] = '';
        $form['colleague[notes]'] = '';

        /* Submit New Colleague Form */
        $client->submit($form);

        /* Validate New Colleague Form Submit*/
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertStringContainsString(
            'Error',
            $client->getResponse()->getContent()
        );
    }
}
