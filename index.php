<?php
session_start();

// Use session to store username
if(isset($_POST['username'])){
	$_SESSION['username']=$_POST['username'];
}

// Unset session when logout
if(isset($_GET['logout'])){
	unset($_SESSION['username']);
	header('Location:index.php');
}

?>

<html>
<head>
	<title>Simple Chat Room</title>
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,400,300' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="css/style.css" />
	<script type="text/javascript" src="js/jquery-1.10.2.min.js" ></script>
</head>

<body>
    <div class='header'>
        <h1>
            Chat Room
            <?php // Logout link  ?>
            <?php if(isset($_SESSION['username'])) { ?>
                <a class='logout' href="?logout">Logout</a>
            <?php } ?>
        </h1>
    </div>

<div class='main'>
<?php //Check if the user is logged in or not ?>
<?php if(isset($_SESSION['username'])) { ?>
    <div id='result'></div>

<div class='chatcontrols'>
	<form method="post" onsubmit="return submitchat();">
        <input type='text' name='chat' id='chatbox' autocomplete="off" placeholder="ENTER CHAT HERE" />
        <input type='submit' name='send' id='send' class='btn btn-send' value='Send' />
        <input type='button' name='clear' class='btn btn-clear' id='clear' value='X' title="Clear Chat" />
    </form>

<script>
    // Submit Messages
    function submitchat(){
        if($('#chat').val()=='' || $('#chatbox').val()==' ') return false;
        $.ajax({
            url:'chat.php',
            data:{chat:$('#chatbox').val(),ajaxsend:true},
            method:'post',
            success:function(data){
                $('#result').html(data); // Get the chat records and add it to result div
                $('#chatbox').val(''); //Clear chat box after successful submition
                document.getElementById('result').scrollTop=document.getElementById('result').scrollHeight; // Bring the scrollbar to bottom of the chat resultbox in case of long chatbox
            }
        })
        return false;
    };

    // Refresh Chat Message
    setInterval(function(){
        $.ajax({
                url:'chat.php',
                data:{ajaxget:true},
                method:'post',
                success:function(data){
                    $('#result').html(data);
                }
        })
    },1000);

    // Clear Chat
    $(document).ready(function(){
        $('#clear').click(function(){
            if(!confirm('Are you sure you want to clear current chat?'))
                return false;
            $.ajax({
                url:'chat.php',
                data:{username:"<?php echo $_SESSION['username'] ?>",ajaxclear:true},
                method:'post',
                success:function(data){
                    $('#result').html(data);
                }
            })
        })
    })
</script>

<?php } else { ?>
<div class='userscreen'>
	<form method="post">
		<input type='text' class='input-user' placeholder="Enter your name to chat" name='username' />
		<input type='submit' class='btn btn-user' value='GO' />
	</form>
</div>
<?php } ?>

</div>
</div>
</body>
</html>
