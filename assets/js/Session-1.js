$(document).ready(function () {
    $("h1").hover(
        function () {
            $(this).css(
                "color",
                "green"
            );
        },
        function () {
            $(this).css(
                "color",
                "aliceblue"
            );
        }
    );
});