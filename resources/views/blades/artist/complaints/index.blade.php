<x-allthepages-layout pageTitle="الشكاوى">
    <style>
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
        }

        @keyframes shimmer {
            0% {
                background-position: -1000px 0;
            }
            100% {
                background-position: 1000px 0;
            }
        }

        .complaints-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 3rem 1.5rem;
            background: linear-gradient(135deg, #faf8f5 0%, #f5f0e8 100%);
            min-height: 100vh;
            position: relative;
        }

        .complaints-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 400px;
            background: linear-gradient(135deg, rgba(231, 98, 104, 0.08) 0%, rgba(214, 191, 191, 0.12) 50%, rgba(79, 173, 192, 0.08) 100%);
            border-radius: 0 0 50% 50% / 0 0 30% 30%;
            z-index: 0;
        }

        .complaints-container > * {
            position: relative;
            z-index: 1;
        }

        .stats-section {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
            margin-bottom: 3.5rem;
            animation: fadeInUp 0.6s ease-out;
        }

        .stat-card {
            background: linear-gradient(145deg, #ffffff 0%, #f9f7f3 100%);
            border: 3px solid transparent;
            border-radius: 24px;
            padding: 2.5rem 2rem;
            text-align: center;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(25, 57, 72, 0.08);
        }

        .stat-card::before {
            display: none;
        }

        .stat-card:hover {
            transform: translateY(-12px) scale(1.02);
            box-shadow: 0 20px 50px rgba(25, 57, 72, 0.2);
            border-color: #193948;
        }

        .stat-card:nth-child(1) {
            background: linear-gradient(145deg, #fff5f5 0%, #ffe8e8 100%);
        }

        .stat-card:nth-child(2) {
            background: linear-gradient(145deg, #f0fdf4 0%, #dcfce7 100%);
        }

        .stat-card:nth-child(3) {
            background: linear-gradient(145deg, #fffbeb 0%, #fef3c7 100%);
        }

        .stat-icon {
            font-size: 3.5rem;
            margin-bottom: 1rem;
            display: block;
            filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.1));
            animation: pulse 2s ease-in-out infinite;
        }

        .stat-value {
            font-size: 3.5rem;
            font-weight: 800;
            background: linear-gradient(135deg, #193948 0%, #2a4a5a 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0.75rem;
            line-height: 1.2;
            letter-spacing: -2px;
        }

        .stat-card:nth-child(1) .stat-value {
            background: linear-gradient(135deg, #E76268 0%, #c45056 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .stat-card:nth-child(2) .stat-value {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .stat-card:nth-child(3) .stat-value {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .stat-label {
            font-size: 1.15rem;
            color: #193948;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .action-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 3rem;
            flex-wrap: wrap;
            gap: 2rem;
            padding: 2rem;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.9) 0%, rgba(249, 247, 243, 0.9) 100%);
            border-radius: 24px;
            box-shadow: 0 8px 32px rgba(25, 57, 72, 0.1);
            backdrop-filter: blur(10px);
            animation: fadeInUp 0.8s ease-out 0.2s both;
        }

        .page-title {
            font-size: 2.75rem;
            font-weight: 800;
            background: linear-gradient(135deg, #193948 0%, #E76268 50%, #4FADC0 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin: 0;
            letter-spacing: -1px;
            position: relative;
            padding-bottom: 1rem;
        }

        .page-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100px;
            height: 4px;
            background: linear-gradient(90deg, #E76268 0%, #4FADC0 100%);
            border-radius: 2px;
        }

        .btn-submit {
            background: #a7444a;
            color: white;
            padding: 1.125rem 2.5rem;
            border-radius: 16px;
            text-decoration: none;
            font-weight: 700;
            font-size: 1.1rem;
            border: 3px solid #193948;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            box-shadow: 0 8px 24px rgba(25, 57, 72, 0.25);
            position: relative;
            overflow: hidden;
        }

        .btn-submit::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .btn-submit:hover::before {
            width: 300px;
            height: 300px;
        }

        .btn-submit:hover {
            transform: translateY(-4px) scale(1.05);
            box-shadow: 0 12px 32px rgba(25, 57, 72, 0.35);
            border-color: #193948;
            background: #8f3a40;
        }

        .btn-submit span {
            position: relative;
            z-index: 1;
        }

        .complaint-card {
            background: linear-gradient(145deg, #ffffff 0%, #faf8f5 100%);
            border: 3px solid #193948;
            border-radius: 28px;
            padding: 3rem;
            margin-bottom: 2.5rem;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: visible;
            box-shadow: 0 12px 40px rgba(25, 57, 72, 0.1);
            animation: fadeInUp 0.6s ease-out;
        }

        .complaint-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 6px;
            height: 100%;
            background: linear-gradient(180deg, #E76268 0%, #D6BFBF 50%, #4FADC0 100%);
            opacity: 0;
            transition: opacity 0.4s;
        }

        .complaint-card:hover::before {
            opacity: 1;
        }

        .complaint-card:hover {
            box-shadow: 0 20px 60px rgba(25, 57, 72, 0.2);
            transform: translateY(-8px);
            border-color: #E76268;
        }

        .complaint-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 2rem;
            padding-bottom: 2rem;
            border-bottom: 3px solid transparent;
            border-image: linear-gradient(90deg, #193948 0%, #D6BFBF 50%, transparent 100%) 1;
            flex-wrap: wrap;
            gap: 1.5rem;
        }

        .complaint-badges {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .badge {
            padding: 0.75rem 1.5rem;
            border-radius: 50px;
            font-size: 0.95rem;
            font-weight: 700;
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            transition: all 0.3s ease;
            letter-spacing: 0.5px;
        }

        .badge:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.25);
        }

        .badge-complaint {
            background: linear-gradient(135deg, #E76268 0%, #d4555a 100%);
        }

        .badge-pending {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        }

        .badge-resolved {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }

        .badge-progress {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
        }

        .complaint-title {
            font-size: 1.875rem;
            font-weight: 800;
            color: #193948;
            margin: 1rem 0 1rem 0;
            line-height: 1.4;
            letter-spacing: -0.5px;
        }

        .complaint-meta {
            display: flex;
            gap: 2rem;
            flex-wrap: wrap;
            font-size: 1rem;
            color: #193948;
        }

        .complaint-meta-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1.25rem;
            background: linear-gradient(135deg, rgba(25, 57, 72, 0.05) 0%, rgba(214, 191, 191, 0.1) 100%);
            border-radius: 12px;
            font-weight: 600;
        }

        .complaint-meta-item span:first-child {
            font-size: 1.25rem;
        }

        .content-section {
            margin-bottom: 2rem;
        }

        .section-label {
            font-size: 1.25rem;
            font-weight: 800;
            color: #193948;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid rgba(25, 57, 72, 0.1);
        }

        .section-label span:first-child {
            font-size: 1.5rem;
        }

        .message-box {
            background: linear-gradient(135deg, #ffffff 0%, #f9f7f3 100%);
            border: 2px solid #193948;
            border-radius: 20px;
            padding: 2rem;
            color: #193948;
            white-space: pre-wrap;
            line-height: 1.8;
            font-size: 1.05rem;
            box-shadow: inset 0 2px 8px rgba(25, 57, 72, 0.05);
        }

        .response-box {
            background: linear-gradient(135deg, rgba(79, 173, 192, 0.08) 0%, rgba(214, 191, 191, 0.12) 100%);
            border: 3px solid #4FADC0;
            border-radius: 24px;
            padding: 2.5rem;
            margin-top: 1.5rem;
            position: relative;
            box-shadow: 0 8px 32px rgba(79, 173, 192, 0.15);
            overflow: visible;
        }

        .response-box::before {
            content: '✅';
            position: absolute;
            top: -20px;
            right: 30px;
            font-size: 2.5rem;
            background: white;
            padding: 0.5rem;
            border-radius: 50%;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .response-header {
            font-size: 1.5rem;
            font-weight: 800;
            color: #193948;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            padding-top: 0.5rem;
        }

        .response-text {
            color: #193948;
            white-space: pre-wrap;
            line-height: 1.8;
            margin-bottom: 1.5rem;
            font-size: 1.05rem;
            background: rgba(255, 255, 255, 0.6);
            padding: 1.5rem;
            border-radius: 16px;
            border: 1px solid rgba(79, 173, 192, 0.2);
        }

        .response-meta {
            font-size: 0.95rem;
            color: #193948;
            opacity: 0.8;
            padding-top: 1.25rem;
            border-top: 2px solid rgba(79, 173, 192, 0.3);
            display: flex;
            gap: 2rem;
            flex-wrap: wrap;
            font-weight: 600;
        }

        .waiting-box {
            background: linear-gradient(135deg, #FEF3C7 0%, #FDE68A 100%);
            border: 3px dashed #f59e0b;
            border-radius: 24px;
            padding: 2.5rem;
            text-align: center;
            color: #193948;
            margin-top: 1.5rem;
            position: relative;
            overflow: hidden;
        }

        .waiting-box::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent 30%, rgba(255, 255, 255, 0.3) 50%, transparent 70%);
            animation: shimmer 3s infinite;
        }

        .waiting-box > * {
            position: relative;
            z-index: 1;
        }

        .waiting-box div:first-child {
            font-size: 3rem;
            margin-bottom: 1rem;
            animation: pulse 2s ease-in-out infinite;
        }

        .waiting-box div:last-child {
            font-weight: 700;
            font-size: 1.25rem;
        }

        .actions-section {
            display: flex;
            justify-content: flex-end;
            gap: 1.5rem;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 3px solid transparent;
            border-image: linear-gradient(90deg, transparent 0%, #D6BFBF 50%, transparent 100%) 1;
        }

        .btn-delete {
            background: #9f4046;
            color: white;
            padding: 0.875rem 2rem;
            border-radius: 14px;
            border: 3px solid #193948;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 6px 20px rgba(25, 57, 72, 0.25);
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-delete:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 10px 28px rgba(25, 57, 72, 0.35);
            border-color: #193948;
            background: #86363b;
        }

        .empty-state {
            text-align: center;
            padding: 6rem 3rem;
            background: linear-gradient(135deg, #ffffff 0%, #f9f7f3 100%);
            border: 3px dashed #193948;
            border-radius: 32px;
            position: relative;
            overflow: hidden;
        }

        .empty-state::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(231, 98, 104, 0.05) 0%, transparent 70%);
            animation: pulse 4s ease-in-out infinite;
        }

        .empty-state > * {
            position: relative;
            z-index: 1;
        }

        .empty-icon {
            font-size: 6rem;
            margin-bottom: 2rem;
            filter: drop-shadow(0 8px 16px rgba(0, 0, 0, 0.1));
            animation: pulse 2s ease-in-out infinite;
        }

        .empty-title {
            font-size: 2.25rem;
            font-weight: 800;
            background: linear-gradient(135deg, #193948 0%, #E76268 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 1.5rem;
        }

        .empty-text {
            font-size: 1.25rem;
            color: #193948;
            opacity: 0.8;
            margin-bottom: 3rem;
            font-weight: 500;
        }

        .alert-success {
            background: linear-gradient(135deg, #D1FAE5 0%, #6EE7B7 100%);
            border: 3px solid #10b981;
            border-radius: 20px;
            padding: 1.5rem 2rem;
            margin-bottom: 2.5rem;
            color: #193948;
            font-weight: 700;
            font-size: 1.1rem;
            box-shadow: 0 8px 24px rgba(16, 185, 129, 0.2);
            animation: fadeInUp 0.6s ease-out;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .alert-success::before {
            content: '✓';
            font-size: 2rem;
            background: #10b981;
            color: white;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }


        @media (max-width: 768px) {
            .complaints-container {
                padding: 2rem 1rem;
            }

            .stat-value {
                font-size: 2.5rem;
            }

            .stat-icon {
                font-size: 2.5rem;
            }

            .complaint-card {
                padding: 2rem 1.5rem;
            }

            .complaint-title {
                font-size: 1.5rem;
            }

            .page-title {
                font-size: 2rem;
            }

            .action-header {
                padding: 1.5rem;
            }

            .btn-submit {
                padding: 1rem 2rem;
                font-size: 1rem;
            }
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
    </style>
    
    <script>
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

    <div class="complaints-container">
        @if(session('success'))
            <div class="alert-success">
                <p>{{ session('success') }}</p>
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

        <!-- Statistics Section -->
        <div class="stats-section">
            <div class="stat-card">
                <span class="stat-icon">📊</span>
                <div class="stat-value">{{ $totalComplaints }}</div>
                <div class="stat-label">إجمالي الشكاوى</div>
            </div>
            <div class="stat-card">
                <span class="stat-icon">✅</span>
                <div class="stat-value">{{ $respondedCount }}</div>
                <div class="stat-label">تم الرد عليها</div>
            </div>
            <div class="stat-card">
                <span class="stat-icon">⏳</span>
                <div class="stat-value">{{ $totalComplaints - $respondedCount }}</div>
                <div class="stat-label">قيد الانتظار</div>
            </div>
        </div>

        <!-- Action Header -->
        <div class="action-header">
            <h1 class="page-title">الشكاوى الخاصة بي</h1>
            <a href="{{ route('artist.complaints.create', ['type' => 'complaint']) }}" class="btn-submit">
                <span>⚠️</span>
                <span>تقديم شكوى جديدة</span>
            </a>
        </div>

        <!-- Complaints List -->
        @forelse($complaints as $item)
            <div class="complaint-card">
                <!-- Header -->
                <div class="complaint-header">
                    <div style="flex: 1;">
                        <div class="complaint-badges">
                            <span class="badge badge-complaint">شكوى</span>
                            <span class="badge badge-{{ strtolower(str_replace('_', '-', $item->status)) }}">
                                {{ str_replace('_', ' ', $item->status) }}
                            </span>
                        </div>
                        <h3 class="complaint-title">{{ $item->subject }}</h3>
                        <div class="complaint-meta">
                            <div class="complaint-meta-item">
                                <span>📤</span>
                                <span>إلى: {{ ucfirst(str_replace('_', ' ', $item->target_role ?? 'admin')) }}</span>
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
                        <span>الرسالة</span>
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
                            <span>المرفقات</span>
                        </div>
                        <div style="background: white; border: 2px solid #193948; border-radius: 20px; padding: 1.5rem;">
                            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 1rem;">
                                @foreach($item->images as $image)
                                    @php
                                        $imagePath = ltrim($image, '/');
                                        $imageUrl = asset('storage/' . $imagePath);
                                    @endphp
                                    <img src="{{ $imageUrl }}" alt="Attachment" style="width: 100%; height: 150px; object-fit: cover; border: 2px solid #193948; border-radius: 8px; display: block; pointer-events: none; user-select: none; -webkit-user-select: none; -moz-user-select: none; -ms-user-select: none; cursor: default;" draggable="false" oncontextmenu="return false;" onclick="return false;">
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Response -->
                @php
                    $responseField = $item->target_role === 'gestionnaire' ? 'gestionnaire_response' : 'admin_response';
                    $responseImagesField = $item->target_role === 'gestionnaire' ? 'gestionnaire_response_images' : 'admin_response_images';
                    $responseLabel = $item->target_role === 'gestionnaire' ? 'رد المسؤول' : 'رد الإدارة';
                    $responseValue = $item->{$responseField};
                    $responseImages = $item->{$responseImagesField} ?? [];
                    $responderName = $item->target_role === 'gestionnaire'
                        ? ($item->gestionnaire->name ?? $item->targetUser->name ?? 'المسؤول')
                        : ($item->admin->name ?? $item->targetUser->name ?? 'الإدارة');
                @endphp

                @if($responseValue)
                    <div class="response-box">
                        <div class="response-header">
                            <span>{{ $responseLabel }}</span>
                        </div>
                        <div class="response-text">
                            {{ $responseValue }}
                        </div>

                        @if(is_array($responseImages) && count($responseImages) > 0)
                            <div style="margin-top: 1.5rem;">
                                <div class="section-label" style="margin-bottom: 1rem;">
                                    <span>🖼️</span>
                                    <span>صور الرد</span>
                                </div>
                                <div style="background: white; border: 2px solid #4FADC0; border-radius: 20px; padding: 1.5rem;">
                                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 1rem;">
                                        @foreach($responseImages as $image)
                                            @php
                                                $imagePath = ltrim($image, '/');
                                                $imageUrl = asset('storage/' . $imagePath);
                                            @endphp
                                            <img src="{{ $imageUrl }}" alt="Response Image" style="width: 100%; height: 150px; object-fit: cover; border: 2px solid #4FADC0; border-radius: 8px; display: block; pointer-events: none; user-select: none; -webkit-user-select: none; -moz-user-select: none; -ms-user-select: none; cursor: default;" draggable="false" oncontextmenu="return false;" onclick="return false;">
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="response-meta">
                            <div>تم الرد بواسطة: <strong>{{ $responderName }}</strong></div>
                            <div>التاريخ: <strong>{{ $item->updated_at->format('Y-m-d H:i') }}</strong></div>
                        </div>
                    </div>
                @else
                    <div class="waiting-box">
                        <div>⏳</div>
                        <div>في انتظار رد {{ $item->target_role === 'gestionnaire' ? 'المسؤول' : 'الإدارة' }}...</div>
                    </div>
                @endif

                <!-- Actions -->
                <div class="actions-section">
                    <form action="{{ route('artist.complaints.delete', $item->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذه الشكوى؟');" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-delete">
                            🗑️ حذف الشكوى
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <div class="empty-icon">📭</div>
                <h3 class="empty-title">لا توجد شكاوى بعد</h3>
                <p class="empty-text">لم تقم بتقديم أي شكاوى حتى الآن</p>
                <a href="{{ route('artist.complaints.create', ['type' => 'complaint']) }}" class="btn-submit">
                    <span>⚠️</span>
                    <span>تقديم أول شكوى</span>
                </a>
            </div>
        @endforelse

        <!-- Pagination -->
        @if($complaints->hasPages())
            <div style="display: flex; justify-content: center; margin-top: 3rem;">
                {{ $complaints->links() }}
            </div>
        @endif
    </div>
</x-allthepages-layout>
