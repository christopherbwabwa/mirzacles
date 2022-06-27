@props(['name'])

<input type="radio" name="prefixname" id="{{$name}}" value="{{$name}}" @if(old($name)) checked @endif/>
<label for="{{ $name }}">{{ $name }}</label>
