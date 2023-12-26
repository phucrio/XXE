<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>XXE Vulnerable</title>
</head>
<body>

<h1>XXE Vulnerable</h1>

<form action="" method="post" enctype="multipart/form-data">
    <label for="xmlFile">Choose XML file:</label>
    <input type="file" name="xmlFile" id="xmlFile" accept=".xml">
    <button type="submit">Upload and Parse</button>
</form>

<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['xmlFile']) && $_FILES['xmlFile']['error'] === UPLOAD_ERR_OK) {
        $xmlData = file_get_contents($_FILES['xmlFile']['tmp_name']);

        libxml_disable_entity_loader(false);
        $dom = new DOMDocument;
        $dom->loadXML($xmlData, LIBXML_NOENT | LIBXML_DTDLOAD);

        $students = simplexml_import_dom($dom);

        echo '<h2>Student Information:</h2>';
        echo '<table border="1">';
        echo '<tr><th>Name</th><th>Birth Year</th><th>School</th></tr>';

        foreach ($students->student as $student) {
            echo '<tr>';
            echo '<td>' . $student->name . '</td>';
            echo '<td>' . $student->birth_year . '</td>';
            echo '<td>' . $student->school . '</td>';
            echo '</tr>';
        }

        echo '</table>';
    } else {
        echo '<p>Error uploading the XML file.</p>';
    }
}
?>

</body>
</html>
