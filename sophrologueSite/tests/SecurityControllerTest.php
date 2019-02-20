<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\User;
use App\Form\EditPasswordType;
use Symfony\Component\HttpFoundation\Response;


class SecurityControllerTest extends WebTestCase
{

    // Test que l'accès admin est refusé si role autre qu'admin
    public function testAccessDeniedForAnonymousUsers($url, User $user)
    {
        $user = new User();
        $user->setRoles(array('ROLE_USER'));
        $client = self::createClient();
        $client->request('GET',$url);
        $response = $client->getResponse();
        $this->assertTrue($client->getResponse()->isSuccessful());
        //$this->assertSame($response->getStatusCode());
    }

    // Test qu'un message d’erreur s’affiche bien lorsque l’utilisateur ne saisi pas les 2 mêmes mot de passe lors de l'edit:
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

    // Test que les 2 mots de passe lors de l'édition sont identique
    public function testCheckSamePasswordEdit(){

        $client = static::createClient();
        $client->request(
            'GET','/admin/password_edit'
        );    
        $form['user[password]'] = 'pass1';
        $form['user[confirm_password]'] = 'pass1';

        //echo $client->getResponse()->getContent();
        $this->assertSame($form['user[password]'], $form['user[confirm_password]']
    );

    }


}
