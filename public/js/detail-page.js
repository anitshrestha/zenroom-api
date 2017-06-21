
$(document).ready(function() {
    //Initialize Components
    ZenRoom.setUpRoomDetailsOnLoad();
    ZenRoom.initializeDate();
    ZenRoom.initializeEditable();

    //Initiatializing Component Configuration
    $.fn.editable.defaults.ajaxOptions = { type: 'PUT' };
    $.notify.defaults({globalPosition: 'top center'});
    $('.date-input').datepicker({ autoclose: true, format: 'yyyy-mm-dd'});
    $('#detail-table').tableHeadFixer({'top': false, 'left' : 1});
    
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    //Adding Validation
    $.validator.addMethod('greaterThan', ZenRoom.validateDate, 'Must be greater than \'from date\'');       
    $('form.store-details').validate(ZenRoom.validationRule());
    
    //Table Navigation
    $('.page-navigation .btn-arrow.right').on('click', ZenRoom.moveRight);
    $('.page-navigation .btn-arrow.left').on('click', ZenRoom.moveLeft);

    //Ajax Operations
    $('body').on('click', '.date-holder', ZenRoom.getDateListing);
    $('form.store-details').on('submit', ZenRoom.updateRoomDetails);
});
