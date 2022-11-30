<?php

namespace App\Http\Controllers\Api\Master;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\Master\VoucherHelper;
use App\Http\Requests\Voucher\VoucherRequest;
use App\Http\Resources\Voucher\VoucherResource;
use App\Http\Resources\Voucher\VoucherCollection;

class VoucherController extends Controller
{
    private $voucher;

    public function __construct()
    {
        $this->voucher = new VoucherHelper();
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
            'id_promo' => $json->id_promo ?? '',
            'id_user' => $json->id_user ?? '',
        ];
        $items=$this->voucher->getAll($filter, 5, $request->sort ?? '');

        return response()->success(new VoucherCollection($items));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(VoucherRequest $request)
    {
         /**
         * Menampilkan pesan error ketika validasi gagal
         * pengaturan validasi bisa dilihat pada class app/Http/request/Customer/CustomerRequest
         */
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->failed($request->validator->errors(), 422);
        }

        $dataInput = $request->only([
            'id_promo',
            'id_user',
            'nominal',
            'info_voucher',
            'periode_mulai',
            'periode_selesai',
            'status',
            'type',
            'catatan'
        ]);
        $dataVoucher = $this->voucher->create($dataInput);

        if (!$dataVoucher['status']) {
            return response()->failed($dataVoucher['error'], 422);
        }

        return response()->success([], 'Data voucher berhasil disimpan');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $dataVoucher = $this->voucher->getById($id);

        if (empty($dataVoucher)) {
            return response()->failed(['Data voucher tidak ditemukan']);
        }

        return response()->success(new VoucherResource($dataVoucher));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(VoucherRequest $request)
    {
        /**
         * Menampilkan pesan error ketika validasi gagal
         * pengaturan validasi bisa dilihat pada class app/Http/request/Customer/CustomerRequest
         */
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->failed($request->validator->errors());
        }

        $dataInput = $request->only([
            'id_voucher',
            'id_promo',
            'id_user',
            'nominal',
            'info_voucher',
            'periode_mulai',
            'periode_selesai',
            'status',
            'catatan'
        ]);
        $dataVoucher = $this->voucher->update($dataInput, $dataInput['id_voucher']);

        if (!$dataVoucher['status']) {
            return response()->failed($dataVoucher['error'], 422);
        }

        return response()->success([], 'Data voucher berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $dataVoucher = $this->voucher->delete($id);
        // dd($id);

        if(!$dataVoucher) {
            return response()->failed(['Mohon maaf data voucher tidak ditemukan']);
        }

        return response()->success($dataVoucher);
    }
}
