$(function () {
   // click of the create article button
   $('#modalButton').click(function () {

       $('#modal').modal('show')
           .find('#modalContent')
           .load($(this).attr('value'));
   });


});

function changeStatus(key,index) {
    // console.log(key +" "+index);
    $.post('index.php?r=articles/changestatus', { id: key, index: index }),
    function(data) {
        console.log(data);
    }
}