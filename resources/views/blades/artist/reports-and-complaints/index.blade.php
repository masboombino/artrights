<x-allthepages-layout pageTitle="Complaints">
    <style>
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .complaints-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 1.5rem 1rem;
            background-color: #F3EBDD;
            position: relative;
        }

        .complaints-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 150px;
            background: linear-gradient(135deg, rgba(231, 98, 104, 0.05) 0%, rgba(214, 191, 191, 0.08) 50%, rgba(79, 173, 192, 0.05) 100%);
            border-radius: 0 0 30% 30% / 0 0 20% 20%;
            z-index: 0;
        }

        .complaints-container > * {
            position: relative;
            z-index: 1;
        }


        .stat-card {
            background-color: #F3EBDD;
            border: 2px solid #193948;
            border-radius: 8px;
            padding: 0.625rem 0.875rem;
            text-align: center;
            transition: all 0.3s ease;
            position: relative;
            box-shadow: 0 1px 4px rgba(25, 57, 72, 0.1);
            min-width: 100px;
            flex: 1;
            max-width: 140px;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, #E76268 0%, #D6BFBF 50%, #4FADC0 100%);
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 2px 8px rgba(25, 57, 72, 0.15);
        }

        .stat-icon {
            font-size: 1rem;
            margin-bottom: 0.25rem;
            display: block;
            line-height: 1;
        }

        .stat-value {
            font-size: 1.25rem;
            font-weight: 700;
            color: #193948;
            margin-bottom: 0.125rem;
            line-height: 1.2;
        }

        .stat-card:nth-child(1) .stat-value {
            color: #E76268;
        }

        .stat-card:nth-child(2) .stat-value {
            color: #4FADC0;
        }

        .stat-card:nth-child(3) .stat-value {
            color: #193948;
        }

        .stat-label {
            font-size: 0.6875rem;
            color: #193948;
            font-weight: 600;
            line-height: 1.2;
        }

        .action-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            gap: 1rem;
            padding: 1rem 1.25rem;
            background-color: #F3EBDD;
            border: 2px solid #193948;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(25, 57, 72, 0.1);
            animation: fadeInUp 0.5s ease-out 0.1s both;
        }

        .action-header-stats {
            display: flex;
            gap: 0.75rem;
            align-items: center;
            flex: 1;
            justify-content: center;
            flex-wrap: wrap;
        }

        .page-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #193948;
            margin: 0;
            position: relative;
            padding-bottom: 0.5rem;
        }

        .page-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 60px;
            height: 3px;
            background: linear-gradient(90deg, #E76268 0%, #4FADC0 100%);
            border-radius: 2px;
        }

        .btn-submit {
            background-color: #E76268;
            color: white;
            padding: 0.625rem 1.25rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.875rem;
            border: 2px solid #193948;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            box-shadow: 0 2px 8px rgba(231, 98, 104, 0.3);
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(231, 98, 104, 0.4);
            background-color: #d4555a;
        }

        .complaint-card {
            background-color: #F3EBDD;
            border: 3px solid #193948;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            transition: all 0.3s ease;
            position: relative;
            box-shadow: 0 2px 8px rgba(25, 57, 72, 0.1);
            animation: fadeInUp 0.4s ease-out;
        }

        .complaint-card:not(:last-child)::after {
            content: '';
            position: absolute;
            bottom: -1rem;
            left: 50%;
            transform: translateX(-50%);
            width: 80%;
            height: 4px;
            background: linear-gradient(90deg, transparent 0%, #D6BFBF 20%, #D6BFBF 80%, transparent 100%);
            border-radius: 2px;
        }

        .complaint-card:hover {
            box-shadow: 0 4px 16px rgba(25, 57, 72, 0.2);
            transform: translateY(-2px);
            border-color: #E76268;
        }

        .complaint-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #D6BFBF;
            flex-wrap: wrap;
            gap: 0.75rem;
        }

        .complaint-header-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            width: 100%;
            margin-bottom: 0.75rem;
        }

        .complaint-badges {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
            align-items: center;
        }

        .complaint-actions-top {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }

        .btn-response-toggle {
            background-color: #4FADC0;
            color: #193948;
            padding: 0.375rem 0.75rem;
            border-radius: 6px;
            border: 2px solid #193948;
            font-weight: 600;
            font-size: 0.75rem;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(79, 173, 192, 0.3);
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
        }

        .btn-response-toggle:hover {
            transform: translateY(-1px);
            box-shadow: 0 3px 8px rgba(79, 173, 192, 0.4);
            background-color: #3d9db0;
        }

        .response-content-hidden {
            display: none;
        }

        .response-content-visible {
            display: block;
            margin-top: 1rem;
        }

        .response-text-container {
            background-color: white;
            border: 2px solid #4FADC0;
            border-radius: 8px;
            padding: 0.875rem;
            margin-bottom: 0.75rem;
            color: #193948;
            white-space: pre-wrap;
            line-height: 1.5;
            font-size: 0.875rem;
        }

        .response-images-wrapper {
            background-color: white;
            border: 1px solid #4FADC0;
            border-radius: 8px;
            padding: 0.875rem;
        }

        .badge {
            padding: 0.375rem 0.75rem;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 600;
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .badge-complaint {
            background-color: #E76268;
        }

        .badge-pending {
            background-color: #4FADC0;
        }

        .badge-resolved {
            background-color: #10b981;
        }

        .badge-progress {
            background-color: #4FADC0;
        }

        .complaint-title {
            font-size: 1.125rem;
            font-weight: 700;
            color: #193948;
            margin: 0.5rem 0;
            line-height: 1.4;
        }

        .complaint-meta {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
            font-size: 0.8125rem;
            color: #193948;
        }

        .complaint-meta-item {
            display: flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.375rem 0.625rem;
            background-color: #D6BFBF;
            border: 1px solid #193948;
            border-radius: 6px;
            font-weight: 500;
        }

        .content-section {
            margin-bottom: 1rem;
        }

        .section-label {
            font-size: 0.875rem;
            font-weight: 700;
            color: #193948;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid #D6BFBF;
        }

        .message-box {
            background-color: white;
            border: 2px solid #193948;
            border-radius: 8px;
            padding: 0.875rem;
            color: #193948;
            white-space: pre-wrap;
            line-height: 1.6;
            font-size: 0.875rem;
        }

        .response-box {
            background-color: #F3EBDD;
            border: 3px solid #4FADC0;
            border-radius: 8px;
            padding: 0.75rem;
            margin-top: 1rem;
            position: relative;
            box-shadow: 0 2px 8px rgba(79, 173, 192, 0.2);
        }

        .response-header {
            font-size: 0.9375rem;
            font-weight: 700;
            color: #193948;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .response-text {
            color: #193948;
            white-space: pre-wrap;
            line-height: 1.5;
            margin-bottom: 0;
            font-size: 0.875rem;
            background-color: white;
            padding: 0.875rem;
            border-radius: 6px;
            border: 1px solid #4FADC0;
        }

        .response-meta {
            font-size: 0.75rem;
            color: #193948;
            padding-top: 0.5rem;
            margin-top: 0.5rem;
            border-top: 2px solid #4FADC0;
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            font-weight: 500;
        }

        .response-images-wrapper {
            margin-top: 0.75rem;
        }
        
        /* Prevent image opening */
        .complaints-container img {
            pointer-events: none !important;
            user-select: none !important;
            -webkit-user-select: none !important;
            -moz-user-select: none !important;
            -ms-user-select: none !important;
            cursor: default !important;
            -webkit-touch-callout: none !important;
            -webkit-tap-highlight-color: transparent !important;
        }

        .waiting-box {
            background-color: #F3EBDD;
            border: 2px dashed #4FADC0;
            border-radius: 8px;
            padding: 1rem;
            text-align: center;
            color: #193948;
            margin-top: 1rem;
        }

        .waiting-box div:first-child {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }

        .waiting-box div:last-child {
            font-weight: 600;
            font-size: 0.875rem;
        }

        .actions-section {
            display: none;
        }

        .btn-delete-small {
            background-color: #E76268;
            color: white;
            padding: 0.375rem 0.75rem;
            border-radius: 6px;
            border: 2px solid #193948;
            font-weight: 600;
            font-size: 0.75rem;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(231, 98, 104, 0.3);
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
        }

        .btn-delete-small:hover {
            transform: translateY(-1px);
            box-shadow: 0 3px 8px rgba(231, 98, 104, 0.4);
            background-color: #d4555a;
        }

        .btn-view-small {
            background-color: #4FADC0;
            color: #193948;
            padding: 0.375rem 0.75rem;
            border-radius: 6px;
            border: 2px solid #193948;
            font-weight: 600;
            font-size: 0.75rem;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(79, 173, 192, 0.3);
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
        }

        .btn-view-small:hover {
            transform: translateY(-1px);
            box-shadow: 0 3px 8px rgba(79, 173, 192, 0.4);
            background-color: #3d9db0;
        }

        .empty-state {
            text-align: center;
            padding: 3rem 2rem;
            background-color: #F3EBDD;
            border: 3px dashed #193948;
            border-radius: 12px;
        }

        .empty-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        .empty-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #193948;
            margin-bottom: 0.75rem;
        }

        .empty-text {
            font-size: 0.9375rem;
            color: #193948;
            margin-bottom: 1.5rem;
        }

        .alert-success {
            background-color: #F3EBDD;
            border: 2px solid #4FADC0;
            border-radius: 8px;
            padding: 0.875rem 1rem;
            margin-bottom: 1rem;
            color: #193948;
            font-weight: 600;
            font-size: 0.875rem;
            box-shadow: 0 2px 8px rgba(79, 173, 192, 0.2);
            animation: fadeInUp 0.4s ease-out;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .alert-success::before {
            content: '✓';
            font-size: 1.25rem;
            background-color: #4FADC0;
            color: #193948;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-weight: 700;
        }

        .alert-error {
            background-color: #F3EBDD;
            border: 2px solid #E76268;
            border-radius: 8px;
            padding: 0.875rem 1rem;
            margin-bottom: 1rem;
            color: #193948;
            font-weight: 600;
            font-size: 0.875rem;
            box-shadow: 0 2px 8px rgba(231, 98, 104, 0.2);
            animation: fadeInUp 0.4s ease-out;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .alert-error::before {
            content: '✕';
            font-size: 1.25rem;
            background-color: #E76268;
            color: white;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-weight: 700;
        }

        @media (max-width: 768px) {
            .complaints-container {
                padding: 1rem 0.75rem;
            }

            .stat-value {
                font-size: 1.5rem;
            }

            .stat-icon {
                font-size: 1.5rem;
            }

            .complaint-card {
                padding: 1rem;
            }

            .complaint-title {
                font-size: 1rem;
            }

            .page-title {
                font-size: 1.25rem;
            }

            .action-header {
                padding: 0.875rem;
            }

            .btn-submit {
                padding: 0.5rem 1rem;
                font-size: 0.8125rem;
            }

            .complaint-actions-top {
                flex-direction: column;
                gap: 0.375rem;
            }
        }
    </style>

    <div class="complaints-container">
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

        @php
            $totalComplaints = $stats['complaints_total'] ?? 0;
            $respondedCount = 0;
            foreach($complaints as $item) {
                $responseField = $item->target_role === 'gestionnaire' ? 'gestionnaire_response' : 'admin_response';
                if($item->{$responseField}) {
                    $respondedCount++;
                }
            }
        @endphp

        <!-- Action Header -->
        <div class="action-header">
            <h1 class="page-title">My Complaints</h1>
            <div class="action-header-stats">
                <div class="stat-card">
                    <span class="stat-icon">📊</span>
                    <div class="stat-value">{{ $totalComplaints }}</div>
                    <div class="stat-label">Total Complaints</div>
                </div>
                <div class="stat-card">
                    <span class="stat-icon">✅</span>
                    <div class="stat-value">{{ $respondedCount }}</div>
                    <div class="stat-label">Responded</div>
                </div>
                <div class="stat-card">
                    <span class="stat-icon">⏳</span>
                    <div class="stat-value">{{ $totalComplaints - $respondedCount }}</div>
                    <div class="stat-label">Pending</div>
                </div>
            </div>
            <a href="{{ route('artist.complaints.create') }}" class="btn-submit">
                <span>⚠️</span>
                <span>Submit New Complaint</span>
            </a>
        </div>

        <!-- Complaints List -->
        @forelse($complaints as $item)
            <div class="complaint-card">
                <!-- Header with Actions -->
                <div class="complaint-header">
                    <div style="flex: 1;">
                        <div class="complaint-header-top">
                            <div class="complaint-badges">
                                <span class="badge badge-complaint">Complaint</span>
                                <span class="badge badge-{{ strtolower(str_replace('_', '-', $item->status)) }}">
                                    @if($item->status === 'PENDING')
                                        ⏳ {{ str_replace('_', ' ', $item->status) }}
                                    @elseif($item->status === 'RESOLVED')
                                        ✅ {{ str_replace('_', ' ', $item->status) }}
                                    @else
                                        {{ str_replace('_', ' ', $item->status) }}
                                    @endif
                                </span>
                            </div>
                            <div class="complaint-actions-top">
                                @php
                                    $responseField = $item->target_role === 'gestionnaire' ? 'gestionnaire_response' : 'admin_response';
                                    $hasResponse = $item->{$responseField};
                                @endphp
                                @if($hasResponse)
                                    <button type="button" class="btn-response-toggle" onclick="toggleResponse({{ $item->id }})" id="btn-response-{{ $item->id }}">
                                        ✅ View Response
                                    </button>
                                @endif
                                <a href="{{ route('artist.complaints.show', $item->id) }}" class="btn-view-small">
                                    👁️ Open
                                </a>
                                @if($item->type === 'COMPLAINT')
                                    <form action="{{ route('artist.complaints.delete', $item->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this complaint?');" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-delete-small">
                                            🗑️ Delete
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                        <h3 class="complaint-title">{{ $item->subject }}</h3>
                        <div class="complaint-meta">
                            @if($item->agency)
                                <div class="complaint-meta-item">
                                    <span>📍</span>
                                    <span>{{ $item->agency->wilaya ?? 'N/A' }}</span>
                                </div>
                                <div class="complaint-meta-item">
                                    <span>🏢</span>
                                    <span>{{ $item->agency->agency_name ?? 'N/A' }}</span>
                                </div>
                            @endif
                            <div class="complaint-meta-item">
                                <span>📤</span>
                                <span>To: {{ ucfirst(str_replace('_', ' ', $item->target_role ?? 'admin')) }}</span>
                            </div>
                            <div class="complaint-meta-item">
                                <span>📅</span>
                                <span>{{ $item->created_at->format('Y-m-d H:i') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Message -->
                <div class="content-section">
                    <div class="section-label">
                        <span>💬</span>
                        <span>Message</span>
                    </div>
                    <div class="message-box">
                        {{ $item->message }}
                    </div>
                </div>

                <!-- Images -->
                @if($item->images && count($item->images) > 0)
                    <div class="content-section">
                        <div class="section-label">
                            <span>🖼️</span>
                            <span>Attachments</span>
                        </div>
                        <div style="background: white; border: 1px solid #193948; border-radius: 8px; padding: 0.875rem;">
                            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(80px, 1fr)); gap: 0.5rem;">
                                @foreach($item->images as $image)
                                    @php
                                        $imagePath = ltrim($image, '/');
                                        $imageUrl = asset('storage/' . $imagePath);
                                    @endphp
                                    <img src="{{ $imageUrl }}" alt="Attachment" style="width: 100%; height: 80px; object-fit: cover; border: 1px solid #193948; border-radius: 4px; display: block; pointer-events: none; user-select: none; -webkit-user-select: none; -moz-user-select: none; -ms-user-select: none; cursor: default;" draggable="false" oncontextmenu="return false;" onclick="return false;">
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Response -->
                @php
                    $responseField = $item->target_role === 'gestionnaire' ? 'gestionnaire_response' : 'admin_response';
                    $responseImagesField = $item->target_role === 'gestionnaire' ? 'gestionnaire_response_images' : 'admin_response_images';
                    $responseLabel = $item->target_role === 'gestionnaire' ? 'Gestionnaire Response' : 'Admin Response';
                    $responseValue = $item->{$responseField};
                    $responseImages = $item->{$responseImagesField} ?? [];
                    $responderName = $item->target_role === 'gestionnaire'
                        ? ($item->gestionnaire->name ?? $item->targetUser->name ?? 'Gestionnaire')
                        : ($item->admin->name ?? $item->targetUser->name ?? 'Admin');
                @endphp

                @if($responseValue)
                    <div class="response-content-hidden" id="response-{{ $item->id }}">
                        <div style="margin-bottom: 0.75rem;">
                            <label class="section-label" style="margin-bottom: 0.5rem; font-size: 0.875rem; font-weight: 600; color: #193948;">
                                {{ $responseLabel }}:
                            </label>
                            <div class="response-text-container">
                                {{ $responseValue }}
                            </div>
                        </div>
                        @if(is_array($responseImages) && count($responseImages) > 0)
                            <div style="margin-top: 0.75rem;">
                                <label class="section-label" style="margin-bottom: 0.5rem; font-size: 0.875rem; font-weight: 600; color: #193948;">
                                    Response Photos:
                                </label>
                                <div class="response-images-wrapper">
                                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(80px, 1fr)); gap: 0.5rem;">
                                        @foreach($responseImages as $image)
                                            @php
                                                $imagePath = ltrim($image, '/');
                                                $imageUrl = asset('storage/' . $imagePath);
                                            @endphp
                                            <img src="{{ $imageUrl }}" alt="Response Image" style="width: 100%; height: 80px; object-fit: cover; border: 1px solid #4FADC0; border-radius: 4px; display: block; pointer-events: none; user-select: none; -webkit-user-select: none; -moz-user-select: none; -ms-user-select: none; cursor: default;" draggable="false" oncontextmenu="return false;" onclick="return false;">
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="response-meta" style="margin-top: 0.75rem; padding-top: 0.75rem; border-top: 2px solid #4FADC0;">
                            <div>Responded by: <strong>{{ $responderName }}</strong></div>
                            <div>Date: <strong>{{ $item->updated_at->format('Y-m-d H:i') }}</strong></div>
                        </div>
                    </div>
                @endif
            </div>
        @empty
            <div class="empty-state">
                <div class="empty-icon">📭</div>
                <h3 class="empty-title">No Complaints Yet</h3>
                <p class="empty-text">You haven't submitted any complaints yet</p>
                <a href="{{ route('artist.complaints.create') }}" class="btn-submit">
                    <span>⚠️</span>
                    <span>Submit First Complaint</span>
                </a>
            </div>
        @endforelse

        <!-- Pagination -->
        @if($complaints->hasPages())
            <div style="display: flex; justify-content: center; margin-top: 1.5rem;">
                {{ $complaints->links() }}
            </div>
        @endif
    </div>

    <script>
        function toggleResponse(complaintId) {
            const responseDiv = document.getElementById('response-' + complaintId);
            const button = document.getElementById('btn-response-' + complaintId);
            
            if (responseDiv.classList.contains('response-content-hidden')) {
                responseDiv.classList.remove('response-content-hidden');
                responseDiv.classList.add('response-content-visible');
                button.textContent = '❌ Hide Response';
            } else {
                responseDiv.classList.remove('response-content-visible');
                responseDiv.classList.add('response-content-hidden');
                button.textContent = '✅ View Response';
            }
        }
        
        // Prevent all image opening behaviors
        document.addEventListener('DOMContentLoaded', function() {
            // Prevent image clicks
            document.querySelectorAll('.complaints-container img').forEach(function(img) {
                img.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    e.stopImmediatePropagation();
                    return false;
                }, true);
                
                img.addEventListener('mousedown', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    return false;
                }, true);
                
                img.addEventListener('dblclick', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    return false;
                }, true);
                
                img.addEventListener('contextmenu', function(e) {
                    e.preventDefault();
                    return false;
                }, true);
                
                img.addEventListener('dragstart', function(e) {
                    e.preventDefault();
                    return false;
                }, true);
                
                // Remove any href or onclick
                img.removeAttribute('href');
                img.removeAttribute('onclick');
                img.style.pointerEvents = 'none';
                img.style.cursor = 'default';
            });
        });
    </script>
</x-allthepages-layout>
