<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate Template</title>
</head>
<style>
@import url('https://fonts.googleapis.com/css?family=Open+Sans|Pinyon+Script|Rochester');

.cursive {
    font-family: Cambria, Georgia, serif;
    font-style: italic;
}

body {
    max-width: 100%;
    max-height:100vh;
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
    border-top: 30px solid #52af3a;
    border-left: 31px solid #52af3a;
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
    border-bottom: 30px solid #52af3a;
    border-right: 31px solid #52af3a;
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
    color: #52af3a;
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
    font-weight: normal;
    font-size: 18px;
    line-height: 23px;
}

.employe-sen-name {
    color: #52af3a;
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
                <img src="{{ $logoUrl }}" class="logo head-logo" alt="">
                <img src="{{ $mainLogoUrl }}" class="logo" alt="" style="max-width: 70px;">

            </div>
            <div class="certificate-body">
                <h1 class="cursive">Certificate of Completion</h1>
                <div class="about-certificate">
                    <p>
                        This certificate has been awarded to
                    </p>
                </div>
                <h1 class="student-name ">{{ $empName }}</h1>
                <div class="certificate-content">

                    <p class="topic-title">
                        For their outstanding performence on completing highest order at the AGROJIVAN and had a great
                        influence.
                    </p>
                </div>
                <div style="margin-top: auto; position: absolute; bottom: 150px;width: 100%; left: 0;">
                <div style="float: left; width: 50%;">
                    <p class="employe-sen-name-sub">
                       BHARAT PATEL
                    </p>
                    <p class="employe-sen-name">
                        DIRECTOR
                    </p>
                </div>
                <div style="float: right; width: 50%;">
                    <p class="employe-sen-name-sub">
                        DINESH VAGHOSI
                    </p>
                    <p class="employe-sen-name">
                        MANAGER
                    </p>
                </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>