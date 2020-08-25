<?php

namespace App\Tests\Colleague;

use App\Repository\ColleagueRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ColleagueUnauthorizedTest extends WebTestCase
{
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
    }

    /**
     * Created By Nahla Sameh
     * this function test UnAuthorized access for colleague_index route
     */
    public function testIndex()
    {
        /* Create new client */
        $client = static::createClient();

        /* Validate Colleague request without login */
        $client->request('GET', '/colleague/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertStringContainsString(
            'Unauthorized Access',
            $client->getResponse()->getContent()
        );
    }

    /**
     * Created By Nahla Sameh
     * this function test UnAuthorized access for colleague_new route
     */
    public function testNew()
    {
        /* Create new client */
        $client = static::createClient();

        /* Validate New Colleague request without login*/
        $client->request('GET', '/colleague/new');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertStringContainsString(
            'Unauthorized Access',
            $client->getResponse()->getContent()
        );
    }


    /**
     * Created By Nahla Sameh
     * this function test UnAuthorized access for colleague_edit route
     */
    public function testEdit()
    {
        /* Create new client */
        $client = static::createClient();

        /* Validate Edit Colleague request without login with valid colleagueId*/
        $colleagueRepository = static::$container->get(ColleagueRepository::class);
        if (count($colleagueRepository->findAll()) > 0) {
            $firstColleague = $colleagueRepository->findAll()[0];
            $client->request('GET', '/colleague/' . $firstColleague->getId() . '/edit');
            $this->assertEquals(200, $client->getResponse()->getStatusCode());
            $this->assertStringContainsString(
                'Unauthorized Access',
                $client->getResponse()->getContent()
            );
        }
        /* Validate Edit Colleague request without login with invalid colleagueId*/
        $client->request('GET', '/colleague/0/edit');
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }


    /**
     * Created By Nahla Sameh
     * this function test UnAuthorized access for colleague_delete route
     */
    public function testDelete()
    {
        /* Create new client */
        $client = static::createClient();

        /* Validate Delete Colleague request without login with valid colleagueId*/
        $colleagueRepository = static::$container->get(ColleagueRepository::class);
        if (count($colleagueRepository->findAll()) > 0) {
            $firstColleague = $colleagueRepository->findAll()[0];
            $client->request('GET', '/colleague/' . $firstColleague->getId());
            $this->assertEquals(200, $client->getResponse()->getStatusCode());
            $this->assertStringContainsString(
                'Unauthorized Access',
                $client->getResponse()->getContent()
            );
        }

        /* Validate Delete Colleague request without login with invalid colleagueId*/
        $client->request('GET', '/colleague/0');
        $this->assertEquals(404, $client->getResponse()->getStatusCode());

    }
}
