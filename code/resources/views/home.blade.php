@extends("layout")

@section("content")
	<h1>Top 100 Accounts:</h1>
	@foreach($topAccounts as $account)
		{{ $account->total_uniques }} - <a href="{{ action("HomeController@showAccount", $account) }}">{{ $account->name }}</a><br />
	@endforeach
@endsection