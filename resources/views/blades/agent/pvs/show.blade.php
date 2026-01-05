<x-allthepages-layout pageTitle="PV #{{ $pv->id }}">
    <div class="space-y-6">
        @if(session('success'))
            <div class="rounded p-4" style="background-color: #d1fae5; color: #065f46;">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="rounded p-4" style="background-color: #fee2e2; color: #991b1b;">
                <ul class="list-disc ml-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="rounded-lg shadow p-6" style="background-color: #F3EBDD; border: 2px solid #193948;">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="p-4 rounded" style="background-color: #ffffff; border: 2px solid #193948; border-radius: 8px;">
                    <h1 class="text-2xl font-bold mb-2" style="color: #193948;">{{ $pv->shop_name }}</h1>
                    <p class="mb-1" style="color: #36454f;">{{ $pv->shop_type }} — {{ $pv->date_of_inspection?->format('d/m/Y H:i') }}</p>
                    <p class="mb-1" style="color: #36454f;">Agency: {{ $pv->agency->agency_name ?? 'N/A' }}</p>
                    @if($pv->mission)
                        <p style="color: #36454f;">Mission:
                            <span class="font-semibold">{{ $pv->mission->title }}</span>
                            <a href="{{ route('agent.missions.show', $pv->mission->id) }}" style="color: #D6BFBF;">View mission</a>
                        </p>
                    @endif
                </div>
                <div class="p-4 rounded text-right" style="background-color: #ffffff; border: 2px solid #193948; border-radius: 8px;">
                    <div class="mb-2">
                        <span class="px-3 py-1 rounded text-xs font-semibold" style="background-color: #193948; color: #4FADC0;">Status: {{ $pv->status }}</span>
                    </div>
                    <div class="mb-2">
                        <span class="px-3 py-1 rounded text-xs font-semibold" style="background-color: #193948; color: #4FADC0;">
                            Payment: {{ $pv->payment_method ?? 'N/A' }} / {{ $pv->payment_status }}
                        </span>
                    </div>
                    <div class="text-2xl font-bold" style="color: #193948;">
                        Total: {{ number_format($pv->total_amount, 2) }} DZD
                    </div>
                </div>
            </div>

            <div class="flex flex-wrap gap-3 mt-6">
                <a href="{{ route('agent.pvs.devices.create', $pv) }}" class="rounded px-4 py-2 font-semibold transition hover:opacity-90" style="background-color: #D6BFBF; color: #193948;">Add Device</a>
                <a href="{{ route('agent.pvs.artworks.create', $pv) }}" class="rounded px-4 py-2 font-semibold transition hover:opacity-90" style="background-color: #D6BFBF; color: #193948;">Add Artwork Usage</a>
                <a href="{{ route('agent.pvs.index') }}" class="rounded px-4 py-2 font-semibold transition hover:opacity-90" style="background-color: #D6BFBF; color: #193948;">Back to list</a>
                @if($pv->status === 'CLOSED' && $pv->mission)
                    <a href="{{ route('agent.missions.print', $pv->mission->id) }}" target="_blank" class="rounded px-4 py-2 font-semibold transition hover:opacity-90" style="background-color: #4FADC0; color: #193948;">🖨️ Print Mission</a>
                @endif
                @if($pv->status === 'CLOSED')
                    <a href="{{ route('agent.pvs.print', $pv) }}" target="_blank" class="rounded px-4 py-2 font-semibold transition hover:opacity-90" style="background-color: #4FADC0; color: #193948;">🖨️ Print PV</a>
                @endif
            </div>
        </div>

        @if($pv->status === 'OPEN')
            <div class="rounded-lg shadow p-6" style="background-color: #F3EBDD;">
                <h3 class="text-xl font-semibold mb-4" style="color: #193948;">Add Photos from Location</h3>
                <form method="POST" action="{{ route('agent.pvs.photos', $pv) }}" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-semibold mb-1" style="color: #193948;">Select Photos (You can upload up to 100 photos)</label>
                        <input type="file" name="photos[]" multiple accept="image/*" capture="environment" class="w-full rounded border p-2" id="photo-upload">
                        <p class="text-xs mt-1" style="color: #193948;">
                            @php($currentCount = count($pv->evidenceFiles()))
                            Current Photos: {{ $currentCount }} / 100
                        </p>
                        <p class="text-xs mt-1" style="color: #36454f;">
                            You can photograph devices and exploitation directly from your device. All image types and sizes are accepted.
                        </p>
                    </div>
                    <button type="submit" class="rounded px-4 py-2 font-semibold" style="background-color: #193948; color: #4FADC0;">
                        Upload Photos
                    </button>
                </form>
            </div>
        @endif

        @php($evidenceFiles = $pv->evidenceFiles())
        @if(count($evidenceFiles))
            <div class="rounded-lg shadow p-6" style="background-color: #F3EBDD;">
                <h2 class="text-xl font-semibold mb-4" style="color: #193948;">Evidence Photos ({{ count($evidenceFiles) }})</h2>
                @include('blades.partials.complaint-gallery', [
                    'galleryId' => 'agent-pv-'.$pv->id,
                    'images' => $evidenceFiles,
                    'useStorageRoute' => true,
                ])
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="rounded-lg shadow" style="background-color: #F3EBDD;">
                <div class="px-6 py-4 border-b border-black/10 flex justify-between items-center">
                    <h2 class="text-xl font-semibold" style="color: #193948;">Devices</h2>
                </div>
                <div class="divide-y divide-black/10">
                    @forelse($pv->devices as $device)
                        <div class="px-6 py-4 flex justify-between items-center">
                            <div>
                                <p class="font-semibold" style="color: #193948;">{{ $device->name }}</p>
                                <p class="text-sm" style="color: #193948;">
                                    {{ $device->type }} · Coefficient {{ $device->coefficient }} · Qty {{ $device->quantity }}
                                </p>
                                <p class="text-sm font-semibold" style="color: #193948;">
                                    Assigned Fine: {{ number_format($device->amount, 2) }} DZD
                                </p>
                            </div>
                            <form method="POST" action="{{ route('agent.pvs.devices.destroy', [$pv, $device]) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-sm font-semibold" style="color: #991b1b;">Remove</button>
                            </form>
                        </div>
                    @empty
                        <p class="px-6 py-4 text-center text-sm" style="color: #193948;">No devices recorded.</p>
                    @endforelse
                </div>
            </div>

            <div class="rounded-lg shadow" style="background-color: #F3EBDD;">
                <div class="px-6 py-4 border-b border-black/10 flex justify-between items-center">
                    <h2 class="text-xl font-semibold" style="color: #193948;">Artworks</h2>
                </div>
                <div class="divide-y divide-black/10">
                    @forelse($pv->artworkUsages as $usage)
                        <div class="px-6 py-4">
                            <div class="flex justify-between">
                                <div>
                                    <p class="font-semibold" style="color: #193948;">{{ $usage->artwork->title }}</p>
                                    <p class="text-sm" style="color: #193948;">
                                        Artist: {{ $usage->artwork->artist->user->name ?? 'Unknown' }}
                                    </p>
                                    <p class="text-sm" style="color: #193948;">
                                        Hours: {{ $usage->hours_used }} · Plays: {{ $usage->plays_count }}
                                        @if($usage->device)
                                            · Device: {{ $usage->device->name }}
                                        @endif
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="text-lg font-bold" style="color: #193948;">{{ number_format($usage->fine_amount, 2) }} DZD</p>
                                    <form method="POST" action="{{ route('agent.pvs.artworks.destroy', [$pv, $usage]) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-sm font-semibold" style="color: #991b1b;">Remove</button>
                                    </form>
                                </div>
                            </div>
                            @if($usage->notes)
                                <p class="mt-2 text-sm" style="color: #193948;">{{ $usage->notes }}</p>
                            @endif
                        </div>
                    @empty
                        <p class="px-6 py-4 text-center text-sm" style="color: #193948;">Add artworks that were used illegally.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @if(!$pv->agent_payment_confirmed)
                <div class="rounded-lg shadow p-6" style="background-color: #F3EBDD;">
                    <h3 class="text-xl font-semibold mb-4" style="color: #193948;">Confirm Payment Receipt</h3>
                    <form method="POST" action="{{ route('agent.pvs.payment', $pv) }}" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-semibold mb-1" style="color: #193948;">Payment Method</label>
                            <select name="payment_method" class="w-full rounded border p-2" required>
                                <option value="">Select</option>
                                <option value="CASH" @selected($pv->payment_method === 'CASH')>Cash</option>
                                <option value="CHEQUE" @selected($pv->payment_method === 'CHEQUE')>Cheque</option>
                            </select>
                            <p class="text-xs mt-1" style="color: #193948;">Use cash when you collected money. Choose cheque when you received a cheque.</p>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-1" style="color: #193948;">Amount Received from Client</label>
                            <input type="number" step="0.01" name="cash_received_amount" value="{{ old('cash_received_amount', $pv->total_amount) }}" class="w-full rounded border p-2" required>
                            <p class="text-xs mt-1" style="color: #193948;">PV Total: {{ number_format($pv->total_amount, 2) }} DZD</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <input type="checkbox" name="agent_payment_confirmed" value="1" id="confirm_payment" class="rounded">
                            <label for="confirm_payment" class="text-sm font-semibold" style="color: #193948;">I confirm that I received the payment from the client</label>
                        </div>
                        <button type="submit" class="rounded px-4 py-2 font-semibold" style="background-color: #193948; color: #4FADC0;">
                            Confirm Payment
                        </button>
                    </form>
                </div>
            @else
                <div class="rounded-lg shadow p-6" style="background-color: #10b981; color: white;">
                    <h3 class="text-xl font-semibold mb-2">✅ Payment Confirmed by Agent</h3>
                    <p class="text-sm">Method: {{ $pv->payment_method }}</p>
                    <p class="text-sm">Amount: {{ number_format($pv->cash_received_amount ?? $pv->total_amount, 2) }} DZD</p>
                    <p class="text-xs mt-2">Confirmed at: {{ $pv->agent_confirmed_at?->format('d/m/Y H:i') }}</p>
                    @if($pv->payment_status === 'VALIDATED')
                        <p class="text-xs mt-2 font-semibold">✅ Gestionnaire has validated and added to agency wallet</p>
                    @else
                        <p class="text-xs mt-2">⏳ Waiting for gestionnaire to validate and add to agency wallet</p>
                    @endif
                </div>
            @endif

            <div class="rounded-lg shadow p-6" style="background-color: #F3EBDD;">
                <h3 class="text-xl font-semibold mb-4" style="color: #193948;">Upload Payment Proof</h3>
                <form method="POST" action="{{ route('agent.pvs.payment-proof', $pv) }}" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <input type="file" name="payment_proof" class="w-full rounded border p-2" required>
                    <button type="submit" class="rounded px-4 py-2 font-semibold" style="background-color: #193948; color: #4FADC0;">
                        Upload Proof
                    </button>
                </form>
                @if($pv->payment_proof_path)
                    <div class="mt-4">
                        <p class="text-sm mb-2" style="color: #193948;">Current proof:</p>
                        <a href="{{ \Illuminate\Support\Facades\Storage::url($pv->payment_proof_path) }}" target="_blank" class="inline-block rounded px-4 py-2 font-semibold transition hover:opacity-90" style="background-color: #193948; color: #4FADC0;">View file</a>
                    </div>
                @endif
            </div>

            <div class="rounded-lg shadow p-6" style="background-color: #F3EBDD;">
                <h3 class="text-xl font-semibold mb-4" style="color: #193948;">Close PV</h3>
                <form method="POST" action="{{ route('agent.pvs.close', $pv) }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-semibold mb-1" style="color: #193948;">Notes</label>
                        <textarea name="notes" rows="4" class="w-full rounded border p-2">{{ old('notes', $pv->notes) }}</textarea>
                    </div>
                    <button type="submit" class="rounded px-4 py-2 font-semibold" style="background-color: #D6BFBF; color: #193948;">
                        Mark as Closed
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-allthepages-layout>

