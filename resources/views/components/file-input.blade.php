@props(['disabled' => false, 'defaultFilePath' => '', 'displayWidget' => false, 'value' => null])
@php

    if (!(empty($defaultFilePath) || $defaultFilePath == '[]')) {
        $fileName = basename($defaultFilePath);
        $noFileIcon = true;
    } else {
        $fileName = __('No file available');
        $defaultFilePath = '#';
        
    }
@endphp

<div>
    <!-- Hidden file input field -->
    <input type="file" {{ $disabled ? 'disabled' : '' }} />

    <!-- Display a single file widget -->
    @if($displayWidget)
        <div class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm overflow-hidden">
            <div class="flex items-center justify-between bg-gray-100 px-4 py-2">
                <!-- Clickable file path to open in a new tab -->
                <button onclick="openFile('{{ $defaultFilePath }}')" {{ $disabled ? 'disabled' : '' }}
                    class="flex items-center {{ $disabled ? 'cursor-not-allowed' : '' }}">
                    @if(!empty($noFileIcon))
                        <i class="fas fa-file mr-2 text-gray-600"></i>
                    @endif
                    <span>{{ $fileName }}</span>
                </button>
            </div>
        </div>
    @endif
</div>