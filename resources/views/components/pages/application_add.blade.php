<x-layouts.table>
    @php
        $title = __('New Application');
    @endphp
    <x-slot:title>
        {{$title}}
    </x-slot:title>

    @php
        // Check if "edit" is present in the URL
        $pageSubTitle = __('New Permit Applications');
    @endphp

    <!-- Table Header Buttons Ends-->
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <!-- Table -->
    <div class="progress-table-wrap mb-4">

        <!-- Applicant Section -->
        <div class="mb-3" style="padding-top: 2em; ">
            <div class="text-center border-bottom: 3px solid black; ">
                <h3 class="mb-3" style="font-size:2em;">{{__('Applicant')}}</h3>
            </div>
        </div>


        <form action="{{route('application.store')}}" method="post" enctype="multipart/form-data">
            @csrf

            <!-- Permit Holder -->
            <div class="mb-4">
                <x-input-label for="permitholder" :value="__('Permit Holder')" class="input-class" :required=true />
                <a href="{{ route('permitholder.add') }}" style="margin-bottom:0.5em; width:auto; height:auto;"
                    class="text-center genric-btn danger success-border small">{{ __('Add New Holder') }}</a>
                <x-holder-input-select-dynamic class="block mt-1 w-full input-class w-100" :options="$options"
                    id="permitholder" :value="old('holder_id')" name="holder_id">
                </x-holder-input-select-dynamic>
                <x-input-error :messages="$errors->get('holder_id')" class="mt-2" />
            </div>

            <!-- Applicant Category -->
            <div class="mb-4">
                <x-input-label for="applicantcat" :value="__('Applicant Category')" class="input-class"
                    :required=true />
                <x-applicantcat-input-select-dynamic class="block mt-1 w-full input-class w-100"
                    :options="$applicantcatOptions" :id="applicantcat" id="applicantcat" name="applicant_category_id"
                    :secondId="'company_name'" :secondLabel="__('Company Name')" :thirdId="'company_address'"
                    :thirdLabel="__('Company Address')" :value="old('applicant_category_id')">
                </x-applicantcat-input-select-dynamic>
                <x-input-error :messages="$errors->get('applicant_category_id')" class="mt-2" />
            </div>


            <!-- Purpose -->
            <div class="mb-3">
                <x-input-label for="purpose" :value="__('Purpose ')" :required=true />
                <x-textarea-input id="purpose" class="block mt-1 w-full input-class" type="text" name="purpose"
                    placeholder="" :value="old('purpose')" autocomplete="purpose" />
                <x-input-error :messages="$errors->get('purpose')" class="mt-2" />
            </div>
            <!-- Applicant Section Ends-->

            <!-- Vehicle Section -->
            <div class="mb-3">
                <div style="position: relative; display: flex; justify-content: center;" class="mb-3">
                    <!-- Centered Border -->
                    <div style="position: absolute; bottom: 0; width: 30%; border-bottom: 1px solid black;"></div>
                    <!-- Content -->
                    <h3 class="mb-15 mt-15">{{__('Vehicles ')}}</h3>
                </div>
            </div>

            <!-- Vehicle Type -->
            <div class="mb-4">
                <x-input-label for="vehicletype" :value="__('Vehicle Type')" class="input-class" :required=true />
                <x-input-select class="block mt-1 w-full input-class w-100" :options="$vehicleTypeOptions"
                    id="vehicletype" :value="old('vehicletype')" name="vehicletype">
                </x-input-select>
                <x-input-error :messages="$errors->get('vehicletype')" class="mt-2" />
            </div>

            <!-- Vehicle Reg No -->
            <div class="mb-3">
                <x-input-label for="reg_no" :value="__('Vehicle Registration No. ')" :required=true />
                <x-text-input id="reg_no" class="block mt-1 w-full" type="text" name="reg_no" placeholder="" 
                    :value="old('reg_no')" autocomplete="reg_no" :autocaps=true />
                <x-input-error :messages="$errors->get('reg_no')" class="mt-2" />
            </div>

            <!-- Vehicle Model -->
            <div class="mb-3">
                <x-input-label for="vehicle_model" :value="__('Vehicle Model ')" :required=true />
                <x-text-input id="vehicle_model" class="block mt-1 w-full" type="text" name="vehicle_model"
                    placeholder="" :value="old('vehicle_model')" autocomplete="vehicle_model" />
                <x-input-error :messages="$errors->get('vehicle_model')" class="mt-2" />
            </div>
            <!-- Vehicle Section Ends-->

            <!-- Document Section -->
            <div style="position: relative; display: flex; justify-content: center;" class="mb-3">
                <!-- Centered Border -->
                <div style="position: absolute; bottom: 0; width: 30%; border-bottom: 1px solid black;"></div>
                <!-- Content -->
                <h3 class="mb-15 mt-15">{{__('Documents')}}</h3>
            </div>

            <!-- Surat Permohonan-->
            <div class="mb-3">
                <x-input-label for="surat_permohonan" :value="__('Surat Permohonan (Max: 1 File)')" :required=true />
                <x-multiple-image-input id="surat_permohonan" class="block mt-1 w-full"
                    style="border:1px solid grey; border-radius:3px;" name="surat_permohonan" placeholder=""
                    :value="old('surat_permohonan')" autocomplete="surat_permohonan" :displayWidget=false
                    :singleFile=true :disabled=false :data_name="'surat_permohonan'" :maxFiles=1 />
                <x-input-error :messages="$errors->get('surat_permohonan')" class="mt-2" />
            </div>

            <!-- Surat Indemnity Bond -->
            <div class="mb-3">
                <x-input-label for="surat_indemnity" :value="__('Surat Indemnity Bond (Max: 1 File)')" :required=true />
                <x-multiple-image-input id="surat_indemnity" class="block mt-1 w-full"
                    style="border:1px solid grey; border-radius:3px;" name="surat_indemnity" placeholder=""
                    :value="old('surat_indemnity')" :displayWidget=false :singleFile=true autocomplete="surat_indemnity"
                    :data_name="'surat_indemnity'" :maxFiles=1 />
                <x-input-error :messages="$errors->get('surat_indemnity')" class="mt-2" />
            </div>

            <!-- Surat Sokongan -->
            <div class="mb-3">
                <x-input-label for="surat_sokongan" :value="__('Surat Sokongan (Max: 1 File)')" :required=true />
                <x-multiple-image-input id="surat_sokongan" class="block mt-1 w-full"
                    style="border:1px solid grey; border-radius:3px;" name="surat_sokongan" placeholder=""
                    :value="old('surat_sokongan')" autocomplete="surat_sokongan" :singleFile=true :displayWidget=false
                    :data_name="'surat_sokongan'" :maxFiles=1 />
                <x-input-error :messages="$errors->get('surat_sokongan')" class="mt-2" />
            </div>

            <!-- Salinan Kad Pengenalan -->
            <div class="mb-3">
                <x-input-label for="salinan_kad_pengenalan" :value="__('Surat Sokongan (Max: 3 File)')"
                    :required=true />
                <x-multiple-image-input id="salinan_kad_pengenalan" class="block mt-1 w-full"
                    style="border:1px solid grey; border-radius:3px;" name="salinan_kad_pengenalan" placeholder=""
                    :value="old('salinan_kad_pengenalan')" autocomplete="salinan_kad_pengenalan" :singleFile=true
                    :displayWidget=false :data_name="'salinan_kad_pengenalan'" :maxFiles=3 />
                <x-input-error :messages="$errors->get('salinan_kad_pengenalan')" class="mt-2" />
            </div>
            
            <!-- Salinan Lesen Memandu -->
            <div class="mb-3">
                <x-input-label for="salinan_lesen_memandu" :value="__('Salinan Lesen Memandu (Max: 3 File)')"
                    :required="true" />
                <x-multiple-image-input id="salinan_lesen_memandu" class="block mt-1 w-full"
                    style="border:1px solid grey; border-radius:3px;" name="salinan_lesen_memandu[]"
                    :value="old('salinan_lesen_memandu')" autocomplete="salinan_lesen_memandu" :maxFiles="3"   :data_name="'salinan_lesen_memandu'"/>
                <x-input-error :messages="$errors->get('salinan_lesen_memandu')" class="mt-2" />
            </div>

            <!-- Salinan Geran Kenderaan -->
            <div class="mb-3">
                <x-input-label for="salinan_geran_kenderaan" :value="__('Saliana Geran Kenderaan (Max: 3 File)')"
                    :required="true" />
                <x-multiple-image-input id="salinan_geran_kenderaan" class="block mt-1 w-full"
                    style="border:1px solid grey; border-radius:3px;" name="salinan_geran_kenderaan[]"
                    :value="old('salinan_geran_kenderaan')" autocomplete="salinan_geran_kenderaan" :maxFiles="3" :data_name="'salinan_geran_kenderaan'"/>
                <x-input-error :messages="$errors->get('salinan_geran_kenderaan')" class="mt-2" />
            </div>

            <!-- Salinan Insurans Kenderaan -->
            <div class="mb-3">
                <x-input-label for="salinan_insurans_kenderaan" :value="__('Saliana Insurans Kenderaan (Max: 3 File)')"
                    :required="true" />
                <x-multiple-image-input id="salinan_insurans_kenderaan" class="block mt-1 w-full"
                    style="border:1px solid grey; border-radius:3px;" name="salinan_insurans_kenderaan[]"
                    :value="old('salinan_insurans_kenderaan')" autocomplete="salinan_insurans_kenderaan"
                    :maxFiles="3" :data_name="'salinan_insurans_kenderaan'"/>
                <x-input-error :messages="$errors->get('salinan_insurans_kenderaan')" class="mt-2" />
            </div>

            <!-- Salinan Road Tax -->
            <div class="mb-3">
                <x-input-label for="salinan_road_tax" :value="__('Saliana Road Tax (Max: 3 File)')" :required="true" />
                <x-multiple-image-input id="salinan_road_tax" class="block mt-1 w-full"
                    style="border:1px solid grey; border-radius:3px;" name="salinan_road_tax[]"
                    :value="old('salinan_road_tax')" autocomplete="salinan_road_tax" :maxFiles="3" :data_name="'salinan_road_tax'"/>
                <x-input-error :messages="$errors->get('salinan_road_tax')" class="mt-2" />
            </div>

            <!-- Gambar Kenderaan -->
            <div class="mb-3">
                <x-input-label for="gambar_kenderaan" :value="__('Gambar Kenderaan (Max: 3 File)')" :required="true" />
                <x-multiple-image-input id="gambar_kenderaan" class="block mt-1 w-full"
                    style="border:1px solid grey; border-radius:3px;" name="gambar_kenderaan[]"
                    :value="old('gambar_kenderaan')" autocomplete="gambar_kenderaan" :maxFiles="3" :data_name="'gambar_kenderaan'"/>
                <x-input-error :messages="$errors->get('gambar_kenderaan')" class="mt-2" />
            </div>

            <!-- Back Button -->
            <div class="mb-3 text-right">
                <a href="{{ route('application') }}" style="margin-bottom:0.5em; width:8em;"
                    class="text-center genric-btn danger success-border small">{{ __('Back') }}</a>
                <button type="submit" style="margin-bottom: 0.5em; width: 8em;"
                    class="text-center genric-btn info success-border small">{{ __('Submit') }}</button>
            </div>


        </form>
    </div>

    <!-- Table ends -->
    <script>
        $(document).ready(function () {
            var isEditPage = window.location.href.includes('edit');
            var editStatus = isEditPage ? true : false;
            var inputElements = $('.input-class');
            if (editStatus) {
                inputElements.attr('disabled', 'disabled');
            }
        });

        //To fix storage URL
        function openFile(filePath) {
            if (filePath) {
                var startUrl = "{{ config('filesystems.disks.admin.url') }}";
                if (!filePath.startsWith(startUrl)) {
                    filePath = startUrl + filePath;
                }
                window.open(filePath, '_blank');
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            const selectElement = document.getElementById('applicantcat');
            const divElement = document.getElementById('companynameContainer');

            selectElement.addEventListener('change', function () {
                if (selectElement.value === '1' || selectElement.value === 1) {
                    divElement.style.display = 'block';
                } else {
                    divElement.style.display = 'block';
                }
            });
        });
    </script>



    </x-layouts.app>