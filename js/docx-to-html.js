jQuery(document).ready(function ($) {
  var fileName = "";
  $("#docx-to-html-form").on("submit", function (e) {
    e.preventDefault();

    var formData = new FormData(this);
    formData.append("action", "convert_docx_to_html");
    formData.append("nonce", docxToHtml.nonce);

    fileName = $("#docx_file")[0].files[0].name;

    $.ajax({
      url: docxToHtml.ajax_url,
      type: "POST",
      data: formData,
      processData: false,
      contentType: false,
      success: function (response) {
        if (response.success) {
          var arrayBuffer = base64ToArrayBuffer(response.data.file_content);
          mammoth
            .convertToHtml({ arrayBuffer: arrayBuffer })
            .then(displayResult)
            .catch(handleError);
        } else {
          alert("Error: " + response.data);
        }
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

  function displayResult(result) {
    $("#html-content").html(result.value);
    $("#conversion-result").show();
  }

  function handleError(err) {
    console.log(err);
    alert("Conversion error.");
  }

  $("#create-post").on("click", function () {
    var content = $("#html-content").html();

    $.ajax({
      url: docxToHtml.ajax_url,
      type: "POST",
      data: {
        action: "create_post_from_html",
        nonce: docxToHtml.nonce,
        content: content,
        title: fileName,
      },
      success: function (response) {
        if (response.success) {
          alert("Post created successfully!");
        } else {
          alert("Error creating post: " + response.data);
        }
      },
    });
  });
});
