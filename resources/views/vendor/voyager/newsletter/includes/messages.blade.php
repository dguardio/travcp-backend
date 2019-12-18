@if (count($errors) > 0)
@foreach($errors->all() as $error)
<p class="alert alert-danger">{{ $error }}</p>
@endforeach
@endif

@if (session()->has('message'))
<div class="alert alert-success">{{session('message')}}</div>
@endif

@if (session()->has('status'))
<div style="color: #00004A; font-size:12px; display:block; font-family:Rubik; font-weight: 500;">{{session('status')}}</div>
@endif