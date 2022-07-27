<template>
    <div id="chart_div" style="height: 400px; width: 100%"></div>
</template>

<script>
export default {
    mounted() {
        google.charts.load("current", { packages: ["corechart", "bar"] });
        google.charts.setOnLoadCallback(this.drawBasic);
    },
    props: ["po_result"],
    methods: {
         markPercentColor(percentage) {
            if (percentage >= 80) {
                return "rgb(0,128,0)";
            } else if (percentage <= 79 && percentage >= 60) {
                return "rgb(0, 110, 189)";
            } else if (percentage <= 59 && percentage >= 40) {
                return "rgb(255, 251, 1)";
            } else {
                return "rgb(255,0,0)";
            }
        },
        drawBasic() {
            let dataRow = [["PO", "Po Percent Rate", { role: "style" }]];
            this.po_result.forEach((val) => {
                dataRow.push([val.po_no, val.percent, `color: ${this.markPercentColor(val.percent)}`]);
            });

            var data = google.visualization.arrayToDataTable(dataRow);

            var options = {
                title: "Individual Co Po Result",
                hAxis: {
                    title: "Po Result",
                },
                vAxis: {
                    title: "Percentage",
                },
            };
            let chart_div = document.getElementById("chart_div");
            var chart = new google.visualization.ColumnChart(chart_div);
            google.visualization.events.addListener(
                chart,
                "ready",
                function () {
                    chart_div.innerHTML = `<img src="${chart.getImageURI()}" alt="Bar Po Chart">`;
                }
            );
            chart.draw(data, options);
        },
    },
};
</script>

<style></style>
