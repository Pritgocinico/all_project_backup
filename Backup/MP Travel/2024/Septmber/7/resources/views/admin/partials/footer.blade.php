<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header d-block">
                <h5 class="modal-title text-center" id="staticBackdropLabel">Break Time Calculation</h5>
            </div>
            <div class="modal-body text-center">
                <?php $break = DB::table('break_logs')
                    ->where('user_id', Auth()->user() !== null ? Auth::user()->id : '')
                    ->orderBy('id', 'DESC')
                    ->first(); ?>
                @if (!blank($break))
                    <div class="row g-3 mt-6 text-start">
                        <div class="col-md-6">
                            <p>Break Start From:</p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-danger">
                                @if (!blank($break->break_start))
                                    {{ \Carbon\Carbon::parse($break->break_start)->format('d/m/Y H:i:s') }}
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="row g-3 mt-6 text-start">
                        <div class="col-md-6">
                            <p>Today's Break Time: </p>
                        </div>
                        <div class="col-md-6">
                            <span class="break-time-modal text-danger"></span>
                        </div>
                    </div>
                @endif
                <div class="row align-items-center g-3 mt-6">
                        <a href="{{ route('complete_break') }}" class="btn btn-primary">Complete Break</a>
                </div>
            </div>
            <div class="modal-footer">
            </div>
        </div>
        <input type="hidden" id="pusher_role_id" value="{{ Auth()->user() !== null ? Auth()->user()->id : '' }}">
    </div>
</div>
<footer></footer>
<script src="{{ asset('plugin/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('plugin/poper/popper.min.js') }}"></script>
<script src="{{ asset('plugin/bootstrap/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('plugin/sweet-alert/sweetalert.min.js') }}"></script>
<script src="{{ asset('plugin/toastr/toastr.min.js') }}"></script>
<script src="{{ asset('plugin/select2/select2.min.js') }}"></script>
<script src="{{ asset('plugin/moment/moment.min.js') }}"></script>
<script src="{{ asset('plugin/fullcalendar/fullcalendar.bundle.js') }}"></script>
<script src="{{ asset('plugin/fullcalendar/fullcalendar.min.js') }}"></script>
<script src="{{ asset('plugin/daterangepicker/daterangepicker.min.js') }}"></script>
<script src="{{ asset('plugin/datatable/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('plugin/datatable/datatables/datatable.custom1.js') }}"></script>
<script src="{{ asset('plugin/datatable/datatables/datatable.custom.js') }}"></script>
<script src="https://js.pusher.com/7.0/pusher.min.js"></script>
<script>
    $(document).ready(function(e){
        $('[data-bs-toggle="tooltip"]').tooltip()
    });
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
            } else if (data.type == "cancel") {
                toastr.error(data.title, data.text);
            } else {
                toastr.success(data.title, data.text);

            }
        }
    });
</script>
<?php $break = DB::table('break_logs')->where('user_id',Auth()->user() !== null ?Auth::user()->id:"")->orderBy('id','DESC')->first(); 
    if(!empty($break)){
        if(empty($break->break_over)){
        ?>
<script>
    window.setInterval(function() {
        $.ajax({
            type: 'GET',
            url: "{{ route('break_time') }}",
            success: function(data) {
                console.log(data);
                
                $('.break-time-modal').html(data);
                $('.break-time').html(data);
            },
            error: function(data) {}
        });
    }, 60);
    $(document).ready(function() {
        $('#staticBackdrop').modal('show');
    });
</script>
<?php
        }
        }
        ?>
<script>
    function toggleDropdown() {
        var dropdown = document.getElementById("myDropdown");
        dropdown.style.display = (dropdown.style.display === 'block') ? 'none' : 'block';
    }

    // Close the dropdown if the user clicks outside of it
    window.onclick = function(event) {
        if (!event.target.matches('.dropbtn')) {
            var dropdowns = document.getElementsByClassName("dropdown-content");
            for (var i = 0; i < dropdowns.length; i++) {
                var openDropdown = dropdowns[i];
                if (openDropdown.style.display === 'block') {
                    openDropdown.style.display = 'none';
                }
            }
        }
    }
    $(function() {
        $('[data-bs-toggle="tooltip"]').tooltip()
    })
</script>
@yield('script')
@if (session('success'))
    <script>
        toastr.success("{{ session('success') }}")
    </script>
@endif
@if (session('error'))
    <script>
        toastr.error("{{ session('error') }}")
    </script>
@endif
<script>
    $(document).on('click', '.take_break', function() {
        var breakTime = "{{ route('employee-break-time-start') }}";
        $.ajax({
            type: 'GET',
            url: breakTime,
            success: function(data) {
                $('#staticBackdrop').modal('show');
            },
            error: function(data) {}
        });
    });
</script>
<script>
    $(document).on('click', '.read_notification', function() {
        var id = $(this).data('id');
        $.ajax({
            url: "{{ route('notification.mark_as_read') }}",
            type: 'POST',
            data: {
                "_token": "{{ csrf_token() }}",
                'id': id
            },
            dataType: 'json',
            success: function(data) {}
        });
    });

    $(document).on('click', '.read_all_notification', function() {
        $.ajax({
            url: "{{ route('notification.mark_all_as_read') }}",
            type: 'POST',
            data: {
                "_token": "{{ csrf_token() }}"
            },
            dataType: 'json',
            success: function(data) {
                $('.notification_clear').empty();
                var clerDiv = $(
                    '<div class="py-2 text-left border-top"> <a href="#" class="btn btn-color-gray-600">No New Notification</a></div>'
                );
                $('.notification_clear').append(clerDiv);
                $('.notification_count, .read_all_notification').remove();
                toastr.success('All Notifications Are Cleared');
            },
            error: function(xhr, status, error) {
                toastr.error('Failed to mark notifications as read');
            }
        });
    });
</script>
<script>
    function updateClock() {
        const now = new Date();

        const day = now.getDate().toString().padStart(2, '0');
        const month = (now.getMonth() + 1).toString().padStart(2, '0'); // Months are zero-based
        const year = now.getFullYear();
        const formattedDate = `${day}-${month}-${year}`;

        var hours = now.getHours().toString().padStart(2, '0');
        const minutes = now.getMinutes().toString().padStart(2, '0');
        const seconds = now.getSeconds().toString().padStart(2, '0');
        const ampm = hours >= 12 ? 'PM' : 'AM';
        hours = hours % 12;
        hours = hours ? hours : 12;
        const formattedTime = `${formattedDate} | ${hours.toString().padStart(2, '0')}:${minutes}:${seconds} ${ampm}`;

        document.getElementById('clock').innerText = formattedTime;
    }
    updateClock();
    setInterval(updateClock, 1000);
    
</script>
</body>

</html>
