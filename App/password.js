const passwordFields = [
    {eyeIconId: "eyeicon", passwordFieldId: "password"},
    {eyeIconId: "eyeicon1", passwordFieldId: "password1"},
    {eyeIconId: "eyeicon2", passwordFieldId: "password2"},
];

function togglePasswordVisibility(eyeIconId, passwordFieldId) {
    let eyeicon = document.getElementById(eyeIconId);
    let password = document.getElementById(passwordFieldId);

    eyeicon.onclick = function () {
        if (password.type === "password") {
            password.type = "text";
            eyeicon.src = "../imgs/olho-aberto.svg";
        } else {
            password.type = "password";
            eyeicon.src = "../imgs/olho-fechado.svg";
        }
    };
}

// Iterar sobre o array e chamar a função togglePasswordVisibility para cada par de elementos
passwordFields.forEach(pair => {
    togglePasswordVisibility(pair.eyeIconId, pair.passwordFieldId);
});
