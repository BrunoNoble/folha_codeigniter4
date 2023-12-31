<?php

namespace App\Models;

use App\Entities\FolhaPontoEntity;
use CodeIgniter\Model;

class FolhaPontoModel extends Model
{
    protected $table            = 'folha_pontos';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = FolhaPontoEntity::class;
    protected $useSoftDeletes   = false;
    protected $protectFields    = false;
    protected $allowedFields    = [];




    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = false;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    public function getUser($userId)
    {
        $userModel = new UserModel();
        return $userModel->find($userId);
    }
}
