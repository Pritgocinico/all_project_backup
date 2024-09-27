<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Shree Ganesh Alluminium</title>
    <link rel="shortcut icon" href="{{url('/')}}/assets/media/image/favicon.png"/>
    <link rel="stylesheet" href="{{url('/')}}/vendors/bundle.css" type="text/css">
    <link rel="stylesheet" href="{{url('/')}}/assets/css/app.min.css" type="text/css">
</head>
<body class="form-membership">
    <div class="form-wrapper">
        <div id="logo">
            <h1>SGA</h1>
        </div>
        <h5 class="mb-0">Feedback Form</h5>
       
        <form class="alert-repeater" action="{{route('feedbackStore')}}" enctype="multipart/form-data"
            method="POST">
            @csrf
            <div class="form-row row">
                <div class="form-group ">
                    <input type="text" class="form-control" name="name" placeholder="Name" value="{{$customer->name}}" readonly>
                </div>

                <div class="form-group ">
                    <input type="text" class="form-control" name="email" placeholder="Email" value="{{$customer->email}}" readonly>
                </div>

                <div class="form-group ">
                    <input type="text" class="form-control" name="phone" placeholder="Phone" value="{{$customer->phone}}" readonly>
                </div>

                <div class="form-group ">
                    <textarea class="form-control" name="comment" placeholder="Comment"></textarea>
                </div>

                <div class="form-group ">
                    <label for="files"><strong>Upload Photos</strong></label>
                    <input class="form-control" type="file" id="files" name="feedbackfile[]" multiple  />
                </div>

                <div class="form-group ">
                    <label for="Phone"><strong>Please rate your overall satisfaction</strong></label>
                    <div class="rating">
                        <input type="radio" id="star5" name="rating" value="5">
                        <label for="star5" title="5 stars"></label>
                        <input type="radio" id="star4" name="rating" value="4">
                        <label for="star4" title="4 stars"></label>
                        <input type="radio" id="star3" name="rating" value="3">
                        <label for="star3" title="3 stars"></label>
                        <input type="radio" id="star2" name="rating" value="2">
                        <label for="star2" title="2 stars"></label>
                        <input type="radio" id="star1" name="rating" value="1">
                        <label for="star1" title="1 star"></label>
                    </div>
                </div>
                <input type="hidden" name="project_id" value="{{$project->id}}">
                <input type="hidden" name="customer_id" value="{{$customer->id}}">
                <button type="submit" class="btn btn-primary mt-2">Submit</button>
            </div>
        </form>
    </div>


<script src="{{url('/')}}/vendors/bundle.js"></script>
<script src="{{url('/')}}/assets/js/app.min.js"></script>
    <script>
        function showpw() {
            var x = document.getElementById("password");
            if (x.type === "password") {
                x.type = "text";
            } else {
                x.type = "password";
            }
        }
    </script>
    <style>
form .form-group {
    margin-bottom: 0px;
    width: 100%;
    text-align: left;
}
body.form-membership .form-wrapper h5 {
    text-align: center;
    margin-bottom: 2rem !important;
}
form  button{
    width: 100% !important; 
    text-align: center !important;
    align-items: center;
    display: flex !important;
    justify-content: center;
    margin-top: 15px !important;
}
form .rating{
    margin-bottom: 12px !important;

}
.outer-thankyou-section{
    background-color: white;
    width: 30%;
    display: flex;
    border-radius: 0.5rem;
    padding: 40px 30px;
    text-align: center;
    justify-content: space-between;
    margin: 0 auto;
}
.outer-thankyou-section .detail{
    font-size: 16px;
    margin-top: 20px;
}
.outer-thankyou-section .detail p{
    margin-bottom: 0px;

}

.outer-thankyou-section .logo{
    margin-bottom: 30px;
}
    </style>
</body>
</html> 