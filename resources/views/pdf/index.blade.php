<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <script src="{{ asset('js/chart.min.js') }}"></script>
    <style>
        /* canvas{
            height: 500px !important;
            width: 500px !important;
        } */
    </style>
</head>

<body>
    {{-- {{ $data }} --}}
    <div style="position: relative; height:40vh; width:80vw">
        <canvas id="myChart" width="400" height="400"></canvas>
    </div>
    <script>
        const data = {
            labels: [
                'Red',
                'Blue',
                'orange',
                'green'
            ],
            datasets: [{
                label: 'My First Dataset',
                data: [300, 50, 100,30],
                backgroundColor: [
                    'rgb(255, 99, 132)',
                    'rgb(54, 162, 235)',
                    'rgb(255, 205, 86)',
                    'rgb(50,205,50)'
                ],
                hoverOffset: 4
            }]
        };
        const config = {
            type: 'pie',
            data,
            options: {
                aspectRatio:1,
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Chart.js Pie Chart'
                    }
                }
            },
        };
        const myChart = new Chart(
            document.getElementById('myChart').getContext('2d'),
            config
        );
        chart.canvas.parentNode.style.height = '128px';
        chart.canvas.parentNode.style.width = '128px';
    </script>
</body>

</html>
