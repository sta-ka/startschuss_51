@if(Session::has('errors')) 
	<ul class="validation-errors">
		@foreach ($errors->toArray() as $error)
			<li>{{ $error[0] }}</li>
		@endforeach
	</ul>
@endif

