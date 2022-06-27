@props(['name'])

<label for="{{ $name }}" class="col-md-4 col-form-label text-md-end"
>
{{ ucwords($name) }}
</label>
