<x-allthepages-layout pageTitle="Manage Law Content">
    <div class="space-y-6">
        <div style="background-color: #F3EBDD; padding: 1.5rem; border-radius: 0.5rem; margin-bottom: 1.5rem;">
            <h1 style="color: #193948; font-size: 2rem; font-weight: 700; margin-bottom: 0.5rem;">
                Manage Law Content
            </h1>
            <p style="color: #193948; font-size: 1rem;">
                Edit the legal content that appears to artists and agents. Changes will be reflected immediately in both the website and mobile app.
            </p>
        </div>

        <!-- Language Tabs -->
        <div style="background-color: #F3EBDD; border-radius: 0.5rem; padding: 0.5rem; margin-bottom: 1.5rem;">
            <div style="display: flex; gap: 0.5rem;">
                <button type="button" 
                        onclick="switchLanguage('english')" 
                        id="tab-english"
                        class="language-tab active"
                        style="flex: 1; padding: 0.75rem 1rem; border-radius: 0.5rem; border: none; background-color: #193948; color: #F3EBDD; font-weight: 700; cursor: pointer; transition: all 0.3s;">
                    English
                </button>
                <button type="button" 
                        onclick="switchLanguage('french')" 
                        id="tab-french"
                        class="language-tab"
                        style="flex: 1; padding: 0.75rem 1rem; border-radius: 0.5rem; border: none; background-color: rgba(25, 57, 72, 0.3); color: #193948; font-weight: 700; cursor: pointer; transition: all 0.3s;">
                    Français
                </button>
                <button type="button" 
                        onclick="switchLanguage('arabic')" 
                        id="tab-arabic"
                        class="language-tab"
                        style="flex: 1; padding: 0.75rem 1rem; border-radius: 0.5rem; border: none; background-color: rgba(25, 57, 72, 0.3); color: #193948; font-weight: 700; cursor: pointer; transition: all 0.3s;">
                    العربية
                </button>
            </div>
        </div>

        <!-- English Section -->
        <form action="{{ route('superadmin.update-law') }}" method="POST" class="law-form" id="form-english" style="display: block;">
            @csrf
            <input type="hidden" name="language" value="english">
            
            <div class="rounded-lg shadow p-6" style="background-color: #F3EBDD; margin-bottom: 2rem;">
                <h2 class="text-2xl font-semibold mb-4" style="color: #193948;">English Law Content</h2>
                
                <div class="mb-4">
                    <label for="english_title" class="block text-sm font-medium mb-2" style="color: #193948;">Title</label>
                    <input type="text" 
                           id="english_title" 
                           name="title" 
                           value="{{ old('title', $englishLaw->title ?? '') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           required>
                </div>

                <div class="mb-4">
                    <label for="english_notice" class="block text-sm font-medium mb-2" style="color: #193948;">Important Notice</label>
                    <textarea id="english_notice" 
                              name="notice" 
                              rows="3"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                              required>{{ old('notice', $englishLaw->notice ?? '') }}</textarea>
                </div>

                <div id="english_sections" class="space-y-4">
                    @if(isset($englishLaw) && $englishLaw->sections)
                        @foreach($englishLaw->sections as $index => $section)
                            <div class="section-item p-4 rounded" style="background-color: #ffffff; border: 2px solid #193948;">
                                <div class="mb-3">
                                    <label class="block text-sm font-medium mb-2" style="color: #193948;">Section Title</label>
                                    <input type="text" 
                                           name="sections[{{ $index }}][title]" 
                                           value="{{ $section['title'] ?? '' }}"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg"
                                           required>
                                </div>
                                <div class="mb-3">
                                    <label class="block text-sm font-medium mb-2" style="color: #193948;">Content</label>
                                    <textarea name="sections[{{ $index }}][content]" 
                                              rows="3"
                                              class="w-full px-4 py-2 border border-gray-300 rounded-lg"
                                              required>{{ $section['content'] ?? '' }}</textarea>
                                </div>
                                @if(isset($section['formula']))
                                    <div class="mb-3">
                                        <label class="block text-sm font-medium mb-2" style="color: #193948;">Formula</label>
                                        <input type="text" 
                                               name="sections[{{ $index }}][formula]" 
                                               value="{{ $section['formula'] ?? '' }}"
                                               class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                                    </div>
                                @endif
                                @if(isset($section['items']) && is_array($section['items']))
                                    <div class="mb-3">
                                        <label class="block text-sm font-medium mb-2" style="color: #193948;">Items (one per line)</label>
                                        <textarea name="sections[{{ $index }}][items_text]" 
                                                  rows="4"
                                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg">{{ implode("\n", $section['items']) }}</textarea>
                                    </div>
                                @endif
                                <div class="mb-3">
                                    <label class="flex items-center">
                                        <input type="checkbox" 
                                               name="sections[{{ $index }}][highlight]" 
                                               value="1"
                                               {{ isset($section['highlight']) && $section['highlight'] ? 'checked' : '' }}
                                               class="mr-2">
                                        <span style="color: #193948;">Highlight this section</span>
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>

                <button type="submit" 
                        class="mt-4 px-6 py-2 rounded-lg text-white font-semibold"
                        style="background-color: #193948;">
                    Save English Content
                </button>
            </div>
        </form>

        <!-- Arabic Section -->
        <form action="{{ route('superadmin.update-law') }}" method="POST" class="law-form" id="form-arabic" style="display: none; direction: rtl;">
            @csrf
            <input type="hidden" name="language" value="arabic">
            
            <div class="rounded-lg shadow p-6" style="background-color: #F3EBDD; margin-bottom: 2rem;">
                <h2 class="text-2xl font-semibold mb-4" style="color: #193948; text-align: right;">محتوى القانون بالعربية</h2>
                
                <div class="mb-4">
                    <label for="arabic_title" class="block text-sm font-medium mb-2" style="color: #193948; text-align: right;">العنوان</label>
                    <input type="text" 
                           id="arabic_title" 
                           name="title" 
                           value="{{ old('title', $arabicLaw->title ?? '') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           dir="rtl"
                           required>
                </div>

                <div class="mb-4">
                    <label for="arabic_notice" class="block text-sm font-medium mb-2" style="color: #193948; text-align: right;">إشعار مهم</label>
                    <textarea id="arabic_notice" 
                              name="notice" 
                              rows="3"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                              dir="rtl"
                              required>{{ old('notice', $arabicLaw->notice ?? '') }}</textarea>
                </div>

                <div id="arabic_sections" class="space-y-4">
                    @if(isset($arabicLaw) && $arabicLaw->sections)
                        @foreach($arabicLaw->sections as $index => $section)
                            <div class="section-item p-4 rounded" style="background-color: #ffffff; border: 2px solid #193948;">
                                <div class="mb-3">
                                    <label class="block text-sm font-medium mb-2" style="color: #193948; text-align: right;">عنوان القسم</label>
                                    <input type="text" 
                                           name="sections[{{ $index }}][title]" 
                                           value="{{ $section['title'] ?? '' }}"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg"
                                           dir="rtl"
                                           required>
                                </div>
                                <div class="mb-3">
                                    <label class="block text-sm font-medium mb-2" style="color: #193948; text-align: right;">المحتوى</label>
                                    <textarea name="sections[{{ $index }}][content]" 
                                              rows="3"
                                              class="w-full px-4 py-2 border border-gray-300 rounded-lg"
                                              dir="rtl"
                                              required>{{ $section['content'] ?? '' }}</textarea>
                                </div>
                                @if(isset($section['formula']))
                                    <div class="mb-3">
                                        <label class="block text-sm font-medium mb-2" style="color: #193948; text-align: right;">الصيغة</label>
                                        <input type="text" 
                                               name="sections[{{ $index }}][formula]" 
                                               value="{{ $section['formula'] ?? '' }}"
                                               class="w-full px-4 py-2 border border-gray-300 rounded-lg"
                                               dir="rtl">
                                    </div>
                                @endif
                                @if(isset($section['items']) && is_array($section['items']))
                                    <div class="mb-3">
                                        <label class="block text-sm font-medium mb-2" style="color: #193948; text-align: right;">العناصر (واحد في كل سطر)</label>
                                        <textarea name="sections[{{ $index }}][items_text]" 
                                                  rows="4"
                                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg"
                                                  dir="rtl">{{ implode("\n", $section['items']) }}</textarea>
                                    </div>
                                @endif
                                <div class="mb-3">
                                    <label class="flex items-center" style="direction: rtl;">
                                        <input type="checkbox" 
                                               name="sections[{{ $index }}][highlight]" 
                                               value="1"
                                               {{ isset($section['highlight']) && $section['highlight'] ? 'checked' : '' }}
                                               class="ml-2">
                                        <span style="color: #193948;">تمييز هذا القسم</span>
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>

                <button type="submit" 
                        class="mt-4 px-6 py-2 rounded-lg text-white font-semibold"
                        style="background-color: #193948;">
                    حفظ المحتوى العربي
                </button>
            </div>
        </form>

        <!-- French Section -->
        <form action="{{ route('superadmin.update-law') }}" method="POST" class="law-form" id="form-french" style="display: none;">
            @csrf
            <input type="hidden" name="language" value="french">
            
            <div class="rounded-lg shadow p-6" style="background-color: #F3EBDD;">
                <h2 class="text-2xl font-semibold mb-4" style="color: #193948;">Contenu de la Loi en Français</h2>
                
                <div class="mb-4">
                    <label for="french_title" class="block text-sm font-medium mb-2" style="color: #193948;">Titre</label>
                    <input type="text" 
                           id="french_title" 
                           name="title" 
                           value="{{ old('title', $frenchLaw->title ?? '') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           required>
                </div>

                <div class="mb-4">
                    <label for="french_notice" class="block text-sm font-medium mb-2" style="color: #193948;">Avis Important</label>
                    <textarea id="french_notice" 
                              name="notice" 
                              rows="3"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                              required>{{ old('notice', $frenchLaw->notice ?? '') }}</textarea>
                </div>

                <div id="french_sections" class="space-y-4">
                    @if(isset($frenchLaw) && $frenchLaw->sections)
                        @foreach($frenchLaw->sections as $index => $section)
                            <div class="section-item p-4 rounded" style="background-color: #ffffff; border: 2px solid #193948;">
                                <div class="mb-3">
                                    <label class="block text-sm font-medium mb-2" style="color: #193948;">Titre de la Section</label>
                                    <input type="text" 
                                           name="sections[{{ $index }}][title]" 
                                           value="{{ $section['title'] ?? '' }}"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg"
                                           required>
                                </div>
                                <div class="mb-3">
                                    <label class="block text-sm font-medium mb-2" style="color: #193948;">Contenu</label>
                                    <textarea name="sections[{{ $index }}][content]" 
                                              rows="3"
                                              class="w-full px-4 py-2 border border-gray-300 rounded-lg"
                                              required>{{ $section['content'] ?? '' }}</textarea>
                                </div>
                                @if(isset($section['formula']))
                                    <div class="mb-3">
                                        <label class="block text-sm font-medium mb-2" style="color: #193948;">Formule</label>
                                        <input type="text" 
                                               name="sections[{{ $index }}][formula]" 
                                               value="{{ $section['formula'] ?? '' }}"
                                               class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                                    </div>
                                @endif
                                @if(isset($section['items']) && is_array($section['items']))
                                    <div class="mb-3">
                                        <label class="block text-sm font-medium mb-2" style="color: #193948;">Éléments (un par ligne)</label>
                                        <textarea name="sections[{{ $index }}][items_text]" 
                                                  rows="4"
                                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg">{{ implode("\n", $section['items']) }}</textarea>
                                    </div>
                                @endif
                                <div class="mb-3">
                                    <label class="flex items-center">
                                        <input type="checkbox" 
                                               name="sections[{{ $index }}][highlight]" 
                                               value="1"
                                               {{ isset($section['highlight']) && $section['highlight'] ? 'checked' : '' }}
                                               class="mr-2">
                                        <span style="color: #193948;">Mettre en évidence cette section</span>
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>

                <button type="submit" 
                        class="mt-4 px-6 py-2 rounded-lg text-white font-semibold"
                        style="background-color: #193948;">
                    Enregistrer le Contenu Français
                </button>
            </div>
        </form>
    </div>

    @if(session('success'))
        <div class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg" id="success-message">
            {{ session('success') }}
        </div>
        <script>
            setTimeout(function() {
                document.getElementById('success-message').style.display = 'none';
            }, 3000);
        </script>
    @endif

    <script>
        function switchLanguage(language) {
            // Hide all forms
            document.getElementById('form-english').style.display = 'none';
            document.getElementById('form-arabic').style.display = 'none';
            document.getElementById('form-french').style.display = 'none';

            // Remove active class from all tabs
            document.querySelectorAll('.language-tab').forEach(tab => {
                tab.style.backgroundColor = 'rgba(25, 57, 72, 0.3)';
                tab.style.color = '#193948';
            });

            // Show selected form
            document.getElementById('form-' + language).style.display = 'block';

            // Add active class to selected tab
            const activeTab = document.getElementById('tab-' + language);
            activeTab.style.backgroundColor = '#193948';
            activeTab.style.color = '#F3EBDD';
        }

        // Handle items_text conversion to items array before form submission
        document.querySelectorAll('.law-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                // Convert items_text to items array for each section
                const sectionItems = form.querySelectorAll('[name*="[items_text]"]');
                sectionItems.forEach(textarea => {
                    const name = textarea.getAttribute('name');
                    const indexMatch = name.match(/\[(\d+)\]/);
                    if (indexMatch) {
                        const index = indexMatch[1];
                        const itemsText = textarea.value;
                        if (itemsText && itemsText.trim() !== '') {
                            const items = itemsText.split('\n')
                                .map(item => item.trim())
                                .filter(item => item !== '');
                            
                            // Add hidden input for items array
                            items.forEach((item, itemIndex) => {
                                const hiddenInput = document.createElement('input');
                                hiddenInput.type = 'hidden';
                                hiddenInput.name = `sections[${index}][items][${itemIndex}]`;
                                hiddenInput.value = item;
                                form.appendChild(hiddenInput);
                            });
                        }
                    }
                });
            });
        });
    </script>
</x-allthepages-layout>
