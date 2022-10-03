<?php
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/

require_once "MyPDO.php";
require_once "Word.php";
require_once "Translations.php";

$myPdo = new MyPDO("mysql:host=localhost;dbname=glosar","xsedlacekp","VsFfwZsyaPVOUSG");


 if (isset($_FILES['file'])){
     $file = fopen($_FILES['file']['tmp_name'],'r');
     while (!feof($file)){
         $pole = fgetcsv($file,null,';');
         if ($pole[0]){
             $word = new Word($myPdo);
             $word->setTitle($pole[2]);
             $word->save();

             $slovakTranslation = new Translations($myPdo);
             $slovakTranslation->setTitle($pole[2]);
             $slovakTranslation->setLanguageId(1);
             $slovakTranslation->setDescription($pole[3]);
             $slovakTranslation->setWordId($word->getId());
             $slovakTranslation->save();

             $englishTranslation = new Translations($myPdo);
             $englishTranslation->setTitle($pole[0]);
             $englishTranslation->setLanguageId(2);
             $englishTranslation->setDescription($pole[1]);
             $englishTranslation->setWordId($word->getId());
             $englishTranslation->save();
         }

     }

 }

if (isset($_POST['title_sk']) && isset($_POST['desc_sk']) && isset($_POST['title_en']) && isset($_POST['desc_en'])){
    $word = new Word($myPdo);
    $word->setTitle($_POST['title_sk']);
    $word->save();

    $slovakTranslation = new Translations($myPdo);
    $slovakTranslation->setTitle($_POST['title_sk']);
    $slovakTranslation->setLanguageId(1);
    $slovakTranslation->setDescription($_POST['desc_sk']);
    $slovakTranslation->setWordId($word->getId());
    $slovakTranslation->save();

    $englishTranslation = new Translations($myPdo);
    $englishTranslation->setTitle($_POST['title_en']);
    $englishTranslation->setLanguageId(2);
    $englishTranslation->setDescription($_POST['desc_en']);
    $englishTranslation->setWordId($word->getId());
    $englishTranslation->save();


}
?>
<!doctype html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Glosar</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<a href="index.php">
    <button class="button-33" role="button">Domov</button>
</a>
    <h1>Stránka administrátora</h1>
<div class="grid-container">
    <div class="grid-child1">
        <form action="admin.php" method="post" enctype="multipart/form-data">
            <div class="upload">
                <h2>Nahratie .csv súboru</h2>
                <div>
                    <label for="file">Súbor:</label>
                    <input type="file" name="file" id="file" class="button-33" required>
                </div>
                <div>
                    <input type="submit" value="Upload" class="button-33" id="uploadBtn">
                </div>
            </div>
        </form>
        <div class="form-style-2">
            <h2>Formulár na pridanie nového pojmu</h2>
            <form action="admin.php" method="post">
                <label for="title_sk"><span>Pojem <span class="required">*</span></span><input type="text" class="input-field" name="title_sk" value="" /></label>
                <label for="desc_sk"><span>Vysvetlenie <span class="required">*</span></span><textarea name="desc_sk" class="textarea-field"></textarea></label>
                <label for="title_en"><span>Term <span class="required">*</span></span><input type="text" class="input-field" name="title_en" value="" /></label>
                <label for="desc_en"><span>Explanation <span class="required">*</span></span><textarea name="desc_en" class="textarea-field"></textarea></label>
                <label><span> </span><input type="submit" value="Pridaj" class="button-33"/></label>
            </form>
        </div>

    </div>
    <div class="grid-child2">
        <h2>Zmazanie/úprava pojmu</h2>

        <button id="show" type="button" class="button-33">Zobraz databazu</button>
        <div class="form-style-2">
        <div class="left">
            <label for="desc_sk" style="display: none" id="label1"><span>Vysvetlenie <span class="required">*</span></span><textarea id="textarea1" name="desc_sk" class="textarea-field"></textarea></label>
        </div>
        <div class="right">
            <label for="desc_en" style="display: none" id="label2"><span>Explanation <span class="required">*</span></span><textarea id="textarea2" name ="desc_en" class="textarea-field"></textarea></label>
        </div>
        <button id="editBtn" style="display: none" class="button-33" >Uprav</button>
        </div>
        <table class="sortable" id="table-admin">
            <thead>
            <tr class="active-row">
                <th>Pojem</th>
                <th>Vysvetlenie</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>

            </tbody>
        </table>

    </div>
    <div class="grid-child3">

    </div>
</div>
<script>


    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }

    const button = document.querySelector("#show");
    const table = document.querySelector("#table-admin");
    const tbody = document.querySelector("#table-admin").tBodies[0];
    button.addEventListener('click',()=>{
        fetch("translate.php?search=&language_code=sk",{method: "get"}
        ).then(response => response.json()
        ).then(result => {
            result.forEach(item=>{
                const tr = document.createElement('tr');
                const td1 = document.createElement('td');
                td1.append(item.searchTitle);
                const td2 = document.createElement('td');
                td2.append(item.searchDescription);
                tr.append(td1);
                tr.append(td2);
                const button3 = document.createElement('button');
                button3.append("Zmaz");
                button3.setAttribute("id","deleteBtn")
                button3.className = "button-33"
                button3.addEventListener("click",()=>{
                    fetch("delete.php",{method: "post",
                                        headers:{
                                                "Accept":'application/json,text/plain */*',
                                                'Content-Type':'application/json'
                                        },
                                        body: JSON.stringify({id: item.word_id})
                    }).then(response => response.json())
                        .then(result=>{
                            console.log(result)
                            if (result.deleted){
                                tr.remove();
                            }
                        })
                });


                const button4 = document.createElement('button');
                var id;
                button4.addEventListener("click",()=>{
                    id = item.word_id;
                    document.getElementById("table-admin").style.display = "none";
                    document.getElementById("textarea1").style.display ="block";
                    document.getElementById("textarea2").style.display ="block";
                    document.getElementById("label1").style.display ="block";
                    document.getElementById("label2").style.display ="block";
                    document.getElementById("editBtn").style.display ="block";

                });
                const editBtn = document.getElementById("editBtn");
                editBtn.addEventListener('click',()=>{
                    fetch("edit.php",{method: "post",
                        headers:{
                            "Accept":'application/json,text/plain */*',
                            'Content-Type':'application/json'
                        },
                        body: JSON.stringify({id: id,
                            desc_sk: document.getElementById("textarea1").value,
                            desc_en: document.getElementById("textarea2").value
                        })
                    });
                    window.location.reload();
                })

                button4.append("Uprav");
                button4.className = "button-33";
                tr.append(button3);
                tr.append(button4);
                tbody.append(tr);




            })
        })
    })
</script>



</body>
</html>
