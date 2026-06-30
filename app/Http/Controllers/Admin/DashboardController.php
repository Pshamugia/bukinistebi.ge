<?php

namespace App\Http\Controllers\Admin;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date') ? $request->input('start_date') . ' 00:00:00' : null;
        $endDate = $request->input('end_date') ? $request->input('end_date') . ' 23:59:59' : null;
        $cacheSuffix = md5(($startDate ?? 'all') . '|' . ($endDate ?? 'all'));
    
        $bookTotals = Cache::remember("book_totals_{$cacheSuffix}", 60, function () use ($startDate, $endDate) {
            return DB::table('books')
                ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                    return $query->whereBetween('created_at', [$startDate, $endDate]);
                })
                ->selectRaw('COALESCE(SUM(price * quantity), 0) as total_value')
                ->selectRaw('COALESCE(SUM((price - COALESCE(acquisition_price, 0)) * quantity), 0) as potential_profit')
                ->selectRaw('COALESCE(AVG(price), 0) as average_price')
                ->selectRaw('COALESCE(SUM(quantity), 0) as total_quantity')
                ->first();
        });

        $totalValueOfProducts = (float) $bookTotals->total_value;
        $potentialProfit = (float) $bookTotals->potential_profit;
        $averagePriceOfProducts = (float) $bookTotals->average_price;
        $totalQuantityOfProducts = (int) $bookTotals->total_quantity;
    
        // Profit is revenue minus acquisition cost. Missing acquisition cost means zero cost.
        $totalSalesProfit = Cache::remember("total_sales_profit_{$cacheSuffix}", 60, function () use ($startDate, $endDate) {
            return DB::table('order_items')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->leftJoin('books', 'books.id', '=', 'order_items.book_id')
                ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                    return $query->whereBetween('orders.created_at', [$startDate, $endDate]);
                })
                ->whereIn('orders.status', ['delivered', 'Succeeded'])
                ->sum(DB::raw('(order_items.price - COALESCE(books.acquisition_price, 0)) * order_items.quantity'));
        });
    
        // Cache the average profit per unit


        $averageProfitPerUnit = Cache::remember("average_profit_per_unit_{$cacheSuffix}", 60, function () use ($startDate, $endDate) {
            $query = DB::table('order_items')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->leftJoin('books', 'books.id', '=', 'order_items.book_id')
                ->whereIn('orders.status', ['delivered', 'Succeeded'])
                ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                    return $query->whereBetween('orders.created_at', [$startDate, $endDate]);
                });
        
            $totals = $query
                ->selectRaw('COALESCE(SUM((order_items.price - COALESCE(books.acquisition_price, 0)) * order_items.quantity), 0) as total_profit')
                ->selectRaw('COALESCE(SUM(order_items.quantity), 0) as total_quantity')
                ->first();
        
            return $totals->total_quantity > 0 ? $totals->total_profit / $totals->total_quantity : 0;
        });
        
        
        
        
    
        // Cache the total purchased price
      $totalPurchasedPrice = Cache::remember(
    "total_purchased_price_{$cacheSuffix}",
    60,
    function () use ($startDate, $endDate) {

        return DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereIn('orders.status', ['delivered', 'Succeeded'])
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                $query->whereBetween('orders.created_at', [$startDate, $endDate]);
            })
            ->sum(DB::raw('order_items.price * order_items.quantity'));
    }
);

    
        // Cache the top 10 customers
        $topCustomers = Cache::remember("top_customers_{$cacheSuffix}", 60, function () use ($startDate, $endDate) {
            return DB::table('orders')
                ->leftJoin('users', 'users.id', '=', 'orders.user_id')
                ->select('orders.user_id', 'users.name')
                ->selectRaw('SUM(orders.total) as total_spent, COUNT(*) as total_orders')
                ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                    return $query->whereBetween('orders.created_at', [$startDate, $endDate]);
                })
                ->groupBy('orders.user_id', 'users.name')
                ->orderByDesc('total_spent')
                ->take(10)
                ->get();
        });
    
        // Cache the top 10 bestselling books
        $topBooks = Cache::remember("top_books_{$cacheSuffix}", 60, function () use ($startDate, $endDate) {
            return Book::with('author') // Include the author relationship
                ->join('order_items', 'order_items.book_id', '=', 'books.id')
                ->join('orders', 'orders.id', '=', 'order_items.order_id')
                ->select('books.id', 'books.title', 'books.author_id', 'books.category_id', 'books.price', 'books.photo') // List all necessary columns explicitly
                ->selectRaw('SUM(order_items.quantity) as total_sold')
                ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                    return $query->whereBetween('orders.created_at', [$startDate, $endDate]);
                })
                ->groupBy('books.id', 'books.title', 'books.author_id', 'books.category_id', 'books.price', 'books.photo') // Group by these columns
                ->orderByDesc('total_sold')
                ->take(10)
                ->get();
        });
        
    
        // Cache the top-rated books
        $topRatedArticles = Cache::remember('top_rated_articles_dashboard', 60, function () {
            return DB::table('article_ratings')
                ->leftJoin('books', 'books.id', '=', 'article_ratings.book_id')
                ->select('article_ratings.book_id', 'books.title')
                ->selectRaw('AVG(article_ratings.rating) as avg_rating')
                ->selectRaw('COUNT(*) as rating_count')
                ->selectRaw('SUM(article_ratings.rating) as total_rating')
                ->groupBy('article_ratings.book_id', 'books.title')
                ->orderByDesc('avg_rating')
                ->limit(10)
                ->get();
        });
    
        // Prepare data for the chart
        $ordersData = [];
        $orderStats = Cache::remember('orders_chart_' . date('Y'), 60, function () {
            return DB::table('orders')
                ->selectRaw('MONTH(created_at) as month, COUNT(*) as total_orders')
                ->whereYear('created_at', date('Y'))
                ->groupBy('month')
                ->orderBy('month')
                ->get();
        });
    
        for ($i = 1; $i <= 12; $i++) {
            $monthData = $orderStats->firstWhere('month', $i);
            $ordersData[] = $monthData ? $monthData->total_orders : 0; // Fill missing months with 0
        }
        $maxOrders = max($ordersData) ?: 1;
    
        return view('admin.dashboard', compact(
            'totalValueOfProducts',
            'potentialProfit',
            'averagePriceOfProducts',
            'totalQuantityOfProducts',
            'totalSalesProfit',
            'averageProfitPerUnit',
            'totalPurchasedPrice',
            'ordersData',
            'maxOrders',
            'topCustomers',
            'topBooks',
            'topRatedArticles'
        ));
    }
    

}

