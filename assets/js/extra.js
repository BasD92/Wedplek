function init() {
    $('#logout').click(logout);
}

function logout() {
    alert('Uw bent succesvol uitgelogd!');
}

//Uitvoeren als pagina geladen is
$(document).on('ready', init);