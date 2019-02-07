<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AppControllerTest extends WebTestCase
{
    // 5°)On ajoute l’ @dataProvider annotation pour associer les deux méthodes d'en dessous (permet d'exécuter le même test sur différents ensembles de données pour vérifier les multiples conditions que doit gérer le code)
    
    /**
     * @dataProvider provideUrls
     */
    public function testPagesHomeIsOk($url) //Test que les pages de l'AppController renvois bien la connexion 200
    {
        //1°) Methode static qui permet de créer le client Http
        $client = self::createClient();
        //2°) Apelle la methode request qui prend en 1er paramètre la methode Http en GET et en 2ème l'URI que l'on veut requêter (ici le paramètre $url)
        $client->request('GET', $url);
        //3°) Récupère la réponse
        $this->assertTrue($client->getResponse()->isSuccessful());
        // $this->assertSame(200, $client->getResponse()->getStatusCode());
        // Affiche le contenu de ma réponse
        echo $client->getResponse()->getStatusCode();
    }
    //4°) On défini une autre méthode qui renvoie un tableau imbriqué avec les arguments à utiliser à chaque exécution de test.
    public function provideUrls()
    {
    return [
        ['/'],
        ['/sophrologie'],
        ['/ateliers'],
        ['/tarifsHoraires'],
        ['/faq']
        // ['/actualites'],
        // ['/articles']
        // ['/contact'],
        // ['/conseil'],
        // ['/formation'],
        // ['/experience'],
        // ['/cabinets']
        ];
    }

    //Test que le title est bien présent dans la page home
    public function testHomepage()
    {
        $client = static::createClient();
        //le crawler permet de se balader dans le DOM document de notre page => c'est 1 objet qui peut être utilisé pour sélectionner des éléments dans la réponse, cliquer sur des liens et soumettre des formulaires.
        $crawler = $client->request('GET', '/');
        // On s'assure que dans l'assertion il y a 1fois notre title puis on fait appel à $crawler qui fait appel à la méthode filter() qui permet de récuperer des info en rapport avec un élément balise html
        $this->assertContains('Labat Sophrologie', $crawler->filter('title')->text());
    }

}
