// ~~~~~~~~~~~~~~~~~~~~~~~ CONSTRUCTION.PHP  ET RESERVATION.PHP ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ //

//Permet de faire apparaitre les inputs pour le formulaire CONSTRUCTION
var button = document.getElementById("btnMod"); // le bouton Modifier
var val = document.getElementById("btnValid"); // le bouton Valider en position cache au debut du script
var lesInputs = document.getElementsByTagName('input'); // On selectionne les inputs
var lesLabels = document.getElementsByClassName('labelA'); // On selectionne les labels


button.onclick = function () {
    
    for (var a = 0; a < lesLabels.length; a++) {
        var etat = lesLabels[a].style.display = "none";
    };

    for (var i = 0; i < lesInputs.length; i++) {
        etat = lesInputs[i].style.display;
        if (etat == "none")
        { 
            lesInputs[i].style.display = "inline"; 
        } else 
        { 
            lesInputs[i].style.display = "none"; 
        };
    };
    
    button.style.display = "none";
    val.style.display = "inline";
};

// Permet de faire disparaitre les inputs pour le formulaire CONSTRUCTION

val.onclick = function () {
    val.style.display = "none";
    button.style.display = "inline";

    for (var i = 0; i < lesInputs.length; i++) {
        etat = lesInputs[i].style.display;
        if (etat == "inline") 
        { 
            lesInputs[i].style.display = "none"; 
        } else 
        { 
            lesInputs.style.display = "inline"; 
        };
    };
};


