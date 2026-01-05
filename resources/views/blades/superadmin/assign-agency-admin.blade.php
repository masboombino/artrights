<x-allthepages-layout pageTitle="Assign Admin to Agency">
    <div class="space-y-6">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-3xl font-bold" style="color: #D6BFBF;">Assign Admin to {{ $agency->agency_name }}</h1>
            <a href="{{ route('superadmin.show-agency', $agency->id) }}" class="rounded transition hover:opacity-90" style="background-color: #D6BFBF; color: #193948; padding: 0.75rem 1.5rem;">
                <span>Back</span>
            </a>
        </div>

        <div class="rounded-lg shadow-lg p-6" style="background-color: #F3EBDD; border: 3px solid #193948;">
            <form action="{{ route('superadmin.store-agency-admin', $agency->id) }}" method="POST">
                @csrf
                
                <div class="mb-4">
                    <label for="admin_id" class="block text-sm font-medium mb-2" style="color: #193948;">Select Admin</label>
                    <select name="admin_id" id="admin_id" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        style="background-color: white; color: #193948;">
                        <option value="">-- Select Admin --</option>
                        @foreach($admins as $admin)
                            <option value="{{ $admin->id }}" {{ old('admin_id') == $admin->id ? 'selected' : '' }}>
                                {{ $admin->name }} ({{ $admin->email }})
                                @if($admin->agency_id == $agency->id)
                                    - Current
                                @elseif($admin->agency && $admin->agency_id != $agency->id)
                                    - Currently: {{ $admin->agency->agency_name }} ({{ $admin->agency->wilaya }})
                                @else
                                    - No agency assigned
                                @endif
                            </option>
                        @endforeach
                    </select>
                    @error('admin_id')
                        <p class="mt-1 text-sm" style="color: #E76268;">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-4">
                    <button type="submit" class="rounded transition hover:opacity-90" style="background-color: #D6BFBF; color: #193948; padding: 0.75rem 1.5rem;">
                        <span>Assign Admin</span>
                    </button>
                    <a href="{{ route('superadmin.show-agency', $agency->id) }}" class="rounded transition hover:opacity-90" style="background-color: #6b7280; color: #4FADC0; padding: 0.75rem 1.5rem;">
                        <span>Cancel</span>
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-allthepages-layout>

