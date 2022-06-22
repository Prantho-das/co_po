<template>
    <canvas :id="name" width="400" height="400"></canvas>
</template>

<script>
export default {
    name: "PieChart",
    data() {
        return {};
    },
    mounted() {
        const data = {
            labels: ["Below 40%", "60%-79%", "40%-59%", "Above 80%"],
            datasets: [
                {
                    label: "CO PO In A Batch",
                    data: [
                        this.below_40,
                        this.below_80,
                        this.below_60,
                        this.above_80,
                    ],
                    backgroundColor: [
                        "rgb(255,0,0)",
                        "rgb(0, 110, 189)",
                        "rgb(255, 251, 1)",
                        "rgb(0,128,0)",
                    ],
                    weight: 0.5,
                    hoverOffset: 4,
                },
            ],
        };
        const config = {
            type: "pie",
            data: data,
            options: {
                maintainAspectRatio: false,
                responsive: true,
                plugins: {
                    legend: {
                        position: "right",
                    },
                    title: {
                        display: true,
                        position: "bottom",
                        text: this.po_name,
                    },
                },
            },
        };
        const myChart = new Chart(
            document.getElementById(this.name).getContext("2d"),
            config
        );
    },
    props: ["name", "po_name", "below_40", "below_80", "below_60", "above_80"],
};
</script>
<style>
canvas {
    max-width: 30% !important;
    height: 500px !important;
}
</style>
