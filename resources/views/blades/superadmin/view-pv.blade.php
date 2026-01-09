<x-allthepages-layout pageTitle="PV #{{ $pv->id }}">
    <div class="space-y-6">
        <div class="rounded-lg shadow p-6" style="background-color: #F3EBDD; border: 2px solid #193948;">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="p-4 rounded" style="background-color: #ffffff; border: 2px solid #193948; border-radius: 8px;">
                    <h1 class="text-2xl font-bold mb-2" style="color: #193948;">{{ $pv->shop_name }}</h1>
                    <p class="mb-1" style="color: #36454f;">{{ $pv->shop_type }} — {{ $pv->date_of_inspection?->format('d/m/Y H:i') }}</p>
                    <p class="mb-1" style="color: #36454f;">Agency: {{ $pv->agency->agency_name ?? 'N/A' }}</p>
                    @if($pv->agency && $pv->agency->bank_account_number)
                        <p class="mt-2 text-sm" style="color: #193948;">
                            <strong>🏦 Bank Account Number:</strong> <span style="font-weight: 700; color: #1e40af;">{{ $pv->agency->bank_account_number }}</span>
                        </p>
                    @endif
                    <p style="color: #36454f;">Agent: {{ $pv->agent->user->name ?? 'N/A' }}</p>
                </div>
                <div class="p-4 rounded text-right" style="background-color: #ffffff; border: 2px solid #193948; border-radius: 8px;">
                    <p class="font-semibold mb-1" style="color: #193948;">Status: {{ $pv->status }}</p>
                    <p class="font-semibold mb-1" style="color: #193948;">Payment: {{ $pv->payment_method ?? 'N/A' }} / {{ $pv->payment_status }}</p>
                    <p class="text-2xl font-bold" style="color: #193948;">{{ number_format($pv->total_amount, 2) }} DZD</p>
                </div>
            </div>
        </div>

        @php($evidenceFiles = $pv->evidenceFiles())
        @if(count($evidenceFiles))
            <div class="rounded-lg shadow p-6" style="background-color: #F3EBDD;">
                <h2 class="text-xl font-semibold mb-4" style="color: #193948;">Evidence Photos</h2>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach($evidenceFiles as $file)
                        <a href="{{ \Illuminate\Support\Facades\Storage::url($file) }}" target="_blank" class="block rounded overflow-hidden shadow hover:opacity-90">
                            <img src="{{ \Illuminate\Support\Facades\Storage::url($file) }}" alt="Evidence" style="width: 100%; height: 180px; object-fit: cover;">
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="rounded-lg shadow" style="background-color: #F3EBDD;">
                <div class="px-6 py-4 border-b border-black/10">
                    <h2 class="text-xl font-semibold" style="color: #193948;">Devices</h2>
                </div>
                <div class="divide-y divide-black/10">
                    @forelse($pv->devices as $device)
                        <div class="px-6 py-4">
                            <p class="font-semibold" style="color: #193948;">{{ $device->name }}</p>
                            <p style="color: #193948;">Type: {{ $device->type }} · Coefficient: {{ $device->coefficient }}</p>
                            <p style="color: #193948;">Amount: {{ number_format($device->amount, 2) }} DZD</p>
                        </div>
                    @empty
                        <p class="px-6 py-4 text-center text-sm" style="color: #193948;">No devices listed.</p>
                    @endforelse
                </div>
            </div>

            <div class="rounded-lg shadow" style="background-color: #F3EBDD;">
                <div class="px-6 py-4 border-b border-black/10">
                    <h2 class="text-xl font-semibold" style="color: #193948;">Artworks</h2>
                </div>
                <div class="divide-y divide-black/10">
                    @forelse($pv->artworkUsages as $usage)
                        <div class="px-6 py-4">
                            <p class="font-semibold" style="color: #193948;">{{ $usage->artwork->title }}</p>
                            <p class="text-sm" style="color: #193948;">Artist: {{ $usage->artwork->artist->user->name ?? 'Unknown' }}</p>
                            <p class="text-sm" style="color: #193948;">Hours: {{ $usage->hours_used }} · Plays: {{ $usage->plays_count }}</p>
                            <p class="text-lg font-bold" style="color: #193948;">{{ number_format($usage->fine_amount, 2) }} DZD</p>
                        </div>
                    @empty
                        <p class="px-6 py-4 text-center text-sm" style="color: #193948;">No artworks recorded.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="rounded-lg shadow" style="background-color: #F3EBDD;">
            <div class="px-6 py-4 border-b border-black/10">
                <h2 class="text-xl font-semibold" style="color: #193948;">Transactions</h2>
            </div>
            <div class="divide-y divide-black/10">
                @forelse($pv->transactions as $transaction)
                    <div class="px-6 py-4 flex justify-between">
                        <div>
                            <p class="font-semibold" style="color: #193948;">{{ $transaction->artist->user->name ?? 'N/A' }}</p>
                            <p class="text-sm" style="color: #193948;">Method: {{ $transaction->payment_method }}</p>
                        </div>
                        <p class="text-lg font-bold" style="color: #193948;">{{ number_format($transaction->amount, 2) }} DZD</p>
                    </div>
                @empty
                    <p class="px-6 py-4 text-center text-sm" style="color: #193948;">No transactions yet.</p>
                @endforelse
            </div>
        </div>

        <a href="{{ route('superadmin.manage-pvs') }}" class="inline-block rounded px-4 py-2 font-semibold" style="background-color: #D6BFBF; color: #193948;">
            Back to list
        </a>
    </div>
</x-allthepages-layout>

