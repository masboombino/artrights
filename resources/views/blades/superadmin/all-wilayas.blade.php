<x-allthepages-layout pageTitle="All Wilayas">
    <div style="padding: 5px; margin: 5px;">
        @if(session('success'))
            <div class="alert-success">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        <div style="margin: 10px 5px; padding: 10px;">
            <h2 style="color: #F3EBDD; font-size: 1.8rem; font-weight: 700; margin-bottom: 1rem; text-align: center;">
                All Algerian Wilayas
            </h2>
            <p style="color: #D6BFBF; text-align: center; margin-bottom: 1rem;">
                Wilayas with active agencies are highlighted in a different color
            </p>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(120px, 1fr)); gap: 8px; padding: 10px;">
            @foreach($normalizedWilayas as $code => $wilaya)
                <a href="{{ route('superadmin.show-wilaya', $code) }}" 
                   style="text-decoration: none; display: block;">
                    <div class="service-card" 
                         style="
                            background-color: {{ $wilaya['has_agencies'] ? '#4FADC0' : '#F3EBDD' }};
                            border: 2px solid {{ $wilaya['has_agencies'] ? '#2C5F7D' : '#D6BFBF' }};
                            transition: transform 0.2s, box-shadow 0.2s;
                            cursor: pointer;
                         "
                         onmouseover="this.style.transform='scale(1.05)'; this.style.boxShadow='0 4px 8px rgba(0,0,0,0.2)';"
                         onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='none';">
                        <div style="padding: 0.75rem; text-align: center;">
                            <div style="
                                width: 35px;
                                height: 35px;
                                border-radius: 50%;
                                background-color: {{ $wilaya['has_agencies'] ? '#2C5F7D' : '#D6BFBF' }};
                                color: {{ $wilaya['has_agencies'] ? '#4FADC0' : '#193948' }};
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                font-size: 0.9rem;
                                font-weight: 800;
                                margin: 0 auto 0.5rem;
                            ">
                                {{ $code }}
                            </div>
                            <h3 style="
                                color: {{ $wilaya['has_agencies'] ? '#193948' : '#193948' }};
                                font-size: 0.75rem;
                                font-weight: 700;
                                margin-bottom: 0.3rem;
                                min-height: 2rem;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                line-height: 1.2;
                            ">
                                {{ $wilaya['name'] }}
                            </h3>
                            @if($wilaya['has_agencies'])
                                <div style="
                                    background-color: #2C5F7D;
                                    color: #4FADC0;
                                    padding: 0.25rem;
                                    border-radius: 3px;
                                    margin-top: 0.3rem;
                                    font-weight: 600;
                                    font-size: 0.7rem;
                                ">
                                    {{ $wilaya['agencies_count'] }} {{ $wilaya['agencies_count'] == 1 ? 'Agency' : 'Agencies' }}
                                </div>
                            @else
                                <div style="
                                    color: #193948;
                                    padding: 0.25rem;
                                    margin-top: 0.3rem;
                                    font-weight: 500;
                                    font-size: 0.65rem;
                                ">
                                    No agencies
                                </div>
                            @endif
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <div style="margin-top: 2rem; padding: 1rem; text-align: center;">
            <a href="{{ route('superadmin.dashboard') }}" 
               class="secondary-button" 
               style="display: inline-block; padding: 0.75rem 1.5rem;">
                Back to Dashboard
            </a>
        </div>
    </div>
</x-allthepages-layout>

