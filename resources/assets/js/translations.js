const localeStrings = {
    "it": {
        "Picture link": "Link immagine",
        "Upload picture": "Carica immagine",
        "Remove picture": "Rimuovi immagine",
        "The chosen picture is not valid.": "L'immagine selezionata non è valida",
        "No picture has been chosen": "Nessuna immagine è stata selezionata."
    }
};

export default function __(string) {
    if(localeStrings[window.locale] && localeStrings[window.locale][string])
        return localeStrings[window.locale][string];
    else
        return string;
};