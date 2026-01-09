<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\WalletController as AdminWalletController;
use App\Http\Controllers\Agent\AgentController;
use App\Http\Controllers\Agent\MissionController as AgentMissionController;
use App\Http\Controllers\Artist\ArtistController;
use App\Http\Controllers\Gestionnaire\GestionnaireController;
use App\Http\Controllers\Gestionnaire\MissionController as GestionnaireMissionController;
use App\Http\Controllers\Gestionnaire\WalletController as GestionnaireWalletController;
use App\Http\Controllers\Gestionnaire\WalletRechargeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SuperAdmin\SuperAdminController;
use App\Http\Controllers\SuperAdmin\FooterSettingsController;
use App\Http\Controllers\SupportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/thanx', function () {
    return view('thanks');
});

// Support and Help pages
Route::get('/support', [SupportController::class, 'support'])->name('support');
Route::get('/help', [SupportController::class, 'help'])->middleware('auth')->name('help');

Route::middleware('auth')->get('/media/{path}', function (string $path, \Illuminate\Http\Request $request) {
    $fullPath = storage_path('app/public/' . $path);
    
    if (!file_exists($fullPath)) {
        abort(404);
    }

    $headers = ['Content-Type' => mime_content_type($fullPath)];
    
    if ($request->has('download')) {
        $headers['Content-Disposition'] = 'attachment; filename="' . ($request->get('filename') ?: basename($path)) . '"';
    }
    
    return response()->file($fullPath, $headers);
})->where('path', '.*')->name('media.show');

Route::get('/dashboard', function () {
    $user = auth()->user();
    $roleRoutes = [
        'super_admin' => 'superadmin.dashboard',
        'admin' => 'admin.dashboard',
        'gestionnaire' => 'gestionnaire.dashboard',
        'artist' => 'artist.dashboard',
        'agent' => 'agent.dashboard',
    ];

    foreach ($roleRoutes as $role => $route) {
        if ($user->hasRole($role)) {
            return redirect()->route($route);
        }
    }

    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:super_admin'])->prefix('superadmin')->name('superadmin.')->group(function () {
    Route::get('/dashboard', [SuperAdminController::class, 'dashboard'])->name('dashboard');
    
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllRead'])->name('notifications.mark-all-read');
    Route::post('/notifications/{id}/mark-read', [NotificationController::class, 'markRead'])->name('notifications.mark-read');
    Route::get('/notifications/{id}/view', [NotificationController::class, 'viewAndMarkRead'])->name('notifications.view');
    Route::delete('/notifications/{id}', [NotificationController::class, 'delete'])->name('notifications.delete');
    
    Route::get('/profile', [SuperAdminController::class, 'profile'])->name('profile');
    Route::post('/profile', [SuperAdminController::class, 'updateProfile'])->name('profile.update');

    Route::get('/wilayas', [SuperAdminController::class, 'allWilayas'])->name('all-wilayas');
    Route::get('/wilayas/{wilayaCode}', [SuperAdminController::class, 'showWilaya'])->name('show-wilaya');
    
    Route::get('/agencies', [SuperAdminController::class, 'manageAgencies'])->name('manage-agencies');
    Route::get('/agencies/{id}', [SuperAdminController::class, 'showAgency'])->name('show-agency');
    Route::post('/agencies/{id}/bank-account', [SuperAdminController::class, 'updateAgencyBankAccount'])->name('update-agency-bank-account');
    Route::get('/agencies/{id}/assign-admin', [SuperAdminController::class, 'assignAgencyAdmin'])->name('assign-agency-admin');
    Route::post('/agencies/{id}/assign-admin', [SuperAdminController::class, 'storeAgencyAdmin'])->name('store-agency-admin');
    Route::delete('/agencies/{id}/admin', [SuperAdminController::class, 'removeAgencyAdmin'])->name('remove-agency-admin');
    Route::get('/agencies/{id}/gestionnaires/create', [SuperAdminController::class, 'createAgencyGestionnaire'])->name('create-agency-gestionnaire');
    Route::post('/agencies/{id}/gestionnaires', [SuperAdminController::class, 'storeAgencyGestionnaire'])->name('store-agency-gestionnaire');
    Route::delete('/agencies/{agencyId}/gestionnaires/{gestionnaireId}', [SuperAdminController::class, 'removeAgencyGestionnaire'])->name('remove-agency-gestionnaire');
    Route::post('/agencies/{agencyId}/gestionnaires/{gestionnaireId}/transfer', [SuperAdminController::class, 'transferAgencyGestionnaire'])->name('transfer-agency-gestionnaire');
    
    Route::get('/agencies/{id}/agents/create', [SuperAdminController::class, 'createAgencyAgent'])->name('create-agency-agent');
    Route::post('/agencies/{id}/agents', [SuperAdminController::class, 'storeAgencyAgent'])->name('store-agency-agent');
    Route::delete('/agencies/{agencyId}/agents/{agentId}', [SuperAdminController::class, 'removeAgencyAgent'])->name('remove-agency-agent');
    Route::post('/agencies/{agencyId}/agents/{agentId}/transfer', [SuperAdminController::class, 'transferAgencyAgent'])->name('transfer-agency-agent');
    
    Route::post('/agencies/{agencyId}/artists/{artistId}/transfer', [SuperAdminController::class, 'transferAgencyArtist'])->name('transfer-agency-artist');
    
    Route::post('/agencies/{id}/admin/transfer', [SuperAdminController::class, 'transferAgencyAdmin'])->name('transfer-agency-admin');

    Route::get('/pvs', [SuperAdminController::class, 'managePVs'])->name('manage-pvs');
    Route::get('/pvs/{pv}', [SuperAdminController::class, 'viewPV'])->name('view-pv');

    Route::get('/admins/create', [SuperAdminController::class, 'createAdmin'])->name('create-admin');
    Route::post('/admins', [SuperAdminController::class, 'storeAdmin'])->name('store-admin');
    Route::post('/transfer-workers/create-admin', [SuperAdminController::class, 'storeAdminFromTransfer'])->name('store-admin-from-transfer');
    Route::delete('/admins/{id}', [SuperAdminController::class, 'removeAdmin'])->name('remove-admin');

    Route::get('/gestionnaires', [SuperAdminController::class, 'manageGestionnaires'])->name('manage-gestionnaires');
    Route::get('/gestionnaires/create', [SuperAdminController::class, 'createGestionnaire'])->name('create-gestionnaire');
    Route::post('/gestionnaires', [SuperAdminController::class, 'storeGestionnaire'])->name('store-gestionnaire');
    Route::delete('/gestionnaires/{id}', [SuperAdminController::class, 'removeGestionnaire'])->name('remove-gestionnaire');

    Route::get('/categories', [SuperAdminController::class, 'manageCategories'])->name('manage-categories');
    Route::get('/categories/create', [SuperAdminController::class, 'createCategory'])->name('create-category');
    Route::post('/categories', [SuperAdminController::class, 'storeCategory'])->name('store-category');
    Route::get('/categories/{id}/edit', [SuperAdminController::class, 'editCategory'])->name('edit-category');
    Route::put('/categories/{id}', [SuperAdminController::class, 'updateCategory'])->name('update-category');
    Route::delete('/categories/{id}', [SuperAdminController::class, 'deleteCategory'])->name('delete-category');

    Route::get('/device-types', [SuperAdminController::class, 'manageDeviceTypes'])->name('manage-device-types');
    Route::get('/device-types/create', [SuperAdminController::class, 'createDeviceType'])->name('create-device-type');
    Route::post('/device-types', [SuperAdminController::class, 'storeDeviceType'])->name('store-device-type');
    Route::get('/device-types/{id}/edit', [SuperAdminController::class, 'editDeviceType'])->name('edit-device-type');
    Route::put('/device-types/{id}', [SuperAdminController::class, 'updateDeviceType'])->name('update-device-type');
    Route::delete('/device-types/{id}', [SuperAdminController::class, 'deleteDeviceType'])->name('delete-device-type');

    Route::get('/transfer-workers', [SuperAdminController::class, 'manageTransferWorkers'])->name('manage-transfer-workers');
    Route::post('/users/{userId}/transfer', [SuperAdminController::class, 'transferUser'])->name('transfer-user');
    Route::delete('/users/{userId}', [SuperAdminController::class, 'deleteUser'])->name('delete-user');

    // Complaints System
    Route::prefix('complaints')->name('complaints.')->group(function () {
        Route::get('/', [SuperAdminController::class, 'complaints'])->name('index');
        Route::get('/create', [SuperAdminController::class, 'createComplaint'])->name('create');
        Route::post('/store', [SuperAdminController::class, 'storeComplaint'])->name('store');
        Route::post('/{id}/respond', [SuperAdminController::class, 'respondToComplaint'])->name('respond');
        Route::post('/{id}/resolve', [SuperAdminController::class, 'resolveComplaint'])->name('resolve');
        Route::delete('/{id}', [SuperAdminController::class, 'deleteComplaint'])->name('delete');
        Route::get('/{id}', [SuperAdminController::class, 'showComplaint'])->name('show');
    });

    // Reports System
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [SuperAdminController::class, 'reports'])->name('index');
        Route::get('/create', [SuperAdminController::class, 'createReport'])->name('create');
        Route::post('/store', [SuperAdminController::class, 'storeReport'])->name('store');
        Route::get('/{id}', [SuperAdminController::class, 'showReport'])->name('show');
        Route::post('/{id}/respond', [SuperAdminController::class, 'respondToReport'])->name('respond');
        Route::post('/{id}/resolve', [SuperAdminController::class, 'resolveReport'])->name('resolve');
    });

    // Law Management
    Route::get('/law', [SuperAdminController::class, 'manageLaw'])->name('manage-law');
    Route::post('/law', [SuperAdminController::class, 'updateLaw'])->name('update-law');

    // Footer Settings
    Route::get('/footer-settings', [FooterSettingsController::class, 'index'])->name('footer-settings');
    Route::post('/footer-settings', [FooterSettingsController::class, 'update'])->name('footer-settings.update');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllRead'])->name('notifications.mark-all-read');
    Route::post('/notifications/{id}/mark-read', [NotificationController::class, 'markRead'])->name('notifications.mark-read');
    Route::get('/notifications/{id}/view', [NotificationController::class, 'viewAndMarkRead'])->name('notifications.view');
    Route::delete('/notifications/{id}', [NotificationController::class, 'delete'])->name('notifications.delete');
    
    Route::get('/profile', [AdminController::class, 'profile'])->name('profile');
    Route::post('/profile', [AdminController::class, 'updateProfile'])->name('profile.update');

    Route::get('/users', [AdminController::class, 'manageUsers'])->name('manage-users');
    Route::get('/artists/{id}/view', [AdminController::class, 'viewArtist'])->name('view-artist');
    Route::post('/artists/{id}/approve', [AdminController::class, 'approveArtist'])->name('approve-artist');
    Route::post('/artists/{id}/reject', [AdminController::class, 'rejectArtist'])->name('reject-artist');

    // Reports and Complaints System
    Route::get('/reports-and-complaints', [AdminController::class, 'complaints'])->name('reports-and-complaints.index');
    
    Route::prefix('complaints')->name('complaints.')->group(function () {
        Route::get('/', [AdminController::class, 'complaints'])->name('index');
        Route::get('/inbox', [AdminController::class, 'complaintsInbox'])->name('inbox');
        Route::get('/sent', [AdminController::class, 'complaintsSent'])->name('sent');
        Route::get('/create', [AdminController::class, 'createComplaint'])->name('create');
        Route::post('/store', [AdminController::class, 'storeComplaint'])->name('store');
        Route::post('/{id}/forward', [AdminController::class, 'forwardToGestionnaire'])->name('forward');
        Route::post('/{id}/respond', [AdminController::class, 'respondToComplaint'])->name('respond');
        Route::post('/{id}/resolve', [AdminController::class, 'resolveComplaint'])->name('resolve');
        Route::delete('/{id}', [AdminController::class, 'deleteComplaint'])->name('delete');
        Route::get('/{id}', [AdminController::class, 'viewComplaint'])->name('show');
        Route::post('/{id}/assign', [AdminController::class, 'assignComplaint'])->name('assign');
        Route::post('/{id}/forward', [AdminController::class, 'forwardToGestionnaire'])->name('forward');
    });
    
    Route::get('/view-complaint/{id}', [AdminController::class, 'viewComplaint'])->name('view-complaint');

    // Reports System
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [AdminController::class, 'reports'])->name('index');
        Route::get('/inbox', [AdminController::class, 'reportsInbox'])->name('inbox');
        Route::get('/sent', [AdminController::class, 'reportsSent'])->name('sent');
        Route::get('/create', [AdminController::class, 'createReport'])->name('create');
        Route::post('/store', [AdminController::class, 'storeReport'])->name('store');
        Route::get('/{id}', [AdminController::class, 'showReport'])->name('show');
        Route::post('/{id}/respond', [AdminController::class, 'respondToReport'])->name('respond');
        Route::post('/{id}/resolve', [AdminController::class, 'resolveReport'])->name('resolve');
    });

    Route::get('/pvs', [AdminController::class, 'managePVs'])->name('manage-pvs');
    Route::get('/pvs/{pv}', [AdminController::class, 'viewPV'])->name('view-pv');
    Route::get('/financial-transactions', [AdminController::class, 'financialTransactions'])->name('financial-transactions');

    Route::get('/gestionnaires', [AdminController::class, 'manageGestionnaires'])->name('manage-gestionnaires');
    Route::get('/gestionnaires/create', [AdminController::class, 'createGestionnaire'])->name('create-gestionnaire');
    Route::post('/gestionnaires', [AdminController::class, 'storeGestionnaire'])->name('store-gestionnaire');
    Route::delete('/gestionnaires/{id}', [AdminController::class, 'removeGestionnaire'])->name('remove-gestionnaire');

    Route::get('/agents', [AdminController::class, 'manageAgents'])->name('manage-agents');
    Route::get('/agents/create', [AdminController::class, 'createAgent'])->name('create-agent');
    Route::post('/agents', [AdminController::class, 'storeAgent'])->name('store-agent');
    Route::delete('/agents/{id}', [AdminController::class, 'removeAgent'])->name('remove-agent');
    Route::get('/missions', [AdminController::class, 'manageMissions'])->name('manage-missions');
    Route::get('/missions/create', [AdminController::class, 'createMission'])->name('create-mission');
    Route::post('/missions', [AdminController::class, 'storeMission'])->name('store-mission');
});

Route::middleware(['auth', 'role:artist'])->prefix('artist')->name('artist.')->group(function () {
    Route::get('/dashboard', [ArtistController::class, 'dashboard'])->name('dashboard');
    
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllRead'])->name('notifications.mark-all-read');
    Route::post('/notifications/{id}/mark-read', [NotificationController::class, 'markRead'])->name('notifications.mark-read');
    Route::get('/notifications/{id}/view', [NotificationController::class, 'viewAndMarkRead'])->name('notifications.view');
    Route::delete('/notifications/{id}', [NotificationController::class, 'delete'])->name('notifications.delete');

    Route::get('/profile', [ArtistController::class, 'profile'])->name('profile');
    Route::post('/profile', [ArtistController::class, 'updateProfile'])->name('update-profile');

    Route::get('/wallet', [ArtistController::class, 'wallet'])->name('wallet');
    Route::post('/wallet/recharge', [ArtistController::class, 'rechargeWallet'])->name('wallet.recharge');

    Route::get('/artworks', [ArtistController::class, 'artworks'])->name('artworks');
    Route::get('/artworks/live', [ArtistController::class, 'liveArtworks'])->name('artworks.live');
    Route::get('/artworks/pending', [ArtistController::class, 'pendingArtworks'])->name('artworks.pending');
    Route::get('/artworks/pending-payment', [ArtistController::class, 'pendingPaymentArtworks'])->name('artworks.pending-payment');
    Route::get('/artworks/rejected', [ArtistController::class, 'rejectedArtworks'])->name('artworks.rejected');
    Route::get('/artworks/create', [ArtistController::class, 'createArtwork'])->name('create-artwork');
    Route::post('/artworks', [ArtistController::class, 'storeArtwork'])->name('store-artwork');
    Route::get('/artworks/{id}', [ArtistController::class, 'showArtwork'])->name('show-artwork');
    Route::get('/artworks/{id}/edit', [ArtistController::class, 'editArtwork'])->name('edit-artwork');
    Route::put('/artworks/{id}', [ArtistController::class, 'updateArtwork'])->name('update-artwork');
    Route::delete('/artworks/{id}', [ArtistController::class, 'deleteArtwork'])->name('delete-artwork');
    Route::post('/artworks/{id}/pay-tax', [ArtistController::class, 'payPlatformTax'])->name('pay-platform-tax');

    // Complaints System (Artists can only send complaints)
    Route::prefix('complaints')->name('complaints.')->group(function () {
        Route::get('/', [ArtistController::class, 'complaints'])->name('index');
        Route::get('/inbox', [ArtistController::class, 'complaintsInbox'])->name('inbox');
        Route::get('/sent', [ArtistController::class, 'complaintsSent'])->name('sent');
        Route::get('/create', [ArtistController::class, 'createComplaint'])->name('create');
        Route::post('/store', [ArtistController::class, 'storeComplaint'])->name('store');
        Route::delete('/{id}', [ArtistController::class, 'deleteComplaint'])->name('delete');
        Route::get('/{id}', [ArtistController::class, 'showComplaint'])->name('show');
    });

    Route::get('/law', [ArtistController::class, 'viewLaw'])->name('law');

    // API endpoints for complaints
    Route::get('/api/agency/{agency}/officials', [ArtistController::class, 'getAgencyOfficials'])->name('api.agency.officials');
});

Route::middleware(['auth', 'role:agent'])->prefix('agent')->name('agent.')->group(function () {
    Route::get('/dashboard', [AgentController::class, 'dashboard'])->name('dashboard');
    
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllRead'])->name('notifications.mark-all-read');
    Route::post('/notifications/{id}/mark-read', [NotificationController::class, 'markRead'])->name('notifications.mark-read');
    Route::get('/notifications/{id}/view', [NotificationController::class, 'viewAndMarkRead'])->name('notifications.view');
    Route::delete('/notifications/{id}', [NotificationController::class, 'delete'])->name('notifications.delete');
    
    Route::get('/profile', [AgentController::class, 'profile'])->name('profile');
    Route::post('/profile', [AgentController::class, 'updateProfile'])->name('profile.update');
    
    Route::get('/law', [AgentController::class, 'viewLaw'])->name('law');

    // Complaints System (Agents can only send complaints)
    Route::prefix('complaints')->name('complaints.')->group(function () {
        Route::get('/', [AgentController::class, 'complaints'])->name('index');
        Route::get('/inbox', [AgentController::class, 'complaintsInbox'])->name('inbox');
        Route::get('/sent', [AgentController::class, 'complaintsSent'])->name('sent');
        Route::get('/create', [AgentController::class, 'createComplaint'])->name('create');
        Route::post('/store', [AgentController::class, 'storeComplaint'])->name('store');
        Route::post('/{id}/respond', [AgentController::class, 'respondToComplaint'])->name('respond');
        Route::delete('/{id}', [AgentController::class, 'deleteComplaint'])->name('delete');
        Route::get('/{id}', [AgentController::class, 'showComplaint'])->name('show');
    });

    Route::get('/pvs', [AgentController::class, 'viewPVs'])->name('pvs.index');
    Route::get('/pvs/create', [AgentController::class, 'createPV'])->name('pvs.create');
    Route::post('/pvs', [AgentController::class, 'storePV'])->name('pvs.store');
    Route::get('/pvs/{pv}', [AgentController::class, 'showPV'])->name('pvs.show');
    Route::post('/pvs/{pv}/close', [AgentController::class, 'closePV'])->name('pvs.close');
    Route::post('/pvs/{pv}/payment', [AgentController::class, 'updatePayment'])->name('pvs.payment');
    Route::post('/pvs/{pv}/payment-proof', [AgentController::class, 'uploadPaymentProof'])->name('pvs.payment-proof');
    Route::post('/pvs/{pv}/photos', [AgentController::class, 'uploadPhotos'])->name('pvs.photos');
    Route::get('/pvs/{pv}/print', [AgentController::class, 'printPV'])->name('pvs.print');

    Route::get('/pvs/{pv}/devices/create', [AgentController::class, 'addDevice'])->name('pvs.devices.create');
    Route::post('/pvs/{pv}/devices', [AgentController::class, 'storeDevice'])->name('pvs.devices.store');
    Route::delete('/pvs/{pv}/devices/{device}', [AgentController::class, 'removeDevice'])->name('pvs.devices.destroy');

    Route::get('/pvs/{pv}/artworks/create', [AgentController::class, 'addArtwork'])->name('pvs.artworks.create');
    Route::post('/pvs/{pv}/artworks', [AgentController::class, 'storeArtwork'])->name('pvs.artworks.store');
    Route::delete('/pvs/{pv}/artworks/{pvArtwork}', [AgentController::class, 'removeArtwork'])->name('pvs.artworks.destroy');
    Route::post('/pvs/{pv}/artworks/artists', [AgentController::class, 'getArtistsByAgency'])->name('pvs.artworks.artists');
    Route::post('/pvs/{pv}/artworks/list', [AgentController::class, 'getArtworksByArtist'])->name('pvs.artworks.list');

    Route::get('/missions', [AgentMissionController::class, 'index'])->name('missions.index');
    Route::get('/missions/{mission}', [AgentMissionController::class, 'show'])->name('missions.show');
    Route::post('/missions/{mission}/status', [AgentMissionController::class, 'updateStatus'])->name('missions.update-status');
    Route::get('/missions/{mission}/print', [AgentController::class, 'printMission'])->name('missions.print');
});

Route::middleware(['auth', 'role:gestionnaire'])->prefix('gestionnaire')->name('gestionnaire.')->group(function () {
    Route::get('/dashboard', [GestionnaireController::class, 'dashboard'])->name('dashboard');
    
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllRead'])->name('notifications.mark-all-read');
    Route::post('/notifications/{id}/mark-read', [NotificationController::class, 'markRead'])->name('notifications.mark-read');
    Route::get('/notifications/{id}/view', [NotificationController::class, 'viewAndMarkRead'])->name('notifications.view');
    Route::delete('/notifications/{id}', [NotificationController::class, 'delete'])->name('notifications.delete');
    
    Route::get('/profile', [GestionnaireController::class, 'profile'])->name('profile');
    Route::post('/profile', [GestionnaireController::class, 'updateProfile'])->name('profile.update');

    Route::get('/artworks', [GestionnaireController::class, 'artworks'])->name('artworks');
    Route::get('/artworks/{id}', [GestionnaireController::class, 'showArtwork'])->name('show-artwork');
    Route::get('/artworks/{id}/download', [GestionnaireController::class, 'downloadArtwork'])->name('download-artwork');
    Route::post('/artworks/{id}/approve', [GestionnaireController::class, 'approveArtwork'])->name('approve-artwork');
    Route::post('/artworks/{id}/reject', [GestionnaireController::class, 'rejectArtwork'])->name('reject-artwork');

    Route::get('/agencies', [GestionnaireController::class, 'agencies'])->name('agencies');

    Route::get('/missions', [GestionnaireMissionController::class, 'index'])->name('missions.index');
    Route::get('/missions/create', [GestionnaireMissionController::class, 'create'])->name('missions.create');
    Route::post('/missions', [GestionnaireMissionController::class, 'store'])->name('missions.store');
    Route::get('/missions/{mission}', [GestionnaireMissionController::class, 'show'])->name('missions.show');
    Route::post('/missions/{mission}/status', [GestionnaireMissionController::class, 'updateStatus'])->name('missions.update-status');
    Route::post('/missions/{mission}/assign-agent', [GestionnaireMissionController::class, 'assignAgent'])->name('missions.assign-agent');
    Route::get('/missions/{mission}/print', [GestionnaireMissionController::class, 'printMission'])->name('missions.print');

    Route::get('/wallet', [GestionnaireWalletController::class, 'index'])->name('wallet.index');
    Route::post('/wallet/pv/{pv}/confirm', [GestionnaireWalletController::class, 'confirmPayment'])->name('wallet.confirm-payment');
    Route::post('/wallet/pv/{pv}/release', [GestionnaireWalletController::class, 'releasePayment'])->name('wallet.release-payment');

    Route::get('/wallet-recharge', [WalletRechargeController::class, 'index'])->name('wallet-recharge.index');
    Route::get('/wallet-recharge/{id}', [WalletRechargeController::class, 'show'])->name('wallet-recharge.show');
    Route::post('/wallet-recharge/{id}/approve', [WalletRechargeController::class, 'approve'])->name('wallet-recharge.approve');
    Route::post('/wallet-recharge/{id}/reject', [WalletRechargeController::class, 'reject'])->name('wallet-recharge.reject');

    Route::get('/pvs', [GestionnaireController::class, 'pvs'])->name('pvs.index');
    Route::get('/pvs/{pv}', [GestionnaireController::class, 'showPv'])->name('pvs.show');
    Route::post('/pvs/{pv}/finalize', [GestionnaireController::class, 'finalizePV'])->name('pvs.finalize');

    Route::get('/agents', [GestionnaireController::class, 'agents'])->name('agents.index');
    Route::get('/agents/create', [GestionnaireController::class, 'createAgent'])->name('agents.create');
    Route::post('/agents', [GestionnaireController::class, 'storeAgent'])->name('agents.store');

    // Reports and Complaints System
    Route::get('/reports-and-complaints', [GestionnaireController::class, 'reportsAndComplaints'])->name('reports-and-complaints.index');
    
    // Complaints System (Legacy - keeping for compatibility)
    Route::prefix('complaints')->name('complaints.')->group(function () {
        Route::get('/', [GestionnaireController::class, 'complaints'])->name('index');
        Route::get('/inbox', [GestionnaireController::class, 'complaintsInbox'])->name('inbox');
        Route::get('/sent', [GestionnaireController::class, 'complaintsSent'])->name('sent');
        Route::get('/create', [GestionnaireController::class, 'createComplaint'])->name('create');
        Route::post('/store', [GestionnaireController::class, 'storeComplaint'])->name('store');
        Route::post('/{id}/take', [GestionnaireController::class, 'assignToSelf'])->name('take');
        Route::post('/{id}/status', [GestionnaireController::class, 'updateComplaintStatus'])->name('status');
        Route::post('/{id}/respond', [GestionnaireController::class, 'respondToComplaint'])->name('respond');
        Route::delete('/{id}', [GestionnaireController::class, 'deleteComplaint'])->name('delete');
        Route::get('/{id}', [GestionnaireController::class, 'showComplaint'])->name('show');
    });

    // Reports System
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [GestionnaireController::class, 'reports'])->name('index');
        Route::get('/inbox', [GestionnaireController::class, 'reportsInbox'])->name('inbox');
        Route::get('/sent', [GestionnaireController::class, 'reportsSent'])->name('sent');
        Route::get('/create', [GestionnaireController::class, 'createReport'])->name('create');
        Route::post('/store', [GestionnaireController::class, 'storeReport'])->name('store');
        Route::get('/{id}', [GestionnaireController::class, 'showReport'])->name('show');
        Route::post('/{id}/respond', [GestionnaireController::class, 'respondToReport'])->name('respond');
    });
});

require __DIR__ . '/auth.php';
