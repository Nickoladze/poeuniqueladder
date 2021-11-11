@extends("layout")

@section("content")
	<div class="jumbotron p-4">
		<div class="container">
			<div class="row">
				<div class="col-3">
					<h3>{{ $account->name }}</h3>
					<p>{{ $uniqueItems->count() }} Uniques</p>
				</div>
				<div class="col-9">
					<div class="row">
						@foreach($leagues as $league)
							<div class="col-4 text-center pb-1">
								{{ $league->name }}
								<div class="progress">
									<div class="progress-bar progress-bar-striped" style="width: 25%;"></div>
								</div>
							</div>
						@endforeach
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="album pb-5 bg-light">
		<div class="container">
			<div class="row">

				@foreach($uniqueItems as $uniqueItem)
					<div class="col-3 mb-2">
						<div class="itemBox">
							<div class="itemHeader">
								<div class="itemHeaderLeft"></div>
								<div class="itemHeaderRight"></div>

								<div class="itemName">{{ $uniqueItem->name }}</div>
								<div class="itemBase">{{ $uniqueItem->base_type }}</div>
							</div>
							<div class="itemIcon p-1">
								<img src="{{ $uniqueItem->icon }}" />
							</div>
							@if(!empty($uniqueItem->flavor_text))
								<div class="itemSeparator"></div>
								<div class="itemFlavorText p-1">{!! $uniqueItem->flavor_text !!}</div>
							@endif
						</div>
					</div>
				@endforeach

			</div>
		</div>
	</div>
@endsection