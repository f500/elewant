/**
 * This function expects a field for the herd name and a edit button
 *
 * Example:
 *  <span id="herd-name">Name of herd</span>
 *  <i id="rename-herd" class="fa fa-pencil-square change-herd-name-icon"></i>
 *
 * The location is a dom object around the above given example
 *
 * @param location
 * @constructor
 */
function RenameHerd(location) {
    var herdNameHolder        = location.find('#herd-name-holder');
    var herdNameHolderContent = null;
    var currentHerdName       = herdNameHolder.find('#herd-name').text();

    this.initialize = function () {
        $(herdNameHolder).on('click', '#rename-herd', function() {
            editName();
        });
    };

    var editName = function() {
        herdNameHolderContent = herdNameHolder.html();

        herdNameHolder.html(
            '<input id="rename-herd-input" class="rename-herd-input" type="text" /> ' +
            '<i id="save-rename-herd" class="fa fa-check-square save-rename-herd"></i>'
        );

        var input  = herdNameHolder.find('#rename-herd-input');
        var button = herdNameHolder.find('#save-rename-herd');

        input.on('keyup', function(e) {
            if (e.keyCode === 27) {
                resetName();
            }

            if (e.keyCode === 13) {
                saveName();
            }
        });

        button.on('click', function () {
            saveName();
        });

        input.focus();
        input.val(currentHerdName);
    };

    var saveName = function () {
        var herdName = herdNameHolder.find('#rename-herd-input').val();

        herdNameHolder.html(herdNameHolderContent);
        herdNameHolder.find('#herd-name').html(herdName);

        clearErrors();

        $.post('/herd/rename-herd', {'name': herdName})
        .always(function(jqXHR, status) {
            if (status === 'success') {
                currentHerdName = herdName;
            } else {
                herdNameHolder.find('#herd-name').html(currentHerdName);

                var errorMessages = jqXHR.responseJSON.errorMessages;
                $.each(errorMessages, function(key, value) {
                    addError(value);
                });
            }
        });
    };
    var resetName = function () {
        herdNameHolder.html(herdNameHolderContent);
        herdNameHolder.find('#herd-name').html(currentHerdName);
    };

    var addError = function(error) {
        herdNameHolder.after(
            '<div class="alert alert-danger alert-dismissible fade show rename-validation-error" role="alert">' +
                error +
                '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                    '<span aria-hidden="true">&times;</span>' +
                '</button>' +
            '</div>'
        );
    };

    var clearErrors = function() {
        location.find('.rename-validation-error').remove();
    };
}
