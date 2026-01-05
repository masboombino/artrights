<x-allthepages-layout pageTitle="Manage Gestionnaires">
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
            <a href="{{ route('superadmin.create-gestionnaire') }}" class="rounded transition hover:opacity-90 whitespace-nowrap" style="background-color: #D6BFBF; color: #193948; padding: 1rem 2rem;">
                <span style="padding: 0 0.5rem;">Create New Gestionnaire</span>
            </a>
        </div>

        @if(session('success'))
            <div class="p-4 rounded mb-4" style="background-color: #F3EBDD; color: #193948;">
                {{ session('success') }}
            </div>
        @endif

        <div class="rounded-lg shadow" style="background-color: #F3EBDD;">
            <div style="overflow-x: auto; width: 100%;">
                <table class="w-full" style="border-collapse: collapse; min-width: 700px;">
                    <thead>
                        <tr style="background-color: #193948;">
                            <th class="text-center text-xs font-medium uppercase tracking-wider" style="color: #4FADC0; padding: 1rem 1.5rem;">Name</th>
                            <th class="text-center text-xs font-medium uppercase tracking-wider" style="color: #4FADC0; padding: 1rem 1.5rem;">Email</th>
                            <th class="text-center text-xs font-medium uppercase tracking-wider" style="color: #4FADC0; padding: 1rem 1.5rem;">Phone</th>
                            <th class="text-center text-xs font-medium uppercase tracking-wider" style="color: #4FADC0; padding: 1rem 1.5rem;">Agency</th>
                            <th class="text-center text-xs font-medium uppercase tracking-wider" style="color: #4FADC0; padding: 1rem 1.5rem;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($gestionnaires as $gestionnaire)
                            <tr style="border-top: 1px solid rgba(0,0,0,0.1);">
                                <td class="text-center text-sm whitespace-nowrap" style="color: #193948; padding: 1rem 1.5rem;">{{ $gestionnaire->name }}</td>
                                <td class="text-center text-sm whitespace-nowrap" style="color: #193948; padding: 1rem 1.5rem;">{{ $gestionnaire->email }}</td>
                                <td class="text-center text-sm whitespace-nowrap" style="color: #193948; padding: 1rem 1.5rem;">{{ $gestionnaire->phone ?? 'N/A' }}</td>
                                <td class="text-center text-sm whitespace-nowrap" style="color: #193948; padding: 1rem 1.5rem;">{{ $gestionnaire->agency ? $gestionnaire->agency->agency_name . ' - ' . $gestionnaire->agency->wilaya : 'N/A' }}</td>
                                <td class="text-center text-sm whitespace-nowrap" style="padding: 1rem 1.5rem;">
                                    <form action="{{ route('superadmin.remove-gestionnaire', $gestionnaire->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-block rounded text-sm transition hover:opacity-90" style="background-color: #D6BFBF; color: #193948; padding: 0.5rem 1rem;" onclick="return confirm('Are you sure you want to remove this gestionnaire?')">
                                            <span style="padding: 0 0.25rem;">Remove</span>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-sm" style="color: #193948; padding: 1.5rem;">No gestionnaires found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($gestionnaires->hasPages())
            <div style="margin-top: 1.5rem; padding-top: 1rem; border-top: 2px solid rgba(25, 57, 72, 0.1);">
                {{ $gestionnaires->links() }}
            </div>
        @endif
    </div>
</x-allthepages-layout>
