<?php
    /**
     * $type: určuje typ e-mailu
     *      0: obnova hesla
     *      1: potvrzení změny jména
     *      2: zamítnutí změny jména
     */
    function getEmail($type, $data)
    {
        $email = "";
        
        //Hlavička
        $email .= "
        <table width='100%' cellpadding='0' border='0' cellspacing='0'>
            <tbody>
                <tr>
                    <td align='center' bgcolor='#eeeeee'>
                        <table width='660' cellpadding='0' border='0' cellspacing='0' align='center' bgcolor='#FFFFFF' style='font-size:15px;font-family:Helvetica,Arial,sans-serif;line-height:25px;color:#445566;border-top: 10px solid #405d27;'>
                            <tbody>
                                <tr>
                                    <td align='left' style='padding:45px 50px 45px 50px'>
                                        <div>
        ";
        
        //Hlavní text
        if ($type === 0)
        {
            $email .= "
                <p>For password recovery click on this link: <a href='localhost/Poznavacky/emailPasswordRecovery.php?token=".$data['code']."'>RECOVER PASSWORD</a></p>
                <p>The link will be availible for the upcoming 24 hours or until a new request is sent.</p>
                <p style='color: #990000;'><span style='font-weight: bold;'>IMPORTANT: </span><span>Don't forward this message to anyone! They could get access to your account.</span>
            ";
        }
        if ($type === 1)
        {
            $email .= "
                <p>Based on your request at <a href='poznavacky.jednoduse.cz'>poznavacky.jednoduse.cz</a> your username was changed to <b>".$data['newName']."</b>.
                <br>Under your old name (<b>".$data['oldName']."</b>) you can't no longer log in.</p>
                <p>If you want to change the name back to the old one or to a completely different one, you can do so by sending another request at your account settings.</p>
                <p>Haven't you send any username change request? It is possible that someone has got an access to your account. We recommend you to change your password as soon as possible. If you can't log in, please contact us at <a href='mailto:poznavacky@email.com'>poznavacky@email.com</a>".
                "</p>
            ";
        }
        if ($type === 2)
        {
            $email .= "
                <p>Your request for username change at <a href='poznavacky.jednoduse.cz'>poznavacky.jednoduse.cz</a> was denied by an administrator.
                <br><b>Reason for denial: <span style='color:#990000'>".$data['reason']."</span>.</b><br>
                <p> Your currnet name (<b>".$data['oldName']."</b>) is still valid.</p>
                <p>If you still want to change your username, you can send another request. Hovewer, don't send a request for the name that has been denied.</p>
                <p>Haven't you send any username change request? It is possible that someone has got an access to your account. We recommend you to change your password as soon as possible. If you can't log in, please contact us at <a href='mailto:poznavacky@email.com'>poznavacky@email.com</a></p>"
                ;
        }
        
        //Patička
        $email .= "
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <table width='660' cellpadding='0' border='0' cellspacing='0' align='center' style='font-family:Arial,Helvetica,sans-serif;color:#666666;font-size:12px;line-height:18px;text-align:center;'>
                            <tbody>
                                <tr>
                                    <td>
                                        <div>
                                            <p style='margin-top:30px;margin-bottom:30px'>
        ";
        
        //Zápatí
        if ($type === 0)
        {
            $email .= "<p>If you haven't requested a password change, you can ignore this e-mail.</p>";
            $email .= "<p>In case of problems, contact us at<a href='mailto:poznavacky@email.com'>poznavacky@email.com</a></p>";
        }
        $email .= "<p>This is an automatically generated message. Please, don't reply.</p>";
        
        //Zbytek patičky
        $email .= "
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
        ";
        
        return $email;
    }