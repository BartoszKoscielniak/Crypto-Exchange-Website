# Projekt zaliczeniowy z przedmiotu: _**Aplikacje internetowe**_

# Temat projektu: Strona do tradowania kryptowalut
## Skład grupy: Łukasz Matusik, Bartosz Kościelniak
## Specyfikacja projektu
### Cel projektu :
#### Cele szczegółowe:
   1. Stworzenie aplikacji tradeingowej kryptowalut
   2. Nauka podstawowych działan na giełdach    
### Funkcjonalności:
   1. Monitorowanie zmian kursu kryptowalut
   2. Funkcja trade'owania kryptowalutami
   3. Wgląd do portfela 
   4. Przesyłanie między portfelami 
   5. Kupowano/sprzedaż walut
### Interfejs serwisu

   <details>
       <summary>Strona głowna</summary>

   </details>
	<details>
       <summary>Portfel</summary>

   </details>
	<details>
       <summary>Wykres cen kryptowaluty</summary>

   </details>
         
### Baza danych
####	Diagram ERD
![alt text](https://user-images.githubusercontent.com/47026027/110671886-54737c00-81cf-11eb-875a-8749cd770164.PNG)

####	Skrypt do utworzenia struktury bazy danych

CREATE TABLE "Użytkownicy" (
"id_użytkownika  PK" <type>,
"id_portfela  FK" <type>,
"imię" <type>,
"nazwisko" <type>,
"nr_telefonu" <type>,
"adres_email" <type>,
"login" <type>,
"hasło" <type>
);

CREATE TABLE "Portfel" (
"id_portfela  PK" <type>,
"id_listy  FK" <type>
);

CREATE TABLE "Kryptowaluty" (
"id_krypto  PK" <type>,
"nazwa" <type>,
"kurs" <type>
);

CREATE TABLE "Lista_walut" (
"id_listy  PK" <type>,
"id_portfela FK" <type>,
"id_krypto  FK" <type>,
"ilość_euro" <type>,
"ilość_krypto " <type>,
"nazwa" <type>
); 


## Wykorzystane technologie

* HTML
* JavaScript
* PHP
* CSS

## Proces uruchomienia aplikacji (krok po kroku)
*
### Potrzebne nazwy użytkowników do uruchomienia aplikacji
*

