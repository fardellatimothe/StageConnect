<?php 
namespace App\Controllers;

use App\Models\CandidateModel;

class CandidateController extends Controller {

    protected $templateEngine;
    protected $candidatemodel;

    public function __construct($templateEngine) {
        $this->templateEngine = $templateEngine;
        $this->candidatemodel = new CandidateModel();
    }


    public function candidacy($id_offre) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $user = $_SESSION['user'] ?? null;

        $id_user = $user->Id_Users;

        if (!$id_user || !$id_offre) {
            die("Erreur : utilisateur ou offre invalide.");
        }

        $model = new CandidateModel();

        if ($model->candidatureExiste($id_user, $id_offre)) {
            $_SESSION['flash'] = "Vous avez déjà postulé à cette offre !";
            header("Location: /candidacy/$id_offre");
            exit();
        }

        $message = htmlspecialchars($_POST['message'] ?? '');

        // Dossier de destination
        $dir_path = __DIR__ . "/../../data/offre/" . $id_offre;
        if (!is_dir($dir_path)) {
            mkdir($dir_path, 0755, true);
        }

        $cv_filename = "cv$id_user.pdf";
        $destination = "$dir_path/$cv_filename";

        // Cas 1 : l'utilisateur a uploadé un nouveau CV
        if (isset($_FILES['cv-upload']) && $_FILES['cv-upload']['error'] === UPLOAD_ERR_OK) {
            move_uploaded_file($_FILES['cv-upload']['tmp_name'], $destination);
        } 
        // Cas 2 : copier le CV de base
        else {
            $source = __DIR__ . "/../../data/cv/cv$id_user.pdf";
            if (file_exists($source)) {
                copy($source, $destination);
            } else {
                die("CV introuvable.");
            }
        }

        // Chemin du cv pour la bdd
        $cv_path = "data/offre/$id_offre/$cv_filename";

        // enregistrement dans bdd
        $model->enregistrerCandidature($id_user, $id_offre, $message, $cv_path);

        $_SESSION['flash'] = "La candidature a bien été envoyé !";

        header("Location: /");
        exit();
    }
}