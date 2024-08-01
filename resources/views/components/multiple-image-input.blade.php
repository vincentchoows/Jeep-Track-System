@props(['disabled' => false, 'defaultFilePath' => '', 'singleFile' => true, 'displayWidget' => true, 'displayMode' => false, 'data_name' => 'unknownFileName', 'maxFiles' => 1, 'editMode' => false])

@if (!is_null($defaultFilePath))
    <!-- Pre-processing -->
    @php
    if (is_array($defaultFilePath)) {
            $defaultFilePath = array_filter($defaultFilePath);
            $defaultFilePath = array_map(function ($path) {
                return str_replace('\\', '/', $path);
            }, $defaultFilePath);
            
            if (empty($defaultFilePath)) {
                $error = __('No files available');
                $disabled = true;
            } else {
                $error = null;
            }
            $startUrl = config('filesystems.disks.admin.url');
        }
    @endphp

    

    <!-- Display file upload input fields with preview -->
    <div>
        @if(!$displayMode)
            @if($disabled !== true)
            <!-- File Input Group -->
            <div class="file-input-container mb-3" data-name="{{$data_name}}" data-max-files="{{ $maxFiles }}">
                <div class="file-input">
                    <input type="file" name="{{$data_name}}[]" onchange="previewFiles(this)"
                        style="border:1px solid grey; border-radius:3px; margin-top:3px;" {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'block mt-1 w-full']) !!} />
                </div>
                <x-primary-button style="display:none;" type="button"
                    class="add-file-button">{{ __('Add Another File') }}</x-primary-button>
            </div>
            @endif
        @endif

        <!-- Display file paths -->
        @if (is_array($defaultFilePath))
            @foreach ($defaultFilePath as $filePath)
                <div class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm overflow-hidden">
                    <div class="flex items-center justify-left bg-gray-100 px-4 py-2">
                        <i class="fas fa-file mr-2 text-gray-600"></i>
                        <a href="#" onclick="openFile('{{ $filePath }}'); return false;" class="flex items-center"
                            style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap; text-align: left;">
                            <span
                                style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap; display: inline-block; max-width: 100%; text-align: left; margin-bottom: 0px !important;">
                                {{ basename($filePath) }}
                            </span>
                        </a>
                    </div>
                </div>
            @endforeach
        @else
            <!--null-->
        @endif
    </div>
@endif


