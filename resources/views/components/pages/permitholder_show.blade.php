<x-layouts.table>

    @php
        // Check if "edit" is present in the URL
        $isEditPage = strpos($_SERVER['REQUEST_URI'], 'edit') !== false;
        $pageSubTitle = __('Permit Holder Details');
        $resultsTitle = "";
        if ($isEditPage) {
            $resultsTitle = $pageSubTitle . " - " . __('Edit');
        } else {
            $resultsTitle = $pageSubTitle . " - " . __('View') ;
        }

        $title = $resultsTitle ;
    @endphp

    <x-slot:title>
        {{$title}}
    </x-slot:title>


    <!-- Table -->
    <div class="progress-table-wrap mb-4">

        <form action="{{route('permitholder.submit', $userPermit->id)}}" method="POST">
            @csrf

            <!-- Applicant Section -->
            <div style="position: relative; display: flex; justify-content: center;" class="mb-3">
                <!-- Centered Border -->
                <div style="position: absolute; bottom: 0; width: 30%; border-bottom: 1px solid black;"></div>
                <!-- Content -->
                <h3 class="mb-15 mt-15">{{__('Applicant ')}}</h3>
            </div>

            <!-- Name -->
            <div class="mb-3">
                <x-input-label for="name" :value="__('Name ')" />
                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :disabled=!$isEditPage
                    placeholder="" :value="$userPermit->name ?? old('name')" autocomplete="name" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <!-- Identificaiton No. -->
            <div class="mb-3">
                <x-input-label for="identification_no" :value="__('Identification No. ')" />
                <x-text-input id="identification_no" class="block mt-1 w-full" type="text" name="identification_no"
                    :disabled=!$isEditPage placeholder="" :value="$userPermit->identification_no ?? old('identification_no')" autocomplete="identification_no" />
                <x-input-error :messages="$errors->get('identification_no')" class="mt-2" />
            </div>

            <!-- Contact No -->
            <div class="mb-3">
                <x-input-label for="contact_no" :value="__('Contact No')" />
                <x-text-input id="contact_no" class="block mt-1 w-full" type="text" name="contact_no" placeholder=""
                    :value="$userPermit->contact_no ?? old('contact_no')" autocomplete="contact_no"
                    :disabled=!$isEditPage />
                <x-input-error :messages="$errors->get('contact_no')" class="mt-2" />
            </div>

            <!-- Address. -->
            <div class="mb-3">
                <x-input-label for="address" :value="__('Address')" />
                <x-textarea-input id="address" class="block mt-1 w-full" name="address" placeholder=""
                    :value="$userPermit->address ?? old('address')" autocomplete="address" :disabled=!$isEditPage />
                <x-input-error :messages="$errors->get('address')" class="mt-2" />
            </div>

            <!-- Back Button -->
            <div class="mb-3 text-right">
                <a href="{{ route('permitholder') }}" style="margin-bottom:0.5em; width:8em;"
                    class="text-center genric-btn danger success-border small">{{ __('Back') }}</a>

                @if(!($isEditPage))
                    <a href="{{ route('permitholder.edit', $userPermit->id) }}" style="margin-bottom:0.5em; width:8em;"
                        class="text-center genric-btn info success-border small">{{ __('Edit') }}</a>
                @else
                    <button type="submit" style="margin-bottom:0.5em; width:8em;"
                        class="text-center genric-btn info success-border small">
                        {{ __('Submit') }}
                    </button>
                @endif
            </div>


        </form>





    </div>

    <!-- Table ends -->
    <script>
        $(document).ready(function () {
            // Check if "edit" exists in the URL
            var isEditPage = window.location.href.includes('edit');

            // Log the result to the console for debugging
            console.log('Is Edit Page:', isEditPage);

            // If the URL contains "edit", set editStatus to true; otherwise, set it to false
            var editStatus = isEditPage ? true : false;

            // Get all input elements with the specified class
            var inputElements = $('.input-class');

            // If editStatus is true, add the 'disabled' attribute to all input elements with the specified class
            if (editStatus) {
                inputElements.attr('disabled', 'disabled');
            }
        });

        //To fix storage URL
        function openFile(filePath) {
            if (filePath) {
                var startUrl = "{{ config('filesystems.disks.admin.url') }}";
                // Check if the filePath already contains startUrl
                if (!filePath.startsWith(startUrl)) {
                    // If not, concatenate startUrl with filePath
                    filePath = startUrl + filePath;
                }
                window.open(filePath, '_blank');
            }
        }
    </script>
    </x-layouts.app>