$(function () {
    $("#search-input").easyAutocomplete({
        url: function (q) {
            return "/shepherd/search?q=" + q;
        },
        list: {
            maxNumberOfElements: 6,
            onChooseEvent: function () {
                var username = $("#search-input").getSelectedItemData().username;
                window.location.href = "/shepherd/admire/" + username;
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
            url: '/herd/adopt/' + breedChoice,
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
            url: '/herd/abandon/' + breedChoice,
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
