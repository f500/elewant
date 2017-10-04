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

    $(".elephpant-controls .adopt").click(function (event) {
        var breedChoice = $(event.target).data("breed");

        $.ajax({
            url: Routing.generate('herd_adopt_breed', { 'breed': breedChoice }),
            data: [],
            success: function () {
                var countInput = $(".elephpant-controls .count-" + breedChoice);
                countInput.html(parseInt(countInput.html()) + 1);
            }
        });
    });

    $(".elephpant-controls .abandon").click(function (event) {
        var breedChoice = $(event.target).data("breed");

        $.ajax({
            url: Routing.generate('herd_abandon_breed', { 'breed': breedChoice }),
            data: [],
            success: function () {
                var countInput = $(".elephpant-controls .count-" + breedChoice);
                countInput.html(parseInt(countInput.html()) - 1);
            }
        });
    });

    $('#adoptElePHPants').on('hidden.bs.modal', function () {
        location.reload();
    });
});
