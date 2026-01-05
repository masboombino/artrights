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
                    <p class="text-base" style="color: #36454f;">{{ $request->payment_method === 'CHEQUE' ? 'Cheque' : 'Postal Transfer' }}</p>
                </div>
                @if($request->transaction_reference)
                    <div>
                        <label class="block text-sm font-semibold mb-1" style="color: #193948;">Transaction Reference</label>
                        <p class="text-base" style="color: #36454f;">{{ $request->transaction_reference }}</p>
                    </div>
                @endif
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
                    @php
                        $normalizedPath = ltrim($request->payment_proof_path, '/');
                        $imageUrl = route('media.show', ['path' => $normalizedPath]);
                        
                        // Generate a meaningful filename for download
                        $artistName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $request->artist->user->name ?? 'Artist');
                        $artistName = str_replace(' ', '_', $artistName);
                        $paymentMethod = $request->payment_method === 'CHEQUE' ? 'Cheque' : 'PostalTransfer';
                        $fileExtension = pathinfo($normalizedPath, PATHINFO_EXTENSION);
                        $downloadFileName = $artistName . '_PaymentProof_' . $paymentMethod . '_' . $request->id . '.' . $fileExtension;
                        
                        $downloadUrl = route('media.show', [
                            'path' => $normalizedPath,
                            'download' => 1,
                            'filename' => $downloadFileName
                        ]);
                    @endphp
                    <div style="display: flex; flex-direction: column; gap: 1rem;">
                        <button type="button" class="payment-proof-thumb" onclick="openPaymentProofLightbox('{{ $imageUrl }}')" style="
                            position: relative;
                            padding: 0;
                            border: 2px solid #193948;
                            border-radius: 8px;
                            overflow: hidden;
                            cursor: pointer;
                            background: transparent;
                            transition: transform 0.2s, box-shadow 0.2s;
                            max-width: 300px;
                            max-height: 300px;
                            align-self: flex-start;
                        ">
                            <img src="{{ $imageUrl }}" alt="Payment Proof" style="
                                width: 100%;
                                height: 100%;
                                object-fit: contain;
                                display: block;
                            ">
                        </button>
                        <a href="{{ $downloadUrl }}" class="inline-block rounded transition hover:opacity-90" style="
                            background-color: #193948;
                            color: #4FADC0;
                            padding: 0.5rem 1rem;
                            font-weight: 600;
                            text-decoration: none;
                            font-size: 0.9rem;
                            align-self: flex-start;
                        ">
                            Download Payment Proof
                        </a>
                    </div>
                    
                    <!-- Lightbox -->
                    <div id="payment-proof-lightbox" class="payment-proof-lightbox" style="
                        display: none;
                        position: fixed;
                        top: 0;
                        left: 0;
                        width: 100%;
                        height: 100%;
                        background: rgba(0, 0, 0, 0.95);
                        z-index: 10000;
                        justify-content: center;
                        align-items: center;
                    ">
                        <button type="button" onclick="closePaymentProofLightbox()" style="
                            position: absolute;
                            top: 20px;
                            right: 30px;
                            font-size: 40px;
                            font-weight: bold;
                            color: white;
                            background: transparent;
                            border: none;
                            cursor: pointer;
                            z-index: 10001;
                            line-height: 1;
                            padding: 0;
                            width: 40px;
                            height: 40px;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                        ">&times;</button>
                        <img id="payment-proof-lightbox-img" src="" alt="Payment Proof Preview" style="
                            max-width: 90%;
                            max-height: 90%;
                            object-fit: contain;
                        ">
                    </div>
                    
                    <style>
                        .payment-proof-thumb:hover {
                            transform: translateY(-4px);
                            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
                        }
                        .payment-proof-lightbox button:hover {
                            color: #D6BFBF;
                        }
                    </style>
                    
                    <script>
                        function openPaymentProofLightbox(imageUrl) {
                            const imgElement = document.getElementById('payment-proof-lightbox-img');
                            if (imgElement) {
                                imgElement.src = imageUrl;
                            }
                            document.getElementById('payment-proof-lightbox').style.display = 'flex';
                        }
                        
                        function closePaymentProofLightbox() {
                            document.getElementById('payment-proof-lightbox').style.display = 'none';
                        }
                        
                        // Close lightbox on Escape key
                        document.addEventListener('keydown', function(e) {
                            if (e.key === 'Escape') {
                                closePaymentProofLightbox();
                            }
                        });
                    </script>
                </div>
            @endif

            @if($request->status === 'PENDING')
                <div class="flex gap-4 mt-6">
                    <form action="{{ route('gestionnaire.wallet-recharge.approve', $request->id) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="rounded px-4 py-2 font-semibold transition hover:opacity-90" style="background-color: #10b981; color: white;" onclick="return confirm('Are you sure you want to approve this recharge request? The amount will be added to the artist\'s wallet.');">
                            Approve Request
                        </button>
                    </form>
                    <button type="button" onclick="document.getElementById('rejectForm').classList.toggle('hidden')" class="rounded px-4 py-2 font-semibold transition hover:opacity-90" style="background-color: #ef4444; color: white;">
                        Reject Request
                    </button>
                </div>

                <form id="rejectForm" action="{{ route('gestionnaire.wallet-recharge.reject', $request->id) }}" method="POST" class="hidden mt-4 p-4 rounded border-2" style="background-color: #fee2e2; border-color: #ef4444;">
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
                <a href="{{ route('gestionnaire.wallet-recharge.index') }}" class="inline-block rounded px-4 py-2 font-semibold transition hover:opacity-90" style="background-color: #6b7280; color: white;">
                    Back to List
                </a>
            </div>
        </div>
    </div>
</x-allthepages-layout>

