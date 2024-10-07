       </div>
        <footer class="footer">
          <div class="container-fluid">
            <div class="row">
              <div class="col-md-6 p-0 footer-copyright">
                <p class="mb-0">Copyright 2024 © AARNA INSURANCE SERVICES.</p>
              </div>
              <div class="col-md-6 p-0">
                <p class="heart mb-0">Developed by <a target="_blank" href="https://www.gocinico.com">Gocinico</a>
                  <svg class="footer-icon">
                    <use href="{{url('/')}}/assets/svg/icon-sprite.svg#heart"></use>
                  </svg>
                </p>
              </div>
            </div>
          </div>
        </footer>
      </div>
    </div>
    <div class="toast-container position-fixed top-0 end-0 p-3 toast-index toast-rtl">
        <div class="toast hide toast fade" id="liveToast1" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex justify-content-between  @if (session('success')) alert-success @else alert-danger @endif">
                <div class="toast-body">
                    @if (session('success'))
                        {{ session('success') }}
                    @elseif (session('error'))
                        {{ session('error') }}
                    @endif
                </div>
                <button class="btn-close btn-close-white me-2 m-auto" type="button" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>
    <!-- latest jquery-->
    <script src="{{url('/')}}/assets/js/jquery.min.js"></script>
    <!-- Bootstrap js-->
    <script src="{{url('/')}}/assets/js/bootstrap/bootstrap.bundle.min.js"></script>
    <!-- feather icon js-->
    <script src="{{url('/')}}/assets/js/icons/feather-icon/feather.min.js"></script>
    <script src="{{url('/')}}/assets/js/icons/feather-icon/feather-icon.js"></script>
    <!-- scrollbar js-->
    <script src="{{url('/')}}/assets/js/scrollbar/simplebar.js"></script>
    <script src="{{url('/')}}/assets/js/scrollbar/custom.js"></script>
    <script src="{{url('/')}}/assets/js/custom.js"></script>
    <!-- Sidebar jquery-->
    <script src="{{url('/')}}/assets/js/config.js"></script>
    <!-- Plugins JS start-->
    <script src="{{url('/')}}/assets/js/sidebar-menu.js"></script>
    <script src="{{url('/')}}/assets/js/sidebar-pin.js"></script>
    <script src="{{url('/')}}/assets/js/slick/slick.min.js"></script>
    <script src="{{url('/')}}/assets/js/slick/slick.js"></script>
    <script src="{{url('/')}}/assets/js/header-slick.js"></script>
    <script src="{{url('/')}}/assets/js/datatable/datatables/jquery.dataTables.min.js"></script>
    <script src="{{url('/')}}/assets/js/datatable/datatables/datatable.custom.js"></script>
    <script src="{{url('/')}}/assets/js/datatable/datatables/datatable.custom1.js"></script>
    <script src="{{url('/')}}/assets/js/animation/wow/wow.min.js"></script>
    <!-- Plugins JS Ends-->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Theme js-->
    <script src="{{url('/')}}/assets/js/script.js"></script>
    <script src="{{url('/')}}/assets/js/notify/custom-notify.js"></script>
    @yield('script')
    <!-- Plugin used-->
    <script>new WOW().init();</script>

    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            @if (session('success') || session('error'))
                var toastLiveExample = document.getElementById('liveToast1');
                var toast = new bootstrap.Toast(toastLiveExample);
                toast.show();
            @endif
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
    </script>
  </body>
</html>