<?php

namespace App\Tests\Colleague;

use App\Repository\ColleagueRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ColleagueSuccessTest extends WebTestCase
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
     * Test Success functionality for adding new colleague
     */
    public function testNew()
    {
        /* Authorize client*/
        $client = $this->login();

        /* Get New Colleague Form */
        $crawler = $client->request('GET', '/colleague/new');

        /* Validate New Colleague Form */
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        /* Fill New Colleague Form with valid data*/
        $form = $crawler->selectButton('Save')->form();
        $form['colleague[Name]'] = 'colleaguename';
        $form['colleague[imageFile][file]'] = new UploadedFile(
            getcwd() . '/public/testImages/avatar2.png',
            'avatar2.png',
            'image/png',
            null
        );
        $form['colleague[role]'] = 'role';
        $form['colleague[notes]'] = 'notes';

        /* Submit New Colleague Form */
        $client->submit($form);

        /* Validate New Colleague Form Submit*/
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $this->assertStringContainsString(
            'Redirecting to /colleague/',
            $client->getResponse()->getContent()
        );
    }

    /**
     * Created By Nahla Sameh
     * Test Success functionality for Edit existing colleague
     */
    public function testEdit()
    {
        /* Authorize client*/
        $client = $this->login();

        /* Get edit Colleague Form */
        $colleagueRepository = static::$container->get(ColleagueRepository::class);
        if (count($colleagueRepository->findAll()) > 0) {
            $firstColleague = $colleagueRepository->findAll()[0];
            $crawler = $client->request('GET', '/colleague/' . $firstColleague->getId() . '/edit');

            /* Validate edit Colleague Form */
            $this->assertEquals(200, $client->getResponse()->getStatusCode());

            /* Fill edit Colleague Form with valid data*/
            $form = $crawler->selectButton('Update')->form();
            $form['colleague[Name]'] = 'colleaguename';
            $form['colleague[imageFile][file]'] = new UploadedFile(
                getcwd() . '/public/testImages/avatar2.png',
                'avatar2.png',
                'image/png',
                null
            );
            $form['colleague[role]'] = 'role';
            $form['colleague[notes]'] = 'notes';

            /* Submit edit Colleague Form */
            $client->submit($form);

            /* Validate edit Colleague Form Submit*/
            $this->assertEquals(302, $client->getResponse()->getStatusCode());
            $this->assertStringContainsString(
                'Redirecting to /colleague/',
                $client->getResponse()->getContent()
            );
        }
    }

    /**
     * Created By Nahla Sameh
     * Test Success functionality for Delete existing colleague
     */
    public function testDelete()
    {
        /* Authorize client*/
        $client = $this->login();

        /* Send Get request to delete colleague */
        $colleagueRepository = static::$container->get(ColleagueRepository::class);
        if (count($colleagueRepository->findAll()) > 0) {
            $firstColleague = $colleagueRepository->findAll()[0];
            $client->request('DELETE', '/colleague/' . $firstColleague->getId());

            /* Validate Delete Colleague Response*/
            $this->assertEquals(302, $client->getResponse()->getStatusCode());
            $this->assertStringContainsString(
                'Redirecting to /colleague/',
                $client->getResponse()->getContent()
            );
        }
    }

}
