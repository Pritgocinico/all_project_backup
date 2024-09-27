<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Client Activation Email</title>
</head>
<body bgcolor="#0f3462" style="margin-top:20px;margin-bottom:20px">
  <!-- Main table -->
  <table border="0" align="center" cellspacing="0" cellpadding="0" bgcolor="white" width="650">
    <tr>
      <td>
        <!-- Child table -->
        <table border="0" cellspacing="0" cellpadding="0" style="color:#0f3462; font-family: sans-serif; width: 100%;">
          <tr style="background-color: #00A0AA;">
            <td>
              <img src="{{url('/')}}/frontend/assets/images/main-logo-b.png" height="100px" style="display:block; margin:auto;padding-bottom: 0px; padding-top: 0px; ">
            </td>
          </tr>
          <tr style="margin-top: 20px;">
            <td style="padding: 30px;">
              <h1 style="text-align:center; margin: 0px;padding-bottom: 25px; text-transform: uppercase;">Medisourcerx</h1>
              <p style="margin:0;padding-bottom: 5px;line-height: 2; font-size: 15px;">Hi  <b>{{$manager_name}}.</b></p>
              <p style="margin:0;padding-bottom: 5px;line-height: 2; font-size: 15px;">I wanted to inform you that your client is now active. Kindly connect with them to ensure they have no issues accessing our services. Please also encourage them to place an order in the near future.</p>
              <p style="margin:0;padding-bottom: 5px;line-height: 2; font-size: 15px;">Client Details :-</p>
              <p style="margin:0;padding-bottom: 5px;line-height: 2; font-size: 15px;">Name: <b>{{$name}}</b></p>
              <p style="margin:0;padding-bottom: 5px;line-height: 2; font-size: 15px;">Organization Name: <b>{{$organization}}</b></p>
              <p style="margin:0;padding-bottom: 5px;line-height: 2; font-size: 15px;">Number: <b>{{$number}}</b></p>
              <p style="margin:0;padding-bottom: 5px;line-height: 2; font-size: 15px;">Email: <b>{{$emailText}}</b> </p>
              <p style="margin:0;padding-bottom: 5px;line-height: 2; font-size: 15px;"></p>
              <p style="margin:0;padding-bottom: 5px;line-height: 2; font-size: 15px;">Have a good day!</p>
              <p style="margin:0;padding-bottom: 5px;line-height: 2; font-size: 15px;"></p>
              <h3 style="margin: 0px; padding-bottom: 10px;">Best Regards,</h3>
              <h4 style="margin: 0px; padding-bottom: 0px;">MedisourceRX Team.</h4>
            </td>
          </tr>
        </table>
        <!-- /Child table -->
      </td>
    </tr>
  </table>
  <!-- / Main table -->
</body>

</html>