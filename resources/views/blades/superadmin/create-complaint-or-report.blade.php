<x-allthepages-layout pageTitle="تقديم شكوى" :disableZoom="true">
    <style>
        .form-container {
            max-width: 900px;
            margin: 0 auto;
            padding: 2rem 1rem;
        }
        
        .form-card {
            background: linear-gradient(135deg, #F3EBDD 0%, #D6BFBF 100%);
            border: 3px solid #193948;
            border-radius: 1.5rem;
            padding: 2.5rem;
            box-shadow: 0 10px 30px rgba(25, 57, 72, 0.15);
        }
        
        .form-header {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            margin-bottom: 2.5rem;
            padding-bottom: 1.5rem;
            border-bottom: 3px solid #193948;
        }
        
        .form-icon {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: linear-gradient(135deg, #E76268 0%, #d4555a 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            border: 3px solid #193948;
        }
        
        .form-title-section h2 {
            font-size: 2rem;
            font-weight: 700;
            color: #193948;
            margin: 0 0 0.5rem 0;
        }
        
        .form-title-section p {
            font-size: 1rem;
            color: #193948;
            opacity: 0.8;
            margin: 0;
        }
        
        .form-group {
            margin-bottom: 1.75rem;
        }
        
        .form-label {
            display: block;
            font-size: 1.1rem;
            font-weight: 700;
            color: #193948;
            margin-bottom: 0.75rem;
        }
        
        .form-label-required::after {
            content: ' *';
            color: #E76268;
        }
        
        .form-input,
        .form-select,
        .form-textarea {
            width: 100%;
            padding: 1rem;
            border: 2px solid #193948;
            border-radius: 0.75rem;
            color: #193948;
            font-size: 1rem;
            background-color: white;
            transition: all 0.3s ease;
        }
        
        .form-input:focus,
        .form-select:focus,
        .form-textarea:focus {
            outline: none;
            border-color: #4FADC0;
            box-shadow: 0 0 0 3px rgba(79, 173, 192, 0.1);
        }
        
        .form-textarea {
            resize: vertical;
            min-height: 150px;
            font-family: inherit;
        }
        
        .form-help {
            font-size: 0.9rem;
            color: #193948;
            opacity: 0.7;
            margin-top: 0.5rem;
        }
        
        .form-error {
            font-size: 0.9rem;
            color: #E76268;
            margin-top: 0.5rem;
            font-weight: 600;
        }
        
        .file-upload-wrapper {
            position: relative;
        }
        
        .file-upload-label {
            display: block;
            padding: 1rem;
            border: 2px dashed #193948;
            border-radius: 0.75rem;
            background: white;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .file-upload-label:hover {
            border-color: #4FADC0;
            background: #F3EBDD;
        }
        
        .file-upload-input {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
        }
        
        .form-actions {
            display: flex;
            gap: 1rem;
            margin-top: 2.5rem;
            padding-top: 2rem;
            border-top: 3px solid #193948;
            flex-wrap: wrap;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #E76268 0%, #d4555a 100%);
            color: white;
            padding: 1rem 2.5rem;
            border-radius: 0.75rem;
            border: 2px solid #193948;
            font-weight: 700;
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            flex: 1;
            min-width: 200px;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(231, 98, 104, 0.3);
        }
        
        .btn-secondary {
            background: white;
            color: #193948;
            padding: 1rem 2.5rem;
            border-radius: 0.75rem;
            border: 2px solid #193948;
            font-weight: 600;
            font-size: 1.1rem;
            text-decoration: none;
            text-align: center;
            transition: all 0.3s ease;
            flex: 1;
            min-width: 200px;
        }
        
        .btn-secondary:hover {
            background: #F3EBDD;
            transform: translateY(-2px);
        }
        
        @media (max-width: 768px) {
            .form-container {
                padding: 1rem 0.5rem;
            }
            
            .form-card {
                padding: 1.5rem;
            }
            
            .form-header {
                flex-direction: column;
                text-align: center;
            }
            
            .form-title-section h2 {
                font-size: 1.5rem;
            }
        }
    </style>

    <div class="form-container">
        <div class="form-card">
            <div class="form-header">
                <div class="form-icon">⚠️</div>
                <div class="form-title-section">
                    <h2>تقديم شكوى جديدة</h2>
                    <p>أبلغ عن مشكلة أو مخالفة على مستوى النظام</p>
                </div>
            </div>
            
            <form action="{{ route('superadmin.complaints.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="type" value="complaint">
                
                @if(!empty($targets))
                    <div class="form-group">
                        <label for="target_role" class="form-label form-label-required">إرسال إلى</label>
                        <select name="target_role" id="target_role" required class="form-select">
                            @foreach($targets as $target)
                                <option value="{{ $target }}" @selected(old('target_role') === $target)>
                                    {{ ucfirst(str_replace('_', ' ', $target)) }}
                                </option>
                            @endforeach
                        </select>
                        <p class="form-help">اختر من يجب أن يستلم هذه الشكوى</p>
                        @error('target_role')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                @endif

                <div class="form-group">
                    <label for="target_user_id" class="form-label">المستخدم المستهدف (اختياري)</label>
                    <input type="number" name="target_user_id" id="target_user_id" value="{{ old('target_user_id') }}"
                        placeholder="معرف المستخدم (اتركه فارغاً لإرسال إلى جميع المستخدمين بالدور المحدد)"
                        class="form-input">
                    <p class="form-help">حدد معرف مستخدم محدد، أو اتركه فارغاً لإرسال إلى جميع المستخدمين بالدور المحدد</p>
                    @error('target_user_id')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="subject" class="form-label form-label-required">الموضوع</label>
                    <input type="text" name="subject" id="subject" value="{{ old('subject') }}" required
                        placeholder="أدخل موضوع واضح للشكوى"
                        class="form-input">
                    @error('subject')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="message" class="form-label form-label-required">الرسالة</label>
                    <textarea name="message" id="message" rows="10" required
                        placeholder="اشرح شكواك بالتفصيل..."
                        class="form-textarea">{{ old('message') }}</textarea>
                    @error('message')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="location_link" class="form-label">رابط الموقع (اختياري)</label>
                    <input type="url" name="location_link" id="location_link" value="{{ old('location_link') }}"
                        placeholder="https://maps.google.com/..."
                        class="form-input">
                    <p class="form-help">أضف رابط خرائط جوجل أو أي مرجع موقع إذا كان ذا صلة</p>
                    @error('location_link')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="images" class="form-label">الصور (اختياري، حد أقصى 5 صور، 10 ميجابايت لكل صورة)</label>
                    <div class="file-upload-wrapper">
                        <label for="images" class="file-upload-label">
                            <div style="font-size: 2rem; margin-bottom: 0.5rem;">📷</div>
                            <div style="font-weight: 600; color: #193948;">انقر لاختيار الصور</div>
                            <div style="font-size: 0.9rem; color: #193948; opacity: 0.7; margin-top: 0.25rem;">يمكنك رفع حتى 5 صور كدليل أو توثيق</div>
                        </label>
                        <input type="file" name="images[]" id="images" multiple accept="image/*" class="file-upload-input">
                    </div>
                    @error('images.*')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-primary">
                        ⚠️ تقديم الشكوى
                    </button>
                    <a href="{{ route('superadmin.complaints') }}" class="btn-secondary">
                        إلغاء
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-allthepages-layout>
