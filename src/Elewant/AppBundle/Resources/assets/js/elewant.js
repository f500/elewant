
$(function() {
    $(".elephpant-controls .adopt").click(function(event) {
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

    $(".elephpant-controls .abandon").click(function(event) {
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

    $('#adoptElePHPants').on('hidden.bs.modal', function (e) {
        location.reload();
    });
});
