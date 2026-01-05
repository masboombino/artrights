<x-allthepages-layout pageTitle="Add Devices to PV #{{ $pv->id }}">
    <div class="space-y-6">
        <div class="rounded-lg shadow p-6" style="background-color: #F3EBDD;">
            <h2 class="text-2xl font-semibold mb-4" style="color: #193948;">Add Devices to PV</h2>
            <p class="text-sm mb-4" style="color: #193948;">Add all devices found at the location. You can add multiple devices at once.</p>
            
            <form method="POST" action="{{ route('agent.pvs.devices.store', $pv) }}" class="space-y-4" id="devicesForm">
                @csrf
                
                <div id="devices-container" class="space-y-4">
                    <!-- First device row -->
                    <div class="device-row p-4 rounded border-2" style="background-color: #ffffff; border-color: #193948;">
                        <div class="flex justify-between items-center mb-3">
                            <h3 class="font-semibold" style="color: #193948;">Device #1</h3>
                            <button type="button" onclick="removeDeviceRow(this)" class="text-sm rounded px-2 py-1" style="background-color: #E76268; color: white; display: none;">Remove</button>
                        </div>
                        
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-semibold mb-1" style="color: #193948;">Device Preset</label>
                                <select name="devices[0][device_type_id]" class="device-type-select w-full rounded border p-2" onchange="fillFromPreset(this, 0)">
                                    <option value="">Select from presets</option>
                                    @foreach($deviceTypes as $type)
                                        <option value="{{ $type->id }}" data-name="{{ $type->name }}" data-type="{{ $type->type }}" data-coefficient="{{ $type->coefficient }}">
                                            {{ $type->name }} ({{ $type->type }} · {{ $type->coefficient }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-sm font-semibold mb-1" style="color: #193948;">Name *</label>
                                    <input type="text" name="devices[0][name]" class="w-full rounded border p-2" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold mb-1" style="color: #193948;">Type</label>
                                    <input type="text" name="devices[0][type]" class="w-full rounded border p-2">
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-sm font-semibold mb-1" style="color: #193948;">Coefficient *</label>
                                    <input type="number" step="0.1" min="0.1" name="devices[0][coefficient]" value="1" class="w-full rounded border p-2" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold mb-1" style="color: #193948;">Quantity *</label>
                                    <input type="number" name="devices[0][quantity]" min="1" value="1" class="w-full rounded border p-2" required>
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold mb-1" style="color: #193948;">Notes</label>
                                <textarea name="devices[0][notes]" rows="2" class="w-full rounded border p-2"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-between items-center">
                    <button type="button" onclick="addDeviceRow()" class="rounded px-4 py-2 font-semibold" style="background-color: #D6BFBF; color: #193948;">
                        + Add Another Device
                    </button>
                    <div class="flex gap-3">
                        <a href="{{ route('agent.pvs.show', $pv) }}" class="rounded border px-4 py-2 font-semibold" style="color: #193948; border-color: #193948;">Cancel</a>
                        <button type="submit" class="rounded px-4 py-2 font-semibold" style="background-color: #193948; color: #4FADC0;">Save All Devices</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        let deviceRowCount = 1;

        function addDeviceRow() {
            const container = document.getElementById('devices-container');
            const newRow = document.createElement('div');
            newRow.className = 'device-row p-4 rounded border-2';
            newRow.style.cssText = 'background-color: #ffffff; border-color: #193948;';
            
            deviceRowCount++;
            const index = deviceRowCount - 1;
            
            newRow.innerHTML = `
                <div class="flex justify-between items-center mb-3">
                    <h3 class="font-semibold" style="color: #193948;">Device #${deviceRowCount}</h3>
                    <button type="button" onclick="removeDeviceRow(this)" class="text-sm rounded px-2 py-1" style="background-color: #E76268; color: white;">Remove</button>
                </div>
                
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-semibold mb-1" style="color: #193948;">Device Preset</label>
                        <select name="devices[${index}][device_type_id]" class="device-type-select w-full rounded border p-2" onchange="fillFromPreset(this, ${index})">
                            <option value="">Select from presets</option>
                            @foreach($deviceTypes as $type)
                                <option value="{{ $type->id }}" data-name="{{ $type->name }}" data-type="{{ $type->type }}" data-coefficient="{{ $type->coefficient }}">
                                    {{ $type->name }} ({{ $type->type }} · {{ $type->coefficient }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-semibold mb-1" style="color: #193948;">Name *</label>
                            <input type="text" name="devices[${index}][name]" class="w-full rounded border p-2" required>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-1" style="color: #193948;">Type</label>
                            <input type="text" name="devices[${index}][type]" class="w-full rounded border p-2">
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-semibold mb-1" style="color: #193948;">Coefficient *</label>
                            <input type="number" step="0.1" min="0.1" name="devices[${index}][coefficient]" value="1" class="w-full rounded border p-2" required>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-1" style="color: #193948;">Quantity *</label>
                            <input type="number" name="devices[${index}][quantity]" min="1" value="1" class="w-full rounded border p-2" required>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold mb-1" style="color: #193948;">Notes</label>
                        <textarea name="devices[${index}][notes]" rows="2" class="w-full rounded border p-2"></textarea>
                    </div>
                </div>
            `;
            
            container.appendChild(newRow);
            updateRemoveButtons();
        }

        function removeDeviceRow(button) {
            const row = button.closest('.device-row');
            row.remove();
            updateDeviceNumbers();
            updateRemoveButtons();
        }

        function updateDeviceNumbers() {
            const rows = document.querySelectorAll('.device-row');
            rows.forEach((row, index) => {
                const title = row.querySelector('h3');
                if (title) {
                    title.textContent = `Device #${index + 1}`;
                }
            });
        }

        function updateRemoveButtons() {
            const rows = document.querySelectorAll('.device-row');
            rows.forEach((row, index) => {
                const removeBtn = row.querySelector('button[onclick*="removeDeviceRow"]');
                if (removeBtn) {
                    removeBtn.style.display = rows.length > 1 ? 'block' : 'none';
                }
            });
        }

        function fillFromPreset(select, index) {
            const selectedOption = select.options[select.selectedIndex];
            if (selectedOption.value) {
                const row = select.closest('.device-row');
                const nameInput = row.querySelector(`input[name="devices[${index}][name]"]`);
                const typeInput = row.querySelector(`input[name="devices[${index}][type]"]`);
                const coefficientInput = row.querySelector(`input[name="devices[${index}][coefficient]"]`);
                
                if (nameInput) nameInput.value = selectedOption.getAttribute('data-name') || '';
                if (typeInput) typeInput.value = selectedOption.getAttribute('data-type') || '';
                if (coefficientInput) coefficientInput.value = selectedOption.getAttribute('data-coefficient') || '1';
            }
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            updateRemoveButtons();
        });
    </script>
</x-allthepages-layout>
