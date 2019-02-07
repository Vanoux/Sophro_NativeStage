<?php
/**
 * Created by PhpStorm.
 * User: jordan
 * Date: 2019-02-07
 * Time: 15:40
 */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 */
class GoogleController extends AbstractController
{
    private $analytics;
    private $profile;

    public function __construct()
    {
        $this->analytics = $this->initializeAnalytics(); // Initialise l'API
        $this->profile = $this->getFirstProfileId($this->analytics); //Récupère le profil Google Analytics
    }
// Fonction d'initialisation et d'authentification
    function initializeAnalytics()
    {
        // Précise où trouver la clé du compte de service
        $KEY_FILE_LOCATION = '../vendor/google/annesophielabat-1781c0572fc1.json';

        // Crée et configure le client
        $client = new \Google_Client();
        $client->setApplicationName("Hello Analytics Reporting");
        $client->setAuthConfig($KEY_FILE_LOCATION);
        $client->setScopes(['https://www.googleapis.com/auth/analytics.readonly']);
        $analytics = new \Google_Service_Analytics($client);
        return $analytics;
    }

// Récupère le profil Google Analytics
    function getFirstProfileId($analytics)
    {
        // Récupère la liste des comptes
        $accounts = $analytics->management_accounts->listManagementAccounts();

        if (count($accounts->getItems()) > 0) {
            $items = $accounts->getItems();
            $firstAccountId = $items[0]->getId();

            // Récupère la liste des propriétés
            $properties = $analytics->management_webproperties
                ->listManagementWebproperties($firstAccountId);

            if (count($properties->getItems()) > 0) {
                $items = $properties->getItems();
                $firstPropertyId = $items[0]->getId();

                // Récupère la liste des vues
                $profiles = $analytics->management_profiles
                    ->listManagementProfiles($firstAccountId, $firstPropertyId);

                if (count($profiles->getItems()) > 0) {
                    $items = $profiles->getItems();

                    // Retourne l'ID de la première vue
                    return $items[0]->getId();

                } else {
                    throw new Exception('No views (profiles) found for this user.');
                }
            } else {
                throw new Exception('No properties found for this user.');
            }
        } else {
            throw new Exception('No accounts found for this user.');
        }
    }

    function getResults($analytics, $profileId, $metric) {
        return $analytics->data_ga->get(
            'ga:' . $profileId, // Précise le profil Google Analytics à utiliser
            '7daysAgo', // Précise la date de début
            'today', // Précise la date de fin
            $metric // Précise le métrique utilisé (session, users...)
        );
    }

    function printResults($results) {
        if (count($results->getRows()) > 0) {
            $rows = $results->getRows();
            $valeur = $rows[0][0];
            return $valeur;
        } else {
            return "Pas de résultat.\n";
        }
    }

    

    /**
     * @Route("/stat", name="myStat")
     */
    public function stat(): Response
    {
        $results = $this->getResults($this->analytics, $this->profile, 'ga:pageviews,ga:users,ga:sessions');
        return $this->render('admin/myStat.html.twig', array(
            'results' => $results
        ));
    }


}