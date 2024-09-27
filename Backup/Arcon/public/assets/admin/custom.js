$(document).ready(function(){
    
        $(document).on('input','input[name="itemrate[]"]',function(){
            var at_id = $(this).attr("data-id");
            var rate = $('#item_amt'+at_id).val();
            var iamt=$('#rate'+at_id).val();
            var amount=$('.subtotal').html();
            if(rate == ''){
                rate = 0;
            }
            if(iamt == ''){
                iamt = 0;
            }
            var ttl=parseInt(amount)+parseInt(iamt)-parseInt(rate);
            $('#item_amt'+at_id).val(iamt);
            $('.subtotal').html(ttl);	
            $('.total').html(ttl);
            $('.hidden_total').val(ttl);
        });
        $(document).on('click','.search-btn',function(){
            if($('.src').hasClass('d-none')){
                $('.src').removeClass('d-none');
            }else{
                $('.src').addClass('d-none');
            }
           
        })
    $('.order-table').on('change', function (e) {
        var optionSelected = $("option:selected", this);
        var valueSelected = this.value;
        var index = $('.table').index();
        var rows = [];
        var thClass = valueSelected;
            // thClass = $('.table').hasClass('asc') ? 'desc' : 'asc';
            // alert(thClass);
            $('.table').removeClass('asc desc');
            $('.table').addClass(thClass);

            $('.table tbody tr').each(function (index, row) {
                rows.push($(row).detach());
            });
            rows.sort(function (a, b) {
                var aValue = $(a).find('td').eq(index).text(),
                    bValue = $(b).find('td').eq(index).text();

                return aValue > bValue
                    ? 1
                    : aValue < bValue
                    ? -1
                    : 0;
            });

            if ($('.table').hasClass('desc')) {
                rows.reverse();
            }

            $.each(rows, function (index, row) {
                $('.table tbody').append(row);
            });
    });
    $("#search-table").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $(".table tbody tr").filter(function() {
          $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
      });
      var maxRows = parseInt($('#maxRows').val());
      var totalRows = $('.table'+' tbody tr').length;			
      var trnum = 0 ;
         $('.table'+' tr:gt(0)').each(function(){			
             trnum++;									
             if (trnum > maxRows ){						
                 
                 $(this).hide();							
             }if (trnum <= maxRows ){$(this).show();}
         });
         
            if (totalRows > maxRows){						
                var pagenum = Math.ceil(totalRows/maxRows);	    
                for (var i = 1; i <= pagenum ;){			 
                $('.pagination').append('<li class="page-item" data-page="'+i+'">\
                <a class="page-link" href="#">'+ i++ +'</a>\
                                   </li>').show();
                }	
           } 											
           $('.pagination li:first-child').addClass('active'); 
            showig_rows_count(maxRows, 1, totalRows);
            $('.pagination li').on('click',function(e){		
                e.preventDefault();
                    var pageNum = $(this).attr('data-page');	
                    var trIndex = 0 ;							
                    $('.pagination li').removeClass('active');	
                    $(this).addClass('active');	
                showig_rows_count(maxRows, pageNum, totalRows);
                $('.table'+' tr:gt(0)').each(function(){	
                    trIndex++;	
                    if (trIndex > (maxRows*pageNum) || trIndex <= ((maxRows*pageNum)-maxRows)){
                        $(this).hide();		
                    }else {$(this).show();} 				
                }); 									
            });  
        
        
      getPagination('.table');  
});

$('#maxRows').trigger('change');
function getPagination (table1){

      $('#maxRows').on('change',function(){
          $('.pagination').html('');						
          var trnum = 0 ;									
          var maxRows = parseInt($(this).val());			
    
          var totalRows = $(table1+' tbody tr').length;		 
         $(table1+' tr:gt(0)').each(function(){			
             trnum++;									
             if (trnum > maxRows ){						
                 
                 $(this).hide();							
             }if (trnum <= maxRows ){$(this).show();}
         });											 
         if (totalRows > maxRows){						
             var pagenum = Math.ceil(totalRows/maxRows);	
                                                         
             for (var i = 1; i <= pagenum ;){			 
             $('.pagination').append('<li class="page-item" data-page="'+i+'">\
             <a class="page-link" href="#">'+ i++ +'</a>\
                                </li>').show();
             }											
 
     
        } 												
        $('.pagination li:first-child').addClass('active'); 
    
   showig_rows_count(maxRows, 1, totalRows);
    $('.pagination li').on('click',function(e){		
    e.preventDefault();
            var pageNum = $(this).attr('data-page');	
            var trIndex = 0 ;							
            $('.pagination li').removeClass('active');	
            $(this).addClass('active');					 
    
    
   showig_rows_count(maxRows, pageNum, totalRows);
  
             $(table1+' tr:gt(0)').each(function(){	
                 trIndex++;								
                
                 if (trIndex > (maxRows*pageNum) || trIndex <= ((maxRows*pageNum)-maxRows)){
                     $(this).hide();		
                 }else {$(this).show();} 				
             }); 										
                });										
    });
}	

//ROWS SHOWING FUNCTION
function showig_rows_count(maxRows, pageNum, totalRows) {
    var end_index = maxRows*pageNum;
    var start_index = ((maxRows*pageNum)- maxRows) + parseFloat(1);
    var string = 'Showing '+ start_index + ' to ' + end_index +' of ' + totalRows + ' entries';               
    $('.rows_count').html(string);
}

