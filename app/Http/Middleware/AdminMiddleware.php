<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Check if the user is logged in
        if (!Auth::check()) {
            // If not logged in, redirect to login page
            return redirect()->route('login')->with('error', 'Please log in to access the admin panel.');
        }

        $user = Auth::user();

        // Check if the logged-in user is an admin
        if ($user->role === 'admin') {
            return $next($request);
        }

        if ($user->role === 'subadmin') {
            $allowedPermissions = $this->permissionsForRoute($request);

            foreach ($allowedPermissions as $permission) {
                if ($user->hasAdminPermission($permission)) {
                    return $next($request);
                }
            }

            if ($user->isCourierOnlySubadmin()) {
                return redirect()->route('admin.courier_transactions')
                    ->with('error', 'კურიერს აქვს წვდომა მხოლოდ კურიერის შეკვეთების გვერდზე.');
            }

            abort(403);
        }

        // Block everyone else
        abort(403);
        // If not an admin, redirect to the home page
        return redirect('/')->with('error', 'Unauthorized access.');
    }

    private function permissionsForRoute(Request $request): array
    {
        $permissionMap = [
            'dashboard.view' => [
                'admin',
            ],
            'books.view' => [
                'admin.books.index',
                'admin.books.show',
                'admin.search',
            ],
            'books.manage' => [
                'admin.books.create',
                'admin.books.store',
                'admin.books.edit',
                'admin.books.update',
                'admin.books.toggleVisibility',
                'admin.authors.*',
                'admin.categories.*',
                'admin.genres.*',
            ],
            'books.delete' => [
                'admin.books.destroy',
            ],
            'book_news.manage' => [
                'admin.book-news.*',
            ],
            'orders.manage' => [
                'admin.book_orders',
                'admin.book_orders.*',
            ],
            'transactions.manage' => [
                'admin.users_transactions',
                'admin.users.transactions.export',
                'admin.user.details',
                'admin.guest.order.details',
                'admin.orders.assign_courier',
                'admin.order.delete',
                'admin.orders.failed.delete',
                'admin.users.admin_note',
                'admin.markAsDelivered',
                'admin.undoDelivered',
                'admin.order.label',
            ],
            'courier.orders' => [
                'admin.courier_transactions',
                'admin.orders.courier_note',
                'admin.markAsDelivered',
                'admin.undoDelivered',
            ],
            'auctions.manage' => [
                'admin.auctions.*',
                'admin.auction.*',
                'admin.auction-categories.*',
                'admin.auction.participants',
                'auction.bids',
            ],
            'users.manage' => [
                'admin.users.list',
                'admin.users.delete',
            ],
            'qookies.manage' => [
                'admin.user.preferences.*',
            ],
            'bundles.manage' => [
                'admin.bundles.*',
            ],
            'exports.use' => [
                'admin.publishers.export',
                'admin.publisher.export',
                'admin.publisher.export.titles',
            ],
            'analytics.view' => [
                'admin.email.stats',
                'admin.topRatedArticles',
                'admin.user.keywords',
            ],
            'announcement.manage' => [
                'announcements.*',
            ],
        ];

        $permissions = [];

        foreach ($permissionMap as $permission => $routeNames) {
            if ($request->routeIs(...$routeNames)) {
                $permissions[] = $permission;
            }
        }

        return $permissions;
    }
}
