$(document).ready(function(){
    $('.select-custom-drop').select2();
    $('.datatable-apply').DataTable(
        { 
            

            ordering: true,
            "language": {
                "lengthMenu": "Affichage _MENU_ produits par page",
                "zeroRecords": "Rien trouvé - désolé",
                "info": "Affichage de page _PAGE_ de _PAGES_",
                "infoEmpty": "Aucun produits disponible",
                "infoFiltered": "(filtered from _MAX_ total records)",
                "sSearch": "Recherche",
                "paginate": {
                  "previous": "Précédente",
                  "next":"Suivante"
                }
                
            }
        }
    );



    $(".increment-ordre").click(function(){
        var idOrdre = $(this).attr('product-order-id');
        var url = '/admin/productOrdre/up/'+idOrdre;

        

        $.ajax({
            url: url,
            context: document.body
          }).done(function(data) {
            console.log(data);
            location.reload();
          });
        
    })

    $(".decrement-ordre").click(function(){
        var idOrdre = $(this).attr('product-order-id');
        var url = '/admin/productOrdre/down/'+idOrdre;

        

        $.ajax({
            url: url,
            context: document.body
          }).done(function(data) {
            console.log(data);
            location.reload();
          });
        
    })
    
});