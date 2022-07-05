<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    {!! $content !!}
    <title>Result</title>
</head>

<body>
    <div style="text-align: center">
        <h1 style="font-weight: bold">
            Dhaka International University
        </h1>
        <h1>
            Teacher Name: <span style="color:blue;">{{ $teacherName }}</span>
        </h1>
        <h2>Batch Name: {{ $batchName }}</h2>
        <h2>Course Name: {{ $courseName }}</h2>
        <h2>Course Code: {{ $courseCode }}</h2>
    </div>
    @if ($comment != null)
    <hr style="margin:1rem 0rem;" />
    <h3><span style='font-weight:bold;'>Comment:</span> {{ $comment }}</h3>
    @endif
    <hr style="margin:1rem 0rem;" />
    <div style="border-left: 1px solid blue;border-right: 1px solid blue;">
        {!! $data !!}
    </div>
</body>

</html>

{{--
google.charts.load('current', {packages: ['corechart', 'bar']});
google.charts.setOnLoadCallback(drawBasic);

function drawBasic() {

var data = new google.visualization.DataTable();
data.addColumn('timeofday', 'Time of Day');
data.addColumn('number', 'Motivation Level');

data.addRows([
[{v: [8, 0, 0], f: '8 am'}, 1],
[{v: [9, 0, 0], f: '9 am'}, 2],
[{v: [10, 0, 0], f:'10 am'}, 3],
[{v: [11, 0, 0], f: '11 am'}, 4],
[{v: [12, 0, 0], f: '12 pm'}, 5],
[{v: [13, 0, 0], f: '1 pm'}, 6],
[{v: [14, 0, 0], f: '2 pm'}, 7],
[{v: [15, 0, 0], f: '3 pm'}, 8],
[{v: [16, 0, 0], f: '4 pm'}, 9],
[{v: [17, 0, 0], f: '5 pm'}, 10],
]);

var options = {
title: 'Motivation Level Throughout the Day',
hAxis: {
title: 'Time of Day',
format: 'h:mm a',
viewWindow: {
min: [7, 30, 0],
max: [17, 30, 0]
}
},
vAxis: {
title: 'Rating (scale of 1-10)'
}
};

var chart = new google.visualization.ColumnChart(
document.getElementById('chart_div'));

chart.draw(data, options);
} --}}
