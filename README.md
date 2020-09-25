# Firmao API
Plugin do Wordpressa, który obsługuje API Firmao.

## Wersje
### 1.0.0 [25.09.2020]
* podstawowa konfiguracja API
* obsługa wysyłki kontrahenta poprzez formularz CF7

## Konfiguracja
http://.../wp-admin/options-general.php?page=settings+-+firmao+-+api

## Obsługa
Do obsługi dodawania kontrahentów, formularz musi być wypełniony w sposób poniżej:
```text
<label> Imię i nazwisko *
    [text* your-name] </label>

<label> Twój email *
    [email* your-email] </label>

<label>Telefon *
    [tel* your-telephone] </label>

<label> Opisz swój problem *
    [textarea your-message] </label>

[hidden customer-group "27"]

[submit "Wyślij"]
```
Pole customer-group jest opcjonalne
