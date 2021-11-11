<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="csrf-token" content="{{ csrf_token() }}">
		<title>Unique Ladder</title>
		<link href="{{ asset("css/app.css") }}" rel="stylesheet">
	</head>
	<body>
		<header>
			<nav class="navbar navbar-dark bg-dark shadow-sm">
				<div class="container d-flex justify-content-between">
					<a href="{{ url("/") }}" class="navbar-brand d-flex align-items-center">
						Unique Ladder
					</a>

					{!! Form::open(["action" => "HomeController@postFindAccount", "method" => "POST", "class" => "form-inline mt-2 mt-md-0"]) !!}
						{!! Form::text("accountName", "", ["class" => "form-control mr-sm-2", "placeholder" => "Enter Account Name"]) !!}
					{!! Form::close() !!}
				</div>
			</nav>
		</header>

		<div class="white-wrapper">
			<main role="main" class="container py-4">
				@if (Session::has("error"))
					<div class="alert alert-danger fade show" role="alert">
						{{ trans(Session::get("error")) }}
					</div>
				@endif

				@if (Session::has("success"))
					<div class="alert alert-success fade show" role="alert">
						{{ trans(Session::get("success")) }}
					</div>
				@endif

				@yield("content")
			</main>
		</div>

		<footer class="text-muted">
			<div class="container">
				<p class="float-right">
					<a href="#">Back to top</a>
				</p>
				<p>This product isn't affiliated with or endorsed by Grinding Gear Games in any way.</p>
			</div>
		</footer>

		<script src="{{ asset("js/app.js") }}"></script>
	</body>
</html>