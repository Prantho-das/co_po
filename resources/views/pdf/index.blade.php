<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    {!! $content !!}
    <title>Chart</title>
    <style>
        .comment_box{
            display: none !important;
        }
        img {
            max-width: 100% !important;
        }

        .table100 {
            width: 95%;
        }

        table {
            border-spacing: 1;
            border-collapse: collapse;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            width: 100%;
            margin: 0 1rem;
            position: relative;
        }

        table * {
            position: relative;
        }

        table td,
        table th {
            padding-left: 8px;
        }

        table thead tr {
            height: 60px;
            background: #36304a;
        }

        table tbody tr {
            height: 50px;
        }

        table tbody tr:last-child {
            border: 0;
        }

        table td,
        table th {
            text-align: left;
        }

        table td.l,
        table th.l {
            text-align: right;
        }

        table td.c,
        table th.c {
            text-align: center;
        }

        table td.r,
        table th.r {
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
            color: black;
            line-height: 1.2;
            font-weight: unset;
        }

        .column1 {
            width: 50px;
            padding-left: 20px;
        }

        .column2 {
            width: 50px;
        }

        .column3 {
            width: 50px;
        }

        .column4 {
            width: 50px;
            text-align: right;
            padding-right: 1rem;
        }

    </style>
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
    {{-- {!!$comment!!} --}}
    <div>
            {!! $data !!}
    </div>
</body>

</html>
