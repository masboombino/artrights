<x-allthepages-layout pageTitle="Mission Details">
    <div style="padding: 5px; margin: 5px;">
        @if(session('success'))
            <div class="alert-success">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        <!-- Mission Header Section -->
        <div style="background-color: #F3EBDD; border-radius: 1rem; margin: 5px; padding: 2rem;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 1.5rem; margin-bottom: 1.5rem;">
                <div style="flex: 1; min-width: 250px;">
                    <h1 style="color: #193948; font-size: 2rem; font-weight: 700; margin: 0 0 1rem 0;">
                        {{ $mission->title }}
                    </h1>
                    <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <span style="color: #193948; font-weight: 600; min-width: 120px;">Status:</span>
                            <span class="status-badge" style="font-size: 0.9rem; padding: 0.5rem 1rem;">
                                {{ $mission->status }}
                            </span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap;">
                            <span style="color: #193948; font-weight: 600; min-width: 120px;">Scheduled:</span>
                            <span style="color: #193948;">
                                {{ $mission->scheduled_at ? $mission->scheduled_at->format('d/m/Y H:i') : 'Not scheduled' }}
                            </span>
                        </div>
                        @if($mission->location_text)
                            <div style="display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap;">
                                <span style="color: #193948; font-weight: 600; min-width: 120px;">Location:</span>
                                <span style="color: #193948;">{{ $mission->location_text }}</span>
                            </div>
                        @endif
                        @if($mission->map_link)
                            <div style="display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap;">
                                <span style="color: #193948; font-weight: 600; min-width: 120px;">Map:</span>
                                <a href="{{ $mission->map_link }}" target="_blank" class="secondary-button" style="padding: 0.5rem 1rem; font-size: 0.9rem;">
                                    View on Map
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
                <div style="min-width: 250px;">
                    <form method="POST" action="{{ route('agent.missions.update-status', $mission->id) }}" style="background-color: #193948; padding: 1.5rem; border-radius: 0.75rem;">
                        @csrf
                        <h3 style="color: #4FADC0; font-size: 1.1rem; font-weight: 700; margin: 0 0 1rem 0; text-align: center;">
                            Update Status
                        </h3>
                        <select name="status" style="width: 100%; padding: 0.75rem; border-radius: 0.5rem; border: 2px solid #4FADC0; background-color: #F3EBDD; color: #193948; font-weight: 600; font-size: 0.9rem; margin-bottom: 1rem; cursor: pointer;">
                            @foreach(['ASSIGNED' => 'Assigned', 'IN_PROGRESS' => 'In Progress', 'DONE' => 'Done', 'CANCELLED' => 'Cancelled'] as $statusValue => $statusLabel)
                                <option value="{{ $statusValue }}" @selected($mission->status === $statusValue)>
                                    {{ $statusLabel }}
                                </option>
                            @endforeach
                        </select>
                        <button type="submit" class="secondary-button" style="width: 100%; padding: 0.75rem; font-size: 1rem; margin: 0;">
                            Update Status
                        </button>
                    </form>
                    <div style="margin-top: 1rem; text-align: center;">
                        <a href="{{ route('agent.missions.print', $mission->id) }}" target="_blank" class="primary-button" style="padding: 0.75rem 2rem; font-size: 1rem; background-color: #4FADC0; display: inline-block; width: 100%; text-decoration: none;">
                            🖨️ Print Mission
                        </a>
                    </div>
                </div>
            </div>

            @if($mission->description)
                <div style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 2px solid rgba(25, 57, 72, 0.2);">
                    <h3 style="color: #193948; font-size: 1.25rem; font-weight: 700; margin: 0 0 0.75rem 0;">
                        Description
                    </h3>
                    <p style="color: #193948; font-size: 1rem; line-height: 1.6; margin: 0; white-space: pre-wrap;">
                        {{ $mission->description }}
                    </p>
                </div>
            @endif
        </div>

        <!-- PV Section -->
        <div style="background-color: #F3EBDD; border-radius: 1rem; margin: 5px; padding: 2rem;">
            <h2 style="color: #193948; font-size: 1.5rem; font-weight: 700; margin: 0 0 1.5rem 0;">
                Associated PV (Report)
            </h2>
            @if($mission->pv)
                <div style="background-color: #ffffff; border: 2px solid #193948; border-radius: 0.75rem; padding: 1.5rem;">
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1.5rem;">
                        <div>
                            <p style="color: #193948; font-weight: 600; font-size: 0.9rem; margin: 0 0 0.5rem 0; opacity: 0.8;">
                                PV ID
                            </p>
                            <p style="color: #193948; font-size: 1.1rem; font-weight: 700; margin: 0;">
                                #{{ $mission->pv->id }}
                            </p>
                        </div>
                        <div>
                            <p style="color: #193948; font-weight: 600; font-size: 0.9rem; margin: 0 0 0.5rem 0; opacity: 0.8;">
                                Shop Name
                            </p>
                            <p style="color: #193948; font-size: 1.1rem; font-weight: 700; margin: 0;">
                                {{ $mission->pv->shop_name }}
                            </p>
                        </div>
                        <div>
                            <p style="color: #193948; font-weight: 600; font-size: 0.9rem; margin: 0 0 0.5rem 0; opacity: 0.8;">
                                Status
                            </p>
                            <span class="status-badge" style="font-size: 0.9rem; padding: 0.5rem 1rem;">
                                {{ $mission->pv->status }}
                            </span>
                        </div>
                        @if($mission->pv->date_of_inspection)
                            <div>
                                <p style="color: #193948; font-weight: 600; font-size: 0.9rem; margin: 0 0 0.5rem 0; opacity: 0.8;">
                                    Inspection Date
                                </p>
                                <p style="color: #193948; font-size: 1.1rem; font-weight: 700; margin: 0;">
                                    {{ $mission->pv->date_of_inspection->format('d/m/Y H:i') }}
                                </p>
                            </div>
                        @endif
                    </div>
                    <div style="text-align: center; padding-top: 1rem; border-top: 2px solid rgba(25, 57, 72, 0.1); display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
                        <a href="{{ route('agent.pvs.show', $mission->pv->id) }}" class="primary-button" style="padding: 0.75rem 2rem; font-size: 1rem;">
                            View Full PV Details
                        </a>
                    </div>
                </div>
            @else
                <div style="background-color: #ffffff; border: 2px dashed #193948; border-radius: 0.75rem; padding: 3rem 1.5rem; text-align: center;">
                    <p style="color: #193948; font-size: 1.2rem; font-weight: 600; margin: 0 0 1rem 0;">
                        No PV created yet for this mission
                    </p>
                    <p style="color: #193948; font-size: 1rem; margin: 0 0 1.5rem 0; opacity: 0.8;">
                        Create a new inspection report (PV) for this mission
                    </p>
                    <a href="{{ route('agent.pvs.create', ['mission_id' => $mission->id]) }}" class="primary-button" style="padding: 0.75rem 2rem; font-size: 1rem;">
                        Create New PV
                    </a>
                </div>
            @endif
        </div>

        <!-- Navigation -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 1.5rem; flex-wrap: wrap; gap: 10px; padding: 5px;">
            <a href="{{ route('agent.missions.index') }}" class="secondary-button" style="padding: 0.75rem 1.5rem;">
                ← Back to Missions
            </a>
        </div>
    </div>
</x-allthepages-layout>
