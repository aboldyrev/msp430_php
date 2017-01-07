google.charts.load('current', {'packages': ['line', 'corechart']});
google.charts.setOnLoadCallback(drawChart);

function drawChart()
{
	var settings = {
		title: 'Температура и освещение за последние сутки',
		colors: ['#4CAF50', '#F44336'],
		labels: {
			temp: 'Тепрература (°C)',
			light: 'Освещённость'
		}
	};

	var chartDiv = document.getElementById('chart_div');

	var data = new google.visualization.DataTable();
	data.addColumn('string', 'Время');
	data.addColumn('number', "Температура");
	data.addColumn('number', "Освещённость");

	timestamps.forEach(function (value, index)
	{
		data.addRow([
			value,
			Number(temperatures[index]),
			Number(lights[index])]
		);
	});


	// Настройки для отрисовки графика в material design стиле
	var materialOptions = {
		chart: {
			title: settings.title
		},
		colors: settings.colors,
		width: settings.width,
		height: settings.height,
		series: {
			// Gives each series an axis name that matches the Y-axis below.
			0: {axis: 'Temps'},
			1: {axis: 'Daylight'}
		},
		axes: {
			// Adds labels to each axis; they don't have to match the axis names.
			y: {
				Temps: {label: settings.labels.temp},
				Daylight: {label: settings.labels.light}
			}
		}
	};

	// Настройки для отрисовки графика в класическо стиле
	var classicOptions = {
		title: settings.title,
		colors: settings.colors,
		width: settings.width,
		height: settings.height,
		curveType: 'function',
		// Gives each series an axis that matches the vAxes number below.
		series: {
			0: {targetAxisIndex: 0},
			1: {targetAxisIndex: 1}
		},
		vAxes: {
			// Adds titles to each axis.
			0: {title: settings.labels.temp},
			1: {title: settings.labels.light}
		}
	};


	function drawChart(material)
	{
		if (material == true) {
			var materialChart = new google.charts.Line(chartDiv);
			materialChart.draw(data, materialOptions);
		} else {
			var classicChart = new google.visualization.LineChart(chartDiv);
			classicChart.draw(data, classicOptions);
		}

	}

	drawChart();
}