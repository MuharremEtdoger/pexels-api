@include('header', ['status' => 'complete'])
@if (isset($photo_infos))
	<div class="webtures-app-photographers-list-area">
		<div class="container">
			<h2 class="webtures-app-page-title">Photos</h2>
			<div class="row">
				@foreach ($photo_infos as $photo)
					<div class="col col-lg-4 photo-col">
						<a class="photo-card" href="{{$photo['pexel_url']}}" target="_blank" style="background-image:url({{ $photo['sizes']->getMedium }})">
						   <h3>{{$photo['title']}}</h3>
						</a>
						<ul class="images-size">
							@foreach ($photo['sizes'] as $ksize => $vsize)
								@if (isset($image_sizes_text[$ksize]))
									<li><a href="{{$vsize}}" target="_blank">{{ $image_sizes_text[$ksize] }}</a></li>
							    @endif
							@endforeach
						</ul>
					</div>
				@endforeach
		    </div>
			<a href="{{ url('/reset-site')}}" class="btn btn-danger webtures-app-btn">Verileri Sıfırla</a>
		</div>
	</div>
@endif
@include('footer', ['status' => 'complete'])