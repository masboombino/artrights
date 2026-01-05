<x-allthepages-layout pageTitle="تفاصيل الشكوى">
    <style>
        .complaint-detail-container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 2rem 1rem;
        }
        
        .back-link {
            display: inline-block;
            margin-bottom: 1.5rem;
            padding: 0.75rem 1.5rem;
            background: linear-gradient(135deg, #F3EBDD 0%, #D6BFBF 100%);
            color: #193948;
            border: 2px solid #193948;
            border-radius: 0.75rem;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .back-link:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(25, 57, 72, 0.2);
        }
        
        .detail-card {
            background: linear-gradient(135deg, #F3EBDD 0%, #D6BFBF 100%);
            border: 3px solid #193948;
            border-radius: 1.5rem;
            padding: 2.5rem;
            box-shadow: 0 10px 30px rgba(25, 57, 72, 0.15);
        }
        
        .card-title {
            font-size: 2rem;
            font-weight: 700;
            color: #193948;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 3px solid #193948;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .info-box {
            background: white;
            border: 2px solid #193948;
            border-radius: 0.75rem;
            padding: 1.25rem;
            text-align: center;
        }
        
        .info-label {
            display: block;
            font-size: 0.9rem;
            font-weight: 700;
            color: #193948;
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .info-value {
            font-size: 1.1rem;
            color: #36454f;
            font-weight: 600;
        }
        
        .status-badge {
            display: inline-block;
            padding: 0.5rem 1rem;
            border-radius: 2rem;
            font-size: 0.875rem;
            font-weight: 700;
            color: white;
        }
        
        .status-resolved {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }
        
        .status-pending {
            background: linear-gradient(135deg, #E76268 0%, #d4555a 100%);
        }
        
        .content-section {
            margin-bottom: 2rem;
        }
        
        .section-label {
            font-size: 1.1rem;
            font-weight: 700;
            color: #193948;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .message-box {
            background: white;
            border: 2px solid #193948;
            border-radius: 0.75rem;
            padding: 1.5rem;
            color: #36454f;
            white-space: pre-wrap;
            line-height: 1.8;
            font-size: 1rem;
        }
        
        .response-section {
            margin-top: 2.5rem;
            padding-top: 2rem;
            border-top: 3px solid #193948;
        }
        
        .response-form {
            background: white;
            border: 2px solid #4FADC0;
            border-radius: 0.75rem;
            padding: 2rem;
        }
        
        .response-display {
            background: linear-gradient(135deg, #F3EBDD 0%, #D6BFBF 100%);
            border: 2px solid #4FADC0;
            border-radius: 0.75rem;
            padding: 1.5rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-label {
            display: block;
            font-size: 1rem;
            font-weight: 700;
            color: #193948;
            margin-bottom: 0.75rem;
        }
        
        .form-label-required::after {
            content: ' *';
            color: #E76268;
        }
        
        .form-textarea {
            width: 100%;
            padding: 1rem;
            border: 2px solid #193948;
            border-radius: 0.75rem;
            color: #193948;
            font-size: 1rem;
            background-color: white;
            resize: vertical;
            min-height: 150px;
            font-family: inherit;
        }
        
        .form-textarea:focus {
            outline: none;
            border-color: #4FADC0;
            box-shadow: 0 0 0 3px rgba(79, 173, 192, 0.1);
        }
        
        .form-input {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #193948;
            border-radius: 0.5rem;
            color: #193948;
            font-size: 1rem;
            background-color: white;
        }
        
        .form-input:focus {
            outline: none;
            border-color: #4FADC0;
            box-shadow: 0 0 0 3px rgba(79, 173, 192, 0.1);
        }
        
        .form-error {
            font-size: 0.9rem;
            color: #E76268;
            margin-top: 0.5rem;
            font-weight: 600;
        }
        
        .btn-submit {
            background: linear-gradient(135deg, #193948 0%, #2a4a5a 100%);
            color: #4FADC0;
            padding: 1rem 2.5rem;
            border-radius: 0.75rem;
            border: 2px solid #193948;
            font-weight: 700;
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(25, 57, 72, 0.3);
        }
        
        .response-text {
            color: #36454f;
            white-space: pre-wrap;
            line-height: 1.8;
            font-size: 1rem;
            margin-bottom: 1rem;
        }
        
        .response-meta {
            font-size: 0.9rem;
            color: #193948;
            opacity: 0.8;
            padding-top: 1rem;
            border-top: 1px solid rgba(25, 57, 72, 0.2);
        }
        
        @media (max-width: 768px) {
            .complaint-detail-container {
                padding: 1rem 0.5rem;
            }
            
            .detail-card {
                padding: 1.5rem;
            }
            
            .card-title {
                font-size: 1.5rem;
            }
            
            .info-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="complaint-detail-container">
        <a href="{{ route('superadmin.complaints') }}" class="back-link">
            ← العودة إلى قائمة الشكاوى
        </a>
        
        <div class="detail-card">
            <h2 class="card-title">تفاصيل الشكوى</h2>
            
            <!-- Info Grid -->
            <div class="info-grid">
                <div class="info-box">
                    <span class="info-label">الإدارة</span>
                    <div class="info-value">{{ $complaint->admin->name ?? 'غير متوفر' }}</div>
                </div>
                <div class="info-box">
                    <span class="info-label">الوكالة</span>
                    <div class="info-value">
                        {{ $complaint->admin->agency ? $complaint->admin->agency->agency_name . ' - ' . $complaint->admin->agency->wilaya : 'غير متوفر' }}
                    </div>
                </div>
                <div class="info-box">
                    <span class="info-label">الحالة</span>
                    <span class="status-badge status-{{ strtolower($complaint->status) }}">
                        {{ $complaint->status }}
                    </span>
                </div>
                <div class="info-box">
                    <span class="info-label">الموضوع</span>
                    <div class="info-value">{{ $complaint->subject }}</div>
                </div>
                <div class="info-box">
                    <span class="info-label">تاريخ الإرسال</span>
                    <div class="info-value">{{ $complaint->created_at->format('Y-m-d H:i') }}</div>
                </div>
            </div>

            <!-- Message -->
            <div class="content-section">
                <div class="section-label">
                    <span>💬</span>
                    <span>الرسالة</span>
                </div>
                <div class="message-box">
                    {{ $complaint->message }}
                </div>
            </div>

            <!-- Images -->
            @if($complaint->images && count($complaint->images) > 0)
                <div class="content-section">
                    <div class="section-label">
                        <span>🖼️</span>
                        <span>صور الشكوى</span>
                    </div>
                    @include('blades.partials.complaint-gallery', [
                        'galleryId' => 'superadmin-complaint-' . $complaint->id,
                        'images' => $complaint->images
                    ])
                </div>
            @endif

            <!-- Response Section -->
            <div class="response-section">
                <h3 class="section-label" style="font-size: 1.5rem; margin-bottom: 1.5rem;">
                    <span>💬</span>
                    <span>رد السوبر أدمن</span>
                </h3>

                @if($complaint->status !== 'RESOLVED')
                    <div class="response-form">
                        <form method="POST" action="{{ route('superadmin.respond-admin-complaint', $complaint->id) }}" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="super_admin_response" class="form-label form-label-required">الرد</label>
                                <textarea name="super_admin_response" id="super_admin_response" rows="8" required
                                    class="form-textarea">{{ old('super_admin_response') }}</textarea>
                                @error('super_admin_response')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="super_admin_response_images" class="form-label">صور الرد (اختياري، حد أقصى 5)</label>
                                <input type="file" name="super_admin_response_images[]" id="super_admin_response_images" multiple accept="image/*"
                                    class="form-input">
                                @error('super_admin_response_images.*')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>
                            <button type="submit" class="btn-submit">
                                إرسال الرد
                            </button>
                        </form>
                    </div>
                @else
                    <div class="response-display">
                        <div class="response-text">
                            {{ $complaint->super_admin_response }}
                        </div>
                        
                        @if($complaint->super_admin_response_images && count($complaint->super_admin_response_images) > 0)
                            <div style="margin-top: 1.5rem;">
                                <div class="section-label" style="margin-bottom: 1rem;">
                                    <span>🖼️</span>
                                    <span>صور الرد</span>
                                </div>
                                @include('blades.partials.complaint-gallery', [
                                    'galleryId' => 'superadmin-response-' . $complaint->id,
                                    'images' => $complaint->super_admin_response_images
                                ])
                            </div>
                        @endif
                        
                        <div class="response-meta">
                            <div>تم الرد بواسطة: {{ $complaint->superAdmin->name ?? 'غير متوفر' }}</div>
                            <div>التاريخ: {{ $complaint->updated_at->format('Y-m-d H:i') }}</div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-allthepages-layout>
