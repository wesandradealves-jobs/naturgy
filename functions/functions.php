<?php 
    function randHash($len=32)
    {
        return substr(md5(openssl_random_pseudo_bytes(20)),-$len);
    }
    function to_permalink($str)
    {
        if($str !== mb_convert_encoding( mb_convert_encoding($str, 'UTF-32', 'UTF-8'), 'UTF-8', 'UTF-32') )
            $str = mb_convert_encoding($str, 'UTF-8', mb_detect_encoding($str));
        $str = htmlentities($str, ENT_NOQUOTES, 'UTF-8');
        $str = preg_replace('`&([a-z]{1,2})(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig);`i', '\\1', $str);
        $str = html_entity_decode($str, ENT_NOQUOTES, 'UTF-8');
        $str = preg_replace(array('`[^a-z0-9]`i','`[-]+`'), '-', $str);
        $str = strtolower( trim($str, '-') );
        return $str;
    }
    function getForm($str, $arr, $basename){
        $form = '<form method="POST" action="functions/search.php" class="searchbar forms">';
            $form .= '<div class="container">';
                $form .= '<div class="fieldset">';
                    $form .= '<i class="fa fa-search"></i>';
                $form .= '</div>';

                if($str == 'users'){
                    $form .= '<div class="fieldset">';
                        $form .= '<label for="nome">Nome de usuário</label>';
                        $form .= '<span>';
                            $form .= '<input name="nome" type="text">';
                        $form .= '</span>';
                    $form .= '</div>';
                    $form .= '<div class="fieldset">';
                        $form .= '<label for="sap">Número da matrícula (SAP)</label>';
                        $form .= '<span>';
                            $form .= '<input name="sap" type="text">';
                        $form .= '</span>';
                    $form .= '</div>';
                } else {
                    $form .= '<div class="fieldset">';
                        $form .= '<label for="">Número do processo</label>';
                        $form .= '<span>';
                            $form .= '<input type="text">';
                        $form .= '</span>';
                    $form .= '</div>';
                    $form .= '<div class="fieldset">';
                        $form .= '<label for="">Nome do processo</label>';
                        $form .= '<span>';
                            $form .= '<input type="text">';
                        $form .= '</span>';
                    $form .= '</div>';
                    $form .= '<div class="fieldset">';
                        $form .= '<label for="">Tipo do processo</label>';
                        $form .= '<span class="custom-combobox">';
                            $form .= '<i class="fal fa-angle-down"></i>';
                            $form .= '<select name="" id="">';
                                $form .= '<option value="">Lorem ipsum dolor.</option>';
                                $form .= '<option value="">Delectus ab, labore.</option>';
                                $form .= '<option value="">Iste, ut, nihil!</option>';
                                $form .= '<option value="">Voluptatibus, cumque, ab.</option>';
                                $form .= '<option value="">Iure, quis voluptatum.</option>';
                            $form .= '</select>';
                        $form .= '</span>';
                    $form .= '</div>';
                }
                $form .= '<div class="fieldset">';
                    $form .= '<button class="btn btn-1">Buscar</button>';
                $form .= '</div>';
            $form .= '</div>';
        $form .= '</form>';
        
        foreach ($arr as $value) {
            if($value == $basename){
                print $form;
            }
        }
    }
