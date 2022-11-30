<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasRelationships;
use App\Models\User\UserModel;

class VoucherModel extends Model
{
    use HasRelationships, HasFactory;

    /**
    * Menentukan nama tabel yang terhubung dengan Class ini
    *
    * @var string
    */
    protected $table = 'm_voucher';

    /**
     * Menentukan primary key, jika nama kolom primary key adalah "id",
     * langkah deklarasi ini bisa dilewati
     *
     * @var string
     */
    protected $primaryKey = 'id_voucher';

    /**
     * Akan mengisi kolom "created_at" dan "updated_at" secara otomatis,
     *
     * @var bool
     */
    public $timestamps = true;

    protected $attributes = [

    ];

    protected $fillable = [
        'id_promo',
        'id_user',
        'nominal',
        'info_voucher',
        'periode_mulai',
        'periode_selesai',
        'type',
        'status',
        'catatan',
    ];

        /**
     * Relasi ke UserModel / tabel m_user
     *
     * @return void
     */
    public function user()
    {
        return $this->hasOne(UserModel::class, 'id', 'id_user');
    }

    /**
     * Relasi ke PromoModel / tabel m_promo
     *
     * @return void
     */
    public function promo()
    {
        return $this->hasOne(PromoModel::class, 'id_promo', 'id_promo')->withTrashed();
    }

    /**
     * Relasi ke ItemModelDet / tabel m_item_det
     *
     * @return void
     */

    public function getAll(array $filter, int $voucherPerPage = 0, string $sort = ''): object
    {
        $dataVoucher = $this->query()->with('user', 'promo');

        if(!empty($filter['id_user'])){
            $dataVoucher->where('id_user', $filter['id_user']);
        }

        if(!empty($filter['id_promo'])){
            $dataVoucher->where('id_promo', $filter['id_promo']);
        }

        if(!empty($filter['periode_mulai']) && !empty($filter['periode_selesai'])){
            $dataVoucher->whereBetween('periode_mulai', [$filter['periode_mulai'], $filter['periode_selesai']]);
        }

        $sort = $sort ?: 'id_user DESC';
        $dataVoucher->orderByRaw($sort);
        $voucherPerPage = $voucherPerPage > 0 ? $voucherPerPage : false;

        return $dataVoucher->paginate($voucherPerPage)->appends('sort', $sort);
    }

    public function getById(int $id_voucher): object
    {
    return $this->query()->find($id_voucher);
    }

    public function store(array $payload)
    {
        return $this->create($payload);
    }

    public function edit(array $payload, int $id_voucher)
    {
        return $this->find($id_voucher)->update($payload);
    }

    public function drop(int $id_voucher)
    {
        return $this->find($id_voucher)->delete();
    }


}
