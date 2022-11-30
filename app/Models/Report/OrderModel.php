<?php

namespace App\Models\Report;

use App\Models\User\UserModel;
use App\Models\Master\ItemModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasRelationships;

class OrderModel extends Model
{
    use HasFactory, HasRelationships;

    protected $table = 't_order';

    protected $primaryKey = 'id_order';

    public function user(){
        return $this->hasOne(UserModel::class, 'id', 'id_user');
    }

    public function detOrder(){
        return $this->hasMany(DetOrderModel::class, 'id_detail', 'id_order');
    }

    public function getAll(array $filter, int $orderPerPage = 0, string $sort = ''): object{
        $dataOrder = $this->query()->with(['user','detOrder']);

        $sort = $sort ?: 'id_order DESC';
        $dataOrder->orderByRaw($sort);
        $orderPerPage = $orderPerPage > 0 ? $orderPerPage : false;
        return $dataOrder->get();
    }

    public function getPenjualanMenu(
        array $filter,
        int $itemsPerPage = 0,
        string $sort = '')
    {
        $temp = [];
        if(!empty($filter['id_bulan']) && !empty($filter['id_tahun'])){
            switch($filter['kategori']){
                case 'food':
                    $menuFood = $this->queryLaporanMenuByKategori(
                        $filter['id_bulan'],
                        $filter['id_tahun'],
                        'food'
                    );
                    dd($menuFood);
                    // $menuTotal = DB::select(
                    //     $this->queryLaporanMenuTotalByKategori(
                    //         $filter['id_bulan'],
                    //         $filter['id_tahun'],
                    //         ['food']
                    //         )
                    //     );
                    break;
                    case 'snack':
                        // $menuTotal = DB::select(
                        //     $this->queryLaporanMenuTotalByKategori(
                        //         $filter['id_bulan'],
                        //         $filter['id_tahun'],
                        //         ['snack']
                        //     )
                        // );
                        $menuSnack = $this->queryLaporanMenuByKategori(
                            $filter['id_bulan'],
                            $filter['id_tahun'],
                            'snack'
                        );
                    break;
                    case 'drink':
                        // $menuTotal = DB::select(
                        //     $this->queryLaporanMenuTotalByKategori(
                        //         $filter['id_bulan'],
                        //         $filter['id_tahun'],
                        //         ['drink']
                        //     )
                        // );
                        $menuDrink = $this->queryLaporanMenuByKategori(
                            $filter['id_bulan'],
                            $filter['id_tahun'],
                            'drink'
                        );
                        break;
                    default:
                        // $menuTotal = DB::select(
                        //     $this->queryLaporanMenuTotalByKategori(
                        //         $filter['id_bulan'],
                        //         $filter['id_tahun'],
                        //         ['drink', 'food', 'snack']
                        //     )
                        // );
                        $menuFood = $this->queryLaporanMenuByKategori(
                            $filter['id_bulan'],
                            $filter['id_tahun'],
                            'food'
                        );
                        $menuSnack = $this->queryLaporanMenuByKategori(
                            $filter['id_bulan'],
                            $filter['id_tahun'],
                            'snack'
                        );
                        $menuDrink = $this->queryLaporanMenuByKategori(
                            $filter['id_bulan'],
                            $filter['id_tahun'],
                            'drink'
                        );
            }
            $temp = [$menuFood, $menuDrink, $menuSnack];
            return $temp;
        }
    }

    public function queryLaporanMenuByKategori(
        $month,
        $year,
        $filter
        )
    {
        // SUM(CASE WHEN DAY(tanggal)=29 THEN t_order.total_order ELSE 0 END) as tgl29,
        $date_length = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        $query = "SELECT m_item.nama, m_item.kategori, SUM(t_order.total_order) AS total,";
        $temp_test = '';
        for ($i=0; $i < $date_length ; $i++) {
            if($i != $date_length - 1){
                $temp_test .= ' SUM(CASE WHEN DAY(tanggal)=' . ($i + 1) . ' THEN t_order.total_order ELSE 0 END) as tgl' . ($i + 1) . ', ';
            }
            if($i == $date_length - 1){
                $temp_test .= ' SUM(CASE WHEN DAY(tanggal)=' . ($i + 1) . ' THEN t_order.total_order ELSE 0 END) as tgl' . ($i + 1) . '';
            }
        }

        // $queryKategori = 'where' . ' (';
        // for ($i = 0; $i < count($kategori); $i++) {
        //     $queryKategori .=
        //         "
        //     kategori='" .
        //         $kategori[$i] .
        //         "'" .
        //         ($i + 1 == count($kategori) ? '' : 'OR') .
        //         "
        //     ";
        // }

        // $from = " SUM(CASE WHEN MONTH(tanggal)= " . $month . " AND YEAR(tanggal)= " . $year . " THEN t_order.total_order ELSE 0 END) AS total FROM m_item LEFT JOIN t_detail_order ON t_detail_order.id_item = m_item.id LEFT JOIN t_order ON t_detail_order.id_order = t_order.id_order WHERE MONTH(tanggal)= " . $month . "AND YEAR(tanggal)= ". $year . $queryKategori . ') GROUP BY m_item.nama';

        // $from = " FROM t_detail_order JOIN m_item on t_detail_order.id_item = m_item.id JOIN t_order on t_detail_order.id_order = t_order.id_order where MONTH(tanggal)= '" . (string)$month . "' AND YEAR(tanggal)= '" . (string)$year . "' WHERE m_item.kategori = '" . (string)$filter . "' GROUP BY m_item.nama";

        $from = " FROM t_detail_order JOIN m_item on t_detail_order.id_item = m_item.id JOIN t_order on t_detail_order.id_order = t_order.id_order where MONTH(tanggal)= '" . (string)$month . "' AND YEAR(tanggal)= '" . (string)$year . "' WHERE GROUP BY m_item.nama";

        $query .= $temp_test . $from;
        // dd($query);
        return DB::select(DB::raw($query));
    }

    public function queryLaporanMenuTotalByKategori(
        $month = 8,
        $year = 2020,
        $kategori = ['snack','food','drink']
        )
    {
        $date_length = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        $query = "SELECT t_order.total_order AS nama ,";
        $temp_test = '';
        for ($i = 0; $i < $date_length; $i++) {
            $temp_test .=
                'SUM(CASE WHEN DAY(tanggal)=' .
                ($i + 1) .
                ' AND MONTH(tanggal)=' . $month . ' AND YEAR(tanggal)=' . $year . ' THEN t_detail_order.total ELSE 0 END) as tgl' .
                ($i + 1) .
                ',';
        }

        $queryKategori = 'AND' . ' (';
        for ($i = 0; $i < count($kategori); $i++) {
            $queryKategori .=
                "
            kategori='" .
                $kategori[$i] .
                "'" .
                ($i + 1 == count($kategori) ? '' : 'OR') .
                "
            ";
        }

        $from =
            "
            SUM(CASE WHEN MONTH(tanggal)= " . $month . " AND YEAR(tanggal)=" . $year . " THEN t_detail_order.total ELSE 0 END) AS total
         FROM t_detail_order
         JOIN m_item ON t_detail_order.id_item = m_item.id
         JOIN t_order ON t_detail_order.id_order = t_order.id_order
        where MONTH(tanggal)= '" .
            $month .
            "' and YEAR(tanggal)= '" .
            $year .
            "' " .
            $queryKategori .
            ')';
            $query .= $temp_test . $from;
        return $query;
    }
}
