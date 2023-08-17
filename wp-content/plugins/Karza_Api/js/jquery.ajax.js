jQuery(document).ready( function($){

$( "#product_groups" ).change(function() {

  // alert( $(this).find(":selected").val() );
   var pid=$(this).find(":selected").val();
//  alert(pid);
  $.ajax({
    url: ajax_object.ajaxurl, // this is the object instantiated in wp_localize_script function
    type: 'POST',
    dataType: "json",
    data:{ 
      action: 'myaction', // this is the function in your functions.php that will be triggered
      pid: pid,
    },
    success: function( data ){
      //Do something with the result from server
      //console.log( data );
        $('#sub_category').html(data.html);
    }
  });
});
 
$('#Login').onclick(function(){
  alert('sdgf');
  $.ajax({
    type: 'POST',
      dataType:"json",
    url: 'https://api.karza.in/gst/uat/v2/gstdetailed',
    headers:{         
        'Content-Type: application/json','x-karza-key:C3cqA88yEUdrCVfi'      
    },
    success: function (data, status, xhr) {
      console.log('data: ', data);
    }
  });  
})
         
          

 
});