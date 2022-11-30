<?php

namespace App\Http\Controllers\Api\Report;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\Report\OrderHelper;

class OrderController extends Controller
{
    private $order;

    public function __construct()
    {
        $this->order = new OrderHelper();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $json = json_decode($request->filter);
        $filter = [
            'id_bulan' => $json->id_bulan ?? '',
            'id_tahun' => $json->id_tahun ?? '',
        ];
        $items=$this->order->getAll($filter, 0, $request->sort ?? '');

        return response()->success($items);
    }

    public function getAllPenjualanMenu(Request $request)
    {
        $filter = json_decode($request->filter);
        $filter = [
            'kategori' => $filter->kategori ?? '',
            'id_bulan' => $filter->bulan ?? 6,
            'id_tahun' => $filter->tahunn ?? 2022
        ];

        $items=$this->order->getPenjualanMenu($filter, 0, $request->sort ?? '');
        // dd('woi');
        return response()->success($items);
    }
}
