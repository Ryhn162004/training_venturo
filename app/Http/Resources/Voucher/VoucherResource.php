<?php

namespace App\Http\Resources\Voucher;

use Illuminate\Http\Resources\Json\JsonResource;

class VoucherResource extends JsonResource
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
            'id_voucher' => $this->id_voucher,
            'id_promo' => $this->id_promo,
            'id_user' => $this->id_user,
            'id_user' => $this->id_user,
            'nominal' => $this->nominal,
            'info_voucher' => $this->info_voucher,
            'periode_mulai' => $this->periode_mulai,
            'periode_selesai' => $this->periode_selesai,
            'type' => $this->type,
            'status' => $this->status,
            'catatan' => $this->catatan,
            'user' => [
                'id_user' => $this->user->id,
                'user_roles_id' => $this->user->user_roles_id,
                'nama' => $this->user->nama,
                'email' => $this->user->email,
                'foto' => $this->user->foto,
                'fotoUrl' => $this->user->fotoUrl()
            ],
            'promo' => [
                'id_promo' => $this->promo->id_promo,
                'nama' => $this->promo->nama,
                'type' => $this->promo->type,
                'nominal' => $this->promo->nominal,
                'kadaluarsa' => $this->promo->kadaluarsa,
                'syarat_ketentuan' => $this->promo->syarat_ketentuan,
                'foto' => $this->promo->foto,
                'fotoUrl' => $this->promo->fotoUrl()
            ]
        ];
    }
}
