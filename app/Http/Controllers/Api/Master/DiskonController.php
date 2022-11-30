<?php

namespace App\Http\Controllers\Api\Master;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\Master\DiskonHelper;
use App\Http\Requests\Diskon\DiskonRequest;

class DiskonController extends Controller
{
    private $diskon;

    public function __construct()
    {
        $this->diskon = new DiskonHelper();
    }

    public function index(Request $request)
    {
        $json = json_decode($request->filter);
        $filter = ['nama' => $json->nama ?? ''];
        $items = $this->diskon->getAll($filter, 5, $request->sort ?? '');

        return response()->success($items);
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
    public function store(DiskonRequest $request)
    {
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->failed($request->validator->errors(), 422);
        }

        $dataInput = $request->only(['id_user', 'id_promo', 'status']);
        $dataDiskon = $this->diskon->create($dataInput);
        if (!$dataDiskon['status']) {
            return response()->failed($dataDiskon['error'], 422);
        }

        return response()->success([], 'Data diskon berhasil disimpan');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $dataDiskon = $this->diskon->getById($id);

        if(empty($dataDiskon)){
            return response()->failed(['Data diskon tidak ditemukan']);
        }

        return response()->success($dataDiskon);
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

        $dataInput = $request->only(['id_diskon', 'status']);
        $dataDiskon = $this->diskon->update($dataInput, $dataInput['id_diskon']);

        if (!$dataDiskon['status']) {
            return response()->failed($dataDiskon['error'], 422);
        }

        return response()->success($dataDiskon['data'], 'Data diskon berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $dataDiskon = $this->diskon->delete($id);
        // dd($id);

        if(!$dataDiskon) {
            return response()->failed(['Mohon maaf data diskon tidak ditemukan']);
        }

        return response()->success($dataDiskon);
    }
}
