<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>

      html {
        margin:20px 30px 10px 30px;
      }

      body {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 14px;
      }

      .title table {
        width: 100%;
      }
      .title table td {
        border:0px;
      }
      .title .address {
        text-align: right;
      }
      .title .img {
        padding-right: 30px;
      }
      .title img {
        max-height: 120px;
      }

      .table-bordered {
        border-collapse: collapse;
        width:100%;
      }
      .table-bordered, th, td {
        padding: 5px;
        border: 1px solid black;
      }

      #bookingDetails table td {
        border:0px;
      }
      #bookingDetails table td:first-child {
        padding-right: 30px;
      }

      .content h1 {
        text-align: center;
        font-size: 40px;
        margin: 5px, 0px, 5px, 0px;
      }
      .content h1#header {
        text-decoration: underline;
      }
      .tblBold{
        font-weight: bold;
      }

      #info {
        page-break-inside:avoid;
        margin: 20px 60px 0px 60px;
      }

      .footer {
          width: 100%;
          text-align: center;
          position: absolute;
          bottom: 0px;
          font-size: 12px;
          color: #666;
      }

    </style>

</head>
<body>
  <div class="container">
      @yield('content')
  </div>
</body>
</html>
