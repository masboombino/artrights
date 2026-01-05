<x-allthepages-layout pageTitle="Messages Center">
    <div style="padding: 5px; margin: 5px;">
        @if(session('success'))
            <div class="alert-success" style="margin-bottom: 1.5rem;">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        <!-- Header -->
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px; margin-bottom: 2rem; padding: 1rem; background: linear-gradient(135deg, #F3EBDD 0%, #D6BFBF 100%); border-radius: 1rem; border: 2px solid #193948;">
            <div>
                <h1 style="color: #193948; font-size: 2rem; font-weight: 700; margin: 0;">💬 Messages Center</h1>
                <p style="color: #193948; font-size: 0.9rem; margin-top: 0.5rem; opacity: 0.8;">Your Complaints & Reports</p>
            </div>
            <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                <a href="{{ route('artist.messages.create', ['type' => 'complaint']) }}" style="padding: 12px 24px; background-color: #E76268; color: white; border-radius: 0.5rem; text-decoration: none; font-weight: 600;">
                    ⚠️ New Complaint
                </a>
                <a href="{{ route('artist.messages.create', ['type' => 'report']) }}" style="padding: 12px 24px; background-color: #10b981; color: white; border-radius: 0.5rem; text-decoration: none; font-weight: 600;">
                    📊 New Report
                </a>
            </div>
        </div>

        <!-- Statistics -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 15px; margin-bottom: 2rem;">
            <div class="page-container" style="text-align: center; padding: 1rem; background: linear-gradient(135deg, #F3EBDD 0%, #D6BFBF 100%);">
                <div style="font-size: 2rem; font-weight: 700; color: #193948;">{{ $stats['total'] ?? 0 }}</div>
                <div style="color: #193948; font-size: 0.85rem; margin-top: 0.5rem;">Total</div>
            </div>
            <div class="page-container" style="text-align: center; padding: 1rem; background: linear-gradient(135deg, #FEE2E2 0%, #FCA5A5 100%);">
                <div style="font-size: 2rem; font-weight: 700; color: #E76268;">{{ $stats['complaints'] ?? 0 }}</div>
                <div style="color: #193948; font-size: 0.85rem; margin-top: 0.5rem;">Complaints</div>
            </div>
            <div class="page-container" style="text-align: center; padding: 1rem; background: linear-gradient(135deg, #D1FAE5 0%, #6EE7B7 100%);">
                <div style="font-size: 2rem; font-weight: 700; color: #10b981;">{{ $stats['reports'] ?? 0 }}</div>
                <div style="color: #193948; font-size: 0.85rem; margin-top: 0.5rem;">Reports</div>
            </div>
            <div class="page-container" style="text-align: center; padding: 1rem; background: linear-gradient(135deg, #FEF3C7 0%, #FDE68A 100%);">
                <div style="font-size: 2rem; font-weight: 700; color: #f59e0b;">{{ $stats['pending'] ?? 0 }}</div>
                <div style="color: #193948; font-size: 0.85rem; margin-top: 0.5rem;">Pending</div>
            </div>
        </div>

        <!-- Tabs -->
        <div style="display: flex; gap: 5px; border-bottom: 3px solid #D6BFBF; margin-bottom: 1.5rem; flex-wrap: wrap; background-color: #F3EBDD; padding: 0.5rem; border-radius: 0.5rem 0.5rem 0 0;">
            <a href="{{ route('artist.messages.index') }}" 
               style="padding: 12px 24px; font-weight: 600; color: #193948; text-decoration: none; border-bottom: 3px solid {{ ($type ?? 'all') === 'all' ? '#193948' : 'transparent' }};">
                📋 All
            </a>
            <a href="{{ route('artist.messages.index', ['type' => 'complaint']) }}" 
               style="padding: 12px 24px; font-weight: 600; color: #193948; text-decoration: none; border-bottom: 3px solid {{ ($type ?? 'all') === 'complaint' ? '#E76268' : 'transparent' }};">
                ⚠️ Complaints
            </a>
            <a href="{{ route('artist.messages.index', ['type' => 'report']) }}" 
               style="padding: 12px 24px; font-weight: 600; color: #193948; text-decoration: none; border-bottom: 3px solid {{ ($type ?? 'all') === 'report' ? '#10b981' : 'transparent' }};">
                📊 Reports
            </a>
            <a href="{{ route('artist.messages.inbox') }}" 
               style="padding: 12px 24px; font-weight: 600; color: #193948; text-decoration: none; border-bottom: 3px solid transparent;">
                📥 Inbox
            </a>
            <a href="{{ route('artist.messages.sent') }}" 
               style="padding: 12px 24px; font-weight: 600; color: #193948; text-decoration: none; border-bottom: 3px solid transparent;">
                📤 Sent
            </a>
        </div>

        <!-- Messages List -->
        @forelse($messages as $item)
            <div class="page-container" style="margin-bottom: 1.5rem; border-left: 4px solid {{ $item->type === 'COMPLAINT' ? '#E76268' : '#10b981' }};">
                <div style="display: flex; justify-content: space-between; align-items: start; flex-wrap: wrap; gap: 15px; padding-bottom: 1rem; margin-bottom: 1rem; border-bottom: 2px solid rgba(255, 227, 227, 0.2);">
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
                        <div style="display: flex; gap: 15px; flex-wrap: wrap; font-size: 0.85rem; color: #193948; opacity: 0.8;">
                            <span>To: {{ ucfirst(str_replace('_', ' ', $item->target_role ?? 'admin')) }}</span>
                            <span>•</span>
                            <span>{{ $item->created_at->format('Y-m-d H:i') }}</span>
                        </div>
                    </div>
                    <a href="{{ route('artist.messages.show', $item->id) }}" style="padding: 8px 16px; background-color: #193948; color: #4FADC0; border-radius: 0.5rem; text-decoration: none; font-weight: 600; font-size: 0.9rem;">
                        View Details
                    </a>
                </div>

                <div style="background-color: white; border: 2px solid #193948; border-radius: 0.5rem; padding: 1rem; margin-bottom: 1rem;">
                    <p style="color: #193948; white-space: pre-wrap; margin: 0;">{{ \Illuminate\Support\Str::limit($item->message, 200) }}</p>
                </div>

                @if($item->images && count($item->images) > 0)
                    <div style="background-color: white; border: 2px solid #193948; border-radius: 0.5rem; padding: 1rem; margin-bottom: 1rem;">
                        <p style="color: #193948; font-weight: 600; margin-bottom: 0.5rem; font-size: 0.9rem;">📷 Attachments ({{ count($item->images) }})</p>
                    </div>
                @endif

                @php
                    $responseField = $item->target_role === 'gestionnaire' ? 'gestionnaire_response' : 'admin_response';
                    $responseValue = $item->{$responseField};
                @endphp

                @if($responseValue)
                    <div style="margin-top: 1rem; padding-top: 1rem; border-top: 2px solid rgba(255, 227, 227, 0.2);">
                        <p style="color: #193948; font-weight: 700; margin-bottom: 0.5rem;">✅ Response:</p>
                        <div style="background-color: #D1FAE5; border: 2px solid #10b981; border-radius: 0.5rem; padding: 1rem;">
                            <p style="color: #193948; white-space: pre-wrap; margin: 0;">{{ \Illuminate\Support\Str::limit($responseValue, 150) }}</p>
                        </div>
                    </div>
                @else
                    <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px dashed rgba(255, 227, 227, 0.3);">
                        <p style="color: #193948; font-size: 0.9rem; opacity: 0.7;">⏳ Waiting for response...</p>
                    </div>
                @endif
            </div>
        @empty
            <div class="page-container" style="text-align: center; padding: 4rem;">
                <div style="font-size: 4rem; margin-bottom: 1rem;">📭</div>
                <h3 style="color: #193948; font-size: 1.5rem; font-weight: 700; margin-bottom: 1rem;">No Messages Yet</h3>
                <p style="color: #193948; margin-bottom: 1.5rem; opacity: 0.8;">You haven't sent or received any messages yet.</p>
                <div style="display: flex; gap: 10px; justify-content: center; flex-wrap: wrap;">
                    <a href="{{ route('artist.messages.create', ['type' => 'complaint']) }}" style="padding: 12px 24px; background-color: #E76268; color: white; border-radius: 0.5rem; text-decoration: none; font-weight: 600;">
                        Submit Your First Complaint
                    </a>
                    <a href="{{ route('artist.messages.create', ['type' => 'report']) }}" style="padding: 12px 24px; background-color: #10b981; color: white; border-radius: 0.5rem; text-decoration: none; font-weight: 600;">
                        Submit Your First Report
                    </a>
                </div>
            </div>
        @endforelse

        @if($messages->hasPages())
            <div style="display: flex; justify-content: center; margin-top: 2rem;">
                {{ $messages->links() }}
            </div>
        @endif
    </div>
</x-allthepages-layout>






