<!DOCTYPE html>
<html>
	<head>
		<title>Laravel</title>
		<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
		<style>
			html, body {
				margin: 0;
				padding: 0;
				height: 100%;
			}
		</style>
	</head>
	<body>
		<div>
			<a href="{{ route('index', ['hours' => 12]) }}">Последние 12 часов</a>
			<a href="{{ route('index', ['hours' => 24]) }}">Последние 24 часа</a>
		</div>
		<div id="chart_div" style="height: 99%;"></div>

		<script type="text/javascript">
			var timestamps = [{!! implode(',', $timestamps) !!}],
				temperatures = [{!! implode(',', $temperatures) !!}],
				lights = [{!! implode(',', $lights) !!}],
				hours = parseInt({!! $hours !!});
		</script>
		<script type="text/javascript" src="{{ asset('js/chart.js') }}"></script>
	</body>
</html>