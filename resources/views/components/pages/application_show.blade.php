<x-layouts.table>
    @php
    // Check if "edit" is present in the URL
    $isEditPage = strpos($_SERVER['REQUEST_URI'], 'edit') !== false;
    $pageSubTitle = __('Permit Details');
    $resultsTitle = "";

    if ($isEditPage) {
    $resultsTitle = $pageSubTitle . " - " . __('Edit');
    } else {
    $resultsTitle = $pageSubTitle . " - " . __('View');
    }

    @endphp

    <x-slot:title>
        {{$resultsTitle}}
    </x-slot:title>

    <!-- Display Status -->
    @if(isset($renewalPage))
    <div class="mb-3 mt-3 pb-3">
        <x-input-label for="status" :value="__('Renewal Status')" class="mt-3" />
        <div id="status"
            class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
            type="text" name="status" :disabled="true" placeholder="" style="height:2.5em;" autocomplete="status">
            @if($userPermit->renewal_status == 0)
            <i class="fas fa-exclamation-circle" style="color: orange; margin-right: 5px;"></i>
            {{__('Pending Renewal')}}
            @elseif($userPermit->renewal_status == 1)
            <i class="fas fa-spinner" style="color: blue; margin-right: 5px;"></i> {{__('Approving')}}
            @else
            <i class="fas fa-ban" style="color: grey; margin-right: 5px;"></i> {{__('Error')}}
            @endif
        </div>
    </div>
    @else
    <div class="mb-3 mt-3 pb-3">
        <x-input-label for="status" :value="__('Permit Status')" class="mt-3" />
        <div id="status"
            class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
            type="text" name="status" :disabled="true" placeholder="" style="height:2.5em;" autocomplete="status">

            @if($userPermit->status == 0)
            <i class="fas fa-spinner" style="color: orange; margin-right: 5px;"></i> {{__('Reviewing')}}
            @elseif($userPermit->status == 1)
            <i class="fas fa-times-circle" style="color: red; margin-right: 5px;"></i> {{__('Rejected')}}
            @elseif($userPermit->status == 2)
            <i class="fas fa-spinner" style="color: orange; margin-right: 5px;"></i> {{__('Pending Payment')}}
            @elseif($userPermit->status == 3)
            <i class="fas fa-spinner" style="color: green; margin-right: 5px;"></i> {{__('Processing Payment')}}
            @elseif($userPermit->status == 4)
            <i class="fas fa-check-circle" style="color: green; margin-right: 5px;"></i> {{__('Activated')}}
            @elseif($userPermit->status == 5)
            <i class="fas fa-ban" style="color: yellow; margin-right: 5px;"></i> {{__('Disabled')}}
            @elseif($userPermit->status == 6)
            <i class="fas fa-times-circle" style="color: red; margin-right: 5px;"></i> {{__('Terminated')}}
            @else
            <i class="fas fa-ban" style="color: grey; margin-right: 5px;"></i> {{__('Error')}}
            @endif
        </div>
    </div>
    @endif
    <!-- Table -->
    <div class="progress-table-wrap mb-4">
        <form action="{{route('application.edit', $userPermit->id)}}" method="post" enctype="multipart/form-data">
            @csrf
            <!-- Applicant Section -->
            <div style="position: relative; display: flex; justify-content: center;" class="mb-3">
                <!-- Centered Border -->
                <div style="position: absolute; bottom: 0; width: 30%; border-bottom: 1px solid black;"></div>
                <!-- Content -->
                <h3 class="mb-15 mt-15">{{__('Applicant')}}</h3>
            </div>

            <!-- Serial number -->
            <div class="mb-3">
                <x-input-label for="name" :value="__('Serial No.')" class="" />
                <x-text-input id="name" class="block mt-1 w-full " type="text" name="name" :disabled=true placeholder=""
                    :value="$userPermit->serial_no ?? ''" autocomplete="name" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <!-- Name -->
            <div class="mb-3">
                <x-input-label for="name" :value="__('Name ')" class="" />
                <x-text-input id="name" class="block mt-1 w-full " type="text" name="name" :disabled=true placeholder=""
                    name="holder[identification_no]" :value="$userPermit->holder->name ?? ''" autocomplete="name" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <!-- Identification No. -->
            <div class="mb-3">
                <x-input-label for="identification_no" :value="__('Identification No.')" />
                <x-text-input id="identification_no" class="block mt-1 w-full" type="text"
                    name="holder[identification_no]" placeholder="xxxxxx" :disabled=true
                    :value="$userPermit->holder->identification_no ?? ''" autocomplete="identification_no" />
                <x-input-error :messages="$errors->get('identification_no')" class="mt-2" />
            </div>

            <!-- Contact No. -->
            <div class="mb-3">
                <x-input-label for="contact_no" :value="__('Contact No.')" />
                <x-text-input id="contact_no" class="block mt-1 w-full" type="text" name="holder[contact_no]"
                    :disabled=true :value="$userPermit->holder->contact_no ?? ''" autocomplete="contact_no" />
                <x-input-error :messages="$errors->get('contact_no')" class="mt-2" />
            </div>

            <!-- Address. -->
            <div class="mb-3">
                <x-input-label for="address" :value="__('Address')" />
                <x-textarea-input id="address" class="block mt-1 w-full" name="holder[address]"
                    :value="$userPermit->holder->address ?? ''" :oldValue="old('address')" autocomplete="address"
                    :disabled=true />
                <x-input-error :messages="$errors->get('address')" class="mt-2" />
            </div>

            <!-- Applicant Category (Cannot change in Edit) -->
            <div class="mb-4">
                <x-input-label for="applicant_category_id" :value="__('Applicant Category ')" />
                <x-applicantcat-input-select-dynamic class="block mt-1 w-full w-100" :id="'applicant_category_id'"
                    name="applicant_category_id" :options=$applicantcatOptions
                    :value=" $userPermit->applicant_category_id ?? old('applicant_category_id')" :disabled=true
                    :value="$userPermit->applicant_category_id ?? ''" :displayMode=true>
                </x-applicantcat-input-select-dynamic>
                <x-input-error :messages="$errors->get('applicant_category_id')" class="mt-2" />
            </div>

            <!-- Purpose -->
            <div class="mb-3">
                <x-input-label for="purpose" :value="__('Purpose ')" />
                <x-textarea-input id="purpose" class="block mt-1 w-full" type="text" name="purpose"
                    :value="old('purpose')" autocomplete="purpose" :disabled=true :value="$userPermit->purpose ?? ''" />
                <x-input-error :messages="$errors->get('purpose')" class="mt-2" />
            </div>


            @if($userPermit->applicant_category_id !== 1)
            <!-- OPTIONAL: Company Name -->
            <div class="mb-3">
                <x-input-label for="company_name" :value="__('Company Name')" />
                <x-text-input id="company_name" class="block mt-1 w-full " type="text" name="company_name"
                    :disabled=true placeholder="" :value="$userPermit->company_name ?? ''"
                    autocomplete="company_name" />
                <x-input-error :messages="$errors->get('company_name')" class="mt-2" />
            </div>

            <!-- OPTIONAL: Company Address -->
            <div class="mb-3">
                <x-input-label for="company_address" :value="__('Company Address')" />
                <x-textarea-input id="company_address" class="block mt-1 w-full " type="text" name="company_address"
                    :disabled=true placeholder="" :value="$userPermit->company_address ?? ''"
                    autocomplete="company_address" />
                <x-input-error :messages="$errors->get('company_address')" class="mt-2" />
            </div>
            @endif
            <!-- Applicant Section Ends-->

            <!-- Vehicle Section Ends-->
            <div style="position: relative; display: flex; justify-content: center;" class="mb-3">
                <!-- Centered Border -->
                <div style="position: absolute; bottom: 0; width: 30%; border-bottom: 1px solid black;"></div>
                <!-- Content -->
                <h3 class="mb-15 mt-15">{{__('Vehicle ')}}</h3>
            </div>

            <!-- Vehicle Type -->
            <div class="mb-4">
                <x-input-label for="vehicletype" :value="__('Vehicle Type')" class="input-class" />
                <x-input-select class="block mt-1 w-full input-class w-100" :options="$vehicleTypeOptions"
                    id="vehicletype" :disabled=true :value="$userPermit->vehicle->vehicletype->id ?? old('vehicletype')"
                    name="vehicle[type]">
                </x-input-select>
                <x-input-error :messages="$errors->get('vehicletype')" class="mt-2" />
            </div>

            <!-- Vehicle Reg No -->
            <div class="mb-3">
                <x-input-label for="reg_no" :value="__('Vehicle Registration No. ')" />
                <x-text-input id="reg_no" class="block mt-1 w-full" type="text" name="vehicle[reg_no]" :disabled=true
                    :value="$userPermit->vehicle->reg_no" autocomplete="reg_no" />
                <x-input-error :messages="$errors->get('reg_no')" class="mt-2" />
            </div>

            <!-- Vehicle Model -->
            <div class="mb-3">
                <x-input-label for="model" :value="__('Vehicle Model ')" />
                <x-text-input id="model" class="block mt-1 w-full capitalizedInput" type="text" name="vehicle[model]"
                    :disabled=true :value="old('model')" autocomplete="model" :value="$userPermit->vehicle->model" />
                <x-input-error :messages="$errors->get('model')" class="mt-2" />
            </div>


            <!-- Vehicle Section Ends-->


            <!-- Document Section -->
            <div style="position: relative; display: flex; justify-content: center;" class="mb-3">
                <!-- Centered Border -->
                <div style="position: absolute; bottom: 0; width: 30%; border-bottom: 1px solid black;"></div>
                <!-- Content -->
                <h3 class="mb-15 mt-15">{{__('Documents')}}</h3>
            </div>


            <!-- Surat Permohonan (single file) -->
            <div class="mb-3">
                <x-input-label for="surat_permohonan" :value="__('Surat Permohonan ')" />
                <x-multiple-image-input id="surat_permohonan" class="block mt-1 w-full" type="text"
                    name="surat_permohonan" placeholder="" :value="old('surat_permohonan')"
                    :defaultFilePath="$fileArray['surat_permohonan'] ?? ''" :displayWidget=true
                    :disabled="$userPermit->surat_permohonan_status == 2" :displayMode=!$isEditPage
                    autocomplete="surat_permohonan" :data_name="'surat_permohonan'" :maxFiles=1 />
                <x-input-error :messages="$errors->get('surat_permohonan')" class="mt-2" />
                @if($userPermit->surat_permohonan_status == 1)
                <x-input-label for="surat_permohonan" :value="__('[Please Resubmit The Documents]')"
                    style="color:red;margin-top:1em;" />
                <x-textarea-input id="" class="block mt-1 w-full" type="text" name="" placeholder="" style="color:red;"
                    :value="'Comments: ' . $userPermit->surat_permohonan_comment" disabled />
                @endif
            </div>

            <!-- Surat Indemnity Bond (single file) -->
            <div class="mb-3">
                <x-input-label for="surat_indemnity" :value="__('Surat Indemnity ')" />
                <x-multiple-image-input id="surat_indemnity" class="block mt-1 w-full" type="text"
                    name="surat_indemnity" placeholder="" :value="old('surat_indemnity')"
                    :defaultFilePath="$fileArray['surat_indemnity'] ?? ''" :displayWidget=true
                    :disabled="$userPermit->surat_indemnity_status == 2" :displayMode=!$isEditPage
                    autocomplete="surat_indemnity" :data_name="'surat_indemnity'" :maxFiles=1 />
                <x-input-error :messages="$errors->get('surat_indemnity')" class="mt-2" />
                @if($userPermit->surat_indemnity_status == 1)
                <x-input-label for="surat_indemnity" :value="__('[Please Resubmit The Documents]')"
                    style="color:red;margin-top:1em;" />
                <x-textarea-input id="" class="block mt-1 w-full" type="text" name="" placeholder="" style="color:red;"
                    :value="'Comments: ' . $userPermit->surat_indemnity_comment" disabled />
                @endif
            </div>

            <!-- Surat Sokongan (single file) -->
            <div class="mb-3">
                <x-input-label for="surat_sokongan" :value="__('Surat Sokongan ')" />
                <x-multiple-image-input id="surat_sokongan" class="block mt-1 w-full" type="text" name="surat_sokongan"
                    placeholder="" :value="old('surat_sokongan')" :defaultFilePath="$fileArray['surat_sokongan'] ?? ''"
                    :displayWidget=true :disabled="$userPermit->surat_sokongan_status == 2" :displayMode=!$isEditPage
                    autocomplete="surat_sokongan" :data_name="'surat_sokongan'" :maxFiles=1 />
                <x-input-error :messages="$errors->get('surat_sokongan')" class="mt-2" />
                @if($userPermit->surat_sokongan_status == 1)
                <x-input-label for="surat_sokongan" :value="__('[Please Resubmit The Documents]')"
                    style="color:red;margin-top:1em;" />
                <x-textarea-input id="" class="block mt-1 w-full" type="text" name="" placeholder="" style="color:red;"
                    :value="'Comments: ' . $userPermit->surat_sokongan_comment" disabled />
                @endif
            </div>

            <!-- Salinan Kad Pengenalan -->
            <div class="mb-3">
                <x-input-label for="salinan_kad_pengenalan" :value="__('Salinan Kad Pengenalan')" />
                <x-multiple-image-input id="salinan_kad_pengenalan" class="block mt-1 w-full" type="text"
                    name="salinan_kad_pengenalan" placeholder="" :value="old('salinan_kad_pengenalan')"
                    :defaultFilePath="$fileArray['salinan_kad_pengenalan'] ?? ''" :displayWidget=true
                    :disabled="$userPermit->salinan_kad_pengenalan_status == 2" :displayMode=!$isEditPage
                    autocomplete="salinan_kad_pengenalan" :data_name="'salinan_kad_pengenalan'" :maxFiles=3 />
                <x-input-error :messages="$errors->get('salinan_kad_pengenalan')" class="mt-2" />
                @if($userPermit->salinan_kad_pengenalan_status == 1)
                <x-input-label for="salinan_kad_pengenalan" :value="__('[Please Resubmit The Documents]')"
                    style="color:red;margin-top:1em;" />
                <x-textarea-input id="" class="block mt-1 w-full" type="text" name="" placeholder="" style="color:red;"
                    :value="'Comments: ' . $userPermit->salinan_kad_pengenalan_comment" disabled />
                @endif
            </div>

            <!-- Salinan Lesen Memandu -->
            <div class="mb-3">
                <x-input-label for="salinan_lesen_memandu" :value="__('Salinan Lesen Memandu')" />
                <x-multiple-image-input id="salinan_lesen_memandu" class="block mt-1 w-full" type="text"
                    name="salinan_lesen_memandu" placeholder="" :value="old('salinan_lesen_memandu')"
                    :defaultFilePath="$fileArray['salinan_lesen_memandu'] ?? ''" :displayWidget=true
                    :displayMode=!$isEditPage :disabled="$userPermit->salinan_lesen_memandu_status == 2"
                    autocomplete="salinan_lesen_memandu" :editMode=true :data_name="'salinan_lesen_memandu'"
                    :maxFiles=3 />
                <x-input-error :messages="$errors->get('salinan_lesen_memandu')" class="mt-2" />
                @if($userPermit->salinan_lesen_memandu_status == 1)
                <x-input-label for="salinan_lesen_memandu" :value="__('[Please Resubmit The Documents]')"
                    style="color:red;margin-top:1em;" />
                <x-textarea-input id="" class="block mt-1 w-full" type="text" name="" placeholder="" style="color:red;"
                    :value="'Comments: ' . $userPermit->salinan_lesen_memandu_comment" disabled />
                @endif
            </div>

            <!-- Salinan Geran Kenderaan -->
            <div class="mb-3">
                <x-input-label for="salinan_geran_kenderaan" :value="__('Salinan Geran Kenderaan')" />
                <x-multiple-image-input id="salinan_geran_kenderaan" class="block mt-1 w-full" type="text"
                    name="salinan_geran_kenderaan" placeholder="" :value="old('salinan_geran_kenderaan')"
                    :defaultFilePath="$fileArray['salinan_geran_kenderaan'] ?? ''" :displayWidget=true
                    :displayMode=!$isEditPage :disabled="$userPermit->salinan_geran_kenderaan_status == 2"
                    autocomplete="salinan_geran_kenderaan" :editMode=true :data_name="'salinan_geran_kenderaan'"
                    :maxFiles=3 />
                <x-input-error :messages="$errors->get('salinan_geran_kenderaan')" class="mt-2" />
                @if($userPermit->salinan_geran_kenderaan_status == 1)
                <x-input-label for="salinan_geran_kenderaan" :value="__('[Please Resubmit The Documents]')"
                    style="color:red;margin-top:1em;" />
                <x-textarea-input id="" class="block mt-1 w-full" type="text" name="" placeholder="" style="color:red;"
                    :value="'Comments: ' . $userPermit->salinan_geran_kenderaan_comment" disabled />
                @endif
            </div>

            <!-- Salinan Insurans Kenderaan -->
            <div class="mb-3">
                <x-input-label for="salinan_insurans_kenderaan" :value="__('Salinan Insurans Kenderaan')" />
                <x-multiple-image-input id="salinan_insurans_kenderaan" class="block mt-1 w-full" type="text"
                    name="salinan_insurans_kenderaan" placeholder="" :value="old('salinan_insurans_kenderaan')"
                    :defaultFilePath="$fileArray['salinan_insurans_kenderaan'] ?? ''" :displayWidget=true
                    :displayMode=!$isEditPage :disabled="$userPermit->salinan_insurans_kenderaan_status == 2"
                    autocomplete="salinan_insurans_kenderaan" :editMode=true :data_name="'salinan_insurans_kenderaan'"
                    :maxFiles=3 />
                <x-input-error :messages="$errors->get('salinan_insurans_kenderaan')" class="mt-2" />
                @if($userPermit->salinan_insurans_kenderaan_status == 1)
                <x-input-label for="salinan_insurans_kenderaan" :value="__('[Please Resubmit The Documents]')"
                    style="color:red;margin-top:1em;" />
                <x-textarea-input id="" class="block mt-1 w-full" type="text" name="" placeholder="" style="color:red;"
                    :value="'Comments: ' . $userPermit->salinan_insurans_kenderaan_comment" disabled />
                @endif
            </div>

            <!-- Salinan Road Tax -->
            <div class="mb-3">
                <x-input-label for="salinan_road_tax" :value="__('Salinan Road Tax')" />
                <x-multiple-image-input id="salinan_road_tax" class="block mt-1 w-full" type="text"
                    name="salinan_road_tax" placeholder="" :value="old('salinan_road_tax')"
                    :defaultFilePath="$fileArray['salinan_road_tax'] ?? ''" :displayWidget=true
                    :disabled="$userPermit->salinan_road_tax_status == 2" :displayMode=!$isEditPage
                    autocomplete="salinan_road_tax" :editMode=true :data_name="'salinan_road_tax'" :maxFiles=3 />
                <x-input-error :messages="$errors->get('salinan_road_tax')" class="mt-2" />
                @if($userPermit->salinan_road_tax_status == 1)
                <x-input-label for="salinan_road_tax" :value="__('[Please Resubmit The Documents]')"
                    style="color:red;margin-top:1em;" />
                <x-textarea-input id="" class="block mt-1 w-full" type="text" name="" placeholder="" style="color:red;"
                    :value="'Comments: ' . $userPermit->salinan_road_tax_comment" disabled />
                @endif
            </div>

            <!-- Gambar Kenderaan -->
            <div class="mb-3">
                <x-input-label for="gambar_kenderaan" :value="__('Gambar Kenderaan')" />
                <x-multiple-image-input id="gambar_kenderaan" class="block mt-1 w-full" type="text"
                    name="gambar_kenderaan" placeholder="" :value="old('gambar_kenderaan')"
                    :defaultFilePath="$fileArray['gambar_kenderaan'] ?? ''" :displayWidget=true
                    :disabled="$userPermit->gambar_kenderaan_status == 2" :displayMode=!$isEditPage
                    autocomplete="gambar_kenderaan" :editMode=true :data_name="'gambar_kenderaan'" :maxFiles=3 />
                <x-input-error :messages="$errors->get('gambar_kenderaan')" class="mt-2" />

                <!--//If rejected-->
                @if($userPermit->gambar_kenderaan_status == 1)
                <x-input-label for="gambar_kenderaan" :value="__('[Please Resubmit The Documents]')"
                    style="color:red;margin-top:1em;" />
                <x-textarea-input id="" class="block mt-1 w-full" type="text" name="" placeholder="" style="color:red;"
                    :value="'Comments: ' . $userPermit->gambar_kenderaan_comment" disabled />
                @endif
            </div>

            <!-- Permit Log / Changelog Section -->
            <div style="position: relative; display: flex; justify-content: center;" class="mb-3">
                <!-- Centered Border -->
                <div style="position: absolute; bottom: 0; width: 30%; border-bottom: 1px solid black;"></div>
                <!-- Content -->
                <h3 class="mb-15 mt-15">{{__('Application Log')}}</h3>
            </div>



            {{--  Permit Log Table --}}
            <div class="progress-table-wrap mb-5">
                <div class="progress-table text-center bg-white">
                    <div class="table-head text-left column-hover">
                        <div class="visit text-center" style="width:30%;">
                            <a href="#">
                                {{_('Date Added')}}
                            </a>
                        </div>

                        <div class="visit text-left" style="width:70%;">
                            <a href="#">
                                {{_('Action')}}
                            </a>
                        </div>
                    </div>
                    @if($tables->isEmpty())
                    <!-- Display empty icon -->
                    <div class="empty-table-icon bg-white" style="margin-top:1em;">
                        <i class="fas fa-folder-open fa-5x" style="opacity: 0.3;"></i>
                        <p style="font-size: 1.5em; opacity: 0.5;">{{ __('No data available') }}</p>
                    </div>
                    @else

                    @foreach($tables as $table)
                    <div class="table-row text-left" style="">
                        <div class="visit justify-content-center" style="width:30%;">{{ $table->created_at ?? '' }}
                        </div>
                        <div class="visit justify-content-left " style="width:70%;">{{ $table->description?? '' }}</div>

                    </div>
                    @endforeach

                    @endif
                </div>
            </div>

            {{-- Payment Button --}}

            <!-- Custom CSS -->
            <style>
            .btn-custom-confirm {
                background-color: green;
                color: #ffffff;
                border: none;
                padding: 10px 20px;
                border-radius: 5px;
                font-size: 16px;
            }

            .btn-custom-confirm:hover {
                background-color: darkgreen;
            }
            </style>

            @if($userPermit->status == 2)
            <a style="margin-bottom:0.5em; width:100%;display: inline-flex; align-items: center; justify-content: center;border-radius: 5px;text-align: center; font-size: 16px; font-weight: bold;height:3em;"
                href="#" onclick="showTransactionAlert(event)"
                class="text-center genric-btn danger success-border small"><i class="fas fa-credit-card"
                    style="margin-right: 8px;"></i>{{ __('Proceed To Payment') }}</a>
            @endif

            {{--   Table Ends  --}}

            <!-- Back Button -->
            <div class="mb-3 text-right">
                <a href="#" style="margin-bottom:0.5em; width:8em;" onclick="goToHomePage()"
                    class="text-center genric-btn danger success-border small">{{ __('Back') }}</a>

                <!-- Edit Button -->
                @if(!($isEditPage))
                <a href="{{ route('application.edit', $userPermit->id) }}" style="margin-bottom:0.5em; width:8em;"
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
    $(document).ready(function() {
        // Check if "edit" exists in the URL
        var isEditPage = window.location.href.includes('edit');

        // If the URL contains "edit", set editStatus to true; otherwise, set it to false
        var editStatus = isEditPage ? true : false;

        // Get all input elements with the specified class
        var inputElements = $('.');

        // If editStatus is true, add the 'disabled' attribute to all input elements with the specified class
        if (editStatus) {
            inputElements.attr('disabled', 'disabled');
        }
    });
    //To fix storage URL
    function openFile(filePath) {
        if (filePath) {
            var startUrl = "{{ url('/') }}/";
            // Check if the filePath already contains startUrl
            if (!filePath.startsWith(startUrl)) {
                // If not, concatenate startUrl with filePath
                filePath = startUrl + filePath;
            }
            window.open(filePath, '_blank');
        }
    }
    </script>
    <script>
    function goToHomePage() {
        // Get the current URL
        var currentUrl = window.location.href;

        // Extract the base URL by removing the last segment (e.g., "/15")
        var baseUrl = currentUrl.substring(0, currentUrl.lastIndexOf('/'));

        // Navigate to the home page of the particular type of page
        window.location.href = baseUrl;
    }
    </script>
    <!-- JavaScript for SweetAlert -->
    <script>
    function showTransactionAlert(event) {
        event.preventDefault(); // Prevents the default link behavior

        Swal.fire({
            title: 'Processing',
            text: 'Please do not exit or refresh the page during the transaction process.',
            icon: 'info',
            showCancelButton: false,
            customClass: {
                confirmButton: 'btn-custom-confirm'
            },
            confirmButtonText: 'Okay',
            allowOutsideClick: false
        }).then((result) => {
            if (result.isConfirmed) {
                // Redirect or perform action after the alert
                window.location.href = "{{ url('/complete-payment/' . $userPermit->id) }}";
            }
        });
    }
    </script>

    </x-layouts.app>