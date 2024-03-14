function validateLogin() {
    let birthday = document.getElementById("birthdayInput").value;
    birthday = new Date(birthday);

    var monthDiffrence = Date.now() - birthday.getTime();
    var age_dt = new Date(monthDiffrence);
    var year = age_dt.getUTCFullYear();
    var age = Math.abs(year - 1970);

    if (age >= 18) {
        window.location.href += "../../fatpaymentportal/index.php?controller=checkout";
    }
}