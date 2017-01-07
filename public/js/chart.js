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


	var classicOptions = {
		title: 'Температура и освещение за последние сутки',
		colors: ['#4CAF50', '#F44336'],
		width: 1900,
		height: 900,
		// Gives each series an axis that matches the vAxes number below.
		series: {
			0: {targetAxisIndex: 0},
			1: {targetAxisIndex: 1}
		},
		vAxes: {
			// Adds titles to each axis.
			0: {title: 'Тепрература (°C)'},
			1: {title: 'Освещённость'}
		}
	};


	function drawMaterialChart() {
		var materialChart = new google.charts.Line(chartDiv);
		materialChart.draw(data, materialOptions);
	}


	function drawClassicChart() {
		var classicChart = new google.visualization.LineChart(chartDiv);
		classicChart.draw(data, classicOptions);
	}

	// drawMaterialChart();
	drawClassicChart();
}