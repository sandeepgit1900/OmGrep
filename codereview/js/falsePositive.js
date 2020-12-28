function markFalsePositive(ref, str) {
    ref = ref
    $.ajax({
        type: "POST",
        url: "fpController.php",
        data: {type: "ADD", code: str}
    }).done(function (msg) {
        if (msg.status == true) {
            var saveRef = $(ref).parent().parent().prev();
            $(ref).parent().parent().hide();
            saveRef.hide();
        } else {
            alert("There is some error");
        }
    });
}

