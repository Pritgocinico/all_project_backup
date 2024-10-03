@extends('admin.partials.header', ['active' => 'user'])
@section('content')
    <div
        class="flex-fill overflow-y-lg-auto scrollbar  main-table bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">
        <main class="container-fluid p-0">
            <div class="px-6 px-lg-7 pt-8 border-bottom">
                <div class="d-flex align-items-center mb-5">
                    <h1>Disease</h1>
                    <div class="hstack gap-2 ms-auto">
                        @if (collect($accesses)->where('menu_id', '22')->first()->status == 2)
                            <a href="#" class="btn btn-sm btn-dark" data-bs-toggle="modal"
                                data-bs-target="#kt_modal_export_users" data-bs-toggle="modal">
                                Export</a>
                            <a href="#" class="btn btn-sm btn-dark" data-bs-target="#addDiseaseModal"
                                data-bs-toggle="modal"><i class="fa-solid fa-plus"></i>
                                Create Disease</a>
                        @endif
                    </div>
                </div>
            </div>
            <div class="px-6 px-lg-7 pt-8">
                <div id="disease_table_ajax" class=" custom-scrollbar">

                </div>
            </div>
        </main>
        <div class="modal fade" id="addDiseaseModal" tabindex="-1" aria-labelledby="depositLiquidityModalLabel" data-bs-backdrop="static"
            style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content overflow-hidden">
                    <div class="modal-header pb-0 border-0">
                        <h1 class="modal-title h4">Add Disease</h1>
                        <button type="button" class="btn-close close-disease" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form class="vstack" method="POST" id="addForm">
                        @csrf
                        <div class="modal-body undefined">
                            <div class="vstack gap-1">
                                <div class="row align-items-center g-3 mt-6">
                                    <div class="col-md-2"><label class="form-label mb-0">Name <span class="text_danger_require">*</span></label></div>
                                    <div class="col-md-10 col-xl-10">
                                        <input type="text" name="name" class="form-control" id="disease_name"
                                            placeholder="Enter Disease Name">
                                            <span style="color: red;" id="disease_error"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-neutral close-disease" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-dark" id="submit_disease_add"
                                onclick="submitForm()">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal fade" id="editDiseaseModal" tabindex="-1" aria-labelledby="depositLiquidityModalLabel" data-bs-backdrop="static"
            style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content overflow-hidden">
                    <div class="modal-header pb-0 border-0">
                        <h1 class="modal-title h4">Edit Disease</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form class="vstack" method="POST" id="updateForm">
                        @csrf
                        <input type="hidden" name="disease_id" id="disease_id">
                        <div class="modal-body undefined">
                            <div class="vstack gap-1">
                                <div class="row align-items-center g-3 mt-6">
                                    <div class="col-md-2"><label class="form-label mb-0">Name <span class="text_danger_require">*</span></label></div>
                                    <div class="col-md-10 col-xl-10">
                                        <input type="text" name="name" class="form-control" id="edit_name"
                                            placeholder="Enter Disease Name">
                                            <span style="color: red;" id="edit_disease_error"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-neutral" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-dark" id="update_button_disease"
                                onclick="updateDisease()">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal fade" id="kt_modal_export_users" tabindex="-1" aria-labelledby="depositLiquidityModalLabel"
            style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content overflow-hidden">
                    <div class="modal-header pb-0 border-0">
                        <h1 class="modal-title h4" id="depositLiquidityModalLabel">Export Disease</h1>
                        <button type="reset" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="" class="form" action="#">
                        <div class="modal-body undefined">
                            <div class="vstack gap-1">
                                <div class="row align-items-center g-3 mt-6">
                                    <div class="col-md-2"><label class="form-label mb-0">Export Format:</label></div>
                                    <div class="col-md-10 col-xl-10">
                                        <select name="format" class="form-control" id="export_format">
                                            <option value="">Select Format</option>
                                            <option value="excel">Excel</option>
                                            <option value="pdf">PDF</option>
                                            <option value="csv">CSV</option>
                                        </select>
                                        <span id="format_error" class="text-danger"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="reset" class="btn btn-neutral" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-dark" id="submitBtn"
                                onclick="exportCSV()">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function(e) {
            diseaseAjax(1);
        })

        function diseaseAjax(page) {
            var search = $('#search_data').val();
            $.ajax({
                'method': 'get',
                'url': "{{ route('disease-ajax') }}",
                data: {
                    search: search,
                    page: page,
                },
                success: function(res) {
                    $('#disease_table_ajax').html('');
                    $('#disease_table_ajax').html(res);
                    $("#disease_table").DataTable({
                        initComplete: function() {
                            var searchInput = $('#disease_table_filter input');
                            searchInput.attr('id', 'disease_search');
                            searchInput.attr('placeholder', 'Search Disease');
                        },
                        "pageLength": 10,
                        drawCallback: function() {
                            $('#disease_table_paginate .paginate_button').addClass(
                                'datatable_paginate');
                        },
                        lengthChange: false,
                        "order": [
                            [0, 'asc']
                        ],
                        "columnDefs": [{
                            "orderable": false,
                            "targets": 0
                        }]
                    });
                    $('[data-bs-toggle="tooltip"]').tooltip()
                },
            });
        }
        $(document).on('click', '.pagination a', function(event) {
            event.preventDefault();
            $('li').removeClass('active');
            $(this).parent('li').addClass('active');
            var page = $(this).attr('href').split('page=')[1];
            diseaseAjax(page);
        });

        function exportCSV() {
            var exportFile = "{{ route('disease-export') }}";
            var format = $('#export_format').val();
            $('#format_error').html('');
            if (format.trim() == "") {
                $('#format_error').html('Please Select Export Format.');
                return false;
            }
            var allowValues = ['csv', 'excel', 'pdf'];
            if (!allowValues.includes(format)) {
                $('#format_error').html('Please Select Valid Export Format.');
                return false;
            }
            var search = $('#disease_search').val();
            window.open(exportFile + '?format=' + format + '&search=' + search, '_blank');
        }


        function editDisease(id) {
            $.ajax({
                url: "{{ route('disease.edit', ['disease' => 'empid']) }}".replace('empid', id),
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    $('#edit_name').val(data.data.name);
                    $('#disease_id').val(id);
                    $('#editDiseaseModal').modal('show');
                },
                error: function(error) {
                    toastr.error(error.responseJSON.message)
                }
            });
        }

        function deleteDisease(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "Are you sure the delete this Disease?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('disease.destroy', '') }}" + "/" + id,
                        type: 'DELETE',
                        dataType: 'json',
                        data: {
                            _token: "{{ csrf_token() }}",
                        },
                        success: function(data) {
                            toastr.success(data.message);
                            diseaseAjax(1);
                        },
                        error: function(error) {
                            toastr.error(error.responseJSON.message)
                        }
                    });
                }
            });
        }

        document.getElementById('addForm').addEventListener('submit', function(event) {
            event.preventDefault();

            submitForm();
        });

        function submitForm() {
            var disease = $('#disease_name').val();
            
            if (disease == '') {
                $('#disease_error').html('Please Enter Disease.')
                    return false;
            }else{
                $('#disease_error').html('')
            }

            $('#submit_disease_add').html('<i class="fa fa-spinner fa-spin"></i>');

            $.ajax({
                url: "{{ route('disease.store') }}",
                type: 'POST',
                data: $('#addForm').serialize(),
                success: function(data) {
                    $('#addForm').trigger("reset");
                    $('#addDiseaseModal').modal('hide');
                    toastr.success(data.message);
                    diseaseAjax(1);
                    $('#submit_disease_add').html('Submit');
                },
                error: function(error) {
                    toastr.error(error.responseJSON.message);
                    $('#submit_disease_add').html('Submit');
                }
            });
        }

        $('#disease_name').on('keyup', function() {
            if ($(this).val() != '') {
                $('#disease_error').html('');
            }
        });

        document.getElementById('addForm').addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                submitForm();
            }
        });

        document.getElementById('updateForm').addEventListener('submit', function(event) {
            event.preventDefault();

            updateDisease();
        });

        $('.close-disease').on('click', function(){
            $('#addForm').trigger("reset");
        });

        function updateDisease() {
            var diseaseName = $('#edit_name').val();
            var id = $('#disease_id').val();
            
            if (diseaseName == '') {
                $('#edit_disease_error').html('Please Enter Disease.')
                editDisease(id);
                    return false;
            }else{
                $('#edit_disease_error').html('')
            }

            $('#update_button_disease').html('<i class="fa fa-spinner fa-spin"></i>');

            $.ajax({
                url: "{{ route('disease.update', ['disease' => 'empid']) }}".replace('empid', id),
                type: 'PUT',
                data: $('#updateForm').serialize(),
                success: function(data) {
                    $('#editDiseaseModal').modal('hide');
                    toastr.success(data.message);
                    diseaseAjax(1);
                    $('#update_button_disease').html('Submit');
                },
                error: function(error) {
                    toastr.error(error.responseJSON.message);
                    $('#update_button_disease').html('Submit');
                }
            });
        }

        $('#edit_name').on('keyup', function() {
            if ($(this).val() != '') {
                $('#edit_disease_error').html('');
            }
        });

        document.getElementById('updateForm').addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                updateDisease();
            }
        });
    </script>
@endsection
