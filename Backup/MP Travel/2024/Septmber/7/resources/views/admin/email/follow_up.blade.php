<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body style="width: 660px;border-left:1px solid #eaeaea;border: 1px solid gray;padding: 17px 21px;">

    <table style="width:100% ">
        <tr style="">
            <td style="text-align:center;">
                <p
                    style="text-align: center;background-color: #f7df7c;margin: 0 auto;/* border-bottom: 1px solid gray; */padding-bottom: 6px;width: 32px;border-radius: 50px;padding: 17px;color: black;justify-content: center;font-size: 28px;">
                    {{ Utility::getInitials($createdUser->name) }}
                </p>
            </td>
        </tr>
    </table>

    <table style="width:100%; ">
        <tr>
            <td style="font-size: 20px; line-height: 20px; text-align: center; margin: 20px 0 20px 0px;  padding: 1px">
                <h1
                    style="font-size: 24px; line-height: 20px; text-align: center; margin: 20px 0 20px 0px; padding: 1px">
                    {{ $createdUser->name }} Assigned a Follow Up to you</h1>
            </td>
        </tr>
    </table>

    <table style="width:100%;">
        <tbody>
            <tr>
                <td bgcolor="#EAEAEA" align="center"
                    style="background: #4873cf;text-align:center;padding: 16px 15px;width: 100%;color: white;font-size: 18px;border-radius: 5px;">
                    <center>
                        <a href="{{ route('follow-up.index') }}" style="font-size:18px;margin:0">View Follow Up</a>
                    </center>
                </td>
            </tr>
        </tbody>
    </table>

    <table cellspacing="0" cellpadding="0" border="0" width="100%"
        style="border:1px solid gray;border-radius: 4px;margin-bottom: 20px;margin-top: 20px;padding: 15px;">
        <tbody>
            <tr>
                <td colspan="3" align="left" valign="top" style=" padding-bottom: 15px;"><a
                        href="{{ route('follow-up.index') }}"><strong
                            style="font-size: 12px;padding: 6px 10px;color: #6e6e6e;border-bottom:1px dotted #cccccc;border: 1px solid gray;border-radius: 4px;text-align: center;"><i
                                class="fa fa-check" style=" color: #6e6e6e; margin-right: 6px; " aria-hidden="true"></i>
                            Mark Complete</strong></a> </td>

                <td colspan="3" align="right" valign="top" style=" padding-bottom: 15px;"><a
                        href="{{ route('follow-up.index') }}">
                        <span
                            style="font-size: 12px;padding: 6px 10px;color: #6e6e6e; border-bottom:1px dotted #cccccc;border: 1px solid gray;border-radius: 4px;text-align: center;"><i
                                style=" color: #6e6e6e; margin-right: 6px; " class="fa-solid fa-thumbs-up"></i>Like This
                            Follow UP</span></a>
                </td>
            </tr>
        </tbody>
        <tbody>
            <tr>
                <td colspan="3" align="left" valign="top"
                    style="font-size:12px;padding-top:12px;padding-bottom:12px; "><strong
                        style="font-size:16px;color: #4b74cc;"><i
                            style="margin-right: 6px;color: #6e6e6e;font-size: 20px;"
                            class="fa-regular fa-circle-check"></i>{{ $followUpEvent->event_name }}</strong> </td>
            </tr>
        </tbody>
        <tbody>
            <tr>
                <td align="left" style="padding:5px 0px;font-size:15px;color: #6e6e6e;width: 130px;">Assigned to
                </td>
                <td align="left" style="padding:5px 0px;font-size:15px;"> <span
                        style="padding: 5px 3px;background: #4b74cc;border-radius: 17px;align-items: center;color: white;font-size: 11px;">{{ Utility::getInitials($userDetail->name) }}</span>
                </td>
                <td align="left" style="padding:5px 0px ; font-size:15px;"> <span
                        style="padding:5px 0px; color: #6e6e6e;">{{$userDetail->name}}</span>
                </td>
            </tr>
            <tr>
                <td align="left" style="padding: 10px 0px;font-size:15px;color: #6e6e6e;width: 130px;"> Due date</td>
                <td align="left" style="padding:5px 0px;font-size: 16px;"> <span
                        style="padding:5px 0px;font-size: 16px;"><i style=" color: #6e6e6e; margin-right: 6px; "
                            class="fa-solid fa-calendar-days"></i>
                    </span> </td>
                <td align="left" style="padding:5px 0px ; font-size:15px;"> <span
                        style="padding:5px 0px; color: #6e6e6e;">{{Utility::convertFJYFormat($followUpEvent->event_end)}}</span>
                </td>
            </tr>
            <tr>
                <td align="left" style="padding:5px 0px;font-size:15px;color: #6e6e6e;width: 130px;"> Collaborators
                </td>
                <td align="left" style="padding:5px 0px ; font-size:15px;"> <span
                        style="padding: 5px 5px;background: #f5dc84;border-radius: 17px;align-items: center;color: black;font-size: 11px; margin-right: 3px;">{{ Utility::getInitials($createdUser->name) }}</span>
                    <span
                        style="padding: 5px 3px;background: #4b74cc;border-radius: 17px;align-items: center;color: white;font-size: 11px;">{{ Utility::getInitials($userDetail->name) }}</span>
                </td>
                <td align="left" style="padding:5px 0px ; font-size:15px;"> <span
                        style="padding:5px 0px; color: #6e6e6e;">{{$createdUser->name}} and {{$userDetail->name}}</span>
                </td>
            </tr>
        </tbody>
    </table>
    <table style="width:100%;">
        <tbody>
            <tr>
                <td bgcolor="#EAEAEA" align="center"
                    style="background: white;text-align:center;padding: 16px 15px;width: 100%;color: black;font-size: 18px;border-radius: 5px;border: 1px solid #6e6e6e;margin-top: 20px;">
                    <center>
                        <a href="{{ route('follow-up.index') }}" style="font-size:18px;margin:0;color:#6e6e6e;">View In Asana</a>
                    </center>
                </td>
            </tr>
        </tbody>
    </table>

</body>

</html>
