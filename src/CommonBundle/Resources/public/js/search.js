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

        sort();
    });
    $("#game_sort_trier_par").on('change', function() {
        sort();
    });
    $("#game_sort_age_min").on('change', function() {
        sort();
    });
    $("#game_sort_age_max").on('change', function() {
        sort();
    });

    function sort(){
      var publishers = $("#game_sort_editeur").val();
      var ageMin=$("#game_sort_age_min").val();
      var ageMax=$("#game_sort_age_max").val();
      var orderby=$("#game_sort_trier_par").val();
      $.ajax({
          type: "POST",
          url: Routing.generate('display_games'),
          data: {
              ageMin: ageMin,
              ageMax: ageMax,
              orderby: orderby,
              publishers: publishers
          },
          dataType: 'json',
          success: function(data) {
            console.log(data);

            $("#games-panel").html("");
            var bloc="";
            if(!data.games.length){
              bloc+="<p>Aucun jeu ne correspond a votre recherche</p>";
            }
            $.each(jQuery.parseJSON(data.games), function (i) {
                  bloc+='<a class="game-sticker thumbnail" href="#"><img src="#" alt="#" class="game-image"><div class="game-name">'+this.name+'</div></a>';
            });

            $('#games-panel').prepend(bloc);

          },
      });
    }

});
