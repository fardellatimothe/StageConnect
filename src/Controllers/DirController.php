<?php 
namespace App\Controllers;

use App\Models\CorporationModel;
use App\Models\OfferModel;

class DirController extends Controller {

    protected $modelcorporation = null;
    protected $modeloffer = null;

    public function __construct($templateEngine) { 
        $this->modelcorporation = new CorporationModel();
        $this->modeloffer = new OfferModel();
        $this->templateEngine = $templateEngine;
        
    }

    public function homePage() {
        // $entreprises = $this->model->getEntreprises();
        
        // $selectedCategory = isset($_GET['category']) ? $_GET['category'] : 'all';

        // $filteredCompanies = $selectedCategory === 'all' ? $entreprises : array_filter($entreprises, function($entreprise) use ($selectedCategory) {
        //     return $entreprise['secteur'] === $selectedCategory;
        // });

        // $filteredCompanies = array_slice($filteredCompanies, 0, 12);

        // Passer les données à Twig
        // echo $this->templateEngine->render('home.html.twig', [
        //     'entreprises' => $filteredCompanies,
        //     'selectedCategory' => $selectedCategory
        // ]);

        $user = $_SESSION['user'] ?? null;

        $companies = $this->modelcorporation->getAll();
        $offers = $this->modeloffer->getAll();
        
        echo $this->templateEngine->render("pages/home.html.twig", [
            'count' => $offers ? count($offers) : 0,
            "user"=> $user,
            "companies" => $companies
        ]);
    }   

    public function loginPage() {
        echo $this->templateEngine->render('pages/login.html.twig');
    }  

    public function aboutPage() {
        $user = $_SESSION['user'] ?? null;
        
        echo $this->templateEngine->render('pages/about.html.twig', [
            "user"=> $user,
        ]);
    }

    public function cguPage() {
        $user = $_SESSION['user'] ?? null;
        
        echo $this->templateEngine->render('pages/cgu.html.twig', [
            "user"=> $user,
        ]);
    }

    public function contactPage() {
        $user = $_SESSION['user'] ?? null;
        
        echo $this->templateEngine->render('pages/contact.html.twig', [
            "user"=> $user,
        ]);
    }

    public function cookiesPolicyPage() {
        $user = $_SESSION['user'] ?? null;
        
        echo $this->templateEngine->render('pages/cookies-policy.html.twig', [
            "user"=> $user,
        ]);
    }

    public function legalNoticesPage() {
        $user = $_SESSION['user'] ?? null;
        
        echo $this->templateEngine->render('pages/legal-notices.html.twig', [
            "user"=> $user,
        ]);
    }

    public function privacyPolicyPage() {
        $user = $_SESSION['user'] ?? null;
        
        echo $this->templateEngine->render('pages/privacy-policy.html.twig', [
            "user"=> $user,
        ]);
    }


    public function searchOfferPage() {
        $user = $_SESSION['user'] ?? null;
        
        echo $this->templateEngine->render('pages/search-offer.html.twig', [
            "user"=> $user,
        ]);
    }

    public function searchCompanyPage() {
        $user = $_SESSION['user'] ?? null;
        
        echo $this->templateEngine->render('pages/search-company.html.twig', [
            "user"=> $user,
        ]);
    }

    public function accountPage() {
        $user = $_SESSION['user'] ?? null;
        
        echo $this->templateEngine->render('pages/user.html.twig', [
            "user"=> $user,
        ]);
    }

    public function ListUserPage() {
        $user = $_SESSION['user'] ?? null;
        
        echo $this->templateEngine->render('pages/user.html.twig', [
            "user"=> $user,
        ]);
    }
}