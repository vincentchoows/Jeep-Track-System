@props(['value' => null, 'disabled' => false, 'content' => '1', 'options' => [], 'id' => null, 'secondId' => null, 'secondLabel' => null, 'thirdId' => null, 'thirdLabel' => null, 'displayMode' => false])
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

    .nice-select.disabled {
        /* border-color: initial; */
        color: initial;
    }
</style>

@if($displayMode)
    <!-- Applicant Category Field -->
    <select id="{{ $id }}" onchange="toggleInput()" {{ $disabled ? 'disabled' : '' }} {{ $attributes->merge(['class' => 'form-control w-100 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mb-3 full-width justify-content-center', ':value' => $value]) }}>
        <option value="0">{{ __('Choose an option') }}</option>
        @foreach($options as $option)
            <option value="{{ $option->id }}" {{ $value == $option->id ? 'selected' : '' }}>{{ $option->name }}</option>
        @endforeach
    </select>
@else

    <select id="{{ $id }}" onchange="toggleInput()" {{ $disabled ? 'disabled' : '' }} {{ $attributes->merge(['class' => 'form-control w-100 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mb-3 full-width justify-content-center', ':value' => $value]) }}>
        <option value="0">{{ __('Choose an option') }}</option>
        @foreach($options as $option)
            <option value="{{ $option->id }}" {{ $value == $option->id ? 'selected' : '' }}>{{ $option->name }}</option>
        @endforeach
    </select>
    <div id="{{$secondId}}" class="mb-3" style=" margin-bottom: -0em; display:none;">
        <x-input-label for="{{$secondId}}" :value="$secondLabel" />
        <x-text-input id="" class="block mt-1 w-full " type="text" name="{{$secondId}}" placeholder=""
            :value="old('{{$secondId}}')" autocomplete="{{$secondId}}" />
        <x-input-error :messages="$errors->get('{{$secondId}}')" class="mt-2" />

        <x-input-label for="{{$thirdId}}" :value="$thirdLabel" class="mt-3" />
        <x-textarea-input id="" class="block mt-1 w-full " type="text" name="{{$thirdId}}" placeholder=""
            :value="old('{{$thirdId}}')" autocomplete="{{$thirdId}}" />
        <x-input-error :messages="$errors->get('{{$thirdId}}')" class="mt-2" />
    </div>
@endif

<script>
    function toggleInput() {
        var selectValue = document.getElementById("{{$id}}").value;
        var inputField = document.getElementById("{{$secondId}}");
        if (selectValue === "1") {
            inputField.style.display = "none"; // Show the input field
        } else {
            inputField.style.display = "block"; // Hide the input field
        }
    }
</script>