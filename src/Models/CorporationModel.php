<?php
namespace App\Models;
use App\Models\Model;

class CorporationModel extends Model {

    protected $table = "Corporation";
    private $connexion;

    public function __construct() {
        $this->connexion = new Connexion();
    }

    public function getAll(): array{
        $result = $this->connexion->select($this->table);
        return $result ?? null;
    }

    public function getById(int $id): array|null {
        $result = $this->connexion->selectJoin(
            $this->table,
            'INNER JOIN Offer AS o ON c.Siret = o.Siret', 
            ['Siret' => $id]
        );

        return $result ?? null;
    }

    public function searchcorporation($search) {

        $conditions = [
            'name'=> $search[0],
        ];

        $rawfilters = [
            'localisation'=> $search[1],
            'rating'=> $search[2],
            'sector'=> $search[3],
            'type'=> $search[4],
        ];

        $filters = array_filter($rawfilters, fn($v) => $v !== null && $v !== '');

        $result = $this->connexion->selectLikeFilters($this->table, $conditions, $filters);
        
        return $result ?? null;
    }


    public function createCompany($Siret, $name, $mail, $phone, $description, $grade): bool {
        $data = [
            'Siret'=>$Siret,
            'name'=> $name,
            'compdescription' => $description,
            'mail'=> $mail,
            'phone'=> $phone,
            'rating'=> $grade
        ];
        return $this->connexion->insert($this->table, $data);
    }
    
    public function supprimerEntreprise(string $siret): bool{
        return $this->connexion->delete($this->table, [
            'Siret' => $siret
        ]);
    }
    
    public function updateCompany($Siret, $name, $mail, $phone, $description, $grade): bool {
        $data = [
            'name'=> $name,
            'compdescription' => $description,
            'mail'=> $mail,
            'phone'=> $phone,
            'rating' => $grade
        ];

        $condition = [
            'Siret' => $Siret,
        ];

        return $this->connexion->update($this->table, $data, $condition);
    }

    public function deleteCompany($siret): bool {
        return $this->connexion->delete($this->table, [
            'Siret' => $siret
        ]);
    }

    public function updateRating($siret, $newRating) {
        $result = $this->connexion->select($this->table, ['Siret' => $siret]);
        if (empty($result)) {
            return false; 
        }
        $company = $result[0];
        $currentRating = $company->rating;
        $currentNb = $company->nbrating;
        
        // calcule moyenne
        $newNb = $currentNb + 1;
        $newAvg = (($currentRating * $currentNb) + $newRating) / $newNb;
        
        $data = [
            'rating'   => $newAvg,
            'nbrating' => $newNb,
        ];
        $condition = [
            'Siret' => $siret,
        ];
        
        $result =  $this->connexion->update($this->table, $data, $condition);

        return $result;
    }
}



