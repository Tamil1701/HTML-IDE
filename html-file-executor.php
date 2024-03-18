<?php
/*
Plugin Name: HTML File Executor
Description: Simple HTML file executor with code editor. Allows users to input HTML code and execute it. To use, add the shortcode [html_file_executor] to any post or page.
Version: 1.1.0
Author: Tamilarasan Selvam
Author URI: https://tamil1701.github.io/tamilarasan/
Text Domain: html-file-executor
*/

function html_file_executor_shortcode() {
    ob_start();
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HTML File Executor</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.63.0/codemirror.min.css">
    <style>
      body {
        margin: 0;
        padding: 0;
        font-family: Arial, sans-serif;
      }

      #container {
        display: flex;
        height: 100vh;
      }

      #editor-container, #result-container {
        flex: 1;
        padding: 20px;
        box-sizing: border-box;
      }

      #editor-container {
        background-color: #f0f0f0;
      }

      #result-container {
        background-color: #e0e0e0;
        position: relative;
      }

      .code-editor {
        width: 100%;
        height: 100%;
        border: none;
        outline: none;
      }

      #new-run{
        margin-left: 50px;
        margin-top: 50px;
        margin-bottom: 50px;
        padding: 10px 20px;
        font-size: 16px;
        background-color: #007bff;
        color: #fff;
        border: none;
        border-radius: 5px;
        cursor: pointer;
      }

      @media screen and (max-width: 768px) {
        #container {
          flex-direction: column;
        }
      }

      .cm-tag-open {
        color: blue; /* Opening tag color */
      }

      .cm-tag-close {
        color: red; /* Closing tag color */
      }
    </style>
    </head>
    <body>
    <div id="container">
      <div id="editor-container">
        <h4>HTML code editor</h4>
        <textarea id="code" class="code-editor"></textarea>
        <button id="new-run" onclick="runCode()">Run Code</button>
      </div>
      
      <div id="result-container">
        <h4>Output</h4>
        <iframe id="result" style="padding: -10px; background-color: #fff;  border: 2px solid #1b1b1b; width: 100%; height: calc(100% - 60px);"></iframe>
      </div>
    </div>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.63.0/codemirror.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.63.0/mode/xml/xml.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.63.0/mode/javascript/javascript.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.63.0/mode/css/css.min.js"></script>
    <script>
      CodeMirror.defineMode("html_custom", function() {
        var htmlTags = {
          html: true,
          head: true,
          body: true,
          div: true,
          span: true,
          p: true,
          a: true,
          img: true,
          input: true,
          button: true,
          h1: true,
          h2: true,
          h3: true,
          h4: true,
          h5: true,
          h6: true,
          ul: true,
          ol: true,
          li: true,
          table: true,
          tr: true,
          td: true,
          th: true,
          form: true,
          textarea: true,
          select: true,
          option: true,
          script: true,
          style: true,
          link: true,
          meta: true,
          title: true,
          iframe: true
        };

        return {
          token: function(stream, state) {
            if (stream.match("<")) {
              if (stream.match("/")) {
                var tagName = stream.match(/[^\s>\/]+/);
                if (tagName && htmlTags[tagName[0].toLowerCase()]) {
                  return "tag-close";
                }
              } else {
                var tagName = stream.match(/[^\s>\/]+/);
                if (tagName && htmlTags[tagName[0].toLowerCase()]) {
                  return "tag-open";
                }
              }
            }
            stream.next();
            return null;
          }
        };
      });

      var editor = CodeMirror.fromTextArea(document.getElementById("code"), {
        lineNumbers: true,
        mode: "html_custom",
        theme: "default",
        tabSize: 2,
        indentWithTabs: true,
        lineWrapping: true
      });

      function runCode() {
        var code = editor.getValue();
        var iframe = document.getElementById("result");
        var iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
        iframeDoc.open();
        iframeDoc.write(code);
        iframeDoc.close();
      }
    </script>
    </body>
    </html>
    <?php
    return ob_get_clean();
}

add_shortcode('html_file_executor', 'html_file_executor_shortcode');
