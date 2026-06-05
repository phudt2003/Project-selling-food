<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{
    // 📊 Thống kê đơn hàng theo trạng thái
    public function orders()
    {
        $data = DB::table('tbl_order')
            // Join với bảng payment để lấy payment_method
            ->join('tbl_payment', 'tbl_order.payment_id', '=', 'tbl_payment.payment_id')
            ->select(
                // Sử dụng CASE để phân loại: nếu là 'COD' thì gán nhãn là 'cod', ngược lại là 'paid'
                DB::raw("CASE WHEN tbl_payment.payment_method = 'COD' THEN 'cod' ELSE 'paid' END as payment_group"),
                DB::raw('COUNT(tbl_order.order_id) as total')
            )
            ->groupBy('payment_group') // Nhóm theo kết quả của CASE
            ->pluck('total', 'payment_group')
            ->toArray();

        return view('admin.statistics.orders', compact('data'));
    }

     public function filterOrders(Request $request)
    {
        $start = $request->start_date;
        $end   = $request->end_date;

        $query = DB::table('tbl_order')
            ->join('tbl_payment', 'tbl_order.payment_id', '=', 'tbl_payment.payment_id');

        if ($start && $end) {
            $start_date = date('Y-m-d 00:00:00', strtotime($start));
            $end_date   = date('Y-m-d 23:59:59', strtotime($end));
            $query->whereBetween('tbl_order.created_at', [$start_date, $end_date]);
        }

        $data = $query->select(
                DB::raw("CASE WHEN tbl_payment.payment_method = 'COD' THEN 'cod' ELSE 'paid' END as payment_group"),
                DB::raw('COUNT(tbl_order.order_id) as total')
            )
            ->groupBy('payment_group')
            ->pluck('total', 'payment_group')
            ->toArray();
            
        return view('admin.statistics.orders', compact('data'));
    }

    // 📦 Thống kê sản phẩm bán chạy
    public function products()
    {
        $data = DB::table('tbl_order_details')
            ->join('tbl_product', 'tbl_order_details.product_id', '=', 'tbl_product.product_id')
            ->select('tbl_product.product_name', DB::raw('SUM(tbl_order_details.product_sales_quantity) as total_sold'))
            ->groupBy('tbl_product.product_name')
            ->orderByDesc('total_sold')
            ->limit(10)
            ->get();

        return view('admin.statistics.products', compact('data'));
    }

    public function filterProducts(Request $request)
{
    $start = $request->start_date;
    $end   = $request->end_date;

    $query = DB::table('tbl_order_details')
        ->join('tbl_product', 'tbl_order_details.product_id', '=', 'tbl_product.product_id')
        ->join('tbl_order', 'tbl_order_details.order_id', '=', 'tbl_order.order_id');

    if ($start && $end) {
        $start = date('Y-m-d 00:00:00', strtotime($start));
        $end   = date('Y-m-d 23:59:59', strtotime($end));

        $query->whereBetween('tbl_order.created_at', [$start, $end]);
    }

    $data = $query->select('tbl_product.product_name', DB::raw('SUM(tbl_order_details.product_sales_quantity) as total_sold'))
        ->groupBy('tbl_product.product_name')
        ->orderByDesc('total_sold')
        ->limit(10)
        ->get();

    return view('admin.statistics.products', compact('data'));
}

    // 💰 Thống kê doanh thu theo tháng
    public function revenue()
    {
        $year = now()->year;

        $data = DB::table('tbl_order')
            ->select(DB::raw('MONTH(created_at) as month, SUM(order_total) as total'))
            ->where('order_status', 1)
            ->whereYear('created_at', $year)
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->pluck('total', 'month')
            ->toArray();

        return view('admin.statistics.revenue', compact('data', 'year'));
    }

    public function filterRevenue(Request $request)
    {
        $year = $request->year ?? now()->year;

        $data = DB::table('tbl_order')
            ->select(DB::raw('MONTH(created_at) as month, SUM(order_total) as total'))
            ->where('order_status', 1)
            ->whereYear('created_at', $year)
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->pluck('total', 'month')
            ->toArray();

        return view('admin.statistics.revenue', compact('data', 'year'));
    }
}
