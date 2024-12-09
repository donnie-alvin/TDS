<?php
// Array of doctors with their details
$doctors = [
    ['name' => 'Dr. Tendai Moyo', 'email' => 'tendai.moyo@example.com'],
    ['name' => 'Dr. Nyasha Chikwanje', 'email' => 'nyasha.chikwanje@example.com'],
    ['name' => 'Dr. Chipo Ndlovu', 'email' => 'chipo.ndlovu@example.com'],
    ['name' => 'Dr. Farai Mavhunga', 'email' => 'farai.mavhunga@example.com'],
    ['name' => 'Dr. Rudo Mupfumi', 'email' => 'rudo.mupfumi@example.com'],
];

echo "<pre>\n";
foreach ($doctors as $doctor) {
    $email = $doctor['email'];
    $password = substr($email, 0, strpos($email, '@')); // Use part before @ as password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    echo "INSERT INTO doctors (name, email, password, specialization, location, hospital, description, picture) VALUES\n";
    echo "('{$doctor['name']}', '{$email}', '{$hashed_password}', 'Specialization', 'Location', 'Hospital', 'Description', 'images/doctor.png');\n";
}
echo "</pre>\n";

echo "<p>If you can see this, the script has run successfully.</p>";
?>
