<div class="wrap">
    <h1>DOCX to HTML Converter</h1>
    <form id="docx-to-html-form" method="post" enctype="multipart/form-data">
        <input type="file" id="docx_file" name="docx_file" />
        <input type="submit" name="preview" value="Preview" />
    </form>
    <div id="conversion-result" style="display:none;">
        <h2>Conversion Result</h2>
        <div id="html-content" class="docx-to-html-content"></div>
        <button id="create-post">Create Post</button>
    </div>
</div>
<style>
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

.docx-to-html-content img {
    display: inline-block;
    position: relative;
    max-width: 100%;
    width: 300px;
    height: 400px;
    margin: 5px;
    border-radius: 7px 7px 7px 7px !important;
    overflow: hidden !important;
    box-shadow: 6px 6px 18px 0 rgba(0, 0, 0, .3);
}

</style>