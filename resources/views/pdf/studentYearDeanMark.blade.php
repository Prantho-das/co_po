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

        <div>
            <h3>Dean Year Wise Report</h3>
            <h5>Po Name:{{$po}}</h5>
            <h5>Year:{{$year}}</h5>
        </div>

    </div>
    <hr style="margin:1rem 0rem;" />
    <div style="border-left: 1px solid blue;border-right: 1px solid blue;">
        {!! $data !!}
    </div>
</body>

</html>
