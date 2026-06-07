<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoryProduct;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\DatabaseSyncController;
// =================== FRONTEND ====================
Route::get('/', [HomeController::class, 'index']);
Route::get('/trang-chu', [HomeController::class, 'index']);
Route::get('/_sync-database', DatabaseSyncController::class);
Route::post('/tim-kiem', [HomeController::class, 'search']);

// Khuyến mãi sốc
Route::get('/khuyen-mai-soc', [HomeController::class, 'show_shock_sale_products'])->name('show_shock_sale_products');

// Danh mục sản phẩm
Route::get('/danh-muc-san-pham/{category_id}', [CategoryProduct::class, 'show_category_home']);
Route::get('/chi-tiet-san-pham/{product_id}', [ProductController::class, 'details_product']);

// =================== BACKEND ====================
Route::get('/admin', [AdminController::class, 'index']);
Route::get('/dashboard', [AdminController::class, 'show_dashboard']);
Route::get('/logout', [AdminController::class, 'logout']);
Route::post('/admin-dashboard', [AdminController::class, 'dashboard']);

// =================== CATEGORY PRODUCT ====================
Route::get('/add-category-product', [CategoryProduct::class, 'add_category_product']);
Route::get('/edit-category-product/{category_product_id}', [CategoryProduct::class, 'edit_category_product']);
Route::get('/delete-category-product/{category_product_id}', [CategoryProduct::class, 'delete_category_product']);
Route::get('/all-category-product', [CategoryProduct::class, 'all_category_product']);
Route::get('/unactive-category-product/{category_product_id}', [CategoryProduct::class, 'unactive_category_product']);
Route::get('/active-category-product/{category_product_id}', [CategoryProduct::class, 'active_category_product']);
Route::post('/save-category-product', [CategoryProduct::class, 'save_category_product']);
Route::post('/update-category-product/{category_product_id}', [CategoryProduct::class, 'update_category_product']);

// =================== PRODUCT ====================
Route::get('/add-product', [ProductController::class, 'add_product']);
Route::get('/edit-product/{product_id}', [ProductController::class, 'edit_product']);
Route::get('/delete-product/{product_id}', [ProductController::class, 'delete_product']);
Route::get('/all-product', [ProductController::class, 'all_product']);
Route::get('/unactive-product/{product_id}', [ProductController::class, 'unactive_product']);
Route::get('/active-product/{product_id}', [ProductController::class, 'active_product']);
Route::post('/save-product', [ProductController::class, 'save_product']);
Route::post('/update-product/{product_id}', [ProductController::class, 'update_product']);

// =================== CART ====================
Route::post('/update-cart-quantity', [CartController::class, 'update_cart_quantity']);
Route::post('/save-cart-by-weight', [CartController::class, 'save_cart_by_weight']);
Route::post('/save-cart', [CartController::class, 'save_cart']);
Route::get('/show-cart', [CartController::class, 'show_cart']);
Route::get('/delete-to-cart/{rowId}', [CartController::class, 'delete_to_cart']);

// =================== CHECKOUT ====================
Route::get('/login-checkout', [CheckoutController::class, 'login_checkout']);
Route::get('/logout-checkout', [CheckoutController::class, 'logout_checkout']);
Route::post('/add-customer', [CheckoutController::class, 'add_customer']);
Route::post('/login-customer', [CheckoutController::class, 'login_customer']);
Route::post('/order-place', [CheckoutController::class, 'order_place']);

// ✅ Đổi checkout sang show_checkout và đặt tên route
Route::get('/checkout', [CheckoutController::class, 'show_checkout']);

Route::get('/payment', [CheckoutController::class, 'payment']);
Route::post('/save-checkout-customer', [CheckoutController::class, 'save_checkout_customer']);

// =================== ORDER ====================
Route::get('/manage-order', [CheckoutController::class, 'manage_order']);
Route::get('/view-order/{orderId}', [CheckoutController::class, 'view_order']);
Route::delete('/delete-order/{order_id}', [CheckoutController::class, 'delete_order']);
Route::post('/complete-order', [CheckoutController::class, 'complete_order'])->name('complete_order');
Route::post('/update-order-status/{order_id}', [CheckoutController::class, 'update_order_status']);

// =================== SHIPPING (Địa chỉ mới) ====================
Route::get('/add_new_address', [CheckoutController::class, 'add_new_address'])->name('add_new_address');
Route::delete('/delete-address/{shipping_id}', [CheckoutController::class, 'delete_address']);
Route::post('/save-new-address', [CheckoutController::class, 'save_new_address']);
Route::get('/edit-address/{shipping_id}', [CheckoutController::class, 'edit_address']);
Route::post('/update-address/{shipping_id}', [CheckoutController::class, 'update_address']);

// Lịch sử đơn hàng
Route::get('/lich-su-don-hang', [CheckoutController::class, 'show_history']);
Route::get('/xem-don-hang/{order_id}', [CheckoutController::class, 'view_customer_order']);

// =================== WISHLIST ====================
Route::post('/add-wishlist', [WishlistController::class, 'add_wishlist'])->name('wishlist.add');
Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
Route::post('/wishlist/remove/{id}', [WishlistController::class, 'remove'])->name('wishlist.remove');

// MoMo
Route::post('/momo-payment', [CheckoutController::class, 'momo_payment'])->name('momo.payment');
Route::get('/momo-return', [CheckoutController::class, 'momo_return'])->name('momo.return');
Route::get('/momo-napas-return', [CheckoutController::class, 'momo_napas_return'])->name('momo.napas.return');
Route::post('/momo-ipn', [CheckoutController::class, 'momo_ipn'])->name('momo.ipn');
// =================== STATISTICS ====================
// Orders
Route::get('/statistics-orders', [StatisticsController::class, 'orders'])->name('statistics.orders');
Route::post('/statistics-orders/filter', [StatisticsController::class, 'filterOrders'])->name('statistics.orders.filter');

// Products
Route::get('/statistics-products', [StatisticsController::class, 'products'])->name('statistics.products');
Route::post('/statistics-products/filter', [StatisticsController::class, 'filterProducts'])->name('statistics.products.filter');
//Manage_Payment
// =================== PAYMENT ====================
Route::get('/manage-payment', [CheckoutController::class, 'manage_payment'])->name('manage.payment');
Route::get('/view-payment/{payment_id}', [CheckoutController::class, 'view_payment'])->name('view.payment');
Route::delete('/delete-payment/{payment_id}', [CheckoutController::class, 'delete_payment'])->name('delete.payment');
Route::post('/update-payment-status/{payment_id}', [CheckoutController::class, 'update_payment_status'])->name('update.payment.status');

// Revenue
Route::get('/statistics-revenue', [StatisticsController::class, 'revenue'])->name('statistics.revenue');
Route::post('/statistics-revenue/filter', [StatisticsController::class, 'filterRevenue'])->name('statistics.revenue.filter');
