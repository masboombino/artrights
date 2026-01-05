<x-allthepages-layout pageTitle="My Wallet">
    <div style="padding: 5px; margin: 5px;">
        @if(session('success'))
            <div class="alert-success">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="alert-error">
                <p>{{ session('error') }}</p>
            </div>
        @endif

        <div class="page-container">
            <h2 style="color: #193948; font-size: 1.75rem; font-weight: 700; margin-bottom: 1rem;">Wallet Balance</h2>
            <p style="color: #193948; font-size: 3rem; font-weight: 800; margin-bottom: 1.5rem;">{{ number_format($wallet->balance, 2) }} DZD</p>
            
            <div style="margin-top: 2rem;">
                <h3 style="color: #193948; font-size: 1.5rem; font-weight: 700; margin-bottom: 1rem;">Recharge Wallet</h3>
                <p style="color: #193948; font-size: 0.95rem; margin-bottom: 1rem;">Please choose a payment method and upload proof of payment. Your request will be reviewed by an administrator.</p>
                
                <form action="{{ route('artist.wallet.recharge') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div style="margin: 5px; padding: 5px;">
                        <label for="amount" class="form-label">Amount (DZD) *</label>
                        <input type="number" step="0.01" min="100" name="amount" id="amount" required class="form-input" placeholder="Minimum 100 DZD">
                        <p style="color: #193948; font-size: 0.85rem; margin-top: 0.25rem;">Minimum recharge amount: 100 DZD</p>
                        @error('amount')
                            <p style="color: #E76268; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</p>
                        @enderror
                    </div>

                    <div style="margin: 5px; padding: 5px;">
                        <label for="payment_method" class="form-label">Payment Method *</label>
                        <select name="payment_method" id="payment_method" required class="form-input">
                            <option value="">Select payment method</option>
                            <option value="CHEQUE" @selected(old('payment_method') == 'CHEQUE')>Cheque</option>
                            <option value="POSTAL_TRANSFER" @selected(old('payment_method') == 'POSTAL_TRANSFER')>Postal Transfer</option>
                        </select>
                        <p style="color: #193948; font-size: 0.85rem; margin-top: 0.25rem;">Cheque: upload a clear photo of the cheque. Postal Transfer: upload the transfer voucher (bon).</p>
                        @error('payment_method')
                            <p style="color: #E76268; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</p>
                        @enderror
                    </div>

                    <div id="postal_transfer_fields" class="hidden">
                        <div style="margin: 5px; padding: 5px;">
                            <label for="transaction_reference" class="form-label">Transaction Reference / Receipt Number *</label>
                            <input type="text" name="transaction_reference" id="transaction_reference" value="{{ old('transaction_reference') }}" class="form-input" placeholder="Transaction reference or receipt number">
                            @error('transaction_reference')
                                <p style="color: #E76268; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div style="margin: 5px; padding: 5px;">
                        <label for="payment_proof" class="form-label">Payment Proof (Image) *</label>
                        <input type="file" name="payment_proof" id="payment_proof" accept="image/*" required class="form-input">
                        <p style="color: #193948; font-size: 0.85rem; margin-top: 0.25rem;">Upload a screenshot or photo of your payment receipt (Max 10MB, JPG/PNG)</p>
                        @error('payment_proof')
                            <p style="color: #E76268; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</p>
                        @enderror
                    </div>

                    <div style="margin: 5px; padding: 5px;">
                        <label for="notes" class="form-label">Additional Notes (Optional)</label>
                        <textarea name="notes" id="notes" rows="3" class="form-input" placeholder="Any additional information about the payment">{{ old('notes') }}</textarea>
                    </div>

                    <button type="submit" class="primary-button" style="margin-top: 1rem;">
                        Submit Recharge Request
                    </button>
                </form>
            </div>
        </div>

        @if($pendingRequests->count() > 0)
            <div class="page-container">
                <h3 style="color: #193948; font-size: 1.5rem; font-weight: 700; margin-bottom: 1rem;">Pending Recharge Requests</h3>
                <div style="display: flex; flex-direction: column; gap: 15px;">
                    @foreach($pendingRequests as $request)
                        <div style="background-color: #fef3c7; border: 2px solid #f59e0b; padding: 1.5rem; border-radius: 0.5rem;">
                            <div style="display: flex; justify-content: space-between; align-items: start; flex-wrap: wrap; gap: 10px;">
                                <div>
                                    <p style="color: #193948; font-weight: 700;">Amount: {{ number_format($request->amount, 2) }} DZD</p>
                                    <p style="color: #193948; font-size: 0.9rem;">Method: {{ $request->payment_method === 'CHEQUE' ? 'Cheque' : 'Postal Transfer' }}</p>
                                    @if($request->transaction_reference)
                                        <p style="color: #193948; font-size: 0.9rem;">Reference: {{ $request->transaction_reference }}</p>
                                    @endif
                                    <p style="color: #193948; font-size: 0.85rem; opacity: 0.8; margin-top: 0.25rem;">Submitted: {{ $request->created_at->format('Y-m-d H:i') }}</p>
                                </div>
                                <span style="background-color: #f59e0b; color: white; padding: 0.25rem 0.75rem; border-radius: 0.5rem; font-size: 0.8rem; font-weight: 700;">
                                    PENDING
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="page-container" style="overflow-x: auto;">
            <h2 style="color: #193948; font-size: 1.75rem; font-weight: 700; margin-bottom: 1.5rem;">Transaction History</h2>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Description</th>
                        <th>Reference</th>
                        <th>Amount</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $transaction)
                        <tr>
                            <td style="white-space: nowrap;">{{ $transaction->created_at->format('Y-m-d H:i') }}</td>
                            <td>
                                @php
                                    $typeLabels = [
                                        'PV_PAYMENT' => 'PV Payment',
                                        'WALLET_RECHARGE' => 'Wallet Recharge',
                                        'PLATFORM_TAX' => 'Platform Tax',
                                        'OTHER' => 'Other',
                                    ];
                                    $typeColors = [
                                        'PV_PAYMENT' => '#10b981',
                                        'WALLET_RECHARGE' => '#4FADC0',
                                        'PLATFORM_TAX' => '#E76268',
                                        'OTHER' => '#193948',
                                    ];
                                    $type = $transaction->type ?? ($transaction->pv_id ? 'PV_PAYMENT' : ($transaction->payment_method === 'WALLET_RECHARGE' ? 'WALLET_RECHARGE' : 'OTHER'));
                                @endphp
                                <span style="background-color: {{ $typeColors[$type] ?? '#193948' }}; color: white; padding: 0.25rem 0.75rem; border-radius: 0.5rem; font-size: 0.8rem; font-weight: 600;">
                                    {{ $typeLabels[$type] ?? 'Other' }}
                                </span>
                            </td>
                            <td style="max-width: 300px;">
                                {{ $transaction->description ?? (
                                    $transaction->pv ? 'Payment from PV #' . $transaction->pv->id : (
                                        $transaction->payment_method === 'WALLET_RECHARGE' ? 'Wallet recharge' : 'N/A'
                                    )
                                ) }}
                            </td>
                            <td>
                                @if($transaction->pv)
                                    <span style="color: #193948; font-weight: 600;">
                                        PV #{{ $transaction->pv->id }}
                                    </span>
                                    @if($transaction->pv->shop_name)
                                        <br><span style="color: #193948; font-size: 0.85rem; opacity: 0.8;">{{ Str::limit($transaction->pv->shop_name, 25) }}</span>
                                    @endif
                                @elseif($transaction->artwork)
                                    <a href="{{ route('artist.show-artwork', $transaction->artwork->id) }}" style="color: #4FADC0; text-decoration: none; font-weight: 600;">
                                        {{ Str::limit($transaction->artwork->title, 30) }}
                                    </a>
                                @else
                                    -
                                @endif
                            </td>
                            <td style="font-weight: 700; color: {{ $transaction->amount < 0 ? '#E76268' : '#10b981' }};">
                                {{ $transaction->amount < 0 ? '-' : '+' }}{{ number_format(abs($transaction->amount), 2) }} DZD
                            </td>
                            <td>
                                <span class="status-badge">
                                    {{ $transaction->payment_status ?? 'N/A' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 2rem;">
                                <p style="color: #193948; font-size: 1rem;">No transactions found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <script>
        document.getElementById('payment_method').addEventListener('change', function() {
            const method = this.value;
            const postalTransferFields = document.getElementById('postal_transfer_fields');
            const transactionReferenceInput = document.getElementById('transaction_reference');

            if (method === 'POSTAL_TRANSFER') {
                postalTransferFields.classList.remove('hidden');
                if (transactionReferenceInput) {
                    transactionReferenceInput.setAttribute('required', 'required');
                }
            } else {
                postalTransferFields.classList.add('hidden');
                if (transactionReferenceInput) {
                    transactionReferenceInput.removeAttribute('required');
                }
            }
        });

        @if(old('payment_method'))
            document.getElementById('payment_method').dispatchEvent(new Event('change'));
        @endif
    </script>
</x-allthepages-layout>
