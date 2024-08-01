@props(['disabled' => false, 'content' => '1', 'options' => [], 'id' => 'firstId', 'secondId' => 'secondId', 'secondLabel'])
<style>
    .full-width ul {
        width: 99% !important;
        margin: 0 auto;
        border: 1px solid #ccc;
        padding: 10px;
    }

    span {
        font-family: "Roboto", sans-serif;
        font-weight: normal;
        font-style: normal;
        margin-bottom: 2em !important;
    }

    .form-control {
        padding: 0px 0px 0px 1em !important;
    }
</style>
@php
    $holder_name_label = __('Holder Name');
    $holder_name = 'holder_name';
    $identification_no_label = __('Identification No.');
    $identification_no = __('identification_no');
    $contact_no_label = __('Contact No.');
    $contact_no = 'contact_no';
    $address_label = __('Address');
    $address = 'address';
@endphp

<select id="firstId" onchange="toggleInputHolder()" {{ $attributes->merge(['class' => 'form-control w-100 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mb-3 full-width justify-content-center']) }}>
    <option value="">{{__('Choose an option')}}</option>
    <option value="0">{{__('[Create new permit holder]')}}</option>
    @foreach($options as $option)
        <option value="{{$option->id}}">{{ $option->name }}</option>
    @endforeach
</select>

{{--if no existing holder is selected, render new holder input fields--}}
<div id="{{$secondId}}" class="mb-3" style=" margin-bottom: -0em; display:none;">


    <x-input-label for="{{$holder_name}}" :value="$holder_name_label" :required=true />
    <x-text-input id="" class="block mt-1 w-full input-class" type="text" name="{{$holder_name}}" placeholder=""
        :value="old('')" autocomplete="" />
    <x-input-error :messages="$errors->get('{{$holder_name}}')" class="mt-2" />

    <x-input-label for="{{$identification_no}}" :value="$identification_no_label" class="mt-3" :required=true />
    <x-text-input id="" class="block mt-1 w-full input-class" type="text" name="{{$identification_no}}" placeholder=""
        :value="old('$identification_no')" autocomplete="{{$identification_no}}" />
    <x-input-error :messages="$errors->get('$identification_no')" class="mt-2" />

    <x-input-label for="{{$contact_no}}" :value="$contact_no_label" :required=true class="mt-3" />
    <x-text-input id="" class="block mt-1 w-full input-class" type="text" name="{{$contact_no}}" placeholder=""
        :value="old('$contact_no')" autocomplete="{{$contact_no}}" />
    <x-input-error :messages="$errors->get('$contact_no')" class="mt-2" />

    <x-input-label for="{{$address}}" :value="$address_label" class="mt-3" :required=true />
    <x-text-input id="" class="block mt-1 w-full input-class" type="text" name="{{$address}}" placeholder=""
        :value="old('$address')" autocomplete="{{$address}}" />
    <x-input-error :messages="$errors->get('$address')" class="mt-2" />

</div>

<script>
    function toggleInputHolder() {
        var selectValue = document.getElementById("firstId").value;
        var inputField = document.getElementById("{{$secondId}}");
        // Check if the select value is 1
        if (selectValue === "0") {
            // if (selectValue === "1") {
            inputField.style.display = "block";
        } else {
            inputField.style.display = "none";
        }
    }
</script>