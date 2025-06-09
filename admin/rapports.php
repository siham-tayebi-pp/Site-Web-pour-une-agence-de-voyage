<?php
ob_start();
session_start();
$admin=true;
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../libs/dompdf/autoload.inc.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// Vérification des droits admin
if ($_SESSION['user_role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

// Traitement du formulaire de rapport
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['type_rapport'])) {
    $type_rapport = $_POST['type_rapport'];
    $date_debut = $_POST['date_debut'] ?? null;
    $date_fin = $_POST['date_fin'] ?? null;
    $format = $_POST['format'] ?? 'pdf';

    // Conversion des dates
    if ($date_debut) {
        $date_debut = DateTime::createFromFormat('d/m/Y', $date_debut)->format('Y-m-d');
    }
    if ($date_fin) {
        $date_fin = DateTime::createFromFormat('d/m/Y', $date_fin)->format('Y-m-d');
    }

    // Génération du rapport
    $rapport = genererRapport($conn, $type_rapport, $date_debut, $date_fin);

    if ($format === 'pdf') {
        genererPDF($rapport, $type_rapport);
    } else {
        genererExcel($rapport, $type_rapport);
    }
    exit;
}

function genererRapport($conn, $type_rapport, $date_debut, $date_fin) {
    $data = [];
    $title = '';
    $description = '';
    
    switch ($type_rapport) {
        case 'reservations':
            $title = 'Rapport des Réservations';
            $description = 'Ce rapport présente toutes les réservations effectuées par les clients, avec les détails des voyages, les montants et les statuts.';
            $query = "SELECT r.id, u.nom, u.prenom, v.titre, r.date_reservation, 
                             r.nombre_personnes, r.statut, v.prix,
                             (r.nombre_personnes * v.prix) AS montant_total
                      FROM reservation r
                      JOIN utilisateur u ON r.client_id = u.id
                      JOIN voyage v ON r.voyage_id = v.id";
            
            if ($date_debut && $date_fin) {
                $query .= " WHERE r.date_reservation BETWEEN ? AND ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("ss", $date_debut, $date_fin);
            } else {
                $stmt = $conn->prepare($query);
            }
            
            $stmt->execute();
            $data = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            break;
            
        case 'revenus':
            $title = 'Analyse des Revenus';
            $description = 'Analyse financière détaillée montrant les revenus générés par chaque voyage, le nombre de réservations et la moyenne par réservation.';
            $query = "SELECT v.id, v.titre, COUNT(r.id) AS nb_reservations, 
                             SUM(r.nombre_personnes * v.prix) AS revenu_total,
                             AVG(r.nombre_personnes * v.prix) AS moyenne_par_reservation
                      FROM voyage v
                      LEFT JOIN reservation r ON v.id = r.voyage_id
                      WHERE r.statut = 'confirmé'";
            
            if ($date_debut && $date_fin) {
                $query .= " AND r.date_reservation BETWEEN ? AND ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("ss", $date_debut, $date_fin);
            } else {
                $stmt = $conn->prepare($query);
            }
            
            $query .= " GROUP BY v.id ORDER BY revenu_total DESC";
            $stmt->execute();
            $data = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            break;
            
        case 'utilisateurs':
            $title = 'Rapport des Utilisateurs';
            $description = 'Statistiques complètes sur les utilisateurs, incluant leur historique de réservations et le montant total dépensé.';
            $query = "SELECT u.id, u.nom, u.prenom, u.email, u.dateInscription, 
                             COUNT(r.id) AS nb_reservations,
                             SUM(CASE WHEN r.statut = 'confirmé' THEN r.nombre_personnes * v.prix ELSE 0 END) AS montant_total
                      FROM utilisateur u
                      LEFT JOIN reservation r ON u.id = r.client_id
                      LEFT JOIN voyage v ON r.voyage_id = v.id
                      GROUP BY u.id ORDER BY nb_reservations DESC";
            
            $stmt = $conn->prepare($query);
            $stmt->execute();
            $data = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            break;
    }
    
    return [
        'title' => $title,
        'description' => $description,
        'type' => $type_rapport,
        'data' => $data,
        'date_debut' => $date_debut,
        'date_fin' => $date_fin,
        'generation_date' => date('d/m/Y H:i'),
        'generation_timestamp' => time()
    ];
}

function genererPDF($rapport, $filename) {
    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isRemoteEnabled', true);
    $options->set('defaultFont', 'Helvetica');
    
    $dompdf = new Dompdf($options);
    $html = buildReportHTML($rapport);
    
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'landscape');
    $dompdf->render();
    
    $dompdf->stream("rapport_{$filename}_" . date('Ymd-His') . ".pdf", ["Attachment" => true]);
    exit;
}

function buildReportHTML($rapport) {
    ob_start();
    ?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($rapport['title']) ?></title>
    <style>
    @page {
        margin: 1.5cm;
    }

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        font-size: 12px;
        line-height: 1.6;
        color: #1e293b;
        margin: 0;
        padding: 0;
        background-color: #f8fafc;
    }

    .container {
        background-color: white;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(59, 130, 246, 0.1);
        border: 1px solid #e2e8f0;
    }

    .header {
        border-bottom: 3px solid #3b82f6;
        padding-bottom: 20px;
        margin-bottom: 30px;
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        position: relative;
    }

    .header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.02) 0%, rgba(147, 197, 253, 0.02) 100%);
        border-radius: 8px;
        z-index: -1;
    }

    .agency-info {
        width: 65%;
    }

    .agency-logo {
        text-align: right;
        width: 35%;
    }

    .agency-name {
        color: #1e40af;
        font-size: 22px;
        font-weight: 700;
        margin: 0 0 8px 0;
        letter-spacing: 0.5px;
    }

    .agency-details {
        color: #64748b;
        font-size: 12px;
        margin: 0;
        line-height: 1.5;
    }

    .logo-placeholder {
        border: 2px solid #e2e8f0;
        padding: 15px;
        text-align: center;
        border-radius: 10px;
        background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
    }

    .logo-text {
        color: #1e40af;
        font-weight: 700;
        font-size: 16px;
        margin-bottom: 5px;
    }

    .logo-subtitle {
        color: #64748b;
        font-size: 11px;
    }

    .report-title {
        color: #0f172a;
        font-size: 26px;
        font-weight: 700;
        margin: 20px 0 8px 0;
        position: relative;
    }

    .report-title::after {
        content: '';
        position: absolute;
        bottom: -5px;
        left: 0;
        width: 60px;
        height: 3px;
        background: linear-gradient(90deg, #3b82f6, #60a5fa);
        border-radius: 2px;
    }

    .report-description {
        color: #64748b;
        font-size: 14px;
        margin-bottom: 25px;
        line-height: 1.6;
    }

    .report-info {
        background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
        border: 1px solid #bfdbfe;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 30px;
        display: flex;
        justify-content: space-between;
        font-size: 13px;
        position: relative;
    }

    .report-info::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, #3b82f6, #60a5fa);
        border-radius: 10px 10px 0 0;
    }

    .report-info div {
        flex: 1;
        padding: 0 10px;
    }

    .report-info strong {
        color: #1e40af;
        font-weight: 600;
        display: block;
        margin-bottom: 5px;
    }

    .report-info span {
        color: #475569;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        font-size: 11px;
        background: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(59, 130, 246, 0.08);
    }

    th {
        background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
        color: white;
        text-align: left;
        padding: 12px 15px;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 10px;
        letter-spacing: 0.8px;
        position: relative;
    }

    th::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 2px;
        background: linear-gradient(90deg, #60a5fa, #93c5fd);
    }

    td {
        padding: 12px 15px;
        border-bottom: 1px solid #f1f5f9;
        color: #334155;
        vertical-align: middle;
    }

    tr:nth-child(even) {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    }

    tr:hover {
        background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
    }

    .total-row {
        font-weight: 700;
        background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%) !important;
        color: #1e40af;
        border-top: 2px solid #3b82f6;
    }

    .status-badge {
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-confirmed {
        background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
        color: #1e40af;
        border: 1px solid #93c5fd;
    }

    .status-pending {
        background: linear-gradient(135deg, #e0f2fe 0%, #b3e5fc 100%);
        color: #0277bd;
        border: 1px solid #81d4fa;
    }

    .status-cancelled {
        background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
        color: #475569;
        border: 1px solid #cbd5e1;
    }

    .summary {
        background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
        border-radius: 10px;
        padding: 18px 20px;
        margin-top: 25px;
        font-size: 13px;
        color: #1e40af;
        font-weight: 600;
        border: 1px solid #bfdbfe;
        position: relative;
    }

    .summary::before {
        content: '📊';
        position: absolute;
        left: 20px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 16px;
    }

    .summary-content {
        margin-left: 30px;
    }

    .footer {
        margin-top: 50px;
        padding-top: 25px;
        border-top: 3px solid #3b82f6;
        font-size: 11px;
        color: #64748b;
        position: relative;
    }

    .footer::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.02) 0%, rgba(147, 197, 253, 0.02) 100%);
        border-radius: 8px;
        z-index: -1;
    }

    .signatures {
        display: flex;
        justify-content: space-between;
        margin: 40px 0;
    }

    .signature {
        width: 45%;
        text-align: center;
        position: relative;
    }

    .signature-line {
        border-top: 2px solid #cbd5e1;
        width: 80%;
        margin: 0 auto;
        padding-top: 8px;
        position: relative;
    }

    .signature-line::before {
        content: '';
        position: absolute;
        top: -2px;
        left: 50%;
        transform: translateX(-50%);
        width: 20px;
        height: 2px;
        background: #3b82f6;
    }

    .signature-title {
        font-weight: 600;
        color: #1e40af;
        margin-top: 15px;
        font-size: 12px;
    }

    .signature-subtitle {
        color: #64748b;
        font-size: 10px;
        margin-top: 5px;
    }

    .footer-info {
        text-align: center;
        margin-top: 35px;
        padding: 20px;
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-radius: 8px;
        border: 1px solid #e2e8f0;
    }

    .footer-info p {
        margin: 5px 0;
        line-height: 1.4;
    }

    .page-number {
        text-align: right;
        margin-top: 20px;
        font-weight: 600;
        color: #3b82f6;
    }

    .text-right {
        text-align: right;
    }

    .text-center {
        text-align: center;
    }

    .nowrap {
        white-space: nowrap;
    }

    .amount {
        font-weight: 600;
        color: #1e40af;
    }
    </style>
</head>

<body>
    <div class="container">
        <!-- En-tête avec logo et infos agence -->
        <div class="header">
            <div class="agency-info">
                <h1 class="agency-name">AGENCE DE VOYAGES MAROC TOUR</h1>
                <p class="agency-details">
                    123 Avenue Mohammed V, Casablanca, Maroc<br>
                    Tél: +212 522 123 456 | Email: contact@maroctour.ma<br>
                    Site web: www.maroctour.ma | RC: 12345 Casablanca
                </p>
            </div>
            <div class="agency-logo">
                <div class="logo-placeholder">
                    <div class="logo-text">MAROC TOUR</div>
                    <div class="logo-subtitle">Voyages & Découvertes</div>
                </div>
            </div>
        </div>

        <!-- Titre du rapport -->
        <h2 class="report-title"><?= htmlspecialchars($rapport['title']) ?></h2>
        <p class="report-description"><?= $rapport['description'] ?></p>

        <!-- Informations sur le rapport -->
        <div class="report-info">
            <div>
                <strong>Période couverte</strong>
                <span><?= $rapport['date_debut'] ? date('d/m/Y', strtotime($rapport['date_debut'])) . ' au ' . date('d/m/Y', strtotime($rapport['date_fin'])) : 'Toutes dates' ?></span>
            </div>
            <div>
                <strong>Généré le</strong>
                <span><?= $rapport['generation_date'] ?></span>
            </div>
            <div>
                <strong>Référence</strong>
                <span>RAPP-<?= strtoupper(substr($rapport['type'], 0, 3)) ?>-<?= date('Ymd', $rapport['generation_timestamp']) ?></span>
            </div>
        </div>

        <!-- Tableau des données -->
        <table>
            <thead>
                <tr>
                    <?php
                    switch ($rapport['type']) {
                        case 'reservations':
                            echo '<th>ID</th><th>Client</th><th>Voyage</th><th>Date Réservation</th>';
                            echo '<th class="text-right">Personnes</th><th class="text-right">Prix U.</th>';
                            echo '<th class="text-right">Montant</th><th>Statut</th>';
                            break;
                        case 'revenus':
                            echo '<th>Voyage</th><th class="text-right">Réservations</th>';
                            echo '<th class="text-right">Revenu Total</th><th class="text-right">Moyenne/Resa</th>';
                            break;
                        case 'utilisateurs':
                            echo '<th>ID</th><th>Client</th><th>Email</th><th>Inscription</th>';
                            echo '<th class="text-right">Réservations</th><th class="text-right">Montant Total</th>';
                            break;
                    }
                    ?>
                </tr>
            </thead>
            <tbody>
                <?php
                $total = 0;
                foreach ($rapport['data'] as $row) {
                    echo '<tr>';
                    
                    switch ($rapport['type']) {
                        case 'reservations':
                            echo '<td>' . $row['id'] . '</td>';
                            echo '<td>' . htmlspecialchars($row['prenom'] ). ' ' . htmlspecialchars($row['nom']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['titre']) . '</td>';
                            echo '<td class="nowrap">' . date('d/m/Y', strtotime($row['date_reservation'])) . '</td>';
                            echo '<td class="text-right">' . $row['nombre_personnes'] . '</td>';
                            echo '<td class="text-right amount">' . number_format($row['prix'], 2, ',', ' ') . ' DH</td>';
                            echo '<td class="text-right amount">' . number_format($row['montant_total'], 2, ',', ' ') . ' DH</td>';
                            
                            $statusClass = 'status-pending';
                            if ($row['statut'] == 'confirmé') $statusClass = 'status-confirmed';
                            if ($row['statut'] == 'annulé') $statusClass = 'status-cancelled';
                            
                            echo '<td><span class="status-badge ' . $statusClass . '">' . ucfirst($row['statut']) . '</span></td>';
                            $total += $row['montant_total'];
                            break;
                            
                        case 'revenus':
                            echo '<td>' . htmlspecialchars($row['titre']) . '</td>';
                            echo '<td class="text-right">' . $row['nb_reservations'] . '</td>';
                            echo '<td class="text-right amount">' . number_format($row['revenu_total'], 2, ',', ' ') . ' DH</td>';
                            echo '<td class="text-right amount">' . number_format($row['moyenne_par_reservation'], 2, ',', ' ') . ' DH</td>';
                            $total += $row['revenu_total'];
                            break;
                            
                        case 'utilisateurs':
                            echo '<td>' . $row['id'] . '</td>';
                            echo '<td>' . htmlspecialchars($row['prenom'] . ' ' . $row['nom']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['email']) . '</td>';
                            echo '<td class="nowrap">' . date('d/m/Y', strtotime($row['dateInscription'])) . '</td>';
                            echo '<td class="text-right">' . $row['nb_reservations'] . '</td>';
                            echo '<td class="text-right amount">' . number_format($row['montant_total'], 2, ',', ' ') . ' DH</td>';
                            break;
                    }
                    
                    echo '</tr>';
                }
                
                // Ligne de total si applicable
                if (in_array($rapport['type'], ['reservations', 'revenus'])) {
                    echo '<tr class="total-row">';
                    echo '<td colspan="' . ($rapport['type'] === 'reservations' ? '6' : '2') . '"><strong>TOTAL GÉNÉRAL</strong></td>';
                    echo '<td class="text-right"><strong>' . number_format($total, 2, ',', ' ') . ' DH</strong></td>';
                    if ($rapport['type'] === 'reservations') echo '<td></td>';
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>

        <!-- Statistiques résumées -->
        <?php if (!empty($rapport['data'])): ?>
        <div class="summary">
            <div class="summary-content">
                <strong>Résumé de l'analyse :</strong>
                <?php 
                switch ($rapport['type']) {
                    case 'reservations':
                        echo count($rapport['data']) . ' réservations trouvées';
                        if (in_array($rapport['type'], ['reservations', 'revenus'])) {
                            echo ' pour un montant total de ' . number_format($total, 2, ',', ' ') . ' DH';
                        }
                        break;
                    case 'revenus':
                        echo count($rapport['data']) . ' voyages analysés';
                        echo ' générant ' . number_format($total, 2, ',', ' ') . ' DH de revenus';
                        break;
                    case 'utilisateurs':
                        echo count($rapport['data']) . ' utilisateurs inscrits dans la base de données';
                        break;
                }
                ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Pied de page avec signatures -->
        <div class="footer">
            <div class="signatures">
                <div class="signature">
                    <div class="signature-line"></div>
                    <div class="signature-title">Responsable Commercial</div>
                    <div class="signature-subtitle">Agence Maroc Tour</div>
                </div>
                <div class="signature">
                    <div class="signature-line"></div>
                    <div class="signature-title">Responsable Financier</div>
                    <div class="signature-subtitle">Agence Maroc Tour</div>
                </div>
            </div>

            <div class="footer-info">
                <p><strong>AGENCE DE VOYAGES MAROC TOUR</strong> - Siège social: 123 Avenue Mohammed V, Casablanca</p>
                <p>Tél: +212 522 123 456 | Email: contact@maroctour.ma | Site: www.maroctour.ma</p>
                <p>SARL au capital de 500 000 DH - RC: 12345 Casablanca - ICE: 001234567891</p>
                <p style="margin-top: 15px; font-style: italic;">Ce document est généré automatiquement et ne nécessite
                    pas de signature manuscrite.</p>
            </div>

            <div class="page-number">
                Page 1 sur 1 | Généré le <?= date('d/m/Y à H:i') ?>
            </div>
        </div>
    </div>
</body>

</html>
<?php
    return ob_get_clean();
}

function genererExcel($rapport, $filename) {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="rapport_' . $filename . '_' . date('Ymd-His') . '.csv"');
    
    $output = fopen('php://output', 'w');
    
    // En-tête avec infos agence
    fputcsv($output, ['AGENCE DE VOYAGES MAROC TOUR'], ';');
    fputcsv($output, ['123 Avenue Mohammed V, Casablanca, Maroc'], ';');
    fputcsv($output, ['Tél: +212 522 123 456 | Email: contact@maroctour.ma | Site web: www.maroctour.ma'], ';');
    fputcsv($output, [], ';');
    
    // Titre du rapport
    fputcsv($output, [$rapport['title']], ';');
    fputcsv($output, [$rapport['description']], ';');
    fputcsv($output, [], ';');
    
    // Infos rapport
    fputcsv($output, ['Période:', $rapport['date_debut'] ? $rapport['date_debut'] . ' au ' . $rapport['date_fin'] : 'Toutes dates'], ';');
    fputcsv($output, ['Généré le:', $rapport['generation_date']], ';');
    fputcsv($output, ['Référence:', 'RAPP-' . strtoupper(substr($rapport['type'], 0, 3)) . '-' . date('Ymd', $rapport['generation_timestamp'])], ';');
    fputcsv($output, [], ';');
    
    // En-têtes des colonnes
    $headers = [];
    switch ($rapport['type']) {
        case 'reservations':
            $headers = ['ID', 'Client', 'Voyage', 'Date Réservation', 'Personnes', 'Prix unitaire', 'Montant', 'Statut'];
            break;
        case 'revenus':
            $headers = ['Voyage', 'Réservations', 'Revenu Total', 'Moyenne/Resa'];
            break;
        case 'utilisateurs':
            $headers = ['ID', 'Nom', 'Email', 'Inscription', 'Réservations', 'Montant Total'];
            break;
    }
    fputcsv($output, $headers, ';');
    
    // Données
    $total = 0;
    foreach ($rapport['data'] as $row) {
        $data = [];
        
        switch ($rapport['type']) {
            case 'reservations':
                $data = [
                    $row['id'],
                    $row['prenom'] . ' ' . $row['nom'],
                    $row['titre'],
                    $row['date_reservation'],
                    $row['nombre_personnes'],
                    number_format($row['prix'], 2, ',', ' ') . ' DH',
                    number_format($row['montant_total'], 2, ',', ' ') . ' DH',
                    ucfirst($row['statut'])
                ];
                $total += $row['montant_total'];
                break;
                
            case 'revenus':
                $data = [
                    $row['titre'],
                    $row['nb_reservations'],
                    number_format($row['revenu_total'], 2, ',', ' ') . ' DH',
                    number_format($row['moyenne_par_reservation'], 2, ',', ' ') . ' DH'
                ];
                $total += $row['revenu_total'];
                break;
                
            case 'utilisateurs':
                $data = [
                    $row['id'],
                    $row['prenom'] . ' ' . $row['nom'],
                    $row['email'],
                    $row['dateInscription'],
                    $row['nb_reservations'],
                    number_format($row['montant_total'], 2, ',', ' ') . ' DH'
                ];
                break;
        }
        
        fputcsv($output, $data, ';');
    }
    
    // Total
    if (in_array($rapport['type'], ['reservations', 'revenus'])) {
        fputcsv($output, [], ';');
        $totalRow = array_fill(0, count($headers) - 1, '');
        $totalRow[count($headers) - ($rapport['type'] === 'reservations' ? 2 : 3)] = 'TOTAL';
        $totalRow[count($headers) - 1] = number_format($total, 2, ',', ' ') . ' DH';
        fputcsv($output, $totalRow, ';');
    }
    
    // Pied de page
    fputcsv($output, [], ';');
    fputcsv($output, ['Document généré automatiquement par le système de gestion de voyages'], ';');
    fputcsv($output, ['Agence Maroc Tour - ' . date('Y')], ';');
    
    fclose($output);
    exit;
}

include('../includes/header.php');
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Génération de Rapports - Maroc Tour</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <style>
    /* Variables CSS - Palette bleue uniquement */
    :root {
        --primary-50: #eff6ff;
        --primary-100: #dbeafe;
        --primary-200: #bfdbfe;
        --primary-300: #93c5fd;
        --primary-400: #60a5fa;
        --primary-500: #3b82f6;
        --primary-600: #2563eb;
        --primary-700: #1d4ed8;
        --primary-800: #1e40af;
        --primary-900: #1e3a8a;

        --slate-50: #f8fafc;
        --slate-100: #f1f5f9;
        --slate-200: #e2e8f0;
        --slate-300: #cbd5e1;
        --slate-400: #94a3b8;
        --slate-500: #64748b;
        --slate-600: #475569;
        --slate-700: #334155;
        --slate-800: #1e293b;
        --slate-900: #0f172a;

        --shadow-sm: 0 1px 2px 0 rgba(59, 130, 246, 0.05);
        --shadow-md: 0 4px 6px -1px rgba(59, 130, 246, 0.1), 0 2px 4px -1px rgba(59, 130, 246, 0.06);
        --shadow-lg: 0 10px 15px -3px rgba(59, 130, 246, 0.1), 0 4px 6px -2px rgba(59, 130, 246, 0.05);
        --shadow-xl: 0 20px 25px -5px rgba(59, 130, 246, 0.1), 0 10px 10px -5px rgba(59, 130, 246, 0.04);
    }

    /* Animations */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes slideInLeft {
        from {
            opacity: 0;
            transform: translateX(-30px);
        }

        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    @keyframes pulse {

        0%,
        100% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.02);
        }
    }

    @keyframes shimmer {
        0% {
            transform: translateX(-100%);
        }

        100% {
            transform: translateX(100%);
        }
    }

    /* Layout principal */
    body {
        background: linear-gradient(135deg, var(--slate-50) 0%, var(--primary-50) 100%);
        color: var(--slate-800);
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        min-height: 100vh;
    }

    .report-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 30px 20px;
        animation: fadeIn 0.8s ease;
    }

    /* En-tête de page */
    .page-header {
        background: linear-gradient(135deg, var(--primary-600) 0%, var(--primary-700) 100%);
        color: white;
        border-radius: 16px;
        padding: 40px;
        margin-bottom: 40px;
        position: relative;
        overflow: hidden;
        box-shadow: var(--shadow-xl);
    }

    .page-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.1'%3E%3Ccircle cx='30' cy='30' r='2'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        animation: shimmer 20s linear infinite;
    }

    .page-title {
        font-size: 2.5rem;
        font-weight: 700;
        margin: 0 0 15px 0;
        position: relative;
        z-index: 2;
    }

    .page-subtitle {
        font-size: 1.2rem;
        opacity: 0.9;
        margin: 0;
        position: relative;
        z-index: 2;
    }

    .breadcrumb-nav {
        position: absolute;
        top: 20px;
        right: 30px;
        z-index: 2;
    }

    .btn-back {
        background: rgba(255, 255, 255, 0.15);
        border: 1px solid rgba(255, 255, 255, 0.3);
        color: white;
        padding: 10px 20px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
        backdrop-filter: blur(10px);
    }

    .btn-back:hover {
        background: rgba(255, 255, 255, 0.25);
        color: white;
        transform: translateY(-2px);
    }

    /* Cartes principales */
    .report-card {
        background: white;
        border-radius: 16px;
        box-shadow: var(--shadow-lg);
        padding: 40px;
        margin-bottom: 30px;
        border: 1px solid var(--slate-200);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .report-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, var(--primary-500), var(--primary-400));
    }

    .report-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-xl);
    }

    /* Sections du formulaire */
    .form-section {
        background: linear-gradient(135deg, var(--primary-50) 0%, var(--slate-50) 100%);
        border-radius: 12px;
        padding: 30px;
        margin-bottom: 30px;
        border: 1px solid var(--primary-200);
        position: relative;
        animation: slideInLeft 0.6s ease;
    }

    .section-title {
        font-size: 1.4rem;
        font-weight: 700;
        color: var(--primary-700);
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .section-title i {
        font-size: 1.3rem;
        color: var(--primary-600);
        padding: 8px;
        background: var(--primary-100);
        border-radius: 8px;
    }

    /* Contrôles de formulaire */
    .form-label {
        font-weight: 600;
        color: var(--slate-700);
        margin-bottom: 8px;
        font-size: 0.95rem;
    }

    .form-control,
    .form-select {
        border: 2px solid var(--slate-200);
        border-radius: 10px;
        padding: 12px 16px;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: white;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: var(--primary-500);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        outline: none;
    }

    .form-control:hover,
    .form-select:hover {
        border-color: var(--primary-400);
    }

    /* Options de format */
    .format-options {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-top: 20px;
    }

    .format-option {
        position: relative;
    }

    .format-option input[type="radio"] {
        display: none;
    }

    .format-option label {
        display: block;
        padding: 25px 20px;
        border: 2px solid var(--slate-200);
        border-radius: 12px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        background: white;
        position: relative;
        overflow: hidden;
    }

    .format-option label::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(59, 130, 246, 0.1), transparent);
        transition: left 0.6s;
    }

    .format-option:hover label::before {
        left: 100%;
    }

    .format-option input[type="radio"]:checked+label {
        border-color: var(--primary-500);
        background: linear-gradient(135deg, var(--primary-50) 0%, white 100%);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        transform: translateY(-2px);
    }

    .format-option i {
        font-size: 2.5rem;
        margin-bottom: 15px;
        display: block;
        color: var(--primary-600);
        transition: all 0.3s ease;
    }

    .format-option input[type="radio"]:checked+label i {
        color: var(--primary-700);
        transform: scale(1.1);
    }

    .format-label {
        font-weight: 700;
        color: var(--slate-700);
        font-size: 1.1rem;
        margin-bottom: 5px;
    }

    .format-description {
        font-size: 0.9rem;
        color: var(--slate-500);
        margin: 0;
    }

    /* Boutons */
    .btn-generate {
        background: linear-gradient(135deg, var(--primary-600) 0%, var(--primary-700) 100%);
        border: none;
        padding: 16px 40px;
        font-weight: 700;
        font-size: 1.1rem;
        border-radius: 12px;
        color: white;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        min-width: 220px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .btn-generate::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.7s;
    }

    .btn-generate:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-lg);
        color: white;
    }

    .btn-generate:hover::before {
        left: 100%;
    }

    .btn-generate:active {
        transform: translateY(-1px);
    }

    /* Carte d'information */
    .info-card {
        background: linear-gradient(135deg, var(--primary-50) 0%, white 100%);
        border: 1px solid var(--primary-200);
        border-radius: 16px;
        padding: 35px;
        position: relative;
    }

    .info-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, var(--primary-500), var(--primary-400));
        border-radius: 16px 16px 0 0;
    }

    .info-card h4 {
        color: var(--primary-700);
        font-weight: 700;
        margin-bottom: 25px;
        font-size: 1.3rem;
    }

    .info-card h5 {
        color: var(--primary-600);
        font-weight: 600;
        margin-bottom: 15px;
        font-size: 1.1rem;
    }

    .info-card ul {
        list-style: none;
        padding: 0;
    }

    .info-card li {
        margin-bottom: 12px;
        position: relative;
        padding-left: 25px;
        color: var(--slate-600);
        line-height: 1.6;
    }

    .info-card li::before {
        content: '▶';
        color: var(--primary-500);
        font-size: 0.8rem;
        position: absolute;
        left: 0;
        top: 2px;
    }

    /* États de validation */
    .is-invalid {
        border-color: var(--slate-400) !important;
        animation: pulse 0.5s ease;
    }

    .invalid-feedback {
        color: var(--slate-600);
        font-size: 0.875rem;
        margin-top: 5px;
    }

    /* Loading state */
    .loading {
        position: relative;
        pointer-events: none;
    }

    .loading::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 20px;
        height: 20px;
        margin: -10px 0 0 -10px;
        border: 2px solid transparent;
        border-top: 2px solid white;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    /* Responsive */
    @media (max-width: 768px) {
        .report-container {
            padding: 20px 15px;
        }

        .page-header {
            padding: 30px 25px;
            text-align: center;
        }

        .page-title {
            font-size: 2rem;
        }

        .breadcrumb-nav {
            position: static;
            margin-top: 20px;
        }

        .report-card {
            padding: 25px 20px;
        }

        .form-section {
            padding: 25px 20px;
        }

        .format-options {
            grid-template-columns: 1fr;
        }

        .btn-generate {
            width: 100%;
            margin-top: 20px;
        }
    }

    @media (max-width: 576px) {
        .page-title {
            font-size: 1.8rem;
        }

        .page-subtitle {
            font-size: 1rem;
        }

        .section-title {
            font-size: 1.2rem;
            flex-direction: column;
            text-align: center;
            gap: 8px;
        }

        .format-option label {
            padding: 20px 15px;
        }

        .format-option i {
            font-size: 2rem;
        }
    }

    /* Animations d'entrée */
    .form-section:nth-child(1) {
        animation-delay: 0.1s;
    }

    .form-section:nth-child(2) {
        animation-delay: 0.3s;
    }

    .info-card {
        animation: fadeIn 0.8s ease 0.5s both;
    }
    </style>
</head>

<body>
    <div class="report-container">
        <!-- En-tête de page -->
        <div class="page-header">
            <div class="breadcrumb-nav">
                <a href="dashboard.php" class="btn-back">
                    <i class="fas fa-arrow-left me-2"></i>Tableau de bord
                </a>
            </div>
            <h1 class="page-title">
                <i class="fas fa-chart-line me-3"></i>Génération de Rapports
            </h1>
            <p class="page-subtitle">Créez des rapports détaillés sur les réservations, revenus et utilisateurs</p>
        </div>

        <!-- Formulaire principal -->
        <div class="report-card">
            <form method="post" id="reportForm" novalidate>
                <!-- Section type de rapport -->
                <div class="form-section">
                    <h4 class="section-title">
                        <i class="fas fa-file-alt"></i>
                        Configuration du Rapport
                    </h4>

                    <div class="row g-4">
                        <div class="col-md-6">
                            <label for="type_rapport" class="form-label">Type de rapport *</label>
                            <select class="form-select" id="type_rapport" name="type_rapport" required>
                                <option value="">-- Sélectionnez un type --</option>
                                <option value="reservations">📋 Rapport des Réservations</option>
                                <option value="revenus">💰 Analyse des Revenus</option>
                                <option value="utilisateurs">👥 Statistiques des Utilisateurs</option>
                            </select>
                            <div class="invalid-feedback">Veuillez sélectionner un type de rapport</div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Format de sortie *</label>
                            <div class="format-options">
                                <div class="format-option">
                                    <input type="radio" id="format-pdf" name="format" value="pdf" checked>
                                    <label for="format-pdf">
                                        <i class="fas fa-file-pdf"></i>
                                        <div class="format-label">PDF</div>
                                        <div class="format-description">Format imprimable</div>
                                    </label>
                                </div>
                                <div class="format-option">
                                    <input type="radio" id="format-excel" name="format" value="excel">
                                    <label for="format-excel">
                                        <i class="fas fa-file-excel"></i>
                                        <div class="format-label">Excel</div>
                                        <div class="format-description">Format CSV</div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section filtres de période -->
                <div class="form-section">
                    <h4 class="section-title">
                        <i class="far fa-calendar-alt"></i>
                        Filtres de Période
                    </h4>

                    <div class="alert alert-info border-0"
                        style="background: linear-gradient(135deg, var(--primary-50) 0%, var(--slate-50) 100%); color: var(--slate-600);">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Optionnel :</strong> Laissez vide pour inclure toutes les données disponibles
                    </div>

                    <div class="row g-4">
                        <div class="col-md-6">
                            <label for="date_debut" class="form-label">Date de début</label>
                            <input type="text" class="form-control datepicker" id="date_debut" name="date_debut"
                                placeholder="JJ/MM/AAAA">
                            <div class="invalid-feedback">Date de début requise si date de fin spécifiée</div>
                        </div>

                        <div class="col-md-6">
                            <label for="date_fin" class="form-label">Date de fin</label>
                            <input type="text" class="form-control datepicker" id="date_fin" name="date_fin"
                                placeholder="JJ/MM/AAAA">
                            <div class="invalid-feedback">Date de fin requise si date de début spécifiée</div>
                        </div>
                    </div>
                </div>

                <!-- Bouton de génération -->
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-generate" id="generateBtn">
                        <i class="fas fa-download me-2"></i>Générer le rapport
                    </button>
                </div>
            </form>
        </div>

        <!-- Guide des rapports -->
        <div class="info-card">
            <h4 class="section-title">
                <i class="fas fa-info-circle"></i>
                Guide des Rapports Disponibles
            </h4>

            <div class="row">
                <div class="col-lg-4 mb-4">
                    <h5>📋 Rapport des Réservations</h5>
                    <ul>
                        <li>Liste complète de toutes les réservations</li>
                        <li>Détails clients et informations de contact</li>
                        <li>Montants et statuts des réservations</li>
                        <li>Filtrage par période personnalisable</li>
                        <li>Calcul automatique des totaux</li>
                    </ul>
                </div>

                <div class="col-lg-4 mb-4">
                    <h5>💰 Analyse des Revenus</h5>
                    <ul>
                        <li>Revenus totaux par voyage</li>
                        <li>Nombre de réservations confirmées</li>
                        <li>Moyenne de revenus par réservation</li>
                        <li>Classement des voyages par performance</li>
                        <li>Analyse de rentabilité détaillée</li>
                    </ul>
                </div>

                <div class="col-lg-4 mb-4">
                    <h5>👥 Statistiques des Utilisateurs</h5>
                    <ul>
                        <li>Base de données complète des clients</li>
                        <li>Historique des réservations par client</li>
                        <li>Montant total dépensé par utilisateur</li>
                        <li>Dates d'inscription et fidélité</li>
                        <li>Segmentation de la clientèle</li>
                    </ul>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-6">
                    <h5>📄 Format PDF</h5>
                    <ul>
                        <li>Mise en page professionnelle et imprimable</li>
                        <li>En-tête avec logo et coordonnées de l'agence</li>
                        <li>Tableaux structurés avec totaux automatiques</li>
                        <li>Signatures des responsables</li>
                        <li>Numérotation et référencement des pages</li>
                    </ul>
                </div>

                <div class="col-md-6">
                    <h5>📊 Format Excel (CSV)</h5>
                    <ul>
                        <li>Compatible avec Excel, Google Sheets, etc.</li>
                        <li>Données structurées pour analyse avancée</li>
                        <li>Facilite la création de graphiques</li>
                        <li>Import facile dans d'autres systèmes</li>
                        <li>Manipulation et tri des données</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/fr.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialisation des datepickers
        flatpickr(".datepicker", {
            dateFormat: "d/m/Y",
            locale: "fr",
            allowInput: true,
            maxDate: "today",
            theme: "light"
        });

        const form = document.getElementById('reportForm');
        const generateBtn = document.getElementById('generateBtn');
        const typeRapport = document.getElementById('type_rapport');
        const dateDebut = document.getElementById('date_debut');
        const dateFin = document.getElementById('date_fin');

        // Validation en temps réel
        function validateField(field, condition, message) {
            if (condition) {
                field.classList.remove('is-invalid');
                field.classList.add('is-valid');
                return true;
            } else {
                field.classList.remove('is-valid');
                field.classList.add('is-invalid');
                return false;
            }
        }

        // Validation du formulaire
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            // Reset des états de validation
            [typeRapport, dateDebut, dateFin].forEach(field => {
                field.classList.remove('is-invalid', 'is-valid');
            });

            let isValid = true;
            let errors = [];

            // Validation du type de rapport
            if (!typeRapport.value) {
                typeRapport.classList.add('is-invalid');
                errors.push('Un type de rapport doit être sélectionné');
                isValid = false;
            } else {
                typeRapport.classList.add('is-valid');
            }

            // Validation des dates
            const hasDateDebut = dateDebut.value.trim() !== '';
            const hasDateFin = dateFin.value.trim() !== '';

            if (hasDateDebut && !hasDateFin) {
                dateFin.classList.add('is-invalid');
                errors.push('La date de fin est requise si la date de début est spécifiée');
                isValid = false;
            } else if (!hasDateDebut && hasDateFin) {
                dateDebut.classList.add('is-invalid');
                errors.push('La date de début est requise si la date de fin est spécifiée');
                isValid = false;
            } else if (hasDateDebut && hasDateFin) {
                // Validation du format et de la logique des dates
                const debut = new Date(dateDebut.value.split('/').reverse().join('-'));
                const fin = new Date(dateFin.value.split('/').reverse().join('-'));

                if (debut > fin) {
                    dateDebut.classList.add('is-invalid');
                    dateFin.classList.add('is-invalid');
                    errors.push('La date de début doit être antérieure à la date de fin');
                    isValid = false;
                } else {
                    dateDebut.classList.add('is-valid');
                    dateFin.classList.add('is-valid');
                }
            }

            if (!isValid) {
                // Affichage des erreurs avec SweetAlert2
                Swal.fire({
                    icon: 'error',
                    title: 'Formulaire incomplet',
                    html: '<div class="text-start"><strong>Veuillez corriger les erreurs suivantes :</strong><ul class="mt-2">' +
                        errors.map(error => `<li>${error}</li>`).join('') +
                        '</ul></div>',
                    confirmButtonColor: '#3b82f6',
                    confirmButtonText: 'Compris'
                });

                // Scroll vers la première erreur
                const firstInvalid = form.querySelector('.is-invalid');
                if (firstInvalid) {
                    firstInvalid.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                    setTimeout(() => firstInvalid.focus(), 300);
                }
                return;
            }

            // Affichage de l'état de chargement
            generateBtn.classList.add('loading');
            generateBtn.disabled = true;
            generateBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Génération en cours...';

            // Soumission du formulaire après un délai pour l'UX
            setTimeout(() => {
                form.submit();
            }, 800);
        });

        // Validation en temps réel des champs
        typeRapport.addEventListener('change', function() {
            if (this.value) {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            }
        });

        [dateDebut, dateFin].forEach(field => {
            field.addEventListener('blur', function() {
                const hasDateDebut = dateDebut.value.trim() !== '';
                const hasDateFin = dateFin.value.trim() !== '';

                // Reset des classes
                dateDebut.classList.remove('is-invalid', 'is-valid');
                dateFin.classList.remove('is-invalid', 'is-valid');

                if (hasDateDebut && hasDateFin) {
                    dateDebut.classList.add('is-valid');
                    dateFin.classList.add('is-valid');
                } else if (hasDateDebut || hasDateFin) {
                    // Une seule date remplie - état neutre
                } else {
                    // Aucune date - état neutre
                }
            });
        });

        // Animation des cartes au scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        // Observer les éléments animés
        document.querySelectorAll('.form-section, .info-card').forEach(el => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(20px)';
            el.style.transition = 'all 0.6s ease';
            observer.observe(el);
        });

        // Effet de survol sur les options de format
        document.querySelectorAll('.format-option label').forEach(label => {
            label.addEventListener('mouseenter', function() {
                if (!this.previousElementSibling.checked) {
                    this.style.transform = 'translateY(-3px)';
                    this.style.boxShadow = '0 8px 25px rgba(59, 130, 246, 0.15)';
                }
            });

            label.addEventListener('mouseleave', function() {
                if (!this.previousElementSibling.checked) {
                    this.style.transform = '';
                    this.style.boxShadow = '';
                }
            });
        });

        // Message de confirmation avant génération
        const originalSubmit = form.onsubmit;
        form.addEventListener('submit', function(e) {
            if (this.checkValidity()) {
                e.preventDefault();

                const typeText = typeRapport.options[typeRapport.selectedIndex].text;
                const formatText = document.querySelector('input[name="format"]:checked').value
                    .toUpperCase();
                const periode = dateDebut.value && dateFin.value ?
                    ` du ${dateDebut.value} au ${dateFin.value}` :
                    ' (toutes périodes)';

                Swal.fire({
                    title: 'Confirmer la génération',
                    html: `
                        <div class="text-start">
                            <p><strong>Type :</strong> ${typeText}</p>
                            <p><strong>Format :</strong> ${formatText}</p>
                            <p><strong>Période :</strong> ${periode}</p>
                        </div>
                    `,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3b82f6',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Générer',
                    cancelButtonText: 'Annuler'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Procéder à la génération
                        generateBtn.classList.add('loading');
                        generateBtn.disabled = true;
                        generateBtn.innerHTML =
                            '<i class="fas fa-spinner fa-spin me-2"></i>Génération en cours...';

                        setTimeout(() => {
                            this.submit();
                        }, 500);
                    }
                });
            }
        });

        // Réinitialisation du bouton si l'utilisateur revient sur la page
        window.addEventListener('pageshow', function() {
            generateBtn.classList.remove('loading');
            generateBtn.disabled = false;
            generateBtn.innerHTML = '<i class="fas fa-download me-2"></i>Générer le rapport';
        });
    });
    </script>

    <?php include('../includes/footer.php'); ?>
</body>

</html>