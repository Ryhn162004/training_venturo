<?php

namespace App\Helpers\Report;
use App\Models\Report\OrderModel;
use App\Repository\CrudInterface;

class OrderHelper
{
    private $orderModel;

    public function __construct()
    {
        $this->orderModel = new OrderModel();
    }

    public function getAll(
        array $filter,
        int $itemPerPage = 0,
        string $sort
    )
    {
        return $this->orderModel->getAll($filter, $itemPerPage, $sort);
    }

    public function getPenjualanMenu(
        array $filter,
        int $itemPerPage = 0,
        string $sort
    ){
        return $this->orderModel->getPenjualanMenu($filter, $itemPerPage, $sort);
    }
}
