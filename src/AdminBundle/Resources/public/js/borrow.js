$(document).ready(function() {
  $('#newBorrow').click(function(e){
    console.log($("#borrow_copy").val());
    //return false;
  })
    $('#borrow_game').change( function () {
        var game = $("#borrow_game").val();

        $.ajax({
            type: "POST",
            url: Routing.generate('admin_borrow_new'),
            data: {
              game : game
            },
            dataType: 'json',
            success: function(data) {
                $("#exemplaire").show();
                // On vide la liste des exemplaires
                $("#borrow_copy").html("");
                console.log(data);
                $.each(data,function(key,value){
                  $("#borrow_copy").append("<option value='"+this.id+"'>"+this.reference+"</option>");

                })

            },
        });
        //$("#commonbundle_borrow_copies").append("<option value='10' selected='selected'>Choisissez un exemplaire</option>");

        //var sel = $('<select>').appendTo($(this));
        //$(arr).each(function() {
        //    sel.append($("<option>").attr('value',this.val).text(this.text));
        //});


    });

});
    //        window.actionEvents = {
    //            'click .action': function (e, value, row, index) {
    //                id_demande = row.id;
    //                url = $(this).attr('data-path');
    //
    //                console.log(url);
    //                $.ajax({
    //                    type: "GET",
    //                    data: {'id_demande': id_demande},
    //                    url: url,
    //                    success: function (data) {
    //                        if (typeof(data) !== 'undefined' && data == 1) {
    //                            alert('La demande a été réactivée ! ');
    //                            $('#table').bootstrapTable('refresh');
    //                        } else if (typeof(data) !== 'undefined' && data == 0) {
    //                            alert('La demande a été désactivée ! ');
    //                            $('#table').bootstrapTable('refresh');
    //                        }
    //                        else alert(typeof(data))
    //                    }
    //                });
    //
    //            }
    //        };
