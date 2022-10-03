<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<!doctype html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Klient</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
    <script>
        function formatJson() {
            var element = $("#myDiv");
            var obj = JSON.parse(element.text());
            element.html(JSON.stringify(obj, undefined, 2));
        }
        $(document).ready(function(){
            $("#myPOST").click(function(){
                $.ajax({
                    type: 'POST',
                    url: 'https://site15.webte.fei.stuba.sk/api/osoby/',
                    data: '{"meno":"Miroslav","vek":"22","pohlavie":"M","opis":"student"}',
                    success: function(msg){
                        $("#myDiv").html(msg);    }});
            });
            $("#postInvention").click(function(){
                const id = $('#id').val();
                $.ajax({
                    type: 'POST',
                    url: 'https://site153.webte.fei.stuba.sk/zadanie5/inventors/'+id,
                    success: function (data, textStatus, xhr) {
                        data = JSON.stringify(data);
                        $("#myDiv").html(data);
                    },
                    error: function (xhr, textStatus, errorThrown) {
                        console.log(errorThrown);
                    }
                });
            });
            $("#deleteById").click(function(){
                const id = $('#id').val();
                $.ajax({
                    type: 'DELETE',
                    url: 'https://site15.webte.fei.stuba.sk/zadanie5/inventors/'+id,
                    success: function (data, textStatus, xhr) {
                        data = JSON.stringify(data);
                        $("#myDiv").html(data);
                    },
                    error: function (xhr, textStatus, errorThrown) {
                        console.log(errorThrown);
                    }
                });
            });
            $("#findById").click(function(){
                const id = $('#id').val();
                $.ajax({
                    type: 'GET',
                    url: 'https://site153.webte.fei.stuba.sk/zadanie5/inventors/'+id,
                    success: function (data, textStatus, xhr) {
                        document.querySelector('#myDiv').innerHTML = JSON.stringify(data,null,1)
                            .replace(/\n( *)/g, function (match, p1) {
                                return '<br>'.repeat(p1.length);
                            });
                    },
                    error: function (xhr, textStatus, errorThrown) {
                        console.log(errorThrown);
                    }
                    });
            });
            $("#findBySurname").click(function(){
                const id = $('#surname').val();
                $.ajax({
                    type: 'GET',
                    url: 'https://site153.webte.fei.stuba.sk/zadanie5/inventors/surname/'+ id,
                    success: function (data, textStatus, xhr) {
                        document.querySelector('#myDiv').innerHTML = JSON.stringify(data,null,1)
                            .replace(/\n( *)/g, function (match, p1) {
                                return '<br>'.repeat(p1.length);
                            });
                    },
                    error: function (xhr, textStatus, errorThrown) {
                        console.log(errorThrown);
                    }
                });
            });
            $("#findByCentury").click(function(){
                const id = $('#century').val();
                $.ajax({
                    type: 'GET',
                    url: 'https://site153.webte.fei.stuba.sk/zadanie5/inventors/century/'+ id,
                    success: function (data, textStatus, xhr) {
                        document.querySelector('#myDiv').innerHTML = JSON.stringify(data,null,1)
                            .replace(/\n( *)/g, function (match, p1) {
                                return '<br>'.repeat(p1.length);
                            });
                    },
                    error: function (xhr, textStatus, errorThrown) {
                        console.log(errorThrown);
                    }
                });
            });
            $("#getAll").click(function(){
                $.ajax({
                    type: 'GET',
                    url: 'https://site153.webte.fei.stuba.sk/zadanie5/inventors',
                    success: function (data, textStatus, xhr) {
                        document.querySelector('#myDiv').innerHTML = JSON.stringify(data,null,1)
                            .replace(/\n( *)/g, function (match, p1) {
                                return '<br>'.repeat(p1.length);
                            });
                    },
                    error: function (xhr, textStatus, errorThrown) {
                        console.log(errorThrown);
                    }
                });
            });
            $("#findByYear").click(function(){
                const id = $('#year').val();
                $.ajax({
                    type: 'GET',
                    url: 'https://site153.webte.fei.stuba.sk/zadanie5/inventors/year/'+id,
                    success: function (data, textStatus, xhr) {
                        document.querySelector('#myDiv').innerHTML = JSON.stringify(data,null,1)
                            .replace(/\n( *)/g, function (match, p1) {
                                return '<br>'.repeat(p1.length);
                            });
                    },
                    error: function (xhr, textStatus, errorThrown) {
                        console.log(errorThrown);
                    }
                });
            });
        });
        $(formatJson);
    </script>
</head>
<body>
<div class="grid-container">
    <div>
        <h2>Hladaj Podla ID</h2>
        <label for="id">Zadaj id</label>
        <input type="text" id="id">
        <button class="button-33" id="findById">Hladaj</button>
        <button class="button-33" id="deleteById">Vymaz</button>
        <button class="button-33" id="getAll">Vsetci vynalezcovia</button>
    </div>
    <div>
        <h2>Hladaj podla priezviska</h2>
        <label for="surname">Priezvisko</label>
        <input name="surname" id="surname" type="text">
        <button class="button-33" id="findBySurname">Hladaj</button>
        <br>
        <h2>Hladaj podla storocia</h2>
        <label for="century">Storocie</label>
        <input id="century" name="century" type="number">
        <button class="button-33" id="findByCentury">Hladaj</button>
        <br>
        <h2>Hladaj udalost podla roku</h2>
        <label for="year">Rok</label>
        <input id="year" name="year" type="number">
        <button class="button-33" id="findByYear">Hladaj</button>
    </div>
    <div>
        <h2>Pridaj vynalezcu</h2>
        <form action="inventors/" method="post">
            <div>
                <label for="name">Meno:</label>
                <input id="name" name="name" type="text">
                <br>
                <label for="lastName">Priezvisko:</label>
                <input id="lastName" name="lastName" type="text">
                <br>
                <label for="birthDate">Narodenie:</label>
                <input id="birthDate" name="birthDate" type="date">
                <br>
                <label for="birthPlace">Miesto narodenia:</label>
                <input id="birthPlace" name="birthPlace" type="text">
                <br>
                <label for="description">Popis:</label>
                <input id="description" name="description" type="text">
                <br>
                <label for="deathDate">Umrtie:</label>
                <input id="deathDate" name="deathDate" type="date">
                <br>
                <label for="deathPlace">Miesto umrtia:</label>
                <input id="deathPlace" name="deathPlace" type="text">
                <br>
            </div>
            <div>
                <label for="inventionDate">Datum vynajdenia:</label>
                <input id="inventionDate" name="inventionDate" type="date">
                <br>
                <label for="desc">Popis vynalezu:</label>
                <input id="desc" name="desc" type="text">
            </div>
            <button class="button-33" id="postInventor">Nahraj</button>
        </form>
    </div>
    <div>
        <h2>Pridaj vynalez</h2>
        <form action="inventors/" method="post">
            <label for="inv_id">ID vynalezcu:</label>
            <input id="inv_id" name="inv_id" type="number">
            <br>
            <label for="inv_desc">Popis vynalezu:</label>
            <input id="inv_desc" name="inv_desc" type="text">
            <br>
            <label for="inv_date">Datum vynajdenia:</label>
            <input id="inv_date" name="inv_date" type="date">
            <br>
            <button class="button-33" id="postInvention">Pridaj Vynalez</button>
        </form>

    </div>
</div>

<pre id="myDiv">
</pre>
</body>
</html>