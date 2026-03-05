<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

Route::get('/clear', function () {
    \Illuminate\Support\Facades\Artisan::call('optimize:clear');
});

Route::get('/deploy/run-migrations', function () {
    @set_time_limit(0);

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

    $installOutput = '';
    $warnings = [];
    $baselineApplied = [];
    $targetedMigrationOutput = [];

    $requiredMigrations = [
        [
            'name'  => '2026_03_05_000001_create_course_access_controls_table',
            'table' => 'course_access_controls',
            'path'  => 'database/migrations/2026_03_05_000001_create_course_access_controls_table.php',
        ],
        [
            'name'  => '2026_03_05_000002_create_lesson_completions_table',
            'table' => 'lesson_completions',
            'path'  => 'database/migrations/2026_03_05_000002_create_lesson_completions_table.php',
        ],
    ];

    try {
        if (!\Illuminate\Support\Facades\Schema::hasTable('migrations')) {
            Artisan::call('migrate:install');
            $installOutput = trim(Artisan::output());
        }

        $batch = (int) (\Illuminate\Support\Facades\DB::table('migrations')->max('batch') ?: 1);

        foreach ($requiredMigrations as $migration) {
            $alreadyRecorded = \Illuminate\Support\Facades\DB::table('migrations')
                ->where('migration', $migration['name'])
                ->exists();

            if ($alreadyRecorded) {
                $targetedMigrationOutput[$migration['name']] = 'Already recorded';
                continue;
            }

            if (\Illuminate\Support\Facades\Schema::hasTable($migration['table'])) {
                \Illuminate\Support\Facades\DB::table('migrations')->insert([
                    'migration' => $migration['name'],
                    'batch'     => $batch,
                ]);
                $baselineApplied[] = $migration['name'];
                $targetedMigrationOutput[$migration['name']] = 'Table exists; migration baseline inserted';
                continue;
            }

            Artisan::call('migrate', [
                '--force' => true,
                '--path'  => $migration['path'],
            ]);
            $targetedMigrationOutput[$migration['name']] = trim(Artisan::output());
        }
    } catch (\Throwable $e) {
        return response()->json([
            'status' => 'error',
            'stage' => 'targeted_migrate',
            'message' => $e->getMessage(),
            'migrate_install' => $installOutput,
            'baseline_applied' => $baselineApplied,
            'targeted_migrations' => $targetedMigrationOutput,
        ], 500);
    }

    $clearOutput = '';
    $optimizeOutput = '';

    try {
        Artisan::call('optimize:clear');
        $clearOutput = trim(Artisan::output());
    } catch (\Throwable $e) {
        $warnings[] = 'optimize:clear failed: ' . $e->getMessage();
    }

    try {
        Artisan::call('optimize');
        $optimizeOutput = trim(Artisan::output());
    } catch (\Throwable $e) {
        $warnings[] = 'optimize failed: ' . $e->getMessage();
    }

    return response()->json([
        'status' => 'success',
        'message' => 'Deployment hook executed',
        'migrate_install' => $installOutput,
        'baseline_applied' => $baselineApplied,
        'migrate' => 'Targeted migration strategy completed',
        'targeted_migrations' => $targetedMigrationOutput,
        'optimize_clear' => $clearOutput,
        'optimize' => $optimizeOutput,
        'warnings' => $warnings,
    ]);
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
