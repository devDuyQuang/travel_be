<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Dashboard;
use App\Http\Controllers\DomainController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\HomepageSettingController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PageSettingController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ServiceRegistrationController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SitemapDownloadController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\TeamMemberController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->to(panel_route('login')));
Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('auth.login');

Route::middleware(['web', 'auth', 'admin.panel'])->group(function () {
    Route::get('/dashboard', [Dashboard::class, 'index'])->name('dashboard.index');
    Route::get('/dashboard/export-posts', [Dashboard::class, 'exportPosts'])->name('dashboard.export-posts');

    Route::prefix('product')->name('product.')->group(function () {

        Route::get('/', [ProductController::class, 'index'])->name('index');
        Route::get('/datatable', [ProductController::class, 'datatable'])->name('datatable');
        Route::get('/create', [ProductController::class, 'create'])->name('create');
        Route::post('/', [ProductController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [ProductController::class, 'edit'])->name('edit');
        Route::put('/{id}', [ProductController::class, 'update'])->name('update');

        Route::delete(
            '/gallery-image/{image}',
            [ProductController::class, 'destroyGalleryImage']
        )->name('gallery-image.destroy');

        Route::patch('/{id}/status', [ProductController::class, 'toggleStatus'])->name('toggle-status');
        Route::delete('/{id}', [ProductController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('booking')->name('booking.')->group(function () {
        Route::get('/', [BookingController::class, 'index'])->name('index');
        Route::get('/{booking}', [BookingController::class, 'show'])->name('show');
        Route::patch('/{booking}/status', [BookingController::class, 'updateStatus'])->name('status');
        Route::patch('/{booking}/internal-note', [BookingController::class, 'updateInternalNote'])->name('internal-note');
        Route::patch('/{booking}/payment', [BookingController::class, 'updatePayment'])->name('payment');
        Route::patch('/{booking}/quote', [BookingController::class, 'updateQuote'])->name('quote');
    });

    Route::prefix('order')->name('order.')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::get('/{order}', [OrderController::class, 'show'])->name('show');
        Route::patch('/{order}/status', [OrderController::class, 'updateStatus'])->name('status');
        Route::patch('/{order}/internal-note', [OrderController::class, 'updateInternalNote'])->name('internal-note');
        Route::patch('/{order}/payment', [OrderController::class, 'updatePayment'])->name('payment');
    });

    Route::get('/contact', [ContactController::class, 'index'])
        ->name('contact.index');
    Route::delete('/contact/{id}', [ContactController::class, 'destroy'])
        ->name('contact.destroy');
    Route::prefix('post')->name('post.')->group(function () {
        Route::get('/', [PostController::class, 'index'])->name('index');
        Route::get('/datatable', [PostController::class, 'datatable'])->name('datatable');
        Route::get('/create', [PostController::class, 'create'])->name('create');
        Route::post('/', [PostController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [PostController::class, 'edit'])->name('edit');
        Route::put('/{id}', [PostController::class, 'update'])->name('update');
        Route::delete('/{id}', [PostController::class, 'destroy'])->name('destroy');
        Route::patch('/{id}/status', [PostController::class, 'toggleStatus'])->name('toggle-status');
    });

    Route::resource('tag', TagController::class)->except(['show']);
    Route::resource('team-member', TeamMemberController::class)->parameters(['team-member' => 'teamMember'])->except(['show']);
    Route::resource('faq', FaqController::class)->except(['show']);

    Route::prefix('category')->name('category.')->group(function () {
        Route::patch('/{id}/home', [CategoryController::class, 'toggleHome'])
            ->name('toggle-home');
        Route::get('/', [CategoryController::class, 'index'])->name('index');
        Route::get('/datatable', [CategoryController::class, 'datatable'])->name('datatable');
        Route::post('/reorder', [CategoryController::class, 'reorder'])->name('reorder');
        Route::get('/create', [CategoryController::class, 'create'])->name('create');
        Route::post('/', [CategoryController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [CategoryController::class, 'edit'])->name('edit');
        Route::put('/{id}', [CategoryController::class, 'update'])->name('update');
        Route::patch('/{id}/status', [CategoryController::class, 'toggleStatus'])->name('toggle-status');
        Route::delete('/{id}', [CategoryController::class, 'destroy'])->name('destroy');
        Route::post('/update-order', [CategoryController::class, 'updateOrder'])->name('update-order');
    });

    Route::prefix('domain')->name('domain.')->group(function () {
        Route::get('/', [DomainController::class, 'index'])->name('index');
        Route::get('/datatable', [DomainController::class, 'datatable'])->name('datatable');
        Route::get('/create', [DomainController::class, 'create'])->name('create');
        Route::post('/', [DomainController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [DomainController::class, 'edit'])->name('edit');
        Route::put('/{id}', [DomainController::class, 'update'])->name('update');
        Route::patch('/{id}/status', [DomainController::class, 'toggleStatus'])->name('toggle-status');
        Route::delete('/{id}', [DomainController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('menu')->name('menu.')->group(function () {
        Route::get('/', [MenuController::class, 'index'])->name('index');
        Route::get('/datatable', [MenuController::class, 'datatable'])->name('datatable');
        Route::get('/targets', [MenuController::class, 'targets'])->name('targets');
        Route::post('/reorder', [MenuController::class, 'reorder'])->name('reorder');
        Route::get('/create', [MenuController::class, 'create'])->name('create');
        Route::post('/', [MenuController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [MenuController::class, 'edit'])->name('edit');
        Route::put('/{id}', [MenuController::class, 'update'])->name('update');
        Route::patch('/{id}/status', [MenuController::class, 'toggleStatus'])->name('toggle-status');
        Route::delete('/{id}', [MenuController::class, 'destroy'])->name('destroy');
        Route::post('/update-order', [MenuController::class, 'updateOrder'])->name('update-order');
    });

    Route::prefix('setting')->name('setting.')->group(function () {
        Route::get('/', [SettingController::class, 'index'])->name('index');
        Route::put('/logo-favicon', [SettingController::class, 'updateLogoFavicon'])->name('updateLogoFavicon');
        Route::put('/topbar', [SettingController::class, 'updateTopbar'])->name('updateTopbar');
        Route::put('/floating', [SettingController::class, 'updateFloating'])->name('updateFloating');
        Route::get('/home', [HomepageSettingController::class, 'index'])->name('home');
        Route::put('/home/{section}', [HomepageSettingController::class, 'update'])->name('home.update');
        Route::get('/service', [SettingController::class, 'service'])->name('service');
        Route::put('/service-hero', [SettingController::class, 'updateServiceHero'])->name('updateServiceHero');
        Route::put('/service-features', [SettingController::class, 'updateServiceFeatures'])->name('updateServiceFeatures');
        Route::put('/service-plans', [SettingController::class, 'updateServicePlans'])->name('updateServicePlans');
        Route::get('/contact-page', [SettingController::class, 'contactPage'])->name('contactPage');
        Route::put('/contact-hero', [SettingController::class, 'updateContactHero'])->name('updateContactHero');
        Route::put('/contact-info', [SettingController::class, 'updateContactInfo'])->name('updateContactInfo');
        Route::put('/contact-locations', [SettingController::class, 'updateContactLocations'])->name('updateContactLocations');

        Route::get('/about-page', [SettingController::class, 'aboutPage'])->name('aboutPage');
        Route::put('/about-hero', [SettingController::class, 'updateAboutHero'])->name('updateAboutHero');
        Route::put('/about-gallery', [SettingController::class, 'updateAboutGallery'])->name('updateAboutGallery');
        Route::delete('/about-gallery-image', [SettingController::class, 'removeAboutGalleryImage'])->name('removeAboutGalleryImage');
        Route::put('/about-intro', [SettingController::class, 'updateAboutIntro'])->name('updateAboutIntro');
        Route::put('/about-values', [SettingController::class, 'updateAboutValues'])->name('updateAboutValues');
        Route::put('/about-consultation', [SettingController::class, 'updateAboutConsultation'])->name('updateAboutConsultation');
        Route::get('/page/{page}', [PageSettingController::class, 'edit'])->name('page.edit');
        Route::put('/page/{page}', [PageSettingController::class, 'update'])->name('page.update');
        Route::put('/{id}', [SettingController::class, 'update'])->name('update');
    });

    Route::middleware(['permission:user.view'])->prefix('user')->name('user.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/datatable', [UserController::class, 'datatable'])->name('datatable');

        Route::middleware(['permission:user.create'])->group(function () {
            Route::get('/create', [UserController::class, 'create'])->name('create');
            Route::post('/', [UserController::class, 'store'])->name('store');
        });

        Route::middleware(['permission:user.update'])->group(function () {
            Route::get('/edit/{id}', [UserController::class, 'edit'])->name('edit');
            Route::put('/{id}', [UserController::class, 'update'])->name('update');
        });

        Route::delete('/{id}', [UserController::class, 'destroy'])
            ->middleware('permission:user.delete')
            ->name('destroy');
    });

    Route::middleware(['permission:role.view'])->prefix('role')->name('role.')->group(function () {

        Route::get('/', [RoleController::class, 'index'])->name('index');
        Route::get('/datatable', [RoleController::class, 'datatable'])->name('datatable');

        Route::middleware(['permission:role.create'])->group(function () {
            Route::get('/create', [RoleController::class, 'create'])->name('create');
            Route::post('/', [RoleController::class, 'store'])->name('store');
        });

        Route::middleware(['permission:role.update'])->group(function () {
            Route::get('/edit/{id}', [RoleController::class, 'edit'])->name('edit');
            Route::put('/{id}', [RoleController::class, 'update'])->name('update');
        });

        Route::delete('/{id}', [RoleController::class, 'destroy'])
            ->middleware('permission:role.delete')
            ->name('destroy');
    });

    Route::prefix('comment')->name('comment.')->group(function () {
        Route::get('/', [CommentController::class, 'index'])->name('index');
        Route::get('/datatable', [CommentController::class, 'datatable'])->name('datatable');
        Route::post('/{id}/approve', [CommentController::class, 'approve'])->name('approve');
        Route::post('/{id}/hide', [CommentController::class, 'hide'])->name('hide');
        Route::post('/{id}/reply', [CommentController::class, 'reply'])->name('reply');
        Route::get('/create', [CommentController::class, 'create'])->name('create');
        Route::post('/', [CommentController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [CommentController::class, 'edit'])->name('edit');
        Route::put('/{id}', [CommentController::class, 'update'])->name('update');
        Route::delete('/{id}', [CommentController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('service-registrations')->name('service-registrations.')->group(function () {
        Route::get('/', [ServiceRegistrationController::class, 'index'])->name('index');
        Route::get('/datatable', [ServiceRegistrationController::class, 'datatable'])->name('datatable');
        Route::get('/edit/{id}', [ServiceRegistrationController::class, 'edit'])->name('edit');
        Route::put('/{id}', [ServiceRegistrationController::class, 'update'])->name('update');
        Route::delete('/{id}', [ServiceRegistrationController::class, 'destroy'])->name('destroy');
    });

    Route::group(['prefix' => 'laravel-filemanager', 'middleware' => ['web', 'auth']], function () {
        \UniSharp\LaravelFilemanager\Lfm::routes();
    });

    Route::get('/logout', [AuthController::class, 'logout'])->name('auth.logout');

    Route::get('sitemaps/download-all', [SitemapDownloadController::class, 'downloadAll'])
        ->name('admin.sitemaps.download_all');
});
