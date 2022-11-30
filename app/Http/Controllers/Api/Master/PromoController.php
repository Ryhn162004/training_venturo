<?php

namespace App\Http\Controllers\Api\Master;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\Master\PromoHelper;
use App\Http\Requests\Promo\PromoRequest;
use App\Http\Resources\Promo\PromoResource;
use App\Http\Resources\Promo\PromoCollection;

class PromoController extends Controller
{

    private $promo;

    public function __construct()
    {
        $this->promo = new PromoHelper();
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
            'nama' => $json->nama ?? '',
            'nomor' => $json->nomor ?? 10,
            'type' => $json->type ?? ''
        ];
        $items = $this->promo->getAll($filter, $filter['nomor'], $request->sort ?? '');

        return response()->success(new PromoCollection($items));
    }

    public function index_table(Request $request)
    {
        $json = json_decode($request->filter);
        $filter = [
            'nama' => $json->nama ?? '',
            'nomor' => $json->nomor ?? 10,
            'type' => $json->type ?? ''
        ];
        $items = $this->promo->getAllTable($filter, $filter['nomor'], $request->sort ?? '');

        return response()->success(new PromoCollection($items));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PromoRequest $request)
    {
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->failed($request->validator->errors(), 422);
        }

        $dataInput = $request->only([
            'nama',
            'type',
            'diskon',
            'nominal',
            'kadaluarsa',
            'syarat_ketentuan',
            'foto'
        ]);
        $dataProm = $this->promo->create($dataInput);
        if (!$dataProm['status']) {
            return response()->failed($dataProm['error'], 422);
        }

        return response()->success([], 'Data promo berhasil disimpan');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $dataProm = $this->promo->getById($id);

        if(empty($dataProm)){
            return response()->failed(['Data promo tidak ditemukan']);
        }

        return response()->success(new PromoResource($dataProm));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->failed($request->validator->errors());
        }
        $dataInput = $request->only([
            'id_promo',
            'nama',
            'type',
            'diskon',
            'nominal',
            'kadaluarsa',
            'syarat_ketentuan',
            'foto'
        ]);
        $dataProm = $this->promo->update($dataInput, $dataInput['id_promo']);
        // dd($dataProm);
        if (!$dataProm['status']) {
            return response()->failed($dataProm['error'], 422);
        }

        return response()->success(new PromoResource($dataProm['data']), 'Data promo berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $dataProm = $this->promo->delete($id);

        if(!$dataProm) {
            return response()->failed(['Mohon maaf data promo tidak ditemukan']);
        }

        return response()->success($dataProm);
    }
}
