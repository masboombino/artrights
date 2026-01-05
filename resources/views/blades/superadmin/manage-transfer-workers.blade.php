<x-allthepages-layout pageTitle="Transfer Workers">
    <div style="padding: 5px; margin: 5px;">
        @if(session('success'))
            <div class="alert-success">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error') || $errors->any())
            <div class="alert-error" style="background-color: #fee2e2; border: 2px solid #E76268; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1rem; color: #991b1b;">
                @if(session('error'))
                    <p>{{ session('error') }}</p>
                @endif
                @if($errors->any())
                    <ul style="margin: 0; padding-left: 1.5rem;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>
        @endif

        <div class="page-container">
            <h2 style="color: #193948; font-size: 1.5rem; font-weight: 700; margin-bottom: 1.5rem;">Transfer Workers Between Agencies</h2>
            
            <!-- Filter Form -->
            <div style="background-color: #F3EBDD; padding: 1.5rem; border-radius: 0.5rem; margin-bottom: 1.5rem; border: 2px solid #193948;">
                <form method="GET" action="{{ route('superadmin.manage-transfer-workers') }}" id="filterForm">
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1rem;">
                        <!-- Role Filter -->
                        <div>
                            <label style="display: block; color: #193948; font-weight: 600; margin-bottom: 5px;">Filter by Role:</label>
                            <select name="role" id="role" class="form-input" style="width: 100%; padding: 0.5rem; border: 2px solid #193948; border-radius: 0.5rem;">
                                <option value="">All Roles</option>
                                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="agent" {{ request('role') == 'agent' ? 'selected' : '' }}>Agent</option>
                                <option value="gestionnaire" {{ request('role') == 'gestionnaire' ? 'selected' : '' }}>Gestionnaire</option>
                                <option value="artist" {{ request('role') == 'artist' ? 'selected' : '' }}>Artist</option>
                            </select>
                        </div>

                        <!-- Wilaya Filter -->
                        <div>
                            <label style="display: block; color: #193948; font-weight: 600; margin-bottom: 5px;">Filter by Wilaya:</label>
                            <select name="wilaya" id="wilaya" class="form-input" style="width: 100%; padding: 0.5rem; border: 2px solid #193948; border-radius: 0.5rem;">
                                <option value="">All Wilayas</option>
                                @foreach($availableWilayas as $wilayaName)
                                    <option value="{{ $wilayaName }}" {{ request('wilaya') == $wilayaName ? 'selected' : '' }}>
                                        {{ $wilayaName }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Agency Filter -->
                        <div>
                            <label style="display: block; color: #193948; font-weight: 600; margin-bottom: 5px;">Filter by Agency:</label>
                            <select name="agency_id" id="agency_id" class="form-input" style="width: 100%; padding: 0.5rem; border: 2px solid #193948; border-radius: 0.5rem;">
                                <option value="">All Agencies</option>
                                @foreach($agencies as $agency)
                                    <option value="{{ $agency->id }}" {{ request('agency_id') == $agency->id ? 'selected' : '' }}>
                                        {{ $agency->agency_name }} - {{ $agency->wilaya }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div style="display: flex; gap: 1rem; justify-content: center;">
                        <button type="submit" class="inline-block rounded-lg shadow-lg transition hover:opacity-90" style="background-color: #193948; color: #4FADC0; padding: 0.75rem 2rem; font-weight: 600; border: none; cursor: pointer;">
                            Apply Filters
                        </button>
                        <a href="{{ route('superadmin.manage-transfer-workers') }}" class="inline-block rounded-lg shadow-lg transition hover:opacity-90" style="background-color: #193948; color: #4FADC0; padding: 0.75rem 2rem; font-weight: 600; text-decoration: none;">
                            Clear Filters
                        </a>
                    </div>
                </form>
            </div>

            <!-- Results Count -->
            @if(request()->hasAny(['role', 'wilaya', 'agency_id']))
                <div style="background-color: #D6BFBF; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1rem; text-align: center;">
                    <p style="color: #193948; font-weight: 600; font-size: 1.1rem;">
                        Found {{ $users->total() }} result(s)
                    </p>
                </div>
            @endif

            <!-- Users Table -->
            @if($users->count() > 0)
                <div style="overflow-x: auto; width: 100%;">
                    <table class="data-table" style="min-width: 900px;">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Role</th>
                                <th>Current Agency</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->phone ?? 'N/A' }}</td>
                                    <td>
                                        @foreach($user->roles as $role)
                                            <span class="status-badge">{{ ucfirst($role->name) }}</span>
                                        @endforeach
                                    </td>
                                    <td>
                                        @if($user->agency)
                                            {{ $user->agency->agency_name }} - {{ $user->agency->wilaya }}
                                            @if($user->hasRole('admin'))
                                                @php
                                                    $assignedAgency = \App\Models\Agency::where('admin_id', $user->id)->first();
                                                @endphp
                                                @if($assignedAgency && $assignedAgency->id == $user->agency->id)
                                                    <span style="color: #10b981; font-weight: 600; font-size: 0.8rem;"> (Admin)</span>
                                                @endif
                                            @endif
                                        @else
                                            <span style="color: #E76268; font-weight: 600;">No Agency Assigned</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                                            <button onclick="showTransferForm({{ $user->id }})" class="secondary-button" style="padding: 0.5rem 1rem; font-size: 0.9rem;">
                                                {{ $user->agency_id ? 'Transfer' : 'Assign' }}
                                            </button>
                                            <form action="{{ route('superadmin.delete-user', $user->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone!')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="danger-button" style="padding: 0.5rem 1rem; font-size: 0.9rem;">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                        
                                        <!-- Transfer Form -->
                                        <div id="transferForm{{ $user->id }}" class="hidden" style="margin-top: 10px; padding: 15px; background-color: #F3EBDD; border: 2px solid #193948; border-radius: 0.5rem;">
                                            <form action="{{ route('superadmin.transfer-user', $user->id) }}" method="POST">
                                                @csrf
                                                <label style="display: block; color: #193948; font-weight: 600; margin-bottom: 5px;">Select {{ $user->agency_id ? 'New' : '' }} Agency:</label>
                                                <select name="new_agency_id" class="form-input" required style="width: 100%; margin-bottom: 10px; padding: 0.5rem; border: 2px solid #193948; border-radius: 0.5rem;">
                                                    <option value="">Select Agency</option>
                                                    @foreach($agencies as $agency)
                                                        <option value="{{ $agency->id }}" @selected($user->agency_id == $agency->id)>
                                                            {{ $agency->agency_name }} - {{ $agency->wilaya }}
                                                            @if($agency->admin_id && $agency->admin_id != $user->id)
                                                                (Has Admin: {{ $agency->admin->name ?? 'N/A' }})
                                                            @endif
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @if($user->hasRole('admin'))
                                                    <p style="color: #193948; font-size: 0.85rem; margin-bottom: 10px; font-style: italic;">
                                                        Note: Transferring this admin will update the agency's admin assignment.
                                                    </p>
                                                @endif
                                                <div style="display: flex; gap: 5px;">
                                                    <button type="submit" class="inline-block rounded text-sm transition hover:opacity-90" style="background-color: #10b981; color: white; padding: 0.5rem 1rem; font-size: 0.9rem; border: none; cursor: pointer; font-weight: 600;">
                                                        {{ $user->agency_id ? 'Confirm Transfer' : 'Assign' }}
                                                    </button>
                                                    <button type="button" onclick="hideTransferForm({{ $user->id }})" class="inline-block rounded text-sm transition hover:opacity-90" style="background-color: #193948; color: #4FADC0; padding: 0.5rem 1rem; font-size: 0.9rem; border: none; cursor: pointer; font-weight: 600;">
                                                        Cancel
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div style="background-color: #F3EBDD; padding: 3rem; border-radius: 0.5rem; text-align: center; border: 2px solid #193948;">
                    <p style="color: #193948; font-size: 1.2rem; font-weight: 600;">
                        @if(request()->hasAny(['role', 'wilaya', 'agency_id']))
                            No workers found matching the selected filters.
                        @else
                            No workers found.
                        @endif
                    </p>
                </div>
            @endif

            @if($users->hasPages())
                <div style="margin-top: 1.5rem; padding-top: 1rem; border-top: 2px solid rgba(25, 57, 72, 0.1);">
                    {{ $users->links() }}
                </div>
            @endif

            <!-- Create New Admin Form -->
            <div style="background-color: #F3EBDD; padding: 1.5rem; border-radius: 0.5rem; margin-top: 2rem; border: 2px solid #193948;">
                <h3 style="color: #193948; font-size: 1.2rem; font-weight: 700; margin-bottom: 1rem;">Create New Admin</h3>
                <form method="POST" action="{{ route('superadmin.store-admin-from-transfer') }}" id="createAdminForm">
                    @csrf
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1rem;">
                        <div>
                            <label style="display: block; color: #193948; font-weight: 600; margin-bottom: 5px;">Name *</label>
                            <input type="text" name="name" value="{{ old('name') }}" required
                                   style="width: 100%; padding: 0.5rem; border: 2px solid #193948; border-radius: 0.5rem;">
                            @error('name')
                                <p style="color: #E76268; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label style="display: block; color: #193948; font-weight: 600; margin-bottom: 5px;">Email *</label>
                            <input type="email" name="email" value="{{ old('email') }}" required
                                   style="width: 100%; padding: 0.5rem; border: 2px solid #193948; border-radius: 0.5rem;">
                            @error('email')
                                <p style="color: #E76268; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label style="display: block; color: #193948; font-weight: 600; margin-bottom: 5px;">Phone</label>
                            <input type="text" name="phone" value="{{ old('phone') }}"
                                   style="width: 100%; padding: 0.5rem; border: 2px solid #193948; border-radius: 0.5rem;">
                            @error('phone')
                                <p style="color: #E76268; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label style="display: block; color: #193948; font-weight: 600; margin-bottom: 5px;">Wilaya (Without Agency) *</label>
                            <select name="wilaya_code" required
                                    style="width: 100%; padding: 0.5rem; border: 2px solid #193948; border-radius: 0.5rem;">
                                <option value="">Select Wilaya</option>
                                @foreach($wilayasWithoutAgencies as $code => $name)
                                    <option value="{{ $code }}" @selected(old('wilaya_code') == $code)>
                                        {{ $code }} - {{ $name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('wilaya_code')
                                <p style="color: #E76268; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</p>
                            @enderror
                            @if(count($wilayasWithoutAgencies) == 0)
                                <p style="color: #193948; font-size: 0.85rem; margin-top: 0.25rem; font-style: italic;">All wilayas have agencies. A new agency will be created for the selected wilaya.</p>
                            @else
                                <p style="color: #193948; font-size: 0.85rem; margin-top: 0.25rem; font-style: italic;">Select a wilaya without an agency. A new agency will be created automatically.</p>
                            @endif
                        </div>
                        <div>
                            <label style="display: block; color: #193948; font-weight: 600; margin-bottom: 5px;">Password *</label>
                            <input type="password" name="password" required
                                   style="width: 100%; padding: 0.5rem; border: 2px solid #193948; border-radius: 0.5rem;">
                            @error('password')
                                <p style="color: #E76268; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label style="display: block; color: #193948; font-weight: 600; margin-bottom: 5px;">Confirm Password *</label>
                            <input type="password" name="password_confirmation" required
                                   style="width: 100%; padding: 0.5rem; border: 2px solid #193948; border-radius: 0.5rem;">
                        </div>
                    </div>
                    <div style="display: flex; gap: 1rem; justify-content: center;">
                        <button type="submit" class="inline-block rounded-lg shadow-lg transition hover:opacity-90" 
                                style="background-color: #10b981; color: white; padding: 0.75rem 2rem; font-weight: 600; border: none; cursor: pointer;"
                                @if(count($wilayasWithoutAgencies) == 0) disabled @endif>
                            Create Admin
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function showTransferForm(userId) {
            // Hide all other transfer forms first
            document.querySelectorAll('[id^="transferForm"]').forEach(form => {
                form.classList.add('hidden');
            });
            // Show the selected form
            document.getElementById('transferForm' + userId).classList.remove('hidden');
        }

        function hideTransferForm(userId) {
            document.getElementById('transferForm' + userId).classList.add('hidden');
        }

        // Auto-submit form when filters change (optional)
        document.getElementById('filterForm').addEventListener('change', function() {
            // Uncomment the line below if you want auto-filtering on change
            // this.submit();
        });
    </script>
</x-allthepages-layout>
