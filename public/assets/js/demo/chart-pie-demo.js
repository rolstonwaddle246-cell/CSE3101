// Set new default font family and font color to mimic Bootstrap's default styling
Chart.defaults.global.defaultFontFamily = 'Nunito, -apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = '#858796';

// Pie/Doughnut Chart Example
var ctx = document.getElementById("myPieChart");
var myPieChart = new Chart(ctx, {
  type: 'doughnut', 
  data: {
    labels: ["Male", "Female"],
    datasets: [{
      data: [60, 40], // Change these values to match your data
      backgroundColor: ['#4e73df', '#1cc88a'], // Male = blue, Female = green
      hoverBackgroundColor: ['#2e59d9', '#17a673'],
      hoverBorderColor: "rgba(234, 236, 244, 1)",
    }],
  },
  options: {
    maintainAspectRatio: false,
    tooltips: {
      backgroundColor: "rgb(255,255,255)",
      bodyFontColor: "#858796",
      borderColor: '#dddfeb',
      borderWidth: 1,
      xPadding: 15,
      yPadding: 15,
      displayColors: true,
      caretPadding: 10,
    },
    legend: {
      display: true, // Show legend for Male/Female
      position: 'bottom',
      labels: {
        padding: 30
       } // size of the color box
    },
    cutoutPercentage: 70, // 0 for pie, 80 for doughnut
  },
});

Chart.plugins.register({
  beforeDraw: function(chart) {
    if (chart.config.type !== 'doughnut') return;

    var ctx = chart.chart.ctx;
    var chartArea = chart.chartArea;

    var centerX = (chartArea.left + chartArea.right) / 2;
    var centerY = (chartArea.top + chartArea.bottom) / 2;

    var data = chart.config.data.datasets[0].data;
    var total = data.reduce((a, b) => a + b, 0);

    ctx.save();
    ctx.textAlign = "center";
    ctx.textBaseline = "middle";
    ctx.fillStyle = "#5a5c69";

    // "Total" text
    ctx.font = "600 14px Nunito";
    ctx.fillText("Total", centerX, centerY - 10);

    // Number
    ctx.font = "700 22px Nunito";
    ctx.fillText(total, centerX, centerY + 12);

    ctx.restore();
  }
});
