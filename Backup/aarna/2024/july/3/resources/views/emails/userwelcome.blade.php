<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Welcome to Aarna Insurance Services</title>
</head>
<body bgcolor="#0f3462" style="margin-top:20px;margin-bottom:20px">
  <!-- Main table -->
  <table border="0" align="center" cellspacing="0" cellpadding="0" bgcolor="white" width="650">
    <tr>
      <td>
        <!-- Child table -->
        <table border="0" cellspacing="0" cellpadding="0" style="color:#0f3462; font-family: sans-serif; width: 100%;">
          <tr style="background-color: #dddddd;">
            <td>
              <img src="{{ URL::asset('settings/'.$setting->logo) }}" height="100px" style="display:block; margin:auto;padding-bottom: 10px; padding-top: 10px; ">
            </td>
          </tr>
          <tr style="margin-top: 20px;">
            <td style="padding: 30px;">
              <h1 style="text-align:center; margin: 0px;padding-bottom: 25px; text-transform: uppercase;">Welcome to Aarna Insurance Services </h1>
              <p style="margin:0;padding-bottom: 5px;line-height: 2; font-size: 15px;">Dear <b>{{$username}}.</b></p>
              <p style="margin:0;padding-bottom: 5px;line-height: 2; font-size: 15px;">Your credentials for login is:</p>
              <p style="margin:0;padding-bottom: 5px;line-height: 2; font-size: 15px;">Username : <b>{{$email}}</b></p>
              <p style="margin:0;padding-bottom: 5px;line-height: 2; font-size: 15px;">Password : <b>{{$password}}</b></p>
              <p style="margin:0;padding-bottom: 5px;line-height: 2; font-size: 15px;"><sub>Change your password at first login!!!</sub>
              <p style="margin:0;padding-bottom: 5px;line-height: 2; font-size: 15px;"></p>
              <p style="margin:0;padding-bottom: 5px;line-height: 2; font-size: 15px;">If you have any query or concern please reach us at nirajjani@gmail.com</p>
              <p style="margin:0;padding-bottom: 5px;line-height: 2; font-size: 15px;"></p>
              <p style="margin:0;padding-bottom: 5px;line-height: 2; font-size: 15px;"></p>
              <p style="margin:0;padding-bottom: 5px;line-height: 2; font-size: 15px;"></p>
              <h3 style="margin: 0px; padding-bottom: 10px;">Best Regards,</h3>
              <h4 style="margin: 0px; padding-bottom: 0px;">Aarna Insurance Services Team.</h4>
              <p style="margin:0;padding-bottom: 5px;line-height: 2; font-size: 15px;"><sub>Copyright © {{date('Y')}} Aarna Finserve. All rights reserved.</sub></p>
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