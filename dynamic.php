<?php
session_start();

// Database connection
$conn = new mysqli("localhost", "root", "", "cipher_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Load key mappings from database
$key = [];
$result = $conn->query("SELECT letter, substitute FROM susi_tb ORDER BY letter ASC");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $key[$row['letter']] = $row['substitute'];
    }
}

$alert = '';

// Keyboard UI mapping
$number_row = '1234567890';
$rows = ['qwertyuiop', 'asdfghjkl', 'zxcvbnm'];

// Handle form updates
if (isset($_POST['final_update'])) {
    $updated_key = json_decode($_POST['updated_key'] ?? '{}', true);

    if (is_array($updated_key) && !empty($updated_key)) {
        $success = true;
        $duplicates = false;

        // Fetch current substitutes for duplicate checking
        $existing_subs = [];
        $result = $conn->query("SELECT substitute FROM susi_tb");
        while ($row = $result->fetch_assoc()) {
            $existing_subs[] = $row['substitute'];
        }

        foreach ($updated_key as $letter => $substitute) {
            // Check if the substitute already exists in another key
            if (in_array($substitute, $existing_subs) && $key[$letter] != $substitute) {
                $duplicates = true;
                break;
            }
        }

        if ($duplicates) {
            $alert = 'Error: Duplicate substitute detected. Update aborted.';
        } else {
            // Proceed with update
            foreach ($updated_key as $letter => $substitute) {
                $stmt = $conn->prepare("UPDATE susi_tb SET substitute=? WHERE letter=?");
                $stmt->bind_param("ss", $substitute, $letter);
                if (!$stmt->execute()) $success = false;
                $stmt->close();
            }
            $alert = $success ? 'Key updated successfully!' : 'Error updating key.';

            // Reload keys
            $key = [];
            $result = $conn->query("SELECT letter, substitute FROM susi_tb ORDER BY letter ASC");
            while ($row = $result->fetch_assoc()) {
                $key[$row['letter']] = $row['substitute'];
            }
        }

    } else {
        $alert = 'Invalid key data.';
    }
}


$conn->close();
?>

<!DOCTYPE html>

<html lang="en">
<head>
<meta charset="UTF-8">
<title>Dynamic Key Editing</title>
<link rel="stylesheet" href="desayn.css">
</head>
<body>
<div class="container">
    <h1>Dynamic Key Editing</h1>


<?php if ($alert): ?>
<div class="alert" style="background:#ffe4e1; border:1px solid #ff6b6b; color:#b22222; font-weight:bold; padding:10px; border-radius:10px; margin-bottom:15px;">
    <?php echo htmlspecialchars($alert); ?>
</div>
<?php endif; ?>

<div class="module">
    <h2>Key Mapping Grid</h2>
    <form id="dynamicForm" method="post" onsubmit="return populateUpdatedKey();">
        <div class="keyboard">
            <?php foreach ($rows as $r): ?>
            <div class="row">
                <?php foreach (str_split($r) as $letter):
                    $sub = $key[$letter] ?? '';
                ?>
                <div class="key" onclick="editKey('<?php echo $letter; ?>')">
                    <strong class="letter"><?php echo $letter; ?></strong>
                    <span id="sub_<?php echo $letter; ?>" class="sub"><?php echo htmlspecialchars($sub); ?></span>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endforeach; ?>

            <div class="row">
                <?php foreach (str_split($number_row) as $num):
                    $sub = $key[$num] ?? '';
                ?>
                <div class="key" onclick="editKey('<?php echo $num; ?>')">
                    <strong class="letter"><?php echo $num; ?></strong>
                    <span id="sub_<?php echo $num; ?>" class="sub"><?php echo htmlspecialchars($sub); ?></span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div id="editor" style="display:none;">
            <h3>Edit Key</h3>
            <p>Selected Letter: <strong id="editLetter"></strong></p>
            <input type="text" id="editValue" maxlength="1">
            <button type="button" onclick="applyChange()">Apply</button>
        </div>

        <input type="hidden" name="updated_key" id="updated_key">
        <button name="final_update">Update All</button>
    </form>
     <div class="module">
            <div class="nav" role="navigation" aria-label="Main navigation">

                <a class="nav-btn encrypt" href="enkrip.php" title="Encrypt">
                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <rect x="3" y="11" width="18" height="10" rx="2"></rect>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                    </svg>
                    <span>Encrypt</span>
                </a>    
                <a class="nav-btn decrypt" href="dekrip.php" title="Decrypt">
                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path d="M21 2l-7 7" />
                        <circle cx="7" cy="17" r="4" />
                    </svg>
                    <span>Decrypt</span>
                </a>
            </div>
</div>


</div>

<script src="skrip.js"></script>

<script>
let keyData = <?php echo json_encode($key); ?>;

function populateUpdatedKey() {
    const updatedKey = {};
    for (const letter in keyData) {
        const valEl = document.getElementById('sub_' + letter);
        updatedKey[letter] = valEl ? valEl.innerText : '';
    }
    document.getElementById('updated_key').value = JSON.stringify(updatedKey);
    return true;
}
</script>

</body>
</html>
