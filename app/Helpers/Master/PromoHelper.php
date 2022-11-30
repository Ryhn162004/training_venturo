<?php

namespace App\Helpers\Master;

use App\Models\Master\PromoModel;
use App\Repository\CrudInterface;

class PromoHelper implements CrudInterface
{
    private $promoModel;

    public function __construct()
    {
        $this->promoModel = new PromoModel();
    }

    public function getAll(array $filter, int $itemPerPage, string $sort): object
    {
        return $this->promoModel->getAll($filter, $itemPerPage, $sort);
    }

    public function getAllTable(array $filter, int $itemPerPage, string $sort): object
    {
        return $this->promoModel->getAllTable($filter, $itemPerPage, $sort);
    }

    public function getById(int $id): object
    {
        return $this->promoModel->getById($id);
    }

    public function create(array $payload): array
    {
        try {
            // upload foto promo ke folder fotoPromo
            if(!empty($payload['foto'])){
                $folder_path = 'storage/upload/fotoPromo/';

                $img_parts = explode(';base64,', $payload['foto']);
                $img_type__ = explode('image/', $img_parts[0]);
                $img_type = $img_type__[1];
                $img_base64 = base64_decode($img_parts[1]);
                $file = $folder_path . uniqid() . '.' . $img_type;
                file_put_contents($file, $img_base64);

                $payload['foto'] = $file;
            }

            $newPromo = $this->promoModel->store($payload);
            return [
                'status' => true,
                'data' => $newPromo,
            ];
        } catch (\Throwable $th) {
            return [
                'status' => false,
                'data' => $th->getMessage(),
            ];
        }

        return $this->promoModel->store($payload);
    }

    public function update(array $payload, int $id): array
    {
        try {

            // upload foto promo ke folder fotoPromo
            if(!empty($payload['foto'])){
                $folder_path = 'storage/upload/fotoPromo/';

                $img_parts = explode(';base64,', $payload['foto']);
                $img_type__ = explode('image/', $img_parts[0]);
                $img_type = $img_type__[1];
                $img_base64 = base64_decode($img_parts[1]);
                $file = $folder_path . uniqid() . '.' . $img_type;
                file_put_contents($file, $img_base64);

                $payload['foto'] = $file;
            }
            $this->promoModel->edit($payload, $id);
            return [
                'status' => true,
                'data' => $this->getById($id),
            ];
        } catch (\Throwable $th) {
            return [
                'status' => false,
                'data' => $th->getMessage(),
            ];
        }

        return $this->promoModel->store($payload);
    }

    public function delete(int $id): bool
    {
        try {
            $this->promoModel->drop($id);
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }
}

?>
