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
		<div id="chart_div" style="height: 99%;"></div>

		<script type="text/javascript">
			var timestamps = [{!! implode(',', $timestamps) !!}],
				temperatures = [{!! implode(',', $temperatures) !!}],
				lights = [{!! implode(',', $lights) !!}];
		</script>
		<script type="text/javascript" src="{{ asset('js/chart.js') }}"></script>
	</body>
</html>