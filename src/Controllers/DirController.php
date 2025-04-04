<?php 
namespace App\Controllers;

use App\Controllers\CompanyController;
use App\Controllers\UserController;

use App\Models\CorporationModel;
use App\Models\OfferModel;
use App\Models\CandidateModel;
use App\Models\WishlistModel;


class DirController extends Controller {

    protected $controllercompany = null;
    protected $controlleruser = null;
    protected $modelcorporation = null;
    protected $modeloffer = null;
    protected $modelcandidate = null;
    protected $modelwishlist = null;

    public function __construct($templateEngine) { 
        $this->controllercompany = new CompanyController($templateEngine);
        $this->controlleruser = new UserController($templateEngine);

        $this->modelcorporation = new CorporationModel();
        $this->modeloffer = new OfferModel();
        $this->modelcandidate = new CandidateModel();
        $this->modelwishlist = new WishlistModel();

        $this->templateEngine = $templateEngine;
    }

    public function homePage() {

        $user = $_SESSION['user'] ?? null;
        $popup = $_SESSION['flash'] ?? null;

        $companies = $this->modelcorporation->getAll();

        $offers = $this->modeloffer->getAll();
        
        echo $this->templateEngine->render("pages/home.html.twig", [
            'count' => $offers ? count($offers) : 0,
            "user"=> $user,
            'flash' => $popup,
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


    public function renderPagesAccount(){
        $user = $_SESSION['user'] ?? null;

        $wishlistuser = $this->modelwishlist->getWishlistForUser($user->Id_Users);
        $offersinwishlist = $this->modeloffer->getOffersArray($wishlistuser);

        $candidateOffers = $this->modeloffer->getCandidateOffers($user->Id_Users);

        $alloffers = $this->modeloffer->getAllOffers();
        $promotion = $this->controlleruser->getUserProm($user->Id_Users);
        $candidateCount = $this->modelcandidate->getCandidateCount($user->Id_Users);

        $corporation = $this->controllercompany->listCompany();
        $students = $this->controlleruser->listUsers();
        $tutor = $this->controlleruser->listTutor();

        $currentPage = $_GET['page'] ?? 1;
        $offersPerPage = 10; 
        $offset = ($currentPage - 1) * $offersPerPage;
        $totalOffers = count($alloffers);
        $totalPages = ceil($totalOffers / $offersPerPage); 
        $offersOnPage = array_slice($alloffers, $offset, $offersPerPage);

        echo $this->templateEngine->render('pages/user.html.twig', [
            'tutor' => $tutor,
            'students' => $students, 
            'corporation' => $corporation, 
            'promotion' => $promotion,
            'user' => $user ,
            
            
            'offers' => $alloffers,
            'count' => $alloffers ? count($alloffers) : 0,
            'pageoffers' => $offersOnPage,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,

            'offersinwishlist' => $offersinwishlist,

            'candidateOffers' => $candidateOffers,
            'candidateCount' => $candidateCount,
        ]);
    }


}