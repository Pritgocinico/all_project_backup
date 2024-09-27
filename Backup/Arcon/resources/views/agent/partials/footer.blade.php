</div>
</main>
 <!-- content-footer -->
 <footer class="content-footer">
    <!-- <div>© 2022 Houmanity </div> -->
    <div class="footer-mobile-agent">
        <div class="container">
            <div class="row">
                <ul>
                  <li>
                    <a href="{{ route('agent.dashboard') }}">
                      <img src="{{url('/')}}/assets/admin/images/dashboard.png" class="img-fluid">
                      Dashboard
                    </a>
                  </li>
                  <li>
                    <a href="{{ route('agent.users') }}">
                      <img src="{{url('/')}}/assets/admin/images/clients.png"  class="img-fluid">  
                      Dealers
                    </a>
                  </li>
                  <li>
                    <a href="{{ route('agent.orders') }}">
                      <img src="{{url('/')}}/assets/admin/images/orders.png"  class="img-fluid">  
                      Orders
                    </a>
                  </li>
                </ul>
            </div>
        </div>
    </div>
</footer>  
<!-- ./ content-footer -->
</div>

<!-- ./ layout-wrapper -->
<?php $url=config('app.url') ?>
{{-- <script src="{{ $url }}/assets/admin/libs/bundle.js"></script> --}}
<script src="{{ $url }}/assets/admin/libs/charts/justgage/raphael-2.1.4.min.js"></script>
<script src="{{ $url }}/assets/admin/libs/charts/justgage/justgage.js"></script>
<div class="colors"> <!-- To use theme colors with Javascript -->
    <div class="bg-primary"></div>
    <div class="bg-secondary"></div>
    <div class="bg-info"></div>
    <div class="bg-success"></div>
    <div class="bg-danger"></div>
    <div class="bg-warning"></div>
</div>
<!-- <script src="{{ $url }}/assets/admin/js/ckeditor.js"></script> -->
<script src="{{ $url }}/assets/admin/libs/sweetalert/sweetalert.min.js"></script>
<script src="{{ $url }}/assets/admin/libs/datepicker/daterangepicker.js"></script>
<script src="{{ $url }}/assets/admin/libs/range-slider/js/ion.rangeSlider.min.js"></script>
{{-- <script src="{{ $url }}/assets/admin/js/examples/datepicker.js"></script> --}}
<script src="{{ $url }}/assets/admin/libs/charts/apex/apexcharts.min.js"></script>
<script src="{{ $url }}/assets/admin/libs/slick/slick.min.js"></script>
<script src="{{ $url }}/assets/admin/libs/select2/js/select2.min.js"></script>
{{-- <script src="{{ url('/') }}/assets/admin/js/bootstrap.bundle.min.js"></script> --}}
<script src="//netdna.bootstrapcdn.com/bootstrap/3.0.1/js/bootstrap.min.js" type="text/javascript"></script>
<script src="{{ $url }}/assets/admin/libs/dataTable/jquery.dataTables.min.js"></script>
<script src="{{ $url }}/assets/admin/js/popper.min.js" ></script>
<script src="{{ $url }}/assets/admin/libs/dataTable/dataTables.bootstrap5.min.js"></script>
<script src="{{ $url }}/assets/admin/libs/summernote/summernote-lite.js"></script>
<script src="{{ url('/') }}/assets/admin/js/dashboard.js"></script>
<script src="{{ url('/') }}/assets/admin/js/fullcalendar.min.js"></script>
{{-- <script src="{{ url('/') }}/assets/admin/js/ckeditor.js"></script> --}}
<script>
var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
  return new bootstrap.Popover(popoverTriggerEl)
})

  var acc = document.getElementsByClassName("accordion");
  var i;
  
  for (i = 0; i < acc.length; i++) {
    acc[i].addEventListener("click", function() {
      this.classList.toggle("active-sec");
      var panel = this.nextElementSibling;
      if (panel.style.display === "block") {
        panel.style.display = "none";
      } else {
        panel.style.display = "block";
      }
    });
  }
  </script>
<script>
var elem = document.getElementById("fullscreen");
function openFullscreen() {
if (elem.requestFullscreen) {
  elem.requestFullscreen();
} else if (elem.mozRequestFullScreen) { /* Firefox */
  elem.mozRequestFullScreen();
} else if (elem.webkitRequestFullscreen) { /* Chrome, Safari & Opera */
  elem.webkitRequestFullscreen();
} else if (elem.msRequestFullscreen) { /* IE/Edge */
  elem.msRequestFullscreen();
}
}
</script>
<script>
  
$(".select2").select2();
$(".select2-district").select2();
$(".select2-staff").select2();
$(".select2-status").select2();
function openNav() {
document.getElementById("notifications").style.width = "300px";
}

function closeNav() {
document.getElementById("notifications").style.width = "0";
}
</script>
{{-- script called every day at 11 pm --}}
<script>
  $(document).ready(function() {
  // setTimeToTrigger();
      // Javascript to enable link to tab
      var url = document.location.toString();
      if (url.match('#')) {
          $('.nav-pills a[href="#' + url.split('#')[1] + '"]').tab('show');
      }
      // Change hash for page-reload
      $('.nav-pills a').on('shown.bs.tab', function (e) {
          window.location.hash = e.target.hash;
      })
      var triggerEl = document.querySelector('#myTab a[href="#profile"]')
      bootstrap.Tab.getInstance(triggerEl).show() // Select tab by name

      var triggerFirstTabEl = document.querySelector('#myTab li:first-child a')
      bootstrap.Tab.getInstance(triggerFirstTabEl).show() // Select first tab
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

        $('input[name="lead_datetime"]').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            timePicker: true,
            locale: {
                format: 'D/M/Y hh::mm:ss A'
            }
        });
        $('#datefilterto').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            timePicker: false,
            locale: {
                format: 'DD/MM/YYYY'
            }
        });
        $('#datefilterfrom').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            timePicker: false,
            locale: {
                format: 'DD/MM/YYYY'
            }
        });
        $('#To').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            timePicker: false,
            locale: {
                format: 'YYYY/MM/DD'
            }
        });
        $('#From').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            timePicker: false,
            locale: {
                format: 'YYYY/MM/DD'
            }
        });
        $('#startDate').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            timePicker: false,
            locale: {
                format: 'YYYY/MM/DD'
            }
        });
        $('#endDate').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            timePicker: false,
            locale: {
                format: 'YYYY/MM/DD'
            }
        });
        $('input[name="delivery_date"]').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            timePicker: false,
            locale: {
                format: 'DD/MM/YYYY'
            }
        });
</script>
<script>
$(document).ready(function () {
   // Javascript to enable link to tab
   var url = document.location.toString();
      if (url.match('#')) {
          $('.nav-pills a[href="#' + url.split('#')[1] + '"]').tab('show');
      } 
      // Change hash for page-reload
      $('.nav-pills a').on('shown.bs.tab', function (e) {
          window.location.hash = e.target.hash;
      })
    $('#example').DataTable();
    $('#summernote').summernote({
      height: 300,
      fontNames: ['Arial', 'Arial Black', 'Comic Sans MS', 'Courier New', 'Helvetica', 'Impact', 'Tahoma', 'Times New Roman', 'Decimal Regular']
    });
    $('#summernote1').summernote({
      height: 300,
      fontNames: ['Arial', 'Arial Black', 'Comic Sans MS', 'Courier New', 'Helvetica', 'Impact', 'Tahoma', 'Times New Roman', 'Decimal Regular']
    });
});
</script>
<script>
/* Loop through all dropdown buttons to toggle between hiding and showing its dropdown content - This allows the user to have multiple dropdowns without any conflict */
var dropdown = document.getElementsByClassName("sub-menu");
var i;

for (i = 0; i < dropdown.length; i++) {
  dropdown[i].addEventListener("click", function() {
    this.classList.toggle("active");
    var dropdownContent = this.nextElementSibling;
    if (dropdownContent.style.display === "block") {
      dropdownContent.style.display = "none";
    } else {
      dropdownContent.style.display = "block";
    }
  });
}
</script>
@yield('script')
</body>
</html>
