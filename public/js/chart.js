google.charts.load('current', {'packages': ['line', 'corechart']});
google.charts.setOnLoadCallback(drawChart);

function drawChart() {

	var chartDiv = document.getElementById('chart_div');

	var data = new google.visualization.DataTable();
	data.addColumn('string', 'Время');
	data.addColumn('number', "Температура");
	data.addColumn('number', "Освещённость");

	var dataRows = [];

	timestamps.forEach(function (value, index) {
		dataRows[index] = [value, Number(temperatures[index]), Number(lights[index])];
	});

	console.log(dataRows);

	data.addRows(dataRows);

	var materialOptions = {
		chart: {
			title: 'Температура и освещение за последние сутки'
		},
		colors: ['#4CAF50', '#F44336'],
		width: 1800,
		height: 800,
		series: {
			// Gives each series an axis name that matches the Y-axis below.
			0: {axis: 'Temps'},
			1: {axis: 'Daylight'}
		},
		axes: {
			// Adds labels to each axis; they don't have to match the axis names.
			y: {
				Temps: {label: 'Тепрература (°C)'},
				Daylight: {label: 'Освещённость'}
			}
		}
	};

	function drawMaterialChart() {
		var materialChart = new google.charts.Line(chartDiv);
		materialChart.draw(data, materialOptions);
	}

	drawMaterialChart();

}