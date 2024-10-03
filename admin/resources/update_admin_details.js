$(document).ready(function () {
    $("#submitForm").on("click", function () {
        var formData = $("#missingDetailsForm").serialize();
        $.ajax({
            type: "POST",
            url: "/KeNHA/admin-populate",
            data: formData,
            success: function (response) {
                window.location.href = "/KeNHA/subject_matter_expert";
                alert(response);
            },
            error: function () {
                alert("Error submitting the form.");
                console.log(response);
            }
        });
    });
});