<x-layouts.table>
    @php
        $title = __('Permit Holders');
    @endphp
    <x-slot:title>
        {{$title}}
    </x-slot:title>

    <!-- Table Header Buttons -->
    <!-- <div style="display: flex; justify-content: space-between;border-bottom: 3px solid black;" class="mb-3">
        <h2 class="mb-15 mt-15" style="font-weight:1000;"></h2>
    </div> -->
    <!-- Table Header Buttons Ends-->

    <!-- Table -->
    <div class="progress-table-wrap mb-4">

        <form action="{{route('permitholder.add')}}" method="POST">
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
                <x-input-label for="name" :value="__('Name ')" class="input-class" />
                <x-text-input id="name" class="block mt-1 w-full input-class" type="text" name="name"
                     placeholder="" :value="old('name')" required
                    autocomplete="name" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <!-- Identificaiton No. -->
            <div class="mb-3">
                <x-input-label for="identification_no" :value="__('Identification No. ')" class="input-class" />
                <x-text-input id="identification_no" class="block mt-1 w-full input-class" type="text"
                    name="identification_no"  placeholder=""
                    :value="old('identification_no')" required
                    autocomplete="identification_no" />
                <x-input-error :messages="$errors->get('identification_no')" class="mt-2" />
            </div>

            <!-- Contact No -->
            <div class="mb-3">
                <x-input-label for="contact_no" :value="__('Contact No')" />
                <x-text-input id="contact_no" class="block mt-1 w-full input-class" type="text" name="contact_no"
                    placeholder="" :value=" old('contact_no')" required
                    autocomplete="contact_no"  />
                <x-input-error :messages="$errors->get('contact_no')" class="mt-2" />
            </div>

            <!-- Address. -->
            <div class="mb-3">
                <x-input-label for="address" :value="__('Address')" />
                <x-textarea-input id="address" class="block mt-1 w-full" name="address" placeholder=""
                    :value="old('address')" required autocomplete="address"
                     />
                <x-input-error :messages="$errors->get('address')" class="mt-2" />
            </div>

            <!-- Back Button -->
            <div class="mb-3 text-right">
                <a href="{{ route('permitholder') }}" style="margin-bottom:0.5em; width:8em;"
                    class="text-center genric-btn danger success-border small">{{ __('Back') }}</a>

                    <button type="submit" style="margin-bottom:0.5em; width:8em;"
                        class="text-center genric-btn info success-border small">
                        {{ __('Submit') }}
                    </button>

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