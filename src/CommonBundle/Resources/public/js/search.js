$(document).ready(function() {

  // Autocompletion sur le champs de recherche par nom
  $('#game_search_searchGame').autocomplete({
    source: function(request, response) {
            $.ajax({
              type: "POST",
              url: Routing.generate('display_games'),
              data: {
                  gameName: request.term
              },
              success: function(data){ response($.map
                        (jQuery.parseJSON(data['games']), function(i,v) {
                            return {
                            label: i.name ,
                            value: i.name
                };}));}

            });
        },


    });

    $("#game_sort_editeur").on('change', function() {
      console.log("data");

        sort(true);
    });
    $("#game_sort_trier_par").on('change', function() {
        sort(true);
    });
    $("#game_sort_age_min").on('change', function() {
        sort(true);
    });
    $("#game_sort_age_max").on('change', function() {
        sort(true);
    });
    $("#game_sort_duree").on('change', function() {
        sort(true);
    });
    $("#game_sort_categorie").on('change', function() {
        sort(true);
    });
    $("#game_sort_theme").on('change', function() {
        sort(true);
    });

    $("#game_sort_reinitialiser_les_filtres").on('click', function() {
        sort(false);
    });

    $("#game_search_Annuler").on('click', function() {
        sort(false);
    });

    function sort(sort){
      if(sort){
        var publishers = $("#game_sort_editeur").val();
        var ageMin=$("#game_sort_age_min").val();
        var ageMax=$("#game_sort_age_max").val();
        var orderby=$("#game_sort_trier_par").val();
        var duration=$("#game_sort_duree").val();
        var types=$("#game_sort_categorie").val();
        var themes=$("#game_sort_theme").val();

      }else{
        var publishers = "";
        var ageMin="";
        var ageMax="";
        var types="";
        var orderby="publication_asc";
        var duration="";
        var themes="";

      }


      $.ajax({
          type: "POST",
          url: Routing.generate('display_games'),
          data: {
              ageMin: ageMin,
              ageMax: ageMax,
              orderby: orderby,
              publishers: publishers,
              duration: duration,
              types: types,
              themes:themes
          },
          dataType: 'json',
          beforeSend: function(){
            $("#games-panel").html("");
             $("#loader").show();
           },
           complete: function(){
             $("#loader").hide();
           },
          success: function(data) {
            console.log(data);

            $("#games-panel").html(data);

          },
      });
    }

});
