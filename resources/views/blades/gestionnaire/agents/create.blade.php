<x-allthepages-layout pageTitle="Add Agent">
    <div class="space-y-6">
        <div class="rounded-lg shadow p-6" style="background-color: #F3EBDD;">
            <form method="POST" action="{{ route('gestionnaire.agents.store') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-semibold mb-1" style="color: #193948;">Full Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="w-full rounded border p-2" required>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold mb-1" style="color: #193948;">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="w-full rounded border p-2" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-1" style="color: #193948;">Phone</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" class="w-full rounded border p-2">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-1" style="color: #193948;">Badge Number</label>
                    <input type="text" name="badge_number" value="{{ old('badge_number') }}" class="w-full rounded border p-2">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold mb-1" style="color: #193948;">Password</label>
                        <input type="password" name="password" class="w-full rounded border p-2" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-1" style="color: #193948;">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="w-full rounded border p-2" required>
                    </div>
                </div>

                <div class="flex justify-end gap-3">
                    <a href="{{ route('gestionnaire.agents.index') }}" class="rounded border px-4 py-2 font-semibold" style="color: #193948; border-color: #193948;">Cancel</a>
                    <button type="submit" class="rounded px-4 py-2 font-semibold" style="background-color: #193948; color: #4FADC0;">Create Agent</button>
                </div>
            </form>
        </div>
    </div>
</x-allthepages-layout>

