<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Agency;
use App\Models\Artist;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $user = Auth::user();

        if (!$user->hasAnyRole(['admin', 'artist', 'agent'])) {
            Auth::logout();
            throw ValidationException::withMessages([
                'email' => ['You are not authorized to access the mobile app.'],
            ]);
        }

        if ($user->hasRole('artist') && $user->artist && $user->artist->status !== 'APPROVED') {
            Auth::logout();
            throw ValidationException::withMessages([
                'email' => ['Your artist account is pending approval.'],
            ]);
        }

        $token = $user->createToken('mobile-app')->plainTextToken;

        $userRole = $user->roles->first()->name ?? null;
        $welcomeMessage = "Welcome " . $user->name . " (" . $userRole . ")";

        return response()->json([
            'message' => $welcomeMessage,
            'user' => $this->getUserData($user),
            'token' => $token,
        ]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'first_name' => [
                'required', 
                'string', 
                'max:255',
                'regex:/^[a-zA-Z\s]+$/',
                function ($attribute, $value, $fail) use ($request) {
                    // Check if first name is the same as last name
                    $lastName = $request->input('last_name');
                    if ($lastName && strtolower(trim($value)) === strtolower(trim($lastName))) {
                        $fail('The first name cannot be the same as the last name.');
                    }
                },
            ],
            'last_name' => [
                'required', 
                'string', 
                'max:255',
                'regex:/^[a-zA-Z\s]+$/',
            ],
            'stage_name' => 'nullable|string|max:255|unique:artists,stage_name',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|unique:users,phone',
            'birth_date' => [
                'required', 
                'date',
                'before:' . Carbon::now()->subYears(16)->format('Y-m-d')
            ],
            'birth_place' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'agency_id' => 'required|exists:agencies,id',
            'identity_document' => 'required|file|mimes:jpg,jpeg,png,gif,webp,bmp,heic,heif,tif,tiff,pdf,doc,docx|max:5120',
            'bank_account_number' => 'required|string|max:255',
            'full_name_on_account' => 'required|string|max:255',
            'bank_account_proof' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'password' => [
                'required', 
                'confirmed', 
                'min:8',
                function ($attribute, $value, $fail) {
                    // Must contain at least one letter
                    if (!preg_match('/[a-zA-Z]/', $value)) {
                        $fail('The password must contain at least one letter.');
                        return;
                    }
                    
                    // Must contain at least one number
                    if (!preg_match('/[0-9]/', $value)) {
                        $fail('The password must contain at least one number.');
                        return;
                    }
                    
                    // Must contain at least one uppercase letter
                    if (!preg_match('/[A-Z]/', $value)) {
                        $fail('The password must contain at least one uppercase letter.');
                        return;
                    }
                    
                    // Cannot be only numbers
                    if (preg_match('/^[0-9]+$/', $value)) {
                        $fail('The password cannot consist of numbers only.');
                        return;
                    }
                    
                    // Cannot be only letters
                    if (preg_match('/^[a-zA-Z]+$/', $value)) {
                        $fail('The password cannot consist of letters only.');
                        return;
                    }
                },
            ],
        ], [
            'birth_date.before' => 'You must be at least 16 years old to register.',
            'first_name.regex' => 'The first name must contain only Latin letters (a-z, A-Z) and spaces.',
            'last_name.regex' => 'The last name must contain only Latin letters (a-z, A-Z) and spaces.',
        ]);

        $user = User::create([
            'name' => $request->first_name . ' ' . $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'agency_id' => $request->agency_id,
        ]);

        $identityPath = $request->file('identity_document')->store('identity_documents', 'public');
        $bankAccountProofPath = $request->file('bank_account_proof')->store('bank_account_proofs', 'public');

        $artist = Artist::create([
            'user_id' => $user->id,
            'agency_id' => $request->agency_id,
            'stage_name' => $request->stage_name,
            'birth_date' => $request->birth_date,
            'birth_place' => $request->birth_place,
            'address' => $request->address,
            'identity_document' => $identityPath,
            'bank_account_number' => $request->bank_account_number,
            'full_name_on_account' => $request->full_name_on_account,
            'bank_account_proof' => $bankAccountProofPath,
            'status' => 'PENDING_VALIDATION',
        ]);

        $user->assignRole('artist');

        // Send notification to admins in the same agency
        $agency = Agency::find($request->agency_id);
        $agencyName = $agency?->agency_name ?? 'your wilaya';

        NotificationService::sendToAgencyRole(
            'admin',
            $request->agency_id,
            'New artist registration',
            "{$user->name} just registered in {$agencyName}. Review and approve their profile.",
            [
                'type' => 'artist_registration',
                'artist_id' => $artist->id,
                'user_id' => $user->id,
                'link' => route('admin.manage-users'),
            ]
        );

        return response()->json([
            'message' => 'Registration successful! Your account is pending admin approval.',
            'user' => $this->getUserData($user),
        ], 201);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        
        return response()->json([
            'message' => 'Logged out successfully',
        ]);
    }

    public function welcome(Request $request)
    {
        $user = $request->user();
        $userRole = $user->roles->first()->name ?? null;

        if (!$userRole) {
            return response()->json([
                'message' => 'User does not have a role assigned.',
            ], 403);
        }

        $welcomeMessage = "Welcome " . $user->name . " (" . $userRole . ")";

        return response()->json([
            'message' => $welcomeMessage,
            'name' => $user->name,
            'role' => $userRole,
        ]);
    }

    private function getUserData(User $user)
    {
        // Build API media URL directly for API responses
        // This ensures the URL is always correct for API requests
        $profilePhotoUrl = null;
        if ($user->profile_photo_path) {
            // Ensure path doesn't have leading slash
            $cleanPath = ltrim($user->profile_photo_path, '/');
            $profilePhotoUrl = '/api/media/' . $cleanPath;
            
            // Verify file exists
            $fullPath = storage_path('app/public/' . $cleanPath);
            if (!file_exists($fullPath)) {
                \Log::warning('Profile Photo File Not Found in getUserData', [
                    'profile_photo_path' => $user->profile_photo_path,
                    'clean_path' => $cleanPath,
                    'full_path' => $fullPath,
                    'file_exists' => false,
                ]);
                // Don't set URL if file doesn't exist
                $profilePhotoUrl = null;
            }
        }
        
        $data = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'profile_photo_url' => $profilePhotoUrl,
            'role' => $user->roles->first()->name ?? null,
            'agency_id' => $user->agency_id,
        ];

        if ($user->hasRole('artist') && $user->artist) {
            $data['artist'] = [
                'id' => $user->artist->id,
                'stage_name' => $user->artist->stage_name,
                'status' => $user->artist->status,
                'address' => $user->artist->address,
                'birth_date' => $user->artist->birth_date,
                'birth_place' => $user->artist->birth_place,
            ];
        }

        if ($user->hasRole('agent') && $user->agent) {
            $data['agent'] = [
                'id' => $user->agent->id,
                'badge_number' => $user->agent->badge_number,
            ];
        }

        return $data;
    }

    public function getAgencies()
    {
        $agencies = \App\Models\Agency::select('id', 'agency_name', 'wilaya')
            ->orderBy('wilaya')
            ->orderBy('agency_name')
            ->get();

        return response()->json($agencies);
    }
}
