<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    public function testEditpassword()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/admin/password_edit');

        $form = $crawler->filter('button#modifierMdp')->form();

        $form['user[password]'] = 'totoADMIN31';
        $form['user[confirm_password]'] = 'totoUSERdailleurs';

        $crawler = $client->submit($form);
        
        $this->assertEquals(1,
        $crawler->filter('alert:contains("This value is not valid.")')->count()
    );
    }
}
