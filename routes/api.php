<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ArtistController;
use App\Http\Controllers\Api\AgentController;
use App\Http\Controllers\Api\AdminController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::get('/agencies', [AuthController::class, 'getAgencies']);

// Test route for Bearer token authentication
Route::middleware('auth:sanctum')->get('/me-test', function (\Illuminate\Http\Request $request) {
    return response()->json([
        'authenticated' => true,
        'user' => $request->user(),
    ]);
});

// Media files route (authenticated users only)
Route::middleware('auth:sanctum')->get('/media/{path}', function (string $path, \Illuminate\Http\Request $request) {
    // Clean the path - remove any leading/trailing slashes
    $path = ltrim($path, '/');
    
    $fullPath = storage_path('app/public/' . $path);
    
    if (!file_exists($fullPath)) {
        \Log::error('Media file not found', ['path' => $path, 'fullPath' => $fullPath]);
        abort(404, 'File not found: ' . $path);
    }

    // Get MIME type
    $extension = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));
    $mimeTypes = [
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png' => 'image/png',
        'gif' => 'image/gif',
        'webp' => 'image/webp',
        'pdf' => 'application/pdf',
        'mp3' => 'audio/mpeg',
        'wav' => 'audio/wav',
        'm4a' => 'audio/mp4',
        'mp4' => 'video/mp4',
        'mov' => 'video/quicktime',
        'avi' => 'video/x-msvideo',
        'mkv' => 'video/x-matroska',
    ];
    $mimeType = $mimeTypes[$extension] ?? (mime_content_type($fullPath) ?: 'application/octet-stream');
    
    $fileSize = filesize($fullPath);

    // Disable output buffering for large files
    if (ob_get_level()) {
        ob_end_clean();
    }
    
    // Set time limit for large files
    set_time_limit(0);
    
    // Use streaming response for large files
    return response()->stream(function() use ($fullPath) {
        $handle = fopen($fullPath, 'rb');
        if ($handle) {
            while (!feof($handle)) {
                echo fread($handle, 8192); // Read 8KB chunks
                flush();
            }
            fclose($handle);
        }
    }, 200, [
        'Content-Type' => $mimeType,
        'Content-Length' => $fileSize,
        'Accept-Ranges' => 'bytes',
        'Cache-Control' => 'no-cache, private',
        'Access-Control-Allow-Origin' => '*',
        'Connection' => 'keep-alive',
    ]);
})->where('path', '.*')->name('api.media');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/welcome', [AuthController::class, 'welcome']);

    // Artist Routes
    Route::prefix('artist')->name('artist.')->group(function () {
        // Profile
        Route::get('/profile', [ArtistController::class, 'getProfile']);
        Route::post('/profile', [ArtistController::class, 'updateProfile']);
        Route::put('/profile', [ArtistController::class, 'updateProfile']); // Keep PUT for backward compatibility

        // Artworks
        Route::get('/artworks', [ArtistController::class, 'getArtworks']);
        Route::get('/artworks/{id}', [ArtistController::class, 'getArtwork']);
        Route::post('/artworks', [ArtistController::class, 'createArtwork']);
        Route::put('/artworks/{id}', [ArtistController::class, 'updateArtwork']);
        Route::delete('/artworks/{id}', [ArtistController::class, 'deleteArtwork']);
        Route::post('/artworks/{id}/pay-tax', [ArtistController::class, 'payPlatformTax']);
        Route::get('/categories', [ArtistController::class, 'getCategories']);

        // Wallet
        Route::get('/wallet', [ArtistController::class, 'getWallet']);
        Route::get('/wallet/transactions', [ArtistController::class, 'getTransactions']);
        Route::get('/wallet/pvs', [ArtistController::class, 'getPVs']);
        Route::post('/wallet/recharge', [ArtistController::class, 'rechargeWallet']);
        Route::get('/wallet/recharge-requests', [ArtistController::class, 'getPendingRechargeRequests']);

        // Complaints
        Route::get('/complaints', [ArtistController::class, 'getComplaints']);
        Route::get('/complaints/{id}', [ArtistController::class, 'getComplaint']);
        Route::post('/complaints', [ArtistController::class, 'createComplaint']);

        // Notifications
        Route::get('/notifications', [ArtistController::class, 'getNotifications']);
        Route::get('/notifications/unread-count', [ArtistController::class, 'getUnreadCount']);
        Route::post('/notifications/{id}/mark-read', [ArtistController::class, 'markNotificationRead']);
        Route::post('/notifications/mark-all-read', [ArtistController::class, 'markAllNotificationsRead']);
        Route::delete('/notifications/{id}', [ArtistController::class, 'deleteNotification']);


    });

    // Admin Routes (for replying to complaints)
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::post('/complaints/{id}/reply', [AdminController::class, 'replyToComplaint']);
    });

    // Agent Routes
    Route::prefix('agent')->name('agent.')->group(function () {
        // Dashboard
        Route::get('/dashboard', [AgentController::class, 'getDashboard']);

        // Profile
        Route::get('/profile', [AgentController::class, 'getProfile']);
        Route::post('/profile', [AgentController::class, 'updateProfile']);
        Route::put('/profile', [AgentController::class, 'updateProfile']);

        // Missions
        Route::get('/missions', [AgentController::class, 'getMissions']);
        Route::get('/missions/{id}', [AgentController::class, 'getMission']);
        Route::post('/missions/{id}/status', [AgentController::class, 'updateMissionStatus']);

        // PVs
        Route::get('/pvs', [AgentController::class, 'getPVs']);
        Route::get('/pvs/{id}', [AgentController::class, 'getPV']);
        Route::post('/pvs', [AgentController::class, 'createPV']);
        Route::post('/pvs/{pvId}/devices', [AgentController::class, 'addDeviceToPV']);
        Route::delete('/pvs/{pvId}/devices/{deviceId}', [AgentController::class, 'deleteDeviceFromPV']);
        Route::post('/pvs/{pvId}/artworks', [AgentController::class, 'addArtworkToPV']);
        Route::delete('/pvs/{pvId}/artworks/{artworkUsageId}', [AgentController::class, 'deleteArtworkFromPV']);
        Route::post('/pvs/{pvId}/artworks/artists', [AgentController::class, 'getArtistsByAgency']);
        Route::post('/pvs/{pvId}/artworks/list', [AgentController::class, 'getArtworksByArtist']);

        // PV Payment Management
        Route::post('/pvs/{pvId}/close', [AgentController::class, 'closePV']);
        Route::post('/pvs/{pvId}/payment', [AgentController::class, 'updatePayment']);
        Route::post('/pvs/{pvId}/payment-proof', [AgentController::class, 'uploadPaymentProof']);
        Route::post('/pvs/{pvId}/photos', [AgentController::class, 'uploadPhotos']);
        Route::get('/shop-types', [AgentController::class, 'getShopTypes']);
        Route::get('/device-types', [AgentController::class, 'getDeviceTypes']);
        Route::get('/agencies', [AgentController::class, 'getAgencies']);

        // Complaints
        Route::get('/complaints', [AgentController::class, 'getComplaints']);
        Route::get('/complaints/{id}', [AgentController::class, 'getComplaint']);
        Route::post('/complaints', [AgentController::class, 'createComplaint']);
        Route::get('/agency-users', [AgentController::class, 'getAgencyUsers']);
        Route::post('/complaints/{id}/reply', [AgentController::class, 'replyToComplaint']);

        // Notifications
        Route::get('/notifications', [AgentController::class, 'getNotifications']);
        Route::get('/notifications/unread-count', [AgentController::class, 'getUnreadCount']);
        Route::post('/notifications/{id}/mark-read', [AgentController::class, 'markNotificationRead']);
        Route::post('/notifications/mark-all-read', [AgentController::class, 'markAllNotificationsRead']);
        Route::delete('/notifications/{id}', [AgentController::class, 'deleteNotification']);


    });
});
