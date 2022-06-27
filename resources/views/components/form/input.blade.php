@props(['name', 'type' => 'text', 'require' => ''])

<input id="{{ $name }}" 
       type="{{ $type }}" class="form-control @error($name) is-invalid @enderror" name="{{ $name }}"
       autocomplete="firstname" autofocus {{ $attributes(['value' => old($name)]) }}/>

