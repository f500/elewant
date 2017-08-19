
$(function() {
    $(".elephpant-controls .adopt").click(function(event) {
        var breedChoice = $(event.target).data("breed");

        $.ajax({
            url: '/herd/adopt/' + breedChoice,
            data: [],
            success: function () {
                var countInput = $(".elephpant-controls input." + breedChoice);
                countInput.val(parseInt(countInput.val()) + 1);
            }
        });
    });

    $(".elephpant-controls .abandon").click(function(event) {
        var breedChoice = $(event.target).data("breed");

        $.ajax({
            url: '/herd/abandon/' + breedChoice,
            data: [],
            success: function () {
                var countInput = $(".elephpant-controls input." + breedChoice);
                countInput.val(parseInt(countInput.val()) - 1);
            }
        });
    });

    $('#adoptElePHPants').on('hidden.bs.modal', function (e) {
        location.reload();
    });
});
