<x-allthepages-layout pageTitle="Inbox">
    <div style="padding: 5px; margin: 5px;">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px; margin-bottom: 2rem;">
            <div>
                <h1 style="color: #193948; font-size: 2rem; font-weight: 700; margin: 0;">📥 Inbox</h1>
                <p style="color: #193948; font-size: 0.9rem; margin-top: 0.5rem; opacity: 0.8;">Messages sent to you</p>
            </div>
            <a href="{{ route('artist.messages.index') }}" style="padding: 10px 20px; background-color: #193948; color: #4FADC0; border-radius: 0.5rem; text-decoration: none; font-weight: 600;">
                ← Back to Messages
            </a>
        </div>

        @forelse($messages as $item)
            <div class="page-container" style="margin-bottom: 1.5rem; border-left: 4px solid {{ $item->type === 'COMPLAINT' ? '#E76268' : '#10b981' }};">
                <div style="display: flex; justify-content: space-between; align-items: start; flex-wrap: wrap; gap: 15px; margin-bottom: 1rem;">
                    <div style="flex: 1;">
                        <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap; margin-bottom: 0.5rem;">
                            <span style="padding: 6px 14px; border-radius: 20px; font-size: 0.75rem; font-weight: 600; background-color: {{ $item->type === 'COMPLAINT' ? '#E76268' : '#10b981' }}; color: white;">
                                {{ $item->type === 'COMPLAINT' ? '⚠️ Complaint' : '📊 Report' }}
                            </span>
                            <span style="padding: 6px 14px; border-radius: 20px; font-size: 0.75rem; font-weight: 600; background-color: 
                                @if($item->status === 'PENDING') #f59e0b 
                                @elseif($item->status === 'RESOLVED') #10b981 
                                @else #6366f1 @endif; color: white;">
                                {{ str_replace('_', ' ', $item->status) }}
                            </span>
                        </div>
                        <h3 style="color: #193948; font-size: 1.25rem; font-weight: 700; margin-bottom: 0.5rem;">{{ $item->subject }}</h3>
                        <p style="color: #193948; font-size: 0.85rem; opacity: 0.8;">
                            From: <strong>{{ $item->sender?->name ?? ucfirst($item->sender_role ?? 'Unknown') }}</strong> • {{ $item->created_at->format('Y-m-d H:i') }}
                        </p>
                    </div>
                    <a href="{{ route('artist.messages.show', $item->id) }}" style="padding: 8px 16px; background-color: #193948; color: #4FADC0; border-radius: 0.5rem; text-decoration: none; font-weight: 600; font-size: 0.9rem;">
                        View Details
                    </a>
                </div>
                <div style="background-color: white; border: 2px solid #D6BFBF; border-radius: 0.5rem; padding: 1rem;">
                    <p style="color: #193948; white-space: pre-wrap; margin: 0;">{{ \Illuminate\Support\Str::limit($item->message, 200) }}</p>
                </div>
            </div>
        @empty
            <div class="page-container" style="text-align: center; padding: 4rem;">
                <div style="font-size: 4rem; margin-bottom: 1rem;">📭</div>
                <h3 style="color: #193948; font-size: 1.5rem; font-weight: 700; margin-bottom: 1rem;">Inbox is Empty</h3>
                <p style="color: #193948; opacity: 0.8;">No messages in your inbox yet.</p>
            </div>
        @endforelse

        @if($messages->hasPages())
            <div style="display: flex; justify-content: center; margin-top: 2rem;">
                {{ $messages->links() }}
            </div>
        @endif
    </div>
</x-allthepages-layout>






