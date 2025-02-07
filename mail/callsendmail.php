<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

include_once($_SERVER["DOCUMENT_ROOT"] . '/mail/sendmail.php');
    define("SMTP_USER", "senderMail@gmail.com"); //Modificalo
    define("SMTP_PASS", "passwordSMTP"); //Modificalo

// Manejo de solicitudes OPTIONS (preflight)
if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    http_response_code(200);
    exit();
}

$response = ["success" => false, "message" => "Error en el envío"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $recaptchaSecretKey = "secretkeycaptcha"; //Modificalo
    $recaptchaResponse = $_POST["g-recaptcha-response"];

    // Validar el captcha con Google
    $url = "https://www.google.com/recaptcha/api/siteverify";
    $data = ["secret" => $recaptchaSecretKey, "response" => $recaptchaResponse];

    $options = [
        "http" => [
            "header" => "Content-Type: application/x-www-form-urlencoded",
            "method" => "POST",
            "content" => http_build_query($data)
        ]
    ];

    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    $json = json_decode($result, true);

    if ($json["success"] && $json["score"] >= 0.5) {
        // Si el score es válido, enviar correo
        $address = $_POST["email"];
        $subject = "Consulta Web de: " . $_POST["name"];
        $body = "Consulta Web de: " . $_POST["email"] . "<br>" . $_POST["message"];

        enviar($address, $subject, $body);

        // Enviar respuesta JSON
        $response["success"] = true;
        $response["message"] = "Correo enviado con exito.";
        echo json_encode($response);
        exit();
    } else {
        $response["message"] = "Captcha no válido o puntuación baja.";
    }
}

// Enviar respuesta JSON en caso de error
echo json_encode($response);
exit();
?>

