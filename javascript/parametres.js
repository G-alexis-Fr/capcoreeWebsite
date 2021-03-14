// Le script pour la page parametres.php 

var buttonPrix = document.getElementById("btnModPrix"); // le bouton Modifier
var valPrix = document.getElementById("btnValidPrix"); // le bouton Valider en position cache au debut du script
var lesInputsPrix = document.getElementsByClassName('inputPrix'); // On selectionne les inputs
var lesLabelsPrix = document.getElementsByClassName('labelPrix'); // On selectionne les labels


var buttonEmail = document.getElementById("btnModEmail"); // le bouton Modifier
var valEmail = document.getElementById("btnValidEmail"); // le bouton Valider en position cache au debut du script
var lesInputsEmail = document.getElementsByClassName('inputEmail'); // On selectionne les inputs
var lesLabelsEmail = document.getElementsByClassName('labelEmail'); // On selectionne les labels


//Creation de la function change pour afficher les inputs
function afficher(modifier,valider,inputs,labels){
    for (var a = 0; a < labels.length; a++) {
        var etat = labels[a].style.display = "none";
    };

    for (var i = 0; i < inputs.length; i++) {
        etat = inputs[i].style.display;
        if (etat == "none")
        { 
            inputs[i].style.display = "inline";  
        } else 
        { 
            inputs[i].style.display = "none"; 
        };
    };
    
    modifier.style.display = "none";
    valider.style.display = "inline";
};

//Ajout d'evenements click pour afficher les inputs
buttonPrix.addEventListener('click', function(){afficher(buttonPrix,valPrix,lesInputsPrix,lesLabelsPrix)});
buttonEmail.addEventListener('click', function(){afficher(buttonEmail,valEmail,lesInputsEmail,lesLabelsEmail)});

// function cacher(valider,modifier,inputs){
//     valider.style.display = "none";
//     modifier.style.display = "inline";

//     for (var i = 0; i < inputs.length; i++) {
//         etat = inputs[i].style.display;
//         if (etat == "inline") 
//         { 
//             inputs[i].style.display = "none"; 
//         } else 
//         { 
//             inputs.style.display = "inline"; 
//         };
//     };
// };

// valPrix.addEventListener('click', function(){cacher(valPrix,buttonPrix,lesInputsPrix)});
// valEmail.addEventListener('click', function(){cacher(valEmail,buttonEmail,lesInputsEmail)});