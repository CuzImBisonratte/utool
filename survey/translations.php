<?php

switch ($config["language"]) {
    case "en":
        $translations["requirement_note"] = "Answers to questions marked with * are required.";
        $translations["submit_button"] = "Submit";
        break;
    case 'de':
        $translations["requirement_note"] = "Antworten auf mit * markierte Fragen sind erforderlich.";
        $translations["submit_button"] = "Absenden";
        break;
}
