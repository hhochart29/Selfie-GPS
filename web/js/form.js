$(function() {
    $(".select").change(function() {
        console.log($(this).val());
    }).multipleSelect({
        width: "210px"
    });
});
