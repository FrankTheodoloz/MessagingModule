/***
 * fctFilterJS Return JS code for filtering myTable
 * Reference: https://www.w3schools.com/bootstrap4/bootstrap_filters.asp
 */
$(document).ready(function () {
    $("#myInput").on("keyup", function () {
        var value = $(this).val().toLowerCase();
        $("#myTable tr").filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
});