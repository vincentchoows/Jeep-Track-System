@props(['disabled' => false, 'required' => false, 'autocaps' => false])

<input {{ $required ? 'required' : '' }}
       {{ $disabled ? 'disabled' : '' }}
       {!! $attributes->merge(['class' => 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm']) !!}
       {!! $autocaps ? $attributes->merge(['style' => 'text-transform: uppercase;']) : '' !!}>
