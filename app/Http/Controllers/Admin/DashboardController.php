<?php

namespace App\Http\Controllers\Admin;

use App\Models\Book;
use Illuminate\Http\Request;
use App\Models\ArticleRating;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date') ? $request->input('start_date') . ' 00:00:00' : null;
        $endDate = $request->input('end_date') ? $request->input('end_date') . ' 23:59:59' : null;
    
        // Cache the total value of products
        $totalValueOfProducts = Cache::remember('total_value_of_products', 60, function () use ($startDate, $endDate) {
            return DB::table('books')
                ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                    return $query->whereBetween('created_at', [$startDate, $endDate]);
                })
                ->sum(DB::raw('price * quantity'));
        });
    
        // Cache the average price of products
        $averagePriceOfProducts = Cache::remember('average_price_of_products', 60, function () use ($startDate, $endDate) {
            return DB::table('books')
                ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                    return $query->whereBetween('created_at', [$startDate, $endDate]);
                })
                ->avg('price');
        });
    
        // Cache the total quantity of products
        $totalQuantityOfProducts = Cache::remember('total_quantity_of_products', 60, function () use ($startDate, $endDate) {
            return DB::table('books')
                ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                    return $query->whereBetween('created_at', [$startDate, $endDate]);
                })
                ->sum('quantity');
        });
    
        // Cache the total sales profit
        $totalSalesProfit = Cache::remember('total_sales_profit', 60, function () use ($startDate, $endDate) {
            return DB::table('order_items')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                    return $query->whereBetween('orders.created_at', [$startDate, $endDate]);
                })
                ->where('orders.status', 'pending')
                ->sum(DB::raw('order_items.price * order_items.quantity * 0.3'));
        });
    
        // Cache the average profit per unit
        $averageProfitPerUnit = Cache::remember('average_profit_per_unit', 60, function () use ($startDate, $endDate) {
            return DB::table('order_items')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                    return $query->whereBetween('orders.created_at', [$startDate, $endDate]);
                })
                ->where('orders.status', 'pending')
                ->avg(DB::raw('order_items.price * 0.3'));
        });
    
        // Cache the page views
        $pageViews = Cache::remember('page_views', 60, function () use ($startDate, $endDate) {
            return DB::table('page_views')
                ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                    return $query->whereBetween('created_at', [$startDate, $endDate]);
                })
                ->select(DB::raw('DATE(created_at) as date, COUNT(*) as views_per_day'))
                ->groupBy('date')
                ->orderByDesc('date')
                ->get();
        });
    
        // Cache the purchased products and total price
        $purchasedProducts = Cache::remember('purchased_products', 60, function () use ($startDate, $endDate) {
            return DB::table('order_items')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->join('books', 'order_items.book_id', '=', 'books.id')
                ->select('books.title', 'order_items.quantity', 'order_items.price', 'orders.created_at')
                ->where('orders.status', 'pending')
                ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                    return $query->whereBetween('orders.created_at', [$startDate, $endDate]);
                })
                ->paginate(5);
        });
    
        // Cache the total purchased price
        $totalPurchasedPrice = Cache::remember('total_purchased_price', 60, function () use ($startDate, $endDate) {
            return DB::table('order_items')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->where('orders.status', 'pending')
                ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                    return $query->whereBetween('orders.created_at', [$startDate, $endDate]);
                })
                ->sum(DB::raw('order_items.price * order_items.quantity'));
        });
    
        // Cache the top 10 customers
        $topCustomers = Cache::remember('top_customers', 60, function () {
            return DB::table('orders')
                ->select('user_id')
                ->selectRaw('SUM(total) as total_spent, COUNT(*) as total_orders')
                ->groupBy('user_id')
                ->orderByDesc('total_spent')
                ->take(10)
                ->get();
        });
    
        // Cache the top 10 bestselling books
        $topBooks = Cache::remember('top_books', 60, function () {
            return Book::with('author') // Include the author relationship
                ->join('order_items', 'order_items.book_id', '=', 'books.id')
                ->select('books.id', 'books.title', 'books.author_id', 'books.category_id', 'books.price', 'books.photo') // List all necessary columns explicitly
                ->selectRaw('SUM(order_items.quantity) as total_sold')
                ->groupBy('books.id', 'books.title', 'books.author_id', 'books.category_id', 'books.price', 'books.photo') // Group by these columns
                ->orderByDesc('total_sold')
                ->take(10)
                ->get();
        });
        
    
        // Cache the top-rated books
        $topRatedArticles = Cache::remember('top_rated_articles_dashboard', 60, function () {
            return ArticleRating::select('book_id', DB::raw('AVG(rating) as avg_rating'), DB::raw('count(*) as rating_count'))
                ->groupBy('book_id')
                ->orderByDesc('avg_rating')
                ->limit(10)
                ->get();
        });
    
        // Prepare data for the chart
        $ordersData = [];
        $orderStats = DB::table('orders')
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as total_orders')
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();
    
        for ($i = 1; $i <= 12; $i++) {
            $monthData = $orderStats->firstWhere('month', $i);
            $ordersData[] = $monthData ? $monthData->total_orders : 0; // Fill missing months with 0
        }
    
        return view('admin.dashboard', compact(
            'totalValueOfProducts',
            'averagePriceOfProducts',
            'totalQuantityOfProducts',
            'totalSalesProfit',
            'averageProfitPerUnit',
            'pageViews',
            'purchasedProducts',
            'totalPurchasedPrice',
            'ordersData',
            'topCustomers',
            'topBooks',
            'topRatedArticles'
        ));
    }
    

}
