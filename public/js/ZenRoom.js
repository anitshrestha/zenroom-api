var ZenRoom = {
    validationRule: function(){
        return {
            rules: {
                room_type_id: 'required',
                from_date: {
                    required: true,
                    date: true
                },
                to_date: {
                    required: true,
                    date: true,
                    greaterThan: '.from_date'
                },
                available_rooms: {
                    required: true,
                    min: 1,
                    max: 99999999,
                    number: true
                },
                price: {
                    required: true,
                    min: 1,
                    max: 99999999,
                    number: true
                }
            },
            messages: {
                room_type_id: 'Room type is required',
                from_date: 'From date is required',
                to_date: 'To date is required',
                available_rooms: 'Room availability is required',
                price: 'Price is required'
            }
        };
    },
    initializeDate: function(){
        $('.date-selector').datepicker({
            autoclose: true,
            format: 'yyyy-mm-dd',
            startView: 'months',
            minViewMode: 'months',
        }).on('changeDate', function(selected) {
            Hotel.updateUi(
                HotelContainer.xhrLink + '?date=' + selected.format('yyyy-mm-dd')
            );
        });
    },
    initializeEditable : function(){
        $('.editable-block').editable({
            validate: function (value) {
                if (value) {
                    if (isNaN(value)) {
                        return 'Not a valid number. Please use number from 0-9';
                    } else if(value < 0) {
                        return 'Must be greator than or equal to 0';
                    } else if(value > 99999999) {
                        return 'Can\'t process the input';
                    }
                } else {
                    return 'Value can\'t be empty';
                }
            }
        });
    },
    moveRight: function(event) {
        event.preventDefault();

        var $table = $('.table-responsive');
        if ($table.scrollLeft() < $table.find('.table').width()) {
            $table.animate({scrollLeft: $table.scrollLeft() + 200}, 300);
        }
    },
    moveLeft: function(event) {
        event.preventDefault();

        var $table = $('.table-responsive');
        if ($table.scrollLeft() > 0) {
            $table.animate({scrollLeft: $table.scrollLeft() - 200}, 300);
        }
    },
    validateDate: function(value, element, params) {
        if (!/Invalid|NaN/.test(new Date(value))) {
            return new Date(value) > new Date($(params).val());
        }

        return isNaN(value) && isNaN($(params).val())
            || (Number(value) > Number($(params).val()));
    },
    updateUi: function(url){
        $.ajax({
            url: url,
            dataType: 'html'
        }).success(function(response) {
            //Update content
            $('#main-content').html(response);
            
            //Fix table
            $('#detail-table').tableHeadFixer({'top': false, 'left' : 1});

            //Initialize ui after ajax call
            Hotel.initializeEditable();
            Hotel.initializeDate();
        }).fail(function(response) {
            $.notify('Something went wrong', 'error');
        });
    },
    getDateListing: function() {
        this.updateUi(HotelContainer.xhrLink + '?date=' + $(this).data('date'));
    },
    updateRoomDetails: function(event) {
        event.preventDefault();

        if (!$(this).valid()) {
            return false;
        }

        var startDate = $('input[name=from_date]').val();
        var submitBtn = $(this).find('button[type=submit]');

        submitBtn.notify('Saving', 'info');

        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            dataType: 'json',
            data: $(this).serialize()
        }).success(function(response) {
            Hotel.updateUi(
                HotelContainer.xhrLink + '?date=' + startDate
            );
            $('form.store-details')[0].reset();
            
            submitBtn.notify('Details updated', 'success');
        }).fail(function(response) {
            submitBtn.notify('Can\'t update the record', 'error')
        });

        return false;
    },
    setUpRoomDetailsOnLoad: function(event) {

        // if (!$(this).valid()) {
        //     return false;
        // }

        // var startDate = $('input[name=from_date]').val();
        // var submitBtn = $(this).find('button[type=submit]');

        // submitBtn.notify('Saving', 'info');

        // $.ajax({
        //     url: $(this).attr('action'),
        //     method: 'POST',
        //     dataType: 'json',
        //     data: $(this).serialize()
        // }).success(function(response) {
        //     Hotel.updateUi(
        //         HotelContainer.xhrLink + '?date=' + startDate
        //     );
        //     $('form.store-details')[0].reset();
            
        //     submitBtn.notify('Details updated', 'success');
        // }).fail(function(response) {
        //     submitBtn.notify('Can\'t update the record', 'error')
        // });

        // return false;
    }

};
