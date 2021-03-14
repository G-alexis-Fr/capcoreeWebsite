        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <a class="navbar-brand h1 text-light" href="https://www.capcoree.fr" target = "_blank">CapCorée</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <?php
                if(isset($_SESSION["membre"])) :
                    if($_SESSION["role"] == "Admin") :
                    ?>                    
                        <a class="nav-link" href="employes.php">
                            <li class="nav-item active">EMPLOYES</li>
                        </a>
                        <a class="nav-link" href="compta.php">
                            <li class="nav-item active">COMPTA</li>
                        </a>
                        <a class="nav-link" href="historique.php">
                            <li class="nav-item active">HISTORIQUE</li>
                        </a>
                        <a class="nav-link" href="alerte.php">
                            <li class="nav-item active">ALERTE</li>
                        </a>
                        <a class="nav-link" href="hotels.php">
                            <li class="nav-item active">HOTELS</li>
                        </a>
                        <a class="nav-link" href="activites.php">
                            <li class="nav-item active">ACTIVITES</li>
                        </a>
                        <a class="nav-link" href="voyageurs.php">
                            <li class="nav-item active">VOYAGEURS</li>
                        </a>
                        <a class="nav-link" href="parametres.php">
                            <li class="nav-item active">PARAMETRES</li>
                        </a>
                        <a class="nav-link" href="compte.php">
                            <li class="nav-item active">MON COMPTE</li>
                        </a>
                        <a class="nav-link" href="deconnexion.php">
                            <li class="nav-item active">DECONNEXION</li>
                        </a>
                    <?php
                    endif;
                        
                    if($_SESSION["role"] == "Employe" ||  $_SESSION["role"] == "Stagiaire") :
                        ?>                    
                            <a class="nav-link" href="employes.php">
                                <li class="nav-item active">EMPLOYES</li>
                            </a>
                            <a class="nav-link" href="compta.php">
                                <li class="nav-item active">COMPTA</li>
                            </a>
                            <a class="nav-link" href="historique.php">
                                <li class="nav-item active">HISTORIQUE</li>
                            </a>
                            <a class="nav-link" href="hotels.php">
                                <li class="nav-item active">HOTELS</li>
                            </a>
                            <a class="nav-link" href="activites.php">
                                <li class="nav-item active">ACTIVITES</li>
                            </a>
                            <a class="nav-link" href="voyageurs.php">
                                <li class="nav-item active">VOYAGEURS</li>
                            </a>
                            <a class="nav-link" href="compte.php">
                                <li class="nav-item active">MON COMPTE</li>
                            </a>
                            <a class="nav-link" href="deconnexion.php">
                                <li class="nav-item active">DECONNEXION</li>
                            </a>
                        <?php
                        endif;

                        if($_SESSION["role"] == "Finance") :
                            ?>                    
                                <a class="nav-link" href="employes.php">
                                    <li class="nav-item active">EMPLOYES</li>
                                </a>
                                <a class="nav-link" href="compta.php">
                                    <li class="nav-item active">COMPTA</li>
                                </a>
                                <a class="nav-link" href="historique.php">
                                    <li class="nav-item active">HISTORIQUE</li>
                                </a>
                                <a class="nav-link" href="alerte.php">
                                    <li class="nav-item active">ALERTE</li>
                                </a>
                                <a class="nav-link" href="hotels.php">
                                    <li class="nav-item active">HOTELS</li>
                                </a>
                                <a class="nav-link" href="activites.php">
                                    <li class="nav-item active">ACTIVITES</li>
                                </a>
                                <a class="nav-link" href="voyageurs.php">
                                    <li class="nav-item active">VOYAGEURS</li>
                                </a>
                                <a class="nav-link" href="compte.php">
                                    <li class="nav-item active">MON COMPTE</li>
                                </a>
                                <a class="nav-link" href="deconnexion.php">
                                    <li class="nav-item active">DECONNEXION</li>
                                </a>
                            <?php
                            endif;

                    if($_SESSION["role"] == "Guide") :
                    ?>
                        <a class="nav-link" href="historique.php">
                        <li class="nav-item active">HISTORIQUE</li>
                        </a>
                        <a class="nav-link" href="compta.php">
                        <li class="nav-item active">COMPTA</li>
                        </a>
                        <a class="nav-link" href="compte.php">
                        <li class="nav-item active">MON COMPTE</li>
                        </a>
                        <a class="nav-link" href="deconnexion.php">
                        <li class="nav-item active">DECONNEXION</li>
                        </a>
                    <?php
                       endif;
                else :
                ?>
                    <li class="nav-item active"><a class="nav-link" href="login.php">CONNEXION</a></li>
                <?php
                endif;
                ?>
            
                    
            </ul>
            </div>
        </nav>