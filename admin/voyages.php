<?php
session_start();
$admin = true;
include('../includes/db.php');
include('../includes/auth.php');

if ($_SESSION['user_role'] !== 'admin') {
  header("Location: ../index.php");
  exit;
}

include('../includes/header.php');
?>

<section class="admin-voyages">
    <h2>Liste des Voyages</h2>
    <a href="ajouter_voyage.php" class="btn bg-primary">➕ Ajouter un voyage</a>

    <table id="voyagesTable" class="display nowrap" style="width:100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>Image</th>
                <th>Titre</th>
                <th>Destination</th>
                <th>Date Départ</th>
                <th>Date Retour</th>
                <th>Prix</th>
                <th>Places</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
        $result = $conn->query("SELECT * FROM voyage ORDER BY date_depart ASC");
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>{$row['id']}</td>";
            echo "<td><img src='../images/{$row['image']}' class='img-thumbnail'></td>";
            echo "<td>{$row['titre']}</td>";
            echo "<td>{$row['destination']}</td>";
            echo "<td>".date('d/m/Y', strtotime($row['date_depart']))."</td>";
            echo "<td>".date('d/m/Y', strtotime($row['date_retour']))."</td>";
            echo "<td>{$row['prix']} DH</td>";
            echo "<td>{$row['places_disponibles']}</td>";
            echo "<td>
                    <a href='modifier_voyage.php?id={$row['id']}' class='btn btn-success'>✏️</a>
                    <a href='supprimer_voyage.php?id={$row['id']}' class='btn bg-danger' onclick=\"return confirm('Êtes-vous sûr de vouloir supprimer ce voyage?')\">❌</a>
                  </td>";
            echo "</tr>";
        }
        ?>
        </tbody>
    </table>
    <!-- CSS personnalisé pour le tableau -->
    <style>
    /* Style de base pour le tableau */
    #voyagesTable {
        width: 100% !important;
        margin: 0 auto;
        border-collapse: collapse;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    /* En-tête du tableau */
    #voyagesTable thead th {
        background-color: #2c3e50;
        color: white;
        padding: 12px 15px;
        text-align: left;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85em;
        letter-spacing: 0.5px;
    }

    /* Cellules du tableau */
    #voyagesTable tbody td {
        padding: 12px 15px;
        border-bottom: 1px solid #e0e0e0;
        vertical-align: middle;
    }

    /* Lignes alternées */
    #voyagesTable tbody tr:nth-child(even) {
        background-color: #f8f9fa;
    }

    /* Survol des lignes */
    #voyagesTable tbody tr:hover {
        background-color: #f1f5fd;
        transition: background-color 0.2s ease;
    }

    /* Style des images */
    #voyagesTable img.img-thumbnail {
        width: 80px;
        height: 60px;
        object-fit: cover;
        border-radius: 4px;
        border: 1px solid #ddd;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    /* Style des boutons d'action */
    .btn-action {
        padding: 5px 10px;
        margin: 0 3px;
        border-radius: 4px;
        font-size: 0.85em;
        transition: all 0.2s;
    }

    .btn-edit {
        background-color: #3498db;
        color: white;
        border: 1px solid #2980b9;
    }

    .btn-delete {
        background-color: #e74c3c;
        color: white;
        border: 1px solid #c0392b;
    }

    .btn-action:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        opacity: 0.9;
    }

    /* Style de pagination DataTables */
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        padding: 5px 10px;
        margin-left: 5px;
        border-radius: 4px;
        border: 1px solid #ddd;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: #2c3e50;
        color: white !important;
        border: 1px solid #2c3e50;
    }

    /* Barre de recherche */
    .dataTables_filter input {
        padding: 5px 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        margin-bottom: 15px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        #voyagesTable thead {
            display: none;
        }

        #voyagesTable tbody td {
            display: block;
            text-align: right;
            padding-left: 50%;
            position: relative;
        }

        #voyagesTable tbody td::before {
            content: attr(data-label);
            position: absolute;
            left: 15px;
            width: 45%;
            padding-right: 15px;
            font-weight: bold;
            text-align: left;
        }

        #voyagesTable img.img-thumbnail {
            width: 60px;
            height: 45px;
        }
    }
    </style>

    <!-- JavaScript pour DataTables -->
    <script>
    $(document).ready(function() {
        $('#voyagesTable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.11.5/i18n/fr-FR.json'
            },
            responsive: true,
            columnDefs: [{
                    responsivePriority: 1,
                    targets: 0
                }, // ID
                {
                    responsivePriority: 2,
                    targets: 2
                }, // Titre
                {
                    responsivePriority: 3,
                    targets: 3
                }, // Destination
                {
                    responsivePriority: 4,
                    targets: -1
                } // Actions
            ],
            initComplete: function() {
                // Ajout des labels pour le responsive
                this.api().columns().every(function() {
                    var column = this;
                    var title = $(column.header()).text();

                    $(column.header()).attr('data-label', title);
                });

                this.api().rows().every(function() {
                    var row = this;
                    var data = this.data();

                    $(row.node()).find('td').each(function(i) {
                        var title = $('#voyagesTable thead th').eq(i).text();
                        $(this).attr('data-label', title);
                    });
                });
            }
        });
    });
    </script>
</section>