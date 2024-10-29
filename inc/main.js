const basic_gdpr_alert = document.getElementById("basic-gdpr-alert");

document.querySelector(".basic-gdpr-alert-button").addEventListener("click", function(){
    if(!localStorage.getItem('basic_gdpr_alert')) {
        localStorage.setItem('basic_gdpr_alert', true);
        basic_gdpr_alert.style.display = "none";
    }
}); 

document.addEventListener('DOMContentLoaded', (event) => {
    if(!localStorage.getItem('basic_gdpr_alert')) {
        basic_gdpr_alert.style.display = "block";
    }
});