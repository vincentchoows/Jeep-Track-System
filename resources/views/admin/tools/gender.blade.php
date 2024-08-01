<div class="btn-group" data-toggle="buttons">
    @foreach($options as $option => $label)
    <label class="btn btn-sm user-gender {{ \Request::get('gender', 'all') == $option ? 'btn-secondary' : 'btn-light' }}" data-option="{{$option}}">
        {{$label}}
    </label>
    @endforeach
</div>