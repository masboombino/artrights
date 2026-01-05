@php
    use Illuminate\Support\Facades\Storage;
@endphp

<x-allthepages-layout pageTitle="View Recharge Request">
    <div class="space-y-6">
        @if(session('success'))
            <div class="p-4 rounded-lg border-2 mb-4" style="background-color: #d1fae5; border-color: #10b981;">
                <p class="font-semibold" style="color: #065f46;">{{ session('success') }}</p>
            </div>
        @endif

        @if($errors->any())
            <div class="p-4 rounded-lg border-2 mb-4" style="background-color: #fee2e2; border-color: #ef4444;">
                <ul>
                    @foreach($errors->all() as $error)
                        <li style="color: #991b1b;">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="rounded-lg shadow p-6" style="background-color: #F3EBDD;">
            <h2 class="text-2xl font-semibold mb-4" style="color: #193948;">Recharge Request Details</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="block text-sm font-semibold mb-1" style="color: #193948;">Artist</label>
                    <p class="text-base" style="color: #36454f;">{{ $request->artist->user->name ?? 'N/A' }}</p>
                    <p class="text-sm" style="color: #6b7280;">{{ $request->artist->stage_name ?? '' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1" style="color: #193948;">Amount</label>
                    <p class="text-2xl font-bold" style="color: #193948;">{{ number_format($request->amount, 2) }} DZD</p>
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1" style="color: #193948;">Payment Method</label>
                    <p class="text-base" style="color: #36454f;">{{ $request->payment_method === 'CHEQUE' ? 'Cheque' : 'Bank Transfer' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1" style="color: #193948;">Transaction Reference</label>
                    <p class="text-base" style="color: #36454f;">{{ $request->transaction_reference }}</p>
                </div>
                @if($request->bank_name)
                    <div>
                        <label class="block text-sm font-semibold mb-1" style="color: #193948;">Bank Name</label>
                        <p class="text-base" style="color: #36454f;">{{ $request->bank_name }}</p>
                    </div>
                @endif
                @if($request->account_number)
                    <div>
                        <label class="block text-sm font-semibold mb-1" style="color: #193948;">Account Number</label>
                        <p class="text-base" style="color: #36454f;">{{ $request->account_number }}</p>
                    </div>
                @endif
                <div>
                    <label class="block text-sm font-semibold mb-1" style="color: #193948;">Status</label>
                    <span class="inline-block px-3 py-1 rounded text-sm font-semibold
                        @if($request->status === 'APPROVED') bg-green-500 text-white
                        @elseif($request->status === 'REJECTED') bg-red-500 text-white
                        @else bg-yellow-500 text-white
                        @endif">
                        {{ $request->status }}
                    </span>
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1" style="color: #193948;">Submitted</label>
                    <p class="text-base" style="color: #36454f;">{{ $request->created_at->format('Y-m-d H:i') }}</p>
                </div>
                @if($request->notes)
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold mb-1" style="color: #193948;">Notes</label>
                        <p class="text-base" style="color: #36454f;">{{ $request->notes }}</p>
                    </div>
                @endif
            </div>

            @if($request->payment_proof_path)
                <div class="mb-6">
                    <label class="block text-sm font-semibold mb-2" style="color: #193948;">Payment Proof</label>
                    <a href="{{ Storage::url($request->payment_proof_path) }}" target="_blank" class="inline-block">
                        <img src="{{ Storage::url($request->payment_proof_path) }}" alt="Payment Proof" class="max-w-md rounded border-2" style="border-color: #193948;">
                    </a>
                </div>
            @endif

            @if($request->status === 'PENDING')
                <div class="flex gap-4 mt-6">
                    <form action="{{ route('admin.wallet-recharge.approve', $request->id) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="rounded px-4 py-2 font-semibold transition hover:opacity-90" style="background-color: #10b981; color: white;" onclick="return confirm('Are you sure you want to approve this recharge request? The amount will be added to the artist\'s wallet.');">
                            Approve Request
                        </button>
                    </form>
                    <button type="button" onclick="document.getElementById('rejectForm').classList.toggle('hidden')" class="rounded px-4 py-2 font-semibold transition hover:opacity-90" style="background-color: #ef4444; color: white;">
                        Reject Request
                    </button>
                </div>

                <form id="rejectForm" action="{{ route('admin.wallet-recharge.reject', $request->id) }}" method="POST" class="hidden mt-4 p-4 rounded border-2" style="background-color: #fee2e2; border-color: #ef4444;">
                    @csrf
                    <div class="mb-4">
                        <label for="rejection_reason" class="block text-sm font-semibold mb-2" style="color: #991b1b;">Rejection Reason *</label>
                        <textarea name="rejection_reason" id="rejection_reason" rows="4" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500"
                            style="background-color: white; color: #193948;"
                            placeholder="Please provide a reason for rejection..."></textarea>
                    </div>
                    <button type="submit" class="rounded px-4 py-2 font-semibold transition hover:opacity-90" style="background-color: #ef4444; color: white;">
                        Submit Rejection
                    </button>
                </form>
            @elseif($request->status === 'REJECTED')
                <div class="mt-6 p-4 rounded border-2" style="background-color: #fee2e2; border-color: #ef4444;">
                    <label class="block text-sm font-semibold mb-2" style="color: #991b1b;">Rejection Reason</label>
                    <p class="text-base" style="color: #991b1b;">{{ $request->rejection_reason }}</p>
                    <p class="text-sm mt-2" style="color: #6b7280;">Rejected by: {{ $request->approver->name ?? 'N/A' }} on {{ $request->updated_at->format('Y-m-d H:i') }}</p>
                </div>
            @else
                <div class="mt-6 p-4 rounded border-2" style="background-color: #d1fae5; border-color: #10b981;">
                    <p class="text-base font-semibold" style="color: #065f46;">✅ Request Approved</p>
                    <p class="text-sm mt-2" style="color: #6b7280;">Approved by: {{ $request->approver->name ?? 'N/A' }} on {{ $request->approved_at->format('Y-m-d H:i') }}</p>
                </div>
            @endif

            <div class="mt-6">
                <a href="{{ route('admin.wallet-recharge.index') }}" class="inline-block rounded px-4 py-2 font-semibold transition hover:opacity-90" style="background-color: #6b7280; color: white;">
                    Back to List
                </a>
            </div>
        </div>
    </div>
</x-allthepages-layout>

