function checkPassword() {
    var pwd = $("#inputPwd1").val();
    var pwdCheck = $("#inputPwd2").val();

    if (pwd != pwdCheck) {
        $("#inputPwd2").addClass("is-invalid");
        $("#btnPwdSubmit").prop("disabled", true);

    } else {
        $("#inputPwd2").removeClass("is-invalid");
        $("#btnPwdSubmit").prop("disabled", false);
    }
}

$(document).ready(function () {
    $("#btnPwdSubmit").prop("disabled", true);
    $("#inputPwd2").keyup(checkPassword);
    $("#inputPwd1").keyup(checkPassword);
    $("#modalPwdChange").on("show.bs.modal", function () {
        $("#frmPwdChange")[0].reset();
        $("#inputPwd2").removeClass("is-invalid");
        $("#btnPwdSubmit").prop("disabled", true);
    })
});
