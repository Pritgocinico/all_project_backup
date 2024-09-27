<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header d-block">
                <h5 class="modal-title text-center" id="staticBackdropLabel">Break Time</h5>
            </div>
            <div class="modal-body text-center">
                <?php $break = DB::table('break_logs')
                    ->where('user_id', Auth()->user() !== null ? Auth::user()->id : '')
                    ->orderBy('id', 'DESC')
                    ->first(); ?>
                @if (!blank($break))
                    <p>Break Start From:</p>
                    <p class="text-danger">
                        @if (!blank($break->break_start))
                            {{ \Carbon\Carbon::parse($break->break_start)->format('d/m/Y H:i:s') }}
                        @endif
                    </p>
                    <p class="">Today's Break Time: <span class="break-time-modal text-danger"></span></p>
                @endif
                <a href="{{ route('complete_break') }}" class="btn btn-primary mx-3">Complete Break</a>
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
</body>

</html>
