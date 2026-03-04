<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Artist;
use App\Models\Agency;
use App\Models\User;
use App\Mail\User\RegistrationPendingEmail;
use App\Providers\RouteServiceProvider;
use App\Services\NotificationService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Carbon\Carbon;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
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
            'stage_name' => ['nullable', 'string', 'max:255', 'unique:artists,stage_name'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'phone' => ['required', 'string', 'max:255', 'unique:'.User::class],
            'birth_date' => [
                'required', 
                'date',
                'before:' . Carbon::now()->subYears(16)->format('Y-m-d')
            ],
            'birth_place' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'agency_id' => ['required', 'exists:agencies,id'],
            'identity_document' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'bank_account_number' => ['required', 'string', 'max:255'],
            'full_name_on_account' => ['required', 'string', 'max:255'],
            'bank_account_proof' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
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

        // Check if agency has an admin
        $agency = Agency::find($request->agency_id);
        if (!$agency || !$agency->admin_id) {
            return redirect()->back()
                ->withErrors(['agency_id' => 'Registration is currently unavailable for this agency as it does not have an assigned administrator. Please try again in a week or contact support.'])
                ->withInput();
        }

        $user = User::create([
            'name' => $request->first_name . ' ' . $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'agency_id' => $request->agency_id,
        ]);

        // Handle identity document file upload
        $identityDocumentPath = null;
        if ($request->hasFile('identity_document')) {
            $identityDocumentPath = $request->file('identity_document')->store('identity_documents', 'public');
        }

        // Handle bank account proof file upload
        $bankAccountProofPath = null;
        if ($request->hasFile('bank_account_proof')) {
            $bankAccountProofPath = $request->file('bank_account_proof')->store('bank_account_proofs', 'public');
        }

        // Create Artist with PENDING_VALIDATION status
        $artist = Artist::create([
            'user_id' => $user->id,
            'agency_id' => $request->agency_id,
            'stage_name' => $request->stage_name,
            'birth_date' => $request->birth_date,
            'birth_place' => $request->birth_place,
            'address' => $request->address,
            'identity_document' => $identityDocumentPath,
            'bank_account_number' => $request->bank_account_number,
            'full_name_on_account' => $request->full_name_on_account,
            'bank_account_proof' => $bankAccountProofPath,
            'status' => 'PENDING_VALIDATION',
        ]);

        // Assign artist role to user
        $user->assignRole('artist');

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

        // Send registration pending email to user
        Mail::to($user->email)->send(new RegistrationPendingEmail($user));

        event(new Registered($user));

        return redirect()->route('login')->with('status', '✅ Registration successful! Your account is pending admin approval. You will be able to access your account once an admin approves it.');
    }
}
