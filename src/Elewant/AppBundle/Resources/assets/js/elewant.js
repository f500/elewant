$(function () {
    $("#find-a-herd").easyAutocomplete({
        url: function (q) {
            return Routing.generate('shepherd_search', {'q': q});
        },
        list: {
            maxNumberOfElements: 6,
            onChooseEvent: function () {
                var username = $("#find-a-herd").getSelectedItemData().username;
                window.location.href = Routing.generate('shepherd_admire_herd', {'username': username});
            }
        },
        theme: "bootstrap",
        getValue: "name",
        template: {
            type: "description",
            fields: {
                description: "username"
            }
        },
        requestDelay: 300
    });

    $(".elephpant-adopt").click(function (event) {
        var breedChoice = $(event.target).data("breed");

        $.ajax({
            url: Routing.generate('herd_adopt_breed', { 'breed': breedChoice }),
            data: [],
            context: this,
            success: function () {
                var countInput = $(this).siblings('.elephpant-amount');
                countInput.text(parseInt(countInput.text()) + 1);
            }
        });
    });

    $(".elephpant-abandon").click(function (event) {
        var breedChoice = $(event.target).data("breed");

        $.ajax({
            url: Routing.generate('herd_abandon_breed', { 'breed': breedChoice }),
            data: [],
            context: this,
            success: function () {
                var countInput = $(this).siblings('.elephpant-amount');
                countInput.text(parseInt(countInput.text()) - 1);
            }
        });
    });

    $("#desire_breeds").find(".elephpant").click(function (event) {
        var breedChoice = $(this).data("breed");

        $.ajax({
            url: Routing.generate('herd_desire_breed', { 'breed': breedChoice }),
            data: [],
            context: this,
            success: function () {
                $(this).remove();
            }
        });
    });

    $("#desired_breeds").find(".elephpant").click(function (event) {
        var breedChoice = $(this).data("breed");

        $.ajax({
            url: Routing.generate('herd_eliminate_desire_for_breed', { 'breed': breedChoice }),
            data: [],
            context: this,
            success: function () {
                $(this).remove();
            }
        });
    });


    $('#adopt_elephpants, #desire_breeds').on('hidden.bs.modal', function () {
        location.reload();
    });
});
