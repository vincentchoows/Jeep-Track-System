@props(['disabled' => false, 'value' => '', 'height' => '7em'])

<textarea {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm', 'style' => "height: $height;"]) !!} >{{ $value }}</textarea>
