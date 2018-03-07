<?php
$a=array("red","green","blue","yellow","brown");
echo "<pre>";
print_r($a);
print_r(array_slice($a,0,2));
print_r(array_slice($a,2,2));
print_r(array_slice($a,4,2));
exit;
require_once 'vendor/autoload.php';
$name="Mr. Anilkumar M";
$qualification="Qulification";
$email="test@gmail.com";
 
$html .= '
<!DOCTYPE html>
<html>
    <head>
        <title>Test</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">       
        <link type="text/css" rel="stylesheet" href="css/style.css"/>
    </head>    
    <body>
        <style>
            body{
                padding-left: 200px;
                padding-right: 200px;
                font-family: "Roboto", sans-serif;
            }
            p{
                font-size: 14px;
            }
            .page-content{
                border: 1px solid #e0e0e0;  
                margin-top: 20px;
            }
            .page-content .page-pad{
                padding: 5px 20px;
            }
            .bold-text{
                font-weight: bold;
            }
            .text-upper{
                text-transform: uppercase;
            }
            .text-caps{
                text-transform: capitalize;
            }
            .bottom-margin{
                margin-bottom: 20px;
            }
            .ptxt{
                line-height: 0.5;
                font-size: 14px;
            }
            .heading-strip{
                background-color: #e0e0e0; 
                padding: 10px 20px;
                font-weight: bold;
                text-transform: uppercase;
                margin-top: 0;
                margin-bottom: 0;
            }
            .border{
                
                border:0.1mm solid #222;
                border-radius: 2mm;
                padding: 5px 20px 5px 10px;
            }

        </style>
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <div class="page-content">
                        <div class="row">
                            <div class="header page-pad">
                             

                            
                            <div >
                             <img src="logo-for-pdf.jpg"   style="float:right;"/> 
                test <br/> Founder at test<br/>t@g.co 
                                  
                                </div>
                                
                         
                          
                            </div>
                        </div>  
                        <h5 class="heading-strip">Skills</h5>
                        <div class="row">
                            <div class="skills page-pad bottom-margin">
                                <div style="margin-top:20px; margin-bottom: 20px;">
                                    <a style="border: 1px solid #222; padding: 5px 20px 5px 10px; text-transform: uppercase;">html</a>
                                    <a style="border: 1px solid #222; padding: 5px 20px 5px 10px; text-transform: uppercase;">Database Management System Design</a>
                                    <a style="border: 1px solid #222; padding: 5px 20px 5px 10px; text-transform: uppercase;">CSS</a>
                                    <a style="border: 1px solid #222; padding: 5px 20px 5px 10px; text-transform: uppercase;">JQuery</a>
                                    <a style="border: 1px solid #222; padding: 5px 20px 5px 10px; text-transform: uppercase;">JavaScript</a>
                                    <a style="border: 1px solid #222; padding: 5px 20px 5px 10px; text-transform: uppercase;">.Net</a>
                                </div>
                                <div>
                                    <a style="border: 1px solid #222; padding: 5px 20px 5px 10px; text-transform: uppercase;">Artificial Intelligence & Cognitive</a>
                                    <a style="border: 1px solid #222; padding: 5px 20px 5px 10px; text-transform: uppercase;">C</a>
                                    <a style="border: 1px solid #222; padding: 5px 20px 5px 10px; text-transform: uppercase;">C++</a>
                                    <a style="border: 1px solid #222; padding: 5px 20px 5px 10px; text-transform: uppercase;">Entreprenuership Management System</a>
                                </div>
                            </div>
                        </div>
                        <h5 class="heading-strip">Career Objective</h5>
                        <div class="row">
                            <div class="career page-pad">
                            <p style="color: #a4a4a4;font-size: 14px;font-weight: normal;margin: 0 0 5px; ">IIT Hydrabad, India</p>
                            <p style="font-size: 14px;color: #111111;font-weight: 600;margin: 0 0 5px; ">73% First Grade</p>
                            <p style="color: #a4a4a4;font-size: 14px;font-weight: normal;margin: 0 0 10px ">October 2006- April 2007</p>
                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                                <p style="font-size: 14px;color: #333333;font-weight: normal;margin: 0;border-bottom: 1px solid #e1dede;padding-bottom: 15px; line-height: 20px; ">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                            </div>
                        </div>
                        <h5 class="heading-strip">Professional Summary</h5>
                        <div class="row">
                            <div class="professional page-pad">
                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                            </div>
                        </div>
                        
                        <h5 class="heading-strip">last</h5>
                        <div class="row">
                            <div class="professional page-pad">
                                <table>
                                <tbody>
                                    <tr>
                                        <td style="font-size: 14px;color: #333333;font-weight: bold;margin: 0;text-transform: uppercase; ">Language</td>
                                        <td style=" font-size: 16px;color: #333333;font-weight: 600;margin: 0; text-align: center; ">English</td>
                                        <td style="width: 197.467px; font-size: 16px;color: #333333;font-weight: 600;margin: 0; text-align: center; ">Hindi</td>
                                        <td style=" font-size: 16px;color: #333333;font-weight: 600;margin: 0; text-align: center; ">Telugu</td>
                                    </tr>
                                    <tr>
                                        <td style="width: 25%; ">&nbsp;</td>
                                        <td style="width: 25%; ">
                                            <table style=" text-align: center; width: 100%; ">
                                                <tbody>
                                                    <tr>
                                                        <td style="width: 30%;font-size: 14px;color: #111111;font-weight: normal;margin: 0;background: #ebebeb; ">Read</td>
                                                        <td style="width: 30%;font-size: 14px;color: #111111;font-weight: normal;margin: 0;background: #ebebeb; ">Write</td>
                                                        <td style="width: 30%;font-size: 14px;color: #111111;font-weight: normal;margin: 0;background: #ebebeb; ">Write</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <!-- DivTable.com --></td>
                                        <td style="width: 25%; text-align: center; ">
                                           <table style=" text-align: center; width: 100%; ">
                                                <tbody>
                                                    <tr>
                                                        <td style="width: 30%;font-size: 14px;color: #111111;font-weight: normal;margin: 0;background: #ebebeb; ">Read</td>
                                                        <td style="width: 30%;font-size: 14px;color: #111111;font-weight: normal;margin: 0;background: #ebebeb; ">Write</td>
                                                        <td style="width: 30%;font-size: 14px;color: #111111;font-weight: normal;margin: 0;background: #ebebeb; ">Write</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                        <td style="width: 25%; text-align: center; ">
                                            <table style=" text-align: center; width: 100%; ">
                                                <tbody>
                                                    <tr>
                                                        <td style="width: 30%;font-size: 14px;color: #111111;font-weight: normal;margin: 0;background: #ebebeb; ">Read</td>
                                                        <td style="width: 30%;font-size: 14px;color: #111111;font-weight: normal;margin: 0;background: #ebebeb; ">Write</td>
                                                        <td style="width: 30%;font-size: 14px;color: #111111;font-weight: normal;margin: 0;background: #ebebeb; ">Write</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-size: 14px;color: #333333;font-weight: bold;margin: 0;text-transform: uppercase; ">Location</td>                                    
                                        <td style=" font-size: 15px;color: #333333;font-weight: 400;line-height: 24px;margin: 0; " colspan="3">IIT Hydrabad, India</td>
                                    </tr>
                                    <tr>
                                        <td style="font-size: 14px;color: #333333;font-weight: bold;margin: 0;text-transform: uppercase; ">Hobbies</td>                                    
                                        <td style=" font-size: 15px;color: #333333;font-weight: 400;line-height: 24px;margin: 0; " colspan="3">Cricket, Football</td>
                                    </tr>
                                </tbody>
                            </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>   
</html>

';
$mpdf = new mPDF();
$mpdf->showImageErrors = true;
$mpdf->WriteHTML($html);
$mpdf->SetDisplayMode('fullpage');

$mpdf->Output();

//echo $html;exit;