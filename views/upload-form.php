<div class="wrap">
    <h1>Ajouter un nouveau article via un fichier docx</h1>
    <form id="docx-to-html-form" method="post" enctype="multipart/form-data">
        <label for="docx_file">Choisir un fichier docx</label>
        <input type="file" id="docx_file" name="docx_file" />
        <label for="image_files[]">Choisir des images</label>
        <input type="file" id="image_files" name="image_files[]" multiple />
         <div id="featured-image-upload">
            <label for="featured_image">Background image:</label>
            <input type="file" id="featured_image" name="featured_image" />
        </div>
        <label for="categories">Choisir des catégories</label>
        <select id="categories" name="categories[]" multiple>
            <?php
            $categories = get_categories();
            foreach ($categories as $category) {
                echo '<option value="' . $category->term_id . '">' . $category->name . '</option>';
            }
            ?>
        </select>
        <input type="submit" name="preview" value="Preview" />
    </form>
    <div id="conversion-result" style="display:none;">
        <h2>Resultat</h2>
        <div id="html-content" class="docx-to-html-content"></div>
        <button id="create-post">crée un post</button>
    </div>
    <div id="post-message" style="display:none;"></div>
    <div id="loading-indicator" style="display: none;">
        <div class="loading"></div>
    </div>
</div>
<style>
/* Général */
body {
    font-family: 'Arial', sans-serif;
    background-color: #f7f7f7;
    margin: 0;
    padding: 0;
}
#loading-indicator{
    display: flex;
    align-items: center;
    justify-content: center;
    height: 50px;
    margin-top: 15px;
}
.loading {
    border: 5px solid #4f94d4;
    width: 35px;
    height: 35px;
    border-radius: 50%;
    border-left-color: rgb(255, 255, 255);
    animation: spin 1s ease infinite;
}

@keyframes spin {
    0% {
        transform: rotate(0deg);
    }

    100% {
        transform: rotate(360deg);
    }
}

.wrap {
    max-width: 1000px;
    margin: 50px auto;
    padding: 20px;
    background-color: #fff;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
}

h1 {
    font-size: 24px;
    color: #333;
    margin-bottom: 20px;
    text-align: center;
}

.msg-custom{
    font-size: 20px;
    height: 30px;
    align-content: center;
}

/* Formulaire */
form {
    display: flex;
    flex-direction: column;
}

label {
    font-size: 14px;
    color: #555;
    margin-bottom: 5px;
    font-weight: bold;
}

input[type="file"],
select,
input[type="submit"] {
    padding: 10px;
    margin-bottom: 20px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
    background-color: #fff;
    transition: border-color 0.3s ease;
}

input[type="file"]:focus,
select:focus,
input[type="submit"]:focus {
    border-color: #007bff;
    outline: none;
}

input[type="submit"] {
    background-color: #007bff;
    color: #fff;
    border: none;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

input[type="submit"]:hover {
    background-color: #0056b3;
}

/* Catégories */
select {
    height: 100px;
    overflow-y: auto;
}

/* Résultat de la conversion */
#conversion-result {
    display: none;
    margin-top: 20px;
}

#conversion-result h2 {
    font-size: 20px;
    color: #333;
    margin-bottom: 10px;
    text-align: center;
}

#html-content {
    padding: 20px;
    border: 1px solid #ddd;
    border-radius: 4px;
    background-color: #f9f9f9;
    margin-bottom: 20px;
}

#html-content p {
    margin: 0 0 10px;
}

button#create-post {
    display: block;
    width: 100%;
    padding: 10px;
    font-size: 16px;
    color: #fff;
    background-color: #28a745;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

button#create-post:hover {
    background-color: #218838;
}



.docx-to-html-content {
    font-family: 'Open Sans', Arial, sans-serif;
    line-height: 1.6;
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    justify-content: center;
    gap: 10px;
}

.docx-to-html-content h1, .docx-to-html-content h2, .docx-to-html-content h3, .docx-to-html-content h4, .docx-to-html-content h5, .docx-to-html-content h6 {
    font-family: 'Open Sans', Helvetica, Arial, Lucida, sans-serif;
    font-weight: 700;
    font-variant: small-caps;
    color: #1489e4 !important;
}

.docx-to-html-content p {
    font-size: 14px;
    color: #666;
    line-height: 1.7em;
    font-weight: 500;
}

.docx-to-html-content ol {
    color: #666;
    font-size: 14px;
    line-height: 1.9em;
    margin-left: 35px;
}

.custom-article-title {
    font-family: Allura, handwriting;
    font-weight: 700;
    font-size: 70px;
    color: #03c7f7 !important;
    letter-spacing: 1px;
    line-height: 1.4em;
    text-align: center;
    text-shadow: 0 0 .3em #000;
}

.docx-to-html-content .custom-img{
    display: flex;
}

.docx-to-html-content .custom-img img {
    display: inline-block;
    position: relative;
    max-width: 100%;
    width: 400px;
    height: 200px;
    margin: 5px;
    border-radius: 7px 7px 7px 7px !important;
    overflow: hidden !important;
    box-shadow: 6px 6px 18px 0 rgba(0, 0, 0, .3);
}

</style>