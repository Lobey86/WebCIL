    function writeupdate () {
        var id_array = Sortable.sequence('etapes');
        new Ajax.Request('/cakeflow/circuits/reorder/'+ id_array.join(','), {onSuccess: function() {return true;} });
     }
