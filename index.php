<?php
require_once 'Config.php';
require_once 'user.php';
$user = new User();

if(isset($accessToken)){
    $gitUser = $gitClient->apiRequest($accessToken);
    
    if(!empty($gitUser)){
        $gitUserData = array();
        $gitUserData['oauth_provider'] = 'github';
        $gitUserData['oauth_uid'] = !empty($gitUser->id)?$gitUser->id:'';
        $gitUserData['name'] = !empty($gitUser->name)?$gitUser->name:'';
        $gitUserData['username'] = !empty($gitUser->login)?$gitUser->login:'';
        $gitUserData['email'] = !empty($gitUser->email)?$gitUser->email:'';
        $gitUserData['location'] = !empty($gitUser->location)?$gitUser->location:'';
        $gitUserData['link'] = !empty($gitUser->html_url)?$gitUser->html_url:'';
        
        $userData = $user->checkUser($gitUserData);
        
        $_SESSION['userData'] = $userData;
        
        $output  = '<h2>Github Profile Details</h2>';
        $output .= '<p>ID: '.$userData['oauth_uid'].'</p>';
        $output .= '<p>Name: '.$userData['name'].'</p>';
        $output .= '<p>Login Username: '.$userData['username'].'</p>';
        $output .= '<p>Email: '.$userData['email'].'</p>';
        $output .= '<p>Location: '.$userData['location'].'</p>';
        $output .= '<p>Logout from <a href="logout.php">GitHub</a></p>';
    }else{
        $output = '<h3 style="color:red">Some problem occurred, please try again.</h3>';
    }
    
}elseif(isset($_GET['code'])){
    if(!$_GET['state'] || $_SESSION['state'] != $_GET['state']) {
        header("Location: ".$_SERVER['PHP_SELF']);
    }
    
    $accessToken = $gitClient->getAccessToken($_GET['state'], $_GET['code']);
  
    $_SESSION['access_token'] = $accessToken;
  
    header('Location: ./');
}else{
    $_SESSION['state'] = hash('sha256', microtime(TRUE) . rand() . $_SERVER['REMOTE_ADDR']);
    
    unset($_SESSION['access_token']);
  
    $loginURL = $gitClient->getAuthorizeURL($_SESSION['state']);
    
    $output = '<a href="'.htmlspecialchars($loginURL).'"><img src="images/github-login.jpeg"></a>';
}
?>

<!DOCTYPE html>
<html lang="en-US">
<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<title>Login with GitHub</title>
<meta charset="utf-8">
</head>
<body>
<div class="container">
    <div class="wrapper"><?php echo $output; ?></div>
</div>
</body>
</html>

<script>

$( document ).ready(function() {
    $.ajax({
        type: "GET",
        url: "https://api.github.com/users/sankeerthvodela/repos",
        dataType: "json",
        success: function(result) {
            console.log(result);
        }
    });

});


</script>
