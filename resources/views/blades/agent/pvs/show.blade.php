<x-allthepages-layout pageTitle="PV #{{ $pv->id }}">
    <div class="space-y-6">
        @if(session('success'))
            <div class="rounded p-4" style="background-color: #d1fae5; color: #065f46;">
                {{ session('success') }}
            </div>
        @endif

        @if(session('warning'))
            <div class="rounded p-4" style="background-color: #fef3c7; border: 2px solid #f59e0b; color: #92400e;">
                <strong>⚠️ Warning:</strong> {{ session('warning') }}
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
                    <div class="text-2xl font-bold mb-3" style="color: #193948;">
                        Total: {{ number_format($pv->total_amount, 2) }} DZD
                    </div>
                    <div class="flex flex-wrap gap-2 justify-end">
                        @if($pv->canClosePV())
                            <form method="POST" action="{{ route('agent.pvs.close', $pv) }}" style="display: inline;" id="closePvForm">
                                @csrf
                                <button type="submit" class="inline-flex items-center gap-2 rounded-lg shadow-lg transition hover:opacity-90 px-4 py-2 font-semibold text-sm" style="background-color: #10b981; color: white;">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M20 6L9 17L4 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                    Close the PV
                                </button>
                            </form>
                        @else
                            <button type="button" onclick="showToast('Cannot close PV: Payment must be confirmed and amount must match total.', 'error')" class="inline-flex items-center gap-2 rounded-lg shadow transition hover:opacity-90 px-4 py-2 font-semibold text-sm opacity-60 cursor-not-allowed" style="background-color: #9ca3af; color: white;">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M20 6L9 17L4 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                Close the PV
                            </button>
                        @endif
                        @if($pv->status === 'OPEN')
                            <a href="{{ route('agent.pvs.create') }}" class="inline-flex items-center gap-2 rounded-lg shadow-lg transition hover:opacity-90 px-4 py-2 font-semibold text-sm" style="background-color: #4FADC0; color: white;">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M11 4H4C3.46957 4 2.96086 4.21071 2.58579 4.58579C2.21071 4.96086 2 5.46957 2 6V20C2 20.5304 2.21071 21.0391 2.58579 21.4142C2.96086 21.7893 3.46957 22 4 22H18C18.5304 22 19.0391 21.7893 19.4142 21.4142C19.7893 21.0391 20 20.5304 20 20V13" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M18.5 2.50023C18.8978 2.10243 19.4374 1.87891 20 1.87891C20.5626 1.87891 21.1022 2.10243 21.5 2.50023C21.8978 2.89804 22.1213 3.43762 22.1213 4.00023C22.1213 4.56284 21.8978 5.10243 21.5 5.50023L12 15.0002L8 16.0002L9 12.0002L18.5 2.50023Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                Edit PV
                            </a>
                        @else
                            <button type="button" onclick="showToast('Cannot edit PV: PV must be in OPEN status.', 'error')" class="inline-flex items-center gap-2 rounded-lg shadow transition hover:opacity-90 px-4 py-2 font-semibold text-sm opacity-60 cursor-not-allowed" style="background-color: #9ca3af; color: white;">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M11 4H4C3.46957 4 2.96086 4.21071 2.58579 4.58579C2.21071 4.96086 2 5.46957 2 6V20C2 20.5304 2.21071 21.0391 2.58579 21.4142C2.96086 21.7893 3.46957 22 4 22H18C18.5304 22 19.0391 21.7893 19.4142 21.4142C19.7893 21.0391 20 20.5304 20 20V13" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M18.5 2.50023C18.8978 2.10243 19.4374 1.87891 20 1.87891C20.5626 1.87891 21.1022 2.10243 21.5 2.50023C21.8978 2.89804 22.1213 3.43762 22.1213 4.00023C22.1213 4.56284 21.8978 5.10243 21.5 5.50023L12 15.0002L8 16.0002L9 12.0002L18.5 2.50023Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                Edit PV
                            </button>
                        @endif
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
                    @if($pv->agency && $pv->agency->bank_account_number)
                        <div class="mb-4 p-3 rounded" style="background-color: #dbeafe; border: 2px solid #3b82f6;">
                            <p class="text-sm font-semibold mb-1" style="color: #193948;">🏦 Agency Bank Account Number:</p>
                            <p class="text-lg font-bold" style="color: #1e40af;">{{ $pv->agency->bank_account_number }}</p>
                        </div>
                    @endif
                    <form method="POST" action="{{ route('agent.pvs.payment', $pv) }}" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-semibold mb-1" style="color: #193948;">Payment Method</label>
                            <select name="payment_method" class="w-full rounded border p-2" required>
                                <option value="">Select</option>
                                <option value="CASH" @selected($pv->payment_method === 'CASH' || old('payment_method') === 'CASH')>Cash</option>
                                <option value="CHEQUE" @selected($pv->payment_method === 'CHEQUE' || old('payment_method') === 'CHEQUE')>Cheque</option>
                            </select>
                            <p class="text-xs mt-1" style="color: #193948;">Use cash when you collected money. Choose cheque when you received a cheque.</p>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-1" style="color: #193948;">Amount Received from Client</label>
                            <input type="number" step="0.01" name="cash_received_amount" value="{{ old('cash_received_amount', $pv->total_amount) }}" class="w-full rounded border p-2 @error('cash_received_amount') border-red-500 @enderror" required>
                            <p class="text-xs mt-1" style="color: #193948;">PV Total: {{ number_format($pv->total_amount, 2) }} DZD</p>
                            @error('cash_received_amount')
                                <p class="text-xs mt-1" style="color: #991b1b;">{{ $message }}</p>
                            @enderror
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

            @if($pv->status !== 'CLOSED' && $pv->canClosePV())
                <div class="rounded-lg shadow p-6" style="background-color: #F3EBDD;">
                    <h3 class="text-xl font-semibold mb-4" style="color: #193948;">Close PV</h3>
                    <form method="POST" action="{{ route('agent.pvs.close', $pv) }}" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-semibold mb-1" style="color: #193948;">Notes</label>
                            <textarea name="notes" rows="4" class="w-full rounded border p-2">{{ old('notes', $pv->notes) }}</textarea>
                        </div>
                        <button type="submit" class="rounded px-4 py-2 font-semibold" style="background-color: #10b981; color: white;">
                            Mark as Closed
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>

    <script>
        function showToast(message, type = 'info') {
            // Create toast element
            const toast = document.createElement('div');
            toast.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                padding: 16px 24px;
                background-color: ${type === 'error' ? '#ef4444' : type === 'success' ? '#10b981' : '#3b82f6'};
                color: white;
                border-radius: 8px;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                z-index: 10000;
                font-weight: 500;
                max-width: 400px;
                animation: slideIn 0.3s ease-out;
            `;
            toast.textContent = message;
            
            // Add animation
            const style = document.createElement('style');
            style.textContent = `
                @keyframes slideIn {
                    from {
                        transform: translateX(400px);
                        opacity: 0;
                    }
                    to {
                        transform: translateX(0);
                        opacity: 1;
                    }
                }
                @keyframes slideOut {
                    from {
                        transform: translateX(0);
                        opacity: 1;
                    }
                    to {
                        transform: translateX(400px);
                        opacity: 0;
                    }
                }
            `;
            if (!document.querySelector('style[data-toast-style]')) {
                style.setAttribute('data-toast-style', '');
                document.head.appendChild(style);
            }
            
            document.body.appendChild(toast);
            
            // Remove toast after 4 seconds
            setTimeout(() => {
                toast.style.animation = 'slideOut 0.3s ease-out';
                setTimeout(() => {
                    if (toast.parentNode) {
                        toast.parentNode.removeChild(toast);
                    }
                }, 300);
            }, 4000);
        }

        document.addEventListener('DOMContentLoaded', function() {
            const closeForm = document.getElementById('closePvForm');
            if (closeForm) {
                closeForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    if (confirm('Are you sure you want to close this PV? This action cannot be undone.')) {
                        this.submit();
                    }
                });
            }
        });
    </script>
</x-allthepages-layout>

