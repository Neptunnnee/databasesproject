
$(function () {
  $('input[data-autocomplete]').each(function () {
    const $input = $(this);
    const field  = $input.data('autocomplete'); 

    $input.autocomplete({
      minLength: 1,  
      source: function (request, response) {
        $.ajax({
          url: 'autocomplete.php',       
          dataType: 'json',
          data: {
            term: request.term,         
            field: field                
          },
          success: function (data) {
            response(data);
          },
          error: function () {
            response([]);               
          }
        });
      }
    });
  });
});
