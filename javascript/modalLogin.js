// Permet d'afficher le modal MDP oublie
// Display the modal Forgot Password

function openModal(){
    document.getElementById("modal").style.top = "100px";
}

// Permet de cacher le modal MDP oublie
// Hide the modal Forgot Password

function closeModal() {
    document.getElementById("modal").style.top = "-400px";
}

// Desactive le bouton reinitialiser pour etre sur que les gens ne clique pas plusieurs fois

let button = document.getElementById("resetButton"); // le bouton Modifier
let waiting = document.getElementById("waiting");

button.onclick = function(){
    waiting.style.display = "inline"
}