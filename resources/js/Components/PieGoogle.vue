<template>
    <table style="margin-bottom: 20px">
        <tbody>
            <tr>
                <td style="width: 50%">
                    <div class="table100">
                        <h2 class="mb-3">
                            <span class="text-blue-400"
                                >Po name:-{{ po_name }}({{po_no}})</span
                            >
                            |
                            <span class="text-green-400"
                                >Co name:-{{ co_name }}({{co_no}})</span
                            >
                        </h2>
                        <table>
                            <thead>
                                <tr class="table100-head">
                                    <th class="column1">Roll</th>
                                    <th class="column2">Mark</th>
                                    <th class="column3">Total</th>
                                    <th class="column4">%</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(result, i) in results" :key="i">
                                    <td class="column1">
                                        {{ result.roll }}
                                    </td>
                                    <td class="column2">
                                        {{
                                            result.rel_marks_sum_marks
                                                ? result.rel_marks_sum_marks
                                                : "_"
                                        }}
                                    </td>
                                    <td class="column3">
                                        {{
                                            result.rel_marks_sum_total
                                                ? result.rel_marks_sum_total
                                                : "_"
                                        }}
                                    </td>
                                    <td
                                        class="column4"
                                        :style="{
                                            backgroundColor: markPercentColor(
                                                result.rel_marks_sum_marks,
                                                result.rel_marks_sum_total
                                            ),
                                            color: 'black',
                                        }"
                                    >
                                        {{
                                            result.rel_marks_sum_total &&
                                            result.rel_marks_sum_marks
                                                ? markPercent(
                                                      result.rel_marks_sum_marks,
                                                      result.rel_marks_sum_total
                                                  )
                                                : "_"
                                        }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </td>
                <td style="width: 50%; vertical-align: top;">
                    <div :id="name" style="height: 500px"></div>
                </td>
            </tr>
        </tbody>
    </table>
</template>

<script>
import ResultTable from "./ResultTable.vue";
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
                    [`Below 40%(${self.below_40})`, self.below_40],
                    [`60%-79%(${self.below_80})`, self.below_80],
                    [`40%-59%(${self.below_60})`, self.below_60],
                    [`Above 80%(${self.above_80})`, self.above_80],
                ]);
                var options = {
                    title: "Po No:-" + self.po_no + " | Co No:-" + self.co_no,
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
    props: [
        "name",
        "co_name",
        "po_name",
        "co_no",
        "po_no",
        "below_40",
        "below_80",
        "below_60",
        "above_80",
        "results",
    ],
    methods: {
        markPercent(mark, total) {
            let percentage = ((mark / total) * 100).toFixed(3);
            return percentage;
        },
        markPercentColor(mark, total) {
            let percentage = ((mark / total) * 100).toFixed(3);
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
    },

    components: { ResultTable },
};
</script>
<style scoped>
img {
    max-width: 100% !important;
}

.limiter {
    width: 100%;
    margin: 0 auto;
}

.container-table100 {
    width: 100%;
    min-height: 100vh;
    background: #c850c0;
    background: -webkit-linear-gradient(45deg, #4158d0, #c850c0);
    background: -o-linear-gradient(45deg, #4158d0, #c850c0);
    background: -moz-linear-gradient(45deg, #4158d0, #c850c0);
    background: linear-gradient(45deg, #4158d0, #c850c0);

    display: -webkit-box;
    display: -webkit-flex;
    display: -moz-box;
    display: -ms-flexbox;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-wrap: wrap;
    padding: 33px 30px;
}

.wrap-table100 {
    width: 1170px;
}

.table100 table {
    border-spacing: 1;
    border-collapse: collapse;
    background: white;
    border-radius: 10px;
    overflow: hidden;
    width: 100%;
    margin: 0 auto;
    position: relative;
}
.table100 table * {
    position: relative;
}
.table100 table td,
.table100 table th {
    padding-left: 8px;
}
.table100 table thead tr {
    height: 60px;
    background: #36304a;
}
.table100 table tbody tr {
    height: 50px;
}
.table100 table tbody tr:last-child {
    border: 0;
}
.table100 table td,
.table100 table th {
    text-align: left;
}
.table100 table td.l,
.table100 table th.l {
    text-align: right;
}
.table100 table td.c,
.table100 table th.c {
    text-align: center;
}
.table100 table td.r,
.table100 table th.r {
    text-align: center;
}

.table100-head th {
    font-family: OpenSans-Regular;
    font-size: 18px;
    color: #fff;
    line-height: 1.2;
    font-weight: unset;
}

tbody tr:nth-child(even) {
    background-color: #f5f5f5;
}

tbody tr {
    font-family: OpenSans-Regular;
    font-size: 15px;
    color: #808080;
    line-height: 1.2;
    font-weight: unset;
}

tbody tr:hover {
    color: #555555;
    background-color: #f5f5f5;
    cursor: pointer;
}

.column1 {
    width: 100px;
    padding-left: 40px;
}

.column2 {
    width: 100px;
}

.column3 {
    width: 100px;
}

.column4 {
    width: 110px;
    text-align: right;
    padding-right: 1rem;
}

.column5 {
    width: 170px;
    text-align: right;
}

.column6 {
    width: 222px;
    text-align: right;
    padding-right: 62px;
}

@media screen and (max-width: 992px) {
    table {
        display: block;
    }
    table > *,
    table tr,
    table td,
    table th {
        display: block;
    }
    table thead {
        display: none;
    }
    table tbody tr {
        height: auto;
        padding: 37px 0;
    }
    table tbody tr td {
        padding-left: 40% !important;
        margin-bottom: 24px;
    }
    table tbody tr td:last-child {
        margin-bottom: 0;
    }
    table tbody tr td:before {
        font-family: OpenSans-Regular;
        font-size: 14px;
        color: #999999;
        line-height: 1.2;
        font-weight: unset;
        position: absolute;
        width: 40%;
        left: 30px;
        top: 0;
    }
    .column4,
    .column5,
    .column6 {
        text-align: left;
    }

    .column4,
    .column5,
    .column6,
    .column1,
    .column2,
    .column3 {
        width: 100%;
    }

    tbody tr {
        font-size: 14px;
    }
}

@media (max-width: 576px) {
    .container-table100 {
        padding-left: 15px;
        padding-right: 15px;
    }
}
</style>
