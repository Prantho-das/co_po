<template>
    <div :id="name" style="height:400px"></div>
</template>

<script>
export default {
    name: "PieChart",
    data() {
        return {};
    },
    mounted() {
        let self = this;
        if (self.name) {
            google.charts.load("current", { packages: ["corechart"] });
            google.charts.setOnLoadCallback(drawChart);

            function drawChart() {
                var data = google.visualization.arrayToDataTable([
                    ["Task", "Co Po In A Batch"],
                    ["Below 40%", self.below_40],
                    ["60%-79%", self.below_80],
                    ["40%-59%", self.below_60],
                    ["Above 80%", self.above_80],
                ]);

                var options = {
                    title: self.po_name,
                    colors: [
                        "rgb(255,0,0)",
                        "rgb(0, 110, 189)",
                        "rgb(255, 251, 1)",
                        "rgb(0,128,0)",
                    ],
                    is3D: true,
                };
                let chart_div = document.getElementById(self.name);
                var chart = new google.visualization.PieChart(chart_div);
                google.visualization.events.addListener(
                    chart,
                    "ready",
                    function () {
                        chart_div.innerHTML = `<img src="${chart.getImageURI()}" alt="Pie Chart">`;
                    }
                );
                chart.draw(data, options);
            }
        }
    },
    props: ["name", "po_name", "below_40", "below_80", "below_60", "above_80"],
};
</script>
<style>
img {
    max-width: 100% !important;
}
</style>
