jQuery(document).ready(function ($) {
  var fileName = "";
  var imageFiles = [];

  $("#docx-to-html-form").on("submit", function (e) {
    e.preventDefault();

    $("#loading-indicator").show();

    var formData = new FormData(this);
    formData.append("action", "convert_docx_to_html");
    formData.append("nonce", docxToHtml.nonce);

    fileName = $("#docx_file")[0].files[0].name;
    imageFiles = $("#image_files")[0].files;

    $.ajax({
      url: docxToHtml.ajax_url,
      type: "POST",
      data: formData,
      processData: false,
      contentType: false,
      success: function (response) {
        $("#loading-indicator").hide();
        if (response.success) {
          var arrayBuffer = base64ToArrayBuffer(response.data.file_content);
          mammoth
            .convertToHtml({ arrayBuffer: arrayBuffer })
            .then(function (result) {
              displayResult(result, response.data.image_urls);
            })
            .catch(handleError);
        } else {
          alert("Error: " + response.data);
        }
      },
      error: function () {
        $("#loading-indicator").hide(); // Masquer l'indicateur de chargement en cas d'erreur
        alert("Une erreur est survenue.");
      },
    });
  });

  function base64ToArrayBuffer(base64) {
    var binaryString = window.atob(base64);
    var len = binaryString.length;
    var bytes = new Uint8Array(len);
    for (var i = 0; i < len; i++) {
      bytes[i] = binaryString.charCodeAt(i);
    }
    return bytes.buffer;
  }

  function displayResult(result, imageUrls) {
    var htmlContent = result.value;

    htmlContent += "<div class='custom-img'>";
    // Append images to the HTML content
    if (imageUrls && imageUrls.length > 0) {
      imageUrls.forEach(function (url) {
        htmlContent += '<img src="' + url + '" draggable="true" />';
      });
    }

    htmlContent += "</div>";

    $("#html-content").html(htmlContent);
    $("#conversion-result").show();
    $("#docx-to-html-form").hide();
  }

  function handleError(err) {
    console.log(err);
    alert("Conversion error.");
  }

  $("#create-post").on("click", function () {
    $("#loading-indicator").show();
    var content = $("#html-content").html();
    var categories = $("#categories").val();

    $.ajax({
      url: docxToHtml.ajax_url,
      type: "POST",
      data: {
        action: "create_post_from_html",
        nonce: docxToHtml.nonce,
        content: content,
        title: fileName,
        categories: categories,
      },
      success: function (response) {
        $("#loading-indicator").hide();
        var messageDiv = $("#post-message");
        if (response.success) {
          messageDiv.html(
            "<div class='updated msg-custom'>Le post a bien été crée!</div>"
          );
        } else {
          messageDiv.html(
            "<div class='error'>Erreur msg-custom : " + response.data + "</div>"
          );
        }
        messageDiv.show();
      },
      error: function () {
        $("#loading-indicator").hide();
        var messageDiv = $("#post-message");
        messageDiv.html(
          "<div class='error msg-custom'>Une erreur inconnu est survenu.</div>"
        );
        messageDiv.show();
      },
    });
  });
});
