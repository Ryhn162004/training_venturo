<?php

namespace App\Models\Master;

use App\Repository\ModelInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DiskonModel extends Model implements ModelInterface
{
    use HasFactory;

    protected $table = 'm_diskon';

    protected $primaryKey = 'id_diskon';

    public $timestamps = true;

    protected $fillable = [
        'id_user', 'id_promo', 'status', 'id_diskon'
    ];

    public function getAll(array $filter, int $itemPerPage, string $sort): object
    {
      $diskon = $this->query();

      // filter nama
      if(!empty($filter['nama'])){
        $diskon->where('nama', 'LIKE', '%'.$filter['nama'].'%');
    }

    $sort = $sort ?: 'id_diskon DESC';
    $diskon->orderByRaw($sort);
    $itemPerPage = ($itemPerPage > 0) ? $itemPerPage : false;

    return $diskon->paginate($itemPerPage)->appends('sort', $sort);
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
