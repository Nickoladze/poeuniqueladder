@extends("layout")

@section("content")
	<div class="album pb-5 bg-light">
		<div class="container">
			<h1>{{ $account->name }}</h1>

			<div class="row">

				@foreach($uniqueItems as $uniqueItem)
					<div class="col-md-3 mb-2">
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