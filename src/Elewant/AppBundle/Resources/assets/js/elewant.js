
$(function() {
    $("#adoptivate").click(function () {
        var breedChoice = $("#breedChoice").val();

        // todo: implement fos routing bundle
        $.ajax({
            url: '/herd/adopt/' + breedChoice,
            data: [],
            complete: function () {
                location.reload();
            }
        });
    });
});
