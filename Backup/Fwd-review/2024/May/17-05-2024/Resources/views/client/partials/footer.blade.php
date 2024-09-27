</div>
</div>
</div>
</div>
<script src="{{url('/')}}/assets/JS/datatable/jquery.dataTables.min.js"></script>
<script src="{{url('/')}}/assets/JS/datatable/dataTables.buttons.min.js"></script>
<script src="{{url('/')}}/assets/JS/datatable/buttons.flash.min.js"></script>
<script src="{{url('/')}}/assets/JS/datatable/jszip.min.js"></script>
<script src="{{url('/')}}/assets/JS/datatable/js_buttons.colVis.min.js"></script>
<script src="{{url('/')}}/assets/JS/datatable/js_buttons.html5.min.js"></script>
<script src="{{url('/')}}/assets/JS/datatable/js_buttons.print.min.js"></script>
<script src="{{url('/')}}/assets/JS/datatable/js_dataTables.bootstrap.min.js"></script>
<script src="{{url('/')}}/assets/JS/datatable/buttons.bootstrap.min.js"></script>
<script src="{{url('/')}}/assets/JS/datatable/pdfmake_vfs_fonts.js"></script>
<script src="{{url('/')}}/assets/JS/datatable/pdfmake.min.js"></script>
@yield('script')
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
                window.location.reload();
                toastr.success('All Notifications Are Cleared');
            },
            error: function(xhr, status, error) {
                // Display a toast notification on error if needed
                toastr.error('Failed to mark notifications as read');
            }
        });
    });
    
    $(document).ready(function() {
        var url = document.location.toString();
        if (url.match('#')) {
            $('.nav-pills a[href="#' + url.split('#')[1] + '"]').tab('show');
        }
        // Change hash for page-reload
        $('.nav-pills a').on('shown.bs.tab', function (e) {
            window.location.hash = e.target.hash;
        })
        $('#example').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'copy', 'excelFlash', 'excel', 'pdf', 'print', {
                    text: 'Reload',
                    action: function(e, dt, node, config) {
                        dt.ajax.reload();
                    }
                }
            ]
        });
        $('#dataTable').DataTable();
        $('#example3').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'copy', 'excelFlash', 'excel', 'pdf', 'print', {
                    text: 'Reload',
                    action: function(e, dt, node, config) {
                        dt.ajax.reload();
                    }
                }
            ]
        });
        $('#example4').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'copy', 'excelFlash', 'excel', 'pdf', 'print', {
                    text: 'Reload',
                    action: function(e, dt, node, config) {
                        dt.ajax.reload();
                    }
                }
            ]
        });
        $('#example5').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'copy', 'excelFlash', 'excel', 'pdf', 'print', {
                    text: 'Reload',
                    action: function(e, dt, node, config) {
                        dt.ajax.reload();
                    }
                }
            ]
        });
        $('#example6').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'copy', 'excelFlash', 'excel', 'pdf', 'print', {
                    text: 'Reload',
                    action: function(e, dt, node, config) {
                        dt.ajax.reload();
                    }
                }
            ]
        });
    });
</script>
<script>
    function openNav() {
        document.getElementById("notifications").style.width = "300px";
    }
    function CloseNav() {
        document.getElementById("notifications").style.width = "0px";
    }
</script>
<script>
    $(document).ready(function() {
        // Function to toggle the sidebar and content classes
        function toggleSidebar() {
            $("#sidebar").toggleClass("side_normal side_small");
            $("#content").toggleClass("content_normal content_big");
        }
        // Toggle sidebar on button click
        $("#menu-btn").click(function() {
            toggleSidebar();
        });
    });
</script>
<script>
    /* ======= RESPONSIVNESS ======= */
    let menuBtn = document.getElementById("menu-btn");
    let menu = document.getElementById("menu");
    menuBtn.addEventListener("click", () => {
        menu.classList.toggle("open");
        menu.classList.toggle("visible");
    });
</script>
<script>
    window.addEventListener('DOMContentLoaded', function() {
        // Get references to the sidebar and content elements
        const sidebar = document.getElementById('sidebar'); // Replace with your actual sidebar ID
        const content = document.getElementById('content'); // Replace with your actual content ID
        // Function to set the sidebar height to match the content height
        function setSidebarHeight() {
            const contentHeight = content.offsetHeight;
            sidebar.style.height = contentHeight + 'px';
        }
        // Initial call to set the sidebar height
        setSidebarHeight();
        // Listen for window resize events to update sidebar height if needed
        window.addEventListener('resize', setSidebarHeight);
    });
</script>
<script type="text/javascript">
    $(document).ready(function() {
        //jquery for toggle sub menus
        $('.sub-btn').click(function() {
            $(this).next('.sub-menu').slideToggle();
            $(this).find('.dropdown').toggleClass('rotate');
        });
        //jquery for expand and collapse the sidebar
        $('.menu-btn').click(function() {
            $('.side-bar').addClass('active');
            $('.menu-btn').css("visibility", "hidden");
        });
        $('.close-btn').click(function() {
            $('.side-bar').removeClass('active');
            $('.menu-btn').css("visibility", "visible");
        });
    });
</script>
<script src="{{url('/')}}/assets/JS/datepicker_semantic.min.js"></script>
<script>
    $('#example1').calendar();
    $('#example2').calendar();
</script>
<script src="{{url('/')}}/assets/JS/chart.js"></script>
<script src="{{url('/')}}/assets/JS/canvasjs.min.js"></script>
<script src="{{url('/')}}/assets/JS/bootstrap.bundle.min.js"></script>
<script src="{{url('/')}}/assets/sweetalert/sweetalert.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script>
    $('.select2').select2();
    $(document).on('change','.client_business',function(){
        var business = $(this).val();
        $.ajax({
            url : "{{route('change.business')}}",
            type : 'POST',
            data : {"_token": "{{ csrf_token() }}",'business':business},
            success : function(data) {
                location.reload();
            }
        });
    });
</script>
</body>
</html>
