<?php

namespace netvod\actions;

class ActionNoteCommentaire extends Action {

        public function execute(): string {
            $html = <<<HEREDOC
                <html lang="en">
                    <head><title>NetVod</title>
                          <link href="./css/Notation-style.css" rel="stylesheet">
                          <meta name="viewport" content="width=device-with,initial-scale=1.0">
                    </head>
                    
            HEREDOC;

            if ($_SERVER['REQUEST_METHOD'] == 'GET') {
                $html .= $this->renderHtml(true);
            }else{
                $html .= $this->renderHtml(true);
            }

            return $html;
        }

        public function renderHtml(bool $popup):string{

                $html = <<<HEREDOC
                    <body>
                            
                                <div class="container">
                                    <button type="submit" onclick="openPopup()">click me!</button>
                                    <div class="popup" id="popup">
                                        <h1>Noter le film</h1>
                                        <button type="button" onclick="closePopup()">close</button>
                  
                                    </div>
                                </div>
                                
                                <script>
                                        let popup = document.getElementById("popup");
                                        function openPopup() {
                                            popup.classList.add("open-popup");
                                        }
                                        
                                        function closePopup() {
                                            popup.classList.remove("open-popup");
                                        }
                                </script>
                           
                       
                        </body>
                    </html>
                HEREDOC;




             return $html;

        }







}