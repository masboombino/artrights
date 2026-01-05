<x-allthepages-layout pageTitle="Agency Wallet">
    <div style="padding: 5px; margin: 5px;">
        <h1 style="color: #F3EBDD; font-size: 1.75rem; font-weight: 700; margin-bottom: 1.5rem; padding: 5px;">
            Wallet Overview
        </h1>

        <div style="background-color: #F3EBDD; border-radius: 1rem; padding: 2rem; margin: 5px; margin-bottom: 1.5rem;">
            <h2 style="color: #193948; font-size: 1.5rem; font-weight: 700; margin-bottom: 0.5rem; text-align: center;">
                Current Balance
            </h2>
            <p style="color: #193948; font-size: 3rem; font-weight: 800; text-align: center; margin: 1rem 0;">
                {{ number_format($wallet->balance ?? 0, 2) }} DZD
            </p>
            @if($wallet && $wallet->last_transaction)
                <p style="color: #193948; font-size: 0.95rem; text-align: center; margin: 0; opacity: 0.8;">
                    Last transaction: {{ $wallet->last_transaction->format('Y-m-d H:i') }}
                </p>
            @endif
        </div>

        <div style="margin: 5px; padding: 5px;">
            <h2 style="color: #F3EBDD; font-size: 1.5rem; font-weight: 700; margin: 10px 5px; padding: 5px;">
                Transaction History
            </h2>
            <div style="background-color: #F3EBDD; border-radius: 1rem; margin: 5px; padding: 1.5rem; overflow-x: auto;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Direction</th>
                            <th>Amount</th>
                            <th>PV Reference</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $transaction)
                            <tr>
                                <td style="white-space: nowrap;">{{ $transaction->created_at->format('Y-m-d H:i') }}</td>
                                <td>
                                    <span style="background-color: {{ $transaction->direction === 'IN' ? '#10b981' : '#E76268' }}; color: white; padding: 0.5rem 1rem; border-radius: 0.75rem; font-size: 0.85rem; font-weight: 600;">
                                        {{ $transaction->direction }}
                                    </span>
                                </td>
                                <td style="font-weight: 700; color: {{ $transaction->direction === 'IN' ? '#10b981' : '#E76268' }};">
                                    {{ $transaction->direction === 'IN' ? '+' : '-' }}{{ number_format($transaction->amount, 2) }} DZD
                                </td>
                                <td>
                                    @if($transaction->pv)
                                        <a href="{{ route('gestionnaire.pvs.show', $transaction->pv->id) }}" style="color: #193948; font-weight: 600; text-decoration: underline;">
                                            PV #{{ $transaction->pv->id }}
                                        </a>
                                        @if($transaction->pv->shop_name)
                                            <br><span style="color: #193948; font-size: 0.85rem; opacity: 0.8;">{{ Str::limit($transaction->pv->shop_name, 25) }}</span>
                                        @endif
                                    @else
                                        -
                                    @endif
                                </td>
                                <td style="max-width: 300px;">{{ $transaction->description ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="text-align: center; padding: 2rem;">
                                    <p style="color: #193948; font-size: 1rem;">No transactions yet.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                @if($transactions->hasPages())
                    <div style="margin-top: 1.5rem; padding-top: 1rem; border-top: 2px solid rgba(25, 57, 72, 0.1);">
                        {{ $transactions->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-allthepages-layout>
