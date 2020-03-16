<?php

namespace App\Http\Controllers;

use App\Booking;
use App\Experience;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KPIController extends Controller
{
    public function __construct()
    {
        
    }

    public function get_total_created_experiences_per_month($merchant_id)
    {
        $data = Experience::select(
            DB::raw(
                'year(created_at) as year, monthname(created_at) as month, 
                count(id) as total_experiences')
        )->where('merchant_id', $merchant_id)
        ->whereRaw("YEAR(created_at) = YEAR(CURDATE())")
        ->orderBy(DB::raw('monthname(created_at)'))
        ->groupBy(DB::raw('year(created_at)'))
        ->groupBy(DB::raw('monthname(created_at)'))
        ->get();
        $new_data = compact('data');
        return response()->json($new_data, 200);
    }

    public function get_total_user_bookings_per_month($merchant_id)
    {
        $data = Booking::select(
            DB::raw(
                'monthname(created_at) as month, 
                count(id) as total_bookings')
        )->where('merchant_id', $merchant_id)
        ->whereRaw("YEAR(created_at) = YEAR(CURDATE())")
        ->orderBy(DB::raw('monthname(created_at)'))
        ->groupBy(DB::raw('monthname(created_at)'))
        ->get();
        $new_data = compact('data');
        return response()->json($new_data, 200);
    }

    public function get_total_user_favourites_per_month($merchant_id)
    {
        $sql = "select monthname(fav.created_at) as month,
        count(fav.id) as total_favourites from favourites fav join experiences exp
        on fav.experience_id=exp.id         
        where exp.merchant_id=?
        and YEAR(fav.created_at) = YEAR(CURDATE())
        group by (monthname(fav.created_at))
        order by monthname(fav.created_at)";
        $data = DB::select($sql, [$merchant_id]);

        $new_data = compact('data');
        return response()->json($new_data, 200);
    }
}
