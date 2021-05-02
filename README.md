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
![alt text](baza.png)

####	Skrypt do utworzenia struktury bazy danych
CREATE TABLE Kryptowaluty (
  id_krypto INT NOT NULL,
  nazwa VARCHAR(255) NOT NULL,
  kurs FLOAT(24),
  PRIMARY KEY (id_krypto)
);

CREATE TABLE Kryptowaluty ( id_krypto INT NOT NULL, nazwa VARCHAR(255) NOT NULL, kurs FLOAT(24), PRIMARY KEY (id_krypto) );

CREATE TABLE Użytkownicy ( id_użytkownika INT NOT NULL, imię VARCHAR(255) NOT NULL, nazwisko VARCHAR(255) NOT NULL, nr_telefonu INT NOT NULL, adres_email VARCHAR(255) NOT NULL, haslo VARCHAR(255) NOT NULL, PRIMARY KEY (id_użytkownika) );

CREATE TABLE Portfele ( id_portfela INT NOT NULL, id_użytkownika INT NOT NULL, ilość_euro INT NOT NULL, PRIMARY KEY (id_portfela), FOREIGN KEY (id_użytkownika) REFERENCES Użytkownicy(id_użytkownika) );

CREATE TABLE Lista_walut ( id_listy INT NOT NULL, id_portfela INT NOT NULL ,id_krypto INT, ilość_krypto INT NOT NULL, PRIMARY KEY (id_listy), FOREIGN KEY (id_krypto) REFERENCES Kryptowaluty(id_krypto), FOREIGN KEY (id_portfela) REFERENCES Portfele(id_portfela) );

CREATE TABLE Transakcje ( id_transakcji INT NOT NULL, id_krypto INT NOT NULL, id_portfela INT NOT NULL, data_transakcji DATE NOT NULL, czas_zawarcia TIME NOT NULL, ilosc INT NOT NULL, status VARCHAR(255) NOT NULL, PRIMARY KEY (id_transakcji), FOREIGN KEY (id_krypto) REFERENCES Kryptowaluty(id_krypto), FOREIGN KEY (id_portfela) REFERENCES Portfele (id_portfela) );

## Wykorzystane technologie

* HTML
* JavaScript
* PHP
* CSS

## Proces uruchomienia aplikacji (krok po kroku)
*
### Potrzebne nazwy użytkowników do uruchomienia aplikacji
*

