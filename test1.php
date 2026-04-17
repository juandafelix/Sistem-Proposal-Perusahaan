//nomor1
<?php
$aff_commission = [
    ['commission_id' => 1, 'aff_id' => 48, 'amount' => 50000, 'payout_detail_id' => null,  'is_voided' => 0],
    ['commission_id' => 2, 'aff_id' => 48, 'amount' => 25000, 'payout_detail_id' => 2,     'is_voided' => 0],
    ['commission_id' => 3, 'aff_id' => 48, 'amount' => 10000, 'payout_detail_id' => 1,     'is_voided' => 0],
    ['commission_id' => 4, 'aff_id' => 49, 'amount' => 40000, 'payout_detail_id' => 3,     'is_voided' => 0],
    ['commission_id' => 5, 'aff_id' => 49, 'amount' => 60000, 'payout_detail_id' => 3,     'is_voided' => 0],
    ['commission_id' => 6, 'aff_id' => 50, 'amount' => 75000, 'payout_detail_id' => 4,     'is_voided' => 0],
    ['commission_id' => 7, 'aff_id' => 51, 'amount' => 90000, 'payout_detail_id' => null,  'is_voided' => 0],
    ['commission_id' => 8, 'aff_id' => 52, 'amount' => 20000, 'payout_detail_id' => null,  'is_voided' => 0],
    ['commission_id' => 9, 'aff_id' => 52, 'amount' => 15000, 'payout_detail_id' => null,  'is_voided' => 1],
];

$aff_payout_detail = [
    ['payout_detail_id' => 1, 'payout_id' => 1, 'aff_id' => 48, 'amount' => 10000, 'is_paid' => 1],
    ['payout_detail_id' => 2, 'payout_id' => 2, 'aff_id' => 48, 'amount' => 25000, 'is_paid' => 0],
    ['payout_detail_id' => 3, 'payout_id' => 3, 'aff_id' => 49, 'amount' => 100000,'is_paid' => 1],
    ['payout_detail_id' => 4, 'payout_id' => 3, 'aff_id' => 50, 'amount' => 75000, 'is_paid' => 1],
];
$payout_lookup = [];
foreach ($aff_payout_detail as $pd) { $payout_lookup[$pd['payout_detail_id']] = $pd['is_paid'];
}

$belum_dibayar = [];

foreach ($aff_commission as $row) {
    if ($row['is_voided'] == 1) continue; 

    $is_paid_status = 0;

    if ($row['payout_detail_id'] !== null) {
        $detail_id = $row['payout_detail_id'];
        $is_paid_status = isset($payout_lookup[$detail_id]) ? $payout_lookup[$detail_id] : 0;
    }

    if ($row['payout_detail_id'] === null || $is_paid_status == 0) {
        $aff = $row['aff_id'];
        if (!isset($belum_dibayar[$aff])) {
            $belum_dibayar[$aff] = 0;
        }
        $belum_dibayar[$aff] += $row['amount'];
    }
}

echo "total komisi belum dibayar per affiliate \n";
foreach ($belum_dibayar as $aff_id => $total) {
    echo "aff_id $aff_id : Rp " . number_format($total, 0, ',', '.') . "\n";
}








// nomor 2
$semua_aff = [];
foreach ($aff_commission as $row) {
    $semua_aff[$row['aff_id']] = true;
}

$sudah_dibayar = [];
foreach ($aff_commission as $row) {
    if ($row['payout_detail_id'] === null) continue;

    $detail_id = $row['payout_detail_id'];
    if (isset($payout_lookup[$detail_id]) && $payout_lookup[$detail_id] == 1) {
        $sudah_dibayar[$row['aff_id']] = true;
    }
}

$belum_pernah = array_diff_key($semua_aff, $sudah_dibayar);

echo "\n affiliate yang belum pernah menerima ppayout \n";
foreach (array_keys($belum_pernah) as $aff_id) {
    echo "aff_id: $aff_id\n";
}






//nomor 3
$komisi_48 = array_filter($aff_commission, function($row) {
    return $row['aff_id'] == 48;
});
usort($komisi_48, function($a, $b) {
    return $b['commission_id'] - $a['commission_id'];
});

$lima_terakhir = array_slice($komisi_48, 0, 5);
echo "\n 5 Komisi Terakhir aff_id = 48 \n";
printf("%-16s %-10s %-20s %-10s\n", "commission_id", "amount", "payout_detail_id", "is_voided");
echo str_repeat("-", 58) . "\n";
foreach ($lima_terakhir as $row) {
    $pdd = $row['payout_detail_id'] ?? 'NULL';
    printf("%-16s %-10s %-20s %-10s\n",
        $row['commission_id'],
        number_format($row['amount'], 0, ',', '.'),
        $pdd,
        $row['is_voided']
    );
}






//NOMOR 4
$total_dibayar    = 0;
$total_belum      = 0;

foreach ($aff_commission as $row) {
    if ($row['is_voided'] == 1) continue;
    if ($row['payout_detail_id'] !== null
        && isset($payout_lookup[$row['payout_detail_id']])
        && $payout_lookup[$row['payout_detail_id']] == 1
    ) {
        $total_dibayar += $row['amount'];
    } else {
        $total_belum   += $row['amount'];
    }
}

echo "\n ringkasan total koomisi\n";
echo "Total dibayar    : Rp " . number_format($total_dibayar, 0, ',', '.') . "\n";
echo "Total belum bayar: Rp " . number_format($total_belum,   0, ',', '.') . "\n";



//NOMOR 5
echo '<table border="1" cellpadding="8" cellspacing="0">';
echo '<thead><tr>
        <th>aff_id</th>
        <th>total_dibayar</th>
        <th>total_belum_dibayar</th>
    </tr></thead><tbody>';

$semua_aff_ids = array_unique(array_column($aff_commission, 'aff_id'));
foreach ($semua_aff_ids as $aff_id) {
    $dibayar = 0;
    $belum   = 0;
    foreach ($aff_commission as $row) {
        if ($row['aff_id'] != $aff_id) continue;
        if ($row['is_voided'] == 1)    continue;

        $detail_id = $row['payout_detail_id'];

        if ($detail_id !== null
            && isset($payout_lookup[$detail_id])
            && $payout_lookup[$detail_id] == 1
        ) {
            $dibayar += $row['amount'];
        } else {
            $belum   += $row['amount'];
        }
    }
    echo "<tr>
            <td>$aff_id</td>
            <td>Rp " . number_format($dibayar, 0, ',', '.') . "</td>
            <td>Rp " . number_format($belum,   0, ',', '.') . "</td>
        </tr>";
}
echo '</tbody></table>';
