<x-allthepages-layout pageTitle="Reports and Complaints">
    <div style="padding: 5px; margin: 5px;">
        @if(session('success'))
            <div class="alert-success" style="margin-bottom: 1.5rem;">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="alert-error" style="margin-bottom: 1.5rem;">
                <p>{{ session('error') }}</p>
            </div>
        @endif

        @php
            $totalSubmitted = isset($submitted) ? $submitted->count() : 0;
            $respondedCount = 0;
            if(isset($submitted)) {
                foreach($submitted as $item) {
                    if($item->admin_response || $item->gestionnaire_response || $item->super_admin_response) {
                        $respondedCount++;
                    }
                }
            }
        @endphp

        <!-- Statistics and Submit Button -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 10px; padding: 5px;">
            <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                <div class="stat-card" style="padding: 1.5rem; text-align: center; min-width: 120px;">
                    <div style="font-size: 1.75rem; font-weight: 700; color: #193948;">{{ $totalSubmitted }}</div>
                    <div style="color: #193948; font-size: 0.85rem;">Total Sent</div>
                </div>
                <div class="stat-card" style="padding: 1.5rem; text-align: center; min-width: 120px;">
                    <div style="font-size: 1.75rem; font-weight: 700; color: #10b981;">{{ $respondedCount }}</div>
                    <div style="color: #193948; font-size: 0.85rem;">Responded</div>
                </div>
            </div>
            <a href="{{ route('gestionnaire.complaints.create') }}" class="primary-button" style="background-color: #E76268;">
                ⚠️ Submit Complaint
            </a>
        </div>

        @if(session('success'))
            <div class="alert-success" style="margin-bottom: 1.5rem;">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="alert-error" style="margin-bottom: 1.5rem;">
                <p>{{ session('error') }}</p>
            </div>
        @endif

        @if(isset($inbox) && $inbox->count() > 0)
        <div class="stat-card" style="padding: 0; margin-bottom: 10px; border: 2px solid #193948; border-radius: 0.5rem; overflow: hidden;">
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; min-width: 900px;">
                    <thead>
                        <tr style="background: linear-gradient(135deg, #193948 0%, #2a4a5a 100%);">
                            <th style="color: #4FADC0; padding: 1.25rem 1rem; text-align: center; font-size: 0.9rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; border-right: 1px solid rgba(79, 173, 192, 0.2);">Type</th>
                            <th style="color: #4FADC0; padding: 1.25rem 1rem; text-align: center; font-size: 0.9rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; border-right: 1px solid rgba(79, 173, 192, 0.2);">Subject</th>
                            <th style="color: #4FADC0; padding: 1.25rem 1rem; text-align: center; font-size: 0.9rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; border-right: 1px solid rgba(79, 173, 192, 0.2);">From</th>
                            <th style="color: #4FADC0; padding: 1.25rem 1rem; text-align: center; font-size: 0.9rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; border-right: 1px solid rgba(79, 173, 192, 0.2);">Agency</th>
                            <th style="color: #4FADC0; padding: 1.25rem 1rem; text-align: center; font-size: 0.9rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; border-right: 1px solid rgba(79, 173, 192, 0.2);">Status</th>
                            <th style="color: #4FADC0; padding: 1.25rem 1rem; text-align: center; font-size: 0.9rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; border-right: 1px solid rgba(79, 173, 192, 0.2);">Date</th>
                            <th style="color: #4FADC0; padding: 1.25rem 1rem; text-align: center; font-size: 0.9rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($inbox as $complaint)
                            <tr style="border-bottom: 1px solid rgba(25, 57, 72, 0.1); background-color: {{ $loop->even ? '#F9F9F9' : '#FFFFFF' }}; transition: background-color 0.2s;" onmouseover="this.style.backgroundColor='#F3EBDD'" onmouseout="this.style.backgroundColor='{{ $loop->even ? '#F9F9F9' : '#FFFFFF' }}'">
                                <td style="padding: 1.25rem 1rem; text-align: center; border-right: 1px solid rgba(25, 57, 72, 0.1);">
                                    <span style="padding: 8px 16px; font-size: 0.8rem; font-weight: 700; background-color: {{ $complaint->type === 'COMPLAINT' ? '#E76268' : '#10b981' }}; color: white; display: inline-block; border-radius: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                                        {{ $complaint->type === 'COMPLAINT' ? '⚠️ Complaint' : '📊 Report' }}
                                    </span>
                                </td>
                                <td style="padding: 1.25rem 1rem; text-align: center; color: #193948; font-weight: 600; font-size: 0.95rem; border-right: 1px solid rgba(25, 57, 72, 0.1); max-width: 300px;">
                                    <div style="word-wrap: break-word;">{{ $complaint->subject }}</div>
                                </td>
                                <td style="padding: 1.25rem 1rem; text-align: center; color: #193948; font-weight: 600; font-size: 0.9rem; border-right: 1px solid rgba(25, 57, 72, 0.1);">
                                    <div style="font-weight: 700;">{{ $complaint->sender?->name ?? ucfirst($complaint->sender_role ?? 'Unknown') }}</div>
                                </td>
                                <td style="padding: 1.25rem 1rem; text-align: center; color: #193948; font-size: 0.9rem; border-right: 1px solid rgba(25, 57, 72, 0.1);">
                                    <div style="font-weight: 600;">{{ $complaint->agency->agency_name ?? 'N/A' }}</div>
                                    <div style="font-size: 0.75rem; color: #36454f; margin-top: 0.25rem;">{{ $complaint->agency->wilaya ?? '' }}</div>
                                </td>
                                <td style="padding: 1.25rem 1rem; text-align: center; border-right: 1px solid rgba(25, 57, 72, 0.1);">
                                    <span style="padding: 8px 16px; font-size: 0.8rem; font-weight: 700; background-color: 
                                        @if($complaint->status === 'PENDING') #f59e0b 
                                        @elseif($complaint->status === 'RESOLVED') #10b981 
                                        @elseif($complaint->status === 'IN_PROGRESS') #6366f1
                                        @else #193948 @endif; color: white; display: inline-block; border-radius: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                                        {{ str_replace('_', ' ', $complaint->status) }}
                                    </span>
                                </td>
                                <td style="padding: 1.25rem 1rem; text-align: center; color: #193948; font-size: 0.9rem; border-right: 1px solid rgba(25, 57, 72, 0.1);">
                                    <div style="font-weight: 600;">{{ $complaint->created_at->format('Y-m-d') }}</div>
                                    <div style="font-size: 0.8rem; color: #36454f; margin-top: 0.25rem;">{{ $complaint->created_at->format('H:i') }}</div>
                                </td>
                                <td style="padding: 1.25rem 1rem; text-align: center;">
                                    <div style="display: flex; gap: 8px; justify-content: center; align-items: center;">
                                        <a href="{{ route('gestionnaire.complaints.show', $complaint->id) }}" style="padding: 8px 16px; background-color: #193948; color: #4FADC0; border-radius: 0.5rem; text-decoration: none; font-size: 0.85rem; font-weight: 600; transition: all 0.2s; box-shadow: 0 2px 4px rgba(0,0,0,0.1);" onmouseover="this.style.backgroundColor='#2a4a5a'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 8px rgba(0,0,0,0.2)'" onmouseout="this.style.backgroundColor='#193948'; this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(0,0,0,0.1)'">
                                            👁️ Open
                                        </a>
                                        @if($complaint->type === 'COMPLAINT')
                                            <form action="{{ route('gestionnaire.complaints.delete', $complaint->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this complaint?');" style="display: inline;">
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
        @endif

        @if(isset($submitted) && $submitted->count() > 0)
        <div class="stat-card" style="padding: 0; margin-bottom: 10px; border: 2px solid #193948; border-radius: 0.5rem; overflow: hidden;">
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; min-width: 900px;">
                    <thead>
                        <tr style="background: linear-gradient(135deg, #193948 0%, #2a4a5a 100%);">
                            <th style="color: #4FADC0; padding: 1.25rem 1rem; text-align: center; font-size: 0.9rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; border-right: 1px solid rgba(79, 173, 192, 0.2);">Type</th>
                            <th style="color: #4FADC0; padding: 1.25rem 1rem; text-align: center; font-size: 0.9rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; border-right: 1px solid rgba(79, 173, 192, 0.2);">Subject</th>
                            <th style="color: #4FADC0; padding: 1.25rem 1rem; text-align: center; font-size: 0.9rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; border-right: 1px solid rgba(79, 173, 192, 0.2);">To</th>
                            <th style="color: #4FADC0; padding: 1.25rem 1rem; text-align: center; font-size: 0.9rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; border-right: 1px solid rgba(79, 173, 192, 0.2);">Agency</th>
                            <th style="color: #4FADC0; padding: 1.25rem 1rem; text-align: center; font-size: 0.9rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; border-right: 1px solid rgba(79, 173, 192, 0.2);">Status</th>
                            <th style="color: #4FADC0; padding: 1.25rem 1rem; text-align: center; font-size: 0.9rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; border-right: 1px solid rgba(79, 173, 192, 0.2);">Date</th>
                            <th style="color: #4FADC0; padding: 1.25rem 1rem; text-align: center; font-size: 0.9rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($submitted as $complaint)
                            <tr style="border-bottom: 1px solid rgba(25, 57, 72, 0.1); background-color: {{ $loop->even ? '#F9F9F9' : '#FFFFFF' }}; transition: background-color 0.2s;" onmouseover="this.style.backgroundColor='#F3EBDD'" onmouseout="this.style.backgroundColor='{{ $loop->even ? '#F9F9F9' : '#FFFFFF' }}'">
                                <td style="padding: 1.25rem 1rem; text-align: center; border-right: 1px solid rgba(25, 57, 72, 0.1);">
                                    <span style="padding: 8px 16px; font-size: 0.8rem; font-weight: 700; background-color: {{ $complaint->type === 'COMPLAINT' ? '#E76268' : '#10b981' }}; color: white; display: inline-block; border-radius: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                                        {{ $complaint->type === 'COMPLAINT' ? '⚠️ Complaint' : '📊 Report' }}
                                    </span>
                                </td>
                                <td style="padding: 1.25rem 1rem; text-align: center; color: #193948; font-weight: 600; font-size: 0.95rem; border-right: 1px solid rgba(25, 57, 72, 0.1); max-width: 300px;">
                                    <div style="word-wrap: break-word;">{{ $complaint->subject }}</div>
                                </td>
                                <td style="padding: 1.25rem 1rem; text-align: center; color: #193948; font-weight: 600; font-size: 0.9rem; border-right: 1px solid rgba(25, 57, 72, 0.1);">
                                    <div style="font-weight: 700;">{{ ucfirst($complaint->target_role ?? 'Unknown') }}</div>
                                </td>
                                <td style="padding: 1.25rem 1rem; text-align: center; color: #193948; font-size: 0.9rem; border-right: 1px solid rgba(25, 57, 72, 0.1);">
                                    <div style="font-weight: 600;">{{ $complaint->agency->agency_name ?? 'N/A' }}</div>
                                    <div style="font-size: 0.75rem; color: #36454f; margin-top: 0.25rem;">{{ $complaint->agency->wilaya ?? '' }}</div>
                                </td>
                                <td style="padding: 1.25rem 1rem; text-align: center; border-right: 1px solid rgba(25, 57, 72, 0.1);">
                                    <span style="padding: 8px 16px; font-size: 0.8rem; font-weight: 700; background-color: 
                                        @if($complaint->status === 'PENDING') #f59e0b 
                                        @elseif($complaint->status === 'RESOLVED') #10b981 
                                        @elseif($complaint->status === 'IN_PROGRESS') #6366f1
                                        @else #193948 @endif; color: white; display: inline-block; border-radius: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                                        {{ str_replace('_', ' ', $complaint->status) }}
                                    </span>
                                </td>
                                <td style="padding: 1.25rem 1rem; text-align: center; color: #193948; font-size: 0.9rem; border-right: 1px solid rgba(25, 57, 72, 0.1);">
                                    <div style="font-weight: 600;">{{ $complaint->created_at->format('Y-m-d') }}</div>
                                    <div style="font-size: 0.8rem; color: #36454f; margin-top: 0.25rem;">{{ $complaint->created_at->format('H:i') }}</div>
                                </td>
                                <td style="padding: 1.25rem 1rem; text-align: center;">
                                    <div style="display: flex; gap: 8px; justify-content: center; align-items: center;">
                                        <a href="{{ route('gestionnaire.complaints.show', $complaint->id) }}" style="padding: 8px 16px; background-color: #193948; color: #4FADC0; border-radius: 0.5rem; text-decoration: none; font-size: 0.85rem; font-weight: 600; transition: all 0.2s; box-shadow: 0 2px 4px rgba(0,0,0,0.1);" onmouseover="this.style.backgroundColor='#2a4a5a'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 8px rgba(0,0,0,0.2)'" onmouseout="this.style.backgroundColor='#193948'; this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(0,0,0,0.1)'">
                                            👁️ Open
                                        </a>
                                        @if($complaint->type === 'COMPLAINT')
                                            <form action="{{ route('gestionnaire.complaints.delete', $complaint->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this complaint?');" style="display: inline;">
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
        @endif

        <div class="stat-card" style="padding: 0; margin-bottom: 10px; border: 2px solid #193948; border-radius: 0.5rem; overflow: hidden;">
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; min-width: 900px;">
                    <thead>
                        <tr style="background: linear-gradient(135deg, #193948 0%, #2a4a5a 100%);">
                            <th style="color: #4FADC0; padding: 1.25rem 1rem; text-align: center; font-size: 0.9rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; border-right: 1px solid rgba(79, 173, 192, 0.2);">Type</th>
                            <th style="color: #4FADC0; padding: 1.25rem 1rem; text-align: center; font-size: 0.9rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; border-right: 1px solid rgba(79, 173, 192, 0.2);">Subject</th>
                            <th style="color: #4FADC0; padding: 1.25rem 1rem; text-align: center; font-size: 0.9rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; border-right: 1px solid rgba(79, 173, 192, 0.2);">Artist</th>
                            <th style="color: #4FADC0; padding: 1.25rem 1rem; text-align: center; font-size: 0.9rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; border-right: 1px solid rgba(79, 173, 192, 0.2);">Status</th>
                            <th style="color: #4FADC0; padding: 1.25rem 1rem; text-align: center; font-size: 0.9rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; border-right: 1px solid rgba(79, 173, 192, 0.2);">Assigned</th>
                            <th style="color: #4FADC0; padding: 1.25rem 1rem; text-align: center; font-size: 0.9rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; border-right: 1px solid rgba(79, 173, 192, 0.2);">Date</th>
                            <th style="color: #4FADC0; padding: 1.25rem 1rem; text-align: center; font-size: 0.9rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($artistComplaints as $complaint)
                            <tr style="border-bottom: 1px solid rgba(25, 57, 72, 0.1); background-color: {{ $loop->even ? '#F9F9F9' : '#FFFFFF' }}; transition: background-color 0.2s;" onmouseover="this.style.backgroundColor='#F3EBDD'" onmouseout="this.style.backgroundColor='{{ $loop->even ? '#F9F9F9' : '#FFFFFF' }}'">
                                <td style="padding: 1.25rem 1rem; text-align: center; border-right: 1px solid rgba(25, 57, 72, 0.1);">
                                    <span style="padding: 8px 16px; font-size: 0.8rem; font-weight: 700; background-color: #E76268; color: white; display: inline-block; border-radius: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                                        ⚠️ Complaint
                                    </span>
                                </td>
                                <td style="padding: 1.25rem 1rem; text-align: center; color: #193948; font-weight: 600; font-size: 0.95rem; border-right: 1px solid rgba(25, 57, 72, 0.1); max-width: 300px;">
                                    <div style="word-wrap: break-word;">{{ $complaint->subject }}</div>
                                </td>
                                <td style="padding: 1.25rem 1rem; text-align: center; color: #193948; font-weight: 600; font-size: 0.9rem; border-right: 1px solid rgba(25, 57, 72, 0.1);">
                                    <div style="font-weight: 700;">{{ $complaint->artist->user->name ?? 'N/A' }}</div>
                                </td>
                                <td style="padding: 1.25rem 1rem; text-align: center; border-right: 1px solid rgba(25, 57, 72, 0.1);">
                                    <span style="padding: 8px 16px; font-size: 0.8rem; font-weight: 700; background-color: 
                                        @if($complaint->status === 'PENDING') #f59e0b 
                                        @elseif($complaint->status === 'RESOLVED') #10b981 
                                        @elseif($complaint->status === 'IN_PROGRESS') #6366f1
                                        @else #193948 @endif; color: white; display: inline-block; border-radius: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                                        {{ str_replace('_', ' ', $complaint->status) }}
                                    </span>
                                </td>
                                <td style="padding: 1.25rem 1rem; text-align: center; color: #193948; font-size: 0.9rem; border-right: 1px solid rgba(25, 57, 72, 0.1);">
                                    <div style="font-weight: 600;">{{ $complaint->gestionnaire_id ? ($complaint->gestionnaire_id === $gestionnaire->id ? 'You' : 'Other') : 'Unassigned' }}</div>
                                </td>
                                <td style="padding: 1.25rem 1rem; text-align: center; color: #193948; font-size: 0.9rem; border-right: 1px solid rgba(25, 57, 72, 0.1);">
                                    <div style="font-weight: 600;">{{ $complaint->created_at->format('Y-m-d') }}</div>
                                    <div style="font-size: 0.8rem; color: #36454f; margin-top: 0.25rem;">{{ $complaint->created_at->format('H:i') }}</div>
                                </td>
                                <td style="padding: 1.25rem 1rem; text-align: center;">
                                    <div style="display: flex; gap: 8px; justify-content: center; align-items: center;">
                                        <a href="{{ route('gestionnaire.complaints.show', $complaint->id) }}" style="padding: 8px 16px; background-color: #193948; color: #4FADC0; border-radius: 0.5rem; text-decoration: none; font-size: 0.85rem; font-weight: 600; transition: all 0.2s; box-shadow: 0 2px 4px rgba(0,0,0,0.1);" onmouseover="this.style.backgroundColor='#2a4a5a'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 8px rgba(0,0,0,0.2)'" onmouseout="this.style.backgroundColor='#193948'; this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(0,0,0,0.1)'">
                                            👁️ Open
                                        </a>
                                        @if(!$complaint->gestionnaire_id)
                                            <form method="POST" action="{{ route('gestionnaire.complaints.take', $complaint->id) }}" style="display: inline;">
                                                @csrf
                                                <button type="submit" style="padding: 8px 16px; background-color: #10b981; color: white; border-radius: 0.5rem; border: none; font-size: 0.85rem; font-weight: 600; cursor: pointer; transition: all 0.2s; box-shadow: 0 2px 4px rgba(0,0,0,0.1);" onmouseover="this.style.backgroundColor='#059669'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 8px rgba(0,0,0,0.2)'" onmouseout="this.style.backgroundColor='#10b981'; this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(0,0,0,0.1)'">
                                                    ✅ Take
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-sm" style="color: #193948; padding: 1.5rem;">No complaints found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-allthepages-layout>

