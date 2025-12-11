<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="Utilisation de grid areas">
    <title>Bataille Navale</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
<header>
    <h1>BATAILLE NAVALE</h1>
</header>
<?php
session_start();

$role = $_SESSION["role"] ?? null;
$id   = $_SESSION["player_id"] ?? null; // <-- ici
?>
<main data-player-id="<?= $id ?>">
    <section class="section1">
        <h2>Navires</h2>

        <article class="porte_avion">
            <h3>Porte avion</h3>
            <button id="porte_avion" type="button" name="porte_avion" data-cells="5" data-color="#e74c3c" data-max="1">
              5 cases
            </button>
        </article>

        <article class="croiseur">
            <h3>Croiseur</h3>
            <button id="croiseur" type="button" name="croiseur" data-cells="4" data-color="#3498db" data-max="1">
              4 cases
            </button>
        </article>

        <article class="sous_marin">
            <h3>Sous-marin</h3>
            <button id="sous_marin" type="button" name="sous_marin" data-cells="3" data-color="#2ecc71" data-max="2">
              3 cases
            </button>
        </article>

        <article class="torpilleur">
            <h3>Torpilleur</h3>
            <button id="torpilleur" type="button" name="torpilleur" data-cells="2" data-color="#f1c40f" data-max="1">
              2 cases
            </button>
        </article>
    </section>

    <section class="section2">
        <?php
        $lines = 10;
        $column = 10;
        for ($i = 0; $i < $column; $i++) {
            for ($j = 0; $j < $lines; $j++) {
                ?>
                <button type="button" class="cell disabled" data-x="<?= $i ?>" data-y="<?= $j ?>"></button>
                <?php
            }
        }
        ?>
    </section>

    <section class="section3">
        <article>
            <p>Votre rôle : <?= $role ?></p>
            <p>Votre ID de session : <?= $id ?></p>
        </article>

        <form method="post" action="../scripts/reset_total.php">
            <button type="submit" name="reset_total">❌ Fin de partie (RESET)</button>
        </form>
    </section>
</main>

<script>
  const player_id = document.querySelector("main").dataset.playerId;
if (!player_id) {
    console.error("Player ID is missing!");
}

const cells = Array.from(document.querySelectorAll('.cell'));
const navireButtons = document.querySelectorAll('.section1 button');

let navire_cliqued = null;
let navire_cells = 0;
let selected_cells = 0;
let selected_positions = [];
let navire_color = null;

// --- CLIQUE SUR NAVIRE ---
navireButtons.forEach(btn => {
    btn.addEventListener('click', () => {

        // 🧹 RESET de l'ancien navire si pas encore validé
        if (selected_positions.length > 0) {
            selected_positions.forEach(pos => {
                const c = document.querySelector(`.cell[data-x="${pos.x}"][data-y="${pos.y}"]`);
                if (c) {
                    c.style.backgroundColor = "";
                    c.classList.remove('selected');
                    // ne pas réactiver une case déjà utilisée
                    if (!c.dataset.used) {
                        c.disabled = false;
                    }
                }
            });
        }

        // Réactiver tous les boutons
        navireButtons.forEach(b => {
            b.disabled = false;
            b.classList.remove('active');
        });

        // Désactiver seulement celui qu’on sélectionne
        btn.classList.add('active');
        btn.disabled = true;

        // Nouveau navire
        navire_cliqued = btn.name;
        navire_cells = parseInt(btn.dataset.cells);
        navire_color = btn.dataset.color;

        selected_positions = [];
        selected_cells = 0;

        btn.style.backgroundColor = navire_color;
        btn.style.color = "#fff";

        // Réactiver toutes les cases non utilisées
        cells.forEach(c => {
            if (!c.dataset.used) {
                c.classList.remove('disabled');
            }
        });
    });
});

// --- CLIQUE SUR CASE ---
cells.forEach(cell => {
    cell.addEventListener('click', () => {

        if (!navire_cliqued) return;

        if (cell.classList.contains('selected')) {
            alert("Cette case est déjà sélectionnée !");
            return;
        }

        if (cell.disabled) return;

        // Sélection visuelle
        cell.style.backgroundColor = navire_color;
        cell.classList.add('selected');

        selected_cells++;
        selected_positions.push({
            x: parseInt(cell.dataset.x),
            y: parseInt(cell.dataset.y)
        });

        // Si on a sélectionné toutes les cases du navire
        if (selected_cells >= navire_cells) {
            const confirmPlacement = confirm(`Valider le placement de ${navire_cliqued} ?`);

            if (!confirmPlacement) {

                selected_positions.forEach(pos => {
                    const btn = document.querySelector(`.cell[data-x="${pos.x}"][data-y="${pos.y}"]`);
                    if (btn) {
                        btn.style.backgroundColor = "";
                        btn.classList.remove('selected');
                    }
                });

                selected_positions = [];
                selected_cells = 0;
                return;
            }

            // Envoi au serveur
            fetch("../scripts/add_ship.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({
                    player_id: player_id,
                    name: navire_cliqued,
                    remaining_cells: navire_cells,
                    positions: selected_positions
                })
            })
            .then(r => r.json())
            .then(res => {

                if (!res.success) {
                    alert("Erreur : " + res.error);

                    selected_positions.forEach(pos => {
                        const btn = document.querySelector(`.cell[data-x="${pos.x}"][data-y="${pos.y}"]`);
                        if (btn) {
                            btn.style.backgroundColor = "";
                            btn.classList.remove('selected');
                        }
                    });
                } else {

                    // CASES VALIDÉES -> marquer comme définitivement utilisées
                    selected_positions.forEach(pos => {
                        const btn = document.querySelector(`.cell[data-x="${pos.x}"][data-y="${pos.y}"]`);
                        if (btn) {
                            btn.disabled = true;
                            btn.dataset.used = "1"; // sauvegarde locale
                            btn.classList.remove('selected');
                        }
                    });
                }

                navire_cliqued = null;
                selected_positions = [];
                selected_cells = 0;
            });
        }
    });
});


</script>

</body>
</html>
