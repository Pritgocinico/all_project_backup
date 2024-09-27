<!DOCTYPE html>

<html lang="en">



<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Certificate Template</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@100..900&display=swap" rel="stylesheet">

</head>

<style>

    body {

            font-family: "Lexend", sans-serif !important;

        }



    body {

        max-width: 100%;

        max-height: 100vh;

    }



    .certificate-container {

        padding: 50px;

        max-width: 900px;

        position: relative;

    }



    .parent-footer-element {

        display: flex;

        align-items: center;

        justify-content: space-between;

    }



    .certificate-container:before {

        display: block;

        content: "";

        width: 280px;

        height: 280px;

        position: absolute;

        top: 37px;

        left: 37px;

        border-top: 30px solid #603c93;

        border-left: 31px solid #603c93;

        z-index: 1;

    }



    .certificate-container:after {

        display: block;

        content: "";

        width: 280px;

        height: 280px;

        position: absolute;

        bottom: 37px;

        right: 37px;

        border-bottom: 30px solid #603c93;

        border-right: 31px solid #603c93;

        z-index: 1;

    }



    .certificate {

        border: 2px solid #b38421;

        padding: 50px 50px 50px 50px;

        height: 825px;

        position: relative;

    }



    .certificate-header {

        display: flex;

        justify-content: space-evenly;

        align-items: center;

        gap: 200px;

        margin: 13px 0 !important;

    }



    .head-logo {

        max-width: 150px;

        margin-right: 100px !important;

    }



    .certificate-header.main-logo {

        position: absolute;

        z-index: -1;

        top: 35%;

        left: 45%;

        transform: translateY(-50%);

        transform: translateX(-50%);

        height: 250px;

        width: 250px;

    }



    .certificate:after {

        content: '';

        top: 0px;

        left: 0px;

        bottom: 0px;

        right: 0px;

        position: absolute;

        background-repeat: no-repeat;

        background-position: center;

        background-size: 400px;

        z-index: -1;

    }



    .certificate-header {

        text-align: center;

    }





    .certificate-title {

        text-align: center;

    }



    .certificate-body {

        text-align: center;

    }



    h1 {



        font-weight: 400;

        font-size: 48px;

        color: #603c93;

        text-transform: uppercase;

    }



    .student-name {

        font-size: 30px;

        color: #b38421;

    }



    .certificate-content {

        margin: 0 auto;

    }



    .about-certificate {

        width: 380px;

        margin: 0 auto;

    }



    .topic-description {



        text-align: center;

    }



    .about-certificate p,

    .topic-title {

        font-weight: bold;

    }



    .employe-sen-name {

        color: #603c93;

        font-size: 22px;

        font-weight: bold;

        margin-top: 10px;

    }



    .employe-sen-name-sub {

        margin: 0;

    }



    .certificate-footer {

        margin-top: 100px;

    }

</style>



<body style="font-family: 'Arial', sans-serif; background-color: #f0f0f0; margin: 0; padding: 0;">

    <div class="certificate-container">

        <div class="certificate">

            <div class="water-mark-overlay"></div>

            <div class="certificate-header">

                @php

$imagePath = public_path('assets/img/login/mp-group.png');
            if(isset($setting) && $setting->logo !== null){
                $imagePath = public_path('storage/'.$setting->logo);
            }

                    $imageData = base64_encode(file_get_contents($imagePath));

                    $src = 'data:' . mime_content_type($imagePath) . ';base64,' . $imageData;



                    $imagePath1 = public_path('assets/img/login/mp-group.png');
            if(isset($emp_department) && $emp_department->logo !== null){
                $imagePath1 = public_path('storage/'.$emp_department->logo);
            }
                    // $imagePath1 = public_path('storage/' . $emp_department->logo);

                    $imageData1 = base64_encode(file_get_contents($imagePath1));

                    $src1 = 'data:' . mime_content_type($imagePath1) . ';base64,' . $imageData1;



                @endphp



                

<div style="position: relative;">

<div style="width: 150px; float: left; position: absolute; height: 120px;margin-bottom: 12px;text-align: center;margin: 19px auto;/* margin-bottom: 21px; */ margin-left :90px;">

                     <img src="{{ $src1 }}" class="logo head-logo" alt="" style="width: 100%; height: 100%; object-fit: cover;  margin-left :100px;">

                </div>

                    <div style="width: 150px; float: right; position: absolute;  height: 120px;margin-bottom: 12px;text-align: center;margin: 19px auto;/* margin-bottom: 21px; */  margin-right :90px;">

                        <img src="{{ $src }}" class="logo" alt="" style="max-width: 70px;" style="width: 100%; height: 100%; object-fit: cover;">

                    </div>

</div>

                

                </div>

            <div class="certificate-body">

                <h1 class="cursive">Certificate of Completion</h1>

                <div class="about-certificate">

                    <p>

                        {{ $title }}

                    </p>

                </div>

                <h1 class="student-name ">{{ $employee->name }}</h1>

                <div class="certificate-content">



                    <p class="topic-title">

                        {{ $description }}

                    </p>

                </div>



          

                <div style=" margin-top: auto;position: absolute;bottom: 290px;width: 100%;left: 0;">

                    <div style="float: left;width: 30%;left: 80px; margin-left: 110px; ">

                        @php

                            $src2 = '';

                            $src3 = '';

                            if ($faLogoPath !== '') {

                                $imagePath2 = public_path('storage/' . $faLogoPath);

                                $imageData2 = base64_encode(file_get_contents($imagePath2));

                                $src2 = 'data:' . mime_content_type($imagePath2) . ';base64,' . $imageData2;

                            }

                            if ($mangerSignPath !== '') {

                                $imagePath3 = public_path('storage/' . $mangerSignPath);

                                $imageData3 = base64_encode(file_get_contents($imagePath3));

                                $src3 = 'data:' . mime_content_type($imagePath3) . ';base64,' . $imageData3;

                            }

                        @endphp

                        <div style="width: 150px;height: 120px;margin-bottom: 12px;text-align: center;margin: 19px auto;/* margin-bottom: 21px; */">

                            <img src="{{ $src2 }}" class="logo head-logo @if($src2 == null) d-none   @endif" style="width: 100%; height: 100%; object-fit: cover; margin-left: 100px;" alt="" />

                        </div>

                        <div style="">

                        <p class="employe-sen-name-sub" style="">

                            {{ $director }}

                        </p>

                        </div>

                        <div style="">

                        <p class="employe-sen-name">

                            DIRECTOR

                        </p>

                        </div>

                    </div>

                    <div style="float: right;width: 30%;margin-right: 110px;">

                    <div style="width: 150px;height: 120px;margin-bottom: 12px;text-align: center;margin: 19px auto;">

                        <img src="{{ $src3 }}" class="logo head-logo @if($src3 == null) d-none @endif" style="width: 100%; height: 100%; object-fit: cover; margin-left: 100px;" alt="">

                        </div>

                        <div style="">

                        <p class="employe-sen-name-sub" >

                            {{ $manager }}

                        </p>

                        </div>

                        <div style="" >

                        <p class="employe-sen-name">

                            MANAGER

                        </p>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</body>



</html>

