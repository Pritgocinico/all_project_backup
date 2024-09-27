<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Company Name</title>
  <style>
    .ii a[href] {
    color: #fff !important;
}
  </style>
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
              <img src="https://replete-software.com/projects/medisource/frontend/assets/images/main-logo-b.png" height="100px" style="display:block; margin:auto;padding-bottom: 0px; padding-top: 0px; ">
            </td>
          </tr>
          <tr style="margin-top: 20px;">
            <td style="padding: 30px;">
              <h1 style="text-align:center; margin: 0px;padding-bottom: 25px; text-transform: uppercase;">Welcome to Medisourcerx</h1>
              <p style="margin:0;padding-bottom: 5px;line-height: 2; font-size: 15px;">Dear <b>{{$name}}.</b></p>
              <p style="margin:0;padding-bottom: 5px;line-height: 2; font-size: 15px;">We're delighted to inform you that your account with <b>MedisourceRX</b> is now fully activated!</p>
              <p style="margin:0;padding-bottom: 5px;line-height: 2; font-size: 15px;">You can now access all the features and benefits of being a member of our community. Whether it's exploring our services, connecting with other users, or accessing exclusive content, your account is ready for you to dive in.</p>
              <p style="margin:0;padding-bottom: 0px;line-height: 2; font-size: 15px;">Here are your account details:</p>
              <div style="margin:12px 0; list-style-type: none; background-color: #00A0AA; color: #fff; padding: 22px; gap: 12px;">
                <p style='width: 100%;'><b>Username/ID:</b><span style='color:white'>{{$emailText}}</span></p>
                <p style="width: 100%;"><b>Password:</b>  {{$password}}</span></p>
              </div>
                <a href="{{url('/')}}" target="_blank" style="background-color:#00A0AA;color:white;padding: 15px 44px;outline: none;display: block;margin: auto;border-radius: 0px;font-weight: bold;margin-top: 25px;margin-bottom: 25px;border: none;text-transform:uppercase;width: 50px;text-decoration: none;">Login</a>
              <p style="margin:0;padding-bottom: 5px;line-height: 2; font-size: 15px;">Please ensure to keep your login details safe and secure. If you have any concerns about your account security or need assistance, don't hesitate to reach out to our support team at <b>[support email or contact number]</b>.</p>
              <p style="margin:0;padding-bottom: 5px;line-height: 2; font-size: 15px;">Thank you once again for choosing <b>MedisourceRX</b>. We're thrilled to have you onboard, and we can't wait to see what amazing things we can achieve together!</p>
              <p style="margin:0;padding-bottom: 5px;line-height: 2; font-size: 15px;"></p>
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