<x-allthepages-layout pageTitle="Create New Agency for {{ $wilayaName }}">
    <div class="max-w-4xl mx-auto">
        <div class="rounded-lg shadow p-6" style="background-color: #F3EBDD;">
            <div class="text-center mb-6">
                <h2 class="text-2xl font-bold mb-2" style="color: #193948;">Create New Agency</h2>
                <p class="text-lg" style="color: #D6BFBF;">For Wilaya: <strong>{{ $wilayaCode }} - {{ $wilayaName }}</strong></p>
            </div>
            
            <form action="{{ route('superadmin.store-agency-for-wilaya', $wilayaCode) }}" method="POST">
                @csrf

                <!-- Agency Information Section -->
                <div class="mb-8 p-4 rounded-lg" style="background-color: rgba(255,255,255,0.5);">
                    <h3 class="text-lg font-semibold mb-4" style="color: #193948;">Agency Information</h3>
                    
                    <div class="mb-4">
                        <label for="agency_name" class="block text-sm font-medium mb-2" style="color: #193948;">Agency Name *</label>
                        <input type="text" name="agency_name" id="agency_name" value="{{ old('agency_name') ?: $wilayaName . ' Office' }}" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            style="background-color: white; color: #193948;"
                            placeholder="e.g., {{ $wilayaName }} Office">
                        @error('agency_name')
                            <p class="mt-1 text-sm" style="color: #E76268;">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="bank_account_number" class="block text-sm font-medium mb-2" style="color: #193948;">Bank Account Number</label>
                        <input type="text" name="bank_account_number" id="bank_account_number" value="{{ old('bank_account_number') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            style="background-color: white; color: #193948;"
                            placeholder="e.g., 001234567890123456789012">
                        @error('bank_account_number')
                            <p class="mt-1 text-sm" style="color: #E76268;">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Admin Account Section -->
                <div class="mb-8 p-4 rounded-lg" style="background-color: rgba(255,255,255,0.5);">
                    <h3 class="text-lg font-semibold mb-4" style="color: #193948;">Admin Account</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="mb-4">
                            <label for="admin_name" class="block text-sm font-medium mb-2" style="color: #193948;">Admin Name *</label>
                            <input type="text" name="admin_name" id="admin_name" value="{{ old('admin_name') }}" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                style="background-color: white; color: #193948;">
                            @error('admin_name')
                                <p class="mt-1 text-sm" style="color: #E76268;">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="admin_email" class="block text-sm font-medium mb-2" style="color: #193948;">Admin Email *</label>
                            <input type="email" name="admin_email" id="admin_email" value="{{ old('admin_email') }}" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                style="background-color: white; color: #193948;">
                            @error('admin_email')
                                <p class="mt-1 text-sm" style="color: #E76268;">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="mb-4">
                            <label for="admin_phone" class="block text-sm font-medium mb-2" style="color: #193948;">Admin Phone</label>
                            <input type="text" name="admin_phone" id="admin_phone" value="{{ old('admin_phone') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                style="background-color: white; color: #193948;">
                            @error('admin_phone')
                                <p class="mt-1 text-sm" style="color: #E76268;">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="admin_password" class="block text-sm font-medium mb-2" style="color: #193948;">Password *</label>
                            <input type="password" name="admin_password" id="admin_password" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                style="background-color: white; color: #193948;">
                            @error('admin_password')
                                <p class="mt-1 text-sm" style="color: #E76268;">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="admin_password_confirmation" class="block text-sm font-medium mb-2" style="color: #193948;">Confirm Password *</label>
                        <input type="password" name="admin_password_confirmation" id="admin_password_confirmation" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            style="background-color: white; color: #193948;">
                    </div>
                </div>

                @error('error')
                    <div class="mb-4 p-3 rounded" style="background-color: #E76268; color: white;">
                        {{ $message }}
                    </div>
                @enderror

                <div class="flex gap-4">
                    <button type="submit" class="rounded transition hover:opacity-90" style="background-color: #193948; color: #4FADC0; padding: 1rem 2rem;">
                        <span style="padding: 0 0.5rem;">Create Agency</span>
                    </button>
                    <a href="{{ route('superadmin.show-wilaya', $wilayaCode) }}" class="rounded transition hover:opacity-90" style="background-color: #6c757d; color: white; padding: 1rem 2rem;">
                        <span style="padding: 0 0.5rem;">Cancel</span>
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-allthepages-layout>