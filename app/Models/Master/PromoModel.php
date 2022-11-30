<?php

namespace App\Models\Master;

use App\Repository\ModelInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasRelationships;

class PromoModel extends Model implements ModelInterface
{
    use HasFactory, SoftDeletes, HasRelationships;

    protected $table = 'm_promo';

    protected $primaryKey = 'id_promo';

    public $timestamps = true;

    protected $attributes = [];

    protected $fillable = [
        'nama',
        'type',
        'diskon',
        'nominal',
        'kadaluarsa',
        'syarat_ketentuan',
        'foto'
    ];

    public function fotoUrl(){
        if (empty($this->foto)) {
            return asset('assets/img/no-image.png');
        }

        return asset($this->foto);
    }

    public function getAll(array $filter, int $itemPerPage, string $sort): object
    {
        $promo = $this->query();

        // filter nama
        if(!empty($filter['nama'])){
            $promo->where('nama', 'LIKE', '%'.$filter['nama'].'%');
        }

        if(!empty($filter['type'])){
            $promo->where('type', $filter['type']);
        };

        $sort = $sort ?: 'id_promo ASC';
        $promo->orderByRaw($sort);
        $itemPerPage = ($itemPerPage > 0) ? $itemPerPage : false;

        return $promo->paginate($itemPerPage)->appends('sort', $sort);
    }

    public function getAllTable(array $filter, int $itemPerPage, string $sort): object
    {
        $promo = $this->query();

        // filter nama
        if(!empty($filter['nama'])){
            $promo->where('nama', 'LIKE', '%'.$filter['nama'].'%');
        }

        if(!empty($filter['type'])){
            $promo->where('type', $filter['type']);
        };

        $sort = $sort ?: 'id_promo DESC';
        $promo->orderByRaw($sort);
        $itemPerPage = ($itemPerPage > 0) ? $itemPerPage : false;

        return $promo->paginate($itemPerPage)->appends('sort', $sort);
    }

    public function getById(int $id): object
    {
        return $this->find($id);
    }

    public function store(array $payload)
    {
        return $this->create($payload);
    }

    public function edit(array $payload, int $id)
    {
        return $this->find($id)->update($payload);
    }

    public function drop(int $id)
    {
        return $this->find($id)->delete();
    }
}
