<?php

namespace App\Http\Resources\Promo;

use Illuminate\Http\Resources\Json\JsonResource;

class PromoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id_promo' => $this->id_promo,
            'nama' => $this->nama,
            'type' => $this->type,
            'diskon' => $this->diskon,
            'nominal' => $this->nominal,
            'kadaluarsa' => $this->kadaluarsa,
            'syarat_ketentuan' => $this->syarat_ketentuan,
            'foto' => $this->foto,
            'fotoUrl' => $this->fotoUrl()
        ];
    }
}
