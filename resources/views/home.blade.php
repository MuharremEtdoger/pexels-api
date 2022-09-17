@include('header', ['status' => 'complete'])
<div class="webtures-app-photographers-list-area">
	<div class="container">
	    <h2 class="webtures-app-page-title">Photographers</h2>
		<ul class="webtures-app-photographer-ul row">
		@foreach ($photographers as $photographer)
			<li class="col col-lg-3"><a href="{{ url('/photographer/')}}/{{$photographer->id}}" target="_blank" title="{{$photographer->getPhotographer}}">{{$photographer->getPhotographer}}</a></li>
		@endforeach
		</ul>
		<a href="{{ url('/reset-site')}}" class="btn btn-danger webtures-app-btn">Verileri Sıfırla</a>
	</div>
</div>
@include('footer', ['status' => 'complete'])