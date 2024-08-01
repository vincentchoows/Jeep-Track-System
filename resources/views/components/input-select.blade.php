@props(['disabled' => false, 'content' => '1', 'options' => [], 'value' => null])

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
    +padding: 0px 0px 0px 1em !important;
}
</style>

<select
    {{ $disabled ? 'disabled' : ''}}
    {{ $attributes->merge(['class' => 'form-control w-100 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mb-3 full-width justify-content-center']) }}>
    <option value="">Choose an option</option>
    @foreach($options as $option)
    <option value="{{ $option->id }}" @if($option->id == $value) selected @endif>{{ $option->name }}</option>
    @endforeach
</select>