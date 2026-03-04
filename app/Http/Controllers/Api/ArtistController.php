<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Artist;
use App\Models\Artwork;
use App\Models\Category;
use App\Models\Complain;
use App\Models\Notification;
use App\Models\Transaction;
use App\Models\Wallet;
use App\Models\WalletRechargeRequest;
use App\Models\PV;
use App\Models\Law;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ArtistController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    // Profile Management
    public function getProfile()
    {
        try {
            $user = Auth::user();
            $artist = $user->artist;

            if (!$artist) {
                return response()->json([
                    'success' => false,
                    'message' => 'Artist profile not found',
                ], 404);
            }

            $artist->load('agency', 'user');
            
            // Build profile photo URL - ensure it's always correct for API
            $profilePhotoUrl = null;
            if ($user->profile_photo_path) {
                // Ensure path doesn't have leading slash
                $cleanPath = ltrim($user->profile_photo_path, '/');
                $profilePhotoUrl = '/api/media/' . $cleanPath;
                
                // Verify file exists
                $fullPath = storage_path('app/public/' . $cleanPath);
                if (!file_exists($fullPath)) {
                    \Log::warning('Profile Photo File Not Found in getProfile', [
                        'profile_photo_path' => $user->profile_photo_path,
                        'clean_path' => $cleanPath,
                        'full_path' => $fullPath,
                        'file_exists' => false,
                    ]);
                    // Don't set URL if file doesn't exist
                    $profilePhotoUrl = null;
                } else {
                    \Log::info('Profile Photo File Found', [
                        'profile_photo_path' => $user->profile_photo_path,
                        'clean_path' => $cleanPath,
                        'full_path' => $fullPath,
                        'file_exists' => true,
                        'file_size' => filesize($fullPath),
                    ]);
                }
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'phone' => $user->phone,
                        'profile_photo_url' => $profilePhotoUrl,
                        'profile_photo_path' => $user->profile_photo_path,
                    ],
                    'artist' => [
                        'id' => $artist->id,
                        'stage_name' => $artist->stage_name,
                        'address' => $artist->address,
                        'birth_date' => $artist->birth_date,
                        'birth_place' => $artist->birth_place,
                        'status' => $artist->status,
                        'bank_account_number' => $artist->bank_account_number,
                        'full_name_on_account' => $artist->full_name_on_account,
                        'agency_name' => $artist->agency ? $artist->agency->agency_name : 'Not Assigned',
                        'wilaya' => $artist->agency ? $artist->agency->wilaya : 'Not Assigned',
                    ],
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load profile: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $artist = $user->artist;

        if (!$artist) {
            return response()->json([
                'success' => false,
                'message' => 'Artist profile not found',
            ], 404);
        }

        // Exact same validation as web controller
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'stage_name' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'birth_place' => 'nullable|string|max:255',
            'birth_date' => 'nullable|date',
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|min:8|confirmed',
            'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png',
            'bank_account_number' => 'nullable|string|max:255',
            'full_name_on_account' => 'nullable|string|max:255',
            'bank_account_proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        // Exact same update logic as web controller
        $artist->update([
            'stage_name' => $request->stage_name,
            'address' => $request->address,
            'birth_place' => $request->birth_place,
            'birth_date' => $request->birth_date,
            'bank_account_number' => $request->filled('bank_account_number') ? $request->bank_account_number : $artist->bank_account_number,
            'full_name_on_account' => $request->filled('full_name_on_account') ? $request->full_name_on_account : $artist->full_name_on_account,
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->phone) {
            $user->phone = $request->phone;
        }
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }
        if ($request->hasFile('profile_photo')) {
            // Delete old photo if exists
            if ($user->profile_photo_path) {
                $oldPath = storage_path('app/public/' . ltrim($user->profile_photo_path, '/'));
                if (file_exists($oldPath)) {
                    Storage::disk('public')->delete($user->profile_photo_path);
                    \Log::info('Old Profile Photo Deleted', [
                        'old_path' => $user->profile_photo_path,
                        'old_full_path' => $oldPath,
                    ]);
                }
            }
            
            // Store new photo - always use 'profile_photos' folder
            $storedPath = $request->file('profile_photo')->store('profile_photos', 'public');
            $user->profile_photo_path = $storedPath;
            
            // Verify file was saved correctly
            $fullPath = storage_path('app/public/' . $storedPath);
            $fileExists = file_exists($fullPath);
            $fileSize = $fileExists ? filesize($fullPath) : 0;
            
            // Debug logging
            \Log::info('Profile Photo Uploaded', [
                'stored_path' => $storedPath,
                'full_storage_path' => $fullPath,
                'file_exists' => $fileExists,
                'file_size' => $fileSize,
                'mime_type' => $fileExists ? mime_content_type($fullPath) : null,
            ]);
            
            if (!$fileExists) {
                \Log::error('Profile Photo Upload Failed - File Not Found After Storage', [
                    'stored_path' => $storedPath,
                    'full_storage_path' => $fullPath,
                ]);
            }
        }

        // Handle bank account proof upload
        if ($request->hasFile('bank_account_proof')) {
            if ($artist->bank_account_proof) {
                Storage::disk('public')->delete($artist->bank_account_proof);
            }
            $artist->bank_account_proof = $request->file('bank_account_proof')->store('bank_account_proofs', 'public');
            $artist->save();
        }

        $user->save();

        // Build profile photo URL - ensure it's always correct for API
        $profilePhotoUrl = null;
        if ($user->profile_photo_path) {
            // Ensure path doesn't have leading slash
            $cleanPath = ltrim($user->profile_photo_path, '/');
            $profilePhotoUrl = '/api/media/' . $cleanPath;
            
            // Verify file exists
            $fullPath = storage_path('app/public/' . $cleanPath);
            if (!file_exists($fullPath)) {
                \Log::warning('Profile Photo File Not Found', [
                    'profile_photo_path' => $user->profile_photo_path,
                    'clean_path' => $cleanPath,
                    'full_path' => $fullPath,
                    'file_exists' => false,
                ]);
                // Don't set URL if file doesn't exist
                $profilePhotoUrl = null;
            }
        }
        
        // Debug logging
        \Log::info('Profile Photo URL Generated', [
            'profile_photo_path' => $user->profile_photo_path,
            'profile_photo_url' => $profilePhotoUrl,
            'file_exists' => $user->profile_photo_path ? file_exists(storage_path('app/public/' . ltrim($user->profile_photo_path, '/'))) : false,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'profile_photo_url' => $profilePhotoUrl,
                    'profile_photo_path' => $user->profile_photo_path,
                ],
                'artist' => [
                    'id' => $artist->id,
                    'stage_name' => $artist->stage_name,
                    'address' => $artist->address,
                    'birth_date' => $artist->birth_date,
                    'birth_place' => $artist->birth_place,
                    'bank_account_number' => $artist->bank_account_number,
                    'full_name_on_account' => $artist->full_name_on_account,
                    'agency_name' => $artist->agency ? $artist->agency->agency_name : 'Not Assigned',
                    'wilaya' => $artist->agency ? $artist->agency->wilaya : 'Not Assigned',
                ],
            ],
        ]);
    }

    // Artworks Management
    public function getArtworks(Request $request)
    {
        try {
            $user = Auth::user();
            $artist = $user->artist;

            if (!$artist) {
                return response()->json([
                    'message' => 'Artist profile not found',
                ], 404);
            }

            $query = Artwork::where('artist_id', $artist->id)
                ->with('category')
                ->orderBy('created_at', 'desc');

            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            $artworks = $query->get();

            $artworksData = $artworks->map(function ($artwork) {
                // Return public storage path - directly accessible without auth for better performance
                $filePath = $artwork->file_path ? '/storage/' . ltrim($artwork->file_path, '/') : null;
                $fileName = $artwork->file_path ? basename($artwork->file_path) : null;

                return [
                    'id' => $artwork->id,
                    'title' => $artwork->title,
                    'description' => $artwork->description,
                    'status' => $artwork->status,
                    'rejection_reason' => $artwork->rejection_reason,
                    'platform_tax_status' => $artwork->platform_tax_status,
                    'platform_tax_amount' => $artwork->platform_tax_amount,
                    'file_path' => $filePath,
                    'file_name' => $fileName,
                    'category' => $artwork->category ? [
                        'id' => $artwork->category->id,
                        'name' => $artwork->category->name,
                    ] : null,
                    'created_at' => $artwork->created_at,
                    'updated_at' => $artwork->updated_at,
                ];
            });

            $response = [
                'artworks' => $artworksData,
            ];

            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to load artworks: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function getArtwork($id)
    {
        $artist = Auth::user()->artist;
        $artwork = Artwork::where('artist_id', $artist->id)
            ->with('category')
            ->findOrFail($id);

        // Return public storage path - directly accessible without auth for better performance
        $filePath = $artwork->file_path ? '/storage/' . ltrim($artwork->file_path, '/') : null;
        $fileName = $artwork->file_path ? basename($artwork->file_path) : null;
        
        return response()->json([
            'artwork' => [
                'id' => $artwork->id,
                'title' => $artwork->title,
                'description' => $artwork->description,
                'status' => $artwork->status,
                'rejection_reason' => $artwork->rejection_reason,
                'platform_tax_status' => $artwork->platform_tax_status,
                'platform_tax_amount' => $artwork->platform_tax_amount,
                'file_path' => $filePath,
                'file_name' => $fileName,
                'category' => $artwork->category ? [
                    'id' => $artwork->category->id,
                    'name' => $artwork->category->name,
                ] : null,
                'created_at' => $artwork->created_at,
                'updated_at' => $artwork->updated_at,
            ],
        ]);
    }

    public function createArtwork(Request $request)
    {
        $artist = Auth::user()->artist;

        if (!$artist) {
            return response()->json([
                'message' => 'Artist profile not found',
            ], 404);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'file' => 'nullable|file|mimes:jpg,jpeg,png,pdf,mp3,mp4',
        ]);

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('artworks', 'public');
        }

        $artwork = Artwork::create([
            'artist_id' => $artist->id,
            'category_id' => $request->category_id,
            'title' => $request->title,
            'description' => $request->description,
            'file_path' => $filePath,
            'status' => 'PENDING',
            'platform_tax_status' => 'PENDING',
            'platform_tax_amount' => Artwork::calculatePlatformTax($request->category_id),
        ]);

        NotificationService::sendToAgencyRole(
            'gestionnaire',
            $artist->agency_id,
            'New artwork submitted',
            Auth::user()->name . ' uploaded "' . $request->title . '" for review.',
            [
                'type' => 'artwork_submitted',
                'artwork_id' => $artwork->id,
            ]
        );

        return response()->json([
            'message' => 'Artwork created successfully and pending approval',
            'artwork' => [
                'id' => $artwork->id,
                'title' => $artwork->title,
                'status' => $artwork->status,
            ],
        ], 201);
    }

    public function updateArtwork(Request $request, $id)
    {
        $artist = Auth::user()->artist;
        $artwork = Artwork::where('artist_id', $artist->id)->findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'file' => 'nullable|file|mimes:jpg,jpeg,png,pdf,mp3,mp4',
        ]);

        if ($request->hasFile('file')) {
            if ($artwork->file_path) {
                Storage::disk('public')->delete($artwork->file_path);
            }
            $artwork->file_path = $request->file('file')->store('artworks', 'public');
        }

        $artwork->update([
            'title' => $request->title,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'status' => 'PENDING',
        ]);

        return response()->json([
            'message' => 'Artwork updated successfully',
            'artwork' => [
                'id' => $artwork->id,
                'title' => $artwork->title,
                'status' => $artwork->status,
            ],
        ]);
    }

    public function deleteArtwork($id)
    {
        $artist = Auth::user()->artist;
        $artwork = Artwork::where('artist_id', $artist->id)->findOrFail($id);

        if ($artwork->file_path) {
            Storage::disk('public')->delete($artwork->file_path);
        }

        $artwork->delete();

        return response()->json([
            'message' => 'Artwork deleted successfully',
        ]);
    }

    public function getCategories()
    {
        $categories = Category::all();

        return response()->json([
            'categories' => $categories->map(function ($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'description' => $category->description,
                ];
            }),
        ]);
    }

    public function payPlatformTax($id)
    {
        $artist = Auth::user()->artist;

        if (!$artist) {
            return response()->json([
                'message' => 'Artist profile not found',
            ], 404);
        }

        $artwork = Artwork::where('artist_id', $artist->id)->findOrFail($id);

        if ($artwork->platform_tax_status === 'PAID') {
            return response()->json([
                'message' => 'Platform tax already paid for this artwork.',
            ], 400);
        }

        if ($artwork->status !== 'APPROVED') {
            return response()->json([
                'message' => 'Artwork must be approved before paying tax.',
            ], 400);
        }

        $wallet = Wallet::firstOrCreate(['artist_id' => $artist->id], ['balance' => 0]);
        $taxAmount = $artwork->platform_tax_amount ?? config('artrights.platform_tax_amount', 500);

        if ($wallet->balance < $taxAmount) {
            return response()->json([
                'message' => 'Insufficient wallet balance. Please recharge your wallet.',
            ], 400);
        }

        DB::transaction(function () use ($wallet, $artwork, $taxAmount, $artist) {
            $wallet->balance -= $taxAmount;
            $wallet->last_transaction = now();
            $wallet->save();

            $agencyWallet = \App\Models\AgencyWallet::firstOrCreate(
                ['agency_id' => $artist->agency_id],
                ['balance' => 0]
            );
            $agencyWallet->balance += $taxAmount;
            $agencyWallet->last_transaction = now();
            $agencyWallet->save();

            Transaction::create([
                'artist_id' => $artist->id,
                'artwork_id' => $artwork->id,
                'type' => 'PLATFORM_TAX',
                'amount' => -$taxAmount,
                'payment_method' => 'WALLET_RECHARGE',
                'payment_status' => 'VALIDATED',
                'description' => 'Platform tax payment for artwork: ' . $artwork->title,
            ]);

            \App\Models\AgencyWalletTransaction::create([
                'agency_wallet_id' => $agencyWallet->id,
                'direction' => 'IN',
                'amount' => $taxAmount,
                'description' => 'Platform tax payment from artist: ' . Auth::user()->name . ' for artwork: ' . $artwork->title,
            ]);

            $artwork->platform_tax_status = 'PAID';
            $artwork->platform_tax_paid_at = now();
            $artwork->save();
        });

        return response()->json([
            'message' => 'Platform tax paid successfully. Your artwork is now active!',
        ]);
    }

    // Wallet Management
    public function getWallet()
    {
        try {
            $user = Auth::user();
            $artist = $user->artist;

            if (!$artist) {
                return response()->json([
                    'message' => 'Artist profile not found',
                ], 404);
            }

            $wallet = Wallet::firstOrCreate(['artist_id' => $artist->id], ['balance' => 0]);

            return response()->json([
                'wallet' => [
                    'id' => $wallet->id,
                    'balance' => $wallet->balance,
                    'last_transaction' => $wallet->last_transaction,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to load wallet: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function getTransactions()
    {
        $artist = Auth::user()->artist;

        if (!$artist) {
            return response()->json([
                'message' => 'Artist profile not found',
            ], 404);
        }

        $transactions = Transaction::where('artist_id', $artist->id)
            ->with(['pv', 'artwork'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'transactions' => $transactions->map(function ($transaction) {
                return [
                    'id' => $transaction->id,
                    'type' => $transaction->type,
                    'amount' => $transaction->amount,
                    'payment_method' => $transaction->payment_method,
                    'payment_status' => $transaction->payment_status,
                    'description' => $transaction->description,
                    'pv' => $transaction->pv ? [
                        'id' => $transaction->pv->id,
                        'shop_name' => $transaction->pv->shop_name,
                    ] : null,
                    'artwork' => $transaction->artwork ? [
                        'id' => $transaction->artwork->id,
                        'title' => $transaction->artwork->title,
                    ] : null,
                    'created_at' => $transaction->created_at,
                ];
            }),
        ]);
    }

    public function getPVs()
    {
        $artist = Auth::user()->artist;

        if (!$artist) {
            return response()->json([
                'message' => 'Artist profile not found',
            ], 404);
        }

        $pvs = PV::whereHas('artworkUsages.artwork', function ($query) use ($artist) {
            $query->where('artist_id', $artist->id);
        })
            ->with(['artworkUsages.artwork', 'agency', 'agent'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'pvs' => $pvs->map(function ($pv) use ($artist) {
                $artistArtworks = $pv->artworkUsages->filter(function ($usage) use ($artist) {
                    return $usage->artwork && $usage->artwork->artist_id === $artist->id;
                });

                $totalAmount = $artistArtworks->sum('fine_amount');

                return [
                    'id' => $pv->id,
                    'shop_name' => $pv->shop_name,
                    'shop_type' => $pv->shop_type,
                    'date_of_inspection' => $pv->date_of_inspection,
                    'status' => $pv->status,
                    'payment_status' => $pv->payment_status,
                    'total_amount' => $totalAmount,
                    'artworks_count' => $artistArtworks->count(),
                    'agency' => $pv->agency ? [
                        'id' => $pv->agency->id,
                        'agency_name' => $pv->agency->agency_name,
                    ] : null,
                    'created_at' => $pv->created_at,
                ];
            }),
        ]);
    }

    public function rechargeWallet(Request $request)
    {
        $artist = Auth::user()->artist;

        if (!$artist) {
            return response()->json([
                'message' => 'Artist profile not found',
            ], 404);
        }

        // Check if artist has bank account information
        if (!$artist->bank_account_number || !$artist->full_name_on_account) {
            return response()->json([
                'success' => false,
                'message' => 'You must add your bank account information before recharging your wallet. Please update your profile with your bank account number and full name on account.',
            ], 400);
        }

        $request->validate([
            'amount' => 'required|numeric|min:100',
            'payment_method' => 'required|in:CHEQUE,POSTAL_TRANSFER',
            'transaction_reference' => 'required_if:payment_method,POSTAL_TRANSFER|nullable|string|max:255',
            'payment_proof' => 'required|image|mimes:jpg,jpeg,png',
            'notes' => 'nullable|string|max:1000',
        ]);

        $proofPath = $request->file('payment_proof')->store('wallet_recharge_proofs', 'public');

        $rechargeRequest = WalletRechargeRequest::create([
            'artist_id' => $artist->id,
            'amount' => $request->amount,
            'payment_method' => $request->payment_method,
            'transaction_reference' => $request->transaction_reference ?? null,
            'payment_proof_path' => $proofPath,
            'notes' => $request->notes,
            'status' => 'PENDING',
        ]);

        if ($artist->agency_id) {
            NotificationService::sendToAgencyRole(
                'gestionnaire',
                $artist->agency_id,
                'New wallet recharge request',
                Auth::user()->name . ' submitted a ' . number_format($request->amount, 2, '.', ' ') . ' DZD recharge request. Please review and approve.',
                [
                    'type' => 'wallet_recharge_request',
                    'wallet_recharge_id' => $rechargeRequest->id,
                ]
            );
        }

        return response()->json([
            'message' => 'Recharge request submitted successfully. Your request has been sent and is pending approval.',
            'recharge_request' => [
                'id' => $rechargeRequest->id,
                'amount' => $rechargeRequest->amount,
                'status' => $rechargeRequest->status,
            ],
        ], 201);
    }

    public function getPendingRechargeRequests()
    {
        $artist = Auth::user()->artist;

        if (!$artist) {
            return response()->json([
                'message' => 'Artist profile not found',
            ], 404);
        }

        $requests = WalletRechargeRequest::where('artist_id', $artist->id)
            ->where('status', 'PENDING')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'requests' => $requests->map(function ($request) {
                return [
                    'id' => $request->id,
                    'amount' => $request->amount,
                    'payment_method' => $request->payment_method,
                    'status' => $request->status,
                    'created_at' => $request->created_at,
                ];
            }),
        ]);
    }

    // Complaints Management
    public function getComplaints(Request $request)
    {
        $artist = Auth::user()->artist;

        if (!$artist) {
            return response()->json([
                'message' => 'Artist profile not found',
            ], 404);
        }

        $query = Complain::with(['admin', 'gestionnaire', 'targetUser', 'sender', 'agency'])
            ->where('artist_id', $artist->id)
            ->where('type', Complain::TYPE_COMPLAINT);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $complaints = $query->orderBy('created_at', 'desc')->get();

        return response()->json([
            'complaints' => $complaints->map(function ($complaint) {
                // Build full URLs for images
                $images = [];
                if ($complaint->images && is_array($complaint->images)) {
                    foreach ($complaint->images as $image) {
                        if ($image) {
                            // Ensure path doesn't have leading slash
                            $cleanPath = ltrim($image, '/');
                            $images[] = '/api/media/' . $cleanPath;
                        }
                    }
                }

                return [
                    'id' => $complaint->id,
                    'subject' => $complaint->subject,
                    'message' => $complaint->message,
                    'status' => $complaint->status,
                    'target_role' => $complaint->target_role,
                    'images' => $images,
                    'location_link' => $complaint->location_link,
                    'admin_response' => $complaint->admin_response,
                    'gestionnaire_response' => $complaint->gestionnaire_response,
                    'created_at' => $complaint->created_at,
                    'updated_at' => $complaint->updated_at,
                ];
            }),
        ]);
    }

    public function getComplaint($id)
    {
        $artist = Auth::user()->artist;
        $complaint = Complain::with(['admin', 'gestionnaire', 'targetUser', 'sender'])
            ->where('artist_id', $artist->id)
            ->where('type', Complain::TYPE_COMPLAINT)
            ->findOrFail($id);

        // Build full URLs for images
        $images = [];
        if ($complaint->images && is_array($complaint->images)) {
            foreach ($complaint->images as $image) {
                if ($image) {
                    // Ensure path doesn't have leading slash
                    $cleanPath = ltrim($image, '/');
                    $images[] = '/api/media/' . $cleanPath;
                }
            }
        }

        // Build full URLs for admin response images
        $adminResponseImages = [];
        if ($complaint->admin_response_images && is_array($complaint->admin_response_images)) {
            foreach ($complaint->admin_response_images as $image) {
                if ($image) {
                    $cleanPath = ltrim($image, '/');
                    $adminResponseImages[] = '/api/media/' . $cleanPath;
                }
            }
        }

        // Build full URLs for gestionnaire response images
        $gestionnaireResponseImages = [];
        if ($complaint->gestionnaire_response_images && is_array($complaint->gestionnaire_response_images)) {
            foreach ($complaint->gestionnaire_response_images as $image) {
                if ($image) {
                    $cleanPath = ltrim($image, '/');
                    $gestionnaireResponseImages[] = '/api/media/' . $cleanPath;
                }
            }
        }

        return response()->json([
            'complaint' => [
                'id' => $complaint->id,
                'subject' => $complaint->subject,
                'message' => $complaint->message,
                'status' => $complaint->status,
                'target_role' => $complaint->target_role,
                'images' => $images,
                'location_link' => $complaint->location_link,
                'admin_response' => $complaint->admin_response,
                'admin_response_images' => $adminResponseImages,
                'gestionnaire_response' => $complaint->gestionnaire_response,
                'gestionnaire_response_images' => $gestionnaireResponseImages,
                'created_at' => $complaint->created_at,
                'updated_at' => $complaint->updated_at,
            ],
        ]);
    }

    public function createComplaint(Request $request)
    {
        $artist = Auth::user()->artist;

        if (!$artist || !$artist->agency_id) {
            return response()->json([
                'message' => 'You must be associated with an agency to submit complaints.',
            ], 400);
        }

        $data = $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'location_link' => 'nullable|url|max:255',
            'images.*' => 'nullable|image|mimes:jpg,jpeg,png',
            'target_role' => 'required|in:admin,gestionnaire',
        ], [
            'images.*.max' => 'Each image must not be larger than 10MB.',
            'images.*.image' => 'Each file must be an image.',
        ]);

        $images = [];
        if ($request->hasFile('images')) {
            $uploadedImages = $request->file('images');
            if (count($uploadedImages) > 5) {
                return response()->json([
                    'message' => 'You can upload maximum 5 images.',
                ], 400);
            }

            foreach ($uploadedImages as $image) {
                $images[] = $image->store('complaints', 'public');
            }
        }

        $targetRole = $data['target_role'];
        $complaintType = Complain::resolveType('artist', $targetRole);

        $targetUserId = null;
        if ($targetRole === 'admin') {
            $targetUserId = $artist->agency->admin_id;
        } elseif ($targetRole === 'gestionnaire') {
            $gestionnaire = $artist->agency->gestionnaires->first();
            $targetUserId = $gestionnaire ? $gestionnaire->id : null;
        }

        $complaint = Complain::create([
            'type' => Complain::TYPE_COMPLAINT,
            'complaint_type' => $complaintType,
            'artist_id' => $artist->id,
            'agency_id' => $artist->agency_id,
            'sender_user_id' => Auth::id(),
            'sender_role' => 'artist',
            'target_role' => $targetRole,
            'target_user_id' => $targetUserId,
            'gestionnaire_id' => $targetRole === 'gestionnaire' ? $targetUserId : null,
            'admin_id' => $targetRole === 'admin' ? $targetUserId : null,
            'subject' => $data['subject'],
            'message' => $data['message'],
            'location_link' => $data['location_link'] ?? null,
            'images' => !empty($images) ? $images : null,
            'status' => 'PENDING',
        ]);

        NotificationService::sendToAgencyRole(
            $targetRole,
            $artist->agency_id,
            'New complaint from ' . Auth::user()->name,
            'Subject: ' . $data['subject'],
            [
                'type' => 'complaint_created',
                'complaint_id' => $complaint->id,
            ]
        );

        return response()->json([
            'message' => 'Complaint submitted successfully',
            'complaint' => [
                'id' => $complaint->id,
                'subject' => $complaint->subject,
                'status' => $complaint->status,
            ],
        ], 201);
    }

    // Notifications Management
    public function getNotifications(Request $request)
    {
        $user = Auth::user();

        $query = Notification::where('user_id', $user->id)
            ->with('sender')
            ->orderByDesc('created_at');

        if ($request->has('unread_only') && $request->unread_only) {
            $query->where('is_read', false);
        }

        $notifications = $query->paginate(15);

        return response()->json([
            'notifications' => $notifications->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'type' => $notification->type,
                    'title' => $notification->title,
                    'message' => $notification->message,
                    'is_read' => $notification->is_read,
                    'data' => $notification->data,
                    'sender' => $notification->sender ? [
                        'id' => $notification->sender->id,
                        'name' => $notification->sender->name,
                    ] : null,
                    'created_at' => $notification->created_at,
                ];
            }),
            'unread_count' => Notification::where('user_id', $user->id)
                ->where('is_read', false)
                ->count(),
        ]);
    }

    public function markNotificationRead($id)
    {
        $user = Auth::user();

        $notification = Notification::where('user_id', $user->id)
            ->findOrFail($id);

        $notification->update([
            'is_read' => true,
        ]);

        return response()->json([
            'message' => 'Notification marked as read',
        ]);
    }

    public function markAllNotificationsRead()
    {
        $user = Auth::user();

        Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'updated_at' => now(),
            ]);

        return response()->json([
            'message' => 'All notifications marked as read',
        ]);
    }

    public function deleteNotification($id)
    {
        $user = Auth::user();

        $notification = Notification::where('user_id', $user->id)
            ->findOrFail($id);

        $notification->delete();

        return response()->json([
            'message' => 'Notification deleted successfully',
        ]);
    }

    public function getUnreadCount()
    {
        $user = Auth::user();

        $count = Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->count();

        return response()->json([
            'unread_count' => $count,
        ]);
    }


}

