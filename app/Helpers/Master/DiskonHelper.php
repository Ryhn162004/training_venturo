<?php

namespace App\Helpers\Master;

use App\Models\Master\DiskonModel;
use App\Models\Master\PromoModel;
use App\Models\User\UserModel;
use App\Repository\CrudInterface;

class DiskonHelper implements CrudInterface
{
    private $diskonModel;
    private $promoModel;
    private $userModel;

    public function __construct()
    {
        $this->diskonModel = new DiskonModel();
        $this->promoModel = new PromoModel();
        $this->userModel = new UserModel();
    }

    public function getAll(array $filter, int $itemPerPage, string $sort): object
    {
        // membuat temporary array
        $temp = [];

        // ambil semua data user
        $dataUser = $this->userModel->getAll([], 0, 'id DESC');

        // ambil semua data promo dg type diskon
        $dataPromo = $this->promoModel->where('type', 'diskon')->get();

        // ambil semua data diskon
        $dataDiskon = $this->diskonModel->getAll($filter, $itemPerPage, $sort);

        for ($i=0; $i < count($dataUser); $i++) {
            $temp[$i] = [
                'id_user' => $dataUser[$i]->id,
                'nama' => $dataUser[$i]->nama,
                'diskon' => []
            ];
            for ($j=0; $j < count($dataPromo); $j++) {
                $temp[$i]['diskon'][$j] = [
                    'id_promo' => $dataPromo[$j]->id_promo,
                    'nama' => $dataPromo[$j]->nama,
                    'status' => 0,
                    'id_diskon' => 0
                ];
                for ($k=0; $k < count($dataDiskon); $k++) {
                    if($dataDiskon[$k]->id_user == $dataUser[$i]->id && $dataDiskon[$k]->id_promo == $dataPromo[$j]->id_promo){
                        $temp[$i]['diskon'][$j]['status'] = $dataDiskon[$k]->status;
                        $temp[$i]['diskon'][$j]['id_diskon'] = $dataDiskon[$k]->id_diskon;
                    }
                }
            }
        }

        return (object) ['list' => $temp];
    }

    public function getById(int $id): object
    {
        return $this->diskonModel->getById($id);
    }

    public function create(array $payload): array
    {
        try {
            $newDiskon = $this->diskonModel->store($payload);
            return [
                'status' => true,
                'data' => $newDiskon
            ];
        } catch (\Throwable $th) {
            return [
                'status' => false,
                'error' => $th->getMessage()
            ];
        }
    }

    public function update(array $payload, int $id): array
    {
        try {
            $dataDiskon = $this->diskonModel->edit($payload, $id);
            return [
                'status' => true,
                'data' => $dataDiskon
            ];
        } catch (\Throwable $th) {
            return [
                'status' => false,
                'error' => $th->getMessage()
            ];
        }
    }

    public function delete(int $id): bool
    {
        try {
            $this->diskonModel->drop($id);
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }
}
