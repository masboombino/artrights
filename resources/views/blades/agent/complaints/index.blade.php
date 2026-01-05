<x-allthepages-layout pageTitle="Complaints & Reports Center">
    <div class="space-y-6">
        @if(session('success'))
            <div class="p-4 mb-4 rounded" style="background-color: #F3EBDD; color: #193948; border: 2px solid #10b981;">
                {{ session('success') }}
            </div>
        @endif

        @php
            $totalSent = $submitted->count();
            $respondedCount = 0;
            foreach($submitted as $item) {
                if($item->admin_response || $item->gestionnaire_response) {
                    $respondedCount++;
                }
            }
        @endphp

        <!-- Statistics and Submit Button -->
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px; margin-bottom: 1.5rem;">
            <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                <div class="p-4 rounded" style="background-color: #F3EBDD; border: 2px solid #193948; text-align: center; min-width: 120px;">
                    <div style="font-size: 1.75rem; font-weight: 700; color: #193948;">{{ $totalSent }}</div>
                    <div style="color: #193948; font-size: 0.85rem;">Total Sent</div>
                </div>
                <div class="p-4 rounded" style="background-color: #F3EBDD; border: 2px solid #193948; text-align: center; min-width: 120px;">
                    <div style="font-size: 1.75rem; font-weight: 700; color: #10b981;">{{ $respondedCount }}</div>
                    <div style="color: #193948; font-size: 0.85rem;">Responded</div>
                </div>
            </div>
            <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                <a href="{{ route('agent.complaints.create', ['type' => 'complaint']) }}" class="inline-block px-4 py-2 rounded font-semibold transition hover:opacity-90" style="background-color: #E76268; color: white;">
                    ⚠️ Submit Complaint
                </a>
                <a href="{{ route('agent.complaints.create', ['type' => 'report']) }}" class="inline-block px-4 py-2 rounded font-semibold transition hover:opacity-90" style="background-color: #10b981; color: white;">
                    📊 Submit Report
                </a>
            </div>
        </div>

        <!-- Sent Section -->
        <div style="margin-bottom: 2rem;">
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 1rem;">
                <h3 class="text-lg font-semibold" style="color: #193948;">📤 Sent Items</h3>
                <span style="padding: 4px 12px; background-color: #193948; color: #4FADC0; border-radius: 20px; font-size: 0.75rem; font-weight: 600;">
                    {{ $submitted->count() }}
                </span>
            </div>
            
            @if($submitted->count() > 0)
                <div class="stat-card" style="padding: 0; margin-bottom: 10px; border: 2px solid #193948; border-radius: 0.5rem; overflow: hidden;">
                    <div style="overflow-x: auto;">
                        <table style="width: 100%; border-collapse: collapse; min-width: 900px;">
                            <thead>
                                <tr style="background: linear-gradient(135deg, #193948 0%, #2a4a5a 100%);">
                                    <th style="color: #4FADC0; padding: 1.25rem 1rem; text-align: center; font-size: 0.9rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; border-right: 1px solid rgba(79, 173, 192, 0.2);">Type</th>
                                    <th style="color: #4FADC0; padding: 1.25rem 1rem; text-align: center; font-size: 0.9rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; border-right: 1px solid rgba(79, 173, 192, 0.2);">Subject</th>
                                    <th style="color: #4FADC0; padding: 1.25rem 1rem; text-align: center; font-size: 0.9rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; border-right: 1px solid rgba(79, 173, 192, 0.2);">To</th>
                                    <th style="color: #4FADC0; padding: 1.25rem 1rem; text-align: center; font-size: 0.9rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; border-right: 1px solid rgba(79, 173, 192, 0.2);">Status</th>
                                    <th style="color: #4FADC0; padding: 1.25rem 1rem; text-align: center; font-size: 0.9rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; border-right: 1px solid rgba(79, 173, 192, 0.2);">Date</th>
                                    <th style="color: #4FADC0; padding: 1.25rem 1rem; text-align: center; font-size: 0.9rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($submitted as $item)
                                    <tr style="border-bottom: 1px solid rgba(25, 57, 72, 0.1); background-color: {{ $loop->even ? '#F9F9F9' : '#FFFFFF' }}; transition: background-color 0.2s;" onmouseover="this.style.backgroundColor='#F3EBDD'" onmouseout="this.style.backgroundColor='{{ $loop->even ? '#F9F9F9' : '#FFFFFF' }}'">
                                        <td style="padding: 1.25rem 1rem; text-align: center; border-right: 1px solid rgba(25, 57, 72, 0.1);">
                                            <span style="padding: 8px 16px; font-size: 0.8rem; font-weight: 700; background-color: {{ $item->type === 'COMPLAINT' ? '#E76268' : '#10b981' }}; color: white; display: inline-block; border-radius: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                                                {{ $item->type === 'COMPLAINT' ? '⚠️ Complaint' : '📊 Report' }}
                                            </span>
                                        </td>
                                        <td style="padding: 1.25rem 1rem; text-align: center; color: #193948; font-weight: 600; font-size: 0.95rem; border-right: 1px solid rgba(25, 57, 72, 0.1); max-width: 300px;">
                                            <div style="word-wrap: break-word;">{{ $item->subject }}</div>
                                        </td>
                                        <td style="padding: 1.25rem 1rem; text-align: center; color: #193948; font-weight: 600; font-size: 0.9rem; border-right: 1px solid rgba(25, 57, 72, 0.1);">
                                            <div style="font-weight: 700;">{{ ucfirst($item->target_role ?? 'admin') }}</div>
                                        </td>
                                        <td style="padding: 1.25rem 1rem; text-align: center; border-right: 1px solid rgba(25, 57, 72, 0.1);">
                                            <span style="padding: 8px 16px; font-size: 0.8rem; font-weight: 700; background-color: 
                                                @if($item->status === 'PENDING') #f59e0b 
                                                @elseif($item->status === 'RESOLVED') #10b981 
                                                @elseif($item->status === 'IN_PROGRESS') #6366f1
                                                @else #193948 @endif; color: white; display: inline-block; border-radius: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                                                {{ str_replace('_', ' ', $item->status) }}
                                            </span>
                                        </td>
                                        <td style="padding: 1.25rem 1rem; text-align: center; color: #193948; font-size: 0.9rem; border-right: 1px solid rgba(25, 57, 72, 0.1);">
                                            <div style="font-weight: 600;">{{ $item->created_at->format('Y-m-d') }}</div>
                                            <div style="font-size: 0.8rem; color: #36454f; margin-top: 0.25rem;">{{ $item->created_at->format('H:i') }}</div>
                                        </td>
                                        <td style="padding: 1.25rem 1rem; text-align: center;">
                                            <div style="display: flex; gap: 8px; justify-content: center; align-items: center;">
                                                <a href="{{ route('agent.complaints.show', $item->id) }}" style="padding: 8px 16px; background-color: #193948; color: #4FADC0; border-radius: 0.5rem; text-decoration: none; font-size: 0.85rem; font-weight: 600; transition: all 0.2s; box-shadow: 0 2px 4px rgba(0,0,0,0.1);" onmouseover="this.style.backgroundColor='#2a4a5a'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 8px rgba(0,0,0,0.2)'" onmouseout="this.style.backgroundColor='#193948'; this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(0,0,0,0.1)'">
                                                    👁️ Open
                                                </a>
                                                @if($item->type === 'COMPLAINT')
                                                    <form action="{{ route('agent.complaints.delete', $item->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this complaint?');" style="display: inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" style="padding: 8px 16px; background-color: #E76268; color: white; border-radius: 0.5rem; border: none; font-size: 0.85rem; font-weight: 600; cursor: pointer; transition: all 0.2s; box-shadow: 0 2px 4px rgba(0,0,0,0.1);" onmouseover="this.style.backgroundColor='#d4545a'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 8px rgba(0,0,0,0.2)'" onmouseout="this.style.backgroundColor='#E76268'; this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(0,0,0,0.1)'">
                                                            🗑️ Delete
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                <div class="stat-card" style="padding: 2rem; text-align: center; border: 2px dashed #D6BFBF;">
                    <div style="font-size: 3rem; margin-bottom: 1rem;">📭</div>
                    <p style="color: #36454f; font-size: 0.9rem;">No items sent yet.</p>
                </div>
            @endif
        </div>

        <!-- Inbox Section -->
        <div style="margin-top: 2rem;">
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 1rem;">
                <h3 class="text-lg font-semibold" style="color: #193948;">📥 Inbox</h3>
                <span style="padding: 4px 12px; background-color: #193948; color: #4FADC0; border-radius: 20px; font-size: 0.75rem; font-weight: 600;">
                    {{ $inbox->count() }}
                </span>
            </div>
            
            @if($inbox->count() > 0)
                <div class="stat-card" style="padding: 0; margin-bottom: 10px; border: 2px solid #193948; border-radius: 0.5rem; overflow: hidden;">
                    <div style="overflow-x: auto;">
                        <table style="width: 100%; border-collapse: collapse; min-width: 900px;">
                            <thead>
                                <tr style="background: linear-gradient(135deg, #193948 0%, #2a4a5a 100%);">
                                    <th style="color: #4FADC0; padding: 1.25rem 1rem; text-align: center; font-size: 0.9rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; border-right: 1px solid rgba(79, 173, 192, 0.2);">Type</th>
                                    <th style="color: #4FADC0; padding: 1.25rem 1rem; text-align: center; font-size: 0.9rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; border-right: 1px solid rgba(79, 173, 192, 0.2);">Subject</th>
                                    <th style="color: #4FADC0; padding: 1.25rem 1rem; text-align: center; font-size: 0.9rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; border-right: 1px solid rgba(79, 173, 192, 0.2);">From</th>
                                    <th style="color: #4FADC0; padding: 1.25rem 1rem; text-align: center; font-size: 0.9rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; border-right: 1px solid rgba(79, 173, 192, 0.2);">Status</th>
                                    <th style="color: #4FADC0; padding: 1.25rem 1rem; text-align: center; font-size: 0.9rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; border-right: 1px solid rgba(79, 173, 192, 0.2);">Date</th>
                                    <th style="color: #4FADC0; padding: 1.25rem 1rem; text-align: center; font-size: 0.9rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($inbox as $item)
                                    <tr style="border-bottom: 1px solid rgba(25, 57, 72, 0.1); background-color: {{ $loop->even ? '#F9F9F9' : '#FFFFFF' }}; transition: background-color 0.2s;" onmouseover="this.style.backgroundColor='#F3EBDD'" onmouseout="this.style.backgroundColor='{{ $loop->even ? '#F9F9F9' : '#FFFFFF' }}'">
                                        <td style="padding: 1.25rem 1rem; text-align: center; border-right: 1px solid rgba(25, 57, 72, 0.1);">
                                            <span style="padding: 8px 16px; font-size: 0.8rem; font-weight: 700; background-color: {{ $item->type === 'COMPLAINT' ? '#E76268' : '#10b981' }}; color: white; display: inline-block; border-radius: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                                                {{ $item->type === 'COMPLAINT' ? '⚠️ Complaint' : '📊 Report' }}
                                            </span>
                                        </td>
                                        <td style="padding: 1.25rem 1rem; text-align: center; color: #193948; font-weight: 600; font-size: 0.95rem; border-right: 1px solid rgba(25, 57, 72, 0.1); max-width: 300px;">
                                            <div style="word-wrap: break-word;">{{ $item->subject }}</div>
                                        </td>
                                        <td style="padding: 1.25rem 1rem; text-align: center; color: #193948; font-weight: 600; font-size: 0.9rem; border-right: 1px solid rgba(25, 57, 72, 0.1);">
                                            <div style="font-weight: 700;">{{ ucfirst($item->sender_role ?? ($item->sender?->name ?? 'Unknown')) }}</div>
                                        </td>
                                        <td style="padding: 1.25rem 1rem; text-align: center; border-right: 1px solid rgba(25, 57, 72, 0.1);">
                                            <span style="padding: 8px 16px; font-size: 0.8rem; font-weight: 700; background-color: 
                                                @if($item->status === 'PENDING') #f59e0b 
                                                @elseif($item->status === 'RESOLVED') #10b981 
                                                @elseif($item->status === 'IN_PROGRESS') #6366f1
                                                @else #193948 @endif; color: white; display: inline-block; border-radius: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                                                {{ str_replace('_', ' ', $item->status) }}
                                            </span>
                                        </td>
                                        <td style="padding: 1.25rem 1rem; text-align: center; color: #193948; font-size: 0.9rem; border-right: 1px solid rgba(25, 57, 72, 0.1);">
                                            <div style="font-weight: 600;">{{ $item->created_at->format('Y-m-d') }}</div>
                                            <div style="font-size: 0.8rem; color: #36454f; margin-top: 0.25rem;">{{ $item->created_at->format('H:i') }}</div>
                                        </td>
                                        <td style="padding: 1.25rem 1rem; text-align: center;">
                                            <div style="display: flex; gap: 8px; justify-content: center; align-items: center;">
                                                <a href="{{ route('agent.complaints.show', $item->id) }}" style="padding: 8px 16px; background-color: #193948; color: #4FADC0; border-radius: 0.5rem; text-decoration: none; font-size: 0.85rem; font-weight: 600; transition: all 0.2s; box-shadow: 0 2px 4px rgba(0,0,0,0.1);" onmouseover="this.style.backgroundColor='#2a4a5a'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 8px rgba(0,0,0,0.2)'" onmouseout="this.style.backgroundColor='#193948'; this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(0,0,0,0.1)'">
                                                    👁️ Open
                                                </a>
                                                @if($item->type === 'COMPLAINT')
                                                    <form action="{{ route('agent.complaints.delete', $item->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this complaint?');" style="display: inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" style="padding: 8px 16px; background-color: #E76268; color: white; border-radius: 0.5rem; border: none; font-size: 0.85rem; font-weight: 600; cursor: pointer; transition: all 0.2s; box-shadow: 0 2px 4px rgba(0,0,0,0.1);" onmouseover="this.style.backgroundColor='#d4545a'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 8px rgba(0,0,0,0.2)'" onmouseout="this.style.backgroundColor='#E76268'; this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(0,0,0,0.1)'">
                                                            🗑️ Delete
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                <div class="stat-card" style="padding: 2rem; text-align: center; border: 2px dashed #D6BFBF;">
                    <div style="font-size: 3rem; margin-bottom: 1rem;">📭</div>
                    <p style="color: #36454f; font-size: 0.9rem;">No incoming items for now.</p>
                </div>
            @endif
        </div>
    </div>
</x-allthepages-layout>
