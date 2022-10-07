//--------------------------------------autocompletion categorie sous categorie--------------------------------------


var $categorie = $('#annonce_idCategorie');
// Quand la categorie est sélectionné...

$categorie.change(function() {
// ... récupère le formulaire correspondant.
var $form = $(this).closest('form');
// Simulez les données du formulaire, mais n'incluez que la valeur categorie sélectionnée.
var data = {};
data[$categorie.attr('name')] = $categorie.val();
// Soumettez les données via AJAX au chemin d'action du formulaire
$.ajax({
url : $form.attr('action'),
type: $form.attr('method'),
data : data,
complete: function(html) {
// Remplacer le champ de position actuelle ...
$('#annonce_idSousCategorie').replaceWith(
  // ... avec celui renvoyé par la réponse AJAX.
  $(html.responseText).find('#annonce_idSousCategorie')
);
// Le champ Position affiche maintenant les positions appropriées.
}
});
});



//--------------------------------------autocompletion api adresse--------------------------------------



$("#annonce_adresse").autocomplete({
source: function (request, response) {
  $.ajax({
      url: "https://api-adresse.data.gouv.fr/search/?postcode="+$("input[name='annonce[cp]']").val()+"&limit=20",
      data: { q: request.term },
      dataType: "json",
      success: function (data) {
          var postcodes = [];
          var cities = [];
          response($.map(data.features, function (item) {
              // Ici on est obligé d'ajouter les CP dans un array pour ne pas avoir plusieurs fois le même
              if ($.inArray(item.properties.postcode, cities && postcodes) == -1) {
              postcodes.push(item.properties.postcode);
              cities.push(item.properties.postcode);
                  return { label: item.properties.name + " - " + item.properties.postcode + " - " + item.properties.city ,
                  name : item.properties.name,
                  postcode: item.properties.postcode,
                  city: item.properties.city,
                  citycode: item.properties.citycode,
                  value : item.properties.name 
                  };
              }
          }));
      }
  });
},
// On remplit la ville et le code postal et le code INSEE
select: function(event, ui) {
  $('#annonce_ville').val(ui.item.city);
  $('#annonce_cp').val(ui.item.postcode);
  $('#annonce_codeInsee').val(ui.item.citycode);
}
});
$("#annonce_cp").autocomplete({
source: function (request, response) {
  $.ajax({
      url: "https://api-adresse.data.gouv.fr/search/?postcode="+$("input[name='annonce[cp]']").val()+"&limit=20",
      data: { q: request.term },
      dataType: "json",
      success: function (data) {
          var postcodes = [];
          response($.map(data.features, function (item) {
              // Ici on est obligé d'ajouter les CP dans un array pour ne pas avoir plusieurs fois le même
              if ($.inArray(item.properties.city, postcodes) == -1) {
                  postcodes.push(item.properties.city);
                  return { 
                      label: item.properties.postcode + " - " + item.properties.city,
                      city: item.properties.city,
                      citycode: item.properties.citycode,
                      value: item.properties.postcode
                      
                  };
              }
          }));
      }
  });
},
// On remplit aussi la ville et le code insee
select: function(event, ui) {
  $('#annonce_ville').val(ui.item.city);
  $('#annonce_codeInsee').val(ui.item.citycode);
}
});


$("#annonce_ville").autocomplete({
source: function (request, response) {
  $.ajax({
      url: "https://api-adresse.data.gouv.fr/search/?city="+$("input[name='annonce[ville]']").val(),
      data: { q: request.term },
      dataType: "json",
      success: function (data) {
          var cities = [];
          response($.map(data.features, function (item) {
              // Ici on est obligé d'ajouter les villes dans un array pour ne pas avoir plusieurs fois la même
              if ($.inArray(item.properties.postcode, cities) == -1) {
                  cities.push(item.properties.postcode);
                  return {  label: item.properties.city+ " - " + item.properties.postcode, 
                            postcode: item.properties.postcode,
                            citycode: item.properties.citycode,
                            value: item.properties.city
                  };
              }
          }));
      }
  });
},
// On remplit aussi le CP et le code INSEE
select: function(event, ui) {
  $('#annonce_cp').val(ui.item.postcode);
  $('#annonce_codeInsee').val(ui.item.citycode);
}
});
$("#annonce_codeInsee").autocomplete({
source: function (request, response) {
  $.ajax({
      url : "https://api-adresse.data.gouv.fr/search/?citycode="+$("input[name='annonce[codeInsee]']").val()+"&limit=20",
      data: { q: request.term },
      dataType: "json",
      success: function (data) {
          var citycodes = [];
          response($.map(data.features, function (item) {
              
              if ($.inArray(item.properties.citycode, citycodes) == -1) {
                  citycodes.push(item.properties.citycode);
                  return { 
                      label: item.properties.postcode + " - " + item.properties.city + " - " + item.properties.citycode,
                      city: item.properties.city,
                      postcode: item.properties.postcode,
                      value: item.properties.citycode
                  };
              }
          }));
      }
  });
},
// On remplit aussi la ville et le code postal
select: function(event, ui) {
  $('#annonce_ville').val(ui.item.city);
  $('#annonce_cp').val(ui.item.postcode);
}
});
