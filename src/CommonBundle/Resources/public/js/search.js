$(document).ready(function() {

  // Autocompletion sur le champs de recherche par nom
  $('#game_search_searchGame').autocomplete({
    source: function(request, response) {
            $.ajax({
              type: "POST",
              url: Routing.generate('display_games'),
              data: {
                  input: request.term
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
        // On récupère la route
        var gamesURL = Routing.generate('display_games');
        // A chaque saisie d'un caractère, on recupère la valeur
        var publishers = $(this).val();
        // Au bout de deux caractères saisis, on lance l'autompletion
            $.ajax({
                type: "POST",
                url: gamesURL,
                data: {
                    publishers: publishers
                },
                dataType: 'json',
                success: function(response) {
                    var games = jQuery.parseJSON(response['games']);
                    var names = $.map(games, function(i, v) {
                        return i.name;
                    });
                    $('#game_search_searchGame').autocomplete({
                        source: names,
                    });


                },
            });

    });
});
