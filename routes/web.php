<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Frontend Routes
|--------------------------------------------------------------------------
*/

use App\Http\Livewire\Frontend\Products as FrontProducts;
use App\Http\Livewire\CartCheckout;

Route::get('/', function () {
    return view('welcome');
});

// عرض المنتجات للزوار
Route::get('/products', FrontProducts::class)
    ->name('frontend.products');

// صفحة إتمام الطلب
Route::get('/checkout', CartCheckout::class)
    ->name('checkout');
Route::get('/product/{slug}', \App\Http\Livewire\Frontend\ProductDetails::class)
    ->name('product.details');



/*
|--------------------------------------------------------------------------
| Admin Routes (Protected)
|--------------------------------------------------------------------------
*/

use App\Http\Livewire\Categories\Index as CategoriesIndex;
use App\Http\Livewire\Categories\Create as CategoriesCreate;
use App\Http\Livewire\Categories\Edit as CategoriesEdit;

use App\Http\Livewire\Products\Index as ProductsIndex;
use App\Http\Livewire\Products\Create as ProductsCreate;
use App\Http\Livewire\Products\Edit as ProductsEdit;
use App\Http\Livewire\Admin\Dashboard;
use App\Http\Livewire\Orders\Index as OrdersIndex;


use App\Http\Livewire\Frontend\Products;

Route::get('/', Products::class)->name('products.index');

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard
 Route::get('/dashboard', Dashboard::class)
    ->name('dashboard');

    Route::get('/orders', OrdersIndex::class)->name('orders.index');

        /*
        |--------------------------------------------------------------------------
        | Categories Management
        |--------------------------------------------------------------------------
        */

        Route::prefix('categories')
            ->name('categories.')
            ->group(function () {

                Route::get('/', CategoriesIndex::class)
                    ->name('index');

                Route::get('/create', CategoriesCreate::class)
                    ->name('create');

                Route::get('/{category}/edit', CategoriesEdit::class)
                    ->name('edit');
            });


        /*
        |--------------------------------------------------------------------------
        | Products Management
        |--------------------------------------------------------------------------
        */

        Route::prefix('products')
            ->name('products.')
            ->group(function () {

                Route::get('/', ProductsIndex::class)
                    ->name('index');

                Route::get('/create', ProductsCreate::class)
                    ->name('create');

                Route::get('/{product}/edit', ProductsEdit::class)
                    ->name('edit');
            });

    });

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
