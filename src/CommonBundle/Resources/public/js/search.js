$(document).ready(function() {
    // Range slider for sorting by age
    $( "#age-slider" ).slider({
  range: true,
  min: 0,
  max: 107,
  values: [ 0,107],
  slide: function( event, ui ) {
    $( "#age-label" ).html( "De "+ ui.values[ 0 ] + " à " + ui.values[ 1 ]+ " ans" );
  }
});
$( "#age-label" ).html( "De " + $( "#age-slider" ).slider( "values", 0 ) +
  " à " + $( "#age-slider" ).slider( "values", 1 ) +" ans");
    // Autocompletion sur le champs de recherche par nom
    $('#game_search_searchGame').autocomplete({
        source: function(request, response) {
            $.ajax({
                type: "POST",
                url: Routing.generate('display_games'),
                data: {
                    gameName: request.term
                },
                success: function(data) {
                    response($.map(jQuery.parseJSON(data['games']), function(i, v) {
                        return {
                            label: i.name,
                            value: i.name
                        };
                    }));
                }

            });
        },


    });

// A chaque sélection d'un critère dans le formulaire on appelle la fonction de tri
    $("#game_sort_editeur").on('change', function() {
        sort(true);
    });
    $("#game_sort_trier_par").on('change', function() {
        sort(true);
    });
    $("#game_sort_age_min").on('change', function() {
        sort(true);
    });
    $("#game_sort_country").on('change', function() {
        sort(true);
    });
    $("#game_sort_authors").on('change', function() {
        sort(true);
    });
    $("#game_sort_players").on('change', function() {
        sort(true);
    });
    $("#game_sort_congestion").on('change', function() {
        sort(true);
    });
    $("#game_sort_duree").on('change', function() {
        sort(true);
    });
    $("#game_sort_categorie").on('change', function() {
        sort(true);
    });
    $("#game_sort_themes").on('change', function() {
        sort(true);
    });
    $( "#age-slider" ).on( "slidechange", function( event, ui ) {
      sort(true);
    } );
    $("#game_sort_reinitialiser_les_filtres").on('click', function() {
        sort(false);
    });



    function sort(sort) {
        if (sort) {
            var publishers = $("#game_sort_editeur :checked").map(function() {
                return this.value;
            }).get();
            var ageMin = $( "#age-slider" ).slider( "values", 0 );
            var ageMax = $( "#age-slider" ).slider( "values", 1 );
            var orderby = $("#game_sort_trier_par").val();
            var duration = $("#game_sort_duree").val();
            var types = $("#game_sort_categorie :checked").map(function() {
                return this.value;
            }).get();
            var themes = $("#game_sort_themes :checked").map(function() {
                return this.value;
            }).get();
            var authors = $("#game_sort_authors :checked").map(function() {
                return this.value;
            }).get();
            var players = $("#game_sort_players :checked").map(function() {
                return this.value;
            }).get();
            var country = $("#game_sort_country :checked").map(function() {
                return this.value;
            }).get();
            var congestion = $("#game_sort_congestion :checked").map(function() {
                return this.value;
            }).get();

        } else {
            var publishers = "";
            var ageMin = 0;
            var ageMax = 107;
            var authors = "";
            var players = "";
            var country = "";
            var congestion = "";
            var types = "";
            var orderby = "publication_asc";
            var duration = "";
            var themes = "";
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
                themes: themes,
                authors: authors,
                players: players,
                country: country,
                congestion: congestion
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
                $("#games-panel").html(data);

            },
        });
    }

});
