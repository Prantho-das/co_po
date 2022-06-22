<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    {!! $content !!}
    <title>Chart</title>
</head>
<body>
    <div style="text-align: center">
        <h1 style="font-weight: bold">
            Dhaka International University
        </h1>
        <h1>
            Teacher Name: <span style="color:blue;">{{$teacherName}}</span>
        </h1>
        <h2>Batch Name: {{$batchName}}</h2>
        <h2>Course Name: {{$courseName}}</h2>
        <h2>Course Code: {{$courseCode}}</h2>
    </div>
    <hr style="margin:1rem 0rem;"/>
    <div style="border-left: 1px solid blue;border-right: 1px solid blue;">
        {!! $data !!}
    </div>
</body>

</html>
