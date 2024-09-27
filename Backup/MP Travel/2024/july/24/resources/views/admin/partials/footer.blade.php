<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header d-block">
                <h5 class="modal-title text-center" id="staticBackdropLabel">Break Time</h5>
            </div>
            <div class="modal-body text-center">
                
                
                    <p>Break Start From:</p>
                    <p class="text-danger">
                        
                    </p>
                    <p class="">Today's Break Time: <span class="break-time-modal text-danger"></span></p>
                <a href="{{ route('complete_break') }}" class="btn btn-primary mx-3">Complete Break</a>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>
<footer></footer>
    <script src="{{ asset('plugin/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('plugin/poper/popper.min.js') }}"></script>
    <script src="{{ asset('plugin/bootstrap/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('plugin/sweet-alert/sweetalert.min.js') }}"></script>
    <script src="{{ asset('plugin/toastr/toastr.min.js') }}"></script>
    <script src="{{ asset('plugin/select2/select2.min.js') }}"></script>
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
        $(document).on('click', '.take_break', function () {
            var breakTime = "{{ route('employee-break-time-start') }}";
            $.ajax({
                type: 'GET',
                url: breakTime,
                success: function (data) {
                    $('#staticBackdrop').modal('show');
                },
                error: function (data) {
                }
            });
        });
    </script>
    </body>

    </html>
