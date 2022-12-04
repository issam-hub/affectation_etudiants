import { Chart } from "chart.js/auto";

Chart.defaults.color = "#FFFFFF";
Chart.defaults.font.size = 16;
var pieCanvas = document.getElementById("mychart");

var pieData = {
   labels: ["enrolled", "not enrolled"],
   datasets: [
      {
         data: [10.3, 8.2],
         backgroundColor: ["#FF6384", "#63FF84"],
      },
   ],
};

var pieChart = new Chart(pieCanvas, {
   type: "pie",
   data: pieData,
   options: {
      plugins: {
         legend: {
            labels: {
               // This more specific font property overrides the global property
               font: {
                  size: 20,
               },
            },
         },
      },
   },
});
