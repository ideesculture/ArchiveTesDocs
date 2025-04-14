<?php

namespace bs\Core\UsersBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $this->assertContains('bsCoreUsersBundle:index.html.twig', $client->getResponse()->getContent());
    }
}
