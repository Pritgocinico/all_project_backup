<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Offer Letter</title>
<style>
    P{
        font-size: 20px;
        line-height: 22px;
    }
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        line-height: 1.6;
        font-size: 18px;
    }
    .container {
        width: 800px;
        margin: 0 auto;
/*        background-image: url(sampl-back.jpg);*/
        background-repeat: no-repeat;
        background-position: center;
    }
    header {
        text-align: center;
          padding-top: 10px;
          position: relative;
          height: 70px;
          background-color: #58af45; 
    }
    header img{
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
    }
    main {
        padding: 0 20px;
        border-radius: 5px;
    }
    main  h1{
        text-align: center;
        margin: 5px 0;
    }
    footer {
        text-align: center;
        border-top: 2px solid #58af45;
    }
    footer p {
        font-size: 15px;
    }
    img {
        max-width: 100%;
    }
    .name_detail_p_tag{
        line-height: 12px !important;
    }
</style>
</head>
<body>
    <div class="container">
        <header>
            <img src="{{ asset('public/assets/media/svg/AgroJivan_Green_Bg.svg') }}" alt="AgroJivan Logo" style="max-width: 250px;">
        </header>
        <main>
            <h1>Offer Letter</h1>
            <p><strong>Date:</strong> {{Utility::convertMDY('')}}</p>
            <p class="name_detail_p_tag"><strong>To:</strong> {{$employee->name}} - {{ Utility::convertMDY($employee->join_date) }}</p>
            <p class="name_detail_p_tag"><strong>Email:</strong> {{$employee->email}}</p>
            <p class="name_detail_p_tag"><strong>Phone Number:</strong> {{$employee->phone_number}}</p>
            <p class="name_detail_p_tag"><strong>Salary:</strong> {{number_format($employee->employee_salary,2)}}</p>
            <p>Dear {{$employee->name}}</p>
            <p>{!! $offerText !!}</p>
        </main>
        {{-- <footer>
            <p>AgroJivan | L/103, Arohi Elegance N/R Arohi Crest South Bopal A’Bad | +91 97277 00674</p>
        </footer> --}}
    </div>
</body>
</html>
