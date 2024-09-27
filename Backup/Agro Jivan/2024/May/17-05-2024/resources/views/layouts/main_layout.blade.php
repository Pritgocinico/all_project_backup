<!DOCTYPE html>
<html lang="en">

<head>
    @include('layouts.head')
</head>

<body>
    @include('layouts.sidebar')
    @yield('section')
</body>
@include('layouts.footer')
<input type="hidden" id="pusher_role_id" value="{{Auth()->user() !== null ?Auth()->user()->id : ""}}">
<script src="https://js.pusher.com/7.0/pusher.min.js"></script>
<script>
    var role_id = $('#pusher_role_id').val();
    var pusher = new Pusher('{{ env('PUSHER_APP_KEY') }}', {
        cluster: '{{ env('PUSHER_APP_CLUSTER', 'mt1') }}',
        encrypted: true
    });

    var channel = pusher.subscribe('notifications');
        channel.bind('new-notification', function(data) {
        if (data.user_id == role_id) {
            if (data.type == 'message') {
                toastr.info(data.title, data.text);          
            } else if(data.type == "cancel") {
                toastr.error(data.title, data.text);
            } else {
                toastr.success(data.title, data.text);

            }
        }        
    });
</script>

<script>
$(document).on('click','.read_notification',function(){
        var id = $(this).data('id');
        $.ajax({
            url : "{{ route('notification.mark_as_read') }}",
            type : 'POST',
            data: {"_token": "{{ csrf_token() }}",'id':id},
            dataType:'json',
            success : function(data) {
            }
        });
    });
    
$(document).on('click', '.read_all_notification', function() {
    $.ajax({
        url: "{{ route('notification.mark_all_as_read') }}",
        type: 'POST',
        data: {"_token": "{{ csrf_token() }}"},
        dataType: 'json',
        success: function(data) {
            // Display a toast notification on success
            $('.notification_clear').empty();
            var clerDiv = $('<div class="py-2 text-left border-top"> <a href="#" class="btn btn-color-gray-600">No New Notification</a></div>');
            $('.notification_clear').append(clerDiv);
            $('.notification_count, .read_all_notification').remove();
            toastr.success('All Notifications Are Cleared');
        },
        error: function(xhr, status, error) {
            // Display a toast notification on error if needed
            toastr.error('Failed to mark notifications as read');
        }
    });
});

</script>
</html>
