<x-allthepages-layout pageTitle="PV Details">
    <div class="space-y-6">
        <div class="rounded-lg shadow p-6" style="background-color: #F3EBDD; border: 2px solid #193948;">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="p-4 rounded" style="background-color: #ffffff; border: 2px solid #193948; border-radius: 8px;">
                    <h1 class="text-2xl font-bold mb-2" style="color: #193948;">{{ $pv->shop_name }}</h1>
                    <p class="mb-1" style="color: #36454f;">{{ $pv->shop_type }} · {{ $pv->date_of_inspection?->format('d/m/Y H:i') }}</p>
                    <p style="color: #36454f;">Agent: {{ $pv->agent->user->name ?? 'N/A' }} · Badge: {{ $pv->agent->badge_number ?? 'N/A' }}</p>
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
                @include('blades.partials.complaint-gallery', [
                    'galleryId' => 'gestionnaire-pv-'.$pv->id,
                    'images' => $evidenceFiles,
                    'useStorageRoute' => true,
                ])
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="rounded-lg shadow" style="background-color: #F3EBDD;">
                <div class="px-6 py-4 border-b border-black/10">
                    <h2 class="text-xl font-semibold" style="color: #193948;">Devices</h2>
                </div>
                <div class="divide-y divide-black/10">
                    @forelse($pv->devices as $device)
                        <div class="px-6 py-4">
                            <p class="font-semibold" style="color: #193948;">{{ $device->name }} ({{ $device->type }})</p>
                            <p style="color: #193948;">Coefficient: {{ $device->coefficient }} · Quantity: {{ $device->quantity }}</p>
                            <p style="color: #193948;">Fine share: {{ number_format($device->amount, 2) }} DZD</p>
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
                            <p class="text-sm" style="color: #193948;">
                                Artist: {{ $usage->artwork->artist->user->name ?? 'Unknown' }} · Hours: {{ $usage->hours_used }} · Plays: {{ $usage->plays_count }}
                            </p>
                            <p class="text-sm" style="color: #193948;">
                                Device: {{ $usage->device->name ?? 'N/A' }}
                            </p>
                            <p class="text-lg font-bold" style="color: #193948;">{{ number_format($usage->fine_amount, 2) }} DZD</p>
                        </div>
                    @empty
                        <p class="px-6 py-4 text-center text-sm" style="color: #193948;">No artworks connected.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @if(!$pv->agent_payment_confirmed)
                <div class="rounded-lg shadow p-6" style="background-color: #FDE68A; border: 2px solid #F59E0B;">
                    <h3 class="text-xl font-semibold mb-2" style="color: #92400E;">⏳ Waiting for Agent</h3>
                    <p class="text-sm" style="color: #92400E;">The agent must confirm payment receipt from client before you can validate and add to wallet.</p>
                    <p class="text-xs mt-2" style="color: #92400E;">PV Total Amount: {{ number_format($pv->total_amount, 2) }} DZD</p>
                </div>
            @elseif($pv->payment_status !== 'VALIDATED')
                <div class="rounded-lg shadow p-6" style="background-color: #F3EBDD;">
                    <h3 class="text-xl font-semibold mb-4" style="color: #193948;">Validate Payment & Add to Wallet</h3>
                    <div class="mb-4 p-3 rounded" style="background-color: #D1FAE5; color: #065F46;">
                        <p class="text-sm font-semibold">✅ Agent confirmed payment</p>
                        <p class="text-xs">Method: {{ $pv->payment_method }} | Confirmed: {{ $pv->agent_confirmed_at?->format('d/m/Y H:i') }}</p>
                    </div>
                    <form method="POST" action="{{ route('gestionnaire.wallet.confirm-payment', $pv->id) }}" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-semibold mb-1" style="color: #193948;">Amount to Add to Wallet</label>
                            <input type="number" step="0.01" name="amount" value="{{ old('amount', $pv->cash_received_amount ?? $pv->total_amount) }}" class="w-full rounded border p-2" required>
                            <p class="text-xs mt-1" style="color: #193948;">Agent reported: {{ number_format($pv->cash_received_amount ?? $pv->total_amount, 2) }} DZD</p>
                        </div>
                        <button type="submit" class="rounded px-4 py-2 font-semibold" style="background-color: #193948; color: #4FADC0;">Validate & Add to Agency Wallet</button>
                    </form>
                </div>
            @else
                <div class="rounded-lg shadow p-6" style="background-color: #10b981; color: white;">
                    <h3 class="text-xl font-semibold mb-2">✅ Payment Validated</h3>
                    <p class="text-sm">Amount: {{ number_format($pv->cash_received_amount, 2) }} DZD</p>
                    <p class="text-xs mt-2">Payment has been confirmed and added to agency wallet.</p>
                </div>
            @endif

            @if($pv->funds_released_at === null)
                <div class="rounded-lg shadow p-6" style="background-color: #F3EBDD;">
                    <h3 class="text-xl font-semibold mb-4" style="color: #193948;">Release Funds to Artists</h3>
                    <form method="POST" action="{{ route('gestionnaire.wallet.release-payment', $pv->id) }}" class="space-y-4">
                        @csrf
                        <p style="color: #193948;">Total to release: {{ number_format($pv->total_amount, 2) }} DZD</p>
                        <button type="submit" class="rounded px-4 py-2 font-semibold" style="background-color: #D6BFBF; color: #193948;" @disabled(!$pv->canReleaseFunds())>
                            Release Now
                        </button>
                    </form>
                </div>
            @else
                <div class="rounded-lg shadow p-6" style="background-color: #10b981; color: white;">
                    <h3 class="text-xl font-semibold mb-2">✅ Funds Released</h3>
                    <p class="text-sm">Total released: {{ number_format($pv->total_amount, 2) }} DZD</p>
                    <p class="text-xs mt-2">Funds have been transferred to artists' wallets.</p>
                </div>
            @endif
        </div>

        @if($pv->canBeFinalized())
            <div class="rounded-lg shadow p-6 border-4" style="background-color: #F3EBDD; border-color: #10b981;">
                <h3 class="text-xl font-semibold mb-4" style="color: #193948;">Finalize PV</h3>
                <p class="mb-4" style="color: #193948;">
                    This PV has been closed by the agent, payment validated, and funds released. 
                    You can now finalize it to complete the process.
                </p>
                <form method="POST" action="{{ route('gestionnaire.pvs.finalize', $pv->id) }}" class="inline">
                    @csrf
                    <button type="submit" class="rounded px-4 py-2 font-semibold" style="background-color: #10b981; color: white;">
                        Finalize PV
                    </button>
                </form>
            </div>
        @endif

        @if($pv->isFinalized())
            <div class="rounded-lg shadow p-6" style="background-color: #10b981; color: white;">
                <h3 class="text-xl font-semibold mb-2">✅ PV Finalized</h3>
                <p class="text-sm">
                    This PV has been finalized by {{ $pv->finalizedBy->name ?? 'N/A' }} 
                    @if($pv->finalized_at)
                        on {{ $pv->finalized_at->format('Y-m-d H:i') }}
                    @endif
                </p>
            </div>
        @endif

        <a href="{{ route('gestionnaire.pvs.index') }}" class="inline-block rounded px-4 py-2 font-semibold" style="background-color: #D6BFBF; color: #193948;">
            Back to list
        </a>
    </div>
</x-allthepages-layout>

