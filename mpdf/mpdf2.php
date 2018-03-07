<?php
error_reporting(-1);
require_once 'vendor/autoload.php';
include_once 'pdftemplate.php';
$name="Mr. Anilkumar M";
$qualification="Qulification";
$email="test@gmail.com";
$mainHead=array("name"=> "test", "email"=>"t@gmail.com","qualification"=>"Qulification" );
$template["user_skills"][]=  array(
    "tag" => "design"
);
$template["user_skills"][]=  array(
    "tag" => "design2"
);
$template["user_skills"][]=  array(
    "tag" => "design3"
);
$template["user_skills"][]=  array(
    "tag" => "design4"
);
$template["user_skills"][]=  array(
    "tag" => "design5"
);
$template["user_skills"][]=  array(
    "tag" => "design56"
);
$template["user_skills"][]=  array(
    "tag" => "design57"
);
$template["user_skills"][]=  array(
    "tag" => "design578"
);
 
$template["user_summary"]=  array(
    "location" => "Rajolu, India",
    "hobbies" => "Talking nonsense, walking",
    "summary" => "This is new summary. Edited here. Edited again! And again! And again!! This is abnormally long career objective created for testing purposes. Ideally one should not have this long career objective which takes one's career to read your objective. So beware! Keep it short and simple. And make it to the point, so that readers would review your resume further to know more about you. Otherwise they will feel tired after reading the objective itself."
);
//user_work_experience
 
$template["user_work_experience"][]=  array(
    "institution"=>"Potato",
    "location"=>"Guntur, India",
    "from_date"=>"2017-12-04",
    "to_date"=>"",
    "is_currently_working"=>1,
    "description" => "This is new summary. Edited here. Edited again! And again! And again!! This is abnormally long career objective created for testing purposes. Ideally one should not have this long career objective which takes one's career to read your objective. So beware! Keep it short and simple. And make it to the point, so that readers would review your resume further to know more about you. Otherwise they will feel tired after reading the objective itself."
);
$template["user_work_experience"][]=  array(
    "institution"=>"Potato2",
    "location"=>"Guntur, India",
    "from_date"=>"2017-12-04",
    "to_date"=>"",
    "is_currently_working"=>1,
    "description" => "2222."
);
$template["user_work_experience"][]=  array(
    "institution"=>"Potato33",
    "location"=>"Guntur, India",
    "from_date"=>"2017-12-04",
    "to_date"=>"",
    "is_currently_working"=>1,
    "description" => "2222333333."
);


 
$template["user_languages"][]=  array(
    "language"=>"Potato33",
    "can_read" => 1,
    "can_write" => 0,
    "can_speak" => 0
                   
);
$template["user_languages"][]=  array(
    "language"=>"telugu",
    "can_read" => 1,
    "can_write" => 1,
    "can_speak" => 1
                   
);
$template["user_languages"][]=  array(
    "language"=>"english",
    "can_read" => 1,
    "can_write" => 0,
    "can_speak" => 1
                   
);
$template["user_languages"][]=  array(
    "language"=>"english3",
    "can_read" => 1,
    "can_write" => 0,
    "can_speak" => 0
                   
);

$pdfTemplate =new pdfTemplate($mainHead,$template);
$processTemplate=$pdfTemplate->main();
$mpdf = new mPDF();
$mpdf->showImageErrors = true;
$mpdf->WriteHTML($processTemplate);
$mpdf->SetDisplayMode('fullpage');

$mpdf->Output();

//echo $processTemplate;exit;