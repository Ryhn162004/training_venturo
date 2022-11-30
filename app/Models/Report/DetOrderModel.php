<?php

namespace App\Models\Report;

use App\Models\Master\ItemModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasRelationships;

class DetOrderModel extends Model
{
    use HasFactory, HasRelationships;

    protected $table = 't_detail_order';

    protected $primaryKey = 'id_detail';

    public function item(){
        return $this->hasOne(ItemModel::class, 'id', 'id_item')->withTrashed();
    }

    public function order()
    {
        return $this->hasOne(OrderModel::class, 'id_order', 'id_order');
    }
}
