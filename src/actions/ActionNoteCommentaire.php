<?php

namespace netvod\actions;

class ActionNoteCommentaire extends Action {

        public function execute(): string {
            $html = <<<HEREDOC
                <html lang="en">
                    <head><title>NetVod</title>
                          <link href="./css/Notation-style.css" rel="stylesheet">
                    </head>
                    <body>
                        <div class="popup" contenteditable="false">
                            <button onclick="myFunction()">click me!</button>
                            <span class="popuptext" id="myPopup"></span>
                        </div>
                        
                        <script>
                                function myFunction() {
                                    var popup = document.getElementById("myPopup");
                                    popup.classList.toggle("show");
                                }
                        </script>
                   
                    </body>
            HEREDOC;

            if ($_SERVER['REQUEST_METHOD'] == 'GET') {

            }
        }







}