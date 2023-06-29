<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <title>Articles</title>
    <link rel='stylesheet' type='text/css' href='../style.css'>

</head>
<body>
    <main>
        <script type="text/javascript">
            function save(){
                let name = document.getElementById('edit_name').value;
                console.log(name);
                if (name != ''){
                    let content = document.getElementById('edit_article').value.replace(/\n/g, '<br/>');
                    window.location = 'https://webik.ms.mff.cuni.cz/~72707943/cms/article-save/<?php echo $data[0]['id']?>?content=' + content + '&name_=' + name;
                }
            }

        </script>

        <div class="content">
            <form action="">
                <h1>Name</h1>
                <input required maxlength="32" type="text" id="edit_name" value="<?php echo $data[0]['name_']?>">
                <h1>Content</h1>
                <textarea id="edit_article" maxlength="1024"><?php echo $data[0]['content']?></textarea>
                <script type="text/javascript">
                    document.getElementById('edit_article').value = document.getElementById('edit_article').value.replace(/<br\/>/g, '\n');
                </script>
            </form>
            <div class="buttons">
                <button class="btn save"  onclick="save()">Save</button>
                <button class="btn back" onclick="window.location = 'https://webik.ms.mff.cuni.cz/~72707943/cms/articles'">Back to articles</button>
            </div>  
        </div>
    </main>
</body>
</html>