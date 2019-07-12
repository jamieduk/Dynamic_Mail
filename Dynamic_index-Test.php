<?php 
//require("tracker.php");
//require_once('auth.php');
include_once("../../php_includes/check_login_status.php");
include_once("../../headerb.php");
?>
<!DOCTYPE html>
<html lang="en"><head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8"><title>Mail</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="css/w3.css">
<link href="css/css.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<style>
html,body,h1,h2,h3,h4,h5 {font-family: "RobotoDraft", "Roboto", sans-serif}
.w3-bar-block .w3-bar-item {padding: 16px}
</style>

</head>
<?php
$u="";
$mail="";
if(isset($_GET["x"])){
$u=preg_replace('#[^a-z_0-9]#i', '', $_GET['x']);
} else {
$u=$_SESSION["username"];
}
$sql="SELECT id FROM users WHERE username='$u' LIMIT 1";
$user_query=mysqli_query($db_conx, $sql);
$numrows=mysqli_num_rows($user_query);
if($numrows < 1){
header("location: index.php");
exit();	
}
$isOwner="no";
if($u == $log_username && $user_ok == true){
$isOwner="yes";}
if($isOwner != "yes"){
header("location: index.php");
exit();
}
$sql="SELECT id, receiver, sender, subject, message, senttime, rread, sread FROM pm WHERE 
(receiver='$u' AND parent='x' AND rdelete='0') 
OR 
(sender='$u' AND sdelete='0' AND parent='x' AND hasreplies='1') 
ORDER BY senttime DESC";
$query=mysqli_query($db_conx, $sql);
$statusnumrows=mysqli_num_rows($query);
if($statusnumrows > 0){
	while($row=mysqli_fetch_array($query, MYSQLI_ASSOC)) {
$pmid=$row["id"]; // PM ID for message id.
$pmid2='pm_'.$pmid; // reply id
$wrap='pm_wrap_'.$pmid; // not sure!
$btid2='bt_'.$pmid; // no idea!
$rt='replytext_'.$pmid; // reply text
$rb='replyBtn_'.$pmid; // reply button
$receiver=$row["receiver"]; // reciever
$sender=$row["sender"]; // sender from   <<<<
$subject=$row["subject"]; //subject      <<<<
$message=$row["message"]; // message     <<<<
$time=$row["senttime"]; // Time/Date
$rread=$row["rread"]; // reciever read status
$sread=$row["sread"]; // sender read status!

 // get avatar and flag
$sql="SELECT avatar, country FROM users WHERE username='$sender'";
$query=mysqli_query($db_conx, $sql);
$numrows=mysqli_num_rows($query)
or die("Error: ".mysqli_error($db_conx));
if($numrows < 1){
$message="Sorry Theres A Problem";
} else {
while($row=mysqli_fetch_array($query, MYSQLI_ASSOC)) {
$avatar=$row["avatar"];
$country=$row["country"];
$real_av="/user/$sender/$avatar";


$mail .= '<nav class="w3-sidebar w3-bar-block w3-collapse w3-white w3-animate-left w3-card" style="z-index:3;width:320px;" id="mySidebar">
  <a href="javascript:void(0)" class="w3-bar-item w3-button w3-border-bottom w3-large"><img src="/images/logojnet.png" style="width:60%;"></a>
  <a href="javascript:void(0)" onclick="w3_close()" title="Close Sidemenu" class="w3-bar-item w3-button w3-hide-large w3-large">Close <i class="fa fa-remove"></i></a>
  <a href="javascript:void(0)" class="w3-bar-item w3-button w3-dark-grey w3-button w3-hover-black w3-left-align" onclick="document.getElementById(\'id01\').style.display=\'block\'">New Message <i class="w3-padding fa fa-pencil"></i></a>
  <a id="myBtn" onclick="myFunc(\'Demo1\')" href="javascript:void(0)" class="w3-bar-item w3-button w3-red"><i class="fa fa-inbox w3-margin-right"></i>Inbox ('."$statusnumrows".')<i class="fa fa-caret-down w3-margin-left"></i></a>
  <div id="Demo1" class="w3-hide w3-animate-left w3-show">
    <a href="javascript:void(0)" class="w3-bar-item w3-button w3-border-bottom test w3-hover-light-grey" onclick="openMail(\'Borge\');w3_close();" id="firstTab">
      <div class="w3-container">
        <img class="w3-round w3-margin-right" src="'.$real_av.'" style="width:15%;"><span class="w3-opacity w3-large"> '."$sender".'</span>
        <h6>Subject: '."$subject".'</h6>
      </div>
    </a>

   
  
  </div>
 
</nav>';
}
}
$main_out='';
$main_out .='<div id="'.$sender.'" class="w3-container person" style="display: block;">
  <br>
  <img class="w3-round  w3-animate-top" src="'.$real_av.'" style="width:20%;">
  <h5 class="w3-opacity">'."$sender".' <p> Subject: '."$subject".'</h5>
  <h4><i class="fa fa-clock-o"></i>'."$time".'.</h4>
  <a class="w3-button w3-light-grey" href="#">Reply<i class="w3-margin-left fa fa-mail-reply"></i></a>
  <a class="w3-button w3-light-grey" href="#">Forward<i class="w3-margin-left fa fa-arrow-right"></i></a>
  <hr>
  <p>'."$message".'</p>
</div>';

// Now for replies!
$pm_replies="";
$query_replies=mysqli_query($db_conx, "SELECT sender, message, senttime FROM pm WHERE parent='$pmid' ORDER BY senttime ASC");
$replynumrows=mysqli_num_rows($query_replies);
    	if($replynumrows > 0){
	while($row2=mysqli_fetch_array($query_replies, MYSQLI_ASSOC)) {
$rsender=$row2["sender"];
$reply=$row2["message"];
$time2=$row2["senttime"];
$country=$row["country"]; 
$avatar=$row["avatar"]; 
 $sql="SELECT avatar FROM users WHERE username='$rsender'";
$query=mysqli_query($db_conx, $sql);
$numrows=mysqli_num_rows($query)
or die("Error: ".mysqli_error($db_conx));
if($numrows < 1){
$message="Sorry Theres A Problem";
} else {
while ($row=mysqli_fetch_array($query, MYSQLI_ASSOC)) {
$avatar=$row["avatar"];
$country=$row["country"];
}
}
$mail_replies .= '<div class ="pm_post"><hr>Reply From: <a href="'.$rsender.'">'.$rsender.'</a><p><img src=/user/'.$rsender.'/'.$avatar.' width="75" height="70"> <br /> on '.$time2.'<p>'.$reply.'<br /></div>';
	}
}
$mail_replies .= '</div>';
$mail_replies .= '<textarea id="'.$rt.'" placeholder="Quick Reply here..."1" cols="50" rows="5"></textarea><br />';
$mail_replies .= '<button id="'.$rb.'" onclick="replyToPm('.$pmid.',\''.$u.'\',\''.$rt.'\',\''.$rb.'\',\''.$sender.'\')">Reply</button>';
$mail_replies .= '</div>';
	}

}
?>


<body>

<!-- Side Navigation -->

<?php echo "$mail";?>

<!-- Modal that pops up when you click on "New Message" -->
<div id="id01" class="w3-modal" style="z-index:4">
  <div class="w3-modal-content w3-animate-zoom">
    <div class="w3-container w3-padding w3-red">
       <span onclick="document.getElementById('id01').style.display='none'" class="w3-button w3-red w3-right w3-xxlarge"><i class="fa fa-remove"></i></span>
      <h2>Send Mail</h2>
    </div>
    <div class="w3-panel">
      <label>To</label>
      <input class="w3-input w3-border w3-margin-bottom" type="text">
      <label>From</label>
      <input class="w3-input w3-border w3-margin-bottom" type="text">
      <label>Subject</label>
      <input class="w3-input w3-border w3-margin-bottom" type="text">
      <input class="w3-input w3-border w3-margin-bottom" style="height:150px" placeholder="What's on your mind?">
      <div class="w3-section">
        <a class="w3-button w3-red" onclick="document.getElementById('id01').style.display='none'">Cancel &nbsp;<i class="fa fa-remove"></i></a>
        <a class="w3-button w3-light-grey w3-right" onclick="document.getElementById('id01').style.display='none'">Send &nbsp;<i class="fa fa-paper-plane"></i></a> 
      </div>    
    </div>
  </div>
</div>

<!-- Overlay effect when opening the side navigation on small screens -->
<div class="w3-overlay w3-hide-large w3-animate-opacity" onclick="w3_close()" style="cursor:pointer" title="Close Sidemenu" id="myOverlay"></div>

<!-- Page content -->
<div class="w3-main" style="margin-left:320px;">
<i class="fa fa-bars w3-button w3-white w3-hide-large w3-xlarge w3-margin-left w3-margin-top" onclick="w3_open()"></i>
<a href="javascript:void(0)" class="w3-hide-large w3-red w3-button w3-right w3-margin-top w3-margin-right" onclick="document.getElementById('id01').style.display='block'"><i class="fa fa-pencil"></i></a>

<?php echo $main_out;?>
     

</div>

<script>
var openInbox=document.getElementById("myBtn");
openInbox.click();

function w3_open() {
  document.getElementById("mySidebar").style.display="block";
  document.getElementById("myOverlay").style.display="block";
}

function w3_close() {
  document.getElementById("mySidebar").style.display="none";
  document.getElementById("myOverlay").style.display="none";
}

function myFunc(id) {
  var x=document.getElementById(id);
  if (x.className.indexOf("w3-show") == -1) {
    x.className += " w3-show"; 
    x.previousElementSibling.className += " w3-red";
  } else { 
    x.className=x.className.replace(" w3-show", "");
    x.previousElementSibling.className=
    x.previousElementSibling.className.replace(" w3-red", "");
  }
}
var val="<?php echo $sender?>";
openMail(val)
function openMail(personName) {
  var i;
  var x=document.getElementsByClassName("person");
  for (i=0; i < x.length; i++) {
    x[i].style.display="block";
  }
  x=document.getElementsByClassName("test");
  for (i=0; i < x.length; i++) {
    x[i].className=x[i].className.replace(" w3-light-grey", "");
  }
  document.getElementById(personName).style.display="block";
  event.currentTarget.className += " w3-light-grey";
}
</script>

<script>
var openTab=document.getElementById("firstTab");
openTab.click();
</script>


 </body></html>
