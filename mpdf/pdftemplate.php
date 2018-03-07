<?php

class pdfTemplate
{

    var $_data;

    function __construct($mainHead, $template)
    {
        $this->_mainHeaddata = $mainHead;
        $this->_data = $template;
    }

    public function headSection()
    {
        $headData = '<div class="row">
                        <div class="header page-pad">
                        <div>
                            <img src="logo-for-pdf.jpg"   style="float:right;"/> 
                             
                             ' . $this->_mainHeaddata["name"] . ' 
                                <br/> ' . $this->_mainHeaddata["qualification"] . ' 
                                <br/>' . $this->_mainHeaddata["email"] . ' 
                        </div>



                        </div>
                    </div>';
        return $headData;
    }

    public function skillSection()
    {
        $skillList = "";
        if (count($this->_data["user_skills"]) > 0) {

            foreach ($this->_data["user_skills"] as $skill) {
                $skillList .= '<a class="text-upper border">' . $skill["tag"] . '</a>';
            }

            $skillData = '  
                <h5 class="heading-strip">Skills</h5>
                        <div class="row">
                            <div class="skills page-pad bottom-margin">
                                <div style="margin-top:20px; margin-bottom: 20px;">
                                     ' . $skillList . '
                                </div>
                                
                            </div>
                        </div>

                ';
        }


        return $skillData;
    }

    //CAREER OBJECTIVE
    public function careerSection()
    {
        if (count($this->_data["user_summary"]) > 0) {
            $summeryData = '  
                <h5 class="heading-strip">Career Objective</h5>
                        <div class="row">
                            <div class="career page-pad">
                               <p>
                                     ' . $this->_data["user_summary"]["summary"] . '
                                </p>
                                
                            </div>
                        </div>
                ';
        }


        return $summeryData;
    }

    //PROFESSIONAL SUMMARY
    public function professionalSection()
    {
        $plist = "";
        $totalCount = count($this->_data["user_work_experience"]);
        if ($totalCount > 0) {
            $loopcount = 1;
            foreach ($this->_data["user_work_experience"] as $experience) {
                if ($loopcount !== $totalCount) {
                    $plist .= ' 
                            <p style="font-size: 14px;color: #111111;font-weight: 600;margin: 0 0 5px; ">' . $experience["institution"] . '</p>
                            <p style="color: #a4a4a4;font-size: 14px;font-weight: normal;margin: 0 0 10px ">' . $experience["from_date"] . ' - ' . $experience["to_date"] . '</p>
                            <p style="font-size: 14px;color: #333333;font-weight: normal;margin: 0;border-bottom: 1px solid #e1dede;padding-bottom: 15px; line-height: 20px; ">' . $experience["description"] . '</p>';
                } else {
                    $plist .= '  
                            <p style="font-size: 14px;color: #111111;font-weight: 600;margin: 0 0 5px; ">' . $experience["institution"] . '</p>
                            <p style="color: #a4a4a4;font-size: 14px;font-weight: normal;margin: 0 0 10px ">' . $experience["from_date"] . ' - ' . $experience["to_date"] . '</p>
                            <p style="font-size: 14px;color: #333333;font-weight: normal;margin: 0;">' . $experience["description"] . '</p>';
                }
                $loopcount = $loopcount + 1;
            }
        }
        $pdata = '<h5 class="heading-strip">PROFESSIONAL SUMMARY</h5>
                        <div class="row">
                            <div class="career page-pad">
                            ' . $plist . '
                            </div>
                        </div>';
        return $pdata;
    }

    //EDUCATION
    public function eductationSection()
    {
        $elist = "";
        $totalCount = count($this->_data["user_work_experience"]);
        if ($totalCount > 0) {
            $loopcount = 1;
            foreach ($this->_data["user_work_experience"] as $experience) {
                if ($loopcount !== $totalCount) {
                    $elist .= ' 
                            <p style="font-size: 14px;color: #111111;font-weight: 600;margin: 0 0 5px; ">' . $experience["institution"] . '</p>
                            <p style="color: #a4a4a4;font-size: 14px;font-weight: normal;margin: 0 0 10px ">' . $experience["from_date"] . ' - ' . $experience["to_date"] . '</p>
                            <p style="font-size: 14px;color: #333333;font-weight: normal;margin: 0;border-bottom: 1px solid #e1dede;padding-bottom: 15px; line-height: 20px; ">' . $experience["description"] . '</p>';
                } else {
                    $elist .= '  
                            <p style="font-size: 14px;color: #111111;font-weight: 600;margin: 0 0 5px; ">' . $experience["institution"] . '</p>
                            <p style="color: #a4a4a4;font-size: 14px;font-weight: normal;margin: 0 0 10px ">' . $experience["from_date"] . ' - ' . $experience["to_date"] . '</p>
                            <p style="font-size: 14px;color: #333333;font-weight: normal;margin: 0;">' . $experience["description"] . '</p>';
                }
                $loopcount = $loopcount + 1;
            }
        }
        $edata = '<h5 class="heading-strip">PROFESSIONAL SUMMARY</h5>
                        <div class="row">
                            <div class="career page-pad">
                            ' . $elist . '
                            </div>
                        </div>';
        return $edata;
    }

    //GENERAL INFORMATION
    public function generalSection()
    {
        $totalCount = count($this->_data["user_languages"]);
        if ($totalCount > 0) {
            $loopcount = $totalCount / 3;
            $languageData = "";
            for ($i = 0; $i < $loopcount; $i++) {
                $offset = $i * 3;
                $resultArray = array_slice($this->_data["user_languages"], $offset, 3);
                $status=($i==0)?true:false;
                $languageData .= $this->languageTop($resultArray,$status);
                $languageData .= $this->languageBottom($resultArray);
            }
        }

        $gdata = '<h5 class="heading-strip">GENERAL INFORMATION</h5>
                        <div class="row">
                            <div class="professional page-pad">
                                <table>
                                    <tbody>
                                    '.$languageData.'
                                        <tr>
                                            <td style="font-size: 14px;color: #333333;font-weight: bold;margin: 0;text-transform: uppercase; ">Location</td>                                    
                                            <td style=" font-size: 15px;color: #333333;font-weight: 400;line-height: 24px;margin: 0; " colspan="3">' . $this->_data["user_summary"]["location"] . '</td>
                                         </tr>
                            <tr>
                                <td style="font-size: 14px;color: #333333;font-weight: bold;margin: 0;text-transform: uppercase; ">Hobbies</td>                                    
                                <td style=" font-size: 15px;color: #333333;font-weight: 400;line-height: 24px;margin: 0; " colspan="3">' . $this->_data["user_summary"]["hobbies"] . '</td>
                            </tr>
                                    </tbody>
                                 </table>
                            </div>
                        </div>';
        return $gdata;
    }

     

    public function languageTop($list, $statu = FALSE)
    {
//        echo "<pre>";
//        print_r($list);exit;
        $l1 = (isset($list[0])) ? $list[0]["language"] : "";
        $l2 = (isset($list[1])) ? $list[1]["language"] : "";
        $l3 = (isset($list[2])) ? $list[2]["language"] : "";
        $ltext = ($statu) ? "Language" : "";
        $lhtml = ' 
                    <tr>
                        <td style="font-size: 14px;color: #333333;font-weight: bold;margin: 0;text-transform: uppercase; ">' . $ltext . '</td>
                        <td style=" font-size: 16px;color: #333333;font-weight: 600;margin: 0; text-align: center; ">' . $l1 . '</td>
                        <td style="width: 197.467px; font-size: 16px;color: #333333;font-weight: 600;margin: 0; text-align: center; ">' . $l2 . '</td>
                        <td style=" font-size: 16px;color: #333333;font-weight: 600;margin: 0; text-align: center; ">' . $l3 . '</td>
                    </tr>
                
                 ';
        return $lhtml;
    }

    public function languageBottom($list)
    {
        $firstPart='<td style="width: 25%; ">';
        $endPart='</td>';
        $result="";
        
        if(count($list) >0){
            foreach($list as $l)
            {
                $result.=$firstPart;
                $result.=$this->languageBottomBox($l);
                $result.=$endPart;
            }    
        }
        $lhtml = '<tr>
                    <td style="width: 25%; ">&nbsp;</td>
                   '.$result.'
                </tr>
                 ';
        return $lhtml;
    }

    public function languageBottomBox($list)
    {
        $firstPart='<td style="width: 30%;font-size: 14px;color: #111111;font-weight: normal;margin: 0;background: #ebebeb; ">';
        $endPart='</td>';
        $canRead=($list["can_read"]=="1")?$firstPart."Read".$endPart:"";
        $canWrite=($list["can_write"]=="1")?$firstPart."Write".$endPart:"";
        $canSpeak=($list["can_speak"]=="1")?$firstPart."Speak".$endPart:"";
        $lhtml = '<table style=" text-align: center; width: 100%; ">
                    <tbody>
                        <tr>
                            '.$canRead.$canWrite.$canSpeak.'
                        </tr>
                    </tbody>
                  </table>
                 ';
        return $lhtml;
    }

    public function main()
    {
        
        $content = $this->headSection();
        $content .= $this->skillSection();
        $content .= $this->careerSection();
        $content .= $this->professionalSection();
        $content .= $this->eductationSection();
        $content .= $this->generalSection();
        return $this->mainTemplate($content);
    }




    public function mainTemplate($content)
    {
        $html = "";
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
                border: 1px solid #222;
                padding: 5px 20px 5px 10px;
            }

        </style>
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <div class="page-content">
                          
                        ' . $content . '
                    </div>
                </div>
            </div>
        </div>
    </body>   
</html>

';
        return $html;
    }

}
