<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Entity\Article;
use App\Entity\User;


class ArticleControllerTest extends TestCase
{
    
    public function testPagesArticleAdminIsOk($url) //Test que les pages de l'ArticleController renvois bien la connexion 200
    {
        //$user = new User();
        $client = self::createClient();
        $client->request('GET', $url);
        $this->assertTrue($client->getResponse()->isSuccessful());
        // $this->assertSame(200, $client->getResponse()->getStatusCode());
        // Affiche le contenu de ma réponse
        echo $client->getResponse()->getStatusCode();
    }
    public function provideUrls()
    {
    return [
        ['/admin/actu'],
        ['/admin/actu/new'],
        ];
    }
        
}
