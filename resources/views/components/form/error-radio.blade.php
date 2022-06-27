@props(['name'])

@error($name)
    <p class="alert alert-danger sm">{{ $message }}</p>
@enderror
