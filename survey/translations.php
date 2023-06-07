<?php

switch ($config["language"]) {
    case "en":
        $translations["requirement_note"] = "Answers to questions marked with * are required.";
        $translations["submit_button"] = "Submit";
        $translations["errors"]["required"] = "This field is required.";
        $translations["errors"]["max_length"] = "This field can't be longer than %s characters.";
        $translations["errors"]["min_length"] = "This field can't be shorter than %s characters.";
        $translations["errors"]["invalid_option"] = "Invalid option.";
        $translations["errors"]["min_count"] = "You have to select at least %s options.";
        $translations["errors"]["max_count"] = "You can't select more than %s options.";
        $translations["errors"]["invalid_date"] = "Invalid date.";
        $translations["errors"]["min_date"] = "Date must be after %s.";
        $translations["errors"]["max_date"] = "Date must be before %s.";
        $translations["errors"]["invalid_time"] = "Invalid time.";
        $translations["errors"]["min_time"] = "Time must be after %s.";
        $translations["errors"]["max_time"] = "Time must be before %s.";
        $translations["errors"]["invalid_number"] = "Invalid value.";
        $translations["errors"]["min_number"] = "Value must be greater than %s.";
        $translations["errors"]["max_number"] = "Value must be less than %s.";
        $translations["thankyou"]["title"] = "Thank you for your participation!";
        break;
    case 'de':
        $translations["requirement_note"] = "Antworten auf mit * markierte Fragen sind erforderlich.";
        $translations["submit_button"] = "Absenden";
        $translations["errors"]["required"] = "Dieses Feld ist erforderlich.";
        $translations["errors"]["max_length"] = "Dieses Feld darf nicht laenger als %s Zeichen sein.";
        $translations["errors"]["min_length"] = "Dieses Feld darf nicht kuerzer als %s Zeichen sein.";
        $translations["errors"]["invalid_option"] = "Ungueltige Option.";
        $translations["errors"]["min_count"] = "Sie muessen mindestens %s Optionen auswaehlen.";
        $translations["errors"]["max_count"] = "Sie koennen nicht mehr als %s Optionen auswaehlen.";
        $translations["errors"]["invalid_date"] = "Ungueltiges Datum.";
        $translations["errors"]["min_date"] = "Datum muss nach %s sein.";
        $translations["errors"]["max_date"] = "Datum muss vor %s sein.";
        $translations["errors"]["invalid_time"] = "Ungueltige Zeit.";
        $translations["errors"]["min_time"] = "Zeit muss nach %s sein.";
        $translations["errors"]["max_time"] = "Zeit muss vor %s sein.";
        $translations["errors"]["invalid_number"] = "Ungueltiger Wert.";
        $translations["errors"]["min_number"] = "Wert muss groesser als %s sein.";
        $translations["errors"]["max_number"] = "Wert muss kleiner als %s sein.";
        $translations["thankyou"]["title"] = "Vielen Dank f&uuml;r Ihre Teilnahme!";
        break;
}
