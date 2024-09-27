// if < then 768
// mobile sorting overlay jquery start
const mediaQuerymobile = window.matchMedia("(max-width: 768.98px)");

if (mediaQuerymobile.matches) {
  $(".sort-drop").on("show.bs.dropdown", function () {
    $(".overlay").show();
  });
  $(".sort-drop").on("hide.bs.dropdown", function () {
    $(".overlay").hide();
  });
}
//  mobile sorting overlay jquery end

// mobile filter jquery start

// $(".filter-btn").click(function () {
//   $(".sidebar").addClass("open");
//   $("body").addClass("overflow-hidden vh-100");
// });
// $(".filter-close-btn").click(function () {
//   $(".sidebar").removeClass("open");
//   $("body").removeClass("overflow-hidden vh-100");
// });
// mobile filter jquery end

// sidebar sticky
// const mediaQuerySM = window.matchMedia("(min-width: 768px)");
// if (mediaQuerySM.matches) {
//   var sidebar = new StickySidebar(".sidebar", {
//     topSpacing: 80,
//     bottomSpacing: 20,
//     containerSelector: ".main-content",
//     innerWrapperSelector: ".sidebar__inner",
//   });
// }

// increment decrement in product details page

$(function() {
  $('[data-decrease]').click(decrease);
  $('[data-increase]').click(increase);
  $('[data-value]').change(valueChange);
});

function decrease() {
  var value = $(this).parent().find('[data-value]').val();
  if (value > 1) {
      value--;
      $(this).parent().find('[data-value]').val(value);
  }
}

function increase() {
  var value = $(this).parent().find('[data-value]').val();
  if (value < 100) {
      value++;
      $(this).parent().find('[data-value]').val(value);
  }
}

function valueChange() {
  var value = $(this).val();
  if (value == undefined || isNaN(value) == true || value <= 0) {
      $(this).val(1);
  } else if (value >= 101) {
      $(this).val(100);
  }
}


// slider product-details
// $('.slider-for').slick({
//   slidesToShow: 1,
//   slidesToScroll: 1,
//   arrows: false,
//   fade: true,
//   asNavFor: '.slider-nav'
// });
// $('.slider-nav').slick({
//   slidesToShow: 4,
//   slidesToScroll: 1,
//   asNavFor: '.slider-for',
//   dots: false,
//   focusOnSelect: true,
//   responsive: [
//       {
//           breakpoint: 1199,
//           settings: {
//               slidesToShow: 3,
//               asNavFor: '.slider-for',
//           }
//       }, {
//           breakpoint: 990,
//           settings: {
//               slidesToShow: 4,
//               asNavFor: '.slider-for',
//           }
//       },
//       {
//           breakpoint: 584,
//           settings: {
//               slidesToShow: 4,
//               asNavFor: '.slider-for',
//           }
//       }, {
//           breakpoint: 425,
//           settings: {
//               slidesToShow: 3,
//               asNavFor: '.slider-for',
//           }
//       },
//   ]
// });





