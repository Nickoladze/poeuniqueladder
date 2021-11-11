<h1>{{ $account->name }}</h1>
@foreach($uniqueItems as $uniqueItem)
	{{ $uniqueItem->name }}<br />
@endforeach