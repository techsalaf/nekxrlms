<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

Route::get('/clear', function () {
    \Illuminate\Support\Facades\Artisan::call('optimize:clear');
});

Route::get('/deploy/run-migrations', function () {
    $token = request()->header('X-DEPLOY-TOKEN');
    $expectedToken = env('DEPLOY_HOOK_TOKEN');

    if (!$expectedToken) {
        $tokenFile = storage_path('app/deploy_hook_token.txt');
        if (file_exists($tokenFile)) {
            $expectedToken = trim((string) file_get_contents($tokenFile));
        }
    }

    if (!$expectedToken || !$token || !hash_equals($expectedToken, $token)) {
        abort(403);
    }

    try {
        $createdTables = [];
        $baselineApplied = [];

        if (!\Illuminate\Support\Facades\Schema::hasTable('migrations')) {
            \Illuminate\Support\Facades\Schema::create('migrations', function (\Illuminate\Database\Schema\Blueprint $table) {
                $table->id();
                $table->string('migration');
                $table->integer('batch');
            });
        }

        $batch = (int) (\Illuminate\Support\Facades\DB::table('migrations')->max('batch') ?: 1);

        if (!\Illuminate\Support\Facades\Schema::hasTable('course_access_controls')) {
            \Illuminate\Support\Facades\Schema::create('course_access_controls', function (\Illuminate\Database\Schema\Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->index();
                $table->unsignedBigInteger('course_id')->index();
                $table->boolean('is_locked')->default(false);
                $table->unsignedBigInteger('updated_by')->nullable();
                $table->timestamps();
                $table->unique(['user_id', 'course_id']);
            });
            $createdTables[] = 'course_access_controls';
        }

        if (!\Illuminate\Support\Facades\Schema::hasTable('lesson_completions')) {
            \Illuminate\Support\Facades\Schema::create('lesson_completions', function (\Illuminate\Database\Schema\Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->index();
                $table->unsignedBigInteger('course_id')->index();
                $table->unsignedBigInteger('lesson_id')->index();
                $table->timestamp('completed_at')->nullable();
                $table->timestamps();
                $table->unique(['user_id', 'lesson_id']);
            });
            $createdTables[] = 'lesson_completions';
        }

        $migrationNames = [
            '2026_03_05_000001_create_course_access_controls_table',
            '2026_03_05_000002_create_lesson_completions_table',
        ];

        foreach ($migrationNames as $migrationName) {
            $exists = \Illuminate\Support\Facades\DB::table('migrations')
                ->where('migration', $migrationName)
                ->exists();

            if (!$exists) {
                \Illuminate\Support\Facades\DB::table('migrations')->insert([
                    'migration' => $migrationName,
                    'batch'     => $batch,
                ]);
                $baselineApplied[] = $migrationName;
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Deployment schema sync completed',
            'created_tables' => $createdTables,
            'baseline_applied' => $baselineApplied,
        ]);
    } catch (\Throwable $e) {
        return response()->json([
            'status' => 'error',
            'stage' => 'schema_sync',
            'message' => $e->getMessage(),
        ], 500);
    }
})->name('deploy.run.migrations');

// User Support Ticket
Route::controller('TicketController')->prefix('ticket')->name('ticket.')->group(function () {
    Route::get('/', 'supportTicket')->name('index');
    Route::get('new', 'openSupportTicket')->name('open');
    Route::post('create', 'storeSupportTicket')->name('store');
    Route::get('view/{ticket}', 'viewTicket')->name('view');
    Route::post('reply/{id}', 'replyTicket')->name('reply');
    Route::post('close/{id}', 'closeTicket')->name('close');
    Route::get('download/{attachment_id}', 'ticketDownload')->name('download');
});

Route::get('app/deposit/confirm/{hash}', 'Gateway\PaymentController@appDepositConfirm')->name('deposit.app.confirm');

Route::controller('SiteController')->group(function () {

    Route::get('courses', 'courses')->name('courses');
    Route::get('course/details/{slug}/{id}', 'courseDetails')->name('course.details');
    Route::post('course/reviews', 'loadReview')->name('course.reviews');

    Route::get('course/lessons/{slug}/{id}', 'courseLessons')->name('course.lesson');
    Route::post('course/lesson/complete/{id}', 'completeLesson')->middleware('auth')->name('course.lesson.complete');
    Route::get('lesson/asset/download/{id}', 'downloadLessonAsset')->name('lesson.asset.download');

    Route::get('category/{slug}/{id}', 'courseByCategory')->name('category.course');
    Route::post('coupon-check', 'checkCoupon')->name('coupon.check');

    Route::post('subscribe', 'subscribe')->name('subscribe');

    Route::get('/contact', 'contact')->name('contact');
    Route::post('/contact', 'contactSubmit');
    Route::get('/change/{lang?}', 'changeLanguage')->name('lang');

    Route::get('cookie-policy', 'cookiePolicy')->name('cookie.policy');

    Route::get('/cookie/accept', 'cookieAccept')->name('cookie.accept');

    Route::get('policy/{slug}', 'policyPages')->name('policy.pages');

    Route::get('placeholder-image/{size}', 'placeholderImage')->withoutMiddleware('maintenance')->name('placeholder.image');
    Route::get('maintenance-mode', 'maintenance')->withoutMiddleware('maintenance')->name('maintenance');

    Route::get('/{slug}', 'pages')->name('pages');
    Route::get('/', 'index')->name('home');
});
