/* globals Chart:false, feather:false */

(function () {
  "use strict";

  feather.replace({ "aria-hidden": "true" });

  // Graphs
  var ctx = document.getElementById("myChart");
  // eslint-disable-next-line no-unused-vars
  var myChart = new Chart(ctx, {
    type: "line",
    data: {
      labels: ["SUZA", "ZU", "UDOM", "UDSM", "IAA", "MBEYA-UNI", "KAMPALA-UNI"],
      datasets: [
        {
          data: [20, 10, 3, 4, 1, 5, 7],
          lineTension: 0,
          backgroundColor: "transparent",
          borderColor: "#007bff",
          borderWidth: 4,
          pointBackgroundColor: "#007bff",
        },
      ],
    },
    options: {
      scales: {
        yAxes: [
          {
            ticks: {
              beginAtZero: false,
            },
          },
        ],
      },
      legend: {
        display: false,
      },
    },
  });
})();
