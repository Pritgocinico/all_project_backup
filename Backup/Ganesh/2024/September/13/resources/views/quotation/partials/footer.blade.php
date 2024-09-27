            {{-- <footer class="content-footer">
                <div>© 2024 Ganesh Alluminium - <a href="http://gocinico.com/" target="_blank">Gocinico</a></div>
            </footer> --}}
            </div>
            </div>
            </div>
            <!-- jQuery -->
            <!--<script src="http://thecodeplayer.com/uploads/js/jquery-1.9.1.min.js" type="text/javascript"></script>-->
            <!-- jQuery easing plugin -->
            <!--<script src="http://thecodeplayer.com/uploads/js/jquery.easing.min.js" type="text/javascript"></script>-->

            <script src="{{ url('/') }}/vendors/bundle.js"></script>
            <script src="{{ url('/') }}/assets/js/bootstrap.bundle.min.js"></script>
            {{-- <script src="{{url('/')}}/vendors/prism/prism.js"></script> --}}
            <script src="{{ url('/') }}/vendors/datepicker/daterangepicker.js"></script>
            {{-- <script src="{{url('/')}}/vendors/dataTable/datatables.min.js"></script> --}}
            {{-- <script src="{{url('/')}}/assets/js/examples/pages/dashboard.js"></script> --}}
            <script src="{{ url('/') }}/assets/js/app.min.js"></script>
            <script src="{{ url('/') }}/assets/js/feather.min.js"></script>
            <script src="{{ url('/') }}/assets/js/datatable/jquery.dataTables.min.js"></script>
            <script src="{{ url('/') }}/assets/js/datatable/dataTables.buttons.min.js"></script>
            <script src="{{ url('/') }}/assets/js/datatable/buttons.flash.min.js"></script>
            <script src="{{ url('/') }}/assets/js/datatable/jszip.min.js"></script>
            <script src="{{ url('/') }}/assets/js/datatable/js_buttons.colVis.min.js"></script>
            <script src="{{ url('/') }}/assets/js/datatable/js_buttons.html5.min.js"></script>
            <script src="{{ url('/') }}/assets/js/datatable/js_buttons.print.min.js"></script>
            <script src="{{ url('/') }}/assets/js/datatable/js_dataTables.bootstrap.min.js"></script>
            <script src="{{ url('/') }}/assets/js/datatable/buttons.bootstrap.min.js"></script>
            <script src="{{ url('/') }}/assets/js/datatable/pdfmake_vfs_fonts.js"></script>
            <script src="{{ url('/') }}/assets/js/datatable/pdfmake.min.js"></script>
            <script src="{{ url('/') }}/assets/libs/sweetalert/sweetalert.min.js"></script>
            <script src="{{ url('/') }}/vendors/jquery.repeater.min.js"></script>
            <script src="{{ url('/') }}/assets/js/select2.min.js"></script>

            @yield('script')
            <script>
                feather.replace();
                $(document).ready(function() {
                    var table = $('#example2').DataTable({
                        dom: 'Bfrtip',
                        buttons: [
                            'copy', 'excelFlash', 'excel', 'print', {
                                text: 'Reload',
                                action: function(e, dt, node, config) {
                                    dt.ajax.reload();
                                }
                            }
                        ],
                    });
                    
                    
                    $("#example1 tfoot th").each(function() {
                        var title = $(this).text();
                        $(this).html('<input type="text" placeholder="Search ' + title + '" />');
                    });
                    var table = $('#example1').DataTable({
                        dom: 'Bfrtip',
                        buttons: [
                            'copy', 'excelFlash', 'excel', 'print', {
                                text: 'Reload',
                                action: function(e, dt, node, config) {
                                    dt.ajax.reload();
                                }
                            }
                        ],
                        initComplete: function(settings, json) {
                            var footer = $("#example1 tfoot tr");
                            $("#example1 thead").append(footer);
                        }
                    });
                    $("#example1 thead").on("keyup", "input", function() {
                        table.column($(this).parent().index())
                            .search(this.value)
                            .draw();
                    });
                });
            </script>
            <script>
                $('input[name="startdaterangepicker"]').daterangepicker({
                    singleDatePicker: true,
                    showDropdowns: true,
                    locale: {
                        format: 'DD-MM-YYYY'
                    },
                });
                $('input[name="enddaterangepicker"]').daterangepicker({
                    singleDatePicker: true,
                    showDropdowns: true,
                    locale: {
                        format: 'DD-MM-YYYY'
                    },
                });
                $('input[name="projectconfirmdate"]').daterangepicker({
                    singleDatePicker: true,
                    showDropdowns: true,
                    locale: {
                        format: 'DD-MM-YYYY'
                    },
                });
                $('input[name="measurementdate"]').daterangepicker({
                    singleDatePicker: true,
                    showDropdowns: true,
                    locale: {
                        format: 'DD/MM/YYYY'
                    },
                });
                $('input[name="quotationdate"]').daterangepicker({
                    singleDatePicker: true,
                    showDropdowns: true,
                    locale: {
                        format: 'DD-MM-YYYY'
                    },
                });
                $('input[name="deliverydate"]').daterangepicker({
                    singleDatePicker: true,
                    showDropdowns: true,
                    locale: {
                        format: 'DD-MM-YYYY'
                    },
                });
                $('input[name="fittingdate"]').daterangepicker({
                    singleDatePicker: true,
                    showDropdowns: true,
                    locale: {
                        format: 'DD-MM-YYYY'
                    },
                });
                $('input[name="completedate"]').daterangepicker({
                    singleDatePicker: true,
                    showDropdowns: true,
                    locale: {
                        format: 'DD-MM-YYYY'
                    },
                });

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
            </script>
    </body>
</html>
