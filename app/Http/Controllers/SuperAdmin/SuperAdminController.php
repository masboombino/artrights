<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Category;
use App\Models\Agency;
use App\Models\Transaction;
use App\Models\Artist;
use App\Models\PV;
use App\Models\Agent;
use App\Models\Mission;
use App\Models\AgencyWallet;
use App\Models\DeviceType;
use App\Models\Complain;
use App\Models\Law;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Services\NotificationService;
use App\Mail\User\AgencyTransferEmail;
use Illuminate\Support\Facades\Mail;

class SuperAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:super_admin');
    }

    public function dashboard()
    {
        $user = Auth::user();
        $agenciesCount = Agency::count();
        $categoriesCount = Category::count();
        $pvsCount = \App\Models\PV::count();
        $missionsCount = Mission::count();
        $missionsInProgress = Mission::whereIn('status', ['ASSIGNED', 'IN_PROGRESS'])->count();
        $walletTotal = AgencyWallet::sum('balance');
        $pendingReleases = \App\Models\PV::where('payment_status', 'VALIDATED')->whereNull('funds_released_at')->count();
        
        // Count all workers (admins, gestionnaires, agents, artists)
        $workersCount = User::whereHas('roles', function($q) {
            $q->whereIn('name', ['admin', 'gestionnaire', 'agent', 'artist']);
        })->count();
        
        return view('blades.superadmin.dashboard', compact(
            'user',
            'agenciesCount',
            'categoriesCount',
            'pvsCount',
            'missionsCount',
            'missionsInProgress',
            'walletTotal',
            'pendingReleases',
            'workersCount'
        ));
    }

    public function manageAgencies()
    {
        $wilayas = [
            1 => 'Adrar', 2 => 'Chlef', 3 => 'Laghouat', 4 => 'Oum El Bouaghi', 5 => 'Batna',
            6 => 'Béjaïa', 7 => 'Biskra', 8 => 'Béchar', 9 => 'Blida', 10 => 'Bouira',
            11 => 'Tamanrasset', 12 => 'Tébessa', 13 => 'Tlemcen', 14 => 'Tiaret', 15 => 'Tizi Ouzou',
            16 => 'Alger', 17 => 'Djelfa', 18 => 'Jijel', 19 => 'Setif', 20 => 'Saïda',
            21 => 'Skikda', 22 => 'Sidi Bel Abbès', 23 => 'Annaba', 24 => 'Guelma', 25 => 'Constantine',
            26 => 'Médéa', 27 => 'Mostaganem', 28 => 'Msila', 29 => 'Mascara', 30 => 'Ouargla',
            31 => 'Oran', 32 => 'El Bayadh', 33 => 'Illizi', 34 => 'Bordj Bou Arréridj', 35 => 'Boumerdès',
            36 => 'El Tarf', 37 => 'Tindouf', 38 => 'Tissemsilt', 39 => 'El Oued', 40 => 'Khenchela',
            41 => 'Souk Ahras', 42 => 'Tipaza', 43 => 'Mila', 44 => 'Aïn Defla', 45 => 'Naâma',
            46 => 'Aïn Témouchent', 47 => 'Ghardaïa', 48 => 'Relizane', 49 => 'Timimoun', 50 => 'Bordj Badji Mokhtar',
            51 => 'Ouled Djellal', 52 => 'Béni Abbès', 53 => 'In Salah', 54 => 'In Guezzam', 55 => 'Touggourt',
            56 => 'Djanet', 57 => 'El M\'Ghair', 58 => 'El Meniaa'
        ];
        
        $agencies = Agency::with(['admin', 'users', 'artists', 'agents.user', 'gestionnaires', 'wallet'])
            ->orderBy('wilaya')
            ->orderBy('agency_name')
            ->get();
        
        return view('blades.superadmin.manage-agencies', compact('wilayas', 'agencies'));
    }

    public function managePVs()
    {
        $pvs = PV::with(['agent.user', 'agency'])
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('blades.superadmin.manage-pvs', compact('pvs'));
    }

    public function viewPV($id)
    {
        $pv = PV::with([
                'agent.user',
                'agency',
                'mission',
                'devices',
                'artworkUsages.artwork.artist.user',
                'transactions.artist.user',
            ])
            ->findOrFail($id);

        return view('blades.superadmin.view-pv', compact('pv'));
    }

    public function showAgency($id)
    {
        $agency = Agency::with(['admin', 'artists.user'])->findOrFail($id);
        
        $gestionnaires = User::whereHas('roles', function($q) {
                $q->where('name', 'gestionnaire');
            })
            ->where('agency_id', $agency->id)
            ->get();
        
        $agents = Agent::where('agency_id', $agency->id)
            ->with('user')
            ->get();
        
        $artists = Artist::where('agency_id', $agency->id)
            ->with(['user', 'wallet'])
            ->get();
        
        $artistsIds = $artists->pluck('id');
        $transactionsCount = Transaction::whereIn('artist_id', $artistsIds)->count();
        
        // Get all agencies for transfer modals
        $allAgencies = Agency::where('id', '!=', $agency->id)
            ->orderBy('wilaya')
            ->orderBy('agency_name')
            ->get();
        
        return view('blades.superadmin.show-agency', compact(
            'agency', 
            'gestionnaires', 
            'agents', 
            'artists', 
            'transactionsCount',
            'allAgencies'
        ));
    }

    public function createAdmin()
    {
        $agencies = Agency::orderBy('wilaya')->orderBy('agency_name')->get();
        return view('blades.superadmin.create-admin', compact('agencies'));
    }

    public function storeAdmin(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:255',
            'agency_id' => 'required|exists:agencies,id',
        ]);

        $agency = Agency::findOrFail($request->agency_id);

        // Check if agency already has an admin
        if ($agency->admin_id) {
            $existingAdmin = $agency->admin;
            return redirect()->back()
                ->withErrors(['agency_id' => 'This agency already has an admin assigned (' . $existingAdmin->name . ' - ' . $existingAdmin->email . '). Please remove or transfer the existing admin first before adding a new one.'])
                ->withInput();
        }

        $admin = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'agency_id' => $request->agency_id,
            'role_id' => Role::where('name', 'admin')->first()->id,
        ]);

        $admin->assignRole('admin');

        // Update agency admin_id
        $agency->admin_id = $admin->id;
        $agency->save();

        return redirect()->route('superadmin.manage-admins')->with('success', 'Admin created successfully and assigned to agency.');
    }

    public function storeAdminFromTransfer(Request $request)
    {
        $wilayas = [
            1 => 'Adrar', 2 => 'Chlef', 3 => 'Laghouat', 4 => 'Oum El Bouaghi', 5 => 'Batna',
            6 => 'Béjaïa', 7 => 'Biskra', 8 => 'Béchar', 9 => 'Blida', 10 => 'Bouira',
            11 => 'Tamanrasset', 12 => 'Tébessa', 13 => 'Tlemcen', 14 => 'Tiaret', 15 => 'Tizi Ouzou',
            16 => 'Alger', 17 => 'Djelfa', 18 => 'Jijel', 19 => 'Setif', 20 => 'Saïda',
            21 => 'Skikda', 22 => 'Sidi Bel Abbès', 23 => 'Annaba', 24 => 'Guelma', 25 => 'Constantine',
            26 => 'Médéa', 27 => 'Mostaganem', 28 => 'Msila', 29 => 'Mascara', 30 => 'Ouargla',
            31 => 'Oran', 32 => 'El Bayadh', 33 => 'Illizi', 34 => 'Bordj Bou Arréridj', 35 => 'Boumerdès',
            36 => 'El Tarf', 37 => 'Tindouf', 38 => 'Tissemsilt', 39 => 'El Oued', 40 => 'Khenchela',
            41 => 'Souk Ahras', 42 => 'Tipaza', 43 => 'Mila', 44 => 'Aïn Defla', 45 => 'Naâma',
            46 => 'Aïn Témouchent', 47 => 'Ghardaïa', 48 => 'Relizane', 49 => 'Timimoun', 50 => 'Bordj Badji Mokhtar',
            51 => 'Ouled Djellal', 52 => 'Béni Abbès', 53 => 'In Salah', 54 => 'In Guezzam', 55 => 'Touggourt',
            56 => 'Djanet', 57 => 'El M\'Ghair', 58 => 'El Meniaa'
        ];

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:255',
            'wilaya_code' => 'required|integer|min:1|max:58',
        ]);

        if (!isset($wilayas[$request->wilaya_code])) {
            return redirect()->route('superadmin.manage-transfer-workers')
                ->withErrors(['wilaya_code' => 'Invalid wilaya selected.'])
                ->withInput();
        }

        $wilayaName = $wilayas[$request->wilaya_code];

        try {
            DB::beginTransaction();

            // Create agency for this wilaya if it doesn't exist
            $agency = Agency::firstOrCreate(
                ['wilaya' => $wilayaName],
                ['agency_name' => $wilayaName . ' Office']
            );

            // Check if agency already has an admin
            if ($agency->admin_id) {
                $existingAdmin = $agency->admin;
                DB::rollBack();
                return redirect()->route('superadmin.manage-transfer-workers')
                    ->withErrors(['wilaya_code' => 'This wilaya already has an agency with an admin assigned (' . $existingAdmin->name . ' - ' . $existingAdmin->email . ').'])
                    ->withInput();
            }

            // Create admin user
            $admin = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'agency_id' => $agency->id,
                'role_id' => Role::where('name', 'admin')->first()->id,
            ]);

            $admin->assignRole('admin');

            // Update agency admin_id
            $agency->admin_id = $admin->id;
            $agency->save();

            // Create wallet for the agency if it doesn't exist
            AgencyWallet::firstOrCreate(
                ['agency_id' => $agency->id],
                ['balance' => 0]
            );

            DB::commit();

            return redirect()->route('superadmin.manage-transfer-workers')->with('success', 'Agency created for ' . $wilayaName . ' and admin created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('superadmin.manage-transfer-workers')
                ->withErrors(['error' => 'An error occurred: ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function removeAdmin($id)
    {
        $admin = User::findOrFail($id);
        $admin->removeRole('admin');
        $admin->delete();

        return redirect()->route('superadmin.manage-admins')->with('success', 'Admin removed successfully');
    }

    public function manageGestionnaires()
    {
        $gestionnaires = User::whereHas('roles', function($q) { 
                $q->where('name', 'gestionnaire'); 
            })
            ->with('agency')
            ->orderBy('name')
            ->paginate(15);
        return view('blades.superadmin.manage-gestionnaires', compact('gestionnaires'));
    }

    public function createGestionnaire()
    {
        $agencies = Agency::orderBy('wilaya')->orderBy('agency_name')->get();
        return view('blades.superadmin.create-gestionnaire', compact('agencies'));
    }

    public function storeGestionnaire(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:255',
            'agency_id' => 'required|exists:agencies,id',
        ]);

        $gestionnaire = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'agency_id' => $request->agency_id,
            'role_id' => Role::where('name', 'gestionnaire')->first()->id,
        ]);

        $gestionnaire->assignRole('gestionnaire');

        return redirect()->route('superadmin.manage-gestionnaires')->with('success', 'Gestionnaire created successfully');
    }

    public function removeGestionnaire($id)
    {
        $gestionnaire = User::findOrFail($id);
        $gestionnaire->removeRole('gestionnaire');
        $gestionnaire->delete();

        return redirect()->route('superadmin.manage-gestionnaires')->with('success', 'Gestionnaire removed successfully');
    }

    public function manageCategories()
    {
        $categories = Category::with('artworks')->orderBy('name')->paginate(15);
        return view('blades.superadmin.manage-categories', compact('categories'));
    }

    public function createCategory()
    {
        return view('blades.superadmin.create-category');
    }

    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'coefficient' => 'required|numeric|min:0',
        ]);

        Category::create($request->all());

        return redirect()->route('superadmin.manage-categories')->with('success', 'Category created successfully');
    }

    public function editCategory($id)
    {
        $category = Category::findOrFail($id);
        return view('blades.superadmin.edit-category', compact('category'));
    }

    public function updateCategory(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'coefficient' => 'required|numeric|min:0',
        ]);

        $category = Category::findOrFail($id);
        $category->update($request->all());

        return redirect()->route('superadmin.manage-categories')->with('success', 'Category updated successfully');
    }

    public function deleteCategory($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return redirect()->route('superadmin.manage-categories')->with('success', 'Category deleted successfully');
    }

    public function assignAgencyAdmin($id)
    {
        $agency = Agency::findOrFail($id);
        // Get all admins regardless of their current agency assignment
        $admins = User::whereHas('roles', function($q) {
                $q->where('name', 'admin');
            })
            ->with('agency')
            ->orderBy('name')
            ->get();
        
        return view('blades.superadmin.assign-agency-admin', compact('agency', 'admins'));
    }

    public function storeAgencyAdmin(Request $request, $id)
    {
        $agency = Agency::findOrFail($id);
        
        $request->validate([
            'admin_id' => 'required|exists:users,id',
        ]);

        $admin = User::findOrFail($request->admin_id);
        
        if (!$admin->hasRole('admin')) {
            return redirect()->back()->withErrors(['admin_id' => 'Selected user is not an admin']);
        }

        // Check if agency already has an admin and it's different from the selected one
        if ($agency->admin_id && $agency->admin_id != $admin->id) {
            $existingAdmin = $agency->admin;
            return redirect()->back()
                ->withErrors(['admin_id' => 'This agency already has an admin assigned (' . $existingAdmin->name . ' - ' . $existingAdmin->email . '). Please remove or transfer the existing admin first before assigning a new one.']);
        }

        // Handle if admin was assigned to another agency
        $oldAgencyName = null;
        if ($admin->agency_id && $admin->agency_id != $agency->id) {
            $oldAgency = Agency::find($admin->agency_id);
            $oldAgencyName = $oldAgency ? $oldAgency->agency_name : null;
            if ($oldAgency && $oldAgency->admin_id == $admin->id) {
                $oldAgency->admin_id = null;
                $oldAgency->save();
            }
        }

        $agency->admin_id = $admin->id;
        $agency->save();

        $admin->agency_id = $agency->id;
        $admin->save();

        // Send email notification to the admin
        if ($oldAgencyName || $admin->agency_id) {
            Mail::to($admin->email)->send(new AgencyTransferEmail($admin, $agency, $oldAgencyName));
        }

        return redirect()->route('superadmin.show-agency', $agency->id)->with('success', 'Admin assigned successfully');
    }

    public function removeAgencyAdmin($id)
    {
        $agency = Agency::findOrFail($id);
        
        if ($agency->admin) {
            $admin = $agency->admin;
            $agency->admin_id = null;
            $agency->save();
            
            $admin->agency_id = null;
            $admin->save();
        }

        return redirect()->route('superadmin.show-agency', $agency->id)->with('success', 'Admin removed successfully');
    }

    public function createAgencyGestionnaire($id)
    {
        $agency = Agency::findOrFail($id);
        return view('blades.superadmin.create-agency-gestionnaire', compact('agency'));
    }

    public function storeAgencyGestionnaire(Request $request, $id)
    {
        $agency = Agency::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:255',
        ]);

        $gestionnaire = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'agency_id' => $agency->id,
            'role_id' => Role::where('name', 'gestionnaire')->first()->id,
        ]);

        $gestionnaire->assignRole('gestionnaire');

        return redirect()->route('superadmin.show-agency', $agency->id)->with('success', 'Gestionnaire created successfully');
    }

    public function removeAgencyGestionnaire($agencyId, $gestionnaireId)
    {
        $agency = Agency::findOrFail($agencyId);
        $gestionnaire = User::findOrFail($gestionnaireId);
        
        if (!$gestionnaire->hasRole('gestionnaire') || $gestionnaire->agency_id != $agency->id) {
            return redirect()->route('superadmin.show-agency', $agency->id)
                ->withErrors(['error' => 'Invalid gestionnaire']);
        }

        $gestionnaire->removeRole('gestionnaire');
        $gestionnaire->delete();

        return redirect()->route('superadmin.show-agency', $agency->id)->with('success', 'Gestionnaire removed successfully');
    }

    public function createAgencyAgent($id)
    {
        $agency = Agency::findOrFail($id);
        return view('blades.superadmin.create-agency-agent', compact('agency'));
    }

    public function storeAgencyAgent(Request $request, $id)
    {
        $agency = Agency::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:255',
            'badge_number' => 'nullable|string|max:255',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'agency_id' => $agency->id,
            'role_id' => Role::where('name', 'agent')->first()->id,
        ]);

        $user->assignRole('agent');

        $agent = Agent::create([
            'user_id' => $user->id,
            'agency_id' => $agency->id,
            'badge_number' => $request->badge_number ?: 'AG-' . now()->format('ymd') . '-' . $user->id,
        ]);

        return redirect()->route('superadmin.show-agency', $agency->id)->with('success', 'Agent created successfully');
    }

    public function removeAgencyAgent($agencyId, $agentId)
    {
        $agency = Agency::findOrFail($agencyId);
        $agent = Agent::findOrFail($agentId);
        
        if ($agent->agency_id != $agency->id) {
            return redirect()->route('superadmin.show-agency', $agency->id)
                ->withErrors(['error' => 'Invalid agent']);
        }

        $user = $agent->user;
        $agent->forceDelete();
        if ($user) {
            $user->removeRole('agent');
            $user->forceDelete();
        }

        return redirect()->route('superadmin.show-agency', $agency->id)->with('success', 'Agent removed successfully');
    }

    public function transferAgencyAdmin(Request $request, $id)
    {
        $agency = Agency::findOrFail($id);
        
        $request->validate([
            'new_agency_id' => 'required|exists:agencies,id|different:' . $id,
        ]);

        if (!$agency->admin_id) {
            return redirect()->back()->withErrors(['error' => 'This agency has no admin to transfer']);
        }

        $newAgency = Agency::findOrFail($request->new_agency_id);

        // Check if new agency already has an admin
        if ($newAgency->admin_id) {
            $existingAdmin = $newAgency->admin;
            return redirect()->back()
                ->withErrors(['new_agency_id' => 'The target agency already has an admin (' . $existingAdmin->name . ' - ' . $existingAdmin->email . '). Please remove or transfer that admin first.']);
        }

        try {
            DB::beginTransaction();

            $admin = $agency->admin;
            $oldAgencyName = $agency->agency_name;
            
            // Remove admin from old agency
            $agency->admin_id = null;
            $agency->save();

            // Assign admin to new agency
            $newAgency->admin_id = $admin->id;
            $newAgency->save();

            $admin->agency_id = $newAgency->id;
            $admin->save();

            DB::commit();

            // Send email notification to the admin
            Mail::to($admin->email)->send(new AgencyTransferEmail($admin, $newAgency, $oldAgencyName));

            return redirect()->route('superadmin.show-agency', $id)->with('success', 'Admin transferred successfully to ' . $newAgency->agency_name);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'An error occurred while transferring the admin: ' . $e->getMessage()]);
        }
    }

    public function transferAgencyGestionnaire(Request $request, $agencyId, $gestionnaireId)
    {
        $agency = Agency::findOrFail($agencyId);
        $gestionnaire = User::findOrFail($gestionnaireId);
        
        $request->validate([
            'new_agency_id' => 'required|exists:agencies,id|different:' . $agencyId,
        ]);

        if (!$gestionnaire->hasRole('gestionnaire') || $gestionnaire->agency_id != $agency->id) {
            return redirect()->back()->withErrors(['error' => 'Invalid gestionnaire']);
        }

        $newAgency = Agency::findOrFail($request->new_agency_id);

        try {
            DB::beginTransaction();

            $oldAgencyName = $agency->agency_name;
            $gestionnaire->agency_id = $newAgency->id;
            $gestionnaire->save();

            // Update missions agency_id for this gestionnaire
            Mission::where('gestionnaire_id', $gestionnaire->id)
                ->update(['agency_id' => $newAgency->id]);

            DB::commit();

            // Send email notification to the gestionnaire
            Mail::to($gestionnaire->email)->send(new AgencyTransferEmail($gestionnaire, $newAgency, $oldAgencyName));

            return redirect()->route('superadmin.show-agency', $agencyId)->with('success', 'Gestionnaire transferred successfully to ' . $newAgency->agency_name);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'An error occurred while transferring the gestionnaire: ' . $e->getMessage()]);
        }
    }

    public function transferAgencyAgent(Request $request, $agencyId, $agentId)
    {
        $agency = Agency::findOrFail($agencyId);
        $agent = Agent::findOrFail($agentId);
        
        $request->validate([
            'new_agency_id' => 'required|exists:agencies,id|different:' . $agencyId,
        ]);

        if ($agent->agency_id != $agency->id) {
            return redirect()->back()->withErrors(['error' => 'Invalid agent']);
        }

        $newAgency = Agency::findOrFail($request->new_agency_id);

        try {
            DB::beginTransaction();

            $oldAgencyName = $agency->agency_name;
            $agent->agency_id = $newAgency->id;
            $agent->save();

            if ($agent->user) {
                $agent->user->agency_id = $newAgency->id;
                $agent->user->save();

                // Send email notification to the agent
                Mail::to($agent->user->email)->send(new AgencyTransferEmail($agent->user, $newAgency, $oldAgencyName));
            }

            DB::commit();

            return redirect()->route('superadmin.show-agency', $agencyId)->with('success', 'Agent transferred successfully to ' . $newAgency->agency_name);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'An error occurred while transferring the agent: ' . $e->getMessage()]);
        }
    }

    public function transferAgencyArtist(Request $request, $agencyId, $artistId)
    {
        $agency = Agency::findOrFail($agencyId);
        $artist = Artist::findOrFail($artistId);
        
        $request->validate([
            'new_agency_id' => 'required|exists:agencies,id|different:' . $agencyId,
        ]);

        if ($artist->agency_id != $agency->id) {
            return redirect()->back()->withErrors(['error' => 'Invalid artist']);
        }

        $newAgency = Agency::findOrFail($request->new_agency_id);

        try {
            DB::beginTransaction();

            $oldAgencyName = $agency->agency_name;
            $artist->agency_id = $newAgency->id;
            $artist->save();

            if ($artist->user) {
                $artist->user->agency_id = $newAgency->id;
                $artist->user->save();

                // Send email notification to the artist
                Mail::to($artist->user->email)->send(new AgencyTransferEmail($artist->user, $newAgency, $oldAgencyName));
            }

            DB::commit();

            return redirect()->route('superadmin.show-agency', $agencyId)->with('success', 'Artist transferred successfully to ' . $newAgency->agency_name);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'An error occurred while transferring the artist: ' . $e->getMessage()]);
        }
    }


    public function manageTransferWorkers(Request $request)
    {
        $wilayas = [
            1 => 'Adrar', 2 => 'Chlef', 3 => 'Laghouat', 4 => 'Oum El Bouaghi', 5 => 'Batna',
            6 => 'Béjaïa', 7 => 'Biskra', 8 => 'Béchar', 9 => 'Blida', 10 => 'Bouira',
            11 => 'Tamanrasset', 12 => 'Tébessa', 13 => 'Tlemcen', 14 => 'Tiaret', 15 => 'Tizi Ouzou',
            16 => 'Alger', 17 => 'Djelfa', 18 => 'Jijel', 19 => 'Setif', 20 => 'Saïda',
            21 => 'Skikda', 22 => 'Sidi Bel Abbès', 23 => 'Annaba', 24 => 'Guelma', 25 => 'Constantine',
            26 => 'Médéa', 27 => 'Mostaganem', 28 => 'Msila', 29 => 'Mascara', 30 => 'Ouargla',
            31 => 'Oran', 32 => 'El Bayadh', 33 => 'Illizi', 34 => 'Bordj Bou Arréridj', 35 => 'Boumerdès',
            36 => 'El Tarf', 37 => 'Tindouf', 38 => 'Tissemsilt', 39 => 'El Oued', 40 => 'Khenchela',
            41 => 'Souk Ahras', 42 => 'Tipaza', 43 => 'Mila', 44 => 'Aïn Defla', 45 => 'Naâma',
            46 => 'Aïn Témouchent', 47 => 'Ghardaïa', 48 => 'Relizane', 49 => 'Timimoun', 50 => 'Bordj Badji Mokhtar',
            51 => 'Ouled Djellal', 52 => 'Béni Abbès', 53 => 'In Salah', 54 => 'In Guezzam', 55 => 'Touggourt',
            56 => 'Djanet', 57 => 'El M\'Ghair', 58 => 'El Meniaa'
        ];

        // Start building the query
        $query = User::whereHas('roles', function($q) {
                $q->whereIn('name', ['artist', 'agent', 'gestionnaire', 'admin']);
            })
            ->with(['roles', 'agency', 'artist', 'agent']);

        // Filter by role
        if ($request->filled('role')) {
            $query->whereHas('roles', function($q) use ($request) {
                $q->where('name', $request->role);
            });
        }

        // Filter by wilaya (agency location)
        if ($request->filled('wilaya')) {
            $query->whereHas('agency', function($q) use ($request) {
                $q->where('wilaya', $request->wilaya);
            });
        }

        // Filter by agency
        if ($request->filled('agency_id')) {
            $query->where('agency_id', $request->agency_id);
        }
        
        // Also filter users without agency if needed (but this is optional)

        // Get filtered users
        $users = $query->orderByRaw('CASE WHEN agency_id IS NULL THEN 1 ELSE 0 END')
            ->orderBy('agency_id')
            ->orderBy('name')
            ->paginate(15)
            ->appends(request()->query());

        // Get all agencies for dropdown
        $agencies = Agency::orderBy('wilaya')->orderBy('agency_name')->get();
        
        // Get all existing agency wilayas (get actual wilaya values, not normalized)
        $allAgencies = Agency::get();
        
        // Helper function to normalize wilaya names (same as in allWilayas)
        $normalizeString = function($str) {
            if (empty($str)) return '';
            $str = strtolower(trim($str));
            $replacements = [
                'é' => 'e', 'è' => 'e', 'ê' => 'e', 'ë' => 'e',
                'à' => 'a', 'â' => 'a', 'ä' => 'a',
                'î' => 'i', 'ï' => 'i',
                'ô' => 'o', 'ö' => 'o',
                'ù' => 'u', 'û' => 'u', 'ü' => 'u',
                'ç' => 'c',
                'ñ' => 'n',
            ];
            foreach ($replacements as $accent => $normal) {
                $str = str_replace($accent, $normal, $str);
            }
            $str = preg_replace('/\s+/', ' ', $str);
            return $str;
        };
        
        // Find wilayas without agencies
        $wilayasWithoutAgencies = [];
        foreach ($wilayas as $code => $name) {
            $normalizedName = $normalizeString($name);
            $hasAgency = false;
            
            // Special case: Alger vs Algiers - check first
            $algerVariations = ['alger', 'algiers'];
            $isAlgerWilaya = in_array($normalizedName, $algerVariations);
            
            // Check if any agency exists for this wilaya (using fuzzy matching)
            foreach ($allAgencies as $agency) {
                $normalizedAgencyWilaya = $normalizeString($agency->wilaya);
                
                // Special case: Alger vs Algiers
                if ($isAlgerWilaya && in_array($normalizedAgencyWilaya, $algerVariations)) {
                    $hasAgency = true;
                    break;
                }
                
                // Direct match
                if ($normalizedAgencyWilaya === $normalizedName) {
                    $hasAgency = true;
                    break;
                }
                
                // Similarity check
                similar_text($normalizedAgencyWilaya, $normalizedName, $percent);
                if ($percent > 90) {
                    $hasAgency = true;
                    break;
                }
                
                // Word matching
                $nameWords = explode(' ', $normalizedName);
                $agencyWords = explode(' ', $normalizedAgencyWilaya);
                $matchingWords = 0;
                foreach ($nameWords as $word) {
                    if (in_array($word, $agencyWords) && strlen($word) > 2) {
                        $matchingWords++;
                    }
                }
                if (count($nameWords) > 0 && ($matchingWords / count($nameWords)) > 0.7) {
                    $hasAgency = true;
                    break;
                }
            }
            
            if (!$hasAgency) {
                $wilayasWithoutAgencies[$code] = $name;
            }
        }
        
        // Get unique wilayas from agencies
        $availableWilayas = Agency::distinct()->pluck('wilaya')->sort()->toArray();

        return view('blades.superadmin.manage-transfer-workers', compact(
            'users', 
            'agencies', 
            'wilayasWithoutAgencies',
            'wilayas',
            'availableWilayas'
        ));
    }

    public function transferUser(Request $request, $userId)
    {
        $request->validate([
            'new_agency_id' => 'required|exists:agencies,id',
        ]);

        try {
            DB::beginTransaction();

            $user = User::with(['roles', 'artist', 'agent'])->findOrFail($userId);
            $oldAgencyId = $user->agency_id;
            $newAgencyId = $request->new_agency_id;

            // Check if user is being transferred to the same agency
            if ($oldAgencyId == $newAgencyId) {
                DB::rollBack();
                return redirect()->back()->withErrors(['new_agency_id' => 'User is already in this agency.']);
            }

            $newAgency = Agency::findOrFail($newAgencyId);
            $oldAgency = $oldAgencyId ? Agency::find($oldAgencyId) : null;
            $oldAgencyName = $oldAgency ? $oldAgency->agency_name : null;
            $newAgencyName = $newAgency->agency_name;

            // Handle Admin transfer - update agency.admin_id
            if ($user->hasRole('admin')) {
                // Step 1: Remove admin from old agency if exists
                if ($oldAgencyId) {
                    $oldAgency = Agency::where('admin_id', $user->id)
                        ->where('id', $oldAgencyId)
                        ->first();
                    if ($oldAgency) {
                        $oldAgency->admin_id = null;
                        $oldAgency->save();
                    }
                }

                // Step 2: Handle new agency - remove existing admin if any
                if ($newAgency->admin_id && $newAgency->admin_id != $user->id) {
                    // Find and remove the old admin from the new agency
                    $oldAdmin = User::find($newAgency->admin_id);
                    if ($oldAdmin) {
                        // Find and clear the old admin's agency relationship
                        $oldAdminOldAgency = Agency::where('admin_id', $oldAdmin->id)->first();
                        if ($oldAdminOldAgency) {
                            $oldAdminOldAgency->admin_id = null;
                            $oldAdminOldAgency->save();
                        }
                        $oldAdmin->agency_id = null;
                        $oldAdmin->save();
                    }
                    // Clear admin_id from new agency before assigning new admin
                    $newAgency->admin_id = null;
                    $newAgency->save();
                }

                // Step 3: Assign this admin to the new agency
                $newAgency->refresh(); // Refresh to get latest state
                $newAgency->admin_id = $user->id;
                $newAgency->save();
            }

            // Update user agency
            $user->agency_id = $newAgencyId;
            $user->save();

            // Update artist agency if exists
            if ($user->artist) {
                $user->artist->agency_id = $newAgencyId;
                $user->artist->save();
            }

            // Update agent agency if exists
            if ($user->agent) {
                $user->agent->agency_id = $newAgencyId;
                $user->agent->save();
            }

            // Handle Gestionnaire transfer - update missions agency_id
            if ($user->hasRole('gestionnaire')) {
                // Update missions agency_id for this gestionnaire
                Mission::where('gestionnaire_id', $user->id)
                    ->update(['agency_id' => $newAgencyId]);
            }

            DB::commit();

            // Send email notification to the user
            Mail::to($user->email)->send(new AgencyTransferEmail($user, $newAgency, $oldAgencyName));

            return redirect()->back()->with('success', 'User transferred successfully from "' . ($oldAgencyName ?? 'None') . '" to "' . $newAgencyName . '".');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'An error occurred while transferring the user: ' . $e->getMessage()]);
        }
    }

    public function deleteUser($userId)
    {
        try {
            DB::beginTransaction();

            $user = User::with(['roles', 'artist', 'agent'])->findOrFail($userId);

            // Check if user is admin and is assigned to an agency
            if ($user->hasRole('admin')) {
                $agency = Agency::where('admin_id', $user->id)->first();
                if ($agency) {
                    $agency->admin_id = null;
                    $agency->save();
                }
            }

            // Delete related records
            if ($user->artist) {
                // Delete identity document if exists
                if ($user->artist->identity_document) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($user->artist->identity_document);
                }
                // Delete wallet if exists
                if ($user->artist->wallet_id) {
                    \App\Models\Wallet::where('id', $user->artist->wallet_id)->delete();
                }
                $user->artist->forceDelete();
            }

            if ($user->agent) {
                $user->agent->forceDelete();
            }

            // Delete missions if gestionnaire
            if ($user->hasRole('gestionnaire')) {
                \App\Models\Mission::where('gestionnaire_id', $user->id)->delete();
            }

            // Remove all roles
            $user->roles()->detach();

            // Delete user
            $user->forceDelete();

            DB::commit();

            return redirect()->back()->with('success', 'User deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'An error occurred while deleting the user: ' . $e->getMessage()]);
        }
    }

    public function manageDeviceTypes()
    {
        $deviceTypes = DeviceType::orderBy('type')->orderBy('name')->paginate(15);
        return view('blades.superadmin.manage-device-types', compact('deviceTypes'));
    }

    public function createDeviceType()
    {
        return view('blades.superadmin.create-device-type');
    }

    public function storeDeviceType(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'nullable|string|max:255',
            'coefficient' => 'required|numeric|min:0.1',
            'description' => 'nullable|string',
        ]);

        DeviceType::create($request->all());

        return redirect()->route('superadmin.manage-device-types')->with('success', 'Device type created successfully');
    }

    public function editDeviceType($id)
    {
        $deviceType = DeviceType::findOrFail($id);
        return view('blades.superadmin.edit-device-type', compact('deviceType'));
    }

    public function updateDeviceType(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'nullable|string|max:255',
            'coefficient' => 'required|numeric|min:0.1',
            'description' => 'nullable|string',
        ]);

        $deviceType = DeviceType::findOrFail($id);
        $deviceType->update($request->all());

        return redirect()->route('superadmin.manage-device-types')->with('success', 'Device type updated successfully');
    }

    public function deleteDeviceType($id)
    {
        $deviceType = DeviceType::findOrFail($id);
        $deviceType->delete();

        return redirect()->route('superadmin.manage-device-types')->with('success', 'Device type deleted successfully');
    }

    // Complaints System
    public function complaints(Request $request)
    {
        return $this->messages($request);
    }

    public function complaintsInbox(Request $request)
    {
        return $this->inbox($request);
    }

    public function complaintsSent(Request $request)
    {
        return $this->sent($request);
    }

    public function createComplaint(Request $request)
    {
        return $this->createComplaintOrReport($request);
    }

    public function storeComplaint(Request $request)
    {
        return $this->storeComplaintOrReport($request);
    }

    public function deleteComplaint($id)
    {
        $superAdmin = auth()->user();
        
        // SuperAdmin can delete any complaint (they see all complaints)
        $complaint = Complain::where('type', Complain::TYPE_COMPLAINT)
            ->findOrFail($id);
        
        $complaint->hideForUser(Auth::id());
        
        return redirect()->route('superadmin.complaints.index')->with('success', 'Complaint deleted successfully');
    }

    public function showComplaint($id)
    {
        $message = Complain::with(['sender', 'targetUser', 'admin', 'gestionnaire', 'artist.user', 'agentProfile.user', 'agency'])
            ->find($id);
        
        if (!$message) {
            return view('blades.shared.content-not-found', [
                'pageTitle' => 'Complaint Not Found',
                'title' => 'Complaint Not Found',
                'message' => 'Sorry, the complaint you are looking for does not exist or has been deleted.',
                'backUrl' => route('superadmin.complaints.index'),
            ]);
        }
        
        return view('blades.superadmin.complaints.show', compact('message'));
    }

    public function respondToComplaint(Request $request, $id)
    {
        return $this->respondToAdminComplaint($request, $id);
    }

    public function resolveComplaint($id)
    {
        $message = Complain::findOrFail($id);
        $message->status = 'RESOLVED';
        $message->save();
        return redirect()->route('superadmin.complaints.index')->with('success', 'Complaint resolved successfully');
    }

    // Messages System - Unified
    public function messages(Request $request)
    {
        $type = $request->get('type', 'all'); // all, complaint, or report

        $query = Complain::with([
                'sender',
                'targetUser',
                'admin',
                'gestionnaire',
                'artist.user',
                'agentProfile.user',
                'agency',
            ])
            ->notHiddenBy(Auth::id());

        // Filter by type
        if ($type === 'complaint') {
            $query->complaints();
        } elseif ($type === 'report') {
            $query->reports();
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $items = $query->orderByDesc('created_at')->paginate(20)->withQueryString();

        // Statistics
        $stats = [
            'total_complaints' => Complain::complaints()->count(),
            'pending_complaints' => Complain::complaints()->where('status', 'PENDING')->count(),
            'total_reports' => Complain::reports()->count(),
            'pending_reports' => Complain::reports()->where('status', 'PENDING')->count(),
            'admin_complaints' => Complain::where('complaint_type', 'ADMIN_TO_SUPERADMIN')->count(),
        ];

        return view('blades.superadmin.complaints.index', compact('items', 'stats'));
    }

    public function messagesInbox(Request $request)
    {
        return $this->inbox($request);
    }

    public function messagesSent(Request $request)
    {
        return $this->sent($request);
    }

    public function createMessage(Request $request)
    {
        return $this->createComplaintOrReport($request);
    }

    public function storeMessage(Request $request)
    {
        return $this->storeComplaintOrReport($request);
    }

    public function showMessage($id)
    {
        $message = Complain::with(['sender', 'targetUser', 'admin', 'gestionnaire', 'artist.user', 'agentProfile.user', 'agency'])
            ->findOrFail($id);
        
        return view('blades.superadmin.complaints.show', compact('message'));
    }

    public function respondMessage(Request $request, $id)
    {
        return $this->respondToAdminComplaint($request, $id);
    }

    public function resolveMessage($id)
    {
        $message = Complain::findOrFail($id);
        $message->status = 'RESOLVED';
        $message->save();
        return redirect()->route('superadmin.complaints.index')->with('success', 'Complaint resolved successfully');
    }

    public function inbox(Request $request)
    {
        $type = $request->get('type', 'complaint');

        $query = Complain::with(['sender', 'artist.user', 'agentProfile.user', 'gestionnaire', 'admin'])
            ->inbox('super_admin', auth()->id(), null);

        if ($type === 'complaint') {
            $query->complaints();
        } elseif ($type === 'report') {
            $query->reports();
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $items = $query->orderByDesc('created_at')->paginate(20)->withQueryString();

        return view('blades.superadmin.complaints.inbox', compact('items'));
    }

    public function sent(Request $request)
    {
        $type = $request->get('type', 'complaint');

        $query = Complain::with(['targetUser', 'admin', 'gestionnaire', 'agency'])
            ->sent(auth()->id());

        if ($type === 'complaint') {
            $query->complaints();
        } elseif ($type === 'report') {
            $query->reports();
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $items = $query->orderByDesc('created_at')->paginate(20)->withQueryString();

        return view('blades.superadmin.complaints.sent', compact('items'));
    }

    public function createComplaintOrReport(Request $request)
    {
        $type = $request->get('type', 'complaint');
        $targets = array_keys(config('complaints.targets.super_admin', []));
        return view('blades.superadmin.complaints.create', compact('targets'));
    }

    public function storeComplaintOrReport(Request $request)
    {
        $superAdmin = auth()->user();
        $type = $request->get('type', 'complaint');

        $request->validate([
            'target_role' => 'required|string|in:' . implode(',', array_keys(config('complaints.targets.super_admin', []))),
            'target_user_id' => 'nullable|exists:users,id',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'location_link' => 'nullable|url',
            'images.*' => 'nullable|image|mimes:jpg,jpeg,png',
        ]);

        $images = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $images[] = $image->store('complaints', 'public');
            }
        }

        $complaintType = Complain::resolveType('super_admin', $request->target_role);
        
        $complaint = Complain::create([
            'type' => $type === 'report' ? Complain::TYPE_REPORT : Complain::TYPE_COMPLAINT,
            'complaint_type' => $complaintType,
            'super_admin_id' => $superAdmin->id,
            'sender_user_id' => $superAdmin->id,
            'sender_role' => 'super_admin',
            'target_role' => $request->target_role,
            'target_user_id' => $request->target_user_id,
            'subject' => $request->subject,
            'message' => $request->message,
            'location_link' => $request->location_link,
            'images' => !empty($images) ? $images : null,
            'status' => 'PENDING',
        ]);

        $notificationType = $type === 'report' ? 'report_created' : 'complaint_created';
        $itemType = $type === 'report' ? 'report' : 'complaint';
        
        if ($request->target_user_id) {
            NotificationService::send(
                $request->target_user_id,
                'New ' . $itemType . ' from Super Admin',
                'Subject: ' . $request->subject,
                [
                    'type' => $notificationType,
                    'complaint_id' => $complaint->id,
                    'link' => route('admin.complaints.index'),
                ]
            );
        }

        return redirect()->route('superadmin.complaints.index')
            ->with('success', ucfirst($itemType) . ' submitted successfully.');
    }

    public function adminComplaints()
    {
        $complaints = Complain::where('complaint_type', 'ADMIN_TO_SUPERADMIN')
            ->with(['admin', 'superAdmin'])
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('blades.superadmin.admin-complaints', compact('complaints'));
    }

    public function viewAdminComplaint($id)
    {
        $complaint = Complain::where('complaint_type', 'ADMIN_TO_SUPERADMIN')
            ->with(['admin', 'superAdmin'])
            ->findOrFail($id);

        return view('blades.superadmin.view-admin-complaint', compact('complaint'));
    }

    public function respondToAdminComplaint(Request $request, $id)
    {
        $request->validate([
            'super_admin_response' => 'required|string',
            'super_admin_response_images.*' => 'nullable|image|mimes:jpg,jpeg,png',
        ], [
            'super_admin_response_images.*.max' => 'Each image must not be larger than 10MB.',
            'super_admin_response_images.*.image' => 'Each file must be an image.',
        ]);

        $complaint = Complain::where('complaint_type', 'ADMIN_TO_SUPERADMIN')
            ->findOrFail($id);

        $superAdminResponseImages = [];
        if ($request->hasFile('super_admin_response_images')) {
            $uploadedImages = $request->file('super_admin_response_images');
            if (count($uploadedImages) > 5) {
                return redirect()->back()->withErrors(['super_admin_response_images' => 'You can upload maximum 5 images.'])->withInput();
            }
            
            foreach ($uploadedImages as $image) {
                $superAdminResponseImages[] = $image->store('superadmin_complaint_responses', 'public');
            }
        }

        $complaint->super_admin_response = $request->super_admin_response;
        $complaint->super_admin_response_images = !empty($superAdminResponseImages) ? $superAdminResponseImages : null;
        $complaint->super_admin_id = auth()->id();
        $complaint->status = 'RESOLVED';
        $complaint->save();

        $complaint->loadMissing('admin');
        if ($complaint->admin) {
            NotificationService::send(
                $complaint->admin,
                'Super admin responded',
                'Super admin replied to your report "' . $complaint->subject . '".',
                [
                    'type' => 'super_admin_response',
                    'complaint_id' => $complaint->id,
                    'link' => route('admin.complaints.sent'),
                ]
            );
        }

        return redirect()->route('superadmin.complaints.index')->with('success', 'Response sent successfully');
    }

    public function profile()
    {
        $user = auth()->user();
        return view('blades.superadmin.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png',
            'password' => 'nullable|min:8|confirmed',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;

        if ($request->hasFile('profile_photo')) {
            // Delete old photo if exists
            if ($user->profile_photo_path && \Storage::disk('public')->exists($user->profile_photo_path)) {
                \Storage::disk('public')->delete($user->profile_photo_path);
            }
            
            $path = $request->file('profile_photo')->store('profile_photos', 'public');
            $user->profile_photo_path = $path;
        }

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('superadmin.profile')->with('success', 'Profile updated successfully!');
    }

    public function allWilayas()
    {
        $wilayas = [
            1 => 'Adrar', 2 => 'Chlef', 3 => 'Laghouat', 4 => 'Oum El Bouaghi', 5 => 'Batna',
            6 => 'Béjaïa', 7 => 'Biskra', 8 => 'Béchar', 9 => 'Blida', 10 => 'Bouira',
            11 => 'Tamanrasset', 12 => 'Tébessa', 13 => 'Tlemcen', 14 => 'Tiaret', 15 => 'Tizi Ouzou',
            16 => 'Alger', 17 => 'Djelfa', 18 => 'Jijel', 19 => 'Setif', 20 => 'Saïda',
            21 => 'Skikda', 22 => 'Sidi Bel Abbès', 23 => 'Annaba', 24 => 'Guelma', 25 => 'Constantine',
            26 => 'Médéa', 27 => 'Mostaganem', 28 => 'Msila', 29 => 'Mascara', 30 => 'Ouargla',
            31 => 'Oran', 32 => 'El Bayadh', 33 => 'Illizi', 34 => 'Bordj Bou Arréridj', 35 => 'Boumerdès',
            36 => 'El Tarf', 37 => 'Tindouf', 38 => 'Tissemsilt', 39 => 'El Oued', 40 => 'Khenchela',
            41 => 'Souk Ahras', 42 => 'Tipaza', 43 => 'Mila', 44 => 'Aïn Defla', 45 => 'Naâma',
            46 => 'Aïn Témouchent', 47 => 'Ghardaïa', 48 => 'Relizane', 49 => 'Timimoun', 50 => 'Bordj Badji Mokhtar',
            51 => 'Ouled Djellal', 52 => 'Béni Abbès', 53 => 'In Salah', 54 => 'In Guezzam', 55 => 'Touggourt',
            56 => 'Djanet', 57 => 'El M\'Ghair', 58 => 'El Meniaa'
        ];

        // Get all agencies
        $allAgencies = Agency::with(['admin', 'gestionnaires', 'agents', 'artists', 'wallet'])->get();

        // Helper function to normalize strings (remove accents, lowercase, trim)
        $normalizeString = function($str) {
            if (empty($str)) return '';
            
            // Convert to lowercase and trim
            $str = strtolower(trim($str));
            
            // Replace common accent variations manually
            $replacements = [
                'é' => 'e', 'è' => 'e', 'ê' => 'e', 'ë' => 'e',
                'à' => 'a', 'â' => 'a', 'ä' => 'a',
                'î' => 'i', 'ï' => 'i',
                'ô' => 'o', 'ö' => 'o',
                'ù' => 'u', 'û' => 'u', 'ü' => 'u',
                'ç' => 'c',
                'ñ' => 'n',
            ];
            
            foreach ($replacements as $accent => $normal) {
                $str = str_replace($accent, $normal, $str);
            }
            
            // Remove extra spaces
            $str = preg_replace('/\s+/', ' ', $str);
            
            return $str;
        };

        // Create mapping of normalized names to handle variations
        $wilayaVariations = [
            'alger' => ['alger', 'algiers'],
            'beni abbes' => ['beni abbes', 'beni abbes'],
            'bejaia' => ['bejaia', 'bejaia'],
            'tebessa' => ['tebessa', 'tebessa'],
            'medea' => ['medea', 'medea'],
            'ain defla' => ['ain defla', 'ain defla'],
            'naama' => ['naama', 'naama'],
            'ain temouchent' => ['ain temouchent', 'ain temouchent'],
            'ghardaia' => ['ghardaia', 'ghardaia'],
            'bordj bou arreridj' => ['bordj bou arreridj', 'bordj bou arreridj'],
            'boumerdes' => ['boumerdes', 'boumerdes'],
            'saida' => ['saida', 'saida'],
            'sidi bel abbes' => ['sidi bel abbes', 'sidi bel abbes'],
            'el meniaa' => ['el meniaa', 'el menia'],
            'msila' => ['msila', 'm\'sila', 'msila'],
        ];

        // Normalize wilaya names for matching (handle variations)
        $normalizedWilayas = [];
        foreach ($wilayas as $code => $name) {
            $normalizedWilayas[$code] = [
                'name' => $name,
                'has_agencies' => false,
                'agencies_count' => 0
            ];
            
            // Normalize wilaya name
            $normalizedName = $normalizeString($name);
            
            // Get all possible variations for this wilaya
            $variations = [$normalizedName];
            if (isset($wilayaVariations[$normalizedName])) {
                $variations = array_merge($variations, $wilayaVariations[$normalizedName]);
            }
            
            // Check if this wilaya has agencies
            $matchingAgencies = $allAgencies->filter(function($agency) use ($variations, $normalizeString, $normalizedName) {
                $normalizedAgencyWilaya = $normalizeString($agency->wilaya);
                
                // Direct exact match
                if ($normalizedAgencyWilaya === $normalizedName) {
                    return true;
                }
                
                // Check if agency wilaya matches any variation
                foreach ($variations as $variation) {
                    if ($normalizedAgencyWilaya === $variation) {
                        return true;
                    }
                }
                
                // Use similarity check for fuzzy matching (handles minor differences)
                similar_text($normalizedAgencyWilaya, $normalizedName, $percent);
                if ($percent > 90) { // 90% similarity threshold
                    return true;
                }
                
                // Check if one contains the other (for cases like "El Menia" vs "El Meniaa")
                $nameWords = explode(' ', $normalizedName);
                $agencyWords = explode(' ', $normalizedAgencyWilaya);
                
                // If most words match, consider it a match
                $matchingWords = 0;
                foreach ($nameWords as $word) {
                    if (in_array($word, $agencyWords) && strlen($word) > 2) {
                        $matchingWords++;
                    }
                }
                
                if (count($nameWords) > 0 && ($matchingWords / count($nameWords)) > 0.7) {
                    return true;
                }
                
                return false;
            });
            
            if ($matchingAgencies->count() > 0) {
                $normalizedWilayas[$code]['has_agencies'] = true;
                $normalizedWilayas[$code]['agencies_count'] = $matchingAgencies->count();
            }
        }

        return view('blades.superadmin.all-wilayas', compact('normalizedWilayas'));
    }

    public function showWilaya($wilayaCode)
    {
        $wilayas = [
            1 => 'Adrar', 2 => 'Chlef', 3 => 'Laghouat', 4 => 'Oum El Bouaghi', 5 => 'Batna',
            6 => 'Béjaïa', 7 => 'Biskra', 8 => 'Béchar', 9 => 'Blida', 10 => 'Bouira',
            11 => 'Tamanrasset', 12 => 'Tébessa', 13 => 'Tlemcen', 14 => 'Tiaret', 15 => 'Tizi Ouzou',
            16 => 'Alger', 17 => 'Djelfa', 18 => 'Jijel', 19 => 'Setif', 20 => 'Saïda',
            21 => 'Skikda', 22 => 'Sidi Bel Abbès', 23 => 'Annaba', 24 => 'Guelma', 25 => 'Constantine',
            26 => 'Médéa', 27 => 'Mostaganem', 28 => 'Msila', 29 => 'Mascara', 30 => 'Ouargla',
            31 => 'Oran', 32 => 'El Bayadh', 33 => 'Illizi', 34 => 'Bordj Bou Arréridj', 35 => 'Boumerdès',
            36 => 'El Tarf', 37 => 'Tindouf', 38 => 'Tissemsilt', 39 => 'El Oued', 40 => 'Khenchela',
            41 => 'Souk Ahras', 42 => 'Tipaza', 43 => 'Mila', 44 => 'Aïn Defla', 45 => 'Naâma',
            46 => 'Aïn Témouchent', 47 => 'Ghardaïa', 48 => 'Relizane', 49 => 'Timimoun', 50 => 'Bordj Badji Mokhtar',
            51 => 'Ouled Djellal', 52 => 'Béni Abbès', 53 => 'In Salah', 54 => 'In Guezzam', 55 => 'Touggourt',
            56 => 'Djanet', 57 => 'El M\'Ghair', 58 => 'El Meniaa'
        ];

        if (!isset($wilayas[$wilayaCode])) {
            abort(404, 'Wilaya not found');
        }

        $wilayaName = $wilayas[$wilayaCode];

        // Helper function to normalize strings (same as in allWilayas)
        $normalizeString = function($str) {
            if (empty($str)) return '';
            
            $str = strtolower(trim($str));
            
            $replacements = [
                'é' => 'e', 'è' => 'e', 'ê' => 'e', 'ë' => 'e',
                'à' => 'a', 'â' => 'a', 'ä' => 'a',
                'î' => 'i', 'ï' => 'i',
                'ô' => 'o', 'ö' => 'o',
                'ù' => 'u', 'û' => 'u', 'ü' => 'u',
                'ç' => 'c',
                'ñ' => 'n',
            ];
            
            foreach ($replacements as $accent => $normal) {
                $str = str_replace($accent, $normal, $str);
            }
            
            $str = preg_replace('/\s+/', ' ', $str);
            
            return $str;
        };

        // Normalize wilaya name
        $normalizedWilayaName = $normalizeString($wilayaName);

        // Get all agencies and filter using the same matching logic
        $allAgencies = Agency::with(['admin', 'gestionnaires', 'agents.user', 'artists.user', 'wallet'])->get();

        // Find agencies in this wilaya using the same matching logic as allWilayas
        $agencies = $allAgencies->filter(function($agency) use ($normalizedWilayaName, $normalizeString) {
            $normalizedAgencyWilaya = $normalizeString($agency->wilaya);
            
            // Direct exact match
            if ($normalizedAgencyWilaya === $normalizedWilayaName) {
                return true;
            }
            
            // Use similarity check for fuzzy matching
            similar_text($normalizedAgencyWilaya, $normalizedWilayaName, $percent);
            if ($percent > 90) {
                return true;
            }
            
            // Check if most words match
            $nameWords = explode(' ', $normalizedWilayaName);
            $agencyWords = explode(' ', $normalizedAgencyWilaya);
            
            $matchingWords = 0;
            foreach ($nameWords as $word) {
                if (in_array($word, $agencyWords) && strlen($word) > 2) {
                    $matchingWords++;
                }
            }
            
            if (count($nameWords) > 0 && ($matchingWords / count($nameWords)) > 0.7) {
                return true;
            }
            
            return false;
        });

        // Get all users in this wilaya (through agencies)
        $agencyIds = $agencies->pluck('id');
        
        $admins = User::whereIn('agency_id', $agencyIds)
            ->whereHas('roles', function($q) {
                $q->where('name', 'admin');
            })
            ->with('agency')
            ->get();

        $gestionnaires = User::whereIn('agency_id', $agencyIds)
            ->whereHas('roles', function($q) {
                $q->where('name', 'gestionnaire');
            })
            ->with('agency')
            ->get();

        $agents = Agent::whereIn('agency_id', $agencyIds)
            ->with(['user', 'agency'])
            ->get();

        $artists = Artist::whereIn('agency_id', $agencyIds)
            ->with(['user', 'agency'])
            ->get();

        // Statistics
        $stats = [
            'agencies_count' => $agencies->count(),
            'admins_count' => $admins->count(),
            'gestionnaires_count' => $gestionnaires->count(),
            'agents_count' => $agents->count(),
            'artists_count' => $artists->count(),
            'total_wallet_balance' => $agencies->sum(function($agency) {
                return $agency->wallet->balance ?? 0;
            })
        ];

        return view('blades.superadmin.show-wilaya', compact(
            'wilayaCode',
            'wilayaName',
            'agencies',
            'admins',
            'gestionnaires',
            'agents',
            'artists',
            'stats'
        ));
    }

    // Law Management
    public function manageLaw()
    {
        $englishLaw = Law::where('language', 'english')->first();
        $arabicLaw = Law::where('language', 'arabic')->first();
        $frenchLaw = Law::where('language', 'french')->first();

        // If no laws exist, create default ones
        if (!$englishLaw) {
            $englishLaw = $this->createDefaultEnglishLaw();
        }
        if (!$arabicLaw) {
            $arabicLaw = $this->createDefaultArabicLaw();
        }
        if (!$frenchLaw) {
            $frenchLaw = $this->createDefaultFrenchLaw();
        }

        return view('blades.superadmin.manage-law', compact('englishLaw', 'arabicLaw', 'frenchLaw'));
    }

    public function updateLaw(Request $request)
    {
        $request->validate([
            'language' => 'required|in:english,arabic,french',
            'title' => 'required|string|max:255',
            'notice' => 'required|string',
        ]);

        // Parse sections from form fields
        $sections = [];
        $index = 0;
        while ($request->has("sections.{$index}.title")) {
            $section = [
                'title' => $request->input("sections.{$index}.title"),
                'content' => $request->input("sections.{$index}.content"),
            ];
            
            if ($request->has("sections.{$index}.formula") && $request->input("sections.{$index}.formula")) {
                $section['formula'] = $request->input("sections.{$index}.formula");
            }
            
            // Check for items array first, then items_text
            if ($request->has("sections.{$index}.items") && is_array($request->input("sections.{$index}.items"))) {
                $items = array_filter(
                    array_map('trim', $request->input("sections.{$index}.items")),
                    function($item) { return !empty($item); }
                );
                if (!empty($items)) {
                    $section['items'] = array_values($items);
                }
            } elseif ($request->has("sections.{$index}.items_text")) {
                $itemsText = $request->input("sections.{$index}.items_text");
                if ($itemsText) {
                    $items = array_filter(
                        array_map('trim', explode("\n", $itemsText)),
                        function($item) { return !empty($item); }
                    );
                    if (!empty($items)) {
                        $section['items'] = array_values($items);
                    }
                }
            }
            
            if ($request->has("sections.{$index}.highlight") && $request->input("sections.{$index}.highlight") == '1') {
                $section['highlight'] = true;
            }
            
            $sections[] = $section;
            $index++;
        }

        if (empty($sections)) {
            return redirect()->back()->withErrors(['sections' => 'At least one section is required.'])->withInput();
        }

        $law = Law::updateOrCreate(
            ['language' => $request->language],
            [
                'title' => $request->title,
                'notice' => $request->notice,
                'sections' => $sections,
            ]
        );

        return redirect()->route('superadmin.manage-law')->with('success', 'Law content updated successfully');
    }

    private function createDefaultEnglishLaw()
    {
        $baseRate = config('artrights.base_rate', 200);
        return Law::create([
            'language' => 'english',
            'title' => 'Copyright Protection Law - English',
            'notice' => 'Important Notice: This document explains your rights and protections as an artist under copyright law. Please read carefully.',
            'sections' => [
                [
                    'title' => 'Your Rights as an Artist',
                    'content' => 'As an artist, your creative works (music, images, videos, etc.) are automatically protected by copyright law from the moment of creation.',
                ],
                [
                    'title' => 'What is Protected?',
                    'content' => 'Your original artistic works are protected by copyright. You have the exclusive right to reproduce, distribute, and display your works. Others must obtain permission before using your works. You are entitled to compensation when your works are used commercially.',
                    'items' => [
                        'Your original artistic works are protected by copyright',
                        'You have the exclusive right to reproduce, distribute, and display your works',
                        'Others must obtain permission before using your works',
                        'You are entitled to compensation when your works are used commercially'
                    ]
                ],
                [
                    'title' => 'How Compensation is Calculated',
                    'content' => 'When your artwork is used, compensation is calculated using the following formula:',
                    'formula' => 'Compensation = {(Category Coefficient) × (Device Coefficient) × (Hours/Count) × Base Rate}',
                    'items' => [
                        'Category Coefficient: Depends on the type of artwork (music, image, video, etc.)',
                        'Device Coefficient: Depends on the device type where your work is used (public, commercial, personal)',
                        'Hours/Count: Duration of use (for audio/video) or number of uses (for images)',
                        'Base Rate: ' . number_format($baseRate, 2) . ' DZD (current rate)'
                    ]
                ],
                [
                    'title' => 'Your Responsibilities',
                    'content' => 'As an artist, you have certain responsibilities:',
                    'items' => [
                        'Register your artworks through this platform to ensure protection',
                        'Pay the platform tax to activate your registered artworks',
                        'Report any unauthorized use of your works through the complaint system',
                        'Keep records of your registered artworks and their status'
                    ]
                ],
                [
                    'title' => 'Your Protection',
                    'content' => 'By registering your artworks on this platform, you are taking an important step to protect your intellectual property rights and ensure you receive fair compensation for the use of your creative works.',
                    'highlight' => true
                ]
            ]
        ]);
    }

    private function createDefaultArabicLaw()
    {
        $baseRate = config('artrights.base_rate', 200);
        return Law::create([
            'language' => 'arabic',
            'title' => 'قانون حماية حقوق المؤلف - العربية',
            'notice' => 'إشعار مهم: يوضح هذا المستند حقوقك وحمايتك كفنان بموجب قانون حقوق المؤلف. يرجى القراءة بعناية.',
            'sections' => [
                [
                    'title' => 'حقوقك كفنان',
                    'content' => 'كفنان، أعمالك الإبداعية (الموسيقى، الصور، الفيديوهات، إلخ) محمية تلقائياً بموجب قانون حقوق المؤلف من لحظة الإنشاء.',
                ],
                [
                    'title' => 'ما الذي يتم حمايته؟',
                    'content' => 'أعمالك الفنية الأصلية محمية بموجب حقوق المؤلف. لديك الحق الحصري في استنساخ وتوزيع وعرض أعمالك. يجب على الآخرين الحصول على إذن قبل استخدام أعمالك. أنت مستحق للتعويض عند استخدام أعمالك تجارياً.',
                    'items' => [
                        'أعمالك الفنية الأصلية محمية بموجب حقوق المؤلف',
                        'لديك الحق الحصري في استنساخ وتوزيع وعرض أعمالك',
                        'يجب على الآخرين الحصول على إذن قبل استخدام أعمالك',
                        'أنت مستحق للتعويض عند استخدام أعمالك تجارياً'
                    ]
                ],
                [
                    'title' => 'كيف يتم حساب التعويض',
                    'content' => 'عند استخدام عملك الفني، يتم حساب التعويض باستخدام الصيغة التالية:',
                    'formula' => 'التعويض = {(معامل الفئة) × (معامل الجهاز) × (الساعات/العدد) × السعر الأساسي}',
                    'items' => [
                        'معامل الفئة: يعتمد على نوع العمل الفني (موسيقى، صورة، فيديو، إلخ)',
                        'معامل الجهاز: يعتمد على نوع الجهاز الذي يتم استخدام عملك عليه (عام، تجاري، شخصي)',
                        'الساعات/العدد: مدة الاستخدام (للصوت/الفيديو) أو عدد الاستخدامات (للصور)',
                        'السعر الأساسي: ' . number_format($baseRate, 2) . ' دج (السعر الحالي)'
                    ]
                ],
                [
                    'title' => 'مسؤولياتك',
                    'content' => 'كفنان، لديك مسؤوليات معينة:',
                    'items' => [
                        'سجل أعمالك الفنية من خلال هذه المنصة لضمان الحماية',
                        'ادفع ضريبة المنصة لتفعيل أعمالك المسجلة',
                        'أبلغ عن أي استخدام غير مصرح به لأعمالك من خلال نظام الشكاوى',
                        'احتفظ بسجلات لأعمالك المسجلة وحالتها'
                    ]
                ],
                [
                    'title' => 'حمايتك',
                    'content' => 'من خلال تسجيل أعمالك الفنية على هذه المنصة، تتخذ خطوة مهمة لحماية حقوق الملكية الفكرية الخاصة بك وضمان حصولك على تعويض عادل لاستخدام أعمالك الإبداعية.',
                    'highlight' => true
                ]
            ]
        ]);
    }

    private function createDefaultFrenchLaw()
    {
        $baseRate = config('artrights.base_rate', 200);
        return Law::create([
            'language' => 'french',
            'title' => 'Loi sur la Protection du Droit d\'Auteur - Français',
            'notice' => 'Avis Important : Ce document explique vos droits et protections en tant qu\'artiste sous le droit d\'auteur. Veuillez lire attentivement.',
            'sections' => [
                [
                    'title' => 'Vos Droits en tant qu\'Artiste',
                    'content' => 'En tant qu\'artiste, vos œuvres créatives (musique, images, vidéos, etc.) sont automatiquement protégées par le droit d\'auteur dès le moment de leur création.',
                ],
                [
                    'title' => 'Qu\'est-ce qui est Protégé ?',
                    'content' => 'Vos œuvres artistiques originales sont protégées par le droit d\'auteur. Vous avez le droit exclusif de reproduire, distribuer et afficher vos œuvres. Les autres doivent obtenir une permission avant d\'utiliser vos œuvres. Vous avez droit à une compensation lorsque vos œuvres sont utilisées commercialement.',
                    'items' => [
                        'Vos œuvres artistiques originales sont protégées par le droit d\'auteur',
                        'Vous avez le droit exclusif de reproduire, distribuer et afficher vos œuvres',
                        'Les autres doivent obtenir une permission avant d\'utiliser vos œuvres',
                        'Vous avez droit à une compensation lorsque vos œuvres sont utilisées commercialement'
                    ]
                ],
                [
                    'title' => 'Comment la Compensation est Calculée',
                    'content' => 'Lorsque votre œuvre est utilisée, la compensation est calculée à l\'aide de la formule suivante :',
                    'formula' => 'Compensation = {(Coefficient de Catégorie) × (Coefficient d\'Appareil) × (Heures/Nombre) × Taux de Base}',
                    'items' => [
                        'Coefficient de Catégorie : Dépend du type d\'œuvre (musique, image, vidéo, etc.)',
                        'Coefficient d\'Appareil : Dépend du type d\'appareil où votre œuvre est utilisée (public, commercial, personnel)',
                        'Heures/Nombre : Durée d\'utilisation (pour audio/vidéo) ou nombre d\'utilisations (pour images)',
                        'Taux de Base : ' . number_format($baseRate, 2) . ' DZD (taux actuel)'
                    ]
                ],
                [
                    'title' => 'Vos Responsabilités',
                    'content' => 'En tant qu\'artiste, vous avez certaines responsabilités :',
                    'items' => [
                        'Enregistrez vos œuvres via cette plateforme pour assurer la protection',
                        'Payez la taxe de plateforme pour activer vos œuvres enregistrées',
                        'Signalez toute utilisation non autorisée de vos œuvres via le système de plaintes',
                        'Conservez des registres de vos œuvres enregistrées et de leur statut'
                    ]
                ],
                [
                    'title' => 'Votre Protection',
                    'content' => 'En enregistrant vos œuvres sur cette plateforme, vous prenez une étape importante pour protéger vos droits de propriété intellectuelle et assurer que vous recevez une compensation équitable pour l\'utilisation de vos œuvres créatives.',
                    'highlight' => true
                ]
            ]
        ]);
    }
}

