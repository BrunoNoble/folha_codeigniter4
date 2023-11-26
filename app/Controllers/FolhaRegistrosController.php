<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Database\Seeds\FolhaPonto;
use App\Models\FolhaPontoModel;

class FolhaRegistrosController extends BaseController
{
    public function index()
    {

        $model = new FolhaPontoModel();
        $itens = $model->paginate(10);

        return view('pages/registros_pontos', [
            'itens' => $itens,
            'pager' => $model->pager

        ]);
    }
}
