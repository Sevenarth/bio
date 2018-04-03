const localeStrings = {
    "it": {
        "Picture link": "Link immagine",
        "Upload picture": "Carica immagine",
        "Remove picture": "Rimuovi immagine"
    }
};

export default function __(string) {
    if(localeStrings[window.locale] && localeStrings[window.locale][string])
        return localeStrings[window.locale][string];
    else
        return string;
};